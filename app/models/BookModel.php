<?php
require_once __DIR__ . '/../../core/Database.php';

class BookModel
{
    public static function findById(int $id): ?array
    {
        if ($id <= 0) {
            return null;
        }
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->prepare('SELECT * FROM books WHERE id = ? LIMIT 1');
            $stmt->execute([$id]);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row ?: null;
        } catch (PDOException $e) {
            return null;
        }
    }

    public static function all(): array
    {
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->query("SELECT * FROM books ORDER BY id DESC");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            // If the table doesn't exist (or DB not initialized yet), don't crash the app.
            return [];
        }
    }

    public static function create(array $data): bool
    {
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->prepare(
                "INSERT INTO books (title, author, genre, cover, coinCost, xpReward, coinReward, audience, trending)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            return (bool) $stmt->execute([
                $data['title'],
                $data['author'],
                $data['genre'],
                $data['cover'] ?? '📖',
                (int)($data['coinCost'] ?? 0),
                (int)($data['xpReward'] ?? 0),
                (int)($data['coinReward'] ?? 0),
                $data['audience'] ?? 'All',
                (int)($data['trending'] ?? 0),
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function update(int $id, array $data): bool
    {
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->prepare(
                "UPDATE books
                 SET title = ?, author = ?, genre = ?, cover = ?, coinCost = ?, xpReward = ?, coinReward = ?, audience = ?, trending = ?
                 WHERE id = ?"
            );
            return (bool) $stmt->execute([
                $data['title'],
                $data['author'],
                $data['genre'],
                $data['cover'] ?? '📖',
                (int)($data['coinCost'] ?? 0),
                (int)($data['xpReward'] ?? 0),
                (int)($data['coinReward'] ?? 0),
                $data['audience'] ?? 'All',
                (int)($data['trending'] ?? 0),
                $id,
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function delete(int $id): bool
    {
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->prepare("DELETE FROM books WHERE id = ?");
            return (bool) $stmt->execute([$id]);
        } catch (PDOException $e) {
            return false;
        }
    }
    public static function searchByTitle(string $query): array
    {
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->prepare('SELECT * FROM books WHERE title LIKE ? ORDER BY title ASC');
            $stmt->execute(['%' . $query . '%']);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
}

