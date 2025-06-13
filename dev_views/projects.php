<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Проекты — DevManager</title>
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
        <h1>Проекты</h1>
        <a href="/" class="btn btn-secondary mb-3">На главную</a>
        <a href="?project_create=1" class="btn btn-success mb-3 ms-2">Создать проект</a>
        <a href="?project_import=1" class="btn btn-warning mb-3 ms-2">Импортировать проект</a>
        <?php
        $ratings = json_decode(file_get_contents(__DIR__ . '/../dev_data/ratings.json'), true);
        ?>
        <?php if (!empty($visible_projects)): ?>
            <ul class="list-group">
                <?php foreach ($visible_projects as $project): ?>
                    <li class="list-group-item">
                        <strong><?= htmlspecialchars($project['title']) ?></strong>
                        <span class="text-muted ms-2">(<?= t('owner') ?>: <?= htmlspecialchars($project['owner']) ?>)</span>
                        <!-- Рейтинг -->
                        <?php
                        $pratings = array_filter($ratings, fn($r) => $r['project_id'] == $project['id']);
                        $count = count($pratings);
                        $avg = $count ? round(array_sum(array_column($pratings, 'rating')) / $count, 2) : '-';
                        ?>
                        <?php
                        $user_rating = null;
                        if (isset($_SESSION['login'])) {
                            foreach ($pratings as $r) {
                                if ($r['user'] == $_SESSION['login']) $user_rating = $r['rating'];
                            }
                        }
                        ?>
                        <div class="mt-2">
                            <span><?= t('rating') ?>: <strong><?= $avg ?></strong> (<?= $count ?>)</span>
                            <?php if ($user_rating): ?>
                                <span class="ms-2"><?= t('your_rating') ?>: <?= $user_rating ?></span>
                                <a href="?remove_rating=1&project_id=<?= $project['id'] ?>" class="btn btn-sm btn-outline-danger ms-2"><?= t('remove_rating') ?></a>
                            <?php endif; ?>
                            <?php
                            $can_rate = isset($_SESSION['login']) && $project['owner'] !== $_SESSION['login']
                                && (!isset($project['members']) || !in_array($_SESSION['login'], $project['members']))
                                && !array_filter($ratings, fn($r) => $r['project_id'] == $project['id'] && $r['user'] == $_SESSION['login']);
                            ?>
                            <?php if ($can_rate): ?>
                                <form method="post" action="?rate_project=1&project_id=<?= $project['id'] ?>" class="d-inline ms-2">
                                    <select name="rating" class="form-select d-inline" style="width:auto;display:inline-block;">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <option value="<?= $i ?>"><?= $i ?></option>
                                        <?php endfor; ?>
                                    </select>
                                    <button type="submit" class="btn btn-sm btn-outline-success"><?= t('rate') ?></button>
                                </form>
                            <?php endif; ?>
                        </div>
                        <!-- Участники проекта -->
                        <div class="mt-2">
                            <span><?= t('members') ?>: </span>
                            <span><?= htmlspecialchars($project['owner']) ?></span>
                            <?php if (!empty($project['members'])): ?>
                                <?php foreach ($project['members'] as $member): ?>
                                    , <span><?= htmlspecialchars($member) ?></span>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                        <!-- Форма приглашения участника -->
                        <?php if (isset($_SESSION['login']) && $project['owner'] === $_SESSION['login']): ?>
                            <form method="post" action="?add_member=1&project_id=<?= $project['id'] ?>" class="mt-2 d-flex" style="max-width:350px;">
                                <input type="text" name="member_login" class="form-control form-control-sm" placeholder="<?= t('student_login') ?>" required>
                                <button type="submit" class="btn btn-sm btn-outline-primary ms-2"><?= t('invite') ?></button>
                            </form>
                        <?php endif; ?>
                        <?php if (isset($project['members']) && in_array($_SESSION['login'], $project['members'] ?? [])): ?>
                            <a href="?leave_project=1&project_id=<?= $project['id'] ?>" class="btn btn-sm btn-outline-danger ms-2" onclick="return confirm('<?= t('leave_project_confirm') ?>')"><?= t('leave_project') ?></a>
                        <?php endif; ?>
                        <a href="?check_project=1&project_id=<?= $project['id'] ?>" class="btn btn-sm btn-outline-success ms-2">Проверить проект</a>
                        <a href="?preview_project=1&project_id=<?= $project['id'] ?>&device=desktop" class="btn btn-sm btn-outline-info ms-2" target="_blank">Предпросмотр</a>
                        <a href="?export_project=1&project_id=<?= $project['id'] ?>" class="btn btn-sm btn-outline-dark ms-2">Экспорт</a>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'student' && $project['owner'] === $_SESSION['login']): ?>
                            <a href="?attach_project=1" class="btn btn-sm btn-outline-primary ms-2">Прикрепить к заданию</a>
                        <?php endif; ?>
                        <?php if ($project['owner'] === ($_SESSION['login'] ?? '') || ($_SESSION['role'] ?? '') === 'admin'): ?>
                            <a href="?project_rename=1&project_id=<?= $project['id'] ?>" class="btn btn-sm btn-outline-secondary ms-2">Переименовать</a>
                            <a href="?project_delete=1&project_id=<?= $project['id'] ?>" class="btn btn-sm btn-outline-danger ms-2" onclick="return confirm('Удалить проект?')">Удалить</a>
                            <a href="?file_create=1&project_id=<?= $project['id'] ?>" class="btn btn-sm btn-outline-primary ms-2">Создать файл</a>
                        <?php endif; ?>
                        <div class="mt-2">
                            <strong>Файлы:</strong>
                            <ul>
                                <?php foreach ($project['files'] as $file): ?>
                                    <li>
                                        <?= htmlspecialchars($file['name']) ?>
                                        <a href="?file_edit=1&project_id=<?= $project['id'] ?>&file=<?= urlencode($file['name']) ?>" class="btn btn-sm btn-outline-dark ms-2">Редактировать</a>
                                        <a href="?file_history=1&project_id=<?= $project['id'] ?>&file=<?= urlencode($file['name']) ?>" class="btn btn-sm btn-outline-secondary ms-2">История</a>
                                        <?php if ($project['owner'] === ($_SESSION['login'] ?? '') || ($_SESSION['role'] ?? '') === 'admin'): ?>
                                            <a href="?file_rename=1&project_id=<?= $project['id'] ?>&file=<?= urlencode($file['name']) ?>" class="btn btn-sm btn-outline-secondary ms-2">Переименовать</a>
                                            <a href="?file_delete=1&project_id=<?= $project['id'] ?>&file=<?= urlencode($file['name']) ?>" class="btn btn-sm btn-outline-danger ms-2" onclick="return confirm('Удалить файл?')">Удалить</a>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <div class="alert alert-info"><?= t('no_projects') ?></div>
        <?php endif; ?>
    </div>cument.getElementById('theme-toggle-btn').onclick = function() {
    <script>me = document.body.classList.contains('dark-theme') ? 'light' : 'dark';
        function setTheme(theme) {
            if (theme === 'dark') {
                document.body.classList.add('dark-theme');tion() {
                document.getElementById('theme-toggle-btn').innerText = '☀️';onst theme = localStorage.getItem('dev_theme') || 'light';
            } else {     setTheme(theme);
                document.body.classList.remove('dark-theme'); })();
                document.getElementById('theme-toggle-btn').innerText = '🌙';    </script>












</html></body>    </script>        })();            setTheme(theme);            const theme = localStorage.getItem('dev_theme') || 'light';        (function() {        };        }            localStorage.setItem('dev_theme', theme);            }</body>
</html>
