<?php

require_once __DIR__ . '/Database.php';

class UserModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function countUsers(): int
    {
        $sql = 'SELECT COUNT(*) as total FROM users';
        $stmt = $this->db->query($sql);
        $result = $stmt->fetch();
        return (int)($result['total'] ?? 0);
    }

    public function getUsers(string $search = '', string $role = 'All'): array
    {
        $sql = 'SELECT * FROM users';
        $params = [];
        $conditions = [];

        if ($search !== '') {
            $conditions[] = '(nom LIKE :term OR email LIKE :term)';
            $params['term'] = '%' . $search . '%';
        }

        if ($role !== '' && $role !== 'All') {
            $conditions[] = 'role = :role';
            $params['role'] = $role;
        }

        if (!empty($conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $conditions);
        }

        $sql .= ' ORDER BY created_at DESC';
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function addUser(string $nom, string $email, string $password, string $role = 'user', int $coins = 0, int $level = 1, ?string $birthdate = null): ?int
    {
        if ($this->emailExists($email)) {
            return null;
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $sql = 'INSERT INTO users (nom, email, password, role, coins, level, birthdate) VALUES (:nom, :email, :password, :role, :coins, :level, :birthdate)';
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            'nom' => $nom,
            'email' => $email,
            'password' => $passwordHash,
            'role' => $role,
            'coins' => $coins,
            'level' => $level,
            'birthdate' => $birthdate,
        ]);

        return $result ? (int)$this->db->lastInsertId() : null;
    }

    public function deleteUser(int $id): bool
    {
        $sql = 'DELETE FROM users WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    public function emailExists(string $email): bool
    {
        $sql = 'SELECT COUNT(*) as count FROM users WHERE email = :email';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        $result = $stmt->fetch();
        return $result && $result['count'] > 0;
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
