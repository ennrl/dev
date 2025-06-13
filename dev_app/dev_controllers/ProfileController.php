<?php

function dev_show_profile() {
    $users = json_decode(file_get_contents(__DIR__ . '/../../dev_data/users.json'), true);
    $projects = json_decode(file_get_contents(__DIR__ . '/../../dev_data/projects.json'), true);
    $role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
    $login = isset($_SESSION['login']) ? $_SESSION['login'] : null;

    // Если админ и указан login в GET, показываем профиль этого пользователя
    if ($role === 'admin' && isset($_GET['user'])) {
        $profile_login = $_GET['user'];
    } else {
        $profile_login = $login;
    }

    $user = null;
    foreach ($users as $u) {
        if ($u['login'] === $profile_login) {
            $user = $u;
            break;
        }
    }
    $user_projects = array_filter($projects, function($p) use ($profile_login) {
        return $p['owner'] === $profile_login;
    });
    $member_projects = array_filter($projects, function($p) use ($profile_login) {
        return isset($p['members']) && in_array($profile_login, $p['members']);
    });

    require __DIR__ . '/../../dev_views/profile.php';
}

function dev_show_stats() {
    $users = json_decode(file_get_contents(__DIR__ . '/../../dev_data/users.json'), true);
    $projects = json_decode(file_get_contents(__DIR__ . '/../../dev_data/projects.json'), true);
    // Подсчёт пользователей по ролям
    $roles = [];
    foreach ($users as $u) {
        $roles[$u['role']] = ($roles[$u['role']] ?? 0) + 1;
    }
    // Подсчёт проектов и коммитов
    $project_count = count($projects);
    $commit_count = 0;
    foreach ($projects as $project) {
        foreach ($project['files'] as $file) {
            $commit_count += isset($file['history']) ? count($file['history']) : 0;
        }
    }
    // Для комментариев — если реализовано, иначе 0
    $comment_count = 0;
    require __DIR__ . '/../../dev_views/stats.php';
}

function dev_export_stats() {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        http_response_code(403);
        exit;
    }
    $users = json_decode(file_get_contents(__DIR__ . '/../../dev_data/users.json'), true);
    $projects = json_decode(file_get_contents(__DIR__ . '/../../dev_data/projects.json'), true);
    $roles = [];
    foreach ($users as $u) {
        $roles[$u['role']] = ($roles[$u['role']] ?? 0) + 1;
    }
    $project_count = count($projects);
    $commit_count = 0;
    foreach ($projects as $project) {
        foreach ($project['files'] as $file) {
            $commit_count += isset($file['history']) ? count($file['history']) : 0;
        }
    }
    $comment_count = 0;
    header('Content-Type: application/json');
    echo json_encode([
        'roles' => $roles,
        'project_count' => $project_count,
        'commit_count' => $commit_count,
        'comment_count' => $comment_count
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    exit;
}
