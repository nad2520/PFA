<?php
// Minimal DB config for XAMPP/local development.
//
// Back-compat note:
// - Legacy scripts in `controller/` and `view/admin.php` include this file expecting `$cnx`.
// - The new MVC core loads this file expecting it to return an array config.

$db_server = "127.0.0.1";
$db_port = getenv('DB_PORT') ?: "3306";
$db_username = "root";
$db_pwd = "";
$db_name = "lexora";
$db_charset = "utf8mb4";

// Legacy handle (will be removed once all legacy scripts are migrated).
// Keep this non-fatal so MVC code can handle connection errors gracefully.
$cnx = null;
try {
    $cnx = new PDO(
        "mysql:host=$db_server;port=$db_port;dbname=$db_name;charset=$db_charset",
        $db_username,
        $db_pwd,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    // Intentionally swallowed for compatibility; callers should handle null.
}

return [
    'host' => $db_server,
    'port' => $db_port,
    'user' => $db_username,
    'pass' => $db_pwd,
    'name' => $db_name,
    'charset' => $db_charset,
];