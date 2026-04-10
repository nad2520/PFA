<?php
// Front Controller

// Include global config
$config = require_once 'config/config.php';

// Simple routing based on ?page= parameter
$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'admin':
        require_once 'controller/AdminController.php';
        break;
        
    case 'user':
        // Handle all user portal pages like read-book, profile, store inside UserController
        require_once 'controller/UserController.php';
        break;

    case 'home':
    default:
        require_once 'controller/HomeController.php';
        break;
}
