<?php

function dev_users_list() {
    dev_admin_only();
    $users = json_decode(file_get_contents(__DIR__ . '/../../dev_data/users.json'), true);
    require __DIR__ . '/../../dev_views/users.php';
}

function dev_user_create() {
    dev_admin_only();
    $error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'], $_POST['password'], $_POST['role'])) {
        $users = json_decode(file_get_contents(__DIR__ . '/../../dev_data/users.json'), true);
        foreach ($users as $u) {
            if ($u['login'] === $_POST['login']) {
                $error = 'Пользователь с таким логином уже существует';
                break;
            }
        }
        if (!$error) {
            $users[] = [
                'login' => $_POST['login'],
                'password' => $_POST['password'],
                'role' => $_POST['role']
            ];
            file_put_contents(__DIR__ . '/../../dev_data/users.json', json_encode($users, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
            header('Location: ?users=1');
            exit;
        }
    }
    require __DIR__ . '/../../dev_views/user_create.php';
}

function dev_user_edit() {
    dev_admin_only();
    $users = json_decode(file_get_contents(__DIR__ . '/../../dev_data/users.json'), true);
    $login = $_GET['login'] ?? '';
    $user = null;
    foreach ($users as $u) {
        if ($u['login'] === $login) $user = $u;
    }
    $error = '';
    if (!$user) {
        header('Location: ?users=1');
        exit;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['role'])) {
        foreach ($users as &$u) {
            if ($u['login'] === $login) {
                $u['role'] = $_POST['role'];
            }
        }
        file_put_contents(__DIR__ . '/../../dev_data/users.json', json_encode($users, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        header('Location: ?users=1');
        exit;
    }
    require __DIR__ . '/../../dev_views/user_edit.php';
}

function dev_user_delete() {
    dev_admin_only();
    $users = json_decode(file_get_contents(__DIR__ . '/../../dev_data/users.json'), true);
    $login = $_GET['login'] ?? '';
    $users = array_filter($users, fn($u) => $u['login'] !== $login);
    file_put_contents(__DIR__ . '/../../dev_data/users.json', json_encode(array_values($users), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    header('Location: ?users=1');
    exit;
}

function dev_user_reset_password() {
    dev_admin_only();
    $users = json_decode(file_get_contents(__DIR__ . '/../../dev_data/users.json'), true);
    $login = $_GET['login'] ?? '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['password'])) {
        foreach ($users as &$u) {
            if ($u['login'] === $login) {
                $u['password'] = $_POST['password'];
            }
        }
        file_put_contents(__DIR__ . '/../../dev_data/users.json', json_encode($users, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        header('Location: ?users=1');
        exit;
    }
    require __DIR__ . '/../../dev_views/user_reset_password.php';
}

function dev_admin_only() {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header('Location: /');
        exit;
    }
}
