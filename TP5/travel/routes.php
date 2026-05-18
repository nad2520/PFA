<?php

use App\Controller\AuthController;
use App\Controller\ProductController;

return function ($uri, $config) {

    switch ($uri) {

        // =========================
        // 🔐 AUTH
        // =========================
        case '/login':
            (new AuthController($config))->login();
            break;

        case '/register':
            (new AuthController($config))->register();
            break;

        case '/logout':
            (new AuthController($config))->logout();
            break;

        // =========================
        // 🏠 DASHBOARD
        // =========================
        case '/dashboard':
            if (!isset($_SESSION['user'])) {
                header("Location: /login");
                exit;
            }

            require __DIR__ . '/views/dashboard.view.php';
            break;

        // =========================
        // 📦 PRODUCTS
        // =========================
        case '/products':
            if (!isset($_SESSION['user'])) {
                header("Location: /login");
                exit;
            }

            (new ProductController($config))->index();
            break;

        // =========================
        // 🏠 DEFAULT
        // =========================
        case '/':
            header("Location: /login");
            break;

        default:
            http_response_code(404);
            echo "404 - Page not found";
            break;
    }
};