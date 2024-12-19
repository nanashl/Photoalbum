<?php
include 'db_connect.php';

$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$tag_filter = isset($_GET['tag']) ? $_GET['tag'] : '';
$date_filter = isset($_GET['date']) ? $_GET['date'] : '';

// Строим запрос для получения изображений с фильтрами
$query = "SELECT * FROM images WHERE 1";

if ($category_filter) {
    $query .= " AND category = '$category_filter'";
}

if ($tag_filter) {
    $query .= " AND tags LIKE '%$tag_filter%'";
}

if ($date_filter) {
    $query .= " AND created_at >= '$date_filter'";
}

$query .= " ORDER BY created_at DESC";

// Установим лимит на 10 постов для каждой подгрузки
$limit = 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$query .= " LIMIT $limit OFFSET $offset";

$result = $conn->query($query);

$images = [];
while ($row = $result->fetch_assoc()) {
    $images[] = $row;
}

echo json_encode($images);
$conn->close();
?>
