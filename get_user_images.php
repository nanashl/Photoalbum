<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode([]);
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT title, description, image_url, category FROM images WHERE user_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$images = [];
while ($row = $result->fetch_assoc()) {
    $images[] = $row;
}

header('Content-Type: application/json');
echo json_encode($images);
?>
