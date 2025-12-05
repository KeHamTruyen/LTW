<?php
$cartItems = $cartItems ?? [];
$total = $total ?? 0;
?>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Giỏ hàng của bạn</h1>
    
    <?php if (empty($cartItems)): ?>
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Giỏ hàng trống</h3>
            <p class="text-gray-600 mb-6">Bạn chưa có sản phẩm nào trong giỏ hàng.</p>
            <a href="<?= BASE_URL ?>products" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Mua sắm ngay
            </a>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Sản phẩm</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Giá</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Số lượng</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">Thành tiền</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php foreach ($cartItems as $item): ?>
                            <?php
                            $itemPrice = $item['sale_price'] ?? $item['price'];
                            $itemTotal = $itemPrice * $item['quantity'];
                            ?>
                            <tr>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <?php if (!empty($item['image_url'])): ?>
                                            <img src="<?= htmlspecialchars($item['image_url']) ?>" 
                                                 alt="<?= htmlspecialchars($item['name']) ?>" 
                                                 class="w-16 h-16 object-cover rounded lazy-load">
                                        <?php endif; ?>
                                        <div>
                                            <h4 class="font-semibold text-gray-800"><?= htmlspecialchars($item['name']) ?></h4>
                                            <?php if (!empty($item['slug'])): ?>
                                                <a href="<?= BASE_URL ?>products/show?id=<?= $item['product_id'] ?>" 
                                                   class="text-sm text-blue-600 hover:underline">Xem chi tiết</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <?php if (!empty($item['sale_price'])): ?>
                                        <span class="text-red-600 font-semibold">
                                            <?= number_format($item['sale_price'], 0, ',', '.') ?> đ
                                        </span>
                                        <div class="text-sm text-gray-500 line-through">
                                            <?= number_format($item['price'], 0, ',', '.') ?> đ
                                        </div>
                                    <?php else: ?>
                                        <span class="font-semibold">
                                            <?= number_format($item['price'], 0, ',', '.') ?> đ
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4">
                                    <form method="post" action="<?= BASE_URL ?>cart/update" class="flex items-center justify-center gap-2">
                                        <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
                                        <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                                        <input type="number" 
                                               name="quantity" 
                                               value="<?= $item['quantity'] ?>" 
                                               min="1" 
                                               max="<?= $item['stock_quantity'] ?>"
                                               class="w-20 px-2 py-1 border border-gray-300 rounded text-center"
                                               onchange="this.form.submit()">
                                    </form>
                                    <p class="text-xs text-gray-500 mt-1 text-center">
                                        Còn: <?= $item['stock_quantity'] ?>
                                    </p>
                                </td>
                                <td class="px-6 py-4 text-right font-semibold">
                                    <?= number_format($itemTotal, 0, ',', '.') ?> đ
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <form method="post" action="<?= BASE_URL ?>cart/remove" 
                                          onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này?');">
                                        <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
                                        <input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
                                        <button type="submit" class="text-red-600 hover:text-red-800">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-right font-semibold text-gray-700">Tổng cộng:</td>
                            <td colspan="2" class="px-6 py-4 text-right">
                                <span class="text-2xl font-bold text-red-600">
                                    <?= number_format($total, 0, ',', '.') ?> đ
                                </span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            
            <div class="p-6 bg-gray-50 flex justify-between items-center">
                <a href="<?= BASE_URL ?>products" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-white transition">
                    Tiếp tục mua sắm
                </a>
                <a href="<?= BASE_URL ?>cart/checkout" class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold">
                    Đặt hàng
                </a>
            </div>
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

