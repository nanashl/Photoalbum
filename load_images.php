<?php
include 'db_connect.php';

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$images_per_page = 10;
$offset = ($page - 1) * $images_per_page;

$query = "SELECT * FROM images LIMIT ?, ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $offset, $images_per_page);
$stmt->execute();
$result = $stmt->get_result();

$images = [];
while ($row = $result->fetch_assoc()) {
    $images[] = $row;
}

echo json_encode($images);
?>
