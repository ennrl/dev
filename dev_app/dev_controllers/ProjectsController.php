<?php

/**
 * Показать проекты пользователя
 */
function dev_show_projects() {
    $projects = json_decode(file_get_contents(__DIR__ . '/../../dev_data/projects.json'), true);
    $user = isset($_SESSION['login']) ? $_SESSION['login'] : null;
    $role = isset($_SESSION['role']) ? $_SESSION['role'] : null;
    if ($role === 'admin') {
        $visible_projects = $projects;
    } else {
        $visible_projects = array_filter($projects, function($p) use ($user) {
            return $p['owner'] === $user;
        });
    }
    require __DIR__ . '/../../dev_views/projects.php';
}

/**
 * Создать новый проект для пользователя
 */
function dev_create_project() {
    if (!isset($_SESSION['login'])) {
        header('Location: /');
        exit;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'])) {
        $projects = json_decode(file_get_contents(__DIR__ . '/../../dev_data/projects.json'), true);
        $id = count($projects) ? max(array_column($projects, 'id')) + 1 : 1;
        $projects[] = [
            'id' => $id,
            'owner' => $_SESSION['login'],
            'title' => $_POST['title'],
            'files' => [],
            'attached_task_id' => null
        ];
        file_put_contents(__DIR__ . '/../../dev_data/projects.json', json_encode($projects, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        header('Location: ?projects=1');
        exit;
    }
    require __DIR__ . '/../../dev_views/project_create.php';
}

/**
 * Удалить проект (доступно владельцу или администратору)
 */
function dev_delete_project() {
    if (!isset($_SESSION['login']) || !isset($_GET['project_id'])) {
        header('Location: ?projects=1');
        exit;
    }
    $projects = json_decode(file_get_contents(__DIR__ . '/../../dev_data/projects.json'), true);
    $id = (int)$_GET['project_id'];
    $role = $_SESSION['role'];
    $login = $_SESSION['login'];
    $projects = array_filter($projects, function($p) use ($id, $login, $role) {
        if ($p['id'] != $id) return true;
        if ($role === 'admin') return false;
        return $p['owner'] !== $login;
    });
    file_put_contents(__DIR__ . '/../../dev_data/projects.json', json_encode(array_values($projects), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    header('Location: ?projects=1');
    exit;
}

/**
 * Переименовать проект (доступно владельцу или администратору)
 */
function dev_rename_project() {
    if (!isset($_SESSION['login']) || !isset($_GET['project_id'])) {
        header('Location: ?projects=1');
        exit;
    }
    $projects = json_decode(file_get_contents(__DIR__ . '/../../dev_data/projects.json'), true);
    $id = (int)$_GET['project_id'];
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['title'])) {
        foreach ($projects as &$p) {
            if ($p['id'] == $id && ($p['owner'] === $_SESSION['login'] || $_SESSION['role'] === 'admin')) {
                $p['title'] = $_POST['title'];
            }
        }
        file_put_contents(__DIR__ . '/../../dev_data/projects.json', json_encode($projects, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        header('Location: ?projects=1');
        exit;
    }
    $project = null;
    foreach ($projects as $p) {
        if ($p['id'] == $id) $project = $p;
    }
    require __DIR__ . '/../../dev_views/project_rename.php';
}

/**
 * Редактировать файл проекта (сохраняет историю изменений, уведомляет владельца)
 */
function dev_edit_file() {
    if (!isset($_SESSION['login']) || !isset($_GET['project_id']) || !isset($_GET['file'])) {
        header('Location: ?projects=1');
        exit;
    }
    $projects = json_decode(file_get_contents(__DIR__ . '/../../dev_data/projects.json'), true);
    $id = (int)$_GET['project_id'];
    $filename = $_GET['file'];
    $project = null;
    foreach ($projects as &$p) {
        if ($p['id'] == $id) $project = &$p;
    }
    if (!$project || ($project['owner'] !== $_SESSION['login'] && $_SESSION['role'] !== 'admin')) {
        echo "<div class='container mt-5'><div class='alert alert-danger'>Нет доступа.</div></div>";
        return;
    }
    $file = null;
    foreach ($project['files'] as &$f) {
        if ($f['name'] === $filename) $file = &$f;
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])) {
        // История изменений
        $file['history'][] = [
            'content' => $file['content'],
            'time' => date('c')
        ];
        $file['content'] = $_POST['content'];
        // Уведомление владельцу, если редактирует не он
        if ($_SESSION['login'] !== $project['owner']) {
            $notifications = json_decode(file_get_contents(__DIR__ . '/../../dev_data/notifications.json'), true);
            $notifications[] = [
                'id' => count($notifications) ? max(array_column($notifications, 'id')) + 1 : 1,
                'user' => $project['owner'],
                'type' => 'info',
                'message' => "Файл {$file['name']} в проекте {$project['title']} был изменён другим пользователем.",
                'created_at' => date('c'),
                'read' => false
            ];
            file_put_contents(__DIR__ . '/../../dev_data/notifications.json', json_encode($notifications, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        }
        file_put_contents(__DIR__ . '/../../dev_data/projects.json', json_encode($projects, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        header('Location: ?projects=1');
        exit;
    }
    require __DIR__ . '/../../dev_views/file_edit.php';
}

/**
 * Создать новый файл в проекте (с проверкой расширения)
 */
function dev_create_file() {
    if (!isset($_SESSION['login']) || !isset($_GET['project_id'])) {
        header('Location: ?projects=1');
        exit;
    }
    $projects = json_decode(file_get_contents(__DIR__ . '/../../dev_data/projects.json'), true);
    $id = (int)$_GET['project_id'];
    $project = null;
    foreach ($projects as &$p) {
        if ($p['id'] == $id) $project = &$p;
    }
    if (!$project || ($project['owner'] !== $_SESSION['login'] && $_SESSION['role'] !== 'admin')) {
        echo "<div class='container mt-5'><div class='alert alert-danger'>Нет доступа.</div></div>";
        return;
    }
    $error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['filename'])) {
        $filename = trim($_POST['filename']);
        if (!preg_match('/^[a-zA-Z0-9_\-\.]+\.(html?|css|js|php)$/', $filename)) {
            $error = t('invalid_file_extension');
        } else {
            foreach ($project['files'] as $f) {
                if ($f['name'] === $filename) {
                    $error = 'Файл с таким именем уже существует!';
                    break;
                }
            }
            if (!$error) {
                $project['files'][] = [
                    'name' => $filename,
                    'content' => '',
                    'history' => []
                ];
                file_put_contents(__DIR__ . '/../../dev_data/projects.json', json_encode($projects, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
                header('Location: ?projects=1');
                exit;
            }
        }
    }
    require __DIR__ . '/../../dev_views/file_create.php';
}

/**
 * Удалить файл из проекта
 */
function dev_delete_file() {
    if (!isset($_SESSION['login']) || !isset($_GET['project_id']) || !isset($_GET['file'])) {
        header('Location: ?projects=1');
        exit;
    }
    $projects = json_decode(file_get_contents(__DIR__ . '/../../dev_data/projects.json'), true);
    $id = (int)$_GET['project_id'];
    $filename = $_GET['file'];
    foreach ($projects as &$p) {
        if ($p['id'] == $id && ($p['owner'] === $_SESSION['login'] || $_SESSION['role'] === 'admin')) {
            $p['files'] = array_filter($p['files'], function($f) use ($filename) {
                return $f['name'] !== $filename;
            });
        }
    }
    file_put_contents(__DIR__ . '/../../dev_data/projects.json', json_encode($projects, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    header('Location: ?projects=1');
    exit;
}

/**
 * Переименовать файл в проекте (с проверкой расширения)
 */
function dev_rename_file() {
    if (!isset($_SESSION['login']) || !isset($_GET['project_id']) || !isset($_GET['file'])) {
        header('Location: ?projects=1');
        exit;
    }
    $projects = json_decode(file_get_contents(__DIR__ . '/../../dev_data/projects.json'), true);
    $id = (int)$_GET['project_id'];
    $filename = $_GET['file'];
    $project = null;
    foreach ($projects as &$p) {
        if ($p['id'] == $id) $project = &$p;
    }
    if (!$project || ($project['owner'] !== $_SESSION['login'] && $_SESSION['role'] !== 'admin')) {
        echo "<div class='container mt-5'><div class='alert alert-danger'>Нет доступа.</div></div>";
        return;
    }
    $file = null;
    foreach ($project['files'] as &$f) {
        if ($f['name'] === $filename) $file = &$f;
    }
    $error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['newname'])) {
        $newname = trim($_POST['newname']);
        if (!preg_match('/^[a-zA-Z0-9_\-\.]+\.(html?|css|js|php)$/', $newname)) {
            $error = 'Недопустимое имя файла или расширение!';
        } else {
            foreach ($project['files'] as $f) {
                if ($f['name'] === $newname) {
                    $error = 'Файл с таким именем уже существует!';
                    break;
                }
            }
            if (!$error) {
                $file['name'] = $newname;
                file_put_contents(__DIR__ . '/../../dev_data/projects.json', json_encode($projects, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
                header('Location: ?projects=1');
                exit;
            }
        }
    }
    require __DIR__ . '/../../dev_views/file_rename.php';
}

/**
 * Просмотр истории изменений файла
 */
function dev_file_history() {
    if (!isset($_SESSION['login']) || !isset($_GET['project_id']) || !isset($_GET['file'])) {
        header('Location: ?projects=1');
        exit;
    }
    $projects = json_decode(file_get_contents(__DIR__ . '/../../dev_data/projects.json'), true);
    $id = (int)$_GET['project_id'];
    $filename = $_GET['file'];
    $project = null;
    foreach ($projects as $p) {
        if ($p['id'] == $id) $project = $p;
    }
    if (!$project || ($project['owner'] !== $_SESSION['login'] && $_SESSION['role'] !== 'admin')) {
        echo "<div class='container mt-5'><div class='alert alert-danger'>Нет доступа.</div></div>";
        return;
    }
    $file = null;
    foreach ($project['files'] as $f) {
        if ($f['name'] === $filename) $file = $f;
    }
    require __DIR__ . '/../../dev_views/file_history.php';
}

/**
 * Предпросмотр проекта (открывает index.html)
 */
function dev_preview_project() {
    if (!isset($_GET['project_id'])) {
        header('Location: ?projects=1');
        exit;
    }
    $project_id = (int)$_GET['project_id'];
    $projects = json_decode(file_get_contents(__DIR__ . '/../../dev_data/projects.json'), true);
    $project = null;
    foreach ($projects as $p) {
        if ($p['id'] == $project_id) {
            $project = $p;
            break;
        }
    }
    if (!$project) {
        echo "<div class='container mt-5'><div class='alert alert-danger'>Проект не найден.</div></div>";
        return;
    }
    // Сохраняем все файлы проекта во временную папку для iframe
    $tmpdir = sys_get_temp_dir() . '/dev_preview_' . uniqid();
    mkdir($tmpdir, 0777, true);
    foreach ($project['files'] as $file) {
        file_put_contents($tmpdir . '/' . $file['name'], $file['content']);
    }
    // Определяем путь к index.html
    $index_path = $tmpdir . '/index.html';
    if (!file_exists($index_path)) {
        echo "<div class='container mt-5'><div class='alert alert-warning'>В проекте нет index.html</div></div>";
        return;
    }
    // Передаём путь к index.html и id временной папки во view
    $preview_url = '/dev_preview.php?dir=' . urlencode(basename($tmpdir));
    require __DIR__ . '/../../dev_views/project_preview.php';
}

/**
 * Экспортировать проект в zip-архив
 */
function dev_export_project() {
    if (!isset($_GET['project_id'])) {
        header('Location: ?projects=1');
        exit;
    }
    $project_id = (int)$_GET['project_id'];
    $projects = json_decode(file_get_contents(__DIR__ . '/../../dev_data/projects.json'), true);
    $project = null;
    foreach ($projects as $p) {
        if ($p['id'] == $project_id) {
            $project = $p;
            break;
        }
    }
    if (!$project) {
        header('Location: ?projects=1');
        exit;
    }
    $zip = new ZipArchive();
    $tmp = tempnam(sys_get_temp_dir(), 'devzip');
    $zip->open($tmp, ZipArchive::OVERWRITE);
    foreach ($project['files'] as $file) {
        $zip->addFromString($file['name'], $file['content']);
    }
    $zip->close();
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="project_' . $project['id'] . '.zip"');
    readfile($tmp);
    unlink($tmp);
    exit;
}

/**
 * Импортировать проект из zip-архива (с проверкой расширений)
 */
function dev_import_project() {
    if (!isset($_SESSION['login'])) {
        header('Location: /');
        exit;
    }
    $error = '';
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['zipfile'])) {
        $file = $_FILES['zipfile'];
        if ($file['error'] === UPLOAD_ERR_OK && pathinfo($file['name'], PATHINFO_EXTENSION) === 'zip') {
            $zip = new ZipArchive();
            if ($zip->open($file['tmp_name']) === true) {
                $files = [];
                for ($i = 0; $i < $zip->numFiles; $i++) {
                    $stat = $zip->statIndex($i);
                    $name = $stat['name'];
                    // Проверка расширения
                    if (!preg_match('/\.(html?|css|js|php)$/i', $name)) continue;
                    $content = $zip->getFromIndex($i);
                    $files[] = [
                        'name' => $name,
                        'content' => $content,
                        'history' => []
                    ];
                }
                $zip->close();
                if ($files) {
                    $has_index = false;
                    foreach ($files as $f) {
                        if (strtolower($f['name']) === 'index.html') $has_index = true;
                    }
                    if (!$has_index) {
                        $error = 'В архиве отсутствует index.html';
                    } else {
                        $projects = json_decode(file_get_contents(__DIR__ . '/../../dev_data/projects.json'), true);
                        $id = count($projects) ? max(array_column($projects, 'id')) + 1 : 1;
                        $projects[] = [
                            'id' => $id,
                            'owner' => $_SESSION['login'],
                            'title' => 'Импортированный проект',
                            'files' => $files,
                            'attached_task_id' => null
                        ];
                        file_put_contents(__DIR__ . '/../../dev_data/projects.json', json_encode($projects, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
                        header('Location: ?projects=1');
                        exit;
                    }
                } else {
                    $error = 'В архиве нет подходящих файлов.';
                }
            } else {
                $error = 'Ошибка чтения архива.';
            }
        } else {
            $error = 'Неверный формат файла.';
        }
    }
    require __DIR__ . '/../../dev_views/project_import.php';
}

/**
 * Поставить оценку проекту
 */
function dev_rate_project() {
    if (!isset($_SESSION['login']) || !isset($_GET['project_id']) || !isset($_POST['rating'])) {
        header('Location: ?projects=1');
        exit;
    }
    $login = $_SESSION['login'];
    $project_id = (int)$_GET['project_id'];
    $rating = (int)$_POST['rating'];
    if ($rating < 1 || $rating > 5) {
        header('Location: ?projects=1');
        exit;
    }
    $projects = json_decode(file_get_contents(__DIR__ . '/../../dev_data/projects.json'), true);
    $project = null;
    foreach ($projects as $p) {
        if ($p['id'] == $project_id) {
            $project = $p;
            break;
        }
    }
    // Нельзя оценивать свой проект или если ты участник
    if (!$project || $project['owner'] === $login || (isset($project['members']) && in_array($login, $project['members']))) {
        header('Location: ?projects=1');
        exit;
    }
    $ratings = json_decode(file_get_contents(__DIR__ . '/../../dev_data/ratings.json'), true);
    // Проверка: уже оценивал?
    foreach ($ratings as $r) {
        if ($r['project_id'] == $project_id && $r['user'] == $login) {
            header('Location: ?projects=1');
            exit;
        }
    }
    $ratings[] = [
        'project_id' => $project_id,
        'user' => $login,
        'rating' => $rating
    ];
    file_put_contents(__DIR__ . '/../../dev_data/ratings.json', json_encode($ratings, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    header('Location: ?projects=1');
    exit;
}

/**
 * Добавить участника в проект (совместная работа)
 */
function dev_add_member() {
    if (!isset($_SESSION['login']) || !isset($_GET['project_id']) || !isset($_POST['member_login'])) {
        header('Location: ?projects=1');
        exit;
    }
    $login = $_SESSION['login'];
    $project_id = (int)$_GET['project_id'];
    $member_login = trim($_POST['member_login']);
    $projects = json_decode(file_get_contents(__DIR__ . '/../../dev_data/projects.json'), true);
    foreach ($projects as &$p) {
        if ($p['id'] == $project_id && $p['owner'] === $login) {
            if (!isset($p['members'])) $p['members'] = [];
            if ($member_login !== $login && !in_array($member_login, $p['members'])) {
                $p['members'][] = $member_login;
                // Уведомление о приглашении
                if (function_exists('dev_notify_invite')) {
                    dev_notify_invite($member_login, $p['title']);
                }
            }
        }
    }
    file_put_contents(__DIR__ . '/../../dev_data/projects.json', json_encode($projects, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    header('Location: ?projects=1');
    exit;
}

/**
 * Восстановить файл из истории изменений
 */
function dev_restore_file_version() {
    if (!isset($_SESSION['login']) || !isset($_GET['project_id']) || !isset($_GET['file']) || !isset($_GET['version'])) {
        header('Location: ?projects=1');
        exit;
    }
    $projects = json_decode(file_get_contents(__DIR__ . '/../../dev_data/projects.json'), true);
    $id = (int)$_GET['project_id'];
    $filename = $_GET['file'];
    $version = (int)$_GET['version'];
    foreach ($projects as &$project) {
        if ($project['id'] == $id && (
            $project['owner'] === $_SESSION['login'] ||
            (isset($project['members']) && in_array($_SESSION['login'], $project['members'])) ||
            ($_SESSION['role'] ?? '') === 'admin'
        )) {
            foreach ($project['files'] as &$file) {
                if ($file['name'] === $filename && isset($file['history'][$version])) {
                    // Сохраняем текущую версию в историю
                    $file['history'][] = [
                        'content' => $file['content'],
                        'time' => date('c'),
                        'author' => $_SESSION['login']
                    ];
                    // Восстанавливаем выбранную версию
                    $file['content'] = $file['history'][$version]['content'];
                    file_put_contents(__DIR__ . '/../../dev_data/projects.json', json_encode($projects, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
                    header('Location: ?file_history=1&project_id=' . $id . '&file=' . urlencode($filename));
                    exit;
                }
            }
        }
    }
    header('Location: ?projects=1');
    exit;
}

/**
 * Выйти из совместного проекта (для участника)
 */
function dev_leave_project() {
    if (!isset($_SESSION['login']) || !isset($_GET['project_id'])) {
        header('Location: ?projects=1');
        exit;
    }
    $login = $_SESSION['login'];
    $projects = json_decode(file_get_contents(__DIR__ . '/../../dev_data/projects.json'), true);
    $id = (int)$_GET['project_id'];
    foreach ($projects as &$p) {
        if ($p['id'] == $id && isset($p['members'])) {
            $p['members'] = array_values(array_filter($p['members'], fn($m) => $m !== $login));
        }
    }
    file_put_contents(__DIR__ . '/../../dev_data/projects.json', json_encode($projects, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    header('Location: ?projects=1');
    exit;
}
