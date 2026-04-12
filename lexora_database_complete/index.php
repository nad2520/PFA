<?php
// Front Controller

session_start();

// Load config and autoload core classes
$config = require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Model.php';

// Simple class autoloader for controllers and models
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/controllers/' . $class . '.php',
        __DIR__ . '/models/' . $class . '.php',
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

$page = $_GET['page'] ?? 'home';
$action = $_GET['action'] ?? null;

switch ($page) {
    case 'admin':
        $controller = new AdminController($config);
        break;

    case 'user':
        $controller = new UserController($config);
        break;

    case 'home':
    default:
        $controller = new HomeController($config);
        break;
}

if ($action && method_exists($controller, $action)) {
    $controller->{$action}();
    exit;
}

// Default page dispatcher
switch ($page) {
    case 'admin':
        $controller->index();
        break;

    case 'user':
        $controller->index();
        break;

    case 'home':
    default:
        $controller->index();
        break;
}
