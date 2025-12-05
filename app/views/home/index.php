<?php
use App\Models\Product;
use App\Models\Post;

// Get featured products
$featuredProducts = Product::getFeatured(4);

// Get latest posts
$latestPosts = Post::getAll([
    'limit' => 3,
    'offset' => 0,
    'status' => 'published',
]);
?>
<!-- Hero Section -->
<section class="hero-section bg-gradient-to-r from-blue-600 to-purple-600 text-white py-20">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h1 class="text-5xl md:text-6xl font-bold mb-6">Chăm sóc thú cưng tận tâm</h1>
            <p class="text-xl mb-8 text-blue-100">Dịch vụ chuyên nghiệp, giá cả hợp lý. Đồng hành cùng bạn chăm sóc thú cưng yêu quý.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="<?= BASE_URL ?>products" class="px-8 py-3 bg-white text-blue-600 rounded-lg font-semibold hover:bg-blue-50 transition">
                    Mua sắm ngay
                </a>
                <a href="<?= BASE_URL ?>about" class="px-8 py-3 border-2 border-white rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition">
                    Tìm hiểu thêm
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">Tại sao chọn chúng tôi?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Chất lượng đảm bảo</h3>
                <p class="text-gray-600">Sản phẩm chính hãng, chất lượng cao được kiểm định nghiêm ngặt</p>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Giao hàng nhanh</h3>
                <p class="text-gray-600">Giao hàng tận nơi trong 24h tại TP.HCM, 2-5 ngày toàn quốc</p>
            </div>
            
            <div class="bg-white rounded-lg shadow-md p-6 text-center">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold mb-2">Giá cả hợp lý</h3>
                <p class="text-gray-600">Giá cả cạnh tranh, nhiều chương trình khuyến mãi hấp dẫn</p>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<?php if (!empty($featuredProducts)): ?>
<section class="py-16">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Sản phẩm nổi bật</h2>
            <a href="<?= BASE_URL ?>products" class="text-blue-600 hover:text-blue-800 font-semibold">
                Xem tất cả →
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($featuredProducts as $product): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    <a href="<?= BASE_URL ?>products/show?id=<?= $product['id'] ?>">
                        <div class="h-48 bg-gray-100 overflow-hidden">
                            <?php if (!empty($product['image_url'])): ?>
                                <img src="<?= htmlspecialchars($product['image_url']) ?>" 
                                     alt="<?= htmlspecialchars($product['name']) ?>" 
                                     class="w-full h-full object-cover lazy-load">
                            <?php endif; ?>
                        </div>
                        <div class="p-4">
                            <h3 class="font-semibold mb-2 line-clamp-2"><?= htmlspecialchars($product['name']) ?></h3>
                            <p class="text-blue-600 font-bold">
                                <?= number_format($product['sale_price'] ?? $product['price'], 0, ',', '.') ?> đ
                            </p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Latest Posts -->
<?php if (!empty($latestPosts)): ?>
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Tin tức mới nhất</h2>
            <a href="<?= BASE_URL ?>posts" class="text-blue-600 hover:text-blue-800 font-semibold">
                Xem tất cả →
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <?php foreach ($latestPosts as $post): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                    <a href="<?= BASE_URL ?>posts/show?id=<?= $post['id'] ?>">
                        <?php if (!empty($post['cover_image_url'])): ?>
                            <div class="h-48 bg-gray-100 overflow-hidden">
                                <img src="<?= htmlspecialchars($post['cover_image_url']) ?>" 
                                     alt="<?= htmlspecialchars($post['title']) ?>" 
                                     class="w-full h-full object-cover lazy-load">
                            </div>
                        <?php endif; ?>
                        <div class="p-4">
                            <h3 class="font-semibold mb-2 line-clamp-2"><?= htmlspecialchars($post['title']) ?></h3>
                            <?php if (!empty($post['summary'])): ?>
                                <p class="text-gray-600 text-sm line-clamp-2"><?= htmlspecialchars($post['summary']) ?></p>
                            <?php endif; ?>
                            <p class="text-xs text-gray-500 mt-2">
                                <?= date('d/m/Y', strtotime($post['published_at'] ?? $post['created_at'])) ?>
                            </p>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA Section -->
<section class="py-16 bg-blue-600 text-white">
    <div class="container mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold mb-4">Cần tư vấn?</h2>
        <p class="text-xl mb-8 text-blue-100">Liên hệ với chúng tôi để được hỗ trợ tốt nhất</p>
        <a href="<?= BASE_URL ?>contact" class="inline-block px-8 py-3 bg-white text-blue-600 rounded-lg font-semibold hover:bg-blue-50 transition">
            Liên hệ ngay
        </a>
    </div>
</section>

<script>
// Lazy loading
if ('loading' in HTMLImageElement.prototype) {
    document.querySelectorAll('img.lazy-load').forEach(img => {
        img.loading = 'lazy';
    });
} else {
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/lazysizes@5/lazysizes.min.js';
    document.body.appendChild(script);
}
</script>
