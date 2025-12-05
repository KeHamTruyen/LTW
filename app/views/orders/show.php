<?php
$order = $order ?? null;
$orderItems = $orderItems ?? [];
if (!$order) {
    echo '<div class="container mx-auto px-4 py-8"><p>Đơn hàng không tồn tại.</p></div>';
    return;
}
?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6 border-b">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">
                            Đơn hàng #<?= htmlspecialchars($order['order_number']) ?>
                        </h1>
                        <p class="text-gray-600 mt-1">
                            Ngày đặt: <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?>
                        </p>
                    </div>
                    <span class="px-4 py-2 rounded-full text-sm font-semibold <?php
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
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h3 class="font-semibold mb-2">Thông tin người nhận</h3>
                        <div class="text-gray-700 space-y-1">
                            <p><strong>Họ tên:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
                            <p><strong>Email:</strong> <?= htmlspecialchars($order['customer_email']) ?></p>
                            <p><strong>SĐT:</strong> <?= htmlspecialchars($order['customer_phone']) ?></p>
                            <p><strong>Địa chỉ:</strong> <?= nl2br(htmlspecialchars($order['shipping_address'])) ?></p>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="font-semibold mb-2">Thông tin thanh toán</h3>
                        <div class="text-gray-700 space-y-1">
                            <p><strong>Phương thức:</strong> 
                                <?= $order['payment_method'] ? htmlspecialchars($order['payment_method']) : 'Chưa chọn' ?>
                            </p>
                            <p><strong>Trạng thái:</strong> 
                                <span class="<?= $order['payment_status'] === 'paid' ? 'text-green-600' : 'text-yellow-600' ?>">
                                    <?php
                                    switch ($order['payment_status']) {
                                        case 'paid':
                                            echo 'Đã thanh toán';
                                            break;
                                        case 'failed':
                                            echo 'Thanh toán thất bại';
                                            break;
                                        default:
                                            echo 'Chưa thanh toán';
                                    }
                                    ?>
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
                
                <?php if (!empty($order['notes'])): ?>
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <h3 class="font-semibold mb-2">Ghi chú</h3>
                        <p class="text-gray-700"><?= nl2br(htmlspecialchars($order['notes'])) ?></p>
                    </div>
                <?php endif; ?>
                
                <h3 class="font-semibold mb-4">Chi tiết sản phẩm</h3>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold">Sản phẩm</th>
                                <th class="px-4 py-3 text-center text-sm font-semibold">Số lượng</th>
                                <th class="px-4 py-3 text-right text-sm font-semibold">Đơn giá</th>
                                <th class="px-4 py-3 text-right text-sm font-semibold">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($orderItems as $item): ?>
                                <tr>
                                    <td class="px-4 py-3">
                                        <?= htmlspecialchars($item['product_name']) ?>
                                        <?php if (!empty($item['product_slug'])): ?>
                                            <br>
                                            <a href="<?= BASE_URL ?>products/show?id=<?= $item['product_id'] ?>" 
                                               class="text-sm text-blue-600 hover:underline">
                                                Xem sản phẩm
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-4 py-3 text-center"><?= $item['quantity'] ?></td>
                                    <td class="px-4 py-3 text-right">
                                        <?= number_format($item['product_price'], 0, ',', '.') ?> đ
                                    </td>
                                    <td class="px-4 py-3 text-right font-semibold">
                                        <?= number_format($item['subtotal'], 0, ',', '.') ?> đ
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right font-semibold">Tổng cộng:</td>
                                <td class="px-4 py-3 text-right">
                                    <span class="text-xl font-bold text-red-600">
                                        <?= number_format($order['total_amount'], 0, ',', '.') ?> đ
                                    </span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            
            <div class="p-6 bg-gray-50 flex justify-end">
                <a href="<?= BASE_URL ?>orders" class="px-6 py-2 border border-gray-300 rounded-lg hover:bg-white transition">
                    Quay lại danh sách
                </a>
            </div>
        </div>
    </div>
</div>

