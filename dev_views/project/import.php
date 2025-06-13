<!DOCTYPE html>
<html>
<head>
    <title><?= t('Import Project') ?> - DevManager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="card">
            <div class="card-header">
                <h5><?= t('Import Project') ?></h5>
            </div>
            <div class="card-body">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label"><?= t('Select Project Archive') ?></label>
                        <input type="file" name="project_zip" class="form-control" accept=".zip" required>
                        <div class="form-text"><?= t('Only ZIP archives containing HTML, CSS, JS, PHP, or TXT files are allowed') ?></div>
                    </div>
                    <button type="submit" class="btn btn-primary"><?= t('Import') ?></button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
