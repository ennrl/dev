<?php
class DashboardController {
    public function index() {
        if (!is_logged_in()) redirect('/?route=login');
        $lang = $_GET['lang'] ?? 'ru';
        $t = load_lang($lang);
        $projects = json_decode(file_get_contents(__DIR__ . '/../../data/projects.json'), true);
        $user = $_SESSION['user'];
        if ($user['role'] === 'admin') {
            $user_projects = $projects;
        } else {
            $user_projects = array_filter($projects, fn($p) => $p['owner'] === $user['username']);
        }
        $content = __DIR__ . '/../../views/dashboard.php';
        include __DIR__ . '/../../views/layout.php';
    }
}
