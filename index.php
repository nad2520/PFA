<?php

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/controller/Router.php';

$router = new Router();
$router->dispatch();
