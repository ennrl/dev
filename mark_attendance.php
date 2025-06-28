<?php
require 'db.php';
if (!empty($_POST['user_id']) && !empty($_POST['lesson_id'])) {
    $stmt = $db->prepare('SELECT id, absent FROM attendance WHERE user_id=? AND lesson_id=?');
    $stmt->execute([$_POST['user_id'], $_POST['lesson_id']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $new_absent = $row['absent'] ? 0 : 1;
        $db->prepare('UPDATE attendance SET absent=? WHERE id=?')->execute([$new_absent, $row['id']]);
    } else {
        $db->prepare('INSERT INTO attendance (user_id, lesson_id, absent) VALUES (?, ?, 1)')->execute([$_POST['user_id'], $_POST['lesson_id']]);
    }
}
header('Location: index.php');
exit;
