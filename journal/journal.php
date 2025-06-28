<?php
// journal.php — весь функционал в одном файле
require 'db.php';

// --- Добавление пользователя ---
if (isset($_POST['add_user'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $role = $conn->real_escape_string($_POST['role']);
    $conn->query("INSERT INTO users (name, email, role) VALUES ('$name', '$email', '$role')");
}
// --- Добавление занятия ---
if (isset($_POST['add_lesson'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $date = $conn->real_escape_string($_POST['date']);
    $conn->query("INSERT INTO lessons (title, date) VALUES ('$title', '$date')");
}
// --- Удаление пользователя ---
if (isset($_POST['delete_user'])) {
    $user_id = (int)$_POST['delete_user'];
    $conn->query("DELETE FROM marks WHERE user_id=$user_id");
    $conn->query("DELETE FROM attendance WHERE user_id=$user_id");
    $conn->query("DELETE FROM notes WHERE user_id=$user_id");
    $conn->query("DELETE FROM users WHERE id=$user_id");
}
// --- Изменение оценки или посещаемости ---
if (isset($_POST['update_cell'])) {
    $user_id = (int)$_POST['user_id'];
    $lesson_id = (int)$_POST['lesson_id'];
    $value = $_POST['cell_value'];
    if ($value === 'Н') {
        // Отметить отсутствие
        $conn->query("DELETE FROM marks WHERE user_id=$user_id AND lesson_id=$lesson_id");
        $exists = $conn->query("SELECT id FROM attendance WHERE user_id=$user_id AND lesson_id=$lesson_id")->fetch_assoc();
        if ($exists) {
            $conn->query("UPDATE attendance SET present=0 WHERE user_id=$user_id AND lesson_id=$lesson_id");
        } else {
            $conn->query("INSERT INTO attendance (user_id, lesson_id, present) VALUES ($user_id, $lesson_id, 0)");
        }
    } else {
        // Оценка (1-5)
        $mark = (int)$value;
        $conn->query("DELETE FROM attendance WHERE user_id=$user_id AND lesson_id=$lesson_id");
        $exists = $conn->query("SELECT id FROM marks WHERE user_id=$user_id AND lesson_id=$lesson_id")->fetch_assoc();
        if ($exists) {
            $conn->query("UPDATE marks SET mark=$mark WHERE user_id=$user_id AND lesson_id=$lesson_id");
        } else {
            $conn->query("INSERT INTO marks (user_id, lesson_id, mark) VALUES ($user_id, $lesson_id, $mark)");
        }
    }
}
// --- Добавление посещаемости ---
if (isset($_POST['set_attendance'])) {
    $user_id = (int)$_POST['user_id'];
    $lesson_id = (int)$_POST['lesson_id'];
    $present = isset($_POST['present']) ? 1 : 0;
    $exists = $conn->query("SELECT id FROM attendance WHERE user_id=$user_id AND lesson_id=$lesson_id")->fetch_assoc();
    if ($exists) {
        $conn->query("UPDATE attendance SET present=$present WHERE user_id=$user_id AND lesson_id=$lesson_id");
    } else {
        $conn->query("INSERT INTO attendance (user_id, lesson_id, present) VALUES ($user_id, $lesson_id, $present)");
    }
}
// --- Добавление заметки ---
if (isset($_POST['add_note'])) {
    $user_id = (int)$_POST['user_id'];
    $text = $conn->real_escape_string($_POST['text']);
    $date = date('Y-m-d');
    $conn->query("INSERT INTO notes (user_id, text, date) VALUES ($user_id, '$text', '$date')");
}
// --- Получение данных ---
$users = $conn->query("SELECT * FROM users ORDER BY id")->fetch_all(MYSQLI_ASSOC);
$lessons = $conn->query("SELECT * FROM lessons ORDER BY date")->fetch_all(MYSQLI_ASSOC);
$marks = [];
$res = $conn->query("SELECT * FROM marks");
while($row = $res->fetch_assoc()) {
    $marks[$row['user_id']][$row['lesson_id']] = $row['mark'];
}
$attendance = [];
$res = $conn->query("SELECT * FROM attendance");
while($row = $res->fetch_assoc()) {
    $attendance[$row['user_id']][$row['lesson_id']] = $row['present'];
}
$notes = $conn->query("SELECT n.*, u.name FROM notes n JOIN users u ON n.user_id=u.id ORDER BY n.id DESC");
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Журнал оценок</title>
    <style>
        table { border-collapse: collapse; }
        td, th { border: 1px solid #333; padding: 4px; text-align: center; }
        th.rotate { height: 120px; white-space: nowrap; }
        th.rotate > div { transform: translate(0px, 50px) rotate(-90deg); width: 20px; }
        input[type=number], input[type=text] { width: 40px; text-align: center; }
        .del-btn { color: red; background: none; border: none; cursor: pointer; font-weight: bold; }
    </style>
</head>
<body>
<h1>Журнал</h1>
<!-- Добавление пользователя -->
<form method="post" style="margin-bottom:10px;">
    <b>Добавить пользователя:</b>
    Имя: <input type="text" name="name" required>
    Email: <input type="email" name="email" required>
    Роль: <input type="text" name="role" required>
    <button type="submit" name="add_user">Добавить</button>
</form>
<!-- Добавление занятия -->
<form method="post" style="margin-bottom:10px;">
    <b>Добавить занятие:</b>
    Название: <input type="text" name="title" required>
    Дата: <input type="date" name="date" required>
    <button type="submit" name="add_lesson">Добавить</button>
</form>
<!-- Основная таблица журнала -->
<h2>Журнал (оценки и посещаемость)</h2>
<table>
    <tr>
        <th>№</th>
        <th>Имя</th>
        <?php foreach($lessons as $lesson): ?>
            <th class="rotate"><div><?= htmlspecialchars($lesson['title']) ?><br><?= htmlspecialchars($lesson['date']) ?></div></th>
        <?php endforeach; ?>
        <th>Удалить</th>
    </tr>
    <?php foreach($users as $i => $user): ?>
    <tr>
        <td><?= $i+1 ?></td>
        <td><?= htmlspecialchars($user['name']) ?></td>
        <?php foreach($lessons as $lesson): ?>
        <td>
            <form method="post" style="display:inline;">
                <?php
                $cell = '';
                if (isset($attendance[$user['id']][$lesson['id']]) && $attendance[$user['id']][$lesson['id']] == 0) {
                    $cell = 'Н';
                } elseif (isset($marks[$user['id']][$lesson['id']])) {
                    $cell = $marks[$user['id']][$lesson['id']];
                }
                ?>
                <input type="text" name="cell_value" value="<?= htmlspecialchars($cell) ?>" maxlength="1" pattern="[1-5Нн]" style="width:30px; text-align:center;">
                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                <input type="hidden" name="lesson_id" value="<?= $lesson['id'] ?>">
                <button type="submit" name="update_cell" style="width:30px;">OK</button>
            </form>
        </td>
        <?php endforeach; ?>
        <td>
            <form method="post" onsubmit="return confirm('Удалить пользователя?');" style="display:inline;">
                <input type="hidden" name="delete_user" value="<?= $user['id'] ?>">
                <button type="submit" class="del-btn">✖</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<!-- Заметки -->
<h2>Заметки</h2>
<form method="post">
    Пользователь: <select name="user_id">
        <?php foreach($users as $u): ?>
            <option value="<?= $u['id'] ?>"><?= htmlspecialchars($u['name']) ?></option>
        <?php endforeach; ?>
    </select>
    Текст: <input type="text" name="text" required>
    <button type="submit" name="add_note">Добавить</button>
</form>
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
</body>
</html>
