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
require_once __DIR__ . '/../includes/mailer.php';

try {
    $data = json_decode(file_get_contents("php://input"), true);
    $email = trim($data['email'] ?? '');
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "message" => "Valid email is required"]);
        exit();
    }
    
    // Check if user exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if (!$stmt->fetch()) {
        // Return success anyway to prevent email enumeration
        echo json_encode(["success" => true, "message" => "Test Mode: Please use Reset Code 123456"]);
        exit();
    }
    
    // Invalidate old tokens
    $stmt = $pdo->prepare("UPDATE password_resets SET is_used = 1 WHERE email = ? AND is_used = 0");
    $stmt->execute([$email]);
    
    // STATIC MODE (Same as OTP)
    $resetCode = '123456'; 
    
    $stmt = $pdo->prepare("INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 15 MINUTE))");
    $stmt->execute([$email, $resetCode]);
    
    // Bypass actual email sending for static test mode
    // $mailSent = sendResetEmail($email, $resetCode);
    
    $response = ["success" => true, "message" => "Test Mode: Please use Reset Code 123456"];
    
    echo json_encode($response);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error processing request: " . $e->getMessage()]);
}
?>
