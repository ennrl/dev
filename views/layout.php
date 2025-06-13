<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang ?? 'ru') ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DevManager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/monaco-editor@0.44.0/min/vs/loader.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="/?route=dashboard">DevManager</a>
        <!-- ...existing code... -->
        <?php if (is_logged_in()): ?>
            <a class="nav-link" href="/?route=tasks"><?= $t['tasks'] ?? 'Задания' ?></a>
            <?php
            $user_notifications = get_notifications($_SESSION['user']['username']);
            $unread_count = count($user_notifications);
            ?>
            <a class="nav-link position-relative" href="/?route=notifications">
                <?= $t['notifications'] ?? 'Уведомления' ?>
                <?php if ($unread_count): ?>
                    <span class="badge bg-danger"><?= $unread_count ?></span>
                <?php endif; ?>
            </a>
        <?php endif; ?>
        <?php if (is_logged_in() && is_admin()): ?>
            <a class="nav-link" href="/?route=users"><?= $t['user_management'] ?? 'Пользователи' ?></a>
        <?php endif; ?>
        <!-- ...existing code... -->
    </nav>
    <div class="container mt-4">
        <?php include $content; ?>
    </div>
</body>
</html>