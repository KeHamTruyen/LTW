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
                <h2 class="page-title">
                    Quản lý tin tức
                </h2>
                <div class="text-secondary mt-1">
                    Tìm thấy <?= $totalPosts ?> bài viết
                </div>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="<?= BASE_URL ?>admin/posts/create" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
                    Thêm bài viết mới
                </a>
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
                <div class="row w-100">
                    <!-- Search form -->
                    <div class="col-md-6">
                        <form action="<?= BASE_URL ?>admin/posts" method="GET" class="d-flex">
                            <input type="search" name="search" class="form-control me-2" 
                                   placeholder="Tìm kiếm bài viết..." 
                                   value="<?= htmlspecialchars($search) ?>">
                            <button type="submit" class="btn btn-primary">Tìm</button>
                        </form>
                    </div>

                    <!-- Filter by status -->
                    <div class="col-md-6">
                        <ul class="nav nav-pills card-header-pills">
                            <li class="nav-item">
                                <a class="nav-link <?= empty($status) ? 'active' : '' ?>" 
                                   href="<?= BASE_URL ?>admin/posts">
                                    Tất cả
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $status === 'published' ? 'active' : '' ?>" 
                                   href="<?= BASE_URL ?>admin/posts?status=published">
                                    Đã xuất bản
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?= $status === 'draft' ? 'active' : '' ?>" 
                                   href="<?= BASE_URL ?>admin/posts?status=draft">
                                    Nháp
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th style="width: 80px;">Ảnh</th>
                            <th>Tiêu đề</th>
                            <th>Tác giả</th>
                            <th>Trạng thái</th>
                            <th>Ngày đăng</th>
                            <th class="w-1">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($posts)): ?>
                            <tr>
                                <td colspan="7" class="text-center text-secondary">
                                    Không tìm thấy bài viết nào
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($posts as $post): ?>
                                <tr>
                                    <td><?= $post['id'] ?></td>
                                    <td>
                                        <?php if ($post['cover_image_url']): ?>
                                            <img src="<?= BASE_URL ?>uploads/<?= htmlspecialchars($post['cover_image_url']) ?>" 
                                                 alt="" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="rounded bg-secondary-lt" style="width: 60px; height: 60px;"></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div><?= htmlspecialchars($post['title']) ?></div>
                                        <div class="text-secondary"><small><?= htmlspecialchars($post['slug']) ?></small></div>
                                    </td>
                                    <td class="text-secondary">
                                        <?= htmlspecialchars($post['author_name'] ?? 'N/A') ?>
                                    </td>
                                    <td>
                                        <?php if ($post['status'] === 'published'): ?>
                                            <span class="badge bg-success">Đã xuất bản</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Nháp</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-secondary">
                                        <?= date('d/m/Y H:i', strtotime($post['published_at'] ?? $post['created_at'])) ?>
                                    </td>
                                    <td>
                                        <div class="btn-list flex-nowrap">
                                            <a href="<?= BASE_URL ?>posts/show?slug=<?= urlencode($post['slug']) ?>" 
                                               class="btn btn-sm btn-ghost-primary" target="_blank" title="Xem">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                            </a>
                                            <a href="<?= BASE_URL ?>admin/posts/edit?id=<?= $post['id'] ?>" 
                                               class="btn btn-sm btn-primary" title="Sửa">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-danger" 
                                                    onclick="deletePost(<?= $post['id'] ?>, '<?= htmlspecialchars(addslashes($post['title'])) ?>')" 
                                                    title="Xóa">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
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
