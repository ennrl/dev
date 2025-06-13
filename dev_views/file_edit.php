<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= t('edit_file') ?> — DevManager</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/monaco-editor@0.44.0/min/vs/loader.js"></script>
</head>
<body>
<div class="container mt-5">
    <h1><?= t('edit_file') ?>: <?= htmlspecialchars($file['name']) ?></h1>
    <form method="post">
        <div class="mb-3">
            <label class="form-label"><?= t('file_content') ?></label>
            <textarea id="editor" name="content" class="form-control" rows="15"><?= htmlspecialchars($file['content']) ?></textarea>
        </div>
        <button type="submit" class="btn btn-success"><?= t('save') ?></button>
        <a href="?projects=1" class="btn btn-secondary ms-2"><?= t('cancel') ?></a>
    </form>
    <script>
        require.config({ paths: { 'vs': 'https://cdn.jsdelivr.net/npm/monaco-editor@0.44.0/min/vs' }});
        require(['vs/editor/editor.main'], function() {
            monaco.editor.create(document.getElementById('editor'), {
                value: document.getElementById('editor').value,
                language: '<?= pathinfo($file['name'], PATHINFO_EXTENSION) ?>',
                theme: 'vs-dark',
                automaticLayout: true
            });
        });
    </script>
</div>
</body>
</html>
