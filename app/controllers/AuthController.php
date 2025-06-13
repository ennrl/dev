<?php
class AuthController {
    public function login() {
        $lang = $_GET['lang'] ?? 'ru';
        $t = load_lang($lang);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $users = json_decode(file_get_contents(__DIR__ . '/../../data/users.json'), true);
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            foreach ($users as $user) {
                if ($user['username'] === $username && $user['password'] === $password) {
                    $_SESSION['user'] = $user;
                    redirect('/?route=dashboard');
                }
            }
            $error = $t['login_error'] ?? 'Неверный логин или пароль';
        }
        $content = __DIR__ . '/../../views/login.php';
        include __DIR__ . '/../../views/layout.php';
    }

    public function logout() {
        session_destroy();
        redirect('/?route=login');
    }
}
