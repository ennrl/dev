<?php
class TaskController {
    public function handle() {
        if (!is_logged_in()) redirect('/?route=login');
        $lang = $_GET['lang'] ?? 'ru';
        $t = load_lang($lang);

        $action = $_GET['action'] ?? 'list';

        switch ($action) {
            case 'create':
                $this->create($t, $lang);
                break;
            case 'attach':
                $this->attach($t, $lang);
                break;
            default:
                $this->list($t, $lang);
        }
    }

    private function list($t, $lang) {
        $tasks = json_decode(file_get_contents(__DIR__ . '/../../data/tasks.json'), true) ?? [];
        $user = $_SESSION['user'];
        $projects = json_decode(file_get_contents(__DIR__ . '/../../data/projects.json'), true) ?? [];
        $attachments = json_decode(file_get_contents(__DIR__ . '/../../data/task_attachments.json'), true) ?? [];
        $content = __DIR__ . '/../../views/tasks.php';
        include __DIR__ . '/../../views/layout.php';
    }

    private function create($t, $lang) {
        if (!is_admin()) redirect('/?route=tasks');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $tasks = json_decode(file_get_contents(__DIR__ . '/../../data/tasks.json'), true) ?? [];
            $id = uniqid();
            $tasks[] = [
                'id' => $id,
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'created_at' => date('Y-m-d H:i:s')
            ];
            file_put_contents(__DIR__ . '/../../data/tasks.json', json_encode($tasks, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            // Уведомить всех учеников о новом задании
            $users = json_decode(file_get_contents(__DIR__ . '/../../data/users.json'), true);
            foreach ($users as $u) {
                if ($u['role'] !== 'admin') {
                    add_notification($u['username'], $t['notify_new_task'] . ': ' . $_POST['title'], 'info');
                }
            }
            redirect('/?route=tasks');
        }
        $content = __DIR__ . '/../../views/task_create.php';
        include __DIR__ . '/../../views/layout.php';
    }

    private function attach($t, $lang) {
        $task_id = $_GET['id'] ?? '';
        $user = $_SESSION['user'];
        $projects = json_decode(file_get_contents(__DIR__ . '/../../data/projects.json'), true) ?? [];
        $user_projects = array_filter($projects, fn($p) => $p['owner'] === $user['username']);
        $attachments = json_decode(file_get_contents(__DIR__ . '/../../data/task_attachments.json'), true) ?? [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $project_id = $_POST['project_id'] ?? '';
            $attachments[] = [
                'task_id' => $task_id,
                'username' => $user['username'],
                'project_id' => $project_id,
                'attached_at' => date('Y-m-d H:i:s')
            ];
            file_put_contents(__DIR__ . '/../../data/task_attachments.json', json_encode($attachments, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            // Уведомить админа о прикреплении проекта к заданию
            $users = json_decode(file_get_contents(__DIR__ . '/../../data/users.json'), true);
            foreach ($users as $u) {
                if ($u['role'] === 'admin') {
                    add_notification($u['username'], $t['notify_attach_project'] . ': ' . $user['username'], 'info');
                }
            }
            redirect('/?route=tasks');
        }
        $content = __DIR__ . '/../../views/task_attach.php';
        include __DIR__ . '/../../views/layout.php';
    }
}
