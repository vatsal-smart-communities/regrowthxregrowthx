<?php
require_once __DIR__ . '/includes/header.php';

// Fetch KPIs
$stmt = $pdo->query("SELECT COUNT(*) FROM orders");
$totalOrders = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT SUM(total_amount) FROM orders WHERE payment_status = 'paid' OR payment_method = 'COD'");
$totalRevenue = $stmt->fetchColumn() ?: 0;

$stmt = $pdo->query("SELECT COUNT(*) FROM orders WHERE order_status = 'pending'");
$pendingOrders = $stmt->fetchColumn();

// Fetch Recent Orders
$stmt = $pdo->query("SELECT * FROM orders ORDER BY created_at DESC LIMIT 5");
$recentOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);

function formatINR($amount) {
    return '₹' . number_format($amount, 0);
}
?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Total Revenue Card -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-14 h-14 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600">
            <span class="material-symbols-outlined text-3xl">payments</span>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Total Revenue</p>
            <p class="text-2xl font-bold text-gray-900 mt-1"><?= formatINR($totalRevenue) ?></p>
        </div>
    </div>
    
    <!-- Total Orders Card -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-14 h-14 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
            <span class="material-symbols-outlined text-3xl">local_mall</span>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Total Orders</p>
            <p class="text-2xl font-bold text-gray-900 mt-1"><?= number_format($totalOrders) ?></p>
        </div>
    </div>
    
    <!-- Pending Orders Card -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4">
        <div class="w-14 h-14 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600">
            <span class="material-symbols-outlined text-3xl">pending_actions</span>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Pending Orders</p>
            <p class="text-2xl font-bold text-gray-900 mt-1"><?= number_format($pendingOrders) ?></p>
        </div>
    </div>
</div>

<!-- Recent Orders Section -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-900">Recent Orders</h3>
        <a href="orders.php" class="text-sm font-semibold text-brand-primary hover:text-emerald-800 transition-colors">View All</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500">
                    <th class="px-6 py-4 font-semibold">Order ID</th>
                    <th class="px-6 py-4 font-semibold">Customer</th>
                    <th class="px-6 py-4 font-semibold">Date</th>
                    <th class="px-6 py-4 font-semibold">Amount</th>
                    <th class="px-6 py-4 font-semibold">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($recentOrders)): ?>
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">No orders found.</td>
                </tr>
                <?php else: ?>
                    <?php foreach ($recentOrders as $order): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-semibold text-gray-900"><?= htmlspecialchars($order['order_number']) ?></td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-gray-900"><?= htmlspecialchars($order['customer_name']) ?></p>
                            <p class="text-xs text-gray-500"><?= htmlspecialchars($order['email']) ?></p>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600"><?= date('M j, Y', strtotime($order['created_at'])) ?></td>
                        <td class="px-6 py-4 font-bold text-gray-900"><?= formatINR($order['total_amount']) ?></td>
                        <td class="px-6 py-4">
                            <?php 
                                $status = $order['order_status'];
                                $badgeClass = match($status) {
                                    'pending' => 'bg-amber-100 text-amber-800',
                                    'processing' => 'bg-blue-100 text-blue-800',
                                    'shipped' => 'bg-purple-100 text-purple-800',
                                    'delivered' => 'bg-emerald-100 text-emerald-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                            ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wide <?= $badgeClass ?>">
                                <?= htmlspecialchars($status) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
