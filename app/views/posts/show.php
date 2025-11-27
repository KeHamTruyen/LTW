<?php
// Generate CSRF token for comment form
if (!isset($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}
?>

<div class="container-xl">
    <!-- Back button -->
    <div class="page-header d-print-none">
        <div class="row g-2 align-items-center">
            <div class="col">
                <a href="<?= BASE_URL ?>posts" class="btn btn-ghost-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    Quay lại danh sách
                </a>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="row">
            <!-- Main content -->
            <div class="col-lg-8">
                <div class="card">
                    <?php if ($post['cover_image_url']): ?>
                        <img src="<?= BASE_URL ?>uploads/<?= htmlspecialchars($post['cover_image_url']) ?>" 
                             class="card-img-top" 
                             alt="<?= htmlspecialchars($post['title']) ?>"
                             style="max-height: 400px; object-fit: cover;">
                    <?php endif; ?>
                    
                    <div class="card-body">
                        <h1 class="card-title"><?= htmlspecialchars($post['title']) ?></h1>
                        
                        <div class="text-secondary mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path></svg>
                            <?= htmlspecialchars($post['author_name'] ?? 'Admin') ?>
                            &nbsp;•&nbsp;
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect><line x1="16" x2="16" y1="2" y2="6"></line><line x1="8" x2="8" y1="2" y2="6"></line><line x1="3" x2="21" y1="10" y2="10"></line></svg>
                            <?= date('d/m/Y H:i', strtotime($post['published_at'] ?? $post['created_at'])) ?>
                        </div>

                        <?php if ($post['summary']): ?>
                            <div class="alert alert-info">
                                <strong>Tóm tắt:</strong> <?= htmlspecialchars($post['summary']) ?>
                            </div>
                        <?php endif; ?>

                        <div class="markdown">
                            <?= $post['content_html'] ?>
                        </div>
                    </div>
                </div>

                <!-- Comments section -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h3 class="card-title">
                            Bình luận (<?= count($comments) ?>)
                            <?php if ($ratingCount > 0): ?>
                                <span class="badge bg-yellow ms-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                    <?= number_format($avgRating, 1) ?> / 5
                                </span>
                            <?php endif; ?>
                        </h3>
                    </div>
                    <div class="card-body">
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

                        <!-- Comment form -->
                        <form action="<?= BASE_URL ?>posts/comment" method="POST" class="mb-4">
                            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
                            <input type="hidden" name="post_id" value="<?= $post['id'] ?>">

                            <div class="mb-3">
                                <label class="form-label">Đánh giá</label>
                                <div class="form-selectgroup">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <label class="form-selectgroup-item">
                                            <input type="radio" name="rating" value="<?= $i ?>" class="form-selectgroup-input">
                                            <span class="form-selectgroup-label">
                                                <?= str_repeat('⭐', $i) ?>
                                            </span>
                                        </label>
                                    <?php endfor; ?>
                                </div>
                            </div>

                            <?php if (!isset($_SESSION['user_id'])): ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label required">Tên của bạn</label>
                                            <input type="text" name="author_name" class="form-control" required minlength="2">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label required">Email</label>
                                            <input type="email" name="author_email" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="mb-3">
                                <label class="form-label required">Bình luận</label>
                                <textarea name="content" class="form-control" rows="4" required minlength="10" maxlength="1000" placeholder="Nhập bình luận của bạn..."></textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                Gửi bình luận
                            </button>
                        </form>

                        <!-- Comments list -->
                        <?php if (empty($comments)): ?>
                            <p class="text-secondary">Chưa có bình luận nào. Hãy là người đầu tiên bình luận!</p>
                        <?php else: ?>
                            <div class="list-group list-group-flush">
                                <?php foreach ($comments as $comment): ?>
                                    <div class="list-group-item">
                                        <div class="row align-items-start">
                                            <div class="col-auto">
                                                <span class="avatar" style="background-image: url(https://ui-avatars.com/api/?name=<?= urlencode($comment['author_name'] ?? $comment['user_name'] ?? 'User') ?>&background=random)"></span>
                                            </div>
                                            <div class="col">
                                                <div class="d-flex align-items-center">
                                                    <strong><?= htmlspecialchars($comment['author_name'] ?? $comment['user_name'] ?? 'Ẩn danh') ?></strong>
                                                    <?php if ($comment['rating']): ?>
                                                        <span class="badge bg-yellow ms-2">
                                                            <?= str_repeat('⭐', (int)$comment['rating']) ?>
                                                        </span>
                                                    <?php endif; ?>
                                                    <small class="text-secondary ms-auto">
                                                        <?= date('d/m/Y H:i', strtotime($comment['created_at'])) ?>
                                                    </small>
                                                </div>
                                                <div class="text-secondary mt-1">
                                                    <?= nl2br(htmlspecialchars($comment['content'])) ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Thông tin bài viết</h3>
                    </div>
                    <div class="list-group list-group-flush">
                        <div class="list-group-item">
                            <div class="row">
                                <div class="col">Trạng thái:</div>
                                <div class="col-auto">
                                    <span class="badge bg-success">Đã xuất bản</span>
                                </div>
                            </div>
                        </div>
                        <div class="list-group-item">
                            <div class="row">
                                <div class="col">Tác giả:</div>
                                <div class="col-auto"><?= htmlspecialchars($post['author_name'] ?? 'Admin') ?></div>
                            </div>
                        </div>
                        <div class="list-group-item">
                            <div class="row">
                                <div class="col">Ngày đăng:</div>
                                <div class="col-auto"><?= date('d/m/Y', strtotime($post['published_at'] ?? $post['created_at'])) ?></div>
                            </div>
                        </div>
                        <?php if ($ratingCount > 0): ?>
                            <div class="list-group-item">
                                <div class="row">
                                    <div class="col">Đánh giá:</div>
                                    <div class="col-auto">
                                        <strong><?= number_format($avgRating, 1) ?>/5</strong>
                                        <small class="text-secondary">(<?= $ratingCount ?> lượt)</small>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
