<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-box fa-3x text-primary mb-3"></i>
                <h4><?php echo $stats['total_products'] ?? 0; ?></h4>
                <p class="text-muted mb-0">Tổng sản phẩm</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-receipt fa-3x text-success mb-3"></i>
                <h4><?php echo $stats['total_orders'] ?? 0; ?></h4>
                <p class="text-muted mb-0">Tổng đơn hàng</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-shopping-cart fa-3x text-warning mb-3"></i>
                <h4><?php echo $stats['pending_orders'] ?? 0; ?></h4>
                <p class="text-muted mb-0">Đơn hàng chờ xử lý</p>
            </div>
        </div>
    </div>

    <div class="col-md-3 mb-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="fas fa-dollar-sign fa-3x text-info mb-3"></i>
                <h4><?php echo number_format($stats['total_revenue'] ?? 0, 0, ',', '.'); ?> VND</h4>
                <p class="text-muted mb-0">Tổng doanh thu</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Đơn hàng gần đây</h5>
            </div>
            <div class="card-body">
                <?php if (empty($recentOrders)): ?>
                        <p class="text-muted mb-0">Chưa có đơn hàng nào</p>
                <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($recentOrders as $order): ?>
                                    <div class="list-group-item px-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">Đơn hàng #<?php echo $order['ID']; ?></h6>
                                                <small class="text-muted"><?php echo htmlspecialchars($order['user_name']); ?></small>
                                            </div>
                                            <div class="text-end">
                                                <div class="fw-bold"><?php echo number_format($order['total_amount'], 0, ',', '.'); ?> VND</div>
                                                <small class="text-muted"><?php echo date('d/m/Y', strtotime($order['Date'])); ?></small>
                                            </div>
                                        </div>
                                    </div>
                            <?php endforeach; ?>
                        </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Sản phẩm tồn kho thấp</h5>
            </div>
            <div class="card-body">
                <?php if (empty($lowStockProducts)): ?>
                        <p class="text-muted mb-0">Tất cả sản phẩm đều đủ hàng</p>
                <?php else: ?>
                        <div class="list-group list-group-flush">
                            <?php foreach ($lowStockProducts as $product): ?>
                                    <div class="list-group-item px-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1"><?php echo htmlspecialchars($product['name']); ?></h6>
                                                <small class="text-muted">ID: <?php echo $product['ID']; ?></small>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-warning">Còn <?php echo $product['stock_quantity']; ?> sản phẩm</span>
                                            </div>
                                        </div>
                                    </div>
                            <?php endforeach; ?>
                        </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
