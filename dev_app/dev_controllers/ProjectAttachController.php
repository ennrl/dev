<?php

function dev_attach_project() {
    if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'student') {
        header('Location: /');
        exit;
    }
    $login = $_SESSION['login'];
    $projects = json_decode(file_get_contents(__DIR__ . '/../../dev_data/projects.json'), true);
    $tasks = json_decode(file_get_contents(__DIR__ . '/../../dev_data/tasks.json'), true);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['project_id'], $_POST['task_id'])) {
        $project_id = (int)$_POST['project_id'];
        $task_id = (int)$_POST['task_id'];
        foreach ($projects as &$project) {
            if ($project['id'] == $project_id && $project['owner'] === $login) {
                $project['attached_task_id'] = $task_id;
            }
        }
        file_put_contents(__DIR__ . '/../../dev_data/projects.json', json_encode($projects, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

        // Уведомить администратора
        $users = json_decode(file_get_contents(__DIR__ . '/../../dev_data/users.json'), true);
        $notifications = json_decode(file_get_contents(__DIR__ . '/../../dev_data/notifications.json'), true);
        $now = date('c');
        foreach ($users as $u) {
            if ($u['role'] === 'admin') {
                $notifications[] = [
                    'id' => count($notifications) ? max(array_column($notifications, 'id')) + 1 : 1,
                    'user' => $u['login'],
                    'type' => 'info',
                    'message' => "Пользователь $login прикрепил проект к заданию",
                    'created_at' => $now,
                    'read' => false
                ];
            }
        }
        file_put_contents(__DIR__ . '/../../dev_data/notifications.json', json_encode($notifications, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        header('Location: ?projects=1');
        exit;
    }

    // Форма выбора задания для прикрепления
    $user_projects = array_filter($projects, function($p) use ($login) {
        return $p['owner'] === $login;
    });
    require __DIR__ . '/../../dev_views/project_attach.php';
}
