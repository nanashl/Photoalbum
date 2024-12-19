<?php
$host = 'localhost';
$username = 'root';  // Для XAMPP
$password = '';      // Пустой пароль по умолчанию в XAMPP
$database = 'photo_album';

// Подключаемся к базе данных
$conn = new mysqli($host, $username, $password, $database);

// Проверка на ошибку подключения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}
?>
