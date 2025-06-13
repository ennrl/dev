<?php
class UserController {
    public function handle() {
        if (!is_logged_in() || !is_admin()) redirect('/?route=login');
        $lang = $_GET['lang'] ?? 'ru';
        $t = load_lang($lang);

        $users = json_decode(file_get_contents(__DIR__ . '/../../data/users.json'), true);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $role = $_POST['role'] ?? 'user';
            if ($username && $password) {
                foreach ($users as $u) {
                    if ($u['username'] === $username) {
                        $error = $t['user_exists'] ?? 'Пользователь уже существует';
                        break;
                    }
                }
                if (empty($error)) {
                    $users[] = [
                        'username' => $username,
                        'password' => $password,
                        'role' => $role
                    ];
                    file_put_contents(__DIR__ . '/../../data/users.json', json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                    $success = $t['user_added'] ?? 'Пользователь добавлен';
                }
            } else {
                $error = $t['fill_fields'] ?? 'Заполните все поля';
            }
        }

        $content = __DIR__ . '/../../views/users.php';
        include __DIR__ . '/../../views/layout.php';
    }
}
