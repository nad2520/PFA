<?php
$db_server = "127.0.0.1";
$db_username = "root";
$db_pwd = "";

try {
    $cn = new PDO("mysql:host=$db_server", $db_username, $db_pwd);
    $cn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if lexora exists
    $stmt = $cn->query("SHOW DATABASES LIKE 'lexora'");
    if ($stmt->rowCount() == 0) {
        echo "Database lexora does not exist. Creating it...\n";
        $cn->exec("CREATE DATABASE lexora");
    }

    $cn->exec("USE lexora");

    // Check if users table exists
    $stmt = $cn->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() == 0) {
        echo "Table users does not exist. Creating it...\n";
        $cn->exec("CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nom VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            role VARCHAR(50) DEFAULT 'user',
            coins INT DEFAULT 0,
            level INT DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        echo "Table users created successfully.\n";
    } else {
        echo "Table users exists. Columns:\n";
        $stmt = $cn->query("DESCRIBE users");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($columns as $col) {
            echo $col['Field'] . " - " . $col['Type'] . "\n";
        }
    }

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>