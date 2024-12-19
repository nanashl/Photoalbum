<?php
include 'db_connect.php'; // Подключение к базе данных

// CRUD-операции
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $action = $_POST['action'];

        // Добавление товара
        if ($action === 'create') {
            $title = $_POST['title'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $image_url = null;

            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $image_url = 'uploads/' . basename($_FILES['image']['name']);
                move_uploaded_file($_FILES['image']['tmp_name'], $image_url);
            }

            $stmt = $conn->prepare("INSERT INTO products (title, description, image_url, price) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("sssd", $title, $description, $image_url, $price);
            $stmt->execute();
            $stmt->close();
        }

        // Обновление товара
        if ($action === 'update') {
            $id = $_POST['id'];
            $title = $_POST['title'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $image_url = $_POST['existing_image'];

            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $image_url = 'uploads/' . basename($_FILES['image']['name']);
                move_uploaded_file($_FILES['image']['tmp_name'], $image_url);
            }

            $stmt = $conn->prepare("UPDATE products SET title = ?, description = ?, image_url = ?, price = ? WHERE id = ?");
            $stmt->bind_param("sssdi", $title, $description, $image_url, $price, $id);
            $stmt->execute();
            $stmt->close();
        }

        // Удаление товара
        if ($action === 'delete') {
            $id = $_POST['id'];
            $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $stmt->close();
        }
    }
}

// Получение списка товаров
$result = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
$products = $result->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ. Раздел - Каталог товаров</title>
    <link rel="stylesheet" href="style.css">
    <script src="admin.js"></script>
</head>
<body>
    <header>
        <h1>Административный раздел</h1>
    </header>

    <main>
        <!-- Форма добавления/редактирования товара -->
        <section id="productForm">
            <h2>Добавить/Редактировать товар</h2>
            <form action="admin.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" id="productId">
                <input type="hidden" name="action" id="formAction" value="create">

                <label for="title">Название:</label>
                <input type="text" id="title" name="title" required>

                <label for="description">Описание:</label>
                <textarea id="description" name="description" required></textarea>

                <label for="price">Цена:</label>
                <input type="number" step="0.01" id="price" name="price" required>

                <label for="image">Изображение:</label>
                <input type="file" id="image" name="image">
                <input type="hidden" id="existingImage" name="existing_image">

                <button type="submit">Сохранить</button>
            </form>
        </section>

        <!-- Список товаров -->
        <section id="productList">
            <h2>Список товаров</h2>
            <table>
                <thead>
                    <tr>
                        <th>Название</th>
                        <th>Описание</th>
                        <th>Цена</th>
                        <th>Изображение</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo $product['title']; ?></td>
                            <td><?php echo $product['description']; ?></td>
                            <td><?php echo $product['price']; ?> руб.</td>
                            <td>
                                <?php if ($product['image_url']): ?>
                                    <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['title']; ?>" width="100">
                                <?php endif; ?>
                            </td>
                            <td>
                                <button onclick="editProduct(<?php echo htmlspecialchars(json_encode($product)); ?>)">Редактировать</button>
                                <form action="admin.php" method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
                                    <input type="hidden" name="action" value="delete">
                                    <button type="submit" onclick="return confirm('Вы уверены?')">Удалить</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
