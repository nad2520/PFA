<?php
declare(strict_types=1);

require_once __DIR__ . '/../../core/Database.php';

class QuestModel
{
    /**
     * @return list<string>
     */
    private static function questTypeAliases(string $questType): array
    {
        $raw = strtolower(trim($questType));
        return match ($raw) {
            'read_pages_total', 'read_pages', 'pages_read_total' => ['read_pages_total', 'read_pages', 'pages_read_total'],
            'complete_books_count', 'complete_book_count', 'books_completed_count' => ['complete_books_count', 'complete_book_count', 'books_completed_count'],
            'add_to_list_count', 'add_to_list', 'add_books_to_list', 'genre_explorer' => ['add_to_list_count', 'add_to_list', 'add_books_to_list', 'genre_explorer'],
            default => [$raw !== '' ? $raw : 'read_pages_total'],
        };
    }

    private static function normalizeQuestType(string $questType): string
    {
        $aliases = self::questTypeAliases($questType);
        return match ($aliases[0] ?? 'read_pages_total') {
            'read_pages_total', 'read_pages', 'pages_read_total' => 'read_pages_total',
            'complete_books_count', 'complete_book_count', 'books_completed_count' => 'complete_books_count',
            'add_to_list_count', 'add_to_list', 'add_books_to_list', 'genre_explorer' => 'add_to_list_count',
            default => 'read_pages_total',
        };
    }

    public static function all(): array
    {
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->prepare('SELECT * FROM quests ORDER BY sort_order ASC, id ASC');
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (Throwable) {
            return [];
        }
    }

    public static function findById(int $id): ?array
    {
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->prepare('SELECT * FROM quests WHERE id = ? LIMIT 1');
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (Throwable) {
            return null;
        }
    }

    public static function findByKey(string $questKey): ?array
    {
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->prepare('SELECT * FROM quests WHERE quest_key = ? LIMIT 1');
            $stmt->execute([$questKey]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (Throwable) {
            return null;
        }
    }

    public static function activeForBoard(): array
    {
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->prepare(
                'SELECT quest_key, title, description, coins_reward, xp_reward, quest_type, target_value
                 FROM quests
                 WHERE is_active = 1
                 ORDER BY sort_order ASC, id ASC'
            );
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (Throwable) {
            return [];
        }
    }

    public static function create(array $data): bool
    {
        $questKey = trim((string)($data['quest_key'] ?? ''));
        if ($questKey === '' || !preg_match('/^[a-z0-9_-]+$/', $questKey)) {
            return false;
        }
        $title = trim((string)($data['title'] ?? ''));
        if ($title === '') {
            return false;
        }

        try {
            $pdo = Database::pdo();
            $questType = self::normalizeQuestType(trim((string)($data['quest_type'] ?? 'read_pages_total')));
            $stmt = $pdo->prepare(
                'INSERT INTO quests (quest_key, title, description, quest_type, target_value, coins_reward, xp_reward, is_active, sort_order)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)'
            );
            return (bool)$stmt->execute([
                $questKey,
                $title,
                trim((string)($data['description'] ?? '')),
                $questType,
                max(1, (int)($data['target_value'] ?? 1)),
                (int)($data['coins_reward'] ?? 0),
                (int)($data['xp_reward'] ?? 0),
                !empty($data['is_active']) ? 1 : 0,
                (int)($data['sort_order'] ?? 0),
            ]);
        } catch (Throwable) {
            return false;
        }
    }

    public static function update(int $id, array $data): bool
    {
        if ($id <= 0) {
            return false;
        }
        $title = trim((string)($data['title'] ?? ''));
        if ($title === '') {
            return false;
        }

        try {
            $pdo = Database::pdo();
            $questType = self::normalizeQuestType(trim((string)($data['quest_type'] ?? 'read_pages_total')));
            $stmt = $pdo->prepare(
                'UPDATE quests
                 SET title = ?, description = ?, quest_type = ?, target_value = ?, coins_reward = ?, xp_reward = ?, is_active = ?, sort_order = ?
                 WHERE id = ?'
            );
            return (bool)$stmt->execute([
                $title,
                trim((string)($data['description'] ?? '')),
                $questType,
                max(1, (int)($data['target_value'] ?? 1)),
                (int)($data['coins_reward'] ?? 0),
                (int)($data['xp_reward'] ?? 0),
                !empty($data['is_active']) ? 1 : 0,
                (int)($data['sort_order'] ?? 0),
                $id,
            ]);
        } catch (Throwable) {
            return false;
        }
    }

    public static function delete(int $id): bool
    {
        if ($id <= 0) {
            return false;
        }
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->prepare('DELETE FROM quests WHERE id = ?');
            return (bool)$stmt->execute([$id]);
        } catch (Throwable) {
            return false;
        }
    }

    public static function activeByType(string $type): array
    {
        try {
            $pdo = Database::pdo();
            $aliases = self::questTypeAliases($type);
            $placeholders = implode(', ', array_fill(0, count($aliases), '?'));
            $stmt = $pdo->prepare(
                "SELECT *
                 FROM quests
                 WHERE is_active = 1
                   AND LOWER(TRIM(quest_type)) IN ({$placeholders})
                 ORDER BY sort_order ASC, id ASC"
            );
            $stmt->execute($aliases);
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (Throwable) {
            return [];
        }
    }

    public static function activeForBoardForUser(int $userId): array
    {
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->prepare(
                'SELECT
                    q.id,
                    q.quest_key,
                    q.title,
                    q.description,
                    q.quest_type,
                    q.target_value,
                    q.coins_reward,
                    q.xp_reward,
                    COALESCE(uqp.progress_value, 0) AS progress_value,
                    COALESCE(uqp.is_completed, 0) AS is_completed
                 FROM quests q
                 LEFT JOIN user_quest_progress uqp
                   ON uqp.quest_id = q.id AND uqp.user_id = ?
                 WHERE q.is_active = 1
                 ORDER BY q.sort_order ASC, q.id ASC'
            );
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        } catch (Throwable) {
            return [];
        }
    }
}
