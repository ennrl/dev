<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= t('create_task') ?> — DevManager</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1><?= t('create_task') ?></h1>
    <form method="post">
        <div class="mb-3">
            <label class="form-label"><?= t('task_title') ?></label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label"><?= t('task_description') ?></label>
            <textarea name="description" class="form-control" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-success"><?= t('create') ?></button>
        <a href="?tasks=1" class="btn btn-secondary ms-2"><?= t('cancel') ?></a>
    </form>
</div>
</body>
</html>
