<?php
// lessons.php — добавление и просмотр занятий
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $conn->real_escape_string($_POST['title']);
    $date = $conn->real_escape_string($_POST['date']);
    $conn->query("INSERT INTO lessons (title, date) VALUES ('$title', '$date')");
}
$lessons = $conn->query("SELECT * FROM lessons ORDER BY date DESC");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Занятия</title>
</head>
<body>
    <h1>Занятия</h1>
    <form method="post">
        Название: <input type="text" name="title" required>
        Дата: <input type="date" name="date" required>
        <button type="submit">Добавить</button>
    </form>
    <h2>Список занятий</h2>
    <table border="1">
        <tr><th>ID</th><th>Название</th><th>Дата</th></tr>
        <?php while($l = $lessons->fetch_assoc()): ?>
        <tr>
            <td><?= $l['id'] ?></td>
            <td><?= htmlspecialchars($l['title']) ?></td>
            <td><?= htmlspecialchars($l['date']) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <p><a href="index.php">На главную</a></p>
</body>
</html>
