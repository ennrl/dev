<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Профиль — DevManager</title>
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
    <button class="btn btn-outline-secondary theme-toggle" id="theme-toggle-btn" title="<?= t('toggle_theme') ?>">
        🌙
    </button>
    <div class="container mt-5">
        <h1><?= t('user_profile') ?></h1>
        <a href="/" class="btn btn-secondary mb-3"><?= t('to_main') ?></a>
        <?php if ($user): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($user['login']) ?></h5>
                    <p class="card-text"><?= t('role') ?>: <?= htmlspecialchars($user['role']) ?></p>
                    <?php
                    // Статистика активности
                    $project_count = count($user_projects);
                    $commit_count = 0;
                    foreach ($user_projects as $project) {
                        foreach ($project['files'] as $file) {
                            $commit_count += isset($file['history']) ? count($file['history']) : 0;
                        }
                    }
                    // Для комментариев — если реализовано, иначе 0
                    $comment_count = 0;
                    ?>
                    <ul class="list-group mt-3">
                        <li class="list-group-item"><?= t('projects_count') ?>: <strong><?= $project_count ?></strong></li>
                        <li class="list-group-item"><?= t('commits_count') ?>: <strong><?= $commit_count ?></strong></li>
                        <li class="list-group-item"><?= t('comments_count') ?>: <strong><?= $comment_count ?></strong></li>
                    </ul>
                </div>
            </div>
            <h4><?= t('user_projects') ?></h4>
            <?php if (!empty($user_projects)): ?>
                <ul class="list-group">
                    <?php foreach ($user_projects as $project): ?>
                        <li class="list-group-item"><?= htmlspecialchars($project['title']) ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <div class="alert alert-info"><?= t('no_projects') ?></div>
            <?php endif; ?>
            <?php if (!empty($member_projects)): ?>
                <h5 class="mt-4"><?= t('collab_projects') ?></h5>
                <ul class="list-group">
                    <?php foreach ($member_projects as $project): ?>
                        <li class="list-group-item"><?= htmlspecialchars($project['title']) ?> (<?= t('owner') ?>: <?= htmlspecialchars($project['owner']) ?>)</li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        <?php else: ?>
            <div class="alert alert-danger"><?= t('user_not_found') ?></div>
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
