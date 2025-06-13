<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>История изменений файла — DevManager</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1><?= t('file_history') ?>: <?= htmlspecialchars($file['name']) ?></h1>
    <a href="?projects=1" class="btn btn-secondary mb-3"><?= t('back_to_projects') ?></a>
    <?php if (!empty($file['history'])): ?>
        <ul class="list-group">
            <?php
            $history = array_reverse($file['history'], true);
            $total = count($history);
            foreach ($history as $i => $ver):
            ?>
                <li class="list-group-item">
                    <div><strong><?= t('date') ?>:</strong> <?= htmlspecialchars($ver['time']) ?></div>
                    <?php if (isset($ver['author'])): ?>
                        <div><strong><?= t('author') ?>:</strong> <?= htmlspecialchars($ver['author']) ?></div>
                    <?php endif; ?>
                    <pre style="white-space: pre-wrap; background:#f8f9fa; padding:10px;"><?= htmlspecialchars($ver['content']) ?></pre>
                    <a href="?restore_file=1&project_id=<?= (int)$_GET['project_id'] ?>&file=<?= urlencode($file['name']) ?>&version=<?= $i ?>" class="btn btn-sm btn-outline-primary mt-2"><?= t('restore_this_version') ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <div class="alert alert-info"><?= t('no_history') ?></div>
    <?php endif; ?>
</div>
</body>
</html>
