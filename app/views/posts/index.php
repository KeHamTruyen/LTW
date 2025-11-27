<div class="container-xl">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">
                    Tin tức & Bài viết
                </h2>
                <div class="text-secondary mt-1">
                    Tìm thấy <?= $totalPosts ?> bài viết
                </div>
            </div>
            <div class="col-auto ms-auto">
                <!-- Search form -->
                <form action="<?= BASE_URL ?>posts" method="GET" class="d-flex">
                    <input type="search" name="search" class="form-control me-2" 
                           placeholder="Tìm kiếm bài viết..." 
                           value="<?= htmlspecialchars($search) ?>"
                           style="width: 300px;">
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><path d="m21 21-4.35-4.35"></path></svg>
                        Tìm
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Posts grid -->
    <div class="page-body">
        <?php if (empty($posts)): ?>
            <div class="empty">
                <div class="empty-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7V5a2 2 0 0 1 2-2h2"></path><path d="M17 3h2a2 2 0 0 1 2 2v2"></path><path d="M21 17v2a2 2 0 0 1-2 2h-2"></path><path d="M7 21H5a2 2 0 0 1-2-2v-2"></path><circle cx="12" cy="12" r="3"></circle></svg>
                </div>
                <p class="empty-title">Không tìm thấy bài viết nào</p>
                <p class="empty-subtitle text-secondary">
                    <?php if (!empty($search)): ?>
                        Thử tìm kiếm với từ khóa khác
                    <?php else: ?>
                        Chưa có bài viết nào được đăng
                    <?php endif; ?>
                </p>
            </div>
        <?php else: ?>
            <div class="row row-cards">
                <?php foreach ($posts as $post): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card">
                            <?php if ($post['cover_image_url']): ?>
                                <img src="<?= BASE_URL ?>uploads/<?= htmlspecialchars($post['cover_image_url']) ?>" 
                                     class="card-img-top" 
                                     alt="<?= htmlspecialchars($post['title']) ?>"
                                     style="height: 200px; object-fit: cover;">
                            <?php else: ?>
                                <div class="card-img-top" style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);"></div>
                            <?php endif; ?>
                            
                            <div class="card-body">
                                <h3 class="card-title">
                                    <a href="<?= BASE_URL ?>posts/show?slug=<?= urlencode($post['slug']) ?>">
                                        <?= htmlspecialchars($post['title']) ?>
                                    </a>
                                </h3>
                                <p class="text-secondary">
                                    <?= htmlspecialchars(mb_substr($post['summary'] ?? '', 0, 120)) ?>...
                                </p>
                                <div class="d-flex align-items-center pt-3 mt-auto">
                                    <div>
                                        <div class="text-secondary">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path></svg>
                                            <?= htmlspecialchars($post['author_name'] ?? 'Admin') ?>
                                        </div>
                                        <div class="text-secondary mt-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="18" height="18" x="3" y="4" rx="2" ry="2"></rect><line x1="16" x2="16" y1="2" y2="6"></line><line x1="8" x2="8" y1="2" y2="6"></line><line x1="3" x2="21" y1="10" y2="10"></line></svg>
                                            <?= date('d/m/Y', strtotime($post['published_at'] ?? $post['created_at'])) ?>
                                        </div>
                                    </div>
                                    <div class="ms-auto">
                                        <a href="<?= BASE_URL ?>posts/show?slug=<?= urlencode($post['slug']) ?>" class="btn btn-primary">
                                            Đọc tiếp
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="card-footer d-flex align-items-center">
                    <p class="m-0 text-secondary">
                        Trang <?= $currentPage ?> / <?= $totalPages ?>
                    </p>
                    <ul class="pagination m-0 ms-auto">
                        <?php if ($currentPage > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?p=<?= $currentPage - 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>">
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
                                <a class="page-link" href="?p=<?= $i ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($currentPage < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?p=<?= $currentPage + 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>">
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
