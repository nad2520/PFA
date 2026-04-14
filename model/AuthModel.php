<?php

require_once __DIR__ . '/Database.php';

class AuthModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function login(string $identity, string $password): ?array
    {
        $sql = 'SELECT a.* FROM account a LEFT JOIN profile p ON p.accountId = a.id WHERE a.email = :identity OR p.username = :identity LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['identity' => $identity]);
        $account = $stmt->fetch();

        if (!$account) {
            return null;
        }

        if (!password_verify($password, $account['passwordHash'])) {
            return null;
        }

        $update = 'UPDATE account SET lastLoginAt = NOW() WHERE id = :id';
        $stmt = $this->db->prepare($update);
        $stmt->execute(['id' => $account['id']]);

        return $account;
    }

    public function register(string $email, string $password, string $username): ?array
    {
        if ($this->emailExists($email) || $this->usernameExists($username)) {
            return null;
        }

        $accountId = Database::generateUuid();
        $profileId = Database::generateUuid();
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $this->db->beginTransaction();

            $insertAccount = 'INSERT INTO account (id, email, passwordHash, status) VALUES (:id, :email, :hash, :status)';
            $stmt = $this->db->prepare($insertAccount);
            $stmt->execute([
                'id' => $accountId,
                'email' => $email,
                'hash' => $passwordHash,
                'status' => 'ACTIVE',
            ]);

            $insertProfile = 'INSERT INTO profile (id, accountId, username, currentCoins, currentXp, role) VALUES (:id, :accountId, :username, 0, 0, :role)';
            $stmt = $this->db->prepare($insertProfile);
            $stmt->execute([
                'id' => $profileId,
                'accountId' => $accountId,
                'username' => $username,
                'role' => 'USER',
            ]);

            $this->db->commit();
            return ['accountId' => $accountId, 'profileId' => $profileId];
        } catch (PDOException $exception) {
            $this->db->rollBack();
            return null;
        }
    }

    private function emailExists(string $email): bool
    {
        $sql = 'SELECT COUNT(*) as count FROM account WHERE email = :email';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['email' => $email]);
        $result = $stmt->fetch();

        return $result && $result['count'] > 0;
    }

    private function usernameExists(string $username): bool
    {
        $sql = 'SELECT COUNT(*) as count FROM profile WHERE username = :username';
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['username' => $username]);
        $result = $stmt->fetch();

        return $result && $result['count'] > 0;
    }
}
