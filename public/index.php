<?php
declare(strict_types=1);

// Public front controller.

define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');
define('CORE_PATH', BASE_PATH . '/core');
define('CONFIG_PATH', BASE_PATH . '/config');
define('VIEWS_PATH', APP_PATH . '/views');

require CORE_PATH . '/Router.php';

$router = new Router();

// Load route table (currently minimal).
$register = require CONFIG_PATH . '/routes.php';
$register($router);

// Compatibility routing: preserve legacy `?view=` behavior.
// This keeps existing links working while we migrate to controllers/views.
$view = $_GET['view'] ?? null;
if ($view !== null) {
    if ($view === 'admin') {
        require APP_PATH . '/controllers/AdminController.php';
        (new AdminController())->index();
        exit;
    }
    $allowedUserViews = ['user', 'profile', 'store', 'book-detail', 'read-book'];
    if (in_array($view, $allowedUserViews, true)) {
        require APP_PATH . '/controllers/UserPageController.php';
        $page = new UserPageController();
        match ($view) {
            'user' => $page->home(),
            'profile' => $page->profile(),
            'store' => $page->store(),
            'book-detail' => $page->bookDetail(),
            'read-book' => $page->readBook(),
            default => require APP_PATH . '/views/home/index.php',
        };
        exit;
    }
    require APP_PATH . '/views/home/index.php';
    exit;
}

// Clean-path routing.
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

// If hosted at /PFA, strip that base prefix for route matching.
$basePrefix = '/PFA';
if (strncmp($path, $basePrefix . '/', strlen($basePrefix) + 1) === 0) {
    $path = substr($path, strlen($basePrefix));
}

$router->dispatch($method, $path);


