<?php
$product = $product ?? null;
$relatedProducts = $relatedProducts ?? [];
if (!$product) {
    echo '<div class="container mx-auto px-4 py-8"><p>Sản phẩm không tồn tại.</p></div>';
    return;
}
?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6 md:p-8">
                <!-- Product Image -->
                <div>
                    <?php if (!empty($product['image_url'])): ?>
                        <img src="<?= htmlspecialchars($product['image_url']) ?>" 
                             alt="<?= htmlspecialchars($product['name']) ?>" 
                             class="w-full rounded-lg lazy-load">
                    <?php else: ?>
                        <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                            <span class="text-gray-400">Không có hình ảnh</span>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Product Info -->
                <div>
                    <h1 class="text-3xl font-bold mb-4 text-gray-800"><?= htmlspecialchars($product['name']) ?></h1>
                    
                    <?php if (!empty($product['category_name'])): ?>
                        <p class="text-gray-600 mb-4">
                            Danh mục: <a href="<?= BASE_URL ?>products?category=<?= $product['category_id'] ?>" class="text-blue-600 hover:underline">
                                <?= htmlspecialchars($product['category_name']) ?>
                            </a>
                        </p>
                    <?php endif; ?>
                    
                    <div class="mb-6">
                        <?php if (!empty($product['sale_price'])): ?>
                            <div class="flex items-center gap-4 mb-2">
                                <span class="text-3xl font-bold text-red-600">
                                    <?= number_format($product['sale_price'], 0, ',', '.') ?> đ
                                </span>
                                <span class="text-xl text-gray-500 line-through">
                                    <?= number_format($product['price'], 0, ',', '.') ?> đ
                                </span>
                                <span class="bg-red-100 text-red-800 px-3 py-1 rounded text-sm font-semibold">
                                    Giảm <?= round((($product['price'] - $product['sale_price']) / $product['price']) * 100) ?>%
                                </span>
                            </div>
                        <?php else: ?>
                            <span class="text-3xl font-bold text-blue-600">
                                <?= number_format($product['price'], 0, ',', '.') ?> đ
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (!empty($product['description'])): ?>
                        <div class="mb-6">
                            <h3 class="font-semibold mb-2">Mô tả sản phẩm:</h3>
                            <div class="prose max-w-none text-gray-700">
                                <?= nl2br(htmlspecialchars($product['description'])) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="mb-6">
                        <p class="mb-2">
                            <span class="font-semibold">Tình trạng:</span>
                            <?php if ($product['stock_quantity'] > 0): ?>
                                <span class="text-green-600">Còn hàng (<?= $product['stock_quantity'] ?> sản phẩm)</span>
                            <?php else: ?>
                                <span class="text-red-600">Hết hàng</span>
                            <?php endif; ?>
                        </p>
                        <?php if (!empty($product['sku'])): ?>
                            <p class="text-sm text-gray-600">Mã sản phẩm: <?= htmlspecialchars($product['sku']) ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($product['stock_quantity'] > 0): ?>
                        <form method="post" action="<?= BASE_URL ?>cart/add" class="flex gap-4">
                            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                            <input type="number" name="quantity" value="1" min="1" max="<?= $product['stock_quantity'] ?>" 
                                   class="w-20 px-3 py-2 border border-gray-300 rounded-lg text-center">
                            <button type="submit" class="flex-1 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-semibold">
                                Thêm vào giỏ hàng
                            </button>
                        </form>
                    <?php else: ?>
                        <button disabled class="w-full px-6 py-3 bg-gray-400 text-white rounded-lg cursor-not-allowed font-semibold">
                            Sản phẩm đã hết hàng
                        </button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Related Products -->
        <?php if (!empty($relatedProducts)): ?>
            <div class="mt-12">
                <h2 class="text-2xl font-bold mb-6">Sản phẩm liên quan</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <?php foreach ($relatedProducts as $related): ?>
                        <?php if ($related['id'] != $product['id']): ?>
                            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                                <a href="<?= BASE_URL ?>products/show?id=<?= $related['id'] ?>">
                                    <div class="h-48 bg-gray-100 overflow-hidden">
                                        <?php if (!empty($related['image_url'])): ?>
                                            <img src="<?= htmlspecialchars($related['image_url']) ?>" 
                                                 alt="<?= htmlspecialchars($related['name']) ?>" 
                                                 class="w-full h-full object-cover lazy-load">
                                        <?php endif; ?>
                                    </div>
                                    <div class="p-4">
                                        <h3 class="font-semibold mb-2 line-clamp-2"><?= htmlspecialchars($related['name']) ?></h3>
                                        <p class="text-blue-600 font-bold">
                                            <?= number_format($related['sale_price'] ?? $related['price'], 0, ',', '.') ?> đ
                                        </p>
                                    </div>
                                </a>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Lazy loading
if ('loading' in HTMLImageElement.prototype) {
    document.querySelectorAll('img.lazy-load').forEach(img => {
        img.loading = 'lazy';
    });
}
</script>

