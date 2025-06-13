<?php
session_start();

define('ROOT_PATH', __DIR__);
require_once ROOT_PATH . '/dev_app/dev_core/Autoloader.php';
require_once ROOT_PATH . '/dev_app/dev_core/functions.php';

use DevApp\Core\Router;
use DevApp\Core\App;

// Инициализация приложения
$app = new App();
$router = new Router();

// Обработка запроса
$router->handleRequest();
