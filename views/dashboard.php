<h2><?= $t['dashboard'] ?? 'Мои проекты' ?></h2>
<a href="/?route=project&action=create" class="btn btn-success mb-3"><?= $t['create_project'] ?? 'Создать проект' ?></a>
<a href="/?route=project&action=import" class="btn btn-outline-secondary mb-3"><?= $t['import_project'] ?? 'Импортировать проект' ?></a>
<table class="table">
    <thead>
        <tr>
            <th><?= $t['project_title'] ?? 'Название' ?></th>
            <th><?= $t['created_at'] ?? 'Создан' ?></th>
            <th><?= $t['actions'] ?? 'Действия' ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($user_projects as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['title']) ?></td>
                <td><?= htmlspecialchars($p['created_at']) ?></td>
                <td>
                    <a href="/?route=project&action=preview&id=<?= $p['id'] ?>" class="btn btn-secondary btn-sm" target="_blank"><?= $t['preview'] ?? 'Предпросмотр' ?></a>
                    <a href="/?route=project&action=view&id=<?= $p['id'] ?>" class="btn btn-info btn-sm"><?= $t['view'] ?? 'Просмотр' ?></a>
                    <a href="/?route=project&action=edit&id=<?= $p['id'] ?>" class="btn btn-primary btn-sm"><?= $t['edit'] ?? 'Редактировать' ?></a>
                    <a href="/?route=project&action=autotest&id=<?= $p['id'] ?>" class="btn btn-warning btn-sm"><?= $t['autotest'] ?? 'Автотест' ?></a>
                    <a href="/?route=project&action=export&id=<?= $p['id'] ?>" class="btn btn-outline-success btn-sm"><?= $t['export'] ?? 'Экспорт' ?></a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
