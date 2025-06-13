<h2><?= $t['create_task'] ?? 'Создать задание' ?></h2>
<form method="post">
    <div class="mb-3">
        <input type="text" name="title" class="form-control" placeholder="<?= $t['task_title'] ?? 'Название задания' ?>" required>
    </div>
    <div class="mb-3">
        <textarea name="description" class="form-control" placeholder="<?= $t['task_description'] ?? 'Описание задания' ?>" required></textarea>
    </div>
    <button type="submit" class="btn btn-success"><?= $t['create'] ?? 'Создать' ?></button>
</form>
