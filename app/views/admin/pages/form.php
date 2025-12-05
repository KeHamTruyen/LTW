<?php
$page = $page ?? null;
$isEdit = $page !== null;
?>
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title"><?= $isEdit ? 'Chỉnh sửa trang' : 'Thêm trang mới' ?></h2>
            </div>
            <div class="col-auto">
                <a href="<?= BASE_URL ?>admin/pages" class="btn">Quay lại</a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-lg-10">
                <form method="post" action="<?= BASE_URL ?>admin/pages/<?= $isEdit ? 'update' : 'store' ?>" id="pageForm">
                    <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
                    <?php if ($isEdit): ?>
                        <input type="hidden" name="id" value="<?= $page['id'] ?>">
                    <?php endif; ?>

                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">Thông tin cơ bản</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label required">Slug (URL)</label>
                                <input type="text" name="slug" class="form-control" 
                                       value="<?= htmlspecialchars($page['slug'] ?? '') ?>" 
                                       required 
                                       pattern="[a-z0-9-]+"
                                       placeholder="vi-du-trang">
                                <small class="form-hint">Chỉ chấp nhận chữ thường, số và dấu gạch ngang</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Tiêu đề</label>
                                <input type="text" name="title" class="form-control" 
                                       value="<?= htmlspecialchars($page['title'] ?? '') ?>" 
                                       required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nội dung</label>
                                <textarea name="content_html" id="content_html" class="form-control" rows="15"><?= htmlspecialchars($page['content_html'] ?? '') ?></textarea>
                                <small class="form-hint">Hỗ trợ HTML. Sử dụng trình soạn thảo bên dưới để định dạng.</small>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Trạng thái</label>
                                        <select name="status" class="form-select">
                                            <option value="published" <?= ($page['status'] ?? 'published') === 'published' ? 'selected' : '' ?>>Đã xuất bản</option>
                                            <option value="draft" <?= ($page['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Nháp</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">SEO</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Meta Title</label>
                                <input type="text" name="meta_title" class="form-control" 
                                       value="<?= htmlspecialchars($page['meta_title'] ?? '') ?>"
                                       placeholder="Để trống sẽ dùng tiêu đề trang">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Meta Description</label>
                                <textarea name="meta_description" class="form-control" rows="3"><?= htmlspecialchars($page['meta_description'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Cập nhật' : 'Thêm mới' ?></button>
                        <a href="<?= BASE_URL ?>admin/pages" class="btn">Hủy</a>
                        <?php if ($isEdit && $page['status'] === 'published'): ?>
                            <a href="<?= BASE_URL ?>page?slug=<?= urlencode($page['slug']) ?>" 
                               target="_blank" 
                               class="btn">Xem trang</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- TinyMCE WYSIWYG Editor -->
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
tinymce.init({
    selector: '#content_html',
    height: 500,
    menubar: false,
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
    ],
    toolbar: 'undo redo | blocks | ' +
        'bold italic forecolor | alignleft aligncenter ' +
        'alignright alignjustify | bullist numlist outdent indent | ' +
        'removeformat | help',
    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
    language: 'vi',
    promotion: false
});

// Form validation
document.getElementById('pageForm')?.addEventListener('submit', function(e) {
    const slug = this.querySelector('[name="slug"]').value.trim();
    const title = this.querySelector('[name="title"]').value.trim();
    
    if (!slug.match(/^[a-z0-9-]+$/)) {
        e.preventDefault();
        alert('Slug chỉ được chứa chữ thường, số và dấu gạch ngang');
        return false;
    }
    
    if (title.length < 2) {
        e.preventDefault();
        alert('Tiêu đề phải có ít nhất 2 ký tự');
        return false;
    }
});
</script>

