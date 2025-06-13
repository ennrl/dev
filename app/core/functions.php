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

function add_notification($username, $message, $type = 'info') {
    $file = __DIR__ . '/../../data/notifications.json';
    $notifications = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
    $notifications[] = [
        'username' => $username,
        'message' => $message,
        'type' => $type,
        'created_at' => date('Y-m-d H:i:s'),
        'read' => false
    ];
    file_put_contents($file, json_encode($notifications, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function get_notifications($username) {
    $file = __DIR__ . '/../../data/notifications.json';
    $notifications = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
    return array_filter($notifications, fn($n) => $n['username'] === $username && !$n['read']);
}

function mark_notifications_read($username) {
    $file = __DIR__ . '/../../data/notifications.json';
    $notifications = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
    foreach ($notifications as &$n) {
        if ($n['username'] === $username) $n['read'] = true;
    }
    file_put_contents($file, json_encode($notifications, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}
