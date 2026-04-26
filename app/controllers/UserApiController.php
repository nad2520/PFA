<?php
declare(strict_types=1);

require_once CORE_PATH . '/Controller.php';
require_once CORE_PATH . '/Database.php';
require_once APP_PATH  . '/models/UserModel.php';
require_once APP_PATH  . '/models/BookModel.php';
require_once APP_PATH  . '/models/QuestModel.php';
require_once APP_PATH  . '/services/QuestService.php';

/**
 * UserApiController
 * All endpoints return JSON. Used by the frontend JS (read_book_app.js, profile_app.js, store_app.js).
 */
class UserApiController extends Controller
{
    /** Cache schema check within request lifecycle. */
    private static ?bool $hasUserBooksPurchasedAt = null;

    private function userBooksHasPurchasedAt(PDO $pdo): bool
    {
        if (self::$hasUserBooksPurchasedAt !== null) {
            return self::$hasUserBooksPurchasedAt;
        }
        try {
            $q = $pdo->prepare(
                "SELECT 1
                 FROM information_schema.COLUMNS
                 WHERE TABLE_SCHEMA = DATABASE()
                   AND TABLE_NAME = 'user_books'
                   AND COLUMN_NAME = 'purchased_at'
                 LIMIT 1"
            );
            $q->execute();
            self::$hasUserBooksPurchasedAt = (bool)$q->fetchColumn();
        } catch (\Throwable) {
            self::$hasUserBooksPurchasedAt = false;
        }

        return self::$hasUserBooksPurchasedAt;
    }

