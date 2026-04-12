<?php
class Database
{
    private static ?PDO $instance = null;
    private function __construct() {}

    public static function getInstance(array $config): PDO
    {
        if (self::$instance === null) {
            $db = $config['db'];
            $dsn = sprintf(
                '%s:host=%s;port=%s;dbname=%s;charset=%s',
                $db['driver'],
                $db['host'],
                $db['port'],
                $db['database'],
                $db['charset']
            );
            self::$instance = new PDO($dsn, $db['username'], $db['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        }

        return self::$instance;
    }
}
