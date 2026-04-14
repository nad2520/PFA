<?php

require_once __DIR__ . '/HomeController.php';
require_once __DIR__ . '/AuthController.php';
require_once __DIR__ . '/UserController.php';
require_once __DIR__ . '/AdminController.php';

class Router
{
    public function dispatch(): void
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
        if ($basePath !== '' && strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }

        $path = '/' . trim($uri, '/');

        if ($path === '/' || $path === '/index.php' || $path === '/index.html') {
            (new HomeController())->index();
            return;
        }

        switch ($path) {
            case '/user_page/auth.html':
                (new AuthController())->handle();
                return;
            case '/user_page/index.html':
                (new UserController())->index();
                return;
            case '/user_page/profile.html':
                (new UserController())->profile();
                return;
            case '/user_page/store.html':
                (new UserController())->store();
                return;
            case '/user_page/book-detail.html':
                (new UserController())->bookDetail();
                return;
            case '/user_page/read-book.html':
                (new UserController())->readBook();
                return;
            case '/admin':
                (new AdminController())->index();
                return;
            case '/admin/users':
                (new AdminController())->users();
                return;
            case '/admin/books':
                (new AdminController())->books();
                return;
            case '/landing_page/index.html':
                (new HomeController())->index();
                return;
            default:
                $this->render404();
                return;
        }
    }

    private function render404(): void
    {
        header('HTTP/1.1 404 Not Found');
        include __DIR__ . '/../view/not_found.php';
    }
}
