<?php
$products = $products ?? [];
$categories = $categories ?? [];
$search = $search ?? '';
$categoryId = $categoryId ?? 0;
$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;
?>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl md:text-4xl font-bold mb-8 text-center text-gray-800">Sản phẩm</h1>
    
    <!-- Filters -->
    <div class="mb-6 bg-white rounded-lg shadow-md p-4">
        <form method="get" action="<?= BASE_URL ?>products" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" 
                       name="search" 
                       value="<?= htmlspecialchars($search) ?>" 
                       placeholder="Tìm kiếm sản phẩm..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <?php if (!empty($categories)): ?>
                <select name="category" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Tất cả danh mục</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $categoryId == $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
            <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Tìm kiếm
            </button>
        </form>
    </div>
    
    <!-- Products Grid -->
    <?php if (!empty($products)): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php foreach ($products as $product): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    <div class="relative h-48 bg-gray-100 overflow-hidden">
                        <?php if (!empty($product['image_url'])): ?>
                            <img src="<?= htmlspecialchars($product['image_url']) ?>" 
                                 alt="<?= htmlspecialchars($product['name']) ?>" 
                                 class="w-full h-full object-cover lazy-load">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-gray-400">
                                <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($product['sale_price'])): ?>
                            <span class="absolute top-2 right-2 bg-red-500 text-white px-2 py-1 rounded text-sm font-semibold">
                                -<?= round((($product['price'] - $product['sale_price']) / $product['price']) * 100) ?>%
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-2 text-gray-800 line-clamp-2">
                            <?= htmlspecialchars($product['name']) ?>
                        </h3>
                        
                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                            <?= htmlspecialchars(mb_substr($product['description'] ?? '', 0, 100)) ?><?= mb_strlen($product['description'] ?? '') > 100 ? '...' : '' ?>
                        </p>
                        
                        <div class="flex items-center justify-between mb-3">
                            <div>
                                <?php if (!empty($product['sale_price'])): ?>
                                    <span class="text-lg font-bold text-red-600">
                                        <?= number_format($product['sale_price'], 0, ',', '.') ?> đ
                                    </span>
                                    <span class="text-sm text-gray-500 line-through ml-2">
                                        <?= number_format($product['price'], 0, ',', '.') ?> đ
                                    </span>
                                <?php else: ?>
                                    <span class="text-lg font-bold text-blue-600">
                                        <?= number_format($product['price'], 0, ',', '.') ?> đ
                                    </span>
                                <?php endif; ?>
                            </div>
                            <span class="text-xs text-gray-500">
                                Còn: <?= $product['stock_quantity'] ?>
                            </span>
                        </div>
                        
                        <div class="flex gap-2">
                            <a href="<?= BASE_URL ?>products/show?id=<?= $product['id'] ?>" 
                               class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-center">
                                Xem chi tiết
                            </a>
                            <?php if ($product['stock_quantity'] > 0): ?>
                                <form method="post" action="<?= BASE_URL ?>cart/add" class="flex-1">
                                    <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                                        <svg class="w-5 h-5 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                    </button>
                                </form>
                            <?php else: ?>
                                <button disabled class="flex-1 px-4 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed">
                                    Hết hàng
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="mt-8 flex justify-center gap-2">
                <?php if ($currentPage > 1): ?>
                    <a href="<?= BASE_URL ?>products?p=<?= $currentPage - 1 ?>&search=<?= urlencode($search) ?>&category=<?= $categoryId ?>" 
                       class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Trước
                    </a>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php if ($i == $currentPage): ?>
                        <span class="px-4 py-2 bg-blue-600 text-white rounded-lg"><?= $i ?></span>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>products?p=<?= $i ?>&search=<?= urlencode($search) ?>&category=<?= $categoryId ?>" 
                           class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                            <?= $i ?>
                        </a>
                    <?php endif; ?>
                <?php endfor; ?>
                
                <?php if ($currentPage < $totalPages): ?>
                    <a href="<?= BASE_URL ?>products?p=<?= $currentPage + 1 ?>&search=<?= urlencode($search) ?>&category=<?= $categoryId ?>" 
                       class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Sau
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Không tìm thấy sản phẩm</h3>
            <p class="text-gray-600 mb-4">
                <?= !empty($search) ? 'Thử tìm kiếm với từ khóa khác.' : 'Chưa có sản phẩm nào.' ?>
            </p>
            <?php if (!empty($search)): ?>
                <a href="<?= BASE_URL ?>products" class="inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Xem tất cả sản phẩm
                </a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>

<script>
// Lazy loading
if ('loading' in HTMLImageElement.prototype) {
    document.querySelectorAll('img.lazy-load').forEach(img => {
        img.loading = 'lazy';
    });
}
</script>

