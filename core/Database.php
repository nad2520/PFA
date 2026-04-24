<?php
class Database
{
    private static ?PDO $pdo = null;

    public static function pdo(): PDO
    {
        if (self::$pdo instanceof PDO) {
            return self::$pdo;
        }

        $config = require __DIR__ . '/../config/database.php';

        $host = $config['host'] ?? '127.0.0.1';
        $port = (string)($config['port'] ?? '3306');
        $db   = $config['name'] ?? '';
        $user = $config['user'] ?? '';
        $pass = $config['pass'] ?? '';
        $charset = $config['charset'] ?? 'utf8mb4';

        $dsn = "mysql:host={$host};port={$port};dbname={$db};charset={$charset}";
        try {
            $pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            throw new RuntimeException(
                "Database connection failed. Please verify MySQL is running and database settings are correct.",
                0,
                $e
            );
        }

        self::$pdo = $pdo;
        return self::$pdo;
    }
}

