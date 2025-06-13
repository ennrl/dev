<h2><?= $t['history'] ?? 'История изменений' ?>: <?= htmlspecialchars($current_file) ?></h2>
<?php if (empty($history)): ?>
    <div class="alert alert-info"><?= $t['no_history'] ?? 'История отсутствует' ?></div>
<?php else: ?>
    <ul class="list-group mb-3">
        <?php foreach ($history as $h): ?>
            <li class="list-group-item">
                <a href="#" onclick="showHistory('<?= htmlspecialchars($h) ?>');return false;">
                    <?= htmlspecialchars($h) ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
    <pre id="history-content" style="background:#f8f9fa;padding:10px;"></pre>
    <script>
    function showHistory(filename) {
        fetch('<?= '/data/projects/' . $id . '/.history/' ?>' + filename)
            .then(r => r.text())
            .then(txt => document.getElementById('history-content').textContent = txt);
    }
    </script>
<?php endif; ?>
<a href="/?route=project&action=edit&id=<?= $id ?>&file=<?= urlencode($current_file) ?>" class="btn btn-secondary"><?= $t['back'] ?? 'Назад' ?></a>
