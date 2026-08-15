<?php
require_once __DIR__ . '/includes/header.php';
requirePermission('view_products');

// Pagination settings
$limit = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Search 
$search = $_GET['search'] ?? '';

$whereClauses = [];
$params = [];

if ($search !== '') {
    $whereClauses[] = "(p.title LIKE ? OR pv.variant_name LIKE ? OR pv.variant_key LIKE ?)";
    $searchParam = "%$search%";
    $params = array_merge($params, [$searchParam, $searchParam, $searchParam]);
}

$whereSql = '';
if (!empty($whereClauses)) {
    $whereSql = "WHERE " . implode(" AND ", $whereClauses);
}

// Get Total Count
$countSql = "SELECT COUNT(*) FROM product_variants pv JOIN products p ON pv.product_id = p.id $whereSql";
$stmtCount = $pdo->prepare($countSql);
$stmtCount->execute($params);
$totalRecords = $stmtCount->fetchColumn();
$totalPages = ceil($totalRecords / $limit);

// Get Records
$sql = "
    SELECT pv.id as variant_id, pv.product_id, pv.variant_name, pv.price_inr, pv.stock_qty, p.title as product_name 
    FROM product_variants pv 
    JOIN products p ON pv.product_id = p.id
    $whereSql
    ORDER BY p.id ASC, pv.price_inr ASC
    LIMIT $limit OFFSET $offset
";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$variants = $stmt->fetchAll(PDO::FETCH_ASSOC);

function formatINR($amount) {
    return '₹' . number_format($amount, 0);
}

function buildUrl($newPage) {
    $params = $_GET;
    $params['page'] = $newPage;
    return 'products.php?' . http_build_query($params);
}
?>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-gray-50/50">
        <h3 class="text-lg font-bold text-gray-900">Inventory Management</h3>
        
        <div class="flex items-center gap-3">
            <form method="GET" action="products.php" class="flex gap-2">
                <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search product..." class="bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block p-2.5 min-w-[200px]">
                <button type="submit" class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-800 transition-colors">Search</button>
                <?php if ($search !== ''): ?>
                <a href="products.php" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-200 transition-colors flex items-center justify-center">Clear</a>
                <?php endif; ?>
            </form>
            
            <a href="add-product.php" class="px-4 py-2.5 bg-emerald-600 text-white text-sm font-semibold rounded-lg hover:bg-emerald-700 transition-colors flex items-center gap-2 shrink-0">
                <span class="material-symbols-outlined text-sm">add</span> Add Product
            </a>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white border-b border-gray-200 text-xs uppercase tracking-wider text-gray-500">
                    <th class="px-6 py-4 font-semibold">Product Name</th>
                    <th class="px-6 py-4 font-semibold">Variant Name</th>
                    <th class="px-6 py-4 font-semibold">Price (USD)</th>
                    <th class="px-6 py-4 font-semibold">Stock Qty</th>
                    <th class="px-6 py-4 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($variants)): ?>
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">No products found.</td>
                </tr>
                <?php else: ?>
                    <?php foreach ($variants as $v): ?>
                    <tr class="hover:bg-gray-50 transition-colors group">
                        <td class="px-6 py-4 font-semibold text-gray-900"><?= htmlspecialchars($v['product_name']) ?></td>
                        <td class="px-6 py-4 text-sm text-gray-600"><?= htmlspecialchars($v['variant_name']) ?></td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <span class="text-gray-500">$</span>
                                <input type="number" step="0.01" id="price-<?= $v['variant_id'] ?>" value="<?= $v['price_inr'] ?>" class="w-24 bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block p-2">
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <input type="number" id="stock-<?= $v['variant_id'] ?>" value="<?= $v['stock_qty'] ?>" class="w-24 bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block p-2">
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">
                                <!-- Save Inline Changes -->
                                <button onclick="updateVariant(<?= $v['variant_id'] ?>)" title="Save Stock/Price" class="w-8 h-8 rounded bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white transition-colors flex items-center justify-center opacity-0 group-hover:opacity-100">
                                    <span class="material-symbols-outlined text-[18px]">save</span>
                                </button>
                                
                                <!-- Full Edit -->
                                <a href="edit-product.php?id=<?= $v['product_id'] ?>" title="Edit Product" class="w-8 h-8 rounded bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white transition-colors flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </a>
                                
                                <!-- Delete Product -->
                                <button onclick="deleteProduct(<?= $v['product_id'] ?>)" title="Delete Product" class="w-8 h-8 rounded bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition-colors flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </div>
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
        <p class="text-sm text-gray-500">Showing <?= $offset + 1 ?> to <?= min($offset + $limit, $totalRecords) ?> of <?= $totalRecords ?> variants</p>
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
async function updateVariant(variantId) {
    const price = document.getElementById('price-' + variantId).value;
    const stock = document.getElementById('stock-' + variantId).value;
    
    try {
        const res = await fetch('../api/admin-update-stock.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ variant_id: variantId, price_inr: price, stock_qty: stock })
        });
        const data = await res.json();
        if (data.success) {
            showAdminToast('Product variant updated successfully', 'success');
        } else {
            showAdminToast(data.message || 'Failed to update variant', 'error');
        }
    } catch (err) {
        showAdminToast('Network error', 'error');
    }
}

async function deleteProduct(productId) {
    if (!confirm('Are you sure you want to permanently delete this product and all of its variants? This action cannot be undone.')) {
        return;
    }
    
    try {
        const res = await fetch('../api/admin-delete-product.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ product_id: productId })
        });
        const data = await res.json();
        if (data.success) {
            showAdminToast('Product deleted successfully', 'success');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showAdminToast(data.message || 'Failed to delete product', 'error');
        }
    } catch (err) {
        showAdminToast('Network error', 'error');
    }
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
