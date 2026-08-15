<?php
session_start();
require_once __DIR__ . '/config/db.php';

// Redirect to home if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch orders
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$user_id]);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

function formatINR($amount) {
    return '$' . number_format($amount, 2);
}

function getStatusBadgeClass($status) {
    switch ($status) {
        case 'pending': return 'bg-amber-100 text-amber-800';
        case 'processing': return 'bg-blue-100 text-blue-800';
        case 'shipped': return 'bg-purple-100 text-purple-800';
        case 'delivered': return 'bg-emerald-100 text-emerald-800';
        case 'cancelled': return 'bg-red-100 text-red-800';
        default: return 'bg-gray-100 text-gray-800';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - RegrowthX</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #f9fafb; }
        .text-brand-primary { color: #059669; }
        .bg-brand-primary { background-color: #059669; }
        .bg-brand-dark { background-color: #0d1e12; }
    </style>
</head>
<body>

    <!-- Simple Header -->
    <header class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <a href="index.php" class="flex items-center gap-2">
                    <img src="img/logo.jpeg" alt="RegrowthX" class="h-8 w-auto object-contain">
                </a>
                <a href="index.php" class="text-sm font-semibold text-gray-600 hover:text-brand-primary transition-colors flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">arrow_back</span> Back to Store
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-4xl mx-auto px-4 py-12">
        <h2 class="text-3xl font-bold text-gray-900 mb-8">My Orders</h2>

        <?php if (empty($orders)): ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-4xl text-emerald-600">shopping_bag</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">No orders yet</h3>
                <p class="text-gray-500 mb-6">Looks like you haven't placed any orders with us.</p>
                <a href="index.php" class="inline-flex items-center justify-center px-6 py-3 bg-brand-primary text-white font-semibold rounded-xl hover:bg-emerald-700 transition-colors">
                    Start Shopping
                </a>
            </div>
        <?php else: ?>
            <div class="space-y-6">
                <?php foreach ($orders as $order): ?>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="border-b border-gray-100 bg-gray-50/50 px-6 py-4 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <p class="text-sm text-gray-500 font-medium">Order Number</p>
                                <p class="text-base font-bold text-gray-900 mt-0.5"><?= htmlspecialchars($order['order_number']) ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 font-medium">Date Placed</p>
                                <p class="text-base font-semibold text-gray-900 mt-0.5"><?= date('M j, Y', strtotime($order['created_at'])) ?></p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 font-medium">Total Amount</p>
                                <p class="text-base font-bold text-brand-primary mt-0.5"><?= formatINR($order['total_amount']) ?></p>
                            </div>
                            <div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide <?= getStatusBadgeClass($order['order_status']) ?>">
                                    <?= htmlspecialchars($order['order_status']) ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="px-6 py-6 border-b border-gray-100">
                            <h4 class="text-sm font-bold text-gray-900 mb-4 uppercase tracking-wider">Shipping Details</h4>
                            <p class="text-sm text-gray-600"><strong><?= htmlspecialchars($order['customer_name']) ?></strong></p>
                            <p class="text-sm text-gray-600 mt-1"><?= htmlspecialchars($order['address_line']) ?></p>
                            <?php if ($order['landmark']): ?>
                                <p class="text-sm text-gray-600 mt-1">Landmark: <?= htmlspecialchars($order['landmark']) ?></p>
                            <?php endif; ?>
                            <p class="text-sm text-gray-600 mt-1"><?= htmlspecialchars($order['city']) ?>, <?= htmlspecialchars($order['state']) ?> <?= htmlspecialchars($order['pincode']) ?></p>
                            <p class="text-sm text-gray-600 mt-1">Phone: <?= htmlspecialchars($order['phone']) ?></p>
                        </div>
                        
                        <div class="px-6 py-4 bg-gray-50 flex justify-end">
                            <button class="text-sm font-semibold text-brand-primary hover:text-emerald-800 transition-colors flex items-center gap-1">
                                Need Help with this Order? <span class="material-symbols-outlined text-sm">chevron_right</span>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>

</body>
</html>
