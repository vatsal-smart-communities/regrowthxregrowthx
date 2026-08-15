<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/permissions.php';

requirePermission('manage_products', true);

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
