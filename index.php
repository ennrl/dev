<?php
require 'db.php';
// Получение пользователей
$users = [];
$res = $db->query('SELECT * FROM users');
while ($row = $res->fetch_assoc()) $users[] = $row;
// Получение занятий
$lessons = [];
$res = $db->query('SELECT * FROM lessons ORDER BY date');
while ($row = $res->fetch_assoc()) $lessons[] = $row;
// Получение заметок
$notes = [];
$res = $db->query('SELECT * FROM notes');
while ($row = $res->fetch_assoc()) $notes[] = $row;
// Получение оценок и посещаемости
$grades = [];
$res = $db->query('SELECT * FROM grades');
while ($row = $res->fetch_assoc()) $grades[] = $row;
$attendance = [];
$res = $db->query('SELECT * FROM attendance');
while ($row = $res->fetch_assoc()) $attendance[] = $row;

function getGrade($grades, $user_id, $lesson_id) {
    foreach ($grades as $g) {
        if ($g['user_id'] == $user_id && $g['lesson_id'] == $lesson_id) return $g['grade'];
    }
    return '';
}
function isAbsent($attendance, $user_id, $lesson_id) {
    foreach ($attendance as $a) {
        if ($a['user_id'] == $user_id && $a['lesson_id'] == $lesson_id) return $a['absent'] ? 'н' : '';
    }
    return '';
}
function getNotes($notes, $user_id) {
    $out = [];
    foreach ($notes as $n) {
        if ($n['user_id'] == $user_id) $out[] = $n['note'];
    }
    return implode('; ', $out);
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Журнал</title>
    <style>table,th,td{border:1px solid #333;border-collapse:collapse;padding:4px;}form{display:inline;}</style>
</head>
<body>
<h2>Журнал</h2>
<table>
    <tr>
        <th>Имя</th>
        <?php foreach ($lessons as $lesson): ?>
            <th><?= htmlspecialchars($lesson['date']) ?></th>
        <?php endforeach; ?>
        <th>Заметки</th>
    </tr>
    <?php foreach ($users as $user): ?>
    <tr>
        <td><?= htmlspecialchars($user['name']) ?></td>
        <?php foreach ($lessons as $lesson): ?>
            <td>
                <form method="post" action="set_grade.php">
                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                    <input type="hidden" name="lesson_id" value="<?= $lesson['id'] ?>">
                    <input type="number" name="grade" min="1" max="5" value="<?= getGrade($grades, $user['id'], $lesson['id']) ?>" style="width:35px;">
                    <button type="submit">Оц</button>
                </form>
                <form method="post" action="mark_attendance.php">
                    <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                    <input type="hidden" name="lesson_id" value="<?= $lesson['id'] ?>">
                    <input type="hidden" name="absent" value="1">
                    <button type="submit" style="background:<?= isAbsent($attendance, $user['id'], $lesson['id']) ? '#faa' : '#eee' ?>;">Н</button>
                </form>
            </td>
        <?php endforeach; ?>
        <td>
            <?= htmlspecialchars(getNotes($notes, $user['id'])) ?>
            <form method="post" action="add_note.php">
                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                <input type="text" name="note" placeholder="Заметка">
                <button type="submit">+</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<h3>Добавить пользователя</h3>
<form method="post" action="add_user.php">
    <input type="text" name="name" placeholder="Имя" required>
    <button type="submit">Добавить</button>
</form>
<h3>Добавить занятие</h3>
<form method="post" action="add_lesson.php">
    <input type="date" name="date" required>
    <button type="submit">Добавить</button>
</form>
</body>
</html>
