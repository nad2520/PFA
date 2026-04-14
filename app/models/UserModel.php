<?php
require_once __DIR__ . '/../../core/Database.php';

class UserModel
{
    public static function all(): array
    {
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->query("SELECT * FROM users ORDER BY id DESC");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            return [];
        }
    }

    public static function findPasswordById(int $id): ?string
    {
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
            $stmt->execute([$id]);
            $row = $stmt->fetch();
            return $row['password'] ?? null;
        } catch (PDOException $e) {
            return null;
        }
    }

    public static function update(int $id, string $name, string $email, string $passwordHash): bool
    {
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->prepare("UPDATE users SET nom = ?, email = ?, password = ? WHERE id = ?");
            return (bool) $stmt->execute([$name, $email, $passwordHash, $id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public static function delete(int $id): bool
    {
        try {
            $pdo = Database::pdo();
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
            return (bool) $stmt->execute([$id]);
        } catch (PDOException $e) {
            return false;
        }
    }
}

