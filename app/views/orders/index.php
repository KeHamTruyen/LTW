<?php
$orders = $orders ?? [];
$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;
?>
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Đơn hàng của tôi</h1>
    
    <?php if (empty($orders)): ?>
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Chưa có đơn hàng</h3>
            <p class="text-gray-600 mb-6">Bạn chưa đặt đơn hàng nào.</p>
            <a href="<?= BASE_URL ?>products" class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Mua sắm ngay
            </a>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php foreach ($orders as $order): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-800">
                                    Đơn hàng #<?= htmlspecialchars($order['order_number']) ?>
                                </h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    Ngày đặt: <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?>
                                </p>
                            </div>
                            <span class="px-3 py-1 rounded-full text-sm font-semibold <?php
                                switch ($order['status']) {
                                    case 'pending':
                                        echo 'bg-yellow-100 text-yellow-800';
                                        break;
                                    case 'processing':
                                        echo 'bg-blue-100 text-blue-800';
                                        break;
                                    case 'shipped':
                                        echo 'bg-purple-100 text-purple-800';
                                        break;
                                    case 'delivered':
                                        echo 'bg-green-100 text-green-800';
                                        break;
                                    case 'cancelled':
                                        echo 'bg-red-100 text-red-800';
                                        break;
                                    default:
                                        echo 'bg-gray-100 text-gray-800';
                                }
                            ?>">
                                <?php
                                switch ($order['status']) {
                                    case 'pending':
                                        echo 'Chờ xử lý';
                                        break;
                                    case 'processing':
                                        echo 'Đang xử lý';
                                        break;
                                    case 'shipped':
                                        echo 'Đã giao hàng';
                                        break;
                                    case 'delivered':
                                        echo 'Đã nhận hàng';
                                        break;
                                    case 'cancelled':
                                        echo 'Đã hủy';
                                        break;
                                    default:
                                        echo htmlspecialchars($order['status']);
                                }
                                ?>
                            </span>
                        </div>
                        
                        <div class="space-y-2 mb-4">
                            <p class="text-sm">
                                <span class="font-semibold">Người nhận:</span>
                                <?= htmlspecialchars($order['customer_name']) ?>
                            </p>
                            <p class="text-sm">
                                <span class="font-semibold">Địa chỉ:</span>
                                <?= htmlspecialchars($order['shipping_address']) ?>
                            </p>
                            <p class="text-sm">
                                <span class="font-semibold">SĐT:</span>
                                <?= htmlspecialchars($order['customer_phone']) ?>
                            </p>
                        </div>
                        
                        <div class="flex justify-between items-center pt-4 border-t">
                            <span class="text-lg font-semibold">Tổng tiền:</span>
                            <span class="text-xl font-bold text-red-600">
                                <?= number_format($order['total_amount'], 0, ',', '.') ?> đ
                            </span>
                        </div>
                        
                        <div class="mt-4">
                            <a href="<?= BASE_URL ?>orders/show?id=<?= $order['id'] ?>" 
                               class="block w-full text-center px-4 py-2 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition">
                                Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <div class="mt-8 flex justify-center gap-2">
                <?php if ($currentPage > 1): ?>
                    <a href="<?= BASE_URL ?>orders?p=<?= $currentPage - 1 ?>" 
                       class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Trước
                    </a>
                <?php endif; ?>
                
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php if ($i == $currentPage): ?>
                        <span class="px-4 py-2 bg-blue-600 text-white rounded-lg"><?= $i ?></span>
                    <?php else: ?>
                        <a href="<?= BASE_URL ?>orders?p=<?= $i ?>" 
                           class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                            <?= $i ?>
                        </a>
                    <?php endif; ?>
                <?php endfor; ?>
                
                <?php if ($currentPage < $totalPages): ?>
                    <a href="<?= BASE_URL ?>orders?p=<?= $currentPage + 1 ?>" 
                       class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Sau
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

