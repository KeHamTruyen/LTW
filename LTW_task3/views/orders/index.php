<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Đơn hàng của tôi</h2>

            <?php if (empty($orders)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-receipt fa-4x text-muted mb-3"></i>
                        <h4 class="text-muted">Chưa có đơn hàng nào</h4>
                        <p class="text-muted">Bạn chưa đặt đơn hàng nào</p>
                        <a href="<?php echo BASE_URL; ?>products" class="btn btn-primary">Mua sắm ngay</a>
                    </div>
            <?php else: ?>
                    <div class="row">
                        <?php foreach ($orders as $order): ?>
                                <div class="col-md-6 mb-4">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">Đơn hàng #<?php echo $order['ID']; ?></h6>
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
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <small class="text-muted">Ngày đặt:</small><br>
                                                    <span><?php echo date('d/m/Y H:i', strtotime($order['Date'])); ?></span>
                                                </div>
                                                <div class="col-sm-6">
                                                    <small class="text-muted">Tổng tiền:</small><br>
                                                    <strong><?php echo number_format($order['total_amount'], 0, ',', '.'); ?> VND</strong>
                                                </div>
                                            </div>
                                            <div class="mt-3">
                                                <a href="<?php echo BASE_URL; ?>orders/show?id=<?php echo $order['ID']; ?>"
                                                   class="btn btn-outline-primary btn-sm">Xem chi tiết</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                            <nav aria-label="Order pagination" class="mt-4">
                                <ul class="pagination justify-content-center">
                                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                            <li class="page-item <?php echo $i === $currentPage ? 'active' : ''; ?>">
                                                <a class="page-link" href="?p=<?php echo $i; ?>">
                                                    <?php echo $i; ?>
                                                </a>
                                            </li>
                                    <?php endfor; ?>
                                </ul>
                            </nav>
                    <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
