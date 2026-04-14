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

    // --- USERS TABLE ---
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
        echo "Table users exists. Checking for missing columns...\n";
        try {
            $cn->exec("ALTER TABLE users ADD COLUMN birthdate DATE DEFAULT NULL");
            echo "Added 'birthdate' to users.\n";
        } catch (PDOException $e) {
        }
        try {
            $cn->exec("ALTER TABLE users ADD COLUMN coins INT DEFAULT 0");
            echo "Added 'coins' to users.\n";
        } catch (PDOException $e) {
        }
        try {
            $cn->exec("ALTER TABLE users ADD COLUMN level INT DEFAULT 1");
            echo "Added 'level' to users.\n";
        } catch (PDOException $e) {
        }
    }

    // --- BOOKS TABLE ---
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
        echo "Table books exists. Checking for missing columns...\n";
        $bookCols = [
            'genre' => "VARCHAR(100) DEFAULT 'Fantasy'",
            'cover' => "VARCHAR(10) DEFAULT '📖'",
            'coinCost' => "INT DEFAULT 100",
            'xpReward' => "INT DEFAULT 150",
            'coinReward' => "INT DEFAULT 40",
            'audience' => "VARCHAR(50) DEFAULT 'All'",
            'trending' => "BOOLEAN DEFAULT FALSE"
        ];
        foreach ($bookCols as $col => $def) {
            try {
                $cn->exec("ALTER TABLE books ADD COLUMN $col $def");
                echo "Added '$col' to books.\n";
            } catch (PDOException $e) {
            }
        }
    }

    // --- POSTS TABLE ---
    $stmt = $cn->query("SHOW TABLES LIKE 'posts'");
    if ($stmt->rowCount() == 0) {
        echo "Table posts does not exist. Creating it...\n";
        $cn->exec("CREATE TABLE posts (
            id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            content TEXT,
            author VARCHAR(255) NOT NULL,
            book VARCHAR(255) NOT NULL,
            bookId INT DEFAULT NULL,
            tag VARCHAR(50) DEFAULT 'discussion',
            upvotes INT DEFAULT 0,
            comments INT DEFAULT 0,
            status VARCHAR(50) DEFAULT 'Clean',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        echo "Table posts created successfully.\n";
    } else {
        echo "Table posts exists. Checking for missing columns...\n";
        $postCols = [
            'content' => "TEXT",
            'author' => "VARCHAR(255) NOT NULL",
            'book' => "VARCHAR(255) NOT NULL",
            'bookId' => "INT DEFAULT NULL",
            'tag' => "VARCHAR(50) DEFAULT 'discussion'",
            'upvotes' => "INT DEFAULT 0",
            'comments' => "INT DEFAULT 0",
            'status' => "VARCHAR(50) DEFAULT 'Clean'"
        ];
        foreach ($postCols as $col => $def) {
            try {
                $cn->exec("ALTER TABLE posts ADD COLUMN $col $def");
                echo "Added '$col' to posts.\n";
            } catch (PDOException $e) {
            }
        }
    }

    echo "\nDatabase synchronization complete!\n";

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>