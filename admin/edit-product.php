<?php
require_once __DIR__ . '/includes/header.php';

$product_id = $_GET['id'] ?? null;
if (!$product_id) {
    echo "<script>window.location.href='products.php';</script>";
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    echo "<script>window.location.href='products.php';</script>";
    exit();
}

$stmtVars = $pdo->prepare("SELECT * FROM product_variants WHERE product_id = ? ORDER BY id ASC");
$stmtVars->execute([$product_id]);
$variants = $stmtVars->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="max-w-4xl mx-auto space-y-8">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-900">Edit Product Details</h3>
            <a href="products.php" class="text-sm font-semibold text-gray-500 hover:text-gray-900 transition-colors">Back to Inventory</a>
        </div>
        <div class="p-6">
            <form id="edit-product-form" class="space-y-6">
                <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">
                
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Product Title</label>
                    <input type="text" name="title" required value="<?= htmlspecialchars($product['title']) ?>" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 block p-3">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Product Description</label>
                    <textarea name="description" rows="4" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 block p-3"><?= htmlspecialchars($product['description']) ?></textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Status</label>
                    <select name="active" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 block p-3">
                        <option value="1" <?= $product['active'] == 1 ? 'selected' : '' ?>>Active (Visible on Store)</option>
                        <option value="0" <?= $product['active'] == 0 ? 'selected' : '' ?>>Inactive (Hidden)</option>
                    </select>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit" id="submit-btn" class="px-6 py-3 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">save</span> Save Product Details
                    </button>
                </div>
            </form>
        </div>
    </div>
    
    <!-- Variants & Images Management -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 space-y-6">
        <div class="border-b border-gray-100 pb-4">
            <h4 class="text-base font-bold text-gray-900">Existing Variants & Product Images</h4>
            <p class="text-sm text-gray-500">Update variant pricing, stock, display names, and upload custom variant images.</p>
        </div>
        
        <div class="space-y-4">
            <?php foreach ($variants as $v): 
                $imgPath = !empty($v['image_path']) ? $v['image_path'] : 'img/product-box-bottle.jpg';
            ?>
            <form class="edit-variant-form bg-gray-50/60 border border-gray-200 rounded-2xl p-5 shadow-sm space-y-4">
                <input type="hidden" name="variant_id" value="<?= $v['id'] ?>">
                
                <div class="flex flex-col md:flex-row gap-4 items-start md:items-center">
                    <!-- Image Thumbnail & Upload -->
                    <div class="flex items-center gap-3 shrink-0">
                        <div class="w-16 h-16 rounded-xl border border-gray-200 bg-white overflow-hidden shrink-0 flex items-center justify-center shadow-inner">
                            <img src="../<?= htmlspecialchars($imgPath) ?>" class="variant-thumb-img w-full h-full object-cover" alt="Variant Image">
                        </div>
                        <div>
                            <input type="hidden" name="image_path" value="<?= htmlspecialchars($imgPath) ?>" class="variant-image-path-field">
                            <label class="px-3 py-1.5 bg-white border border-gray-300 hover:border-emerald-500 text-gray-700 hover:text-emerald-700 text-xs font-semibold rounded-lg cursor-pointer transition-colors inline-flex items-center gap-1 shadow-sm">
                                <span class="material-symbols-outlined text-[16px]">upload_file</span> Change Image
                                <input type="file" accept="image/*" onchange="uploadExistingVariantImg(this)" class="hidden">
                            </label>
                            <p class="text-[11px] text-gray-400 mt-1 truncate max-w-[140px] img-path-label"><?= htmlspecialchars($imgPath) ?></p>
                        </div>
                    </div>

                    <!-- Fields -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 flex-1 w-full">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Display Name</label>
                            <input type="text" name="variant_name" value="<?= htmlspecialchars($v['variant_name']) ?>" required class="w-full bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 p-2">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Variant Key</label>
                            <input type="text" name="variant_key" value="<?= htmlspecialchars($v['variant_key']) ?>" required class="w-full bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 p-2">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Selling Price (₹)</label>
                            <input type="number" name="price_inr" value="<?= $v['price_inr'] ?>" required class="w-full bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 p-2">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">MRP (₹)</label>
                            <input type="number" name="mrp_inr" value="<?= $v['mrp_inr'] ?>" required class="w-full bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 p-2">
                        </div>
                    </div>

                    <button type="submit" class="px-4 py-2.5 bg-blue-600 text-white hover:bg-blue-700 font-semibold text-xs rounded-xl transition-colors shrink-0 flex items-center gap-1 shadow-sm">
                        <span class="material-symbols-outlined text-[16px]">save</span> Save Variant
                    </button>
                </div>
            </form>
            <?php endforeach; ?>
        </div>

        <!-- Add New Variant Form -->
        <div class="pt-6 border-t border-gray-100">
            <h4 class="text-base font-bold text-gray-900 mb-4">Add New Variant</h4>
            <form id="add-variant-form" class="bg-emerald-50/40 rounded-2xl p-5 border border-emerald-100 space-y-4">
                <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['id']) ?>">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-900 mb-1">Variant Key (e.g. 120ml)</label>
                        <input type="text" name="variant_key" required placeholder="120ml" class="w-full bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block p-2">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-900 mb-1">Display Name</label>
                        <input type="text" name="variant_name" required placeholder="e.g. 120 mL (2 Month Supply)" class="w-full bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block p-2">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-900 mb-1">Initial Stock</label>
                        <input type="number" name="stock_qty" required value="100" class="w-full bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block p-2">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-900 mb-1">Selling Price (₹)</label>
                        <input type="number" name="price_inr" required placeholder="2299" class="w-full bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block p-2">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-900 mb-1">MRP (₹)</label>
                        <input type="number" name="mrp_inr" required placeholder="3999" class="w-full bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block p-2">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-900 mb-1">Variant Image</label>
                        <div class="flex items-center gap-2">
                            <input type="hidden" name="image_path" value="img/product-box-bottle.jpg" id="new-variant-img-path">
                            <div class="w-9 h-9 rounded-lg border border-gray-200 bg-white overflow-hidden shrink-0 flex items-center justify-center">
                                <img src="../img/product-box-bottle.jpg" id="new-variant-img-preview" class="w-full h-full object-cover">
                            </div>
                            <label class="px-3 py-1.5 bg-white border border-gray-300 hover:border-emerald-500 text-gray-700 text-xs font-semibold rounded-lg cursor-pointer transition-colors inline-flex items-center gap-1 shrink-0">
                                <span class="material-symbols-outlined text-[16px]">upload_file</span> Choose Image
                                <input type="file" accept="image/*" onchange="uploadNewVariantImg(this)" class="hidden">
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end pt-2">
                    <button type="submit" id="add-variant-btn" class="px-5 py-2.5 bg-emerald-700 text-white text-sm font-semibold rounded-xl hover:bg-emerald-800 transition-colors flex items-center gap-1.5 shadow-sm">
                        <span class="material-symbols-outlined text-sm">add_circle</span> Add Variant
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
async function uploadExistingVariantImg(fileInput) {
    const file = fileInput.files[0];
    if (!file) return;
    
    const form = fileInput.closest('form');
    const previewImg = form.querySelector('.variant-thumb-img');
    const pathField = form.querySelector('.variant-image-path-field');
    const labelSpan = form.querySelector('.img-path-label');
    
    const formData = new FormData();
    formData.append('image', file);
    
    labelSpan.innerText = 'Uploading...';
    
    try {
        const res = await fetch('../api/admin-upload-image.php', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();
        
        if (data.success) {
            pathField.value = data.image_path;
            previewImg.src = '../' + data.image_path;
            labelSpan.innerText = data.image_path;
            showAdminToast('Variant image updated! Click Save Variant to apply.', 'success');
        } else {
            labelSpan.innerText = 'Upload failed';
            showAdminToast(data.message || 'Failed to upload image', 'error');
        }
    } catch (e) {
        labelSpan.innerText = 'Upload error';
        showAdminToast('Network error during upload', 'error');
    }
}

async function uploadNewVariantImg(fileInput) {
    const file = fileInput.files[0];
    if (!file) return;
    
    const previewImg = document.getElementById('new-variant-img-preview');
    const pathField = document.getElementById('new-variant-img-path');
    
    const formData = new FormData();
    formData.append('image', file);
    
    try {
        const res = await fetch('../api/admin-upload-image.php', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();
        
        if (data.success) {
            pathField.value = data.image_path;
            previewImg.src = '../' + data.image_path;
            showAdminToast('Variant image uploaded!', 'success');
        } else {
            showAdminToast(data.message || 'Upload failed', 'error');
        }
    } catch (e) {
        showAdminToast('Network error during upload', 'error');
    }
}

document.getElementById('edit-product-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('submit-btn');
    btn.disabled = true;
    btn.innerHTML = 'Saving...';
    
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData.entries());
    
    try {
        const res = await fetch('../api/admin-edit-product.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        const result = await res.json();
        
        if (result.success) {
            showAdminToast('Product details updated successfully!', 'success');
        } else {
            showAdminToast(result.message || 'Failed to update product', 'error');
        }
    } catch (err) {
        showAdminToast('Network error', 'error');
    } finally {
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-outlined text-sm">save</span> Save Product Details';
    }
});

document.getElementById('add-variant-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('add-variant-btn');
    btn.disabled = true;
    btn.innerHTML = 'Adding...';
    
    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData.entries());
    
    try {
        const res = await fetch('../api/admin-add-variant.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        const result = await res.json();
        
        if (result.success) {
            showAdminToast('Variant added successfully!', 'success');
            setTimeout(() => {
                window.location.reload();
            }, 800);
        } else {
            showAdminToast(result.message || 'Failed to add variant', 'error');
            btn.disabled = false;
            btn.innerHTML = '<span class="material-symbols-outlined text-sm">add_circle</span> Add Variant';
        }
    } catch (err) {
        showAdminToast('Network error', 'error');
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-outlined text-sm">add_circle</span> Add Variant';
    }
});

document.querySelectorAll('.edit-variant-form').forEach(form => {
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        const btn = this.querySelector('button[type="submit"]');
        const originalText = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = 'Saving...';
        
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());
        
        try {
            const res = await fetch('../api/admin-update-full-variant.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });
            const result = await res.json();
            
            if (result.success) {
                showAdminToast('Variant updated successfully!', 'success');
            } else {
                showAdminToast(result.message || 'Failed to update variant', 'error');
            }
        } catch (err) {
            showAdminToast('Network error', 'error');
        } finally {
            btn.disabled = false;
            btn.innerHTML = originalText;
        }
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
