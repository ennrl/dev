<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>DevManager</title>
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
        <h1><?= t('main_title') ?></h1>
        <p><?= t('welcome') ?></p>
        <div class="mb-3">
            <a href="?lang=ru" class="btn btn-outline-secondary btn-sm">RU</a>
            <a href="?lang=en" class="btn btn-outline-secondary btn-sm">EN</a>
            <a href="?lang=by" class="btn btn-outline-secondary btn-sm">BY</a>
        </div>
        <a href="?tasks=1" class="btn btn-info mb-3"><?= t('tasks') ?></a>
        <a href="?projects=1" class="btn btn-success mb-3 ms-2"><?= t('projects') ?></a>
        <?php if (isset($_SESSION['login'])): ?>
            <a href="?profile=1" class="btn btn-primary mb-3 ms-2"><?= t('profile') ?></a>
            <a href="?notifications=1" class="btn btn-warning mb-3 ms-2"><?= t('notifications') ?></a>
        <?php endif; ?>
        <?php if (isset($_SESSION['login']) && ($_SESSION['role'] ?? '') === 'admin'): ?>
            <a href="?stats=1" class="btn btn-dark mb-3 ms-2">Статистика</a>
            <a href="?users=1" class="btn btn-dark mb-3 ms-2">Пользователи</a>
        <?php endif; ?>
        <form method="post" class="mt-4" style="max-width:400px;">
            <div class="mb-3">
                <label for="login" class="form-label"><?= t('login') ?></label>
                <input type="text" class="form-control" id="login" name="login" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label"><?= t('password') ?></label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary"><?= t('signin') ?></button>
        </form>
    </div>
    <script>
        // Тема: светлая/тёмная
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
        // Инициализация темы при загрузке
        (function() {
            const theme = localStorage.getItem('dev_theme') || 'light';
            setTheme(theme);
        })();
    </script>
</body>
</html>
