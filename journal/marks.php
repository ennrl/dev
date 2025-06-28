<?php
// marks.php — выставление оценок
require 'db.php';
$users = $conn->query("SELECT * FROM users");
$lessons = $conn->query("SELECT * FROM lessons");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = (int)$_POST['user_id'];
    $lesson_id = (int)$_POST['lesson_id'];
    $mark = (int)$_POST['mark'];
    $conn->query("INSERT INTO marks (user_id, lesson_id, mark) VALUES ($user_id, $lesson_id, $mark)");
}
$marks = $conn->query("SELECT m.id, u.name, l.title, m.mark FROM marks m JOIN users u ON m.user_id=u.id JOIN lessons l ON m.lesson_id=l.id ORDER BY m.id DESC");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Оценки</title>
</head>
<body>
    <h1>Выставление оценок</h1>
    <form method="post">
        Пользователь: <select name="user_id">
            <?php while($u = $users->fetch_assoc()): ?>
                <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['name']) ?></option>
            <?php endwhile; ?>
        </select>
        Занятие: <select name="lesson_id">
            <?php while($l = $lessons->fetch_assoc()): ?>
                <option value="<?= $l['id'] ?>"><?= htmlspecialchars($l['title']) ?></option>
            <?php endwhile; ?>
        </select>
        Оценка: <input type="number" name="mark" min="1" max="5" required>
        <button type="submit">Поставить</button>
    </form>
    <h2>Список оценок</h2>
    <table border="1">
        <tr><th>ID</th><th>Пользователь</th><th>Занятие</th><th>Оценка</th></tr>
        <?php while($m = $marks->fetch_assoc()): ?>
        <tr>
            <td><?= $m['id'] ?></td>
            <td><?= htmlspecialchars($m['name']) ?></td>
            <td><?= htmlspecialchars($m['title']) ?></td>
            <td><?= $m['mark'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <p><a href="index.php">На главную</a></p>
</body>
</html>
