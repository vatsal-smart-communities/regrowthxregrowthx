<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . '/../config/db.php';

try {
    $stmt = $pdo->query("SELECT * FROM products WHERE active = 1");
    $products = $stmt->fetchAll();
    
    $result = [];
    foreach ($products as $product) {
        $stmt_variants = $pdo->prepare("SELECT * FROM product_variants WHERE product_id = ?");
        $stmt_variants->execute([$product['id']]);
        $variants = $stmt_variants->fetchAll();
        
        $product['variants'] = $variants;
        $result[] = $product;
    }
    
    echo json_encode([
        "success" => true,
        "products" => $result
    ]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error fetching products: " . $e->getMessage()]);
}
?>
