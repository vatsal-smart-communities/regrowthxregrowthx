<?php
require_once __DIR__ . '/config/db.php';

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : null;

if (!$product_id) {
    header("Location: products.php");
    exit();
}

// Fetch Product
$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ? AND active = 1");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header("Location: products.php");
    exit();
}

// Fetch Variants
$stmtVars = $pdo->prepare("SELECT * FROM product_variants WHERE product_id = ? ORDER BY price_inr ASC");
$stmtVars->execute([$product_id]);
$variants = $stmtVars->fetchAll(PDO::FETCH_ASSOC);

if (empty($variants)) {
    header("Location: products.php");
    exit();
}

$defaultVariant = $variants[0];

// Fetch Related Products
$stmtRelated = $pdo->prepare("
    SELECT p.id as product_id, p.title, p.description, 
           pv.id as variant_id, pv.variant_name, pv.price_inr, pv.mrp_inr, pv.image_path
    FROM products p
    JOIN product_variants pv ON p.id = pv.product_id
    WHERE p.active = 1 AND p.id != ?
    GROUP BY p.id
    LIMIT 4
");
$stmtRelated->execute([$product_id]);
$relatedProducts = $stmtRelated->fetchAll(PDO::FETCH_ASSOC);

$pageTitle = htmlspecialchars($product['title']) . " | RegrowthX";
require_once __DIR__ . '/includes/store-header.php';
?>

<!-- PRODUCT DETAILS CONTAINER -->
<main class="pt-28 pb-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">

  <!-- Breadcrumbs -->
  <nav class="flex items-center gap-2 text-xs font-semibold text-gray-500">
    <a href="index.php" class="hover:text-emerald-700">Home</a>
    <span class="material-symbols-outlined text-xs">chevron_right</span>
    <a href="products.php" class="hover:text-emerald-700">Products</a>
    <span class="material-symbols-outlined text-xs">chevron_right</span>
    <span class="text-gray-900 font-bold truncate max-w-xs"><?= htmlspecialchars($product['title']) ?></span>
  </nav>

  <!-- Product Main Section -->
  <div class="bg-white rounded-3xl p-6 sm:p-10 border border-gray-100 shadow-sm grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">
    
    <!-- LEFT: Image Gallery -->
    <div class="space-y-4">
      <div class="relative w-full h-[400px] sm:h-[450px] rounded-3xl bg-gradient-to-br from-[#0c1f11] via-[#173922] to-[#0c1f11] flex items-center justify-center p-6 border border-emerald-900/30 overflow-hidden shadow-inner">
        <img id="main-product-img" src="<?= htmlspecialchars($defaultVariant['image_path'] ?? 'img/product-box-bottle.jpg') ?>" alt="<?= htmlspecialchars($product['title']) ?>" class="max-h-full max-w-full object-contain relative z-10 drop-shadow-2xl rounded-xl transition-all duration-300">
      </div>

      <!-- Thumbnails gallery -->
      <div class="grid grid-cols-4 gap-3">
        <button onclick="changeMainImage('<?= htmlspecialchars($defaultVariant['image_path'] ?? 'img/product-box-bottle.jpg') ?>', this)" class="thumb-btn border-2 border-emerald-600 rounded-2xl bg-[#0d1e12] p-1.5 h-20 flex items-center justify-center overflow-hidden focus:outline-none shadow-sm cursor-pointer">
          <img class="max-h-full max-w-full object-contain rounded-lg" src="<?= htmlspecialchars($defaultVariant['image_path'] ?? 'img/product-box-bottle.jpg') ?>" alt="Thumb 1">
        </button>
        <button onclick="changeMainImage('img/product-dropper.jpg', this)" class="thumb-btn border-2 border-gray-200 hover:border-emerald-400 rounded-2xl bg-[#0d1e12] p-1.5 h-20 flex items-center justify-center overflow-hidden focus:outline-none shadow-sm cursor-pointer">
          <img class="max-h-full max-w-full object-contain rounded-lg" src="img/product-dropper.jpg" alt="Thumb 2">
        </button>
        <button onclick="changeMainImage('img/routine-guide.jpg', this)" class="thumb-btn border-2 border-gray-200 hover:border-emerald-400 rounded-2xl bg-[#0d1e12] p-1.5 h-20 flex items-center justify-center overflow-hidden focus:outline-none shadow-sm cursor-pointer">
          <img class="max-h-full max-w-full object-contain rounded-lg" src="img/routine-guide.jpg" alt="Thumb 3">
        </button>
        <button onclick="changeMainImage('img/scalp-application.jpg', this)" class="thumb-btn border-2 border-gray-200 hover:border-emerald-400 rounded-2xl bg-[#0d1e12] p-1.5 h-20 flex items-center justify-center overflow-hidden focus:outline-none shadow-sm cursor-pointer">
          <img class="max-h-full max-w-full object-contain rounded-lg" src="img/scalp-application.jpg" alt="Thumb 4">
        </button>
      </div>
    </div>

    <!-- RIGHT: Product Details & Variant Selection -->
    <div class="space-y-6">
      
      <div>
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs font-bold uppercase tracking-wider mb-2">
          <span class="material-symbols-outlined text-xs">verified</span> Dermatologist Recommended
        </div>
        <h1 class="text-3xl sm:text-4xl font-extrabold text-gray-900 tracking-tight leading-tight"><?= htmlspecialchars($product['title']) ?></h1>
        
        <div class="flex items-center gap-3 mt-3">
          <div class="flex text-amber-400">
            <span class="material-symbols-outlined text-lg">star</span>
            <span class="material-symbols-outlined text-lg">star</span>
            <span class="material-symbols-outlined text-lg">star</span>
            <span class="material-symbols-outlined text-lg">star</span>
            <span class="material-symbols-outlined text-lg">star</span>
          </div>
          <span class="text-xs font-semibold text-gray-500">(12,480 Verified Buyer Reviews)</span>
        </div>
      </div>

      <!-- Pricing Display -->
      <div class="bg-gray-50/80 p-5 rounded-2xl border border-gray-100 flex items-baseline gap-4">
        <span id="display-price" class="text-3xl sm:text-4xl font-extrabold text-emerald-700">₹<?= number_format($defaultVariant['price_inr'], 0) ?></span>
        <span id="display-mrp" class="text-base text-gray-400 line-through">₹<?= number_format($defaultVariant['mrp_inr'], 0) ?></span>
        <span id="display-savings" class="bg-emerald-600 text-white text-xs font-extrabold px-2.5 py-1 rounded-full uppercase">Save <?= round((($defaultVariant['mrp_inr'] - $defaultVariant['price_inr']) / $defaultVariant['mrp_inr']) * 100) ?>%</span>
      </div>

      <!-- Description -->
      <p class="text-sm text-gray-600 leading-relaxed">
        <?= htmlspecialchars($product['description']) ?>
      </p>

      <!-- Select Variant Packs -->
      <div class="space-y-3 pt-2">
        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider">Select Variant Pack:</label>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="variants-selector-grid">
          <?php foreach ($variants as $idx => $var): ?>
          <label onclick="selectVariantCard(<?= htmlspecialchars(json_encode($var)) ?>)" class="variant-option-card cursor-pointer p-4 rounded-2xl border-2 transition-all flex items-center justify-between <?= $idx === 0 ? 'border-emerald-600 bg-emerald-50/40 shadow-sm' : 'border-gray-200 bg-white hover:border-gray-300' ?>">
            <div class="flex items-center gap-3">
              <input type="radio" name="variant_radio" value="<?= $var['id'] ?>" <?= $idx === 0 ? 'checked' : '' ?> class="accent-emerald-600 w-4 h-4">
              <div>
                <p class="font-bold text-sm text-gray-900"><?= htmlspecialchars($var['variant_name']) ?></p>
                <p class="text-xs text-gray-500">Stock: <?= $var['stock_qty'] > 0 ? 'In Stock (' . $var['stock_qty'] . ' available)' : 'Out of Stock' ?></p>
              </div>
            </div>
            <span class="font-bold text-sm text-emerald-700">₹<?= number_format($var['price_inr'], 0) ?></span>
          </label>
          <?php endforeach; ?>
        </div>
      </div>

      <!-- Quantity Selector -->
      <div class="flex items-center gap-4 pt-2">
        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">Quantity:</span>
        <div class="inline-flex items-center border border-emerald-300 rounded-xl bg-emerald-50/40 px-3 py-1.5">
          <button onclick="adjustQty(-1)" class="w-8 h-8 rounded-lg text-emerald-900 hover:bg-emerald-100 font-bold text-lg flex items-center justify-center transition-all cursor-pointer">-</button>
          <span id="selected-qty" class="w-10 text-center font-bold text-gray-900 text-base">1</span>
          <button onclick="adjustQty(1)" class="w-8 h-8 rounded-lg text-emerald-900 hover:bg-emerald-100 font-bold text-lg flex items-center justify-center transition-all cursor-pointer">+</button>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex flex-col sm:flex-row gap-3 pt-4 border-t border-gray-100">
        <button onclick="addCurrentVariantToCart()" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 px-6 rounded-2xl transition-all shadow-md active:scale-95 text-center text-sm cursor-pointer flex items-center justify-center gap-2">
          <span class="material-symbols-outlined text-lg">add_shopping_cart</span> Add to Cart
        </button>
        <button onclick="buyNowCurrentVariant()" class="flex-1 bg-gray-900 hover:bg-gray-800 text-white font-bold py-4 px-6 rounded-2xl transition-all shadow-md active:scale-95 text-center text-sm cursor-pointer flex items-center justify-center gap-2">
          <span class="material-symbols-outlined text-lg">bolt</span> Pay Now & Checkout
        </button>
      </div>

    </div>
  </div>

  <!-- Tabbed Product Information Section -->
  <div class="bg-white rounded-3xl p-6 sm:p-8 border border-gray-100 shadow-sm space-y-6">
    <div class="flex items-center justify-center gap-2 sm:gap-4 border-b border-gray-100 pb-4 flex-wrap">
      <button onclick="switchTab('overview')" id="tab-btn-overview" class="tab-btn bg-emerald-600 text-white font-bold px-6 py-2.5 rounded-full text-xs sm:text-sm shadow-sm transition-all cursor-pointer">Overview</button>
      <button onclick="switchTab('howtouse')" id="tab-btn-howtouse" class="tab-btn bg-gray-100 text-gray-600 hover:bg-gray-200 font-bold px-6 py-2.5 rounded-full text-xs sm:text-sm transition-all cursor-pointer">How to Use</button>
      <button onclick="switchTab('specs')" id="tab-btn-specs" class="tab-btn bg-gray-100 text-gray-600 hover:bg-gray-200 font-bold px-6 py-2.5 rounded-full text-xs sm:text-sm transition-all cursor-pointer">Specs & Formula</button>
      <button onclick="switchTab('reviews')" id="tab-btn-reviews" class="tab-btn bg-gray-100 text-gray-600 hover:bg-gray-200 font-bold px-6 py-2.5 rounded-full text-xs sm:text-sm transition-all cursor-pointer">Customer Reviews</button>
    </div>

    <div id="tab-content-overview" class="tab-pane space-y-4">
      <h3 class="text-xl font-extrabold text-gray-900">Clinically Proven Hair Regrowth Formula</h3>
      <p class="text-sm text-gray-600 leading-relaxed">
        RegrowthX Extra Strength 5% Minoxidil Solution is engineered specifically to target miniaturized hair follicles on the scalp. By dilating micro-vessels around hair roots, it reactivates dormant follicles and extends the active Anagen growth phase.
      </p>
    </div>

    <div id="tab-content-howtouse" class="tab-pane hidden space-y-4">
      <h3 class="text-xl font-extrabold text-gray-900">4-Step Application Routine</h3>
      <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 pt-2">
        <div class="bg-gray-50 p-4 rounded-2xl text-center border border-gray-100">
          <div class="w-8 h-8 bg-emerald-600 text-white font-bold text-xs rounded-full flex items-center justify-center mx-auto mb-2">1</div>
          <h4 class="font-bold text-gray-900 text-xs">Clean Scalp</h4>
          <p class="text-[11px] text-gray-500 mt-1">Ensure vertex scalp is completely dry.</p>
        </div>
        <div class="bg-gray-50 p-4 rounded-2xl text-center border border-gray-100">
          <div class="w-8 h-8 bg-emerald-600 text-white font-bold text-xs rounded-full flex items-center justify-center mx-auto mb-2">2</div>
          <h4 class="font-bold text-gray-900 text-xs">1 mL Dosage</h4>
          <p class="text-[11px] text-gray-500 mt-1">Fill included dropper to 1.0 mL mark.</p>
        </div>
        <div class="bg-gray-50 p-4 rounded-2xl text-center border border-gray-100">
          <div class="w-8 h-8 bg-emerald-600 text-white font-bold text-xs rounded-full flex items-center justify-center mx-auto mb-2">3</div>
          <h4 class="font-bold text-gray-900 text-xs">Apply Twice Daily</h4>
          <p class="text-[11px] text-gray-500 mt-1">Apply directly onto thinning areas morning & night.</p>
        </div>
        <div class="bg-gray-50 p-4 rounded-2xl text-center border border-gray-100">
          <div class="w-8 h-8 bg-emerald-600 text-white font-bold text-xs rounded-full flex items-center justify-center mx-auto mb-2">4</div>
          <h4 class="font-bold text-gray-900 text-xs">Gently Massage</h4>
          <p class="text-[11px] text-gray-500 mt-1">Massage into scalp for 30s and allow to dry.</p>
        </div>
      </div>
    </div>

    <div id="tab-content-specs" class="tab-pane hidden space-y-4">
      <h3 class="text-xl font-extrabold text-gray-900">Product Specifications</h3>
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-gray-600 border-collapse">
          <tbody>
            <tr class="border-b border-gray-100"><td class="py-3 px-4 font-bold text-gray-900 bg-gray-50 w-1/3">Active Ingredient</td><td class="py-3 px-4">Minoxidil 5% w/v USP</td></tr>
            <tr class="border-b border-gray-100"><td class="py-3 px-4 font-bold text-gray-900 bg-gray-50">Formulation Type</td><td class="py-3 px-4">Unscented, Fast-Drying Topical Liquid Solution</td></tr>
            <tr class="border-b border-gray-100"><td class="py-3 px-4 font-bold text-gray-900 bg-gray-50">Recommended Usage</td><td class="py-3 px-4">Topical Application Twice Daily (1 mL per dose)</td></tr>
          </tbody>
        </table>
      </div>
    </div>

    <div id="tab-content-reviews" class="tab-pane hidden space-y-4">
      <h3 class="text-xl font-extrabold text-gray-900">Customer Testimonials</h3>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 space-y-1">
          <div class="flex justify-between items-center"><span class="font-bold text-gray-900 text-xs">Robert T.</span><span class="text-xs text-amber-500 font-bold">★★★★★</span></div>
          <p class="text-xs text-gray-600">Seeing noticeable vertex regrowth after 10 weeks of daily application!</p>
        </div>
        <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 space-y-1">
          <div class="flex justify-between items-center"><span class="font-bold text-gray-900 text-xs">Marcus V.</span><span class="text-xs text-amber-500 font-bold">★★★★★</span></div>
          <p class="text-xs text-gray-600">Great non-greasy solution. Highly recommended!</p>
        </div>
      </div>
    </div>
  </div>

  <!-- Related Products Section -->
  <?php if (!empty($relatedProducts)): ?>
  <div class="space-y-6">
    <h3 class="text-2xl font-extrabold text-gray-900">You Might Also Like</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      <?php foreach ($relatedProducts as $rp): ?>
      <div class="bg-white rounded-3xl p-5 border border-gray-100 shadow-sm hover:shadow-lg transition-all flex flex-col justify-between">
        <div>
          <div class="relative w-full h-44 rounded-2xl bg-gradient-to-br from-[#0c1f11] via-[#173922] to-[#0c1f11] flex items-center justify-center p-3 mb-3">
            <img src="<?= htmlspecialchars($rp['image_path'] ?? 'img/product-box-bottle.jpg') ?>" class="max-h-full max-w-full object-contain rounded-lg">
          </div>
          <h4 class="font-bold text-gray-900 text-sm line-clamp-1"><?= htmlspecialchars($rp['title']) ?></h4>
          <p class="text-xs text-gray-500 mb-2"><?= htmlspecialchars($rp['variant_name']) ?></p>
          <p class="font-extrabold text-emerald-700 text-base">₹<?= number_format($rp['price_inr'], 0) ?></p>
        </div>
        <a href="product-details.php?id=<?= $rp['product_id'] ?>" class="w-full mt-3 block bg-gray-100 hover:bg-gray-200 text-gray-800 text-xs font-semibold py-2 rounded-xl text-center transition-colors">
          View Details
        </a>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

</main>

<script>
  let activeVariant = <?= json_encode($defaultVariant) ?>;
  let selectedQty = 1;

  function changeMainImage(src, btn) {
    document.getElementById('main-product-img').src = src;
    document.querySelectorAll('.thumb-btn').forEach(b => {
      b.className = "thumb-btn border-2 border-gray-200 hover:border-emerald-400 rounded-2xl bg-[#0d1e12] p-1.5 h-20 flex items-center justify-center overflow-hidden focus:outline-none shadow-sm cursor-pointer";
    });
    btn.className = "thumb-btn border-2 border-emerald-600 rounded-2xl bg-[#0d1e12] p-1.5 h-20 flex items-center justify-center overflow-hidden focus:outline-none shadow-sm cursor-pointer";
  }

  function selectVariantCard(variantData) {
    activeVariant = variantData;
    
    document.querySelectorAll('.variant-option-card').forEach(card => {
      const radio = card.querySelector('input[type="radio"]');
      if (radio && parseInt(radio.value) === parseInt(variantData.id)) {
        radio.checked = true;
        card.className = "variant-option-card cursor-pointer p-4 rounded-2xl border-2 border-emerald-600 bg-emerald-50/40 shadow-sm transition-all flex items-center justify-between";
      } else {
        card.className = "variant-option-card cursor-pointer p-4 rounded-2xl border-2 border-gray-200 bg-white hover:border-gray-300 transition-all flex items-center justify-between";
      }
    });

    document.getElementById('display-price').innerText = '₹' + Number(activeVariant.price_inr).toLocaleString('en-IN');
    document.getElementById('display-mrp').innerText = '₹' + Number(activeVariant.mrp_inr).toLocaleString('en-IN');
    
    const discount = activeVariant.mrp_inr > activeVariant.price_inr ? Math.round(((activeVariant.mrp_inr - activeVariant.price_inr) / activeVariant.mrp_inr) * 100) : 0;
    document.getElementById('display-savings').innerText = 'Save ' + discount + '%';

    if (activeVariant.image_path) {
      changeMainImage(activeVariant.image_path, document.querySelectorAll('.thumb-btn')[0]);
    }
  }

  function adjustQty(amount) {
    selectedQty += amount;
    if (selectedQty < 1) selectedQty = 1;
    document.getElementById('selected-qty').innerText = selectedQty;
  }

  async function addCurrentVariantToCart() {
    if (!activeVariant || !activeVariant.id) return;
    await addToCartAPI(activeVariant.id, selectedQty);
  }

  async function buyNowCurrentVariant() {
    await addCurrentVariantToCart();
    window.location.href = 'checkout.php';
  }

  function switchTab(tabName) {
    const tabs = ['overview', 'howtouse', 'specs', 'reviews'];
    tabs.forEach(t => {
      const btn = document.getElementById('tab-btn-' + t);
      const pane = document.getElementById('tab-content-' + t);
      if (t === tabName) {
        btn.className = "tab-btn bg-emerald-600 text-white font-bold px-6 py-2.5 rounded-full text-xs sm:text-sm shadow-sm transition-all cursor-pointer";
        pane.classList.remove('hidden');
      } else {
        btn.className = "tab-btn bg-gray-100 text-gray-600 hover:bg-gray-200 font-bold px-6 py-2.5 rounded-full text-xs sm:text-sm transition-all cursor-pointer";
        pane.classList.add('hidden');
      }
    });
  }
</script>

<?php require_once __DIR__ . '/includes/store-footer.php'; ?>
