<?php
// notes.php — добавление заметок пользователям
require 'db.php';
$users = $conn->query("SELECT * FROM users");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = (int)$_POST['user_id'];
    $text = $conn->real_escape_string($_POST['text']);
    $date = date('Y-m-d');
    $conn->query("INSERT INTO notes (user_id, text, date) VALUES ($user_id, '$text', '$date')");
}
$notes = $conn->query("SELECT n.id, u.name, n.text, n.date FROM notes n JOIN users u ON n.user_id=u.id ORDER BY n.id DESC");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Заметки</title>
</head>
<body>
    <h1>Заметки пользователям</h1>
    <form method="post">
        Пользователь: <select name="user_id">
            <?php while($u = $users->fetch_assoc()): ?>
                <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['name']) ?></option>
            <?php endwhile; ?>
        </select>
        Текст заметки: <input type="text" name="text" required>
        <button type="submit">Добавить</button>
    </form>
    <h2>Список заметок</h2>
    <table border="1">
        <tr><th>ID</th><th>Пользователь</th><th>Текст</th><th>Дата</th></tr>
        <?php while($n = $notes->fetch_assoc()): ?>
        <tr>
            <td><?= $n['id'] ?></td>
            <td><?= htmlspecialchars($n['name']) ?></td>
            <td><?= htmlspecialchars($n['text']) ?></td>
            <td><?= $n['date'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <p><a href="index.php">На главную</a></p>
</body>
</html>
