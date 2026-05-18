<?php
declare(strict_types=1);

$autoload = dirname(__DIR__) . '/vendor/autoload.php';
if (!is_file($autoload)) {
    fwrite(STDERR, "Composer autoload file not found. Run `composer install` first.\n");
    exit(1);
}

require_once $autoload;

spl_autoload_register(static function (string $class): void {
    $prefixes = [
        'Tests\\Support\\' => __DIR__ . '/Support/',
        'App\\Chatbot\\' => dirname(__DIR__) . '/app/services/chatbot/',
    ];

    foreach ($prefixes as $prefix => $baseDir) {
        if (!str_starts_with($class, $prefix)) {
            continue;
        }

        $relativeClass = substr($class, strlen($prefix));
        $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';
        if (is_file($file)) {
            require_once $file;
        }
    }
});
