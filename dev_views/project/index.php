<!DOCTYPE html>
<html>
<head>
    <title><?= t('Projects') ?> - DevManager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-4">
            <h1><?= t('My Projects') ?></h1>
            <a href="/project/create" class="btn btn-primary"><?= t('Create Project') ?></a>
        </div>

        <div class="row">
            <?php foreach ($projects as $project): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($project['name']) ?></h5>
                        <p class="card-text">
                            <?= t('Created') ?>: <?= date('d.m.Y', $project['created_at']) ?>
                        </p>
                        <a href="/project/editor?id=<?= $project['id'] ?>" class="btn btn-primary">
                            <?= t('Open Editor') ?>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
