<?php

function dev_show_tasks() {
    $tasks = json_decode(file_get_contents(__DIR__ . '/../../dev_data/tasks.json'), true);
    $role = $_SESSION['role'] ?? null;
    require __DIR__ . '/../../dev_views/tasks.php';
}

function dev_create_task() {
    $role = $_SESSION['role'] ?? null;
    if ($role !== 'admin') {
        header('Location: ?tasks=1');
        exit;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'], $_POST['description'])) {
        $tasks = json_decode(file_get_contents(__DIR__ . '/../../dev_data/tasks.json'), true);
        $id = count($tasks) ? max(array_column($tasks, 'id')) + 1 : 1;
        $tasks[] = [
            'id' => $id,
            'title' => $_POST['title'],
            'description' => $_POST['description']
        ];
        file_put_contents(__DIR__ . '/../../dev_data/tasks.json', json_encode($tasks, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        // Уведомить всех учеников
        $users = json_decode(file_get_contents(__DIR__ . '/../../dev_data/users.json'), true);
        $notifications = json_decode(file_get_contents(__DIR__ . '/../../dev_data/notifications.json'), true);
        $now = date('c');
        foreach ($users as $u) {
            if ($u['role'] === 'student') {
                $notifications[] = [
                    'id' => count($notifications) ? max(array_column($notifications, 'id')) + 1 : 1,
                    'user' => $u['login'],
                    'type' => 'info',
                    'message' => 'Новое задание: ' . $_POST['title'],
                    'created_at' => $now,
                    'read' => false
                ];
            }
        }
        file_put_contents(__DIR__ . '/../../dev_data/notifications.json', json_encode($notifications, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        header('Location: ?tasks=1');
        exit;
    }
    require __DIR__ . '/../../dev_views/task_create.php';
}
