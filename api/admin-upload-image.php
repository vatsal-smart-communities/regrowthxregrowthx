<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode(["success" => false, "message" => "Unauthorized access"]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['image'])) {
    echo json_encode(["success" => false, "message" => "No image file provided"]);
    exit();
}

$file = $_FILES['image'];

if ($file['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(["success" => false, "message" => "File upload error code: " . $file['error']]);
    exit();
}

$maxSize = 5 * 1024 * 1024; // 5 MB
if ($file['size'] > $maxSize) {
    echo json_encode(["success" => false, "message" => "File size exceeds 5MB limit"]);
    exit();
}

$allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg', 'image/gif'];
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if (!in_array($mimeType, $allowedTypes)) {
    echo json_encode(["success" => false, "message" => "Invalid file type. Only JPG, PNG, WEBP and GIF are allowed."]);
    exit();
}

$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$newFilename = time() . '_' . bin2hex(random_bytes(4)) . '.' . strtolower($ext);

$uploadDir = __DIR__ . '/../uploads/products/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$targetPath = $uploadDir . $newFilename;

if (move_uploaded_file($file['tmp_name'], $targetPath)) {
    $relativePath = 'uploads/products/' . $newFilename;
    echo json_encode([
        "success" => true,
        "image_path" => $relativePath,
        "message" => "Image uploaded successfully"
    ]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to save uploaded file on server"]);
}
?>
