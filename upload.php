<?php
include 'db_connect.php';
session_start();

if (isset($_SESSION['user_id'])) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
        $user_id = $_SESSION['user_id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $category = $_POST['category'];
        $tags = $_POST['tags'];

        // Проверка типа файла
        $allowed_types = ['image/jpeg', 'image/png'];
        $file_type = $_FILES['image']['type'];
        if (!in_array($file_type, $allowed_types)) {
            echo "Недопустимый формат файла.";
            exit();
        }

        // Ограничение размера файла (5MB)
        $max_size = 5 * 1024 * 1024;
        if ($_FILES['image']['size'] > $max_size) {
            echo "Файл слишком большой.";
            exit();
        }

        // Путь для сохранения изображения
        $upload_dir = 'uploads/';
        $file_name = basename($_FILES['image']['name']);
        $file_path = $upload_dir . $file_name;

        // Перемещаем файл в папку uploads
        if (move_uploaded_file($_FILES['image']['tmp_name'], $file_path)) {
            // Вставка данных о изображении в базу данных
            $stmt = $conn->prepare("INSERT INTO images (user_id, title, description, image_url, category, tags) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("isssss", $user_id, $title, $description, $file_path, $category, $tags);
            if ($stmt->execute()) {
                echo "Изображение успешно загружено.";
            } else {
                echo "Ошибка при загрузке изображения.";
            }
        } else {
            echo "Ошибка загрузки файла.";
        }
    }
} else {
    echo "Вы не авторизованы.";
}
?>
