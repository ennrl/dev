<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Задания — DevManager</title>
    <link id="theme-link" rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <style>
        body.dark-theme {
            background: #181a1b !important;
            color: #e8eaed !important;
        }
        .dark-theme .card, .dark-theme .list-group-item, .dark-theme .form-control, .dark-theme .btn, .dark-theme .modal-content {
            background-color: #23272b !important;
            color: #e8eaed !important;
            border-color: #444 !important;
        }
        .theme-toggle {
            position: absolute;
            top: 1rem;
            right: 1rem;
        }
    </style>
</head>
<body>
    <button class="btn btn-outline-secondary theme-toggle" id="theme-toggle-btn" title="Переключить тему">
        🌙
    </button>
    <div class="container mt-5">
        <h1><?= t('tasks') ?></h1>
        <a href="/" class="btn btn-secondary mb-3"><?= t('to_main') ?></a>
        <?php if (($role ?? null) === 'admin'): ?>
            <a href="?tasks_create=1" class="btn btn-success mb-3 ms-2"><?= t('create_task') ?></a>
        <?php endif; ?>
        <?php
        $projects = json_decode(file_get_contents(__DIR__ . '/../dev_data/projects.json'), true);
        ?>
        <?php if (!empty($tasks)): ?>
            <ul class="list-group">
                <?php foreach ($tasks as $task): ?>
                    <li class="list-group-item">
                        <h5><?= htmlspecialchars($task['title']) ?></h5>
                        <div><?= nl2br(htmlspecialchars($task['description'])) ?></div>
                        <div class="mt-2">
                            <strong><?= t('attached_projects') ?>:</strong>
                            <ul>
                                <?php foreach ($projects as $project): ?>
                                    <?php if (isset($project['attached_task_id']) && $project['attached_task_id'] == $task['id']): ?>
                                        <li><?= htmlspecialchars($project['title']) ?> (<?= t('owner') ?>: <?= htmlspecialchars($project['owner']) ?>)</li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <div class="alert alert-info"><?= t('no_tasks') ?></div>
        <?php endif; ?>
    </div>
    <script>
        function setTheme(theme) {
            if (theme === 'dark') {
                document.body.classList.add('dark-theme');
                document.getElementById('theme-toggle-btn').innerText = '☀️';
            } else {
                document.body.classList.remove('dark-theme');
                document.getElementById('theme-toggle-btn').innerText = '🌙';
            }
            localStorage.setItem('dev_theme', theme);
        }
        document.getElementById('theme-toggle-btn').onclick = function() {
            const theme = document.body.classList.contains('dark-theme') ? 'light' : 'dark';
            setTheme(theme);
        };
        (function() {
            const theme = localStorage.getItem('dev_theme') || 'light';
            setTheme(theme);
        })();
    </script>
</body>
</html>
