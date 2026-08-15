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
session_start();

try {
    $data = json_decode(file_get_contents("php://input"), true);
    $fullName = trim($data['full_name'] ?? '');
    $email = trim($data['email'] ?? '');
    $phone = trim($data['phone'] ?? '');
    $password = $data['password'] ?? '';
    $otp = trim($data['otp'] ?? '');
    
    if (empty($fullName) || empty($email) || empty($phone) || empty($password) || empty($otp)) {
        echo json_encode(["success" => false, "message" => "All fields including OTP are required"]);
        exit();
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "message" => "Invalid email format"]);
        exit();
    }
    
    $passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';
    if (!preg_match($passwordPattern, $password)) {
        echo json_encode(["success" => false, "message" => "Password does not meet the strict security requirements"]);
        exit();
    }

    // Check if email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        echo json_encode(["success" => false, "message" => "Email is already registered"]);
        exit();
    }
    
    // Verify OTP
    $stmtOtp = $pdo->prepare("SELECT id FROM email_otps WHERE email = ? AND otp_code = ? AND is_used = 0 AND expires_at > NOW() ORDER BY id DESC LIMIT 1");
    $stmtOtp->execute([$email, $otp]);
    $otpRecord = $stmtOtp->fetch(PDO::FETCH_ASSOC);
    
    if (!$otpRecord) {
        echo json_encode(["success" => false, "message" => "Invalid or expired OTP"]);
        exit();
    }
    
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("INSERT INTO users (full_name, email, phone, password_hash, role, status) VALUES (?, ?, ?, ?, 'customer', 'active')");
    $stmt->execute([$fullName, $email, $phone, $passwordHash]);
    
    $userId = $pdo->lastInsertId();
    
    // Mark OTP as used
    $stmtMark = $pdo->prepare("UPDATE email_otps SET is_used = 1 WHERE id = ?");
    $stmtMark->execute([$otpRecord['id']]);
    
    // Send Welcome Email
    require_once __DIR__ . '/../includes/mailer.php';
    sendWelcomeEmail($email, $fullName);
    
    // Auto login after registration
    $_SESSION['user_id'] = $userId;
    $_SESSION['user_email'] = $email;
    $_SESSION['user_name'] = $fullName;
    $_SESSION['user_role'] = 'customer';
    
    echo json_encode([
        "success" => true, 
        "message" => "Registration successful",
        "user" => [
            "id" => $userId,
            "name" => $fullName,
            "email" => $email,
            "role" => 'customer'
        ]
    ]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error during registration: " . $e->getMessage()]);
}
?>
