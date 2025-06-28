<?php
// db.php — подключение к базе данных через mysqli
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'journal';

$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    die('Ошибка подключения: ' . $conn->connect_error);
}
?>
