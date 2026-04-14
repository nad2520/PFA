<?php
/**
 * Base Controller Class
 * ===============================================
 * All controllers inherit from this class
 * Provides common functionality (view rendering, redirects, etc.)
 */

class Controller {
    protected $cnx;

    public function __construct() {
        $this->cnx = Database::getInstance();
    }

    /**
     * Render a view file
     * @param string $viewPath Path relative to app/views/
     * @param array $data Variables to pass to view
     */
    protected function render($viewPath, $data = []) {
        extract($data);
        require_once __DIR__ . '/../app/views/' . $viewPath;
    }

    /**
     * Redirect to a URL
     * @param string $url URL to redirect to
     */
    protected function redirect($url) {
        header("Location: $url");
        exit;
    }

    /**
     * Return JSON response
     * @param array $data Data to return
     * @param int $statusCode HTTP status code
     */
    protected function json($data, $statusCode = 200) {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }
}
?>
