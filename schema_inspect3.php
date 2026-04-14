<?php
$dsn = 'mysql:host=127.0.0.1;dbname=lexora;charset=utf8mb4';
$pdo = new PDO($dsn, 'root', '', [PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE=>PDO::FETCH_ASSOC]);
$stmt = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema='lexora'");
$tables = $stmt->fetchAll();
foreach ($tables as $row) {
    echo $row['table_name'] . "\n";
}
