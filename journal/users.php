<?php
// users.php — добавление и просмотр пользователей
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $role = $conn->real_escape_string($_POST['role']);
    $conn->query("INSERT INTO users (name, email, role) VALUES ('$name', '$email', '$role')");
}
$users = $conn->query("SELECT * FROM users");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Пользователи</title>
</head>
<body>
    <h1>Пользователи</h1>
    <form method="post">
        Имя: <input type="text" name="name" required>
        Email: <input type="email" name="email" required>
        Роль: <input type="text" name="role" required>
        <button type="submit">Добавить</button>
    </form>
    <h2>Список пользователей</h2>
    <table border="1">
        <tr><th>ID</th><th>Имя</th><th>Email</th><th>Роль</th></tr>
        <?php while($u = $users->fetch_assoc()): ?>
        <tr>
            <td><?= $u['id'] ?></td>
            <td><?= htmlspecialchars($u['name']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><?= htmlspecialchars($u['role']) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <p><a href="index.php">На главную</a></p>
</body>
</html>
