<?php
/**
 * Database Connection Handler
 * ===============================================
 * Centralizes PDO database connection
 * Used by all models and controllers
 */

class Database {
    private static $cnx = null;

    public static function connect() {
        if (self::$cnx === null) {
            $config = require_once __DIR__ . '/../config/database.php';
            
            try {
                self::$cnx = new PDO(
                    "mysql:host=" . $config['db_server'] . ";dbname=" . $config['db_name'],
                    $config['db_username'],
                    $config['db_pwd']
                );
                self::$cnx->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Database connection failed: " . $e->getMessage());
            }
        }
        
        return self::$cnx;
    }

    public static function getInstance() {
        return self::connect();
    }
}
?>
