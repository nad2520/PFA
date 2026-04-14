<?php
$dsn = 'mysql:host=127.0.0.1;dbname=lexora;charset=utf8mb4';
$pdo = new PDO($dsn, 'root', '', [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
foreach (['users','books'] as $table) {
    echo "CREATE TABLE $table:\n";
    $stmt = $pdo->query('SHOW CREATE TABLE ' . $table);
    $row = $stmt->fetch();
    echo $row['Create Table'] . "\n\n";
}
