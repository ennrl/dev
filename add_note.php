<?php
require 'db.php';
if (!empty($_POST['user_id']) && !empty($_POST['note'])) {
    $stmt = $db->prepare('INSERT INTO notes (user_id, note) VALUES (?, ?)');
    $stmt->execute([$_POST['user_id'], $_POST['note']]);
}
header('Location: index.php');
exit;
