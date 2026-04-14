<?php

// Application configuration
// Update these values for your MySQL / MariaDB setup.
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'lexora');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

$scriptName = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
$baseUrl = rtrim(dirname($scriptName), '/');
define('BASE_URL', $baseUrl === '' ? '/' : $baseUrl . '/');

// Start session for authentication and flash messages.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
