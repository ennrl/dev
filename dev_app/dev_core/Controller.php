<?php
namespace DevApp\Core;

class Controller {
    protected $data = [];
    protected $layout = 'default';
    
    public function __construct() {
        $this->checkAuth();
    }
    
    protected function render($view, $data = []) {
        $this->data = array_merge($this->data, $data);
        $viewContent = $this->renderView($view);
        
        if ($this->layout) {
            echo $this->renderLayout($viewContent);
        } else {
            echo $viewContent;
        }
    }
    
    protected function renderView($view) {
        extract($this->data);
        ob_start();
        include ROOT_PATH . "/dev_views/{$view}.php";
        return ob_get_clean();
    }
    
    protected function renderLayout($content) {
        ob_start();
        include ROOT_PATH . "/dev_views/layouts/{$this->layout}.php";
        return ob_get_clean();
    }
    
    protected function redirect($url) {
        header("Location: $url");
        exit;
    }
    
    protected function checkAuth() {
        $auth = new Auth();
        $publicActions = ['login'];
        
        $action = isset($_GET['action']) ? $_GET['action'] : 'index';
        
        if (!in_array($action, $publicActions) && !$auth->getUser()) {
            $this->redirect('/login');
        }
    }
}
