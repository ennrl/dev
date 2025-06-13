<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= t('users') ?> — DevManager</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1><?= t('users') ?></h1>
    <a href="?user_create=1" class="btn btn-success mb-3"><?= t('create_user') ?></a>
    <a href="/" class="btn btn-secondary mb-3 ms-2"><?= t('to_main') ?></a>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th><?= t('login') ?></th>
                <th><?= t('role') ?></th>
                <th><?= t('actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['login']) ?></td>
                    <td><?= htmlspecialchars($u['role']) ?></td>
                    <td>
                        <a href="?user_edit=1&login=<?= urlencode($u['login']) ?>" class="btn btn-sm btn-primary"><?= t('edit') ?></a>
                        <a href="?user_reset_password=1&login=<?= urlencode($u['login']) ?>" class="btn btn-sm btn-warning"><?= t('reset_password') ?></a>
                        <a href="?user_delete=1&login=<?= urlencode($u['login']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('<?= t('delete_user_confirm') ?>')"><?= t('delete') ?></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
