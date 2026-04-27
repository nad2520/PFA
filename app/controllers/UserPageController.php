<?php
declare(strict_types=1);

require_once CORE_PATH . '/Controller.php';
require_once APP_PATH . '/models/UserModel.php';
require_once APP_PATH . '/models/BookModel.php';

/**
 * UserPageController
 * Serves all user-facing HTML pages, injecting real session/DB data
 * so views never have to hardcode names, levels, coins, etc.
 */
class UserPageController extends Controller
{
    public function home(): void
    {
        $this->requireAuth();
        $user = $this->currentUserData();
        $this->renderUserView('users/index', $user);
    }

    public function profile(): void
    {
        $this->requireAuth();
        $user = $this->currentUserData();
        $library = UserModel::getUserBooks((int) $_SESSION['user_id']);
        $leaderboard = UserModel::leaderboard(10);

        $this->renderUserView('users/profile', array_merge($user, [
            'library' => $library,
            'leaderboard' => $leaderboard,
        ]));
    }

    public function store(): void
    {
        $this->requireAuth();
        $user = $this->currentUserData();
        $books = BookModel::all();
        $this->renderUserView('users/store', array_merge($user, ['books' => $books]));
    }

    public function bookDetail(): void
    {
        $this->requireAuth();
        $user = $this->currentUserData();
        $bookId = (int) ($_GET['id'] ?? 0);
        $book = $bookId > 0 ? BookModel::findById($bookId) : null;
        $library = UserModel::getUserBooks((int) $_SESSION['user_id']);

        $this->renderUserView('users/book-detail', array_merge($user, [
            'book' => $book,
            'library' => $library
        ]));
    }

    public function readBook(): void
    {
        $this->requireAuth();
        $user = $this->currentUserData();
        $bookId = (int) ($_GET['id'] ?? 0);
        $book = $bookId > 0 ? BookModel::findById($bookId) : null;

        if (!$book) {
            $this->redirect('store');
        }

        // Record today's reading session start
        UserModel::touchLastRead((int) $_SESSION['user_id']);

        $this->renderUserView('users/read-book', array_merge($user, ['book' => $book]));
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /** Loads full user record from DB and computes derived values */
    private function currentUserData(): array
    {
        $userId = (int) $_SESSION['user_id'];
        $row = UserModel::findById($userId);

        if (!$row) {
            // Session is stale — force logout
            session_unset();
            session_destroy();
            $this->redirect('');
        }

        $xp = (int) $row['xp'];
        $level = max(1, (int) $row['level']);
        $xpForNext = $level * 500; // e.g. level 1 needs 500 XP, level 2 needs 1000, etc.
        $levelPct = min(100, (int) round(($xp % $xpForNext) / $xpForNext * 100));

        // Lumo state
        $lumoState = UserModel::computeLumoState($row);
        $lumoMessage = match ($lumoState) {
            'happy' => 'Lumo is happy! Keep reading to maintain your streak. 🌟',
            'dim' => 'Lumo misses you! You haven\'t read in over 24 hours.',
            'worried' => 'Lumo is worried… It\'s been more than 3 days since your last read!',
            default => '',
        };

        return [
            'userId' => $userId,
            'userName' => htmlspecialchars($row['nom'], ENT_QUOTES),
            'userInitials' => strtoupper(mb_substr($row['nom'], 0, 1) . (str_contains($row['nom'], ' ') ? mb_substr(strrchr($row['nom'], ' '), 1, 1) : '')),
            'userRole' => $row['role'],
            'userCoins' => (int) $row['coins'],
            'userXp' => $xp,
            'userLevel' => $level,
            'levelPct' => $levelPct,
            'xpForNext' => $xpForNext,
            'booksRead' => UserModel::countBooksRead($userId),
            'streakDays' => (int) ($row['streak_days'] ?? 0),
            'lumoState' => $lumoState,
            'lumoMessage' => $lumoMessage,
            'csrf_token' => $this->generateCsrf(),
        ];
    }

    /** Render a user view without the global layout (user pages are self-contained) */
    private function renderUserView(string $view, array $data): void
    {
        $this->render($view, $data, null);
    }
}
