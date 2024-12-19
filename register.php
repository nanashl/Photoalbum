<?php
include 'db_connect.php'; // Подключение к базе данных

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $login = $_POST['login'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Хешируем пароль
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $avatar = null;

    // Сохраняем аватар
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
        $avatar = 'uploads/' . basename($_FILES['avatar']['name']);
        move_uploaded_file($_FILES['avatar']['tmp_name'], $avatar);
    }

    // Проверяем уникальность логина и email
    $stmt = $conn->prepare("SELECT id FROM users WHERE login = ? OR email = ?");
    $stmt->bind_param("ss", $login, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "Логин или email уже используются.";
    } else {
        // Добавляем пользователя
        $stmt = $conn->prepare("INSERT INTO users (login, password, full_name, email, avatar) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $login, $password, $full_name, $email, $avatar);
        if ($stmt->execute()) {
            echo "Регистрация прошла успешно!";
        } else {
            echo "Ошибка при регистрации.";
        }
    }

    $stmt->close();
    $conn->close();
}
?>