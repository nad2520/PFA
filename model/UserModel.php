<?php

require_once __DIR__ . '/Database.php';

class UserModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function getProfileByAccountId(string $accountId): ?array
    {
        $sql = 'SELECT * FROM profile WHERE accountId = :accountId LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['accountId' => $accountId]);
        return $stmt->fetch() ?: null;
    }

    public function getProfileById(string $profileId): ?array
    {
        $sql = 'SELECT * FROM profile WHERE id = :id LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $profileId]);
        return $stmt->fetch() ?: null;
    }

    public function getUserBooks(string $profileId): array
    {
        $sql = 'SELECT b.*, ub.status, ub.progressPercent, ub.isPurchased FROM userbook ub INNER JOIN book b ON ub.bookId = b.id WHERE ub.profileId = :profileId';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['profileId' => $profileId]);
        return $stmt->fetchAll();
    }
}
