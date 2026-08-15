<?php
require_once __DIR__ . '/includes/header.php';

// Date Filter Logic
$filter = $_GET['filter'] ?? 'alltime';
$start_date = '';
$end_date = date('Y-m-d 23:59:59');

switch ($filter) {
    case '7days':
        $start_date = date('Y-m-d 00:00:00', strtotime('-7 days'));
        break;
    case '1month':
        $start_date = date('Y-m-d 00:00:00', strtotime('-1 month'));
        break;
    case '3months':
        $start_date = date('Y-m-d 00:00:00', strtotime('-3 months'));
        break;
    case '6months':
        $start_date = date('Y-m-d 00:00:00', strtotime('-6 months'));
        break;
    case '1year':
        $start_date = date('Y-m-d 00:00:00', strtotime('-1 year'));
        break;
    case '5years':
        $start_date = date('Y-m-d 00:00:00', strtotime('-5 years'));
        break;
    case 'custom':
        if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
            $start_date = date('Y-m-d 00:00:00', strtotime($_GET['start_date']));
            $end_date = date('Y-m-d 23:59:59', strtotime($_GET['end_date']));
        } else {
            $start_date = date('Y-m-d 00:00:00', strtotime('-1 month'));
        }
        break;
    case 'alltime':
    default:
        $start_date = '1970-01-01 00:00:00';
        break;
}

// Fetch KPIs
$stmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE created_at >= :start AND created_at <= :end");
$stmt->execute(['start' => $start_date, 'end' => $end_date]);
$totalOrders = $stmt->fetchColumn();

$stmt = $pdo->prepare("SELECT SUM(total_amount) FROM orders WHERE order_status != 'cancelled' AND created_at >= :start AND created_at <= :end");
$stmt->execute(['start' => $start_date, 'end' => $end_date]);
$totalRevenue = $stmt->fetchColumn() ?: 0;

$stmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE order_status = 'pending' AND created_at >= :start AND created_at <= :end");
$stmt->execute(['start' => $start_date, 'end' => $end_date]);
$pendingOrders = $stmt->fetchColumn();

// Fetch Chart Data: Revenue over time
$stmt = $pdo->prepare("SELECT DATE(created_at) as date, SUM(total_amount) as revenue FROM orders WHERE order_status != 'cancelled' AND created_at >= :start AND created_at <= :end GROUP BY DATE(created_at) ORDER BY DATE(created_at) ASC");
$stmt->execute(['start' => $start_date, 'end' => $end_date]);
$chartDataRevenue = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Chart Data: Order Status
$stmt = $pdo->prepare("SELECT order_status, COUNT(*) as count FROM orders WHERE created_at >= :start AND created_at <= :end GROUP BY order_status");
$stmt->execute(['start' => $start_date, 'end' => $end_date]);
$chartDataStatus = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch Recent Orders
$stmt = $pdo->prepare("SELECT * FROM orders WHERE created_at >= :start AND created_at <= :end ORDER BY created_at DESC LIMIT 5");
$stmt->execute(['start' => $start_date, 'end' => $end_date]);
$recentOrders = $stmt->fetchAll(PDO::FETCH_ASSOC);

function formatINR($amount) {
    return '$' . number_format($amount, 2);
}

// Prepare JSON for JS
$revenueDates = [];
$revenueTotals = [];
foreach($chartDataRevenue as $row) {
    $revenueDates[] = date('M j', strtotime($row['date']));
    $revenueTotals[] = (float)$row['revenue'];
}

$statusLabels = [];
$statusCounts = [];
$statusColors = [];
foreach($chartDataStatus as $row) {
    $status = $row['order_status'];
    $statusLabels[] = ucfirst($status);
    $statusCounts[] = (int)$row['count'];
    $statusColors[] = match($status) {
        'pending' => '#f59e0b', // amber-500
        'processing' => '#3b82f6', // blue-500
        'shipped' => '#a855f7', // purple-500
        'delivered' => '#10b981', // emerald-500
        'cancelled' => '#ef4444', // red-500
        default => '#6b7280' // gray-500
    };
}
?>

