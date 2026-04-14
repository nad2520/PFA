<?php
/**
 * Lexora - Front Controller
 */

// Handle generic actions directly in front controller
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_start();
    session_destroy();
    $base = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http')
        . '://' . $_SERVER['HTTP_HOST']
        . strtok($_SERVER['REQUEST_URI'], '?');
    header("Location: " . $base);
    exit;
}

// Simple routing based on ?view parameter
$view = $_GET['view'] ?? 'landing';

$allowed_user_views = ['user', 'profile', 'store', 'book-detail', 'read-book'];

if ($view === 'admin') {
    require_once 'view/admin.php';
} elseif (in_array($view, $allowed_user_views)) {
    // Determine the exact file name
    $file = $view === 'user' ? 'index.php' : $view . '.php';
    require_once "view/user/$file";
} else {
    require_once 'view/index.php';
}
?>