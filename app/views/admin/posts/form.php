<?php
$isEdit = $post !== null;
$oldInput = $_SESSION['old_input'] ?? [];
unset($_SESSION['old_input']);
?>

<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Quản lý nội dung
                </div>
                <h2 class="page-title">
                    <?= $isEdit ? 'Chỉnh sửa bài viết' : 'Thêm bài viết mới' ?>
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="<?= BASE_URL ?>admin/posts" class="btn">
                        Quay lại
                    </a>
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
                <?= $_SESSION['flash_error'] ?>
                <a class="btn-close" data-bs-dismiss="alert"></a>
            </div>
            <?php unset($_SESSION['flash_error']); ?>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>admin/posts/<?= $isEdit ? 'update' : 'store' ?>" 
              method="POST" 
              enctype="multipart/form-data">
            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?= $post['id'] ?>">
            <?php endif; ?>

            <div class="row row-cards">
                <!-- Main content -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <!-- Title -->
                            <div class="mb-3">
                                <label class="form-label required">Tiêu đề</label>
                                <input type="text" 
                                       name="title" 
                                       class="form-control" 
                                       required 
                                       minlength="5" 
                                       maxlength="255"
                                       value="<?= htmlspecialchars($oldInput['title'] ?? $post['title'] ?? '') ?>">
                            </div>

                            <!-- Summary -->
                            <div class="mb-3">
                                <label class="form-label required">Tóm tắt</label>
                                <textarea name="summary" 
                                          class="form-control" 
                                          rows="3" 
                                          required 
                                          minlength="20"><?= htmlspecialchars($oldInput['summary'] ?? $post['summary'] ?? '') ?></textarea>
                                <small class="form-hint">Tóm tắt ngắn gọn về nội dung bài viết (hiển thị ở trang danh sách)</small>
                            </div>

                            <!-- Content -->
                            <div class="mb-3">
                                <label class="form-label required">Nội dung</label>
                                <textarea name="content_html" 
                                          class="form-control" 
                                          rows="15" 
                                          required 
                                          minlength="50"><?= htmlspecialchars($oldInput['content_html'] ?? $post['content_html'] ?? '') ?></textarea>
                                <small class="form-hint">Có thể sử dụng HTML để định dạng nội dung</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Status -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h3 class="card-title">Xuất bản</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Trạng thái</label>
                                <select name="status" class="form-select">
                                    <option value="draft" <?= ($oldInput['status'] ?? $post['status'] ?? 'draft') === 'draft' ? 'selected' : '' ?>>
                                        Nháp
                                    </option>
                                    <option value="published" <?= ($oldInput['status'] ?? $post['status'] ?? '') === 'published' ? 'selected' : '' ?>>
                                        Đã xuất bản
                                    </option>
                                </select>
                            </div>

                            <?php if ($isEdit && $post['published_at']): ?>
                                <div class="text-secondary">
                                    <small>
                                        <strong>Đã xuất bản:</strong><br>
                                        <?= date('d/m/Y H:i', strtotime($post['published_at'])) ?>
                                    </small>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex">
                                <button type="submit" class="btn btn-primary ms-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" /><path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" /><path d="M14 4l0 4l-6 0l0 -4" /></svg>
                                    <?= $isEdit ? 'Cập nhật' : 'Tạo bài viết' ?>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Cover Image -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Ảnh bìa</h3>
                        </div>
                        <div class="card-body">
                            <?php if ($isEdit && $post['cover_image_url']): ?>
                                <div class="mb-3">
                                    <img src="<?= BASE_URL ?>uploads/<?= htmlspecialchars($post['cover_image_url']) ?>" 
                                         class="img-fluid rounded" 
                                         alt="Cover">
                                </div>
                            <?php endif; ?>

                            <div class="mb-0">
                                <label class="form-label">
                                    <?= ($isEdit && $post['cover_image_url']) ? 'Thay đổi ảnh bìa' : 'Upload ảnh bìa' ?>
                                </label>
                                <input type="file" 
                                       name="cover_image" 
                                       class="form-control" 
                                       accept="image/jpeg,image/jpg,image/png,image/webp">
                                <small class="form-hint">
                                    JPG, PNG, WEBP. Tối đa 5MB.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
