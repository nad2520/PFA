<?php
declare(strict_types=1);

require_once CORE_PATH . '/Controller.php';
require_once APP_PATH  . '/models/UserModel.php';
require_once APP_PATH  . '/models/BookModel.php';

/**
 * UserApiController
 * All endpoints return JSON. Used by the frontend JS (user_app.js) instead of localStorage.
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
                'levelPct'    => min(100, (int)round(($xp % $xpForNext) / $xpForNext * 100)),
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
            ],
        ]);
    }

    // ── POST /api/user/reading-session ────────────────────────────────────────
    public function logSession(): void
    {
        $this->requireAuth();
        $body = $this->jsonBody();

        $userId     = (int)$_SESSION['user_id'];
        $bookId     = (int)($body['book_id']      ?? 0);
        $pagesRead  = (int)($body['pages_read']   ?? 0);
        $minutesRead = (int)($body['minutes_read'] ?? 0);

        if ($bookId <= 0) {
            $this->json(['success' => false, 'message' => 'book_id required.'], 400);
        }

        $pdo  = Database::pdo();
        $today = date('Y-m-d');

        // Upsert today's reading session
        $stmt = $pdo->prepare(
            'INSERT INTO reading_sessions (user_id, book_id, session_date, pages_read, minutes_read)
             VALUES (?, ?, ?, ?, ?)
             ON DUPLICATE KEY UPDATE
               pages_read   = pages_read   + VALUES(pages_read),
               minutes_read = minutes_read + VALUES(minutes_read)'
        );
        $stmt->execute([$userId, $bookId, $today, $pagesRead, $minutesRead]);

        // Update user's last_read_at, streak, lumo state, XP
        UserModel::updateAfterReading($userId, $minutesRead);

        $this->json(['success' => true, 'message' => 'Session logged.']);
    }

    // ── POST /api/user/book/purchase ──────────────────────────────────────────
    public function purchaseBook(): void
    {
        $this->requireAuth();
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
        $cost = (int)$book['coinCost'];

        if ((int)$user['coins'] < $cost) {
            $this->json(['success' => false, 'message' => 'Not enough coins.'], 402);
        }

        // Check already owned
        $pdo  = Database::pdo();
        $chk  = $pdo->prepare('SELECT id FROM user_books WHERE user_id = ? AND book_id = ?');
        $chk->execute([$userId, $bookId]);
        if ($chk->fetch()) {
            $this->json(['success' => false, 'message' => 'Book already in your library.'], 409);
        }

        // Deduct coins, add to library
        $pdo->beginTransaction();
        try {
            $pdo->prepare('UPDATE users SET coins = coins - ? WHERE id = ?')->execute([$cost, $userId]);
            $pdo->prepare(
                'INSERT INTO user_books (user_id, book_id, status) VALUES (?, ?, ?)'
            )->execute([$userId, $bookId, 'plan_to_read']);

            // Log economy
            $pdo->prepare(
                'INSERT INTO economy_logs (user_id, log_date, coins_spent, event_type) VALUES (?, CURDATE(), ?, ?)'
            )->execute([$userId, $cost, 'book_purchase']);

            $pdo->commit();
        } catch (\Throwable $e) {
            $pdo->rollBack();
            $this->json(['success' => false, 'message' => 'Purchase failed.'], 500);
        }

        $this->json(['success' => true, 'message' => 'Book added to your library!', 'coinsSpent' => $cost]);
    }

    // ── POST /api/user/book/complete ──────────────────────────────────────────
    public function completeBook(): void
    {
        $this->requireAuth();
        $body   = $this->jsonBody();
        $userId = (int)$_SESSION['user_id'];
        $bookId = (int)($body['book_id'] ?? 0);
        $rating = isset($body['rating']) ? (int)$body['rating'] : null;

        if ($bookId <= 0) {
            $this->json(['success' => false, 'message' => 'book_id required.'], 400);
        }
        if ($rating !== null && ($rating < 1 || $rating > 5)) {
            $this->json(['success' => false, 'message' => 'Rating must be 1–5.'], 400);
        }

        $book = BookModel::findById($bookId);
        if (!$book) {
            $this->json(['success' => false, 'message' => 'Book not found.'], 404);
        }

        $pdo = Database::pdo();
        $pdo->beginTransaction();
        try {
            // Mark book as completed
            $stmt = $pdo->prepare(
                'UPDATE user_books
                 SET status = "completed", completed_at = NOW(), rating = ?
                 WHERE user_id = ? AND book_id = ?'
            );
            $stmt->execute([$rating, $userId, $bookId]);

            // Award XP and coins
            $xpReward   = (int)$book['xpReward'];
            $coinReward = (int)$book['coinReward'];

            $upd = $pdo->prepare(
                'UPDATE users
                 SET xp    = xp    + ?,
                     coins = coins + ?,
                     level = GREATEST(1, FLOOR((xp + ?) / 500) + 1)
                 WHERE id = ?'
            );
            $upd->execute([$xpReward, $coinReward, $xpReward, $userId]);

            // Log economy event
            $pdo->prepare(
                'INSERT INTO economy_logs (user_id, log_date, coins_earned, event_type) VALUES (?, CURDATE(), ?, ?)'
            )->execute([$userId, $coinReward, 'book_completion']);

            $pdo->commit();
        } catch (\Throwable $e) {
            $pdo->rollBack();
            $this->json(['success' => false, 'message' => 'Could not save completion.'], 500);
        }

        // Return updated stats
        $user = UserModel::findById($userId);
        $this->json([
            'success'    => true,
            'message'    => 'Book marked as completed!',
            'xpEarned'   => (int)$book['xpReward'],
            'coinsEarned'=> (int)$book['coinReward'],
            'newCoins'   => (int)$user['coins'],
            'newLevel'   => (int)$user['level'],
            'newXp'      => (int)$user['xp'],
        ]);
    }

    // ── POST /api/user/book/progress ──────────────────────────────────────────
    public function saveProgress(): void
    {
        $this->requireAuth();
        $body   = $this->jsonBody();
        $userId = (int)$_SESSION['user_id'];
        $bookId = (int)($body['book_id'] ?? 0);
        $page   = (int)($body['page']    ?? 0);

        if ($bookId <= 0) {
            $this->json(['success' => false, 'message' => 'book_id required.'], 400);
        }

        $pdo  = Database::pdo();
        $stmt = $pdo->prepare(
            'INSERT INTO user_books (user_id, book_id, status, progress_page, started_at)
             VALUES (?, ?, "reading", ?, NOW())
             ON DUPLICATE KEY UPDATE
               progress_page = VALUES(progress_page),
               status = IF(status = "plan_to_read", "reading", status),
               started_at = IFNULL(started_at, NOW())'
        );
        $stmt->execute([$userId, $bookId, $page]);

        $this->json(['success' => true]);
    }

    // ── GET /api/leaderboard ──────────────────────────────────────────────────
    public function leaderboard(): void
    {
        $top = UserModel::leaderboard(10);
        $this->json(['success' => true, 'data' => $top]);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────
    private function jsonBody(): array
    {
        $raw = file_get_contents('php://input');
        if ($raw === '' || $raw === false) return $_POST;
        try {
            return json_decode($raw, true, 512, JSON_THROW_ON_ERROR) ?? [];
        } catch (\JsonException) {
            return $_POST;
        }
    }
}