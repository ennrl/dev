<h2><?= $t['attach_project'] ?? 'Прикрепить проект' ?></h2>
<form method="post">
    <div class="mb-3">
        <select name="project_id" class="form-control" required>
            <option value=""><?= $t['select_project'] ?? 'Выберите проект' ?></option>
            <?php foreach ($user_projects as $p): ?>
                <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['title']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary"><?= $t['attach'] ?? 'Прикрепить' ?></button>
</form>
