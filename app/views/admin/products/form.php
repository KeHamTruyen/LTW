<?php
$product = $product ?? null;
$categories = $categories ?? [];
$isEdit = $product !== null;
?>
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title"><?= $isEdit ? 'Chỉnh sửa sản phẩm' : 'Thêm sản phẩm mới' ?></h2>
            </div>
            <div class="col-auto">
                <a href="<?= BASE_URL ?>admin/products" class="btn">Quay lại</a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <!-- Flash messages -->
        <?php if (isset($_SESSION['flash_error'])): ?>
            <div class="alert alert-danger alert-dismissible">
                <?= htmlspecialchars($_SESSION['flash_error']) ?>
                <a class="btn-close" data-bs-dismiss="alert"></a>
            </div>
            <?php unset($_SESSION['flash_error']); ?>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-8">
                <form method="post" action="<?= BASE_URL ?>admin/products/<?= $isEdit ? 'update' : 'store' ?>" enctype="multipart/form-data" id="productForm">
                    <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
                    <?php if ($isEdit): ?>
                        <input type="hidden" name="id" value="<?= $product['id'] ?>">
                    <?php endif; ?>

                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">Thông tin cơ bản</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label required">Tên sản phẩm</label>
                                <input type="text" name="name" class="form-control" 
                                       value="<?= htmlspecialchars($product['name'] ?? '') ?>" 
                                       required minlength="2">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Slug (URL)</label>
                                <input type="text" name="slug" class="form-control" 
                                       value="<?= htmlspecialchars($product['slug'] ?? '') ?>" 
                                       placeholder="Tự động tạo từ tên">
                                <small class="form-hint">Để trống để tự động tạo từ tên sản phẩm</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Mô tả</label>
                                <textarea name="description" class="form-control" rows="5"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label required">Giá gốc (đ)</label>
                                        <input type="number" name="price" class="form-control" 
                                               value="<?= $product['price'] ?? '' ?>" 
                                               required min="0" step="0.01">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Giá khuyến mãi (đ)</label>
                                        <input type="number" name="sale_price" class="form-control" 
                                               value="<?= $product['sale_price'] ?? '' ?>" 
                                               min="0" step="0.01">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Danh mục</label>
                                        <select name="category_id" class="form-select">
                                            <option value="">Chọn danh mục</option>
                                            <?php foreach ($categories as $cat): ?>
                                                <option value="<?= $cat['id'] ?>" 
                                                        <?= ($product['category_id'] ?? 0) == $cat['id'] ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($cat['name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">SKU</label>
                                        <input type="text" name="sku" class="form-control" 
                                               value="<?= htmlspecialchars($product['sku'] ?? '') ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Số lượng tồn kho</label>
                                        <input type="number" name="stock_quantity" class="form-control" 
                                               value="<?= $product['stock_quantity'] ?? 0 ?>" 
                                               min="0">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Trạng thái</label>
                                        <select name="status" class="form-select">
                                            <option value="draft" <?= ($product['status'] ?? 'draft') === 'draft' ? 'selected' : '' ?>>Nháp</option>
                                            <option value="published" <?= ($product['status'] ?? '') === 'published' ? 'selected' : '' ?>>Đã xuất bản</option>
                                            <option value="out_of_stock" <?= ($product['status'] ?? '') === 'out_of_stock' ? 'selected' : '' ?>>Hết hàng</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-check">
                                    <input type="checkbox" name="featured" class="form-check-input" 
                                           <?= ($product['featured'] ?? false) ? 'checked' : '' ?>>
                                    <span class="form-check-label">Sản phẩm nổi bật</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">Hình ảnh</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Ảnh đại diện</label>
                                <?php if ($isEdit && !empty($product['image_url'])): ?>
                                    <div class="mb-2">
                                        <img src="<?= htmlspecialchars($product['image_url']) ?>" 
                                             alt="Preview" 
                                             id="imagePreview"
                                             class="img-thumbnail" 
                                             style="max-width: 200px;">
                                    </div>
                                <?php endif; ?>
                                <input type="file" name="image" id="imageInput" accept="image/*" class="form-control">
                                <small class="form-hint">JPG, PNG, GIF tối đa 5MB</small>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">SEO</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Meta Title</label>
                                <input type="text" name="meta_title" class="form-control" 
                                       value="<?= htmlspecialchars($product['meta_title'] ?? '') ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Description</label>
                                <textarea name="meta_description" class="form-control" rows="3"><?= htmlspecialchars($product['meta_description'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Cập nhật' : 'Thêm mới' ?></button>
                        <a href="<?= BASE_URL ?>admin/products" class="btn">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Image preview
document.getElementById('imageInput')?.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            let preview = document.getElementById('imagePreview');
            if (!preview) {
                preview = document.createElement('img');
                preview.id = 'imagePreview';
                preview.className = 'img-thumbnail';
                preview.style.maxWidth = '200px';
                this.previousElementSibling?.appendChild(preview);
            }
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});

// Auto generate slug from name
document.querySelector('[name="name"]')?.addEventListener('blur', function() {
    const slugInput = document.querySelector('[name="slug"]');
    if (!slugInput.value && this.value) {
        slugInput.value = this.value.toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .replace(/đ/g, 'd').replace(/Đ/g, 'D')
            .replace(/[^a-z0-9\s-]/g, '')
            .replace(/\s+/g, '-')
            .replace(/-+/g, '-')
            .trim();
    }
});

// Form validation
document.getElementById('productForm')?.addEventListener('submit', function(e) {
    const name = this.querySelector('[name="name"]').value.trim();
    const price = parseFloat(this.querySelector('[name="price"]').value);
    
    if (name.length < 2) {
        e.preventDefault();
        alert('Tên sản phẩm phải có ít nhất 2 ký tự');
        return false;
    }
    
    if (price <= 0) {
        e.preventDefault();
        alert('Giá sản phẩm phải lớn hơn 0');
        return false;
    }
});
</script>

