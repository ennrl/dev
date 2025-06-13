<h2><?= $t['import_project'] ?? 'Импортировать проект' ?></h2>
<?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<form method="post" enctype="multipart/form-data">
    <div class="mb-3">
        <label class="form-label"><?= $t['project_title'] ?? 'Название проекта' ?></label>
        <input type="text" name="title" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label"><?= $t['select_zip'] ?? 'Выберите zip-архив' ?></label>
        <input type="file" name="zipfile" class="form-control" accept=".zip" required>
    </div>
    <button type="submit" class="btn btn-primary"><?= $t['import'] ?? 'Импортировать' ?></button>
</form>
<a href="/?route=dashboard" class="btn btn-secondary mt-3"><?= $t['back'] ?? 'Назад' ?></a>
