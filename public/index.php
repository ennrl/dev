<?php
session_start();
require_once __DIR__ . '/../app/core/functions.php';

$route = $_GET['route'] ?? 'login';

switch ($route) {
    case 'login':
        require_once __DIR__ . '/../app/controllers/AuthController.php';
        (new AuthController())->login();
        break;
    case 'logout':
        require_once __DIR__ . '/../app/controllers/AuthController.php';
        (new AuthController())->logout();
        break;
    case 'dashboard':
        require_once __DIR__ . '/../app/controllers/DashboardController.php';
        (new DashboardController())->index();
        break;
    case 'project':
        require_once __DIR__ . '/../app/controllers/ProjectController.php';
        (new ProjectController())->handle();
        break;
    case 'users':
        require_once __DIR__ . '/../app/controllers/UserController.php';
        (new UserController())->handle();
        break;
    // ...другие маршруты...
    default:
        header("HTTP/1.0 404 Not Found");
        echo "404 Not Found";
}
