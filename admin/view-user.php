<?php
require_once __DIR__ . '/includes/header.php';

$user_id = $_GET['id'] ?? null;
if (!$user_id) {
    echo "<script>window.location.href='users.php';</script>";
    exit();
}

// Fetch User
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND role = 'customer'");
$stmt->execute([$user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo "<script>window.location.href='users.php';</script>";
    exit();
}

// Fetch Lifetime Metrics
$stmtMetrics = $pdo->prepare("
    SELECT COUNT(id) as total_orders, SUM(total_amount) as total_spent 
    FROM orders 
    WHERE user_id = ? AND order_status != 'cancelled'
");
$stmtMetrics->execute([$user_id]);
$metrics = $stmtMetrics->fetch(PDO::FETCH_ASSOC);

// Fetch Full Order History
$stmtOrders = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmtOrders->execute([$user_id]);
$orders = $stmtOrders->fetchAll(PDO::FETCH_ASSOC);

// Fetch order items if orders exist
$orderItems = [];
if (!empty($orders)) {
    $orderIds = array_column($orders, 'id');
    $placeholders = str_repeat('?,', count($orderIds) - 1) . '?';
    $stmtItems = $pdo->prepare("SELECT order_id, item_name, quantity, unit_price FROM order_items WHERE order_id IN ($placeholders)");
    $stmtItems->execute($orderIds);
    $items = $stmtItems->fetchAll(PDO::FETCH_ASSOC);
    foreach ($items as $item) {
        $orderItems[$item['order_id']][] = $item;
    }
}

function formatINR($amount) {
    return '₹' . number_format($amount, 0);
}
?>

<div class="max-w-5xl mx-auto space-y-6">
    
    <!-- Profile Header Card -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col md:flex-row items-center justify-between p-6 gap-6 relative">
        <a href="users.php" class="absolute top-6 right-6 text-sm font-semibold text-gray-500 hover:text-gray-900 transition-colors">Back to Customers</a>
        
        <div class="flex items-center gap-6 mt-4 md:mt-0">
            <div class="w-20 h-20 bg-emerald-100 text-emerald-700 rounded-full flex items-center justify-center text-3xl font-bold uppercase border-4 border-white shadow-md">
                <?= substr(htmlspecialchars($user['full_name'] ?: $user['email']), 0, 1) ?>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-1"><?= htmlspecialchars($user['full_name'] ?: 'N/A') ?></h2>
                <div class="flex items-center gap-4 text-sm text-gray-600">
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">mail</span> <?= htmlspecialchars($user['email']) ?></span>
                    <span class="flex items-center gap-1"><span class="material-symbols-outlined text-[16px]">phone</span> <?= htmlspecialchars($user['phone'] ?: 'No Phone') ?></span>
                </div>
            </div>
        </div>
        
        <div class="flex items-center gap-8 bg-gray-50/50 py-4 px-8 rounded-xl border border-gray-100 mt-4 md:mt-0 w-full md:w-auto">
            <div class="text-center">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Total Orders</p>
                <p class="text-2xl font-bold text-gray-900"><?= number_format($metrics['total_orders'] ?? 0) ?></p>
            </div>
            <div class="w-px h-12 bg-gray-200"></div>
            <div class="text-center">
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Lifetime Value</p>
                <p class="text-2xl font-bold text-emerald-700"><?= formatINR($metrics['total_spent'] ?? 0) ?></p>
            </div>
        </div>
    </div>
    
    <!-- Order History Section -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-900">Order History</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500">
                        <th class="px-6 py-4 font-semibold">Order ID</th>
                        <th class="px-6 py-4 font-semibold">Date</th>
                        <th class="px-6 py-4 font-semibold">Items Purchased</th>
                        <th class="px-6 py-4 font-semibold">Shipping Address</th>
                        <th class="px-6 py-4 font-semibold">Amount</th>
                        <th class="px-6 py-4 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if (empty($orders)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">This customer hasn't placed any orders yet.</td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($orders as $order): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 align-top">
                                <a href="orders.php?search=<?= urlencode($order['order_number']) ?>" class="font-bold text-emerald-600 hover:underline flex items-center gap-1" title="View in Orders">
                                    <?= htmlspecialchars($order['order_number']) ?> <span class="material-symbols-outlined text-[14px]">open_in_new</span>
                                </a>
                            </td>
                            <td class="px-6 py-4 align-top text-sm text-gray-600">
                                <?= date('M j, Y', strtotime($order['created_at'])) ?><br>
                                <?= date('H:i', strtotime($order['created_at'])) ?>
                            </td>
                            <td class="px-6 py-4 align-top min-w-[250px]">
                                <?php if (isset($orderItems[$order['id']])): ?>
                                    <ul class="space-y-2">
                                    <?php foreach ($orderItems[$order['id']] as $item): ?>
                                        <li class="text-sm flex flex-col">
                                            <span class="font-semibold text-gray-900 line-clamp-2"><?= htmlspecialchars($item['item_name']) ?></span>
                                            <span class="text-xs text-gray-500">Qty: <?= $item['quantity'] ?> × <?= formatINR($item['unit_price']) ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 align-top">
                                <p class="text-xs text-gray-600 truncate max-w-xs"><?= htmlspecialchars($order['address_line']) ?></p>
                                <p class="text-xs text-gray-600 mt-0.5"><?= htmlspecialchars($order['city']) ?>, <?= htmlspecialchars($order['state']) ?> <?= htmlspecialchars($order['pincode']) ?></p>
                            </td>
                            <td class="px-6 py-4 align-top">
                                <p class="font-bold text-gray-900"><?= formatINR($order['total_amount']) ?></p>
                                <p class="text-xs text-gray-500 mt-0.5 uppercase"><?= htmlspecialchars($order['payment_method']) ?></p>
                            </td>
                            <td class="px-6 py-4 align-top">
                                <?php 
                                    $statusColor = 'bg-gray-100 text-gray-700'; // pending
                                    if ($order['order_status'] === 'processing') $statusColor = 'bg-blue-100 text-blue-700';
                                    if ($order['order_status'] === 'shipped') $statusColor = 'bg-purple-100 text-purple-700';
                                    if ($order['order_status'] === 'delivered') $statusColor = 'bg-emerald-100 text-emerald-700';
                                    if ($order['order_status'] === 'cancelled') $statusColor = 'bg-red-100 text-red-700';
                                ?>
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold capitalize <?= $statusColor ?>">
                                    <?= htmlspecialchars($order['order_status']) ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
