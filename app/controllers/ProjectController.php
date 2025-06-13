<?php
class ProjectController {
    public function handle() {
        if (!is_logged_in()) redirect('/?route=login');
        $action = $_GET['action'] ?? 'list';
        switch ($action) {
            case 'create':
                $this->create();
                break;
            case 'view':
                $this->view();
                break;
            case 'edit':
                $this->edit();
                break;
            case 'preview':
                $this->preview();
                break;
            // ...другие действия...
            default:
                redirect('/?route=dashboard');
        }
    }

    private function create() {
        $lang = $_GET['lang'] ?? 'ru';
        $t = load_lang($lang);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $projects = json_decode(file_get_contents(__DIR__ . '/../../data/projects.json'), true);
            $id = uniqid();
            $projects[] = [
                'id' => $id,
                'owner' => $_SESSION['user']['username'],
                'title' => $_POST['title'],
                'created_at' => date('Y-m-d H:i:s')
            ];
            file_put_contents(__DIR__ . '/../../data/projects.json', json_encode($projects));
            mkdir(__DIR__ . '/../../data/projects/' . $id, 0777, true);
            redirect('/?route=project&action=edit&id=' . $id);
        }
        $content = __DIR__ . '/../../views/project_create.php';
        include __DIR__ . '/../../views/layout.php';
    }

    private function view() {
        $lang = $_GET['lang'] ?? 'ru';
        $t = load_lang($lang);
        $id = $_GET['id'] ?? '';
        $projects = json_decode(file_get_contents(__DIR__ . '/../../data/projects.json'), true);
        $project = null;
        foreach ($projects as $p) {
            if ($p['id'] === $id) $project = $p;
        }
        if (!$project) redirect('/?route=dashboard');
        $content = __DIR__ . '/../../views/project_view.php';
        include __DIR__ . '/../../views/layout.php';
    }

    private function edit() {
        $lang = $_GET['lang'] ?? 'ru';
        $t = load_lang($lang);
        $id = $_GET['id'] ?? '';
        $projects = json_decode(file_get_contents(__DIR__ . '/../../data/projects.json'), true);
        $project = null;
        foreach ($projects as $p) {
            if ($p['id'] === $id) $project = $p;
        }
        if (!$project) redirect('/?route=dashboard');
        $content = __DIR__ . '/../../views/project_edit.php';
        include __DIR__ . '/../../views/layout.php';
    }

    private function preview() {
        $id = $_GET['id'] ?? '';
        $projects = json_decode(file_get_contents(__DIR__ . '/../../data/projects.json'), true);
        $project = null;
        foreach ($projects as $p) {
            if ($p['id'] === $id) $project = $p;
        }
        if (!$project) redirect('/?route=dashboard');
        $project_dir = __DIR__ . '/../../data/projects/' . $id;
        $index_file = $project_dir . '/index.html';
        if (file_exists($index_file)) {
            $content = file_get_contents($index_file);
            // Показываем HTML как есть
            header('Content-Type: text/html; charset=utf-8');
            echo $content;
            exit;
        } else {
            echo "<h3>index.html не найден</h3>";
            exit;
        }
    }
}
