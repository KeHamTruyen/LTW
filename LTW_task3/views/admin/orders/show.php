<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Chi tiết đơn hàng #<?php echo $order['ID']; ?></h1>
        <a href="<?php echo BASE_URL; ?>admin/orders" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Quay lại danh sách
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Sản phẩm trong đơn</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Đơn giá</th>
                                    <th>Số lượng</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orderDetails as $item):
                                    $itemPrice = $item['price_at_order'] ?? $item['price'];
                                    $itemSubtotal = $itemPrice * $item['quantity'];
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php if (!empty($item['image'])):
                                                    $imgUrl = strpos($item['image'], 'images/') === 0
                                                        ? BASE_URL . $item['image']
                                                        : BASE_URL . 'uploads/' . $item['image'];
                                                    ?>
                                                    <img src="<?php echo $imgUrl; ?>" alt="Product"
                                                        style="width: 50px; height: 50px; object-fit: cover;"
                                                        class="me-3 rounded">
                                                <?php endif; ?>
                                                <div>
                                                    <span
                                                        class="fw-bold d-block"><?php echo htmlspecialchars($item['product_name'] ?? $item['name']); ?></span>
                                                    <small class="text-muted">ID:
                                                        <?php echo $item['PRODUCT_id'] ?? $item['product_id']; ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo number_format($itemPrice, 0, ',', '.'); ?> đ</td>
                                        <td>x <?php echo $item['quantity']; ?></td>
                                        <td class="fw-bold"><?php echo number_format($itemSubtotal, 0, ',', '.'); ?> đ</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end fw-bold">Tổng tiền:</td>
                                    <td class="text-danger fw-bold h5">
                                        <?php echo number_format($order['total_amount'], 0, ',', '.'); ?> đ</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary text-white">
                    <h6 class="m-0 fw-bold">Cập nhật trạng thái</h6>
                </div>
                <div class="card-body">
                    <form action="<?php echo BASE_URL; ?>admin/orders/update-status" method="POST">
                        <input type="hidden" name="csrf" value="<?php echo \Core\Auth::csrfToken(); ?>">
                        <input type="hidden" name="id" value="<?php echo $order['ID']; ?>">

                        <div class="mb-3">
                            <label class="form-label">Trạng thái hiện tại:</label>
                            <select name="status" class="form-select">
                                <?php
                                $statuses = [
                                    'pending' => 'Chờ xử lý',
                                    'processing' => 'Đang xử lý',
                                    'shipped' => 'Đang giao hàng',
                                    'delivered' => 'Đã giao hàng',
                                    'cancelled' => 'Đã hủy'
                                ];
                                foreach ($statuses as $key => $label):
                                    ?>
                                    <option value="<?php echo $key; ?>" <?php echo $order['Status'] === $key ? 'selected' : ''; ?>>
                                        <?php echo $label; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Cập nhật</button>
                    </form>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin khách hàng</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Họ tên:</strong>
                        <?php echo htmlspecialchars($order['user_name'] ?? 'Khách lẻ'); ?></p>
                    <p class="mb-2"><strong>Email:</strong>
                        <?php echo htmlspecialchars($order['user_email'] ?? 'N/A'); ?></p>
                    <p class="mb-2"><strong>Ngày đặt:</strong>
                        <?php echo date('d/m/Y H:i', strtotime($order['Date'])); ?></p>
                    <hr>
                    <p class="mb-0"><strong>Phương thức thanh toán:</strong></p>
                    <p class="text-muted"><?php echo htmlspecialchars($payment['method'] ?? 'Chưa có thông tin'); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>