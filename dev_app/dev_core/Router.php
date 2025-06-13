<?php
namespace DevApp\Core;

class Router {
    private $routes = [
        'login' => ['controller' => 'Auth', 'action' => 'login'],
        'logout' => ['controller' => 'Auth', 'action' => 'logout'],
        'dashboard' => ['controller' => 'Dashboard', 'action' => 'index'],
        'projects' => ['controller' => 'Project', 'action' => 'index']
    ];
    
    public function handleRequest() {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $path = trim($path, '/');
        
        if (empty($path)) {
            $path = 'dashboard';
        }
        
        if (isset($this->routes[$path])) {
            $route = $this->routes[$path];
            $controllerName = "DevApp\\Controllers\\" . $route['controller'] . "Controller";
            $actionName = $route['action'] . "Action";
            
            $controller = new $controllerName();
            $controller->$actionName();
        } else {
            header("HTTP/1.0 404 Not Found");
            echo "Page not found";
        }
    }
}
