<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= t('delete_project') ?> — DevManager</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1><?= t('delete_project') ?></h1>
    <div class="alert alert-danger">
        <?= t('delete_project_confirm') ?>
    </div>
    <form method="post">
        <button type="submit" class="btn btn-danger"><?= t('delete') ?></button>
        <a href="?projects=1" class="btn btn-secondary ms-2"><?= t('cancel') ?></a>
    </form>
</div>
</body>
</html>
