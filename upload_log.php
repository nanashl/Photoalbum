<?php
include 'db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $offset = intval($_POST['offset']);
    $batch_size = 100; // Число строк за один раз

    $file_path = 'C:\xampp\htdocs\Photoalbum\log.log'; // Укажите путь к лог-файлу
    $lines = file($file_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $batch = array_slice($lines, $offset, $batch_size);

    foreach ($batch as $line) {
        // Предполагается, что лог-файл в формате "file_type file_size timestamp"
        list($file_type, $file_size, $timestamp) = explode(' ', $line);
        $stmt = $conn->prepare("INSERT INTO server_logs (file_type, file_size, timestamp) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $file_type, $file_size, $timestamp);
        $stmt->execute();
    }

    echo json_encode(['status' => 'success', 'processed' => count($batch)]);
}
?>
