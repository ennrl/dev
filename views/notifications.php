<h2><?= $t['notifications'] ?? 'Уведомления' ?></h2>
<?php if (empty($notifications)): ?>
    <div class="alert alert-info"><?= $t['no_notifications'] ?? 'Нет новых уведомлений' ?></div>
<?php else: ?>
    <ul class="list-group mb-3">
        <?php foreach ($notifications as $n): ?>
            <li class="list-group-item list-group-item-<?= $n['type'] ?>">
                <strong><?= htmlspecialchars($n['created_at']) ?>:</strong>
                <?= htmlspecialchars($n['message']) ?>
            </li>
        <?php endforeach; ?>
    </ul>
    <a href="/?route=notifications&mark_read=1" class="btn btn-primary"><?= $t['mark_read'] ?? 'Отметить как прочитанные' ?></a>
<?php endif; ?>
<a href="/?route=dashboard" class="btn btn-secondary mt-3"><?= $t['back'] ?? 'Назад' ?></a>
