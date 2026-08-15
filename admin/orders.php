<?php
require_once __DIR__ . '/includes/header.php';

// Pagination settings
$limit = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Search & Filter
$search = $_GET['search'] ?? '';
$statusFilter = $_GET['status'] ?? 'all';

$whereClauses = [];
$params = [];

if ($search !== '') {
    $whereClauses[] = "(order_number LIKE ? OR customer_name LIKE ? OR email LIKE ? OR phone LIKE ?)";
    $searchParam = "%$search%";
    $params = array_merge($params, [$searchParam, $searchParam, $searchParam, $searchParam]);
}

if ($statusFilter !== 'all') {
    $whereClauses[] = "order_status = ?";
    $params[] = $statusFilter;
}

$whereSql = '';
if (!empty($whereClauses)) {
    $whereSql = "WHERE " . implode(" AND ", $whereClauses);
}

// Get Total Count
$countSql = "SELECT COUNT(*) FROM orders $whereSql";
$stmtCount = $pdo->prepare($countSql);
$stmtCount->execute($params);
$totalRecords = $stmtCount->fetchColumn();
$totalPages = ceil($totalRecords / $limit);

// Get Records
$sql = "SELECT * FROM orders $whereSql ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch items for these orders
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

// Build URL for pagination
function buildUrl($newPage) {
    $params = $_GET;
    $params['page'] = $newPage;
    return 'orders.php?' . http_build_query($params);
}
?>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <h3 class="text-lg font-bold text-gray-900">All Orders</h3>
        
        <form method="GET" action="orders.php" class="flex flex-col sm:flex-row gap-3">
            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search ID, Name, Email..." class="bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block p-2.5 min-w-[200px]">
            <select name="status" class="bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block p-2.5">
                <option value="all" <?= $statusFilter === 'all' ? 'selected' : '' ?>>All Statuses</option>
                <option value="pending" <?= $statusFilter === 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="processing" <?= $statusFilter === 'processing' ? 'selected' : '' ?>>Processing</option>
                <option value="shipped" <?= $statusFilter === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                <option value="delivered" <?= $statusFilter === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                <option value="cancelled" <?= $statusFilter === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>
            <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-800 transition-colors">Filter</button>
            <?php if ($search !== '' || $statusFilter !== 'all'): ?>
            <a href="orders.php" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-200 transition-colors flex items-center justify-center">Clear</a>
            <?php endif; ?>
        </form>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500">
                    <th class="px-6 py-4 font-semibold">Order ID</th>
                    <th class="px-6 py-4 font-semibold">Customer Details</th>
                    <th class="px-6 py-4 font-semibold">Ordered Items</th>
                    <th class="px-6 py-4 font-semibold">Address</th>
                    <th class="px-6 py-4 font-semibold">Amount</th>
                    <th class="px-6 py-4 font-semibold">Status Update</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($orders)): ?>
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">No orders found matching your criteria.</td>
                </tr>
                <?php else: ?>
                    <?php foreach ($orders as $order): ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 align-top">
                            <p class="font-bold text-gray-900"><?= htmlspecialchars($order['order_number']) ?></p>
                            <p class="text-xs text-gray-500 mt-1"><?= date('M j, Y H:i', strtotime($order['created_at'])) ?></p>
                        </td>
                        <td class="px-6 py-4 align-top">
                            <p class="text-sm font-bold text-gray-900"><?= htmlspecialchars($order['customer_name']) ?></p>
                            <p class="text-xs text-gray-600 mt-0.5"><?= htmlspecialchars($order['email']) ?></p>
                            <p class="text-xs text-gray-600 mt-0.5"><?= htmlspecialchars($order['phone']) ?></p>
                        </td>
                        <td class="px-6 py-4 align-top min-w-[200px]">
                            <?php if (isset($orderItems[$order['id']])): ?>
                                <ul class="space-y-2">
                                <?php foreach ($orderItems[$order['id']] as $item): ?>
                                    <li class="text-sm flex flex-col">
                                        <span class="font-semibold text-gray-900 line-clamp-2"><?= htmlspecialchars($item['item_name']) ?></span>
                                        <span class="text-xs text-gray-500">Qty: <?= $item['quantity'] ?> × <?= formatINR($item['unit_price']) ?></span>
                                    </li>
                                <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <span class="text-xs text-gray-400 italic">No items found</span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 align-top">
                            <p class="text-xs text-gray-600 truncate max-w-xs"><?= htmlspecialchars($order['address_line']) ?></p>
                            <p class="text-xs text-gray-600 mt-0.5"><?= htmlspecialchars($order['city']) ?>, <?= htmlspecialchars($order['state']) ?> <?= htmlspecialchars($order['pincode']) ?></p>
                        </td>
                        <td class="px-6 py-4 align-top">
                            <p class="font-bold text-brand-primary"><?= formatINR($order['total_amount']) ?></p>
                            <p class="text-xs text-gray-500 mt-0.5 uppercase"><?= htmlspecialchars($order['payment_method']) ?></p>
                        </td>
                        <td class="px-6 py-4 align-top">
                            <select onchange="updateOrderStatus(<?= $order['id'] ?>, this.value)" class="bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block w-full p-2">
                                <option value="pending" <?= $order['order_status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                <option value="processing" <?= $order['order_status'] === 'processing' ? 'selected' : '' ?>>Processing</option>
                                <option value="shipped" <?= $order['order_status'] === 'shipped' ? 'selected' : '' ?>>Shipped</option>
                                <option value="delivered" <?= $order['order_status'] === 'delivered' ? 'selected' : '' ?>>Delivered</option>
                                <option value="cancelled" <?= $order['order_status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            </select>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <div class="px-6 py-4 border-t border-gray-100 flex items-center justify-between">
        <p class="text-sm text-gray-500">Showing <?= $offset + 1 ?> to <?= min($offset + $limit, $totalRecords) ?> of <?= $totalRecords ?> orders</p>
        <div class="flex gap-2">
            <?php if ($page > 1): ?>
                <a href="<?= buildUrl($page - 1) ?>" class="px-3 py-1.5 border border-gray-200 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50">Previous</a>
            <?php endif; ?>
            
            <?php if ($page < $totalPages): ?>
                <a href="<?= buildUrl($page + 1) ?>" class="px-3 py-1.5 border border-gray-200 rounded-lg text-sm font-semibold text-gray-700 hover:bg-gray-50">Next</a>
            <?php endif; ?>
        </div>
    </div>
    <?php endif; ?>
</div>

<script>
async function updateOrderStatus(orderId, newStatus) {
    try {
        const res = await fetch('../api/admin-update-order.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ order_id: orderId, status: newStatus })
        });
        const data = await res.json();
        if (data.success) {
            showAdminToast('Order status updated successfully', 'success');
        } else {
            showAdminToast(data.message || 'Failed to update order', 'error');
        }
    } catch (err) {
        showAdminToast('Network error', 'error');
    }
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
