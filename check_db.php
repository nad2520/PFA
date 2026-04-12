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
            birthdate DATE DEFAULT NULL,
            coins INT DEFAULT 0,
            level INT DEFAULT 1,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        echo "Table users created successfully.\n";
    } else {
        echo "Table users exists.\n";
        try {
            $cn->exec("ALTER TABLE users ADD COLUMN birthdate DATE DEFAULT NULL");
            echo "Added birthdate column to users table.\n";
        } catch (PDOException $e) {
            // Ignore if column already exists
        }
    }

    // Check if books table exists
    $stmt = $cn->query("SHOW TABLES LIKE 'books'");
    if ($stmt->rowCount() == 0) {
        echo "Table books does not exist. Creating it...\n";
        $cn->exec("CREATE TABLE books (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            author VARCHAR(255) NOT NULL,
            genre VARCHAR(100) DEFAULT 'Fantasy',
            cover VARCHAR(10) DEFAULT '📖',
            coinCost INT DEFAULT 100,
            xpReward INT DEFAULT 150,
            coinReward INT DEFAULT 40,
            audience VARCHAR(50) DEFAULT 'All',
            trending BOOLEAN DEFAULT FALSE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        echo "Table books created successfully.\n";
    } else {
        echo "Table books exists.\n";
    }
    
    // Check if posts table exists
    $stmt = $cn->query("SHOW TABLES LIKE 'posts'");
    if ($stmt->rowCount() == 0) {
        echo "Table posts does not exist. Creating it...\n";
        $cn->exec("CREATE TABLE posts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            author VARCHAR(255) NOT NULL,
            book VARCHAR(255) NOT NULL,
            tag VARCHAR(50) DEFAULT 'discussion',
            upvotes INT DEFAULT 0,
            comments INT DEFAULT 0,
            status VARCHAR(50) DEFAULT 'Clean',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        echo "Table posts created successfully.\n";
    } else {
        echo "Table posts exists.\n";
    }

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
