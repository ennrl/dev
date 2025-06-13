<?php

function dev_check_project() {
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

    $results = [];
    foreach ($project['files'] as $file) {
        $name = $file['name'];
        $content = $file['content'];
        if (preg_match('/\.html?$/i', $name)) {
            $results[$name] = [
                'h1' => (bool)preg_match('/<h1\b/i', $content),
                'img' => (bool)preg_match('/<img\b/i', $content),
                'script' => (bool)preg_match('/<script\b/i', $content),
            ];
        } elseif (preg_match('/\.css$/i', $name)) {
            $results[$name] = [
                'body_bg' => (bool)preg_match('/body\s*{[^}]*background[^:]*:/is', $content),
                'class' => (bool)preg_match('/\.[a-zA-Z0-9_-]+\s*{/', $content),
            ];
        } elseif (preg_match('/\.js$/i', $name)) {
            $results[$name] = [
                'function' => (bool)preg_match('/function\s+[a-zA-Z0-9_]+\s*\(/', $content),
                'alert' => (bool)preg_match('/alert\s*\(/', $content),
            ];
        }
    }

    require __DIR__ . '/../../dev_views/project_check.php';
}
