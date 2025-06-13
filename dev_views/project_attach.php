<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= t('attach_project_to_task') ?> — DevManager</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1><?= t('attach_project_to_task') ?></h1>
    <?php if (empty($user_projects)): ?>
        <div class="alert alert-info"><?= t('no_user_projects_to_attach') ?></div>
    <?php else: ?>
        <form method="post">
            <div class="mb-3">
                <label class="form-label"><?= t('project') ?></label>
                <select name="project_id" class="form-select" required>
                    <?php foreach ($user_projects as $project): ?>
                        <option value="<?= $project['id'] ?>"><?= htmlspecialchars($project['title']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label"><?= t('task') ?></label>
                <select name="task_id" class="form-select" required>
                    <?php foreach ($tasks as $task): ?>
                        <option value="<?= $task['id'] ?>"><?= htmlspecialchars($task['title']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-success"><?= t('attach') ?></button>
            <a href="?projects=1" class="btn btn-secondary ms-2"><?= t('cancel') ?></a>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
