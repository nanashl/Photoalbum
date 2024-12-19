<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    // Проверяем, существует ли пользователь с таким email
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Генерируем ссылку для сброса пароля
        $reset_link = "http://localhost/reset_password.php?token=" . md5($email);
        echo "Ссылка для сброса пароля: " . $reset_link;
    } else {
        echo "Пользователь с таким email не найден.";
    }

    $stmt->close();
    $conn->close();
}
?>
