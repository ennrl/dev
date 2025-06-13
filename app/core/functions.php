<?php
function load_lang($lang = 'ru') {
    $file = __DIR__ . '/../../lang/' . $lang . '.php';
    if (file_exists($file)) {
        return include $file;
    }
    return include __DIR__ . '/../../lang/ru.php';
}

function is_admin() {
    return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
}

function is_logged_in() {
    return isset($_SESSION['user']);
}

function redirect($url) {
    header("Location: $url");
    exit;
}
