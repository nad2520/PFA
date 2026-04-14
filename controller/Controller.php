<?php

class Controller
{
    protected function render(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        $viewFile = __DIR__ . '/../view/' . $view . '.php';

        if (!file_exists($viewFile)) {
            header('HTTP/1.1 500 Internal Server Error');
            echo 'View not found: ' . htmlspecialchars($view);
            exit;
        }

        include $viewFile;
    }

    protected function redirect(string $url): void
    {
        if (strpos($url, '/') === 0 && defined('BASE_URL')) {
            $base = rtrim(BASE_URL, '/');
            if ($base !== '' && strpos($url, $base) !== 0) {
                $url = $base . $url;
            }
        }

        header('Location: ' . $url);
        exit;
    }
}
