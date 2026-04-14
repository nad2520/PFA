<?php
$dsn = 'mysql:host=127.0.0.1;charset=utf8mb4';
$pdo = new PDO($dsn, 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
$stmt = $pdo->query('SHOW DATABASES');
foreach ($stmt->fetchAll() as $row) {
    echo $row['Database'] . "\n";
}
