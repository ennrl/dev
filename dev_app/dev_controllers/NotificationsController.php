<?php

function dev_show_notifications() {
    $login = isset($_SESSION['login']) ? $_SESSION['login'] : null;
    $notifications = json_decode(file_get_contents(__DIR__ . '/../../dev_data/notifications.json'), true);

    // Отметить как прочитанное
    if (isset($_GET['read']) && is_numeric($_GET['read'])) {
        foreach ($notifications as &$n) {
            if ($n['id'] == $_GET['read'] && $n['user'] === $login) {
                $n['read'] = true;
            }
        }
        file_put_contents(__DIR__ . '/../../dev_data/notifications.json', json_encode($notifications, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        header('Location: ?notifications=1');
        exit;
    }

    // Только уведомления для текущего пользователя
    $user_notifications = array_filter($notifications, function($n) use ($login) {
        return $n['user'] === $login;
    });

    require __DIR__ . '/../../dev_views/notifications.php';
}

function dev_notify_invite($user, $project_title) {
    $notifications = json_decode(file_get_contents(__DIR__ . '/../../dev_data/notifications.json'), true);
    $notifications[] = [
        'id' => count($notifications) ? max(array_column($notifications, 'id')) + 1 : 1,
        'user' => $user,
        'type' => 'info',
        'message' => t('invite_notification', ['project' => $project_title]),
        'created_at' => date('c'),
        'read' => false
    ];
    file_put_contents(__DIR__ . '/../../dev_data/notifications.json', json_encode($notifications, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}

function dev_notify_comment($user, $project_title) {
    $notifications = json_decode(file_get_contents(__DIR__ . '/../../dev_data/notifications.json'), true);
    $notifications[] = [
        'id' => count($notifications) ? max(array_column($notifications, 'id')) + 1 : 1,
        'user' => $user,
        'type' => 'info',
        'message' => t('comment_notification', ['project' => $project_title]),
        'created_at' => date('c'),
        'read' => false
    ];
    file_put_contents(__DIR__ . '/../../dev_data/notifications.json', json_encode($notifications, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
}
