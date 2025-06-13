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
            case 'autotest':
                $this->autotest();
                break;
            case 'history':
                $this->history();
                break;
            case 'export':
                $this->export();
                break;
            case 'import':
                $this->import();
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
        $comments_file = __DIR__ . '/../../data/projects/' . $id . '/comments.json';
        $comments = file_exists($comments_file) ? json_decode(file_get_contents($comments_file), true) : [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment'])) {
            $new_comment = [
                'user' => $_SESSION['user']['username'],
                'text' => trim($_POST['comment']),
                'date' => date('Y-m-d H:i:s')
            ];
            $comments[] = $new_comment;
            file_put_contents($comments_file, json_encode($comments, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
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
        $project_dir = __DIR__ . '/../../data/projects/' . $id;

        // Работа с файлами
        $current_file = $_GET['file'] ?? 'index.html';
        $filepath = realpath($project_dir . '/' . $current_file);
        if (strpos($filepath, realpath($project_dir)) !== 0) {
            $filepath = $project_dir . '/index.html';
            $current_file = 'index.html';
        }
        $file_content = file_exists($filepath) ? file_get_contents($filepath) : '';

        // Сохранение файла
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['file_content'])) {
            // Сохраняем резервную копию перед изменением
            $history_dir = $project_dir . '/.history';
            if (!is_dir($history_dir)) mkdir($history_dir, 0777, true);
            $history_file = $history_dir . '/' . $current_file . '.' . date('Ymd_His') . '.bak';
            if (file_exists($filepath)) {
                copy($filepath, $history_file);
            }
            file_put_contents($filepath, $_POST['file_content']);
            $file_content = $_POST['file_content'];
            // Уведомить владельца проекта, если редактирует не он
            if ($_SESSION['user']['username'] !== $project['owner']) {
                add_notification($project['owner'], $t['notify_file_changed'] . ': ' . $current_file, 'warning');
            }
        }

        // Создание нового файла
        if (isset($_POST['new_file']) && $_POST['new_file']) {
            $new_file = basename($_POST['new_file']);
            $new_path = $project_dir . '/' . $new_file;
            if (!file_exists($new_path)) {
                file_put_contents($new_path, '');
            }
        }

        // Удаление файла
        if (isset($_POST['delete_file']) && $_POST['delete_file']) {
            $del_file = basename($_POST['delete_file']);
            $del_path = $project_dir . '/' . $del_file;
            if (file_exists($del_path) && $del_file !== 'index.html') {
                unlink($del_path);
                if ($current_file === $del_file) {
                    $current_file = 'index.html';
                    $filepath = $project_dir . '/index.html';
                    $file_content = file_exists($filepath) ? file_get_contents($filepath) : '';
                }
            }
        }

        // Получение структуры файлов
        $files = [];
        foreach (scandir($project_dir) as $f) {
            if ($f === '.' || $f === '..') continue;
            if (is_file($project_dir . '/' . $f)) $files[] = $f;
        }

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

    private function autotest() {
        $lang = $_GET['lang'] ?? 'ru';
        $t = load_lang($lang);
        $id = $_GET['id'] ?? '';
        $project_dir = __DIR__ . '/../../data/projects/' . $id;
        $results = [];

        // Проверка HTML
        $html_file = $project_dir . '/index.html';
        if (file_exists($html_file)) {
            $html = file_get_contents($html_file);
            $results['html'] = [
                'has_h1' => (bool)preg_match('/<h1\b/i', $html),
                'has_img' => (bool)preg_match('/<img\b/i', $html),
                'has_script' => (bool)preg_match('/<script\b/i', $html),
            ];
        } else {
            $results['html'] = null;
        }

        // Проверка CSS
        $css_file = $project_dir . '/style.css';
        if (file_exists($css_file)) {
            $css = file_get_contents($css_file);
            $results['css'] = [
                'has_body_bg' => (bool)preg_match('/body\s*{[^}]*background[^:]*:/i', $css),
                'has_class' => (bool)preg_match('/\.[a-zA-Z0-9_-]+\s*{/', $css),
            ];
        } else {
            $results['css'] = null;
        }

        // Проверка JS
        $js_file = $project_dir . '/script.js';
        if (file_exists($js_file)) {
            $js = file_get_contents($js_file);
            $results['js'] = [
                'has_function' => (bool)preg_match('/function\s+[a-zA-Z0-9_]+\s*\(/', $js),
                'has_alert' => (bool)preg_match('/alert\s*\(/', $js),
            ];
        } else {
            $results['js'] = null;
        }

        // Проверяем все файлы в проекте
        $files = [];
        foreach (scandir($project_dir) as $f) {
            if ($f === '.' || $f === '..') continue;
            if (is_file($project_dir . '/' . $f)) $files[] = $f;
        }

        $content = __DIR__ . '/../../views/project_autotest.php';
        include __DIR__ . '/../../views/layout.php';
    }

    private function history() {
        $lang = $_GET['lang'] ?? 'ru';
        $t = load_lang($lang);
        $id = $_GET['id'] ?? '';
        $file = $_GET['file'] ?? '';
        $project_dir = __DIR__ . '/../../data/projects/' . $id;
        $history_dir = $project_dir . '/.history';
        $history = [];
        if (is_dir($history_dir)) {
            foreach (scandir($history_dir) as $f) {
                if (strpos($f, $file . '.') === 0 && substr($f, -4) === '.bak') {
                    $history[] = $f;
                }
            }
            rsort($history);
        }
        $current_file = $file;
        $content = __DIR__ . '/../../views/project_history.php';
        include __DIR__ . '/../../views/layout.php';
    }

    private function export() {
        $id = $_GET['id'] ?? '';
        $projects = json_decode(file_get_contents(__DIR__ . '/../../data/projects.json'), true);
        $project = null;
        foreach ($projects as $p) {
            if ($p['id'] === $id) $project = $p;
        }
        if (!$project) redirect('/?route=dashboard');
        $project_dir = __DIR__ . '/../../data/projects/' . $id;

        $zipname = 'project_' . $id . '.zip';
        $zip = new ZipArchive();
        $tmp_zip = sys_get_temp_dir() . '/' . $zipname;
        if ($zip->open($tmp_zip, ZipArchive::CREATE) === TRUE) {
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($project_dir, FilesystemIterator::SKIP_DOTS)
            );
            foreach ($files as $file) {
                $localName = substr($file, strlen($project_dir) + 1);
                $zip->addFile($file, $localName);
            }
            $zip->close();
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . $zipname . '"');
            readfile($tmp_zip);
            unlink($tmp_zip);
            exit;
        } else {
            echo "Ошибка создания архива";
            exit;
        }
    }

    private function import() {
        $lang = $_GET['lang'] ?? 'ru';
        $t = load_lang($lang);
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['zipfile'])) {
            $file = $_FILES['zipfile'];
            if ($file['error'] === UPLOAD_ERR_OK) {
                $id = uniqid();
                $project_dir = __DIR__ . '/../../data/projects/' . $id;
                mkdir($project_dir, 0777, true);
                $zip = new ZipArchive();
                if ($zip->open($file['tmp_name']) === TRUE) {
                    $zip->extractTo($project_dir);
                    $zip->close();
                    // Добавить запись о проекте
                    $projects = json_decode(file_get_contents(__DIR__ . '/../../data/projects.json'), true);
                    $projects[] = [
                        'id' => $id,
                        'owner' => $_SESSION['user']['username'],
                        'title' => $_POST['title'] ?? 'Импортированный проект',
                        'created_at' => date('Y-m-d H:i:s')
                    ];
                    file_put_contents(__DIR__ . '/../../data/projects.json', json_encode($projects, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                    redirect('/?route=dashboard');
                } else {
                    $error = $t['import_error'] ?? 'Ошибка распаковки архива';
                }
            } else {
                $error = $t['import_error'] ?? 'Ошибка загрузки файла';
            }
        }
        $content = __DIR__ . '/../../views/project_import.php';
        include __DIR__ . '/../../views/layout.php';
    }
}
