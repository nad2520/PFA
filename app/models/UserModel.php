<?php
class UserModel
{
    public static function all(): array
    {
        try {
            require __DIR__ . '/../../config/database.php';
            $res = $cnx->query("SELECT * FROM users ORDER BY id DESC");
            return $res ? $res->fetchAll() : [];
        } catch (Exception $e) {
            return [];
        }
    }

    public static function findPasswordById(int $id): ?string
    {
        try {
            require __DIR__ . '/../../config/database.php';
            $id = (int)$id;
            $res = $cnx->query("SELECT password FROM users WHERE id = " . $id);
            $row = $res ? $res->fetch() : false;
            return is_array($row) ? ($row['password'] ?? null) : null;
        } catch (Exception $e) {
            return null;
        }
    }

    public static function update(int $id, string $name, string $email, string $passwordHash): bool
    {
        try {
            require __DIR__ . '/../../config/database.php';
            $id = (int)$id;
            $nom = $cnx->quote($name);
            $mail = $cnx->quote($email);
            $pwd = $cnx->quote($passwordHash);
            $sql = "UPDATE users SET nom=$nom, email=$mail, password=$pwd WHERE id=$id";
            return (bool)$cnx->query($sql);
        } catch (Exception $e) {
            return false;
        }
    }

    public static function delete(int $id): bool
    {
        try {
            require __DIR__ . '/../../config/database.php';
            $id = (int)$id;
            return (bool)$cnx->query("DELETE FROM users WHERE id = " . $id);
        } catch (Exception $e) {
            return false;
        }
    }

    public static function emailExists(PDO $cnx, string $email): bool
    {
        $mail = $cnx->quote($email);
        $res = $cnx->query("SELECT id FROM users WHERE email = $mail LIMIT 1");
        $row = $res ? $res->fetch() : false;
        return (bool)$row;
    }

    public static function createUser(PDO $cnx, string $username, string $email, string $hashed, string $role, $birthdate): bool
    {
        $nom = $cnx->quote($username);
        $mail = $cnx->quote($email);
        $pwd = $cnx->quote($hashed);
        $r = $cnx->quote($role);
        $b = $birthdate === null || $birthdate === '' ? "NULL" : $cnx->quote((string)$birthdate);
        $sql = "INSERT INTO users(nom, email, password, role, birthdate) VALUES ($nom, $mail, $pwd, $r, $b)";
        return (bool)$cnx->query($sql);
    }

    public static function findByEmailAndPassword(PDO $cnx, string $email, string $hashed): ?array
    {
        $mail = $cnx->quote($email);
        $pwd = $cnx->quote($hashed);
        $res = $cnx->query("SELECT * FROM users WHERE email = $mail AND password = $pwd LIMIT 1");
        $row = $res ? $res->fetch() : false;
        return is_array($row) ? $row : null;
    }
}

