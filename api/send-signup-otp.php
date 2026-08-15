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
    
    // Check if email already exists in users table
    $stmtUser = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmtUser->execute([$email]);
    if ($stmtUser->fetch()) {
        echo json_encode(["success" => false, "message" => "Email is already registered"]);
        exit();
    }
    
    // Invalidate old OTPs for this email
    $stmt = $pdo->prepare("UPDATE email_otps SET is_used = 1 WHERE email = ? AND is_used = 0");
    $stmt->execute([$email]);
    
    // Generate a real 6-digit OTP
    $otpCode = sprintf("%06d", mt_rand(1, 999999));
    
    $stmt = $pdo->prepare("INSERT INTO email_otps (email, otp_code, expires_at) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 10 MINUTE))");
    $stmt->execute([$email, $otpCode]);
    
    // Send the actual email
    $mailSent = sendOtpEmail($email, $otpCode);
    
    if ($mailSent === true) {
        $response = ["success" => true, "message" => "An OTP has been sent to your email address."];
    } else {
        $response = ["success" => false, "message" => "Failed to send email. " . $mailSent];
    }
    
    echo json_encode($response);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error sending OTP: " . $e->getMessage()]);
}
?>
