<?php
function dev_get_lang() {
    if (isset($_GET['lang'])) {
        $_SESSION['lang'] = $_GET['lang'];
    }
    $lang = $_SESSION['lang'] ?? 'ru';
    $file = __DIR__ . '/' . $lang . '.php';
    if (!file_exists($file)) $file = __DIR__ . '/ru.php';
    return include $file;
}

function t($key) {
    static $dict = null;
    if ($dict === null) $dict = dev_get_lang();
    return $dict[$key] ?? $key;
}
