<?php
require_once __DIR__ . '/includes/header.php';
requirePermission('manage_products');
?>

<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <h3 class="text-lg font-bold text-gray-900">Add New Product</h3>
            <a href="products.php" class="text-sm font-semibold text-gray-500 hover:text-gray-900 transition-colors">Back to Inventory</a>
        </div>
        <div class="p-6">
            <form id="add-product-form" class="space-y-6">
                
                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Product Title</label>
                    <input type="text" name="title" required placeholder="e.g., RegrowthX 5% Minoxidil Hair Serum" class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 block p-3">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-900 mb-2">Product Description</label>
                    <textarea name="description" rows="4" placeholder="Enter product description and formula details..." class="w-full bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-emerald-500 focus:border-emerald-500 block p-3"></textarea>
                </div>

                <div class="border-t border-gray-100 pt-6 mt-6">
                    <div class="flex justify-between items-center mb-4">
                        <div>
                            <h4 class="text-base font-bold text-gray-900">Product Variants & Images</h4>
                            <p class="text-sm text-gray-500">Add at least one variant (e.g., "60 mL Bottle") and upload its image.</p>
                        </div>
                        <button type="button" onclick="addVariantRow()" class="px-3.5 py-2 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 text-sm font-semibold rounded-xl transition-colors flex items-center gap-1.5">
                            <span class="material-symbols-outlined text-[18px]">add</span> Add Variant
                        </button>
                    </div>
                    
                    <div id="variants-container" class="space-y-4">
                        <div class="variant-row bg-gray-50/70 p-4 rounded-xl border border-gray-200 relative space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Variant Key</label>
                                    <input type="text" name="variant_key[]" required placeholder="e.g. 60ml" class="w-full bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block p-2">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Display Name</label>
                                    <input type="text" name="variant_name[]" required placeholder="60 mL Supply" class="w-full bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block p-2">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Selling Price ($)</label>
                                    <input type="number" step="0.01" name="price_inr[]" required placeholder="19.99" class="w-full bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block p-2">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">MRP ($)</label>
                                    <input type="number" step="0.01" name="mrp_inr[]" required placeholder="34.99" class="w-full bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block p-2">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Initial Stock</label>
                                    <div class="flex gap-2">
                                        <input type="number" name="stock_qty[]" required value="100" class="w-full bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block p-2">
                                        <button type="button" onclick="removeVariantRow(this)" class="w-9 h-9 shrink-0 bg-red-50 text-red-500 hover:bg-red-500 hover:text-white rounded-lg flex items-center justify-center transition-colors">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Image Upload & Preview Bar -->
                            <div class="flex items-center gap-4 pt-2 border-t border-gray-200/60">
                                <div class="w-12 h-12 rounded-lg border border-gray-200 bg-white overflow-hidden shrink-0 flex items-center justify-center">
                                    <img src="../img/product-box-bottle.jpg" class="variant-img-preview w-full h-full object-cover" alt="Preview">
                                </div>
                                <div class="flex-1 min-w-0 flex flex-col sm:flex-row sm:items-center gap-2">
                                    <input type="hidden" name="image_path[]" value="img/product-box-bottle.jpg" class="variant-img-path-input">
                                    <label class="px-3 py-1.5 bg-white border border-gray-300 hover:border-emerald-500 text-gray-700 hover:text-emerald-700 text-xs font-semibold rounded-lg cursor-pointer transition-colors inline-flex items-center gap-1.5 shrink-0 shadow-sm">
                                        <span class="material-symbols-outlined text-[16px]">upload_file</span> Choose Image File
                                        <input type="file" accept="image/*" onchange="uploadImageFile(this)" class="hidden">
                                    </label>
                                    <span class="text-xs text-gray-500 truncate img-filename-display">Default: img/product-box-bottle.jpg</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-4 flex justify-end">
                    <button type="submit" id="submit-btn" class="px-6 py-3 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">save</span> Save Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
