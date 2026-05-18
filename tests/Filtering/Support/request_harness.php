<?php
declare(strict_types=1);

$basePath = dirname(__DIR__, 3);

require_once $basePath . '/core/Database.php';
require_once $basePath . '/tests/Filtering/Support/FilteringTestHelper.php';

$options = [];
foreach (array_slice($argv, 1) as $argument) {
    if (!str_starts_with($argument, '--')) {
        continue;
    }

    [$key, $value] = array_pad(explode('=', substr($argument, 2), 2), 2, '');
    $options[$key] = $value;
}

$method = strtoupper((string)($options['method'] ?? 'GET'));
$uri = (string)($options['uri'] ?? '/');
$fixturePath = (string)($options['fixture'] ?? '');
$sessionPath = (string)($options['session'] ?? '');
$metaPath = (string)($options['meta'] ?? '');

if ($metaPath !== '') {
    register_shutdown_function(static function () use ($metaPath): void {
        $headers = headers_list();
        if (function_exists('xdebug_get_headers')) {
            $headers = xdebug_get_headers();
        }

        file_put_contents($metaPath, json_encode([
            'status' => http_response_code(),
            'headers' => $headers,
        ], JSON_THROW_ON_ERROR));
    });
}

$fixture = [];
if ($fixturePath !== '' && is_file($fixturePath)) {
    $raw = file_get_contents($fixturePath);
    if (is_string($raw) && $raw !== '') {
        $fixture = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
    }
}

if (is_array($fixture) && ($fixture['books'] ?? null) !== null) {
    FilteringTestHelper::setDatabasePdo(
        FilteringTestHelper::createSqliteCatalog(
            $fixture['books'],
            $fixture['book_genres'] ?? []
        )
    );
}

$sessionDir = $basePath . '/tests/Filtering/.session-cache';
if (!is_dir($sessionDir)) {
    mkdir($sessionDir, 0777, true);
}
session_save_path($sessionDir);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$_SESSION = [];
if ($sessionPath !== '' && is_file($sessionPath)) {
    $raw = file_get_contents($sessionPath);
    if (is_string($raw) && $raw !== '') {
        $session = json_decode($raw, true, 512, JSON_THROW_ON_ERROR);
        if (is_array($session)) {
            $_SESSION = $session;
        }
    }
}

$_GET = [];
$_POST = [];
$_COOKIE = [];
$_FILES = [];

$parts = parse_url($uri);
$path = (string)($parts['path'] ?? '/');
$query = (string)($parts['query'] ?? '');

parse_str($query, $_GET);

$_SERVER['REQUEST_METHOD'] = $method;
$_SERVER['REQUEST_URI'] = $uri;
$_SERVER['SCRIPT_NAME'] = '/PFA/public/index.php';
$_SERVER['HTTP_HOST'] = 'localhost';
$_SERVER['SERVER_NAME'] = 'localhost';
$_SERVER['SERVER_PORT'] = '80';
$_SERVER['HTTPS'] = 'off';

if ($method === 'POST' && isset($options['body'])) {
    parse_str((string)$options['body'], $_POST);
}

ob_start();
require $basePath . '/public/index.php';
$buffer = ob_get_clean();

if ($buffer !== false && $buffer !== '') {
    echo $buffer;
}
