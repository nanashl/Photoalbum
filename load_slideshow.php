<?php
$directory = "slideshow";

if (!is_dir($directory)) {
    http_response_code(404);
    echo json_encode(["error" => "Папка slideshow не найдена"]);
    exit();
}

$images = array_filter(scandir($directory), function($file) use ($directory) {
    return is_file($directory . '/' . $file) && preg_match('/\.(jpg|jpeg|png|gif)$/i', $file);
});

$imagePaths = array_map(function($file) use ($directory) {
    return $directory . '/' . $file;
}, $images);

header('Content-Type: application/json');
echo json_encode($imagePaths);
?>
