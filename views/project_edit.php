<h2><?= $t['edit_project'] ?? 'Редактировать проект' ?></h2>
<div class="row">
    <div class="col-md-3">
        <h5><?= $t['project_files'] ?? 'Файлы проекта' ?></h5>
        <ul class="list-group mb-3">
            <?php foreach ($files as $f): ?>
                <li class="list-group-item <?= $f === $current_file ? 'active' : '' ?>">
                    <a href="/?route=project&action=edit&id=<?= $project['id'] ?>&file=<?= urlencode($f) ?>" style="<?= $f === $current_file ? 'color:#fff' : '' ?>">
                        <?= htmlspecialchars($f) ?>
                    </a>
                    <?php if ($f !== 'index.html'): ?>
                        <form method="post" style="display:inline;">
                            <input type="hidden" name="delete_file" value="<?= htmlspecialchars($f) ?>">
                            <button type="submit" class="btn btn-danger btn-sm" title="<?= $t['delete'] ?? 'Удалить' ?>">×</button>
                        </form>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <form method="post" class="mb-3">
            <div class="input-group">
                <input type="text" name="new_file" class="form-control" placeholder="<?= $t['new_file'] ?? 'Новый файл' ?>">
                <button type="submit" class="btn btn-success"><?= $t['create'] ?? 'Создать' ?></button>
            </div>
        </form>
    </div>
    <div class="col-md-9">
        <h5><?= $t['editing'] ?? 'Редактирование' ?>: <?= htmlspecialchars($current_file) ?></h5>
        <a href="/?route=project&action=history&id=<?= $project['id'] ?>&file=<?= urlencode($current_file) ?>" class="btn btn-outline-secondary btn-sm mb-2"><?= $t['history'] ?? 'История' ?></a>
        <form method="post">
            <textarea id="file_content" name="file_content" class="form-control" rows="20"><?= htmlspecialchars($file_content) ?></textarea>
            <button type="submit" class="btn btn-primary mt-2"><?= $t['save'] ?? 'Сохранить' ?></button>
        </form>
    </div>
</div>
                theme: "vs-dark"
            });
        });
        </script>
    </div>
</div>
