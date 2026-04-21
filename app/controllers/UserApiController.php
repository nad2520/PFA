<?php
declare(strict_types=1);

require_once CORE_PATH . '/Controller.php';
require_once CORE_PATH . '/Database.php';
require_once APP_PATH  . '/models/UserModel.php';
require_once APP_PATH  . '/models/BookModel.php';

/**
 * UserApiController
 * All endpoints return JSON. Used by the frontend JS (read_book_app.js, profile_app.js, store_app.js).
 */
class UserApiController extends Controller
{
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

        $user = UserModel::findById($userId);
        if (!$user) {
            $this->json(['success' => false, 'message' => 'User not found.'], 404);
        }
        $cost = (int)$book['coinCost'];

        if ((int)$user['coins'] < $cost) {
            $this->json(['success' => false, 'message' => 'Not enough coins.'], 402);
        }

        $pdo = Database::pdo();
        $chk = $pdo->prepare('SELECT id FROM user_books WHERE user_id = ? AND book_id = ?');
        $chk->execute([$userId, $bookId]);
        if ($chk->fetch()) {
            $this->json(['success' => false, 'message' => 'Book already in your library.'], 409);
        }

        $pdo->beginTransaction();
        try {
            $deduct = $pdo->prepare('UPDATE users SET coins = coins - ? WHERE id = ? AND coins >= ?');
            $deduct->execute([$cost, $userId, $cost]);
            if ($deduct->rowCount() === 0) {
                throw new RuntimeException('Not enough coins');
            }
            $pdo->prepare(
                'INSERT INTO user_books (user_id, book_id, status, progress_page, started_at)
                 VALUES (?, ?, ?, 0, NOW())
                 ON DUPLICATE KEY UPDATE
                   status = CASE
                     WHEN status = "completed" THEN status
                     ELSE "reading"
                   END,
                   started_at = IFNULL(started_at, NOW())'
            )->execute([$userId, $bookId, 'reading']);

            $pdo->prepare(
                'INSERT INTO economy_logs (user_id, log_date, coins_spent, event_type) VALUES (?, CURDATE(), ?, ?)'
            )->execute([$userId, $cost, 'book_purchase']);

            $pdo->commit();
        } catch (\Throwable $e) {
            $pdo->rollBack();
            if ($e instanceof RuntimeException) {
                $this->json(['success' => false, 'message' => 'Not enough coins.'], 402);
            }
            $this->json(['success' => false, 'message' => 'Purchase failed.'], 500);
        }

        $fresh = UserModel::findById($userId);
        $this->json([
            'success' => true,
            'message' => 'Book added to your library!',
            'coinsSpent' => $cost,
            'newCoins' => (int)($fresh['coins'] ?? 0),
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
                $pdo->prepare(
                    'INSERT INTO user_books (user_id, book_id, status, progress_page, started_at)
                     VALUES (?, ?, "reading", 0, NOW())'
                )->execute([$userId, $bookId]);
                $sel->execute([$userId, $bookId]);
                $ub = $sel->fetch(PDO::FETCH_ASSOC);
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

        $pdo = Database::pdo();
        $coinCost = max(0, (int)($book['coinCost'] ?? 0));
        $ownedStmt = $pdo->prepare(
            'SELECT status FROM user_books WHERE user_id = ? AND book_id = ? LIMIT 1'
        );
        $ownedStmt->execute([$userId, $bookId]);
        $owned = $ownedStmt->fetch(PDO::FETCH_ASSOC);

        if (!$owned && $coinCost > 0) {
            $pdo->beginTransaction();
            try {
                $coinsStmt = $pdo->prepare('SELECT coins FROM users WHERE id = ? FOR UPDATE');
                $coinsStmt->execute([$userId]);
                $coins = (int)($coinsStmt->fetchColumn() ?: 0);
                if ($coins < $coinCost) {
                    $pdo->rollBack();
                    $this->json([
                        'success' => false,
                        'error' => 'NOT_ENOUGH_COINS',
                        'message' => "You don't have enough coins",
                    ], 403);
                }
                $deduct = $pdo->prepare('UPDATE users SET coins = coins - ? WHERE id = ? AND coins >= ?');
                $deduct->execute([$coinCost, $userId, $coinCost]);
                if ($deduct->rowCount() === 0) {
                    $pdo->rollBack();
                    $this->json([
                        'success' => false,
                        'error' => 'NOT_ENOUGH_COINS',
                        'message' => "You don't have enough coins",
                    ], 403);
                }
                $pdo->prepare(
                    'INSERT INTO economy_logs (user_id, log_date, coins_spent, event_type) VALUES (?, CURDATE(), ?, ?)'
                )->execute([$userId, $coinCost, 'book_access']);
                $pdo->commit();
            } catch (\Throwable $e) {
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                $this->json(['success' => false, 'message' => 'Could not validate book access.'], 500);
            }
        }

        $ok = UserModel::saveReadingProgress($userId, $bookId, $page);
        if (!$ok) {
            $this->json(['success' => false, 'message' => 'Could not save progress.'], 500);
        }

        $this->json(['success' => true]);
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

        $ok = UserModel::addToMyList($userId, $bookId);
        if (!$ok) {
            $this->json(['success' => false, 'message' => 'Could not add to list.'], 500);
        }
        $this->json(['success' => true, 'message' => 'Book added to your list.']);
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

        $reward = 200;
        $pdo = Database::pdo();
        $pdo->beginTransaction();
        try {
            $ins = $pdo->prepare(
                'INSERT INTO user_quest_rewards (user_id, quest_key, coins_rewarded) VALUES (?, ?, ?)'
            );
            $ins->execute([$userId, $questKey, $reward]);

            $pdo->prepare('UPDATE users SET coins = coins + ? WHERE id = ?')->execute([$reward, $userId]);
            $pdo->prepare(
                'INSERT INTO economy_logs (user_id, log_date, coins_earned, event_type) VALUES (?, CURDATE(), ?, ?)'
            )->execute([$userId, $reward, 'quest_completion']);
            $pdo->commit();
        } catch (\Throwable $e) {
            $pdo->rollBack();
            if (($e instanceof PDOException) && ($e->getCode() === '23000')) {
                $user = UserModel::findById($userId);
                $this->json([
                    'success' => true,
                    'alreadyClaimed' => true,
                    'message' => 'Quest reward already claimed.',
                    'coinsEarned' => 0,
                    'newCoins' => (int)($user['coins'] ?? 0),
                ]);
            }
            $this->json(['success' => false, 'message' => 'Could not complete quest.'], 500);
        }

        $user = UserModel::findById($userId);
        $this->json([
            'success' => true,
            'message' => 'Quest completed!',
            'coinsEarned' => $reward,
            'newCoins' => (int)($user['coins'] ?? 0),
        ]);
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
