<h2><?= $t['tasks'] ?? 'Задания' ?></h2>
<?php if (is_admin()): ?>
    <a href="/?route=tasks&action=create" class="btn btn-success mb-3"><?= $t['create_task'] ?? 'Создать задание' ?></a>
<?php endif; ?>
<table class="table">
    <thead>
        <tr>
            <th><?= $t['task_title'] ?? 'Название' ?></th>
            <th><?= $t['task_description'] ?? 'Описание' ?></th>
            <th><?= $t['created_at'] ?? 'Создано' ?></th>
            <th><?= $t['attachments'] ?? 'Прикрепления' ?></th>
            <th><?= $t['actions'] ?? 'Действия' ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tasks as $task): ?>
            <tr>
                <td><?= htmlspecialchars($task['title']) ?></td>
                <td><?= htmlspecialchars($task['description']) ?></td>
                <td><?= htmlspecialchars($task['created_at']) ?></td>
                <td>
                    <?php
                    $count = 0;
                    foreach ($attachments as $a) {
                        if ($a['task_id'] === $task['id']) $count++;
                    }
                    echo $count;
                    ?>
                </td>
                <td>
                    <?php if (!is_admin()): ?>
                        <a href="/?route=tasks&action=attach&id=<?= $task['id'] ?>" class="btn btn-primary btn-sm"><?= $t['attach_project'] ?? 'Прикрепить проект' ?></a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
