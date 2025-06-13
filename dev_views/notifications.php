<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= t('notifications') ?> — DevManager</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1><?= t('notifications') ?></h1>
    <a href="/" class="btn btn-secondary mb-3"><?= t('to_main') ?></a>
    <?php if (!empty($user_notifications)): ?>
        <ul class="list-group">
            <?php foreach ($user_notifications as $n): ?>
                <li class="list-group-item<?= $n['read'] ? '' : ' list-group-item-warning' ?>">
                    <div>
                        <?= htmlspecialchars($n['message']) ?>
                        <span class="text-muted ms-2" style="font-size:0.9em;"><?= htmlspecialchars($n['created_at']) ?></span>
                    </div>
                    <?php if (!$n['read']): ?>
                        <a href="?notifications=1&read=<?= $n['id'] ?>" class="btn btn-sm btn-outline-success mt-2"><?= t('mark_as_read') ?></a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <div class="alert alert-info"><?= t('no_notifications') ?></div>
    <?php endif; ?>
</div>
</body>
</html>
