<?php

require_once __DIR__ . '/Database.php';

class BookModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getAllBooks(): array
    {
        $sql = 'SELECT * FROM book ORDER BY createdAt DESC';
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getTrendingBooks(int $limit = 8): array
    {
        $sql = 'SELECT * FROM book WHERE isTrending = 1 ORDER BY publishDate DESC LIMIT :limit';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getBookById(string $bookId): ?array
    {
        $sql = 'SELECT * FROM book WHERE id = :id LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $bookId]);
        return $stmt->fetch() ?: null;
    }

    public function searchBooks(string $query): array
    {
        $sql = 'SELECT * FROM book WHERE title LIKE :term OR description LIKE :term ORDER BY createdAt DESC';
        $stmt = $this->db->prepare($sql);
        $term = '%' . $query . '%';
        $stmt->execute(['term' => $term]);
        return $stmt->fetchAll();
    }
}
