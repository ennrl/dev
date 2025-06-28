<?php
require 'db.php';
if (!empty($_POST['user_id']) && !empty($_POST['lesson_id']) && isset($_POST['grade'])) {
    $stmt = $db->prepare('SELECT id FROM grades WHERE user_id=? AND lesson_id=?');
    $stmt->execute([$_POST['user_id'], $_POST['lesson_id']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $db->prepare('UPDATE grades SET grade=? WHERE id=?')->execute([$_POST['grade'], $row['id']]);
    } else {
        $db->prepare('INSERT INTO grades (user_id, lesson_id, grade) VALUES (?, ?, ?)')->execute([
            $_POST['user_id'], $_POST['lesson_id'], $_POST['grade']
        ]);
    }
}
header('Location: index.php');
exit;
