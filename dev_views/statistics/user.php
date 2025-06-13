<!DOCTYPE html>
<html>
<head>
    <title><?= t('My Statistics') ?> - DevManager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1><?= t('My Statistics') ?></h1>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><?= t('Activity Overview') ?></h5>
                    </div>
                    <div class="card-body">
                        <p><?= t('Projects Created') ?>: <?= $stats['projects_created'] ?></p>
                        <p><?= t('Files Edited') ?>: <?= $stats['files_edited'] ?></p>
                        <p><?= t('Comments Added') ?>: <?= $stats['comments_added'] ?></p>
                        <p><?= t('Ratings Given') ?>: <?= $stats['ratings_given'] ?></p>
                        <?php if ($stats['last_activity']): ?>
                            <p><?= t('Last Activity') ?>: <?= date('d.m.Y H:i', $stats['last_activity']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
