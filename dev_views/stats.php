<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= t('system_stats') ?> — DevManager</title>
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
        <h1><?= t('system_stats') ?></h1>
        <a href="/" class="btn btn-secondary mb-3"><?= t('to_main') ?></a>
        <a href="?export_stats=1" class="btn btn-outline-info mb-3"><?= t('export_stats_json') ?></a>
        <ul class="list-group mb-3">
            <?php foreach ($roles as $role => $count): ?>
                <li class="list-group-item"><?= t('users_by_role') ?> (<?= htmlspecialchars($role) ?>): <strong><?= $count ?></strong></li>
            <?php endforeach; ?>
            <li class="list-group-item"><?= t('total_projects') ?>: <strong><?= $project_count ?></strong></li>
            <li class="list-group-item"><?= t('total_commits') ?>: <strong><?= $commit_count ?></strong></li>
            <li class="list-group-item"><?= t('total_comments') ?>: <strong><?= $comment_count ?></strong></li>
        </ul>
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
