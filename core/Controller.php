<?php
abstract class Controller
{
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

