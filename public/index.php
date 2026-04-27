<?php
declare(strict_types=1);

// Public front controller.

define('BASE_PATH', dirname(__DIR__));
define('PUBLIC_PATH', __DIR__);
define('APP_PATH', BASE_PATH . '/app');
define('CORE_PATH', BASE_PATH . '/core');
define('CONFIG_PATH', BASE_PATH . '/config');
define('VIEWS_PATH', APP_PATH . '/views');

require PUBLIC_PATH . '/_lx_public_urls.php';
require CORE_PATH . '/Router.php';

$router = new Router();

// Load route table (currently minimal).
$register = require CONFIG_PATH . '/routes.php';
$register($router);

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
        };
        exit;
    }
    http_response_code(404);
    require APP_PATH . '/views/users/404.php';
    exit;
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

foreach (array_unique([lx_public_base_url(), lx_app_base_url()]) as $basePrefix) {
    if ($basePrefix === '' || $basePrefix === '/') {
        continue;
    }

    if ($path === $basePrefix) {
        $path = '/';
        break;
    }

    $prefixWithSlash = $basePrefix . '/';
    if (strncmp($path, $prefixWithSlash, strlen($prefixWithSlash)) === 0) {
        $path = substr($path, strlen($basePrefix));
        break;
    }
}

$router->dispatch($method, $path);


