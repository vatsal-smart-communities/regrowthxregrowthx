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
    
    $title = trim($data['title'] ?? '');
    $description = trim($data['description'] ?? '');
    $variant_keys = $data['variant_key'] ?? [];
    $variant_names = $data['variant_name'] ?? [];
    $price_inrs = $data['price_inr'] ?? [];
    $mrp_inrs = $data['mrp_inr'] ?? [];
    $stock_qtys = $data['stock_qty'] ?? [];
    $image_paths = $data['image_path'] ?? [];
    
    if (!$title || empty($variant_keys)) {
        echo json_encode(["success" => false, "message" => "Please fill in all required fields"]);
        exit();
    }
    
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title)));
    $slug = trim($slug, '-');
    $slug .= '-' . rand(100, 999); 
    
    $pdo->beginTransaction();
    
    // Insert Product
    $stmt = $pdo->prepare("INSERT INTO products (title, slug, description, active) VALUES (?, ?, ?, 1)");
    $stmt->execute([$title, $slug, $description]);
    $product_id = $pdo->lastInsertId();
    
    // Insert Variants
    $stmtVariant = $pdo->prepare("
        INSERT INTO product_variants 
        (product_id, variant_key, variant_name, price_inr, mrp_inr, stock_qty, image_path) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    
    for ($i = 0; $i < count($variant_keys); $i++) {
        $vk = trim($variant_keys[$i]);
        $vn = trim($variant_names[$i]);
        $p = $price_inrs[$i];
        $m = $mrp_inrs[$i];
        $s = $stock_qtys[$i] ?? 0;
        $img = !empty($image_paths[$i]) ? trim($image_paths[$i]) : 'img/product-box-bottle.jpg';
        
        if ($vk && $vn) {
            $stmtVariant->execute([
                $product_id, 
                $vk . '-' . rand(100, 999), 
                $vn, 
                $p, 
                $m, 
                $s,
                $img
            ]);
        }
    }
    
    $pdo->commit();
    
    echo json_encode(["success" => true]);
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>