    // ── GET /api/user/profile ─────────────────────────────────────────────────
    public function profile(): void
    {
        $this->requireAuth();
        $userId = (int)$_SESSION['user_id'];
        $row    = UserModel::findById($userId);

        if (!$row) {
            $this->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        $level     = max(1, (int)$row['level']);
        $xp        = (int)$row['xp'];
        $xpForNext = $level * 500;

        $lumoState = UserModel::computeLumoState($row);
        $reading   = UserModel::getReadingTimeAggregates($userId);

        // Never cache: browsers could reuse another user's JSON after login / signup.
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Pragma: no-cache');

        $this->json([
            'success' => true,
            'data' => [
                'id'          => $userId,
                'name'        => $row['nom'],
                'coins'       => (int)$row['coins'],
                'level'       => $level,
                'xp'          => $xp,
                'xpForNext'   => $xpForNext,
                'levelPct'    => $xpForNext > 0 ? min(100, (int)round(($xp % $xpForNext) / $xpForNext * 100)) : 0,
                'streakDays'  => (int)($row['streak_days'] ?? 0),
                'booksRead'   => UserModel::countBooksRead($userId),
                'lumoState'   => $lumoState,
                'lumoMessage' => match ($lumoState) {
                    'happy'   => 'Lumo is happy! Keep reading to maintain your streak. 🌟',
                    'dim'     => "Lumo misses you! You haven't read in over 24 hours.",
                    'worried' => "Lumo is worried… It's been more than 3 days since your last read!",
                    default   => '',
                },
                'library'     => UserModel::getUserBooks($userId),
                'backToLecture' => UserModel::getBackToLecture($userId),
                'totalReadingMinutes'   => $reading['totalReadingMinutes'],
                'totalReadingHours'     => $reading['totalReadingHours'],
                'averageReadingMinutes' => $reading['averageReadingMinutes'],
                'averageReadingHours'   => $reading['averageReadingHours'],
                'dailyReadingGoalHours' => $reading['dailyReadingGoalHours'],
            ],
        ]);
    }

    // ── POST /api/user/reading-session ────────────────────────────────────────
    public function logSession(): void
    {
        $this->requireAuth();
        $body = $this->jsonBody();

        $userId      = (int)$_SESSION['user_id'];
        $bookId      = (int)($body['book_id'] ?? 0);
        $pagesRead   = (int)($body['pages_read'] ?? 0);
        $minutesRead = (int)($body['minutes_read'] ?? 0);

        if ($bookId <= 0) {
            $this->json(['success' => false, 'message' => 'book_id required.'], 400);
        }

        if (!UserModel::userHasReadableAccess($userId, $bookId)) {
            $this->json([
                'success' => false,
                'error' => 'ACCESS_DENIED',
                'message' => 'Unlock this book from the book page before logging reading time.',
            ], 403);
        }

        $pdo   = Database::pdo();
        $today = date('Y-m-d');

        $stmt = $pdo->prepare(
            'INSERT INTO reading_sessions (user_id, book_id, session_date, pages_read, minutes_read)
             VALUES (?, ?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE
               pages_read   = pages_read   + VALUES(pages_read),
               minutes_read = minutes_read + VALUES(minutes_read)'
        );
        $stmt->execute([$userId, $bookId, $today, $pagesRead, $minutesRead]);

        UserModel::updateAfterReading($userId, $minutesRead);
        $milestoneCoins = UserModel::applyPageMilestoneRewards($userId, $bookId);
        QuestService::onPagesRead($userId, max(0, $pagesRead));
        $user = UserModel::findById($userId);

        $this->json([
            'success' => true,
            'message' => 'Session logged.',
            'coinRewardEarned' => $milestoneCoins,
            'newCoins' => (int)($user['coins'] ?? 0),
        ]);
    }

    // ── POST /api/user/book/purchase ──────────────────────────────────────────
    public function purchaseBook(): void
    {
        $this->requireAuth();
        if (!$this->verifyCsrfHeader()) {
            $this->json(['success' => false, 'message' => 'Invalid CSRF token.'], 403);
        }
        $body   = $this->jsonBody();
        $userId = (int)$_SESSION['user_id'];
        $bookId = (int)($body['book_id'] ?? 0);

        if ($bookId <= 0) {
            $this->json(['success' => false, 'message' => 'book_id required.'], 400);
        }

        $book = BookModel::findById($bookId);
        if (!$book) {
            $this->json(['success' => false, 'message' => 'Book not found.'], 404);
        }

        if (!UserModel::findById($userId)) {
            $this->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        $pdo = null;
        try {
            $pdo = Database::pdo();
            $pdo->beginTransaction();
            $hasPurchasedAt = $this->userBooksHasPurchasedAt($pdo);

            $selectSql = $hasPurchasedAt
                ? 'SELECT id, status, purchased_at FROM user_books WHERE user_id = ? AND book_id = ? FOR UPDATE'
                : 'SELECT id, status, NULL AS purchased_at FROM user_books WHERE user_id = ? AND book_id = ? FOR UPDATE';
            $chk = $pdo->prepare($selectSql);
            $chk->execute([$userId, $bookId]);
            $existing = $chk->fetch(PDO::FETCH_ASSOC);

            $st = (string)($existing['status'] ?? '');
            $paidRecorded = !empty($existing['purchased_at']);
            $alreadyOwned = $existing && (
                in_array($st, ['reading', 'completed'], true) || $paidRecorded
            );

            if ($alreadyOwned) {
                $pdo->commit();
                $fresh = UserModel::findById($userId);
                $this->json([
                    'success' => true,
                    'already_in_library' => true,
                    'newCoins' => (int)($fresh['coins'] ?? 0),
                    'message' => 'Book already in your library.',
                ]);

                return;
            }

            // ── Coin deduction (only for first-time purchase) ──────────────────────
            $coinCost = (int)($book['coinCost'] ?? 0);
            $coinsSpent = 0;

            if ($coinCost > 0) {
                // Lock the user row to read the current balance safely.
                $userLock = $pdo->prepare('SELECT coins FROM users WHERE id = ? FOR UPDATE');
                $userLock->execute([$userId]);
                $userRow = $userLock->fetch(PDO::FETCH_ASSOC);
                $currentCoins = (int)($userRow['coins'] ?? 0);

                if ($currentCoins < $coinCost) {
                    $pdo->rollBack();
                    $this->json([
                        'success'  => false,
                        'error'    => 'NOT_ENOUGH_COINS',
                        'message'  => UserModel::MSG_INSUFFICIENT_COINS,
                    ], 402);
                    return;
                }

                // Deduct coins atomically.
                $pdo->prepare('UPDATE users SET coins = coins - ? WHERE id = ? AND coins >= ?')
                    ->execute([$coinCost, $userId, $coinCost]);

                // Audit log.
                $pdo->prepare(
                    'INSERT INTO economy_logs (user_id, log_date, coins_earned, coins_spent, event_type)
                     VALUES (?, CURDATE(), 0, ?, ?)'
                )->execute([$userId, $coinCost, 'book_purchase']);

                $coinsSpent = $coinCost;
            }

            if ($hasPurchasedAt) {
                $ins = $pdo->prepare(
                    'INSERT INTO user_books (user_id, book_id, status, progress_page, started_at, purchased_at)
                     VALUES (?, ?, ?, 0, NOW(), NOW())
                     ON DUPLICATE KEY UPDATE
                       status = CASE
                         WHEN user_books.status = "completed" THEN user_books.status
                         ELSE "reading"
                       END,
                       started_at = IFNULL(user_books.started_at, NOW()),
                       purchased_at = COALESCE(user_books.purchased_at, NOW())'
                );
                $ins->execute([$userId, $bookId, 'reading']);
            } else {
                $ins = $pdo->prepare(
                    'INSERT INTO user_books (user_id, book_id, status, progress_page, started_at)
                     VALUES (?, ?, ?, 0, NOW())
                     ON DUPLICATE KEY UPDATE
                       status = CASE
                         WHEN user_books.status = "completed" THEN user_books.status
                         ELSE "reading"
                       END,
                       started_at = IFNULL(user_books.started_at, NOW())'
                );
                $ins->execute([$userId, $bookId, 'reading']);
            }

            $pdo->commit();
        } catch (\Throwable $e) {
            try {
                if ($pdo instanceof \PDO && $pdo->inTransaction()) {
                    $pdo->rollBack();
                }
            } catch (\Throwable) {
                // ignore rollback failures
            }
            $this->json([
                'success' => false,
                'message' => 'Purchase failed. Run database/migrations/008_user_books_purchased_at.sql if `purchased_at` is missing, and ensure `user_books` matches 001_book_completion_lexora.sql.',
            ], 500);

            return;
        }

        $fresh = UserModel::findById($userId);
        $this->json([
            'success'    => true,
            'message'    => $coinsSpent > 0
                ? "Book purchased for {$coinsSpent} coins!"
                : 'Book added to your library!',
            'coinsSpent' => $coinsSpent,
            'newCoins'   => (int)($fresh['coins'] ?? 0),
        ]);
    }

    // ── POST /api/user/book/complete ──────────────────────────────────────────
    public function completeBook(): void
    {
        $this->requireAuth();
        if (!$this->verifyCsrfHeader()) {
            $this->json(['success' => false, 'message' => 'Invalid CSRF token.'], 403);
        }
        $body   = $this->jsonBody();
        $userId = (int)$_SESSION['user_id'];
        $bookId = (int)($body['book_id'] ?? 0);
        $rating = array_key_exists('rating', $body) ? (int)$body['rating'] : null;

        if ($bookId <= 0) {
            $this->json(['success' => false, 'message' => 'book_id required.'], 400);
        }

        $book = BookModel::findById($bookId);
        if (!$book) {
            $this->json(['success' => false, 'message' => 'Book not found.'], 404);
        }

        $xpReward   = (int)$book['xpReward'];
        $coinReward = (int)$book['coinReward'];

        $pdo = Database::pdo();
        $pdo->beginTransaction();
        try {
            $sel = $pdo->prepare(
                'SELECT id, status FROM user_books WHERE user_id = ? AND book_id = ? FOR UPDATE'
            );
            $sel->execute([$userId, $bookId]);
            $ub = $sel->fetch(PDO::FETCH_ASSOC);

            if (!$ub) {
                $pdo->rollBack();
                $this->json([
                    'success' => false,
                    'error' => 'ACCESS_DENIED',
                    'message' => 'Book is not in your library. Unlock it before completing.',
                ], 403);
            }

            if (($ub['status'] ?? '') === 'plan_to_read') {
                $pdo->rollBack();
                $this->json([
                    'success' => false,
                    'error' => 'ACCESS_DENIED',
                    'message' => 'Start reading (unlock) this book before completing.',
                ], 403);
            }

            if (($ub['status'] ?? '') === 'completed') {
                if ($rating !== null && $rating >= 1 && $rating <= 5) {
                    $pdo->prepare(
                        'UPDATE user_books SET rating = ? WHERE user_id = ? AND book_id = ?'
                    )->execute([$rating, $userId, $bookId]);
                }
                $pdo->commit();

                $user = UserModel::findById($userId);
                $this->json([
                    'success'           => true,
                    'alreadyCompleted'  => true,
                    'message'           => 'This book was already marked complete.',
                    'xpEarned'          => 0,
                    'coinsEarned'       => 0,
                    'newCoins'          => (int)($user['coins'] ?? 0),
                    'newLevel'          => max(1, (int)($user['level'] ?? 1)),
                    'newXp'             => (int)($user['xp'] ?? 0),
                    'booksRead'         => UserModel::countBooksRead($userId),
                ]);
            }

            if ($rating === null || $rating < 1 || $rating > 5) {
                $pdo->rollBack();
                $this->json(['success' => false, 'message' => 'Please choose a rating from 1 to 5.'], 400);
            }

            $pdo->prepare(
                'UPDATE user_books
                 SET status = "completed", completed_at = NOW(), rating = ?
                 WHERE user_id = ? AND book_id = ?'
            )->execute([$rating, $userId, $bookId]);

            $pdo->prepare(
                'UPDATE users SET xp = xp + ?, coins = coins + ? WHERE id = ?'
            )->execute([$xpReward, $coinReward, $userId]);

            $pdo->prepare(
                'UPDATE users SET level = GREATEST(1, FLOOR(xp / 500) + 1) WHERE id = ?'
            )->execute([$userId]);

            $pdo->prepare(
                'INSERT INTO economy_logs (user_id, log_date, coins_earned, event_type) VALUES (?, CURDATE(), ?, ?)'
            )->execute([$userId, $coinReward, 'book_completion']);

            $pdo->commit();
        } catch (\Throwable $e) {
            $pdo->rollBack();
            $this->json(['success' => false, 'message' => 'Could not save completion.'], 500);
        }

        QuestService::onBookCompleted($userId, 1);

        $user = UserModel::findById($userId);
        $this->json([
            'success'     => true,
            'message'     => 'Book marked as completed!',
            'xpEarned'    => $xpReward,
            'coinsEarned' => $coinReward,
            'newCoins'    => (int)($user['coins'] ?? 0),
            'newLevel'    => max(1, (int)($user['level'] ?? 1)),
            'newXp'       => (int)($user['xp'] ?? 0),
            'booksRead'   => UserModel::countBooksRead($userId),
        ]);
    }

    // ── POST /api/user/book/rating (completed books only) ────────────────────
    public function updateRating(): void
    {
        $this->requireAuth();
        if (!$this->verifyCsrfHeader()) {
            $this->json(['success' => false, 'message' => 'Invalid CSRF token.'], 403);
        }
        $body   = $this->jsonBody();
        $userId = (int)$_SESSION['user_id'];
        $bookId = (int)($body['book_id'] ?? 0);
        $rating = (int)($body['rating'] ?? 0);

        if ($bookId <= 0 || $rating < 1 || $rating > 5) {
            $this->json(['success' => false, 'message' => 'book_id and rating (1–5) required.'], 400);
        }

        $pdo = Database::pdo();
        $stmt = $pdo->prepare(
            'UPDATE user_books SET rating = ? WHERE user_id = ? AND book_id = ? AND status = "completed"'
        );
        $stmt->execute([$rating, $userId, $bookId]);
        if ($stmt->rowCount() === 0) {
            $this->json(['success' => false, 'message' => 'Book is not completed or not in your library.'], 404);
        }

        $this->json(['success' => true, 'message' => 'Rating updated.']);
    }

    // ── POST /api/user/book/progress ──────────────────────────────────────────
    public function saveProgress(): void
    {
        $this->requireAuth();
        if (!$this->verifyCsrfHeader()) {
            $this->json(['success' => false, 'message' => 'Invalid CSRF token.'], 403);
        }
        $body   = $this->jsonBody();
        $userId = (int)$_SESSION['user_id'];
        $bookId = (int)($body['book_id'] ?? 0);
        $page   = (int)($body['page'] ?? 0);

        if ($bookId <= 0) {
            $this->json(['success' => false, 'message' => 'book_id required.'], 400);
        }

        $book = BookModel::findById($bookId);
        if (!$book) {
            $this->json(['success' => false, 'message' => 'Book not found.'], 404);
        }

        if (!UserModel::userHasReadableAccess($userId, $bookId)) {
            $this->json([
                'success' => false,
                'error' => 'ACCESS_DENIED',
                'message' => 'Unlock this book from the book page (Start Reading) before saving progress.',
            ], 403);
        }

        $ok = UserModel::saveReadingProgress($userId, $bookId, $page);
        if (!$ok) {
            $this->json(['success' => false, 'message' => 'Could not save progress.'], 500);
        }

        $this->json([
            'success' => true,
            'book_id' => $bookId,
            'saved_page' => max(0, $page),
        ]);
    }

    // ── POST /api/user/book/list/add ───────────────────────────────────────────
    public function addBookToList(): void
    {
        $this->requireAuth();
        if (!$this->verifyCsrfHeader()) {
            $this->json(['success' => false, 'message' => 'Invalid CSRF token.'], 403);
        }
        $body   = $this->jsonBody();
        $userId = (int)$_SESSION['user_id'];
        $bookId = (int)($body['book_id'] ?? 0);

        if ($bookId <= 0) {
            $this->json(['success' => false, 'message' => 'book_id required.'], 400);
        }
        $book = BookModel::findById($bookId);
        if (!$book) {
            $this->json(['success' => false, 'message' => 'Book not found.'], 404);
        }

        try {
            $pdo = Database::pdo();
            $chk = $pdo->prepare('SELECT status FROM user_books WHERE user_id = ? AND book_id = ? LIMIT 1');
            $chk->execute([$userId, $bookId]);
            $row = $chk->fetch(PDO::FETCH_ASSOC);
            $st = (string)($row['status'] ?? '');
        } catch (\Throwable) {
            $st = '';
        }

        if ($st === 'plan_to_read') {
            $this->json([
                'success' => true,
                'already_in_list' => true,
                'message' => 'Already in your list.',
            ]);
            return;
        }
        if (in_array($st, ['reading', 'completed'], true)) {
            $this->json([
                'success' => true,
                'in_library' => true,
                'message' => 'This book is already in your library.',
            ]);
            return;
        }

        $ok = UserModel::addToMyList($userId, $bookId);
        if (!$ok) {
            $this->json(['success' => false, 'message' => 'Could not add to list.'], 500);
        }
        QuestService::onBookAddedToList($userId, 1);
        $this->json([
            'success' => true,
            'already_in_list' => false,
            'message' => 'Book added to your list.',
        ]);
    }

    // ── POST /api/user/book/list/remove ────────────────────────────────────────
    public function removeBookFromList(): void
    {
        $this->requireAuth();
        if (!$this->verifyCsrfHeader()) {
            $this->json(['success' => false, 'message' => 'Invalid CSRF token.'], 403);
        }
        $body   = $this->jsonBody();
        $userId = (int)$_SESSION['user_id'];
        $bookId = (int)($body['book_id'] ?? 0);

        if ($bookId <= 0) {
            $this->json(['success' => false, 'message' => 'book_id required.'], 400);
        }
        $ok = UserModel::removeFromMyList($userId, $bookId);
        if (!$ok) {
            $this->json(['success' => false, 'message' => 'Could not remove from list.'], 500);
        }
        $this->json(['success' => true, 'message' => 'Book removed from your list.']);
    }

    // ── GET /api/user/back-to-lecture ──────────────────────────────────────────
    public function backToLecture(): void
    {
        $this->requireAuth();
        $userId = (int)$_SESSION['user_id'];
        $row = UserModel::getBackToLecture($userId);
        $this->json(['success' => true, 'data' => $row]);
    }

    // ── POST /api/user/quest/complete ─────────────────────────────────────────
    public function completeQuest(): void
    {
        $this->requireAuth();
        if (!$this->verifyCsrfHeader()) {
            $this->json(['success' => false, 'message' => 'Invalid CSRF token.'], 403);
        }
        $body   = $this->jsonBody();
        $userId = (int)$_SESSION['user_id'];
        $questKey = trim((string)($body['quest_key'] ?? 'daily_reader'));
        if ($questKey === '') {
            $this->json(['success' => false, 'message' => 'quest_key required.'], 400);
        }

        $user = UserModel::findById($userId);
        $this->json([
            'success' => true,
            'auto' => true,
            'alreadyClaimed' => true,
            'message' => 'Quest rewards are granted automatically when objectives are completed.',
            'coinsEarned' => 0,
            'xpEarned' => 0,
            'newCoins' => (int)($user['coins'] ?? 0),
            'newXp' => (int)($user['xp'] ?? 0),
            'newLevel' => max(1, (int)($user['level'] ?? 1)),
        ]);
    }

    // ── GET /api/user/quests ───────────────────────────────────────────────────
    public function getQuests(): void
    {
        $this->requireAuth();
        $userId = (int)$_SESSION['user_id'];
        $rows = QuestModel::activeForBoardForUser($userId);
        $out = array_map(static function (array $r): array {
            return [
                'id' => (int)($r['id'] ?? 0),
                'quest_key' => (string)($r['quest_key'] ?? ''),
                'title' => (string)($r['title'] ?? ''),
                'description' => (string)($r['description'] ?? ''),
                'quest_type' => (string)($r['quest_type'] ?? ''),
                'target_value' => max(1, (int)($r['target_value'] ?? 1)),
                'progress_value' => max(0, (int)($r['progress_value'] ?? 0)),
                'is_completed' => !empty($r['is_completed']),
                'coins_reward' => (int)($r['coins_reward'] ?? 0),
                'xp_reward' => (int)($r['xp_reward'] ?? 0),
            ];
        }, $rows);

        $this->json(['success' => true, 'data' => $out]);
    }

    // ── GET /api/leaderboard ──────────────────────────────────────────────────
    public function leaderboard(): void
    {
        $top = UserModel::leaderboard(10);
        $this->json(['success' => true, 'data' => $top]);
    }

    // ── GET /api/leaderboard/me ─────────────────────────────────────────────────
    public function myLeaderboard(): void
    {
        $this->requireAuth();
        $userId = (int)$_SESSION['user_id'];
        $window = UserModel::relativeLeaderboard($userId, 4, 2);
        if ($window === null) {
            $this->json(['success' => false, 'message' => 'Leaderboard not available.'], 404);
        }
        $this->json(['success' => true, 'data' => $window]);
    }

    // ── GET /api/leaderboard/search?q=... ───────────────────────────────────────
    public function searchLeaderboard(): void
    {
        $this->requireAuth();
        $q = trim((string)($_GET['q'] ?? ''));
        if ($q === '') {
            $this->json(['success' => false, 'message' => 'Search query is required.'], 400);
        }
        $targetUserId = UserModel::findLeaderboardUserIdByName($q);
        if (!$targetUserId) {
            $this->json(['success' => false, 'message' => 'User not found in leaderboard.'], 404);
        }
        $window = UserModel::relativeLeaderboard($targetUserId, 4, 2);
        if ($window === null) {
            $this->json(['success' => false, 'message' => 'Leaderboard not available.'], 404);
        }
        $this->json(['success' => true, 'data' => $window]);
    }

    // ── GET /api/user/preferences/categories ───────────────────────────────────
    public function getCategoryPreferences(): void
    {
        $this->requireAuth();
        $userId = (int)$_SESSION['user_id'];
        $schemaReady = UserModel::hasUserCategoryPreferencesSchema();
        $selected = UserModel::getUserCategoryPreferences($userId);
        $available = UserModel::getAvailableGenres();
        $this->json([
            'success' => true,
            'data' => [
                'selected' => $selected,
                'available' => $available,
                'hasPreferences' => count($selected) > 0,
                'schemaReady' => $schemaReady,
            ],
        ]);
    }

    // ── POST /api/user/preferences/categories ──────────────────────────────────
    public function saveCategoryPreferences(): void
    {
        $this->requireAuth();
        if (!$this->verifyCsrfHeader()) {
            $this->json(['success' => false, 'message' => 'Invalid CSRF token.'], 403);
        }
        $body = $this->jsonBody();
        $genres = $body['genres'] ?? [];
        if (!is_array($genres) || count($genres) === 0) {
            $this->json(['success' => false, 'message' => 'Select at least one category.'], 400);
        }
        if (!UserModel::hasUserCategoryPreferencesSchema()) {
            $this->json([
                'success' => false,
                'message' => 'Category preferences table is missing. Run database/migrations/010_user_category_preferences.sql.',
            ], 500);
        }
        $available = UserModel::getAvailableGenres();
        $allowed = array_fill_keys($available, true);
        $sanitized = [];
        foreach ($genres as $genre) {
            $g = trim((string)$genre);
            if ($g !== '' && isset($allowed[$g])) {
                $sanitized[$g] = true;
            }
        }
        $selected = array_keys($sanitized);
        if (count($selected) === 0) {
            $this->json(['success' => false, 'message' => 'Selected categories are invalid.'], 400);
        }
        $ok = UserModel::saveUserCategoryPreferences((int)$_SESSION['user_id'], $selected);
        if (!$ok) {
            $this->json(['success' => false, 'message' => 'Could not save preferences.'], 500);
        }
        $this->json([
            'success' => true,
            'message' => 'Preferences saved.',
            'data' => [
                'selected' => UserModel::getUserCategoryPreferences((int)$_SESSION['user_id']),
            ],
        ]);
    }

    // ── GET /api/user/recommendations/for-you ──────────────────────────────────
    public function forYouRecommendations(): void
    {
        $this->requireAuth();
        $userId = (int)$_SESSION['user_id'];
        $limit = (int)($_GET['limit'] ?? 12);
        $rows = UserModel::getForYouBooks($userId, $limit);
        $this->json([
            'success' => true,
            'data' => $rows,
            'count' => count($rows),
        ]);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────
    private function jsonBody(): array
    {
        $raw = file_get_contents('php://input');
        if ($raw === '' || $raw === false) {
            return $_POST;
        }
        try {
            return json_decode($raw, true, 512, JSON_THROW_ON_ERROR) ?? [];
        } catch (\JsonException) {
            return $_POST;
        }
    }
}
