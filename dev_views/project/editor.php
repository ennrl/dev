<!DOCTYPE html>
<html>
<head>
    <title><?= t('Code Editor') ?> - <?= htmlspecialchars($project['name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.32.1/min/vs/loader.min.js"></script>
    <style>
        #editor { height: 600px; border: 1px solid #ccc; }
        #files { min-height: 600px; }
    </style>
</head>
<body>
    <div class="container-fluid mt-3">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header">
                        <h5><?= t('Files') ?></h5>
                    </div>
                    <div class="card-body" id="files">
                        <!-- File list will be loaded here -->
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div id="editor"></div>
            </div>
        </div>
    </div>

    <script>
        require.config({ paths: { vs: 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.32.1/min/vs' }});
        require(['vs/editor/editor.main'], function() {
            window.editor = monaco.editor.create(document.getElementById('editor'), {
                value: '',
                language: 'html',
                theme: 'vs-dark'
            });
        });
    </script>
</body>
</html>
