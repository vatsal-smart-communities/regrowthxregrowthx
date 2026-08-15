<?php
$pageTitle = "All Products | RegrowthX 5% Minoxidil Hair Solutions";
require_once __DIR__ . '/includes/store-header.php';

// Pagination settings
$limit = 8;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Search & Filter parameters
$search = trim($_GET['search'] ?? '');
$min_price = isset($_GET['min_price']) && $_GET['min_price'] !== '' ? (float)$_GET['min_price'] : null;
$max_price = isset($_GET['max_price']) && $_GET['max_price'] !== '' ? (float)$_GET['max_price'] : null;
$sort = $_GET['sort'] ?? 'featured';

// Build SQL Query
$whereClauses = ["p.active = 1"];
$params = [];

if ($search !== '') {
    $whereClauses[] = "(p.title LIKE ? OR p.description LIKE ?)";
    $searchParam = "%$search%";
    $params[] = $searchParam;
    $params[] = $searchParam;
}

if ($min_price !== null) {
    $whereClauses[] = "pv.price_inr >= ?";
    $params[] = $min_price;
}

if ($max_price !== null) {
    $whereClauses[] = "pv.price_inr <= ?";
    $params[] = $max_price;
}

$whereSql = implode(" AND ", $whereClauses);

// Sorting
$orderBy = "p.id ASC, pv.price_inr ASC";
if ($sort === 'price_low') {
    $orderBy = "pv.price_inr ASC";
} elseif ($sort === 'price_high') {
    $orderBy = "pv.price_inr DESC";
} elseif ($sort === 'newest') {
    $orderBy = "p.id DESC";
}

// Count total matching items
$countSql = "
    SELECT COUNT(*) 
    FROM products p
    JOIN product_variants pv ON p.id = pv.product_id
    WHERE $whereSql
";
$stmtCount = $pdo->prepare($countSql);
$stmtCount->execute($params);
$total_items = (int)$stmtCount->fetchColumn();
$total_pages = ceil($total_items / $limit);

// Fetch items
$sql = "
    SELECT p.id as product_id, p.title, p.description, p.slug, 
           pv.id as variant_id, pv.variant_name, pv.variant_key, pv.price_inr, pv.mrp_inr, pv.image_path
    FROM products p
    JOIN product_variants pv ON p.id = pv.product_id
    WHERE $whereSql
    ORDER BY $orderBy
    LIMIT $limit OFFSET $offset
