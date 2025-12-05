<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo BASE_URL; ?>products">Sản phẩm</a></li>
                    <li class="breadcrumb-item active"><?php echo htmlspecialchars($product['name']); ?></li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <!-- Product Image -->
            <div class="card">
                <div class="card-body text-center">
                    <?php if ($product['image']): ?>
                            <img src="<?php echo BASE_URL; ?>uploads/<?php echo htmlspecialchars($product['image']); ?>"
                                 alt="<?php echo htmlspecialchars($product['name']); ?>"
                                 class="img-fluid rounded" style="max-height: 400px;">
                    <?php else: ?>
                            <div class="bg-light d-flex align-items-center justify-content-center rounded"
                                 style="height: 400px;">
                                <i class="fas fa-image fa-4x text-muted"></i>
                            </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <!-- Product Info -->
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0"><?php echo htmlspecialchars($product['name']); ?></h4>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h3 class="text-primary"><?php echo number_format($product['price'], 0, ',', '.'); ?> VND</h3>
                        <small class="text-muted">
                            <?php if ($product['stock_quantity'] > 0): ?>
                                    <i class="fas fa-check-circle text-success me-1"></i>Còn <?php echo $product['stock_quantity']; ?> sản phẩm
                            <?php else: ?>
                                    <i class="fas fa-times-circle text-danger me-1"></i>Hết hàng
                            <?php endif; ?>
                        </small>
                    </div>

                    <?php if ($product['description']): ?>
                            <div class="mb-4">
                                <h6>Mô tả sản phẩm:</h6>
                                <p><?php echo nl2br(htmlspecialchars($product['description'])); ?></p>
                            </div>
                    <?php endif; ?>

                    <div class="mb-4">
                        <small class="text-muted">Ngày thêm: <?php echo date('d/m/Y', strtotime($product['create_at'])); ?></small>
                    </div>

                    <!-- Add to Cart Form -->
                    <?php if ($product['stock_quantity'] > 0): ?>
                            <form method="POST" action="<?php echo BASE_URL; ?>cart/add" class="d-inline">
                                <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['_csrf'] ?? ''); ?>">
                                <input type="hidden" name="product_id" value="<?php echo $product['ID']; ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-success btn-lg me-2">
                                    <i class="fas fa-cart-plus me-2"></i>Thêm vào giỏ hàng
                                </button>
                            </form>
                    <?php else: ?>
                            <button type="button" class="btn btn-secondary btn-lg me-2" disabled>
                                <i class="fas fa-cart-plus me-2"></i>Hết hàng
                            </button>
                    <?php endif; ?>

                    <a href="<?php echo BASE_URL; ?>products" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-arrow-left me-2"></i>Tiếp tục mua sắm
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
