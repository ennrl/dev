<h2><?= $t['create_project'] ?? 'Создать проект' ?></h2>
<form method="post">
    <div class="mb-3">
        <input type="text" name="title" class="form-control" placeholder="<?= $t['project_title'] ?? 'Название проекта' ?>" required>
    </div>
    <button type="submit" class="btn btn-success"><?= $t['create'] ?? 'Создать' ?></button>
</form>
