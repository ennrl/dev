<?php
// attendance.php — отметка посещаемости
require 'db.php';
$users = $conn->query("SELECT * FROM users");
$lessons = $conn->query("SELECT * FROM lessons");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = (int)$_POST['user_id'];
    $lesson_id = (int)$_POST['lesson_id'];
    $present = isset($_POST['present']) ? 1 : 0;
    $conn->query("INSERT INTO attendance (user_id, lesson_id, present) VALUES ($user_id, $lesson_id, $present)");
}
$attendance = $conn->query("SELECT a.id, u.name, l.title, a.present FROM attendance a JOIN users u ON a.user_id=u.id JOIN lessons l ON a.lesson_id=l.id ORDER BY a.id DESC");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Посещаемость</title>
</head>
<body>
    <h1>Отметка посещаемости</h1>
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
        Присутствовал: <input type="checkbox" name="present" value="1">
        <button type="submit">Отметить</button>
    </form>
    <h2>Список посещаемости</h2>
    <table border="1">
        <tr><th>ID</th><th>Пользователь</th><th>Занятие</th><th>Присутствие</th></tr>
        <?php while($a = $attendance->fetch_assoc()): ?>
        <tr>
            <td><?= $a['id'] ?></td>
            <td><?= htmlspecialchars($a['name']) ?></td>
            <td><?= htmlspecialchars($a['title']) ?></td>
            <td><?= $a['present'] ? 'Да' : 'Нет' ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <p><a href="index.php">На главную</a></p>
</body>
</html>
