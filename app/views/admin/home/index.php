<?php
$home = $home ?? null;
$combos = [];
if ($home && !empty($home['service_combos'])) {
    $combos = json_decode($home['service_combos'], true) ?: [];
}
// Ensure at least 3 combos
while (count($combos) < 3) {
    $combos[] = ['name' => '', 'price' => '', 'items' => []];
}
?>
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Quản lý trang Home</h2>
                <div class="text-secondary mt-1">
                    Quản lý nội dung hiển thị trên trang chủ
                </div>
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
        
        <?php if (isset($_SESSION['flash_error'])): ?>
            <div class="alert alert-danger alert-dismissible">
                <?= htmlspecialchars($_SESSION['flash_error']) ?>
                <a class="btn-close" data-bs-dismiss="alert"></a>
            </div>
            <?php unset($_SESSION['flash_error']); ?>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-12">
                <form method="post" action="<?= BASE_URL ?>admin/home/update" enctype="multipart/form-data">
                    <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
                    
                    <!-- Hero Section -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="card-title">Phần Hero (Banner chính)</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label">Tiêu đề phụ</label>
                                        <input type="text" name="hero_subtitle" class="form-control" 
                                               value="<?= htmlspecialchars($home['hero_subtitle'] ?? 'Pet\'s Choice') ?>">
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="form-label required">Tiêu đề chính</label>
                                        <textarea name="hero_title" class="form-control" rows="3" required><?= htmlspecialchars(str_replace('<br>', "\n", str_replace('<br />', "\n", $home['hero_title'] ?? 'A pet store with' . "\n" . 'everything you need'))) ?></textarea>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="form-label">Text nút bấm</label>
                                        <input type="text" name="hero_button_text" class="form-control" 
                                               value="<?= htmlspecialchars($home['hero_button_text'] ?? 'Shop Now') ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label">Hình ảnh</label>
                                        <?php if (!empty($home['hero_image_url'])): ?>
                                            <div class="mb-3">
                                                <img src="<?= htmlspecialchars($home['hero_image_url']) ?>" 
                                                     alt="Hero" 
                                                     id="heroImagePreview"
                                                     class="img-thumbnail" 
                                                     style="max-width: 300px;">
                                            </div>
                                        <?php endif; ?>
                                        <input type="file" name="hero_image" id="heroImageInput" accept="image/*" class="form-control">
                                        <small class="form-hint">JPG, PNG, WEBP, GIF tối đa 5MB</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Company Info -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="card-title">Thông tin công ty</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label">Tên công ty</label>
                                        <input type="text" name="company_name" class="form-control" 
                                               value="<?= htmlspecialchars($home['company_name'] ?? 'Pet\'s Choice Store') ?>">
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="form-label">Số điện thoại</label>
                                        <input type="text" name="company_phone" class="form-control" 
                                               value="<?= htmlspecialchars($home['company_phone'] ?? '+039 871-5611') ?>">
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="company_email" class="form-control" 
                                               value="<?= htmlspecialchars($home['company_email'] ?? 'petschoice@outlook.com') ?>">
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="form-label">Địa chỉ</label>
                                        <input type="text" name="company_address" class="form-control" 
                                               value="<?= htmlspecialchars($home['company_address'] ?? 'Khu phố 6, Phường Linh Trung Thủ Đức, TP.HCM') ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label">Logo công ty</label>
                                        <?php if (!empty($home['company_logo_url'])): ?>
                                            <div class="mb-3">
                                                <img src="<?= htmlspecialchars($home['company_logo_url']) ?>" 
                                                     alt="Logo" 
                                                     id="logoPreview"
                                                     class="img-thumbnail" 
                                                     style="max-width: 200px;">
                                            </div>
                                        <?php endif; ?>
                                        <input type="file" name="company_logo" id="logoInput" accept="image/*" class="form-control">
                                        <small class="form-hint">JPG, PNG, WEBP, GIF tối đa 5MB</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Service Section -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="card-title">Phần Dịch vụ (Service Combos)</h3>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label">Tiêu đề</label>
                                    <input type="text" name="service_title" class="form-control" 
                                           value="<?= htmlspecialchars($home['service_title'] ?? 'Pet Service') ?>">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Tiêu đề phụ</label>
                                    <input type="text" name="service_subtitle" class="form-control" 
                                           value="<?= htmlspecialchars($home['service_subtitle'] ?? 'Price Combo') ?>">
                                </div>
                            </div>
                            
                            <div id="serviceCombos">
                                <?php foreach ($combos as $index => $combo): ?>
                                <div class="card mb-4 combo-item" data-index="<?= $index ?>">
                                    <div class="card-header">
                                        <h4 class="card-title">Combo <?= $index + 1 ?></h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row mb-4">
                                            <div class="col-md-6">
                                                <label class="form-label">Tên combo</label>
                                                <input type="text" name="service_combos[<?= $index ?>][name]" 
                                                       class="form-control" 
                                                       value="<?= htmlspecialchars($combo['name'] ?? '') ?>">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Giá</label>
                                                <input type="text" name="service_combos[<?= $index ?>][price]" 
                                                       class="form-control" 
                                                       value="<?= htmlspecialchars($combo['price'] ?? '') ?>"
                                                       placeholder="200.000 VNĐ">
                                            </div>
                                        </div>
                                        
                                        <div class="mb-4">
                                            <label class="form-label">Dịch vụ</label>
                                            <div id="items-<?= $index ?>">
                                                <?php 
                                                $items = $combo['items'] ?? [];
                                                if (empty($items)) {
                                                    $items = [
                                                        ['name' => 'Tắm sấy', 'included' => false],
                                                        ['name' => 'Vệ sinh', 'included' => false],
                                                        ['name' => 'Cắt tỉa lông', 'included' => false]
                                                    ];
                                                }
                                                foreach ($items as $itemIndex => $item): 
                                                ?>
                                                <div class="input-group mb-3">
                                                    <input type="text" 
                                                           name="service_combos[<?= $index ?>][items][<?= $itemIndex ?>][name]" 
                                                           class="form-control" 
                                                           value="<?= htmlspecialchars($item['name'] ?? '') ?>"
                                                           placeholder="Tên dịch vụ">
                                                    <div class="input-group-text">
                                                        <label class="form-check form-switch">
                                                            <input type="checkbox" 
                                                                   name="service_combos[<?= $index ?>][items][<?= $itemIndex ?>][included]" 
                                                                   value="1" 
                                                                   class="form-check-input"
                                                                   <?= !empty($item['included']) ? 'checked' : '' ?>>
                                                        </label>
                                                    </div>
                                                </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- About Section -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h3 class="card-title">Phần dịch vụ</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label">Tiêu đề phụ</label>
                                        <input type="text" name="about_subtitle" class="form-control" 
                                               value="<?= htmlspecialchars($home['about_subtitle'] ?? 'Pet\'s Choice') ?>">
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="form-label">Tiêu đề chính</label>
                                        <input type="text" name="about_title" class="form-control" 
                                               value="<?= htmlspecialchars($home['about_title'] ?? 'The smarter way to service for your pet') ?>">
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="form-label">Mô tả</label>
                                        <textarea name="about_description" class="form-control" rows="4"><?= htmlspecialchars($home['about_description'] ?? 'Chăm sóc thú cưng tận tâm với đội ngũ chuyên nghiệp, quy trình khoa học và an toàn cho bé.') ?></textarea>
                                    </div>
                                    
                                    <div class="mb-4">
                                        <label class="form-label">Text nút bấm</label>
                                        <input type="text" name="about_button_text" class="form-control" 
                                               value="<?= htmlspecialchars($home['about_button_text'] ?? 'Xem thêm') ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label class="form-label">Hình ảnh</label>
                                        <?php if (!empty($home['about_image_url'])): ?>
                                            <div class="mb-3">
                                                <img src="<?= htmlspecialchars($home['about_image_url']) ?>" 
                                                     alt="About" 
                                                     id="aboutImagePreview"
                                                     class="img-thumbnail" 
                                                     style="max-width: 300px;">
                                            </div>
                                        <?php endif; ?>
                                        <input type="file" name="about_image" id="aboutImageInput" accept="image/*" class="form-control">
                                        <small class="form-hint">JPG, PNG, WEBP, GIF tối đa 5MB</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                        <a href="<?= BASE_URL ?>" target="_blank" class="btn">Xem trang chủ</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Image preview handlers
function setupImagePreview(inputId, previewId) {
    const input = document.getElementById(inputId);
    const preview = document.getElementById(previewId);
    
    if (input) {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    let img = document.getElementById(previewId);
                    if (!img) {
                        img = document.createElement('img');
                        img.id = previewId;
                        img.className = 'img-thumbnail';
                        img.style.maxWidth = '300px';
                        input.parentElement.insertBefore(img, input);
                    }
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }
}

setupImagePreview('heroImageInput', 'heroImagePreview');
setupImagePreview('logoInput', 'logoPreview');
setupImagePreview('aboutImageInput', 'aboutImagePreview');
</script>


