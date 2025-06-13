<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= t('import_project') ?> — DevManager</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1><?= t('import_project') ?></h1>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label"><?= t('select_zip') ?></label>
            <input type="file" name="zipfile" class="form-control" accept=".zip" required>
        </div>
        <button type="submit" class="btn btn-success"><?= t('import') ?></button>
        <a href="?projects=1" class="btn btn-secondary ms-2"><?= t('cancel') ?></a>
    </form>
</div>
</body>
</html>
