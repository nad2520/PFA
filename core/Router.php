<?php
/**
 * Router Class
 * ===============================================
 * Routes HTTP requests to appropriate controllers
 * Used with .htaccess to handle modern URL routing
 */

class Router {
    private $routes = [];

    public function __construct() {
        session_start();
    }

    /**
     * Register a GET route
     * @param string $path URL path
     * @param string $controller Controller class name
     * @param string $method Controller method to call
     */
    public function get($path, $controller, $method) {
        $this->routes['GET'][$path] = ['controller' => $controller, 'method' => $method];
    }

    /**
     * Register a POST route
     * @param string $path URL path
     * @param string $controller Controller class name
     * @param string $method Controller method to call
     */
    public function post($path, $controller, $method) {
        $this->routes['POST'][$path] = ['controller' => $controller, 'method' => $method];
    }

    /**
     * Dispatch the request to the appropriate controller
     */
    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Remove base path
        $basePath = str_replace('public/index.php', '', $_SERVER['SCRIPT_NAME']);
        $path = str_replace($basePath, '', $path);
        $path = '/' . trim($path, '/');

        // Check if route exists
        if (isset($this->routes[$method][$path])) {
            $route = $this->routes[$method][$path];
            $controllerName = $route['controller'];
            $methodName = $route['method'];

            // Include controller file
            $controllerFile = __DIR__ . '/../app/controllers/' . $controllerName . '.php';
            if (file_exists($controllerFile)) {
                require_once $controllerFile;
                $controller = new $controllerName();
                $controller->$methodName();
            } else {
                $this->notFound();
            }
        } else {
            $this->notFound();
        }
    }

    private function notFound() {
        http_response_code(404);
        echo "Page not found";
    }
}
?>
