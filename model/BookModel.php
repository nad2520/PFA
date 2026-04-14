<?php

require_once __DIR__ . '/Database.php';

class BookModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function countBooks(): int
    {
        $sql = 'SELECT COUNT(*) as total FROM books';
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        return (int)($result['total'] ?? 0);
    }

    public function getBooks(string $search = '', string $genre = 'All', string $audience = 'All'): array
    {
        $sql = 'SELECT * FROM books';
        $params = [];
        $conditions = [];

        if ($search !== '') {
            $conditions[] = '(title LIKE :term OR author LIKE :term OR description LIKE :term)';
            $params['term'] = '%' . $search . '%';
        }

        if ($genre !== '' && $genre !== 'All') {
            $conditions[] = 'genre = :genre';
            $params['genre'] = $genre;
        }

        if ($audience !== '' && $audience !== 'All') {
            $conditions[] = 'audience = :audience';
            $params['audience'] = $audience;
        }

        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= ' ORDER BY created_at DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function addBook(string $title, string $author, string $genre, string $description, string $audience, int $coinCost = 100, int $xpReward = 150, int $coinReward = 40, bool $trending = false, string $coverEmoji = '?'): ?int
    {
        $sql = 'INSERT INTO books (title, author, genre, description, audience, coin_cost, xp_reward, coin_reward, trending, cover_emoji, cover, coinCost, xpReward, coinReward) VALUES (:title, :author, :genre, :description, :audience, :coin_cost, :xp_reward, :coin_reward, :trending, :cover_emoji, :cover, :coinCost, :xpReward, :coinReward)';
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            'title' => $title,
            'author' => $author,
            'genre' => $genre,
            'description' => $description,
            'audience' => $audience,
            'coin_cost' => $coinCost,
            'xp_reward' => $xpReward,
            'coin_reward' => $coinReward,
            'trending' => $trending ? 1 : 0,
            'cover_emoji' => $coverEmoji,
            'cover' => $coverEmoji,
            'coinCost' => $coinCost,
            'xpReward' => $xpReward,
            'coinReward' => $coinReward,
        ]);

        return $result ? (int)$this->db->lastInsertId() : null;
    }

    public function deleteBook(int $id): bool
    {
        $sql = 'DELETE FROM books WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    public function getAllBooks(): array
    {
        $sql = 'SELECT * FROM books ORDER BY created_at DESC';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getTrendingBooks(int $limit = 8): array
    {
        $sql = 'SELECT * FROM books WHERE trending = 1 ORDER BY created_at DESC LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getBookById(string $bookId): ?array
    {
        $sql = 'SELECT * FROM books WHERE id = :id LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $bookId]);
        return $stmt->fetch() ?: null;
    }

    public function searchBooks(string $query): array
    {
        $sql = 'SELECT * FROM books WHERE title LIKE :term OR description LIKE :term ORDER BY created_at DESC';
        $stmt = $this->db->prepare($sql);
        $term = '%' . $query . '%';
        $stmt->execute(['term' => $term]);
        return $stmt->fetchAll();
    }
}
