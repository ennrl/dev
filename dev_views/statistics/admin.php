<!DOCTYPE html>
<html>
<head>
    <title><?= t('System Statistics') ?> - DevManager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><?= t('System Statistics') ?></h1>
            <a href="/statistics/export" class="btn btn-primary">
                <?= t('Export Statistics') ?>
            </a>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><?= t('Projects') ?></h5>
                    </div>
                    <div class="card-body">
                        <p><?= t('Total Projects') ?>: <?= $stats['project_create'] ?? 0 ?></p>
                        <p><?= t('Total Files Edited') ?>: <?= $stats['file_edit'] ?? 0 ?></p>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><?= t('User Activity') ?></h5>
                    </div>
                    <div class="card-body">
                        <p><?= t('Comments Added') ?>: <?= $stats['comment_add'] ?? 0 ?></p>
                        <p><?= t('Ratings Given') ?>: <?= $stats['rating_add'] ?? 0 ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
