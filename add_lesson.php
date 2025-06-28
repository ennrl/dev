<?php
require 'db.php';
if (!empty($_POST['date'])) {
    $stmt = $db->prepare('INSERT INTO lessons (date) VALUES (?)');
    $stmt->execute([$_POST['date']]);
}
header('Location: index.php');
exit;
