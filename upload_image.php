<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_path = 'uploads/' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $image_path);

        $stmt = $conn->prepare("INSERT INTO images (user_id, title, description, image_url, category) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("issss", $user_id, $title, $description, $image_path, $category);

        if ($stmt->execute()) {
            header('Location: profile.html');
            exit();
        } else {
            echo "Ошибка загрузки изображения.";
        }
    } else {
        echo "Ошибка загрузки файла.";
    }
}
?>
