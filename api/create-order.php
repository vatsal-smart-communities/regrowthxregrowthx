<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../config/payments.php';

try {
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
        echo json_encode(["success" => false, "message" => "Cart is empty"]);
        exit();
    }
    
    $customer_name = $data['customer_name'] ?? '';
    $email = $data['email'] ?? '';
    $phone = $data['phone'] ?? '';
    $address_line = $data['address_line'] ?? '';
    $city = $data['city'] ?? '';
    $state = $data['state'] ?? '';
    $pincode = $data['pincode'] ?? '';
    $landmark = $data['landmark'] ?? '';
    $payment_method = $data['payment_method'] ?? 'COD';
    
    if (!$customer_name || !$email || !$phone || !$address_line || !$city || !$state || !$pincode) {
        echo json_encode(["success" => false, "message" => "Required fields are missing"]);
        exit();
    }
    
    $order_number = 'RGX-IN-' . strtoupper(substr(uniqid(), -6));
    
    $subtotal = 0;
    foreach ($_SESSION['cart'] as $item) {
        $subtotal += ($item['price_inr'] * $item['quantity']);
    }
    $shipping_amount = 0;
    $total_amount = $subtotal + $shipping_amount;
    
    $user_id = $_SESSION['user_id'] ?? null;
    
    // Process Square Payment if selected
    if ($payment_method === 'SQUARE') {
        $payment_token = $data['payment_token'] ?? '';
        if (!$payment_token) {
            throw new Exception("Secure payment token is missing");
        }

        $ch = curl_init(getSquareEndpoint() . '/payments');
        $payload = json_encode([
            'idempotency_key' => uniqid(),
            'source_id' => $payment_token,
            'amount_money' => [
                'amount' => (int)($total_amount * 100),
                'currency' => 'INR'
            ]
        ]);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Square-Version: 2023-12-13',
            'Authorization: Bearer ' . SQUARE_ACCESS_TOKEN,
            'Content-Type: application/json'
        ]);

        $square_response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $square_result = json_decode($square_response, true);
        
        if ($httpCode !== 200 || isset($square_result['errors'])) {
            $errorDetail = $square_result['errors'][0]['detail'] ?? 'Unknown error';
            throw new Exception("Square Payment Failed: " . $errorDetail);
        }
    }
    
    $pdo->beginTransaction();
    
    $stmt = $pdo->prepare("
        INSERT INTO orders 
        (order_number, user_id, customer_name, email, phone, address_line, city, state, pincode, landmark, subtotal_amount, shipping_amount, total_amount, payment_method)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $order_number, $user_id, $customer_name, $email, $phone, $address_line, $city, $state, $pincode, $landmark, $subtotal, $shipping_amount, $total_amount, $payment_method
    ]);
    
    $order_id = $pdo->lastInsertId();
    
    $stmt_item = $pdo->prepare("INSERT INTO order_items (order_id, variant_id, item_name, quantity, unit_price, total_price) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt_stock = $pdo->prepare("UPDATE product_variants SET stock_qty = stock_qty - ? WHERE id = ?");
    
    foreach ($_SESSION['cart'] as $item) {
        $item_name = $item['title'] . ' - ' . $item['variant_name'];
        $item_total = $item['price_inr'] * $item['quantity'];
        
        $stmt_item->execute([$order_id, $item['variant_id'], $item_name, $item['quantity'], $item['price_inr'], $item_total]);
        $stmt_stock->execute([$item['quantity'], $item['variant_id']]);
    }
    
    $pdo->commit();
    
    // Send Order Confirmation Email
    require_once __DIR__ . '/../includes/mailer.php';
    sendOrderConfirmationEmail($email, $customer_name, $order_number, $total_amount, $_SESSION['cart']);
    
    $_SESSION['cart'] = [];
    
    echo json_encode([
        "success" => true,
        "order_number" => $order_number,
        "total" => $total_amount,
        "payment_method" => $payment_method
    ]);
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode(["success" => false, "message" => "Error creating order: " . $e->getMessage()]);
}
?>
