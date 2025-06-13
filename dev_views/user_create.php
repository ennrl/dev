<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= t('create_user') ?> — DevManager</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1><?= t('create_user') ?></h1>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post">
        <div class="mb-3">
            <label class="form-label"><?= t('login') ?></label>
            <input type="text" name="login" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label"><?= t('password') ?></label>
            <input type="text" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label"><?= t('role') ?></label>
            <select name="role" class="form-select">
                <option value="student"><?= t('student') ?></option>
                <option value="admin"><?= t('admin') ?></option>
            </select>
        </div>
        <button type="submit" class="btn btn-success"><?= t('create') ?></button>
        <a href="?users=1" class="btn btn-secondary ms-2"><?= t('cancel') ?></a>
    </form>
</div>
</body>
</html>
