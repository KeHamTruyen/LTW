<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Thêm sản phẩm mới</h2>
    <a href="<?php echo BASE_URL; ?>admin/products" class="btn btn-outline-primary">
        <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="<?php echo BASE_URL; ?>admin/products/store" enctype="multipart/form-data">
                    <input type="hidden" name="csrf" value="<?= \Core\Auth::csrfToken() ?>">
                    <input type="hidden" name="csrf" value="<?php echo htmlspecialchars($_SESSION['_csrf'] ?? ''); ?>">

                    <div class="mb-3">
                        <label for="name" class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required
                               value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Giá sản phẩm <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="price" name="price" step="0.01" min="0" required
                               value="<?php echo htmlspecialchars($_POST['price'] ?? ''); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="stock_quantity" class="form-label">Số lượng tồn kho <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="stock_quantity" name="stock_quantity" min="0" required
                               value="<?php echo htmlspecialchars($_POST['stock_quantity'] ?? ''); ?>">
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả sản phẩm</label>
                        <textarea class="form-control" id="description" name="description" rows="4"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Hình ảnh sản phẩm</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        <div class="form-text">Chấp nhận các định dạng: JPG, PNG, GIF. Kích thước tối đa: 5MB</div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Lưu sản phẩm
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
                <h6 class="mb-0">Hướng dẫn</h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-info-circle text-info me-2"></i>Tên sản phẩm nên mô tả rõ ràng</li>
                    <li class="mb-2"><i class="fas fa-info-circle text-info me-2"></i>Giá sản phẩm tính theo VND</li>
                    <li class="mb-2"><i class="fas fa-info-circle text-info me-2"></i>Mô tả giúp khách hàng hiểu rõ sản phẩm</li>
                    <li class="mb-0"><i class="fas fa-info-circle text-info me-2"></i>Hình ảnh nên có chất lượng cao</li>
                </ul>
            </div>
        </div>
    </div>
</div>
