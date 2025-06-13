<h2><?= htmlspecialchars($project['title']) ?></h2>
<!-- ...existing code проекта... -->

<h4><?= $t['comments'] ?? 'Комментарии' ?></h4>
<?php if (is_logged_in()): ?>
<form method="post" class="mb-3">
    <div class="input-group">
        <input type="text" name="comment" class="form-control" placeholder="<?= $t['add_comment'] ?? 'Добавить комментарий' ?>" required>
        <button class="btn btn-primary" type="submit"><?= $t['send'] ?? 'Отправить' ?></button>
    </div>
</form>
<?php endif; ?>

<div>
    <?php if (!empty($comments)): ?>
        <?php foreach (array_reverse($comments) as $c): ?>
            <div class="border rounded p-2 mb-2">
                <strong><?= htmlspecialchars($c['user']) ?></strong>
                <span class="text-muted" style="font-size:0.9em"><?= htmlspecialchars($c['date']) ?></span>
                <div><?= nl2br(htmlspecialchars($c['text'])) ?></div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="text-muted"><?= $t['no_comments'] ?? 'Комментариев нет' ?></div>
    <?php endif; ?>
</div>
