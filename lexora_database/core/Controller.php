<?php
abstract class Controller
{
    protected array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    protected function render(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);
        $assetsUrl = $this->config['assets_url'] ?? '';
        $baseUrl = $this->config['base_url'] ?? '';
        $viewFile = __DIR__ . '/../views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            header('HTTP/1.0 404 Not Found');
            echo 'Vue introuvable : ' . htmlspecialchars($view);
            return;
        }

        require_once __DIR__ . '/../views/partials/header.php';
        require_once $viewFile;
        require_once __DIR__ . '/../views/partials/footer.php';
    }

    protected function redirect(string $route): void
    {
        header('Location: ' . $route);
        exit;
    }
}