<!-- Filter UI -->
<div class="mb-6 flex flex-col xl:flex-row xl:items-center justify-between gap-4">
    <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Dashboard Overview</h2>
    
    <form method="GET" action="index.php" class="flex flex-col sm:flex-row items-center gap-3">
        <select name="filter" id="dateFilter" onchange="toggleCustomDates()" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-semibold text-gray-700 shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 cursor-pointer">
            <option value="7days" <?= $filter == '7days' ? 'selected' : '' ?>>Last 7 Days</option>
            <option value="1month" <?= $filter == '1month' ? 'selected' : '' ?>>Last 1 Month</option>
            <option value="3months" <?= $filter == '3months' ? 'selected' : '' ?>>Last 3 Months</option>
            <option value="6months" <?= $filter == '6months' ? 'selected' : '' ?>>Last 6 Months</option>
            <option value="1year" <?= $filter == '1year' ? 'selected' : '' ?>>Last 1 Year</option>
            <option value="5years" <?= $filter == '5years' ? 'selected' : '' ?>>Last 5 Years</option>
            <option value="alltime" <?= $filter == 'alltime' ? 'selected' : '' ?>>All Time</option>
            <option value="custom" <?= $filter == 'custom' ? 'selected' : '' ?>>Custom Dates</option>
        </select>

        <div id="customDateFields" class="<?= $filter == 'custom' ? 'flex' : 'hidden' ?> items-center gap-2">
            <input type="date" name="start_date" value="<?= $_GET['start_date'] ?? '' ?>" class="px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 cursor-pointer">
            <span class="text-gray-500 text-sm font-medium">to</span>
            <input type="date" name="end_date" value="<?= $_GET['end_date'] ?? '' ?>" class="px-3 py-2 bg-white border border-gray-300 rounded-lg text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 cursor-pointer">
        </div>

        <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2 rounded-lg font-bold text-sm transition-colors shadow-sm cursor-pointer whitespace-nowrap">
            Apply Filter
        </button>
    </form>
</div>

<script>
function toggleCustomDates() {
    const filter = document.getElementById('dateFilter').value;
    const customFields = document.getElementById('customDateFields');
    if (filter === 'custom') {
        customFields.classList.remove('hidden');
        customFields.classList.add('flex');
    } else {
        customFields.classList.add('hidden');
        customFields.classList.remove('flex');
    }
}
</script>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Total Revenue Card -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow-md transition-shadow">
        <div class="w-14 h-14 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 shrink-0">
            <span class="material-symbols-outlined text-3xl">payments</span>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Total Revenue</p>
            <p class="text-2xl font-bold text-gray-900 mt-1"><?= formatINR($totalRevenue) ?></p>
        </div>
    </div>
    
    <!-- Total Orders Card -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow-md transition-shadow">
        <div class="w-14 h-14 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 shrink-0">
            <span class="material-symbols-outlined text-3xl">local_mall</span>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Total Orders</p>
            <p class="text-2xl font-bold text-gray-900 mt-1"><?= number_format($totalOrders) ?></p>
        </div>
    </div>
    
    <!-- Pending Orders Card -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center gap-4 hover:shadow-md transition-shadow">
        <div class="w-14 h-14 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 shrink-0">
            <span class="material-symbols-outlined text-3xl">pending_actions</span>
        </div>
        <div>
            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Pending Orders</p>
            <p class="text-2xl font-bold text-gray-900 mt-1"><?= number_format($pendingOrders) ?></p>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Revenue Trend</h3>
        <div class="relative h-72 w-full">
            <canvas id="revenueChart"></canvas>
        </div>
        <?php if(empty($revenueDates)): ?>
            <div class="absolute inset-0 flex flex-col items-center justify-center bg-white/80">
                <span class="material-symbols-outlined text-4xl text-gray-300 mb-2">monitoring</span>
                <p class="text-gray-500 text-sm font-medium">No revenue data for this period.</p>
            </div>
        <?php endif; ?>
    </div>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Order Status Distribution</h3>
        <div class="relative h-72 w-full flex items-center justify-center">
            <canvas id="statusChart"></canvas>
            <?php if(empty($statusLabels)): ?>
                <div class="absolute inset-0 flex flex-col items-center justify-center bg-white/80">
                    <span class="material-symbols-outlined text-4xl text-gray-300 mb-2">pie_chart</span>
                    <p class="text-gray-500 text-sm font-medium">No order data for this period.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Recent Orders Section -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center">
        <h3 class="text-lg font-bold text-gray-900">Recent Orders (Filtered)</h3>
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
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">No orders found for the selected period.</td>
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

<!-- Chart.js Integration -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const revenueDates = <?= json_encode($revenueDates) ?>;
    const revenueTotals = <?= json_encode($revenueTotals) ?>;
    
    const statusLabels = <?= json_encode($statusLabels) ?>;
    const statusCounts = <?= json_encode($statusCounts) ?>;
    const statusColors = <?= json_encode($statusColors) ?>;

    if (revenueDates.length > 0) {
        const ctxRev = document.getElementById('revenueChart').getContext('2d');
        new Chart(ctxRev, {
            type: 'line',
            data: {
                labels: revenueDates,
                datasets: [{
                    label: 'Daily Revenue ($)',
                    data: revenueTotals,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 2,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#ffffff',
                    pointHoverBackgroundColor: '#ffffff',
                    pointHoverBorderColor: '#10b981',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [2, 4], color: '#f3f4f6' },
                        ticks: { callback: function(value) { return '$' + value; } }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }

    if (statusLabels.length > 0) {
        const ctxStat = document.getElementById('statusChart').getContext('2d');
        new Chart(ctxStat, {
            type: 'doughnut',
            data: {
                labels: statusLabels,
                datasets: [{
                    data: statusCounts,
                    backgroundColor: statusColors,
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: { position: 'bottom' }
                }
            }
        });
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
