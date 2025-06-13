<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= t('create_file') ?> — DevManager</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1><?= t('create_file') ?></h1>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= t('invalid_file_extension') ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label class="form-label"><?= t('filename_example') ?></label>
            <input type="text" name="filename" class="form-control" required pattern="^[a-zA-Z0-9_\-\.]+\.(html?|css|js|php)$">
        </div>
        <button type="submit" class="btn btn-success"><?= t('create') ?></button>
        <a href="?projects=1" class="btn btn-secondary ms-2"><?= t('cancel') ?></a>
    </form>
</div>
</body>
</html>
