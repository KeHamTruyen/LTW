<?php
// Generate CSRF for actions
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
                    Bình luận & đánh giá
                </h2>
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
                        <a href="<?= BASE_URL ?>admin/comments" class="nav-link <?= empty($status) ? 'active' : '' ?>">
                            Tất cả
                            <span class="badge bg-secondary ms-2"><?= $totalComments ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>admin/comments?status=pending" class="nav-link <?= $status === 'pending' ? 'active' : '' ?>">
                            Chờ duyệt
                            <?php if ($pendingCount > 0): ?>
                                <span class="badge bg-yellow text-yellow-fg ms-2"><?= $pendingCount ?></span>
                            <?php else: ?>
                                <span class="badge bg-secondary ms-2"><?= $pendingCount ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>admin/comments?status=approved" class="nav-link <?= $status === 'approved' ? 'active' : '' ?>">
                            Đã duyệt
                            <span class="badge bg-success ms-2"><?= $approvedCount ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>admin/comments?status=rejected" class="nav-link <?= $status === 'rejected' ? 'active' : '' ?>">
                            Từ chối
                            <span class="badge bg-red ms-2"><?= $rejectedCount ?></span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= BASE_URL ?>admin/comments?status=spam" class="nav-link <?= $status === 'spam' ? 'active' : '' ?>">
                            Spam
                            <span class="badge bg-dark ms-2"><?= $spamCount ?></span>
                        </a>
                    </li>
                </ul>
            </div>

            <?php if (empty($comments)): ?>
                <div class="card-body">
                    <div class="empty">
                        <div class="empty-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 9h8" /><path d="M8 13h6" /><path d="M18 4a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-5l-5 3v-3h-2a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12z" /></svg>
                        </div>
                        <p class="empty-title">Không có bình luận nào</p>
                        <p class="empty-subtitle text-secondary">
                            Chưa có bình luận nào trong danh mục này
                        </p>
                    </div>
                </div>
            <?php else: ?>
                <div class="list-group list-group-flush">
                    <?php foreach ($comments as $comment): ?>
                        <div class="list-group-item">
                            <div class="row align-items-start">
                                <div class="col-auto">
                                    <span class="avatar" style="background-image: url(https://ui-avatars.com/api/?name=<?= urlencode($comment['author_name'] ?? $comment['user_name'] ?? 'User') ?>&background=random)"></span>
                                </div>
                                <div class="col">
                                    <div class="d-flex align-items-center mb-2">
                                        <strong><?= htmlspecialchars($comment['author_name'] ?? $comment['user_name'] ?? 'Ẩn danh') ?></strong>
                                        
                                        <?php if ($comment['rating']): ?>
                                            <span class="badge bg-yellow ms-2">
                                                <?= str_repeat('⭐', (int)$comment['rating']) ?>
                                            </span>
                                        <?php endif; ?>

                                        <!-- Status badge -->
                                        <?php
                                        $statusBadges = [
                                            'pending' => '<span class="badge bg-yellow text-yellow-fg ms-2">Chờ duyệt</span>',
                                            'approved' => '<span class="badge bg-success ms-2">Đã duyệt</span>',
                                            'rejected' => '<span class="badge bg-red ms-2">Từ chối</span>',
                                            'spam' => '<span class="badge bg-dark ms-2">Spam</span>',
                                        ];
                                        echo $statusBadges[$comment['status']] ?? '';
                                        ?>

                                        <small class="text-secondary ms-auto">
                                            <?= date('d/m/Y H:i', strtotime($comment['created_at'])) ?>
                                        </small>
                                    </div>

                                    <div class="text-secondary mb-2">
                                        <strong>Bài viết:</strong> 
                                        <a href="<?= BASE_URL ?>posts/show?slug=<?= urlencode($comment['post_title'] ?? '') ?>" target="_blank">
                                            <?= htmlspecialchars($comment['post_title'] ?? 'N/A') ?>
                                        </a>
                                    </div>

                                    <div class="text-secondary mb-2">
                                        <?= nl2br(htmlspecialchars($comment['content'])) ?>
                                    </div>

                                    <?php if ($comment['author_email']): ?>
                                        <div class="text-secondary">
                                            <small>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="16" x="2" y="4" rx="2"></rect><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"></path></svg>
                                                <?= htmlspecialchars($comment['author_email']) ?>
                                            </small>
                                            &nbsp;•&nbsp;
                                            <small>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M12 2a14.5 14.5 0 0 0 0 20 14.5 14.5 0 0 0 0-20"></path><path d="M2 12h20"></path></svg>
                                                IP: <?= htmlspecialchars($comment['ip_address'] ?? 'N/A') ?>
                                            </small>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Actions -->
                                    <div class="mt-3">
                                        <div class="btn-list">
                                            <?php if ($comment['status'] !== 'approved'): ?>
                                                <form action="<?= BASE_URL ?>admin/comments/approve" method="POST" style="display: inline;">
                                                    <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
                                                    <input type="hidden" name="id" value="<?= $comment['id'] ?>">
                                                    <button type="submit" class="btn btn-success btn-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-check" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                                        Duyệt
                                                    </button>
                                                </form>
                                            <?php endif; ?>

                                            <?php if ($comment['status'] !== 'rejected'): ?>
                                                <form action="<?= BASE_URL ?>admin/comments/reject" method="POST" style="display: inline;">
                                                    <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
                                                    <input type="hidden" name="id" value="<?= $comment['id'] ?>">
                                                    <button type="submit" class="btn btn-warning btn-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 6l-12 12" /><path d="M6 6l12 12" /></svg>
                                                        Từ chối
                                                    </button>
                                                </form>
                                            <?php endif; ?>

                                            <?php if ($comment['status'] !== 'spam'): ?>
                                                <form action="<?= BASE_URL ?>admin/comments/spam" method="POST" style="display: inline;">
                                                    <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
                                                    <input type="hidden" name="id" value="<?= $comment['id'] ?>">
                                                    <button type="submit" class="btn btn-secondary btn-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-ban" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M5.7 5.7l12.6 12.6" /></svg>
                                                        Spam
                                                    </button>
                                                </form>
                                            <?php endif; ?>

                                            <form action="<?= BASE_URL ?>admin/comments/delete" method="POST" style="display: inline;">
                                                <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
                                                <input type="hidden" name="id" value="<?= $comment['id'] ?>">
                                                <button type="submit" class="btn btn-danger btn-sm" 
                                                        onclick="return confirm('Bạn có chắc muốn xóa bình luận này?')">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                                    Xóa
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($totalPages > 1): ?>
                    <div class="card-footer d-flex align-items-center">
                        <p class="m-0 text-secondary">
                            Trang <?= $currentPage ?> / <?= $totalPages ?>
                        </p>
                        <ul class="pagination m-0 ms-auto">
                            <?php if ($currentPage > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?p=<?= $currentPage - 1 ?><?= !empty($status) ? '&status=' . $status : '' ?>">
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
                                    <a class="page-link" href="?p=<?= $i ?><?= !empty($status) ? '&status=' . $status : '' ?>">
                                        <?= $i ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($currentPage < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?p=<?= $currentPage + 1 ?><?= !empty($status) ? '&status=' . $status : '' ?>">
                                        Sau
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
