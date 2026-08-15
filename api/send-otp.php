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
    $email = $data['email'] ?? '';
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "message" => "Invalid email address"]);
        exit();
    }
    
    $stmt = $pdo->prepare("UPDATE email_otps SET is_used = 1 WHERE email = ? AND is_used = 0");
    $stmt->execute([$email]);
    
    // STATIC OTP MODE (Requested by user)
    $otpCode = '123456'; 
    
    $stmt = $pdo->prepare("INSERT INTO email_otps (email, otp_code, expires_at) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 10 MINUTE))");
    $stmt->execute([$email, $otpCode]);
    
    // Bypass actual email sending for now
    // $mailSent = sendOtpEmail($email, $otpCode);
    
    $response = ["success" => true, "message" => "Test Mode: Please use OTP 123456"];
    
    echo json_encode($response);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error sending OTP: " . $e->getMessage()]);
}
?>
