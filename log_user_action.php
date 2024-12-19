<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;
    $action_type = $_POST['action_type'];
    $page_url = $_POST['page_url'];

    $stmt = $conn->prepare("INSERT INTO user_activity (user_id, action_type, page_url) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $user_id, $action_type, $page_url);
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => $stmt->error]);
    }
    $stmt->close();
    $conn->close();
}
?>
