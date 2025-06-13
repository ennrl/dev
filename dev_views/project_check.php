<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= t('project_check_results') ?> — DevManager</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1><?= t('project_check_results') ?>: <?= htmlspecialchars($project['title']) ?></h1>
    <a href="?projects=1" class="btn btn-secondary mb-3"><?= t('back_to_projects') ?></a>
    <?php if (!empty($results)): ?>
        <?php foreach ($results as $fname => $checks): ?>
            <div class="card mb-3">
                <div class="card-header"><?= htmlspecialchars($fname) ?></div>
                <div class="card-body">
                    <ul>
                        <?php foreach ($checks as $check => $ok): ?>
                            <li>
                                <?= htmlspecialchars($check) ?>:
                                <?php if ($ok): ?>
                                    <span class="text-success"><?= t('ok') ?></span>
                                <?php else: ?>
                                    <span class="text-danger"><?= t('no') ?></span>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-info"><?= t('no_files_to_check') ?></div>
    <?php endif; ?>
</div>
</body>
</html>
