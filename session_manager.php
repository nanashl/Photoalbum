<?php
session_start();
function isUserLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getUserName() {
    return $_SESSION['user_name'] ?? 'Гость';
}

header('Content-Type: application/json');

$response = [
    'isLoggedIn' => isset($_SESSION['user_id']),
    'userName' => $_SESSION['user_name'] ?? null,
];

echo json_encode($response);
?>
