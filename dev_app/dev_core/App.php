<?php
namespace DevApp\Core;

class App {
    private static $instance;
    private $config;
    private $user;
    
    public function __construct() {
        self::$instance = $this;
        $this->config = $this->loadConfig();
        $this->initializeServices();
    }
    
    private function loadConfig() {
        return require ROOT_PATH . '/dev_app/dev_core/config.php';
    }
    
    private function initializeServices() {
        // Инициализация базовых сервисов
        $this->user = new Auth();
    }
    
    public static function getInstance() {
        return self::$instance;
    }
    
    public function getConfig($key = null) {
        if ($key === null) {
            return $this->config;
        }
        return $this->config[$key] ?? null;
    }
}
