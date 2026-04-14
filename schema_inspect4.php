<?php
$dsn = 'mysql:host=127.0.0.1;dbname=lexora;charset=utf8mb4';
$pdo = new PDO($dsn, 'root', '', [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
foreach (['users','books'] as $table) {
    echo "\nTABLE $table:\n";
    $stmt = $pdo->query('SHOW COLUMNS FROM ' . $table);
    foreach ($stmt->fetchAll() as $col) {
        echo sprintf("%s %s %s %s %s\n", $col['Field'], $col['Type'], $col['Null'], $col['Key'], $col['Default'] === null ? 'NULL' : $col['Default']);
    }
}
