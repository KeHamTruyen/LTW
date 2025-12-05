<?php
// File: views/products/index.php
?>
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Danh sách sản phẩm</h2>

            <form method="GET" action="<?php echo BASE_URL; ?>products" class="mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Tìm kiếm sản phẩm..."
                           value="<?php echo htmlspecialchars($search ?? ''); ?>">
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="fas fa-search"></i> Tìm kiếm
                    </button>
                </div>
            </form>

            <?php if (empty($products)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">Không tìm thấy sản phẩm nào</h4>
                    <p class="text-muted">
                        <?php if (!empty($search)): ?>
                            Thử tìm kiếm với từ khóa khác
                        <?php else: ?>
                            Chưa có sản phẩm nào được thêm
                        <?php endif; ?>
                    </p>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($products as $product): ?>
                        <?php
                            // [QUAN TRỌNG] Xử lý đồng bộ dữ liệu để tránh lỗi Undefined Key
                            // Lấy ID (chấp nhận cả 'ID' hoa và 'id' thường)
                            $pId = $product['ID'] ?? $product['id'] ?? 0;
                            $pName = $product['name'] ?? 'Sản phẩm';
                            $pPrice = $product['price'] ?? 0;
                            $pDesc = $product['description'] ?? '';
                            $pStock = $product['stock_quantity'] ?? 0;
                            $pImage = $product['image'] ?? '';

                            // Xử lý đường dẫn ảnh (Seed data vs Upload mới)
                            $imgSrc = 'https://placehold.co/300x300?text=No+Image';
                            if (!empty($pImage)) {
                                if (strpos($pImage, 'images/') === 0) {
                                    // Ảnh mẫu (seed data) nằm trực tiếp trong public/images
                                    $imgSrc = BASE_URL . $pImage;
                                } else {
                                    // Ảnh upload nằm trong public/uploads
                                    $imgSrc = BASE_URL . 'uploads/' . $pImage;
                                }
                            }
                        ?>
                        <div class="col-md-4 mb-4">
                            <div class="card product-card h-100 shadow-sm">
                                <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 200px; overflow: hidden;">
                                    <img src="<?php echo $imgSrc; ?>" 
                                         alt="<?php echo htmlspecialchars($pName); ?>"
                                         style="height: 100%; width: auto; max-width: 100%; object-fit: contain;">
                                </div>

                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title text-truncate"><?php echo htmlspecialchars($pName); ?></h5>
                                    <p class="card-text text-muted small">
                                        <?php echo htmlspecialchars(substr($pDesc, 0, 100)); ?>...
                                    </p>
                                    
                                    <div class="mt-auto">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="h5 text-primary mb-0">
                                                <?php echo number_format($pPrice, 0, ',', '.'); ?> VND
                                            </span>
                                            <small class="text-muted">
                                                Còn <?php echo $pStock; ?> sản phẩm
                                            </small>
                                        </div>

                                        <div class="d-flex gap-2">
                                            <a href="<?php echo BASE_URL; ?>products/show?id=<?php echo $pId; ?>"
                                               class="btn btn-outline-primary flex-fill">Xem chi tiết</a>

                                            <form method="POST" action="<?php echo BASE_URL; ?>cart/add" class="flex-fill">
                                                <input type="hidden" name="csrf" value="<?php echo \Core\Auth::csrfToken(); ?>">
                                                <input type="hidden" name="product_id" value="<?php echo $pId; ?>">
                                                <input type="hidden" name="quantity" value="1">
                                                
                                                <button type="submit" class="btn btn-success w-100"
                                                        <?php echo $pStock <= 0 ? 'disabled' : ''; ?>>
                                                    <i class="fas fa-cart-plus"></i> Thêm
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if (isset($totalPages) && $totalPages > 1): ?>
                    <nav aria-label="Product pagination" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <li class="page-item <?php echo $currentPage <= 1 ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?p=<?php echo $currentPage - 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">Trước</a>
                            </li>

                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?php echo $i == $currentPage ? 'active' : ''; ?>">
                                    <a class="page-link" href="?p=<?php echo $i; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <li class="page-item <?php echo $currentPage >= $totalPages ? 'disabled' : ''; ?>">
                                <a class="page-link" href="?p=<?php echo $currentPage + 1; ?><?php echo !empty($search) ? '&search=' . urlencode($search) : ''; ?>">Sau</a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>