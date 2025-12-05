<?php
$order = $order ?? null;
$orderItems = $orderItems ?? [];
if (!$order) {
    echo '<div class="container-xl"><p>Đơn hàng không tồn tại.</p></div>';
    return;
}
?>
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Chi tiết đơn hàng #<?= htmlspecialchars($order['order_number']) ?></h2>
            </div>
            <div class="col-auto">
                <a href="<?= BASE_URL ?>admin/orders" class="btn">Quay lại</a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Flash messages -->
        <?php if (isset($_SESSION['flash_success'])): ?>
            <div class="alert alert-success alert-dismissible">
                <?= htmlspecialchars($_SESSION['flash_success']) ?>
                <a class="btn-close" data-bs-dismiss="alert"></a>
            </div>
            <?php unset($_SESSION['flash_success']); ?>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-8">
                <!-- Order Items -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Sản phẩm trong đơn hàng</h3>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Đơn giá</th>
                                    <th>Số lượng</th>
                                    <th class="text-end">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orderItems as $item): ?>
                                    <tr>
                                        <td>
                                            <div class="font-weight-medium"><?= htmlspecialchars($item['product_name']) ?></div>
                                            <?php if (!empty($item['product_slug'])): ?>
                                                <a href="<?= BASE_URL ?>products/show?id=<?= $item['product_id'] ?>" 
                                                   class="text-blue text-xs" target="_blank">
                                                    Xem sản phẩm →
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                        <td><?= number_format($item['product_price'], 0, ',', '.') ?> đ</td>
                                        <td><?= $item['quantity'] ?></td>
                                        <td class="text-end font-weight-semibold">
                                            <?= number_format($item['subtotal'], 0, ',', '.') ?> đ
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end font-weight-semibold">Tổng cộng:</td>
                                    <td class="text-end">
                                        <span class="text-red text-xl font-bold">
                                            <?= number_format($order['total_amount'], 0, ',', '.') ?> đ
                                        </span>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <!-- Order Info -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Thông tin đơn hàng</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Trạng thái đơn hàng</label>
                            <form method="post" action="<?= BASE_URL ?>admin/orders/update-status">
                                <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
                                <input type="hidden" name="id" value="<?= $order['id'] ?>">
                                <select name="status" class="form-select mb-2" onchange="this.form.submit()">
                                    <option value="pending" <?= $order['status'] === 'pending' ? 'selected' : '' ?>>Chờ xử lý</option>
                                    <option value="processing" <?= $order['status'] === 'processing' ? 'selected' : '' ?>>Đang xử lý</option>
                                    <option value="shipped" <?= $order['status'] === 'shipped' ? 'selected' : '' ?>>Đã giao hàng</option>
                                    <option value="delivered" <?= $order['status'] === 'delivered' ? 'selected' : '' ?>>Đã nhận hàng</option>
                                    <option value="cancelled" <?= $order['status'] === 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                                </select>
                            </form>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Trạng thái thanh toán</label>
                            <div>
                                <?php
                                $paymentBadges = [
                                    'paid' => 'bg-green',
                                    'failed' => 'bg-red',
                                    'pending' => 'bg-yellow',
                                ];
                                $paymentLabels = [
                                    'paid' => 'Đã thanh toán',
                                    'failed' => 'Thất bại',
                                    'pending' => 'Chờ thanh toán',
                                ];
                                ?>
                                <span class="badge <?= $paymentBadges[$order['payment_status']] ?? 'bg-secondary' ?>">
                                    <?= $paymentLabels[$order['payment_status']] ?? $order['payment_status'] ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Ngày đặt</label>
                            <div><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></div>
                        </div>
                        
                        <?php if (!empty($order['payment_method'])): ?>
                            <div class="mb-3">
                                <label class="form-label">Phương thức thanh toán</label>
                                <div><?= htmlspecialchars($order['payment_method']) ?></div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Customer Info -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Thông tin khách hàng</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <strong>Họ tên:</strong><br>
                            <?= htmlspecialchars($order['customer_name']) ?>
                        </div>
                        <div class="mb-2">
                            <strong>Email:</strong><br>
                            <a href="mailto:<?= htmlspecialchars($order['customer_email']) ?>">
                                <?= htmlspecialchars($order['customer_email']) ?>
                            </a>
                        </div>
                        <div class="mb-2">
                            <strong>SĐT:</strong><br>
                            <a href="tel:<?= htmlspecialchars($order['customer_phone']) ?>">
                                <?= htmlspecialchars($order['customer_phone']) ?>
                            </a>
                        </div>
                        <div>
                            <strong>Địa chỉ giao hàng:</strong><br>
                            <div class="text-muted"><?= nl2br(htmlspecialchars($order['shipping_address'])) ?></div>
                        </div>
                    </div>
                </div>
                
                <?php if (!empty($order['notes'])): ?>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Ghi chú</h3>
                        </div>
                        <div class="card-body">
                            <p class="text-muted"><?= nl2br(htmlspecialchars($order['notes'])) ?></p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

