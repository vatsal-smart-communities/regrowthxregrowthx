<?php
require_once __DIR__ . '/includes/header.php';

// Fetch reviews
$stmt = $pdo->query("
    SELECT r.*, p.title as product_title 
    FROM reviews r 
    JOIN products p ON r.product_id = p.id 
    ORDER BY r.created_at DESC
");
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="mb-6 flex items-center justify-between">
    <div>
        <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Customer Reviews</h2>
        <p class="text-sm text-gray-500 mt-1">Manage and moderate product reviews submitted by customers.</p>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-100 text-xs uppercase tracking-wider text-gray-500">
                    <th class="px-6 py-4 font-semibold">Reviewer</th>
                    <th class="px-6 py-4 font-semibold">Product</th>
                    <th class="px-6 py-4 font-semibold">Rating & Review</th>
                    <th class="px-6 py-4 font-semibold">Status</th>
                    <th class="px-6 py-4 font-semibold text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php if (empty($reviews)): ?>
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-gray-500">No reviews found.</td>
                </tr>
                <?php else: ?>
                    <?php foreach ($reviews as $rev): ?>
                    <tr class="hover:bg-gray-50 transition-colors" id="review-row-<?= $rev['id'] ?>">
                        <td class="px-6 py-4">
                            <p class="text-sm font-bold text-gray-900"><?= htmlspecialchars($rev['reviewer_name']) ?></p>
                            <p class="text-xs text-gray-500"><?= htmlspecialchars($rev['reviewer_email']) ?></p>
                            <p class="text-[10px] text-gray-400 mt-1"><?= date('M j, Y g:i A', strtotime($rev['created_at'])) ?></p>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <?= htmlspecialchars($rev['product_title']) ?>
                        </td>
                        <td class="px-6 py-4 max-w-xs">
                            <div class="text-amber-500 text-xs tracking-widest mb-1 font-bold">
                                <?= str_repeat('★', $rev['rating']) ?><?= str_repeat('☆', 5 - $rev['rating']) ?>
                            </div>
                            <p class="text-sm text-gray-800 line-clamp-2" title="<?= htmlspecialchars($rev['review_text']) ?>">
                                <?= htmlspecialchars($rev['review_text']) ?>
                            </p>
                        </td>
                        <td class="px-6 py-4">
                            <?php 
                                $status = $rev['status'];
                                $badgeClass = match($status) {
                                    'pending' => 'bg-amber-100 text-amber-800',
                                    'approved' => 'bg-emerald-100 text-emerald-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                    default => 'bg-gray-100 text-gray-800'
                                };
                            ?>
                            <span id="status-badge-<?= $rev['id'] ?>" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wide <?= $badgeClass ?>">
                                <?= htmlspecialchars($status) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-2 whitespace-nowrap">
                            <?php if($status === 'pending'): ?>
                                <button onclick="moderateReview(<?= $rev['id'] ?>, 'approve')" class="p-2 bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white rounded-lg transition-colors cursor-pointer" title="Approve">
                                    <span class="material-symbols-outlined text-lg block">check_circle</span>
                                </button>
                                <button onclick="moderateReview(<?= $rev['id'] ?>, 'reject')" class="p-2 bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white rounded-lg transition-colors cursor-pointer" title="Reject">
                                    <span class="material-symbols-outlined text-lg block">cancel</span>
                                </button>
                            <?php endif; ?>
                            <button onclick="moderateReview(<?= $rev['id'] ?>, 'delete')" class="p-2 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg transition-colors cursor-pointer" title="Delete">
                                <span class="material-symbols-outlined text-lg block">delete</span>
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
async function moderateReview(id, action) {
    if (action === 'delete' && !confirm('Are you sure you want to delete this review entirely?')) {
        return;
    }
    
    try {
        const res = await fetch('api/moderate-review.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ review_id: id, action: action })
        });
        const data = await res.json();
        
        if (data.success) {
            if (action === 'delete') {
                document.getElementById('review-row-' + id).remove();
            } else {
                window.location.reload(); // Reload to update badges and buttons
            }
        } else {
            alert(data.message || 'Error updating review');
        }
    } catch (e) {
        alert('Network error');
    }
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
