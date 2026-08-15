<?php
require_once __DIR__ . '/../config/db.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid method']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$product_id = intval($input['product_id'] ?? 0);
$rating = intval($input['rating'] ?? 5);
$review_text = trim($input['review_text'] ?? '');
$name = trim($input['name'] ?? '');
$email = trim($input['email'] ?? '');
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if ($product_id <= 0 || empty($name) || empty($email) || empty($review_text) || $rating < 1 || $rating > 5) {
    echo json_encode(['success' => false, 'message' => 'Please fill all required fields correctly.']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO reviews (product_id, user_id, reviewer_name, reviewer_email, rating, review_text, status) VALUES (:pid, :uid, :name, :email, :rating, :text, 'pending')");
    $stmt->execute([
        'pid' => $product_id,
        'uid' => $user_id,
        'name' => $name,
        'email' => $email,
        'rating' => $rating,
        'text' => $review_text
    ]);

    echo json_encode(['success' => true, 'message' => 'Thank you! Your review has been submitted and is pending approval.']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Server error while submitting review.']);
}
