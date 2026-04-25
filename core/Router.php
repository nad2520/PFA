<?php
class Router
{
    private array $routes = [];

    public function get(string $path, callable $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, callable $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

public function dispatch(string $method, string $path): void
{
    

    // 🚫 Prevent cache
    header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
    header("Pragma: no-cache");

    // 🔐 Protect certain paths
    $protectedPaths = [
        '/user',
        '/store',
        '/profile',
        '/api/user/profile'
    ];

    if (in_array($path, $protectedPaths) && !isset($_SESSION['user_id'])) {
        header("Location: /index.php");
        exit();
    }

    $handler = $this->routes[$method][$path] ?? null;

    if (!$handler) {
        http_response_code(404);
        echo "404 Not Found";
        return;
    }

    $handler();
}
}

