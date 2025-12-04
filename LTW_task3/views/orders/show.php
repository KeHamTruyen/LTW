<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Chi tiết đơn hàng #<?php echo $order['ID']; ?></h2>
                <a href="<?php echo BASE_URL; ?>orders" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
                </a>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <!-- Order Info -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Thông tin đơn hàng</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <small class="text-muted">Mã đơn hàng:</small><br>
                                    <strong>#<?php echo $order['ID']; ?></strong>
                                </div>
                                <div class="col-sm-6">
                                    <small class="text-muted">Ngày đặt:</small><br>
                                    <span><?php echo date('d/m/Y H:i', strtotime($order['Date'])); ?></span>
                                </div>
                                <div class="col-sm-6 mt-3">
                                    <small class="text-muted">Trạng thái:</small><br>
                                    <span class="badge bg-<?php
                                    switch ($order['Status']) {
                                        case 'pending':
                                            echo 'warning';
                                            break;
                                        case 'processing':
                                            echo 'info';
                                            break;
                                        case 'shipped':
                                            echo 'primary';
                                            break;
                                        case 'delivered':
                                            echo 'success';
                                            break;
                                        case 'cancelled':
                                            echo 'danger';
                                            break;
                                        default:
                                            echo 'secondary';
                                    }
                                    ?>">
                                        <?php
                                        switch ($order['Status']) {
                                            case 'pending':
                                                echo 'Chờ xử lý';
                                                break;
                                            case 'processing':
                                                echo 'Đang xử lý';
                                                break;
                                            case 'shipped':
                                                echo 'Đã giao';
                                                break;
                                            case 'delivered':
                                                echo 'Đã nhận';
                                                break;
                                            case 'cancelled':
                                                echo 'Đã hủy';
                                                break;
                                            default:
                                                echo $order['Status'];
                                        }
                                        ?>
                                    </span>
                                </div>
                                <div class="col-sm-6 mt-3">
                                    <small class="text-muted">Khách hàng:</small><br>
                                    <span><?php echo htmlspecialchars($order['user_name']); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Chi tiết sản phẩm</h5>
                        </div>
                        <div class="card-body">
                            <?php foreach ($orderDetails as $item): ?>
                                    <div class="row mb-3 align-items-center">
                                        <div class="col-md-2">
                                            <?php if ($item['image']): ?>
                                                    <img src="<?php echo BASE_URL; ?>uploads/<?php echo htmlspecialchars($item['image']); ?>"
                                                         class="img-fluid rounded" alt="<?php echo htmlspecialchars($item['name']); ?>">
                                            <?php else: ?>
                                                    <div class="bg-light d-flex align-items-center justify-content-center rounded"
                                                         style="height: 60px; width: 60px;">
                                                        <i class="fas fa-image text-muted"></i>
                                                    </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="col-md-4">
                                            <h6><?php echo htmlspecialchars($item['name']); ?></h6>
                                        </div>
                                        <div class="col-md-2">
                                            <small class="text-muted">Số lượng:</small><br>
                                            <span><?php echo $item['quantity']; ?></span>
                                        </div>
                                        <div class="col-md-2">
                                            <small class="text-muted">Đơn giá:</small><br>
                                            <span><?php echo number_format($item['price_at_order'], 0, ',', '.'); ?> VND</span>
                                        </div>
                                        <div class="col-md-2">
                                            <small class="text-muted">Thành tiền:</small><br>
                                            <strong><?php echo number_format($item['quantity'] * $item['price_at_order'], 0, ',', '.'); ?> VND</strong>
                                        </div>
                                    </div>
                                    <?php if ($item !== end($orderDetails)): ?>
                                            <hr>
                                    <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Order Summary -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Tổng kết</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tổng tiền:</span>
                                <strong><?php echo number_format($order['total_amount'], 0, ',', '.'); ?> VND</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Info -->
                    <?php if ($payment): ?>
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="mb-0">Thông tin thanh toán</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-2">
                                        <small class="text-muted">Phương thức:</small><br>
                                        <span><?php echo htmlspecialchars($payment['method']); ?></span>
                                    </div>
                                    <div class="mb-2">
                                        <small class="text-muted">Số tiền:</small><br>
                                        <span><?php echo number_format($payment['amount'], 0, ',', '.'); ?> VND</span>
                                    </div>
                                    <div>
                                        <small class="text-muted">Ngày thanh toán:</small><br>
                                        <span><?php echo date('d/m/Y H:i', strtotime($payment['date'])); ?></span>
                                    </div>
                                </div>
                            </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