async function uploadImageFile(fileInput) {
    const file = fileInput.files[0];
    if (!file) return;
    
    const row = fileInput.closest('.variant-row');
    const previewImg = row.querySelector('.variant-img-preview');
    const pathInput = row.querySelector('.variant-img-path-input');
    const displaySpan = row.querySelector('.img-filename-display');
    
    const formData = new FormData();
    formData.append('image', file);
    
    displaySpan.innerText = 'Uploading...';
    
    try {
        const res = await fetch('../api/admin-upload-image.php', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();
        
        if (data.success) {
            pathInput.value = data.image_path;
            previewImg.src = '../' + data.image_path;
            displaySpan.innerText = 'Uploaded: ' + data.image_path;
            showAdminToast('Image uploaded successfully!', 'success');
        } else {
            displaySpan.innerText = 'Upload failed';
            showAdminToast(data.message || 'Image upload failed', 'error');
        }
    } catch (e) {
        displaySpan.innerText = 'Upload error';
        showAdminToast('Network error while uploading image', 'error');
    }
}

function addVariantRow() {
    const container = document.getElementById('variants-container');
    const newRow = document.createElement('div');
    newRow.className = 'variant-row bg-gray-50/70 p-4 rounded-xl border border-gray-200 relative space-y-4';
    newRow.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-5 gap-3">
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Variant Key</label>
                <input type="text" name="variant_key[]" required placeholder="e.g. 120ml" class="w-full bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block p-2">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Display Name</label>
                <input type="text" name="variant_name[]" required placeholder="120 mL Supply" class="w-full bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block p-2">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Selling Price (₹)</label>
                <input type="number" name="price_inr[]" required placeholder="2299" class="w-full bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block p-2">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">MRP (₹)</label>
                <input type="number" name="mrp_inr[]" required placeholder="3999" class="w-full bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block p-2">
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-700 mb-1">Initial Stock</label>
                <div class="flex gap-2">
                    <input type="number" name="stock_qty[]" required value="100" class="w-full bg-white border border-gray-200 text-gray-900 text-sm rounded-lg focus:ring-emerald-500 focus:border-emerald-500 block p-2">
                    <button type="button" onclick="removeVariantRow(this)" class="w-9 h-9 shrink-0 bg-red-50 text-red-500 hover:bg-red-500 hover:text-white rounded-lg flex items-center justify-center transition-colors">
                        <span class="material-symbols-outlined text-[18px]">delete</span>
                    </button>
                </div>
            </div>
        </div>
        
        <div class="flex items-center gap-4 pt-2 border-t border-gray-200/60">
            <div class="w-12 h-12 rounded-lg border border-gray-200 bg-white overflow-hidden shrink-0 flex items-center justify-center">
                <img src="../img/product-box-bottle.jpg" class="variant-img-preview w-full h-full object-cover" alt="Preview">
            </div>
            <div class="flex-1 min-w-0 flex flex-col sm:flex-row sm:items-center gap-2">
                <input type="hidden" name="image_path[]" value="img/product-box-bottle.jpg" class="variant-img-path-input">
                <label class="px-3 py-1.5 bg-white border border-gray-300 hover:border-emerald-500 text-gray-700 hover:text-emerald-700 text-xs font-semibold rounded-lg cursor-pointer transition-colors inline-flex items-center gap-1.5 shrink-0 shadow-sm">
                    <span class="material-symbols-outlined text-[16px]">upload_file</span> Choose Image File
                    <input type="file" accept="image/*" onchange="uploadImageFile(this)" class="hidden">
                </label>
                <span class="text-xs text-gray-500 truncate img-filename-display">Default: img/product-box-bottle.jpg</span>
            </div>
        </div>
    `;
    container.appendChild(newRow);
}

function removeVariantRow(btn) {
    const rows = document.querySelectorAll('.variant-row');
    if (rows.length > 1) {
        btn.closest('.variant-row').remove();
    } else {
        alert("You must have at least one variant.");
    }
}

document.getElementById('add-product-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('submit-btn');
    btn.disabled = true;
    btn.innerHTML = 'Saving...';
    
    const form = e.target;
    const formData = new FormData(form);
    
    const payload = {
        title: formData.get('title'),
        description: formData.get('description'),
        variant_key: formData.getAll('variant_key[]'),
        variant_name: formData.getAll('variant_name[]'),
        price_inr: formData.getAll('price_inr[]'),
        mrp_inr: formData.getAll('mrp_inr[]'),
        stock_qty: formData.getAll('stock_qty[]'),
        image_path: formData.getAll('image_path[]')
    };
    
    try {
        const res = await fetch('../api/admin-add-product.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });
        const result = await res.json();
        
        if (result.success) {
            showAdminToast('Product created successfully!', 'success');
            setTimeout(() => {
                window.location.href = 'products.php';
            }, 1000);
        } else {
            showAdminToast(result.message || 'Failed to create product', 'error');
            btn.disabled = false;
            btn.innerHTML = '<span class="material-symbols-outlined text-sm">save</span> Save Product';
        }
    } catch (err) {
        showAdminToast('Network error', 'error');
        btn.disabled = false;
        btn.innerHTML = '<span class="material-symbols-outlined text-sm">save</span> Save Product';
    }
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
