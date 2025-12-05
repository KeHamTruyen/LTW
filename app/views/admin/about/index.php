<?php
$about = $about ?? null;
?>
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Quản lý trang Giới thiệu</h2>
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
                <form method="post" action="<?= BASE_URL ?>admin/about/update" enctype="multipart/form-data">
                    <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
                    
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">Nội dung trang Giới thiệu</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label required">Tiêu đề</label>
                                <input type="text" name="title" class="form-control" 
                                       value="<?= htmlspecialchars($about['title'] ?? 'Về chúng tôi') ?>" 
                                       required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Mô tả</label>
                                <textarea name="description" class="form-control" rows="6" 
                                          placeholder="Mô tả về công ty..."><?= htmlspecialchars($about['description'] ?? '') ?></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Sứ mệnh</label>
                                <textarea name="mission" class="form-control" rows="4" 
                                          placeholder="Sứ mệnh của công ty..."><?= htmlspecialchars($about['mission'] ?? '') ?></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Tầm nhìn</label>
                                <textarea name="vision" class="form-control" rows="4" 
                                          placeholder="Tầm nhìn của công ty..."><?= htmlspecialchars($about['vision'] ?? '') ?></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Hình ảnh</label>
                                <?php if (!empty($about['image'])): ?>
                                    <div class="mb-2">
                                        <img src="<?= htmlspecialchars($about['image']) ?>" 
                                             alt="About" 
                                             id="imagePreview"
                                             class="img-thumbnail" 
                                             style="max-width: 300px;">
                                    </div>
                                <?php endif; ?>
                                <input type="file" name="image" id="imageInput" accept="image/*" class="form-control">
                                <small class="form-hint">JPG, PNG, WEBP tối đa 2MB</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                        <a href="<?= BASE_URL ?>about" target="_blank" class="btn">Xem trang</a>
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
                preview.style.maxWidth = '300px';
                this.previousElementSibling?.appendChild(preview);
            }
            preview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }
});
</script>

