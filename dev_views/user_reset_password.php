<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= t('reset_password') ?> — DevManager</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1><?= t('reset_password') ?></h1>
    <form method="post">
        <div class="mb-3">
            <label class="form-label"><?= t('new_password') ?></label>
            <input type="text" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-warning"><?= t('reset') ?></button>
        <a href="?users=1" class="btn btn-secondary ms-2"><?= t('cancel') ?></a>
    </form>
</div>
</body>
</html>
