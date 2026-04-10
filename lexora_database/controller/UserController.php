<?php
// controller/UserController.php

// Application logic for the user portal
// Sub-routing for different user views

$user_view = $_GET['view'] ?? 'index';

switch($user_view) {
    case 'read-book':
        include __DIR__ . '/../view/user_page/read-book.php';
        break;
    case 'book-detail':
        include __DIR__ . '/../view/user_page/book-detail.php';
        break;
    case 'profile':
        include __DIR__ . '/../view/user_page/profile.php';
        break;
    case 'store':
        include __DIR__ . '/../view/user_page/store.php';
        break;
    case 'auth':
        include __DIR__ . '/../view/user_page/auth.php';
        break;
    case '404':
        include __DIR__ . '/../view/user_page/404.php';
        break;
    case 'index':
    default:
        include __DIR__ . '/../view/user_page/index.php';
        break;
}
