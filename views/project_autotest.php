<h2><?= $t['autotest_results'] ?? 'Результаты автотеста' ?></h2>
<?php if ($results['html'] !== null): ?>
    <h4>HTML (index.html)</h4>
    <ul>
        <li><?= $t['check_h1'] ?? 'Есть тег <h1>' ?>: <?= $results['html']['has_h1'] ? '✔️' : '❌' ?></li>
        <li><?= $t['check_img'] ?? 'Есть тег <img>' ?>: <?= $results['html']['has_img'] ? '✔️' : '❌' ?></li>
        <li><?= $t['check_script'] ?? 'Есть тег <script>' ?>: <?= $results['html']['has_script'] ? '✔️' : '❌' ?></li>
    </ul>
<?php else: ?>
    <div class="alert alert-warning">index.html <?= $t['not_found'] ?? 'не найден' ?></div>
<?php endif; ?>

<?php if ($results['css'] !== null): ?>
    <h4>CSS (style.css)</h4>
    <ul>
        <li><?= $t['check_body_bg'] ?? 'body с background' ?>: <?= $results['css']['has_body_bg'] ? '✔️' : '❌' ?></li>
        <li><?= $t['check_class'] ?? 'Есть CSS-класс' ?>: <?= $results['css']['has_class'] ? '✔️' : '❌' ?></li>
    </ul>
<?php else: ?>
    <div class="alert alert-warning">style.css <?= $t['not_found'] ?? 'не найден' ?></div>
<?php endif; ?>

<?php if ($results['js'] !== null): ?>
    <h4>JS (script.js)</h4>
    <ul>
        <li><?= $t['check_function'] ?? 'Есть функция' ?>: <?= $results['js']['has_function'] ? '✔️' : '❌' ?></li>
        <li><?= $t['check_alert'] ?? 'Есть alert()' ?>: <?= $results['js']['has_alert'] ? '✔️' : '❌' ?></li>
    </ul>
<?php else: ?>
    <div class="alert alert-warning">script.js <?= $t['not_found'] ?? 'не найден' ?></div>
<?php endif; ?>
<a href="/?route=dashboard" class="btn btn-secondary mt-3"><?= $t['back'] ?? 'Назад' ?></a>
