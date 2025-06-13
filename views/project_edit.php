<h2><?= $t['edit_project'] ?? 'Редактировать проект' ?></h2>
<div id="editor" style="height:400px;border:1px solid #ccc;"></div>
<script>
require.config({ paths: { 'vs': 'https://cdn.jsdelivr.net/npm/monaco-editor@0.44.0/min/vs' }});
require(['vs/editor/editor.main'], function() {
    monaco.editor.create(document.getElementById('editor'), {
        value: "<!-- Ваш HTML-код -->",
        language: "html",
        theme: "vs-dark"
    });
});
</script>
