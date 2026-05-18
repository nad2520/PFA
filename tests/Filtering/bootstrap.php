<?php
declare(strict_types=1);

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__, 2));
}
if (!defined('PUBLIC_PATH')) {
    define('PUBLIC_PATH', BASE_PATH . '/public');
}
if (!defined('APP_PATH')) {
    define('APP_PATH', BASE_PATH . '/app');
}
if (!defined('CORE_PATH')) {
    define('CORE_PATH', BASE_PATH . '/core');
}
if (!defined('CONFIG_PATH')) {
    define('CONFIG_PATH', BASE_PATH . '/config');
}
if (!defined('VIEWS_PATH')) {
    define('VIEWS_PATH', APP_PATH . '/views');
}

require_once CORE_PATH . '/Database.php';
require_once CORE_PATH . '/Controller.php';
require_once CORE_PATH . '/Router.php';
require_once APP_PATH . '/models/BookModel.php';
require_once APP_PATH . '/controllers/BooksController.php';
require_once __DIR__ . '/Support/FilteringTestHelper.php';
require_once __DIR__ . '/Support/TestableBooksController.php';
