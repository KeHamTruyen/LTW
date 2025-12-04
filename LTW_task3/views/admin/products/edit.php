<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Chỉnh sửa sản phẩm</h2>
    <a href="<?php echo BASE_URL; ?>admin/products" class="btn btn-outline-primary">
        <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?php echo BASE_URL; ?>admin/products/update" enctype="multipart/form-data">
                    <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['_csrf'] ?? ''); ?>">
                    <input type="hidden" name="csrf" value="<?= \Core\Auth::csrfToken() ?>">
                    <input type="hidden" name="id" value="<?php echo $product['ID']; ?>">

                    <div class="mb-3">
                        <label for="name" class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required
                            value="<?php echo htmlspecialchars($product['name']); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Giá sản phẩm <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" required
                            value="<?php echo htmlspecialchars($product['price']); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="stock_quantity" class="form-label">Số lượng tồn kho <span
                                class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" min="0"
                            required value="<?php echo htmlspecialchars($product['stock_quantity']); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả sản phẩm</label>
                        <textarea class="form-control" id="description" name="description"
                            rows="4"><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Hình ảnh hiện tại</label>
                        <div class="mb-2">
                            <?php if ($product['image']): ?>
                                <img src="<?php echo BASE_URL; ?>uploads/<?php echo htmlspecialchars($product['image']); ?>"
                                    alt="Current image" class="img-thumbnail" style="max-width: 200px;">
                            <?php else: ?>
                                <div class="bg-light p-3 text-center rounded">
                                    <i class="fas fa-image fa-2x text-muted"></i>
                                    <p class="text-muted mb-0">Chưa có hình ảnh</p>
                                </div>
                            <?php endif; ?>
                        </div>
                        <label for="image" class="form-label">Thay đổi hình ảnh (tùy chọn)</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        <div class="form-text">Để trống nếu không muốn thay đổi. Chấp nhận: JPG, PNG, GIF. Tối đa 5MB
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Cập nhật sản phẩm
                        </button>
                        <a href="<?php echo BASE_URL; ?>admin/products" class="btn btn-outline-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Thông tin sản phẩm</h6>
            </div>
            <div class="card-body">
                <div class="mb-2">
                    <small class="text-muted">ID sản phẩm:</small><br>
                    <span class="fw-bold"><?php echo $product['ID']; ?></span>
                </div>
                <div class="mb-2">
                    <small class="text-muted">Ngày tạo:</small><br>
                    <span><?php echo date('d/m/Y H:i', strtotime($product['create_at'])); ?></span>
                </div>
                <div class="mb-2">
                    <small class="text-muted">Cập nhật lần cuối:</small><br>
                    <span><?php echo date('d/m/Y H:i', strtotime($product['update_at'] ?? $product['create_at'])); ?></span>
                </div>
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Lưu ý</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled small">
                    <li class="mb-2"><i class="fas fa-info-circle text-info me-2"></i>Thay đổi giá sẽ ảnh hưởng ngay lập
                        tức</li>
                    <li class="mb-2"><i class="fas fa-info-circle text-info me-2"></i>Cập nhật số lượng tồn kho cẩn thận
                    </li>
                    <li class="mb-0"><i class="fas fa-info-circle text-info me-2"></i>Hình ảnh mới sẽ thay thế hình cũ
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>