<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/permissions.php';

requirePermission('manage_orders', true);

try {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $order_id = $data['order_id'] ?? null;
    $status = $data['status'] ?? null;
    
    if (!$order_id || !$status) {
        echo json_encode(["success" => false, "message" => "Missing required fields"]);
        exit();
    }
    
    // Validate status
    $valid_statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
    if (!in_array($status, $valid_statuses)) {
        echo json_encode(["success" => false, "message" => "Invalid status"]);
        exit();
    }
    
    // Fetch order details for email notification
    $stmtOrder = $pdo->prepare("SELECT order_number, customer_name, email, order_status FROM orders WHERE id = ?");
    $stmtOrder->execute([$order_id]);
    $order = $stmtOrder->fetch(PDO::FETCH_ASSOC);

    if ($order) {
        $stmt = $pdo->prepare("UPDATE orders SET order_status = ?, updated_at = NOW() WHERE id = ?");
        $stmt->execute([$status, $order_id]);
        
        // Send email if status has changed
        if ($order['order_status'] !== $status) {
            require_once __DIR__ . '/../includes/mailer.php';
            sendOrderStatusUpdateEmail($order['email'], $order['customer_name'], $order['order_number'], $status);
        }
    }
    
    echo json_encode(["success" => true]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
?>
