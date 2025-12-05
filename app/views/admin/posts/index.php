<?php
// Generate CSRF for delete actions
if (!isset($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
?>

<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">
                    Quản lý nội dung
                </div>
                <h2 class="page-title">
                    Tin tức
                </h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <div class="btn-list">
                    <a href="<?= BASE_URL ?>admin/posts/create" class="btn btn-primary d-none d-sm-inline-block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                        Thêm bài viết mới
                    </a>
                    <a href="<?= BASE_URL ?>admin/posts/create" class="btn btn-primary d-sm-none btn-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
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
                <?= htmlspecialchars($_SESSION['flash_error']) ?>
                <a class="btn-close" data-bs-dismiss="alert"></a>
            </div>
            <?php unset($_SESSION['flash_error']); ?>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" data-bs-toggle="tabs">
                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>admin/posts" class="nav-link <?= empty($status) ? 'active' : '' ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 11l-4 4l4 4m-4 -4h11a4 4 0 0 0 0 -8h-1" /></svg>
                            Tất cả
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>admin/posts?status=published" class="nav-link <?= $status === 'published' ? 'active' : '' ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                            Đã xuất bản
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>admin/posts?status=draft" class="nav-link <?= $status === 'draft' ? 'active' : '' ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" /><path d="M12 12l8 -4.5" /><path d="M12 12l0 9" /><path d="M12 12l-8 -4.5" /></svg>
                            Nháp
                        </a>
                    </li>
                    <li class="nav-item ms-auto">
                        <form action="<?= BASE_URL ?>admin/posts" method="GET" class="d-flex">
                            <div class="input-icon">
                                <input type="search" name="search" class="form-control form-control-sm" 
                                       placeholder="Tìm kiếm..." 
                                       value="<?= htmlspecialchars($search) ?>"
                                       style="min-width: 200px;">
                                <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 10m-7 0a7 7 0 1 0 14 0a7 7 0 1 0 -14 0" /><path d="M21 21l-6 -6" /></svg>
                                </span>
                            </div>
                        </form>
                    </li>
                </ul>
            </div>
            <div class="card-body border-bottom py-3">
                <div class="d-flex">
                    <div class="text-secondary">
                        Hiển thị
                        <span class="mx-2">
                            <?php 
                            $start = ($currentPage - 1) * 10 + 1;
                            $end = min($currentPage * 10, $totalPosts);
                            echo $start . ' - ' . $end;
                            ?>
                        </span>
                        trong số <?= $totalPosts ?> bài viết
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-vcenter table-mobile-md card-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Bài viết</th>
                            <th>Danh mục</th>
                            <th>Tác giả</th>
                            <th>Trạng thái</th>
                            <th>Ngày đăng</th>
                            <th class="w-1"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($posts)): ?>
                            <tr>
                                <td colspan="7" class="text-center">
                                    <div class="empty">
                                        <div class="empty-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 9l1 0" /><path d="M9 13l6 0" /><path d="M9 17l6 0" /></svg>
                                        </div>
                                        <p class="empty-title">Không tìm thấy bài viết nào</p>
                                        <p class="empty-subtitle text-secondary">
                                            Hãy thử điều chỉnh bộ lọc hoặc tìm kiếm của bạn
                                        </p>
                                        <div class="empty-action">
                                            <a href="<?= BASE_URL ?>admin/posts/create" class="btn btn-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                                                Thêm bài viết đầu tiên
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($posts as $post): ?>
                                <tr>
                                    <td>
                                        <span class="text-secondary">#<?= $post['id'] ?></span>
                                    </td>
                                    <td>
                                        <div class="d-flex py-1 align-items-center">
                                            <span class="avatar me-2" style="background-image: url(<?= $post['cover_image_url'] ? BASE_URL . 'uploads/' . htmlspecialchars($post['cover_image_url']) : 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 200 200\'%3E%3Crect fill=\'%23e9ecef\' width=\'200\' height=\'200\'/%3E%3C/svg%3E' ?>)"></span>
                                            <div class="flex-fill">
                                                <div class="font-weight-medium"><?= htmlspecialchars($post['title']) ?></div>
                                                <div class="text-secondary">
                                                    <a href="<?= BASE_URL ?>posts/show?slug=<?= urlencode($post['slug']) ?>" class="text-reset" target="_blank"><?= htmlspecialchars($post['slug']) ?></a>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if (!empty($post['category_name'])): ?>
                                            <span class="badge bg-azure-lt"><?= htmlspecialchars($post['category_name']) ?></span>
                                        <?php else: ?>
                                            <span class="text-secondary">--</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="text-secondary"><?= htmlspecialchars($post['author_name'] ?? 'N/A') ?></div>
                                    </td>
                                    <td>
                                        <?php if ($post['status'] === 'published'): ?>
                                            <span class="badge bg-success me-1"></span> Đã xuất bản
                                        <?php else: ?>
                                            <span class="badge bg-secondary me-1"></span> Nháp
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-secondary">
                                        <?= date('d/m/Y', strtotime($post['published_at'] ?? $post['created_at'])) ?>
                                    </td>
                                    <td>
                                        <div class="btn-list flex-nowrap justify-content-end">
                                            <a href="<?= BASE_URL ?>posts/show?slug=<?= urlencode($post['slug']) ?>" 
                                               class="btn btn-icon btn-ghost-secondary" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="Xem">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                            </a>
                                            <a href="<?= BASE_URL ?>admin/posts/edit?id=<?= $post['id'] ?>" 
                                               class="btn btn-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="Chỉnh sửa">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                            </a>
                                            <button type="button" class="btn btn-icon btn-ghost-danger" 
                                                    onclick="deletePost(<?= $post['id'] ?>, '<?= htmlspecialchars(addslashes($post['title'])) ?>')"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="Xóa">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($totalPages > 1): ?>
                <div class="card-footer d-flex align-items-center">
                    <p class="m-0 text-secondary">
                        Trang <?= $currentPage ?> / <?= $totalPages ?>
                    </p>
                    <ul class="pagination m-0 ms-auto">
                        <?php if ($currentPage > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?p=<?= $currentPage - 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($status) ? '&status=' . $status : '' ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                                    Trước
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php
                        $start = max(1, $currentPage - 2);
                        $end = min($totalPages, $currentPage + 2);
                        
                        for ($i = $start; $i <= $end; $i++):
                        ?>
                            <li class="page-item <?= $i === $currentPage ? 'active' : '' ?>">
                                <a class="page-link" href="?p=<?= $i ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($status) ? '&status=' . $status : '' ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($currentPage < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?p=<?= $currentPage + 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($status) ? '&status=' . $status : '' ?>">
                                    Sau
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Delete form (hidden) -->
<form id="deleteForm" action="<?= BASE_URL ?>admin/posts/delete" method="POST" style="display: none;">
    <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
    <input type="hidden" name="id" id="deletePostId">
</form>

<script>
function deletePost(id, title) {
    if (confirm('Bạn có chắc muốn xóa bài viết "' + title + '"?\n\nHành động này không thể hoàn tác!')) {
        document.getElementById('deletePostId').value = id;
        document.getElementById('deleteForm').submit();
    }
}
</script>
