<?php
/**
 * Front Controller
 * ===============================================
 * Entry point for the entire application
 * Routes requests to appropriate views/controllers
 * All requests go through this file via .htaccess
 */

// Start session
session_start();

// Include core classes
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Controller.php';

// Get the current page from URL or default to 'home'
$page = isset($_GET['page']) ? $_GET['page'] : (isset($_GET['view']) ? $_GET['view'] : 'home');
$section = isset($_GET['section']) ? $_GET['section'] : '';

// Route to appropriate view
switch ($page) {
    case 'admin':
        // Admin dashboard
        require_once __DIR__ . '/../app/views/admin/index.php';
        break;

    case 'auth':
    case 'login':
    case 'register':
        // User authentication pages
        require_once __DIR__ . '/../app/views/user/auth.php';
        break;

    case 'profile':
        // User profile page
        require_once __DIR__ . '/../app/views/user/profile.php';
        break;

    case 'store':
        // Book store
        require_once __DIR__ . '/../app/views/user/store.php';
        break;

    case 'book-detail':
        // Single book detail page
        require_once __DIR__ . '/../app/views/user/book-detail.php';
        break;

    case 'read':
        // Reading page
        require_once __DIR__ . '/../app/views/user/read-book.php';
        break;

    case 'api':
        // API endpoints for AJAX calls
        $action = isset($_GET['action']) ? $_GET['action'] : '';
        switch ($action) {
            case 'get-books':
                require_once __DIR__ . '/../app/controllers/GetBooksController.php';
                $controller = new GetBooksController();
                break;
            case 'add-book':
                require_once __DIR__ . '/../app/controllers/BooksController.php';
                $controller = new BooksController();
                break;
            case 'update-book':
                require_once __DIR__ . '/../app/controllers/UpdateBookController.php';
                $controller = new UpdateBookController();
                break;
            case 'delete-book':
                require_once __DIR__ . '/../app/controllers/DeleteBookController.php';
                $controller = new DeleteBookController();
                break;
        }
        break;

    case 'home':
    default:
        // Landing page (home)
        require_once __DIR__ . '/../app/views/landing/index.php';
        break;
}
?>