";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$variants = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- MAIN CATALOG PAGE -->
<main class="pt-28 pb-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
  
  <!-- Page Header -->
  <div class="text-center space-y-3">
    <span class="inline-block bg-emerald-100 text-emerald-800 text-xs font-extrabold px-4 py-1.5 rounded-full uppercase tracking-wider">
      Full Storefront Catalog
    </span>
    <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 tracking-tight">Our Hair Growth Solutions</h1>
    <p class="text-gray-500 text-base max-w-xl mx-auto">Browse clinically proven 5% Minoxidil solutions, monthly supplies, and bundle packs.</p>
  </div>

  <!-- Search & Filter Controls Card -->
  <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 space-y-4">
    <form method="GET" action="products.php" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
      
      <!-- Search Input -->
      <div class="lg:col-span-2">
        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Search Products</label>
        <div class="relative">
          <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search by name or keyword..." class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl pl-10 pr-4 py-3 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
          <span class="material-symbols-outlined absolute left-3 top-3.5 text-gray-400 text-lg">search</span>
        </div>
      </div>

      <!-- Min Price -->
      <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Min Price ($)</label>
        <input type="number" step="0.01" name="min_price" value="<?= $min_price !== null ? htmlspecialchars($min_price) : '' ?>" placeholder="e.g. 15" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-4 py-3 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
      </div>

      <!-- Max Price -->
      <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Max Price ($)</label>
        <input type="number" step="0.01" name="max_price" value="<?= $max_price !== null ? htmlspecialchars($max_price) : '' ?>" placeholder="e.g. 100" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-4 py-3 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
      </div>

      <!-- Sort Dropdown & Buttons -->
      <div>
        <label class="block text-xs font-semibold text-gray-600 mb-1.5">Sort By</label>
        <select name="sort" onchange="this.form.submit()" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl px-4 py-3 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
          <option value="featured" <?= $sort === 'featured' ? 'selected' : '' ?>>Featured</option>
          <option value="price_low" <?= $sort === 'price_low' ? 'selected' : '' ?>>Price: Low to High</option>
          <option value="price_high" <?= $sort === 'price_high' ? 'selected' : '' ?>>Price: High to Low</option>
          <option value="newest" <?= $sort === 'newest' ? 'selected' : '' ?>>Newest First</option>
        </select>
      </div>

      <div class="lg:col-span-5 flex flex-wrap items-center justify-between gap-4 pt-2 border-t border-gray-100">
        <p class="text-xs font-semibold text-gray-500">Showing <span class="text-gray-900 font-bold"><?= count($variants) ?></span> of <span class="text-gray-900 font-bold"><?= $total_items ?></span> items</p>
        <div class="flex items-center gap-2">
          <?php if ($search !== '' || $min_price !== null || $max_price !== null || $sort !== 'featured'): ?>
          <a href="products.php" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold rounded-xl transition-colors">Clear Filters</a>
          <?php endif; ?>
          <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-all shadow-md">Apply Filters</button>
        </div>
      </div>
    </form>
  </div>

  <!-- Products Grid -->
  <?php if (empty($variants)): ?>
    <div class="bg-white rounded-3xl p-16 text-center shadow-sm border border-gray-100 space-y-4">
      <div class="w-16 h-16 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mx-auto">
        <span class="material-symbols-outlined text-3xl">search_off</span>
      </div>
      <h3 class="text-xl font-bold text-gray-900">No matching products found</h3>
      <p class="text-sm text-gray-500 max-w-md mx-auto">Try adjusting your search keyword or clearing price filters to view our full catalog.</p>
      <a href="products.php" class="inline-block px-6 py-3 bg-emerald-600 text-white font-bold text-xs rounded-xl hover:bg-emerald-700 transition-colors">Reset Search & Filters</a>
    </div>
  <?php else: ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <?php foreach ($variants as $v): 
        $discount = $v['mrp_inr'] > $v['price_inr'] ? round((($v['mrp_inr'] - $v['price_inr']) / $v['mrp_inr']) * 100) : 0;
      ?>
      <div class="bg-white rounded-3xl p-5 border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col justify-between group overflow-hidden">
        
        <div>
          <!-- Image Box -->
          <div class="relative w-full h-56 rounded-2xl bg-gradient-to-br from-[#0c1f11] via-[#173922] to-[#0c1f11] flex items-center justify-center p-4 overflow-hidden mb-4">
            <?php if ($discount > 0): ?>
            <span class="absolute top-3 left-3 bg-emerald-600 text-white font-extrabold text-[10px] uppercase px-2.5 py-1 rounded-full shadow-md z-20"><?= $discount ?>% OFF</span>
            <?php endif; ?>
            <img src="<?= htmlspecialchars($v['image_path'] ?? 'img/product-box-bottle.jpg') ?>" alt="<?= htmlspecialchars($v['title']) ?>" class="max-h-full max-w-full object-contain relative z-10 drop-shadow-xl group-hover:scale-105 transition-transform duration-300 rounded-lg">
          </div>

          <!-- Product Details -->
          <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2.5 py-0.5 rounded-full inline-block mb-2"><?= htmlspecialchars($v['variant_name']) ?></span>
          <h3 class="font-bold text-gray-900 text-base mb-1 line-clamp-2 hover:text-emerald-700 transition-colors">
            <a href="product-details.php?id=<?= $v['product_id'] ?>"><?= htmlspecialchars($v['title']) ?></a>
          </h3>
          
          <div class="flex items-baseline gap-2 mb-3">
            <span class="text-xl font-extrabold text-emerald-700">$<?= number_format($v['price_inr'], 2) ?></span>
            <?php if ($v['mrp_inr'] > $v['price_inr']): ?>
            <span class="text-xs text-gray-400 line-through">$<?= number_format($v['mrp_inr'], 2) ?></span>
            <?php endif; ?>
          </div>
        </div>

        <!-- Card Buttons -->
        <div class="space-y-2 pt-2 border-t border-gray-100">
          <button onclick="addToCartAPI(<?= $v['variant_id'] ?>, 1)" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 rounded-xl text-xs transition-colors flex items-center justify-center gap-1.5 shadow-sm active:scale-95 cursor-pointer">
            <span class="material-symbols-outlined text-base">add_shopping_cart</span> Add to Cart
          </button>
          <a href="product-details.php?id=<?= $v['product_id'] ?>" class="w-full block bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-2 rounded-xl text-xs transition-colors text-center">
            View Details
          </a>
        </div>

      </div>
      <?php endforeach; ?>
    </div>

    <!-- Pagination Controls -->
    <?php if ($total_pages > 1): ?>
    <div class="flex items-center justify-center gap-2 pt-8">
      <?php if ($page > 1): ?>
      <a href="?page=<?= $page - 1 ?>&search=<?= urlencode($search) ?>&min_price=<?= $min_price ?>&max_price=<?= $max_price ?>&sort=<?= $sort ?>" class="px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 hover:bg-gray-50 flex items-center gap-1">
        <span class="material-symbols-outlined text-sm">arrow_back</span> Prev
      </a>
      <?php endif; ?>

      <?php for ($p = 1; $p <= $total_pages; $p++): ?>
      <a href="?page=<?= $p ?>&search=<?= urlencode($search) ?>&min_price=<?= $min_price ?>&max_price=<?= $max_price ?>&sort=<?= $sort ?>" class="w-10 h-10 rounded-xl flex items-center justify-center text-xs font-bold transition-all <?= $p === $page ? 'bg-emerald-600 text-white shadow-md' : 'bg-white border border-gray-200 text-gray-700 hover:bg-gray-50' ?>">
        <?= $p ?>
      </a>
      <?php endfor; ?>

      <?php if ($page < $total_pages): ?>
      <a href="?page=<?= $page + 1 ?>&search=<?= urlencode($search) ?>&min_price=<?= $min_price ?>&max_price=<?= $max_price ?>&sort=<?= $sort ?>" class="px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-xs font-semibold text-gray-700 hover:bg-gray-50 flex items-center gap-1">
        Next <span class="material-symbols-outlined text-sm">arrow_forward</span>
      </a>
      <?php endif; ?>
    </div>
    <?php endif; ?>

  <?php endif; ?>

</main>

<?php require_once __DIR__ . '/includes/store-footer.php'; ?>
