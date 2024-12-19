<?php
include 'session_manager.php';
include 'db_connect.php';

if (!isUserLoggedIn()) {
    header("Location: index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
            echo "Изображение успешно добавлено!";
        } else {
            echo "Ошибка добавления изображения.";
        }
    } else {
        echo "Ошибка загрузки файла.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Личный кабинет</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Добро пожаловать, <?php echo getUserName(); ?></h1>
    <h2>Добавить изображение</h2>
    <form action="profile.php" method="POST" enctype="multipart/form-data">
        <label for="title">Название:</label>
        <input type="text" id="title" name="title" required>

        <label for="description">Описание:</label>
        <textarea id="description" name="description" required></textarea>

        <label for="category">Категория:</label>
        <select id="category" name="category" required>
            <option value="nature">Природа</option>
            <option value="animals">Животные</option>
            <option value="city">Город</option>
        </select>

        <label for="image">Изображение:</label>
        <input type="file" id="image" name="image" required>

        <button type="submit">Добавить</button>
    </form>
</body>
</html>
