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
    $email = $data['email'] ?? '';
    $otp = $data['otp'] ?? '';
    
    if (empty($email) || empty($otp)) {
        echo json_encode(["success" => false, "message" => "Email and OTP are required"]);
        exit();
    }
    
    $stmt = $pdo->prepare("SELECT * FROM email_otps WHERE email = ? AND otp_code = ? AND is_used = 0 AND expires_at > NOW() ORDER BY created_at DESC LIMIT 1");
    $stmt->execute([$email, $otp]);
    $otpRecord = $stmt->fetch();
    
    if (!$otpRecord) {
        echo json_encode(["success" => false, "message" => "Invalid or expired OTP"]);
        exit();
    }
    
    $stmt = $pdo->prepare("UPDATE email_otps SET is_used = 1 WHERE id = ?");
    $stmt->execute([$otpRecord['id']]);
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if (!$user) {
        $stmt = $pdo->prepare("INSERT INTO users (email, role, status) VALUES (?, 'customer', 'active')");
        $stmt->execute([$email]);
        $userId = $pdo->lastInsertId();
        
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();
    }
    
    session_start();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['user_name'] = $user['full_name'] ?? '';
    $_SESSION['user_role'] = $user['role'];
    
    // Load RBAC permissions if user has an admin role
    if (!empty($user['role_id'])) {
        require_once __DIR__ . '/../includes/permissions.php';
        loadUserPermissions($pdo, $user['id']);
        $_SESSION['permissions_loaded_at'] = time();
    }
    
    echo json_encode([
        "success" => true,
        "user" => [
            "id" => $user['id'],
            "email" => $user['email'],
            "name" => $user['full_name'],
            "role" => $user['role']
        ]
    ]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error verifying OTP: " . $e->getMessage()]);
}
?>
