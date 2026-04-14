<?php
// Minimal DB config for XAMPP/local development.
//
// Back-compat note:
// - Legacy scripts in `controller/` and `view/admin.php` include this file expecting `$cnx`.
// - The new MVC core loads this file expecting it to return an array config.

$db_server = "127.0.0.1";
$db_username = "root";
$db_pwd = "";
$db_name = "lexora";
$db_charset = "utf8mb4";

// Legacy handle (will be removed once all legacy scripts are migrated).
$cnx = new PDO(
    "mysql:host=$db_server;dbname=$db_name;charset=$db_charset",
    $db_username,
    $db_pwd,
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
);

return [
    'host' => $db_server,
    'user' => $db_username,
    'pass' => $db_pwd,
    'name' => $db_name,
    'charset' => $db_charset,
];