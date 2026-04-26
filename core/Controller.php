<?php
abstract class Controller
{
    protected function ensureSession(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }

    /**
     * Require logged-in user. JSON 401 for /api/* requests, redirect otherwise.
     */
    protected function requireAuth(): void
    {
        $this->ensureSession();
        if (!empty($_SESSION['user_id'])) {
            return;
        }
        $uri = (string)($_SERVER['REQUEST_URI'] ?? '');
        if (str_contains($uri, '/api/')) {
            $this->json(['success' => false, 'message' => 'Unauthorized.'], 401);
        }
        $this->redirect($this->baseUrl() . '/');
    }

    protected function requireAdmin(): void
    {
        $this->requireAuth();
        $role = (string)($_SESSION['user_role'] ?? '');
        if ($role === 'admin') {
            return;
        }

        $uri = (string)($_SERVER['REQUEST_URI'] ?? '');
        if (str_contains($uri, '/api/')) {
            $this->json(['success' => false, 'message' => 'Forbidden.'], 403);
        }

        $this->redirect('user');
    }

    protected function json(array $payload, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        try {
            echo json_encode($payload, JSON_THROW_ON_ERROR | JSON_INVALID_UTF8_SUBSTITUTE);
        } catch (\JsonException) {
            echo '{"success":false,"message":"Encoding error."}';
        }
        exit;
    }

    protected function generateCsrf(): string
    {
        $this->ensureSession();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return (string)$_SESSION['csrf_token'];
    }

    /** Validate X-CSRF-Token header for mutating API calls. */
    protected function verifyCsrfHeader(): bool
    {
        $this->ensureSession();
        $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if (!is_string($token) || $token === '') {
            return false;
        }
        $expected = $_SESSION['csrf_token'] ?? '';
        return is_string($expected) && $expected !== '' && hash_equals($expected, $token);
    }

    protected function baseUrl(): string
    {
        // This project is served from http://localhost/PFA/
        // Keep it centralized to avoid accidental redirects to /dashboard.
        return '/PFA';
    }

    protected function render(string $viewPath, array $data = [], ?string $layout = 'layouts/main'): void
    {
        $viewsRoot = defined('VIEWS_PATH') ? VIEWS_PATH : (__DIR__ . '/../app/views');
        $viewFile = rtrim($viewsRoot, '/\\') . '/' . ltrim($viewPath, '/\\') . '.php';

        if (!is_file($viewFile)) {
            http_response_code(500);
            echo "View not found: " . htmlspecialchars($viewPath);
            return;
        }

        extract($data, EXTR_SKIP);

        if ($layout === null) {
            require $viewFile;
            return;
        }

        $layoutFile = rtrim($viewsRoot, '/\\') . '/' . ltrim($layout, '/\\') . '.php';
        if (!is_file($layoutFile)) {
            http_response_code(500);
            echo "Layout not found: " . htmlspecialchars($layout);
            return;
        }

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        require $layoutFile;
    }

    protected function redirect(string $path): void
    {
        // If caller provided a relative path, anchor it to the app base.
        if ($path === '') {
            $path = $this->baseUrl() . '/';
        } elseif ($path[0] !== '/') {
            $path = $this->baseUrl() . '/' . ltrim($path, '/');
        }

        header('Location: ' . $path);
        exit;
    }

    protected function redirectBack(string $fallbackPath = 'index.php?view=admin'): void
    {
        $ref = $_SERVER['HTTP_REFERER'] ?? '';
        if (is_string($ref) && $ref !== '') {
            // Only allow redirects within this app to avoid open redirect issues.
            if (strpos($ref, $this->baseUrl() . '/') !== false) {
                header('Location: ' . $ref);
                exit;
            }
        }
        $this->redirect($fallbackPath);
    }
}

