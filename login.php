<?php
include 'db_connect.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'];
    $password = $_POST['password'];

    // Проверяем логин и пароль
    $stmt = $conn->prepare("SELECT id, password, full_name FROM users WHERE login = ?");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($id, $hashed_password, $full_name);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();
        if (password_verify($password, $hashed_password)) {
            // Сохраняем данные пользователя в сессию
            $_SESSION['user_id'] = $id;
            $_SESSION['user_name'] = $full_name;

            // Перенаправляем в личный кабинет
            header("Location: profile.html");
            exit();
        } else {
            echo "Неверный пароль.";
        }
    } else {
        echo "Пользователь не найден.";
    }

    $stmt->close();
    $conn->close();
}
?>
