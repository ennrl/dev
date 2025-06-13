<?php
// dev_app/dev_controllers/AuthController.php

function dev_handle_login($login, $password) {
    $users = json_decode(file_get_contents(__DIR__ . '/../../dev_data/users.json'), true);
    foreach ($users as $user) {
        if ($user['login'] === $login && $user['password'] === $password) {
            $_SESSION['login'] = $login;
            $_SESSION['role'] = $user['role'];
            header('Location: /');
            exit;
        }
    }
    echo "<div class='container mt-5'><div class='alert alert-danger'>Неверный логин или пароль</div></div>";
    require_once __DIR__ . '/../../dev_views/main.php';
}