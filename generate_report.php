<?php
include 'db_connect.php';

$query = "
    SELECT 
        file_type, 
        COUNT(*) AS total_requests, 
        SUM(file_size) AS total_size 
    FROM server_logs 
    GROUP BY file_type
";
$result = $conn->query($query);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
?>
