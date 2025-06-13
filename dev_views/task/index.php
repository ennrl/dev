<!DOCTYPE html>
<html>
<head>
    <title><?= t('Tasks') ?> - DevManager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between mb-4">
            <h1><?= t('Tasks') ?></h1>
            <?php if ($auth->isAdmin()): ?>
            <a href="/task/create" class="btn btn-primary"><?= t('Create Task') ?></a>
            <?php endif; ?>
        </div>

        <div class="row">
            <?php foreach ($tasks as $task): ?>
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($task['title']) ?></h5>
                        <p class="card-text"><?= nl2br(htmlspecialchars($task['description'])) ?></p>
                        <div class="mt-3">
                            <strong><?= t('Attached Projects') ?>:</strong>
                            <ul class="list-unstyled">
                                <?php foreach ($task['projects'] as $projectId): ?>
                                <?php $project = $project->getProject($projectId); ?>
                                <li><?= htmlspecialchars($project['name']) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
