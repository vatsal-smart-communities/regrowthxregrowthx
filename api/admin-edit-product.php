<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit();
}

require_once __DIR__ . '/../config/db.php';

try {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $product_id = $data['product_id'] ?? null;
    $title = trim($data['title'] ?? '');
    $description = trim($data['description'] ?? '');
    $active = isset($data['active']) ? (int)$data['active'] : 1;
    
    if (!$product_id || !$title) {
        echo json_encode(["success" => false, "message" => "Product ID and Title are required"]);
        exit();
    }
    
    $stmt = $pdo->prepare("UPDATE products SET title = ?, description = ?, active = ? WHERE id = ?");
    $stmt->execute([$title, $description, $active, $product_id]);
    
    echo json_encode(["success" => true]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>
