<?php
$cartItems = $cartItems ?? [];
$total = $total ?? 0;
$user = $user ?? null;
?>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Thanh toán</h1>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Order Summary -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold mb-4">Thông tin đơn hàng</h2>
                
                <div class="space-y-4 mb-6">
                    <?php foreach ($cartItems as $item): ?>
                        <div class="flex items-center gap-4 pb-4 border-b">
                            <?php if (!empty($item['image_url'])): ?>
                                <img src="<?= htmlspecialchars($item['image_url']) ?>" 
                                     alt="<?= htmlspecialchars($item['name']) ?>" 
                                     class="w-16 h-16 object-cover rounded">
                            <?php endif; ?>
                            <div class="flex-1">
                                <h4 class="font-semibold"><?= htmlspecialchars($item['name']) ?></h4>
                                <p class="text-sm text-gray-600">Số lượng: <?= $item['quantity'] ?></p>
                            </div>
                            <div class="text-right">
                                <p class="font-semibold">
                                    <?= number_format(($item['sale_price'] ?? $item['price']) * $item['quantity'], 0, ',', '.') ?> đ
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="flex justify-between items-center pt-4 border-t">
                    <span class="text-lg font-semibold">Tổng cộng:</span>
                    <span class="text-2xl font-bold text-red-600">
                        <?= number_format($total, 0, ',', '.') ?> đ
                    </span>
                </div>
            </div>
            
            <!-- Checkout Form -->
            <form method="post" action="<?= BASE_URL ?>orders/checkout" class="bg-white rounded-lg shadow-md p-6">
                <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
                
                <h2 class="text-xl font-semibold mb-4">Thông tin giao hàng</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Họ và tên <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="customer_name" 
                               value="<?= htmlspecialchars($user['name'] ?? '') ?>"
                               required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               name="customer_email" 
                               value="<?= htmlspecialchars($user['email'] ?? '') ?>"
                               required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Số điện thoại <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" 
                               name="customer_phone" 
                               value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                               required 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Địa chỉ giao hàng <span class="text-red-500">*</span>
                        </label>
                        <textarea name="shipping_address" 
                                  rows="3" 
                                  required 
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Phương thức thanh toán
                        </label>
                        <select name="payment_method" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="cod">Thanh toán khi nhận hàng (COD)</option>
                            <option value="bank">Chuyển khoản ngân hàng</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Ghi chú
                        </label>
                        <textarea name="notes" 
                                  rows="2" 
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                                  placeholder="Ghi chú thêm cho đơn hàng..."></textarea>
                    </div>
                </div>
                
                <div class="mt-6 flex gap-4">
                    <a href="<?= BASE_URL ?>cart" class="flex-1 px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 text-center">
                        Quay lại giỏ hàng
                    </a>
                    <button type="submit" class="flex-1 px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold">
                        Xác nhận đặt hàng
                    </button>
                </div>
            </form>
        </div>
        
        <!-- Order Summary Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-md p-6 sticky top-4">
                <h3 class="text-lg font-semibold mb-4">Tóm tắt đơn hàng</h3>
                
                <div class="space-y-2 mb-4">
                    <?php foreach ($cartItems as $item): ?>
                        <div class="flex justify-between text-sm">
                            <span><?= htmlspecialchars($item['name']) ?> x<?= $item['quantity'] ?></span>
                            <span><?= number_format(($item['sale_price'] ?? $item['price']) * $item['quantity'], 0, ',', '.') ?> đ</span>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="border-t pt-4">
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-semibold">Tổng cộng:</span>
                        <span class="text-xl font-bold text-red-600">
                            <?= number_format($total, 0, ',', '.') ?> đ
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Form validation
document.querySelector('form').addEventListener('submit', function(e) {
    const requiredFields = this.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            isValid = false;
            field.classList.add('border-red-500');
        } else {
            field.classList.remove('border-red-500');
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        alert('Vui lòng điền đầy đủ thông tin bắt buộc.');
    }
});
</script>

