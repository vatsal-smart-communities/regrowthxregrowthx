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
    $data = json_decode(file_get_contents("php://input"), true);
    $email = trim($data['email'] ?? '');
    $code = trim($data['code'] ?? '');
    $newPassword = $data['password'] ?? '';
    
    if (empty($email) || empty($code) || empty($newPassword)) {
        echo json_encode(["success" => false, "message" => "All fields are required"]);
        exit();
    }
    
    if (strlen($newPassword) < 6) {
        echo json_encode(["success" => false, "message" => "Password must be at least 6 characters"]);
        exit();
    }
    
    // Verify token
    $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE email = ? AND token = ? AND is_used = 0 AND expires_at > NOW()");
    $stmt->execute([$email, $code]);
    $resetReq = $stmt->fetch();
    
    if (!$resetReq) {
        echo json_encode(["success" => false, "message" => "Invalid or expired reset code"]);
        exit();
    }
    
    // Mark as used
    $stmt = $pdo->prepare("UPDATE password_resets SET is_used = 1 WHERE id = ?");
    $stmt->execute([$resetReq['id'] ?? null]);
    
    // Update user password
    $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE email = ?");
    $stmt->execute([$passwordHash, $email]);
    
    echo json_encode(["success" => true, "message" => "Password reset successfully. You can now login."]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error resetting password: " . $e->getMessage()]);
}
?>
