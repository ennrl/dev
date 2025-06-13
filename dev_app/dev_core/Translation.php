<?php
namespace DevApp\Core;

class Translation {
    private static $instance;
    private $translations = [];
    private $currentLang = 'ru';
    
    public function __construct() {
        self::$instance = $this;
        $this->loadTranslations();
    }
    
    public static function getInstance() {
        if (!self::$instance) {
            new self();
        }
        return self::$instance;
    }
    
    public function translate($key) {
        return $this->translations[$this->currentLang][$key] ?? $key;
    }
    
    public function setLanguage($lang) {
        if (in_array($lang, ['ru', 'en', 'be'])) {
            $this->currentLang = $lang;
            $_SESSION['lang'] = $lang;
        }
    }
    
    private function loadTranslations() {
        $langFiles = glob(ROOT_PATH . '/dev_lang/*.json');
        foreach ($langFiles as $file) {
            $lang = basename($file, '.json');
            $this->translations[$lang] = json_decode(file_get_contents($file), true);
        }
        
        if (isset($_SESSION['lang'])) {
            $this->currentLang = $_SESSION['lang'];
        }
    }
}
