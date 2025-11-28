<!-- MAIN CONTENT -->
<div class="container">
    <div class="content-wrapper">
        <div class="main-content">
            <!-- CONTROLS -->
            <div class="controls">
                <form method="GET" style="display: contents;">
                    <select name="sort" class="sort-btn" onchange="this.form.submit()">
                        <option value="latest" <?= ($sort ?? 'latest') === 'latest' ? 'selected' : '' ?>>Sort by latest</option>
                        <option value="oldest" <?= ($sort ?? '') === 'oldest' ? 'selected' : '' ?>>Sort by oldest</option>
                        <option value="popular" <?= ($sort ?? '') === 'popular' ? 'selected' : '' ?>>Most popular</option>
                    </select>
                </form>

                <form action="<?= BASE_URL ?>posts" method="GET" class="search-box">
                    <input type="text" name="search" placeholder="Search..." value="<?= htmlspecialchars($search ?? '') ?>">
                    <button type="submit" class="search-btn">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.36892 14.4449C12.1745 14.4449 14.4488 12.1705 14.4488 9.36502C14.4488 6.55949 12.1745 4.28516 9.36892 4.28516C6.56339 4.28516 4.28906 6.55949 4.28906 9.36502C4.28906 12.1705 6.56339 14.4449 9.36892 14.4449Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M15.7153 15.7149L12.9531 12.9527" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </form>
            </div>

            <!-- BLOG GRID -->
            <?php if (empty($posts)): ?>
                <div style="text-align: center; padding: 60px 20px;">
                    <h3 style="font-size: 24px; margin-bottom: 12px;">Không tìm thấy bài viết nào</h3>
                    <p style="color: var(--gray-600);">
                        <?php if (!empty($search)): ?>
                            Thử tìm kiếm với từ khóa khác
                        <?php else: ?>
                            Chưa có bài viết nào được đăng
                        <?php endif; ?>
                    </p>
                </div>
            <?php else: ?>
                <div class="blog-grid">
                    <?php foreach ($posts as $post): ?>
                        <div class="blog-card">
                            <?php if ($post['cover_image_url']): ?>
                                <img src="<?= BASE_URL ?>uploads/<?= htmlspecialchars($post['cover_image_url']) ?>" 
                                     class="blog-card-image" 
                                     alt="<?= htmlspecialchars($post['title']) ?>">
                            <?php else: ?>
                                <div class="blog-card-image"></div>
                            <?php endif; ?>
                            
                            <div class="blog-card-content">
                                <h3 class="blog-card-title"><?= htmlspecialchars($post['title']) ?></h3>
                                <p class="blog-card-excerpt">
                                    <?= htmlspecialchars(mb_substr(strip_tags($post['summary'] ?? $post['content_html']), 0, 100)) ?>...
                                </p>
                                <a href="<?= BASE_URL ?>posts/show?slug=<?= urlencode($post['slug']) ?>" class="blog-card-btn">Xem Thêm</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- PAGINATION -->
                <?php if ($totalPages > 1): ?>
                    <div class="pagination">
                        <div class="pagination-numbers">
                            <?php
                            $start = max(1, $currentPage - 2);
                            $end = min($totalPages, $currentPage + 2);
                            
                            for ($i = $start; $i <= $end; $i++):
                            ?>
                                <a href="?p=<?= $i ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($sort) ? '&sort=' . urlencode($sort) : '' ?>" 
                                   class="page-btn <?= $i === $currentPage ? 'active' : '' ?>">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>
                            
                            <?php if ($end < $totalPages): ?>
                                <span class="page-btn" style="cursor: default; border: none;">..</span>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($currentPage < $totalPages): ?>
                            <a href="?p=<?= $currentPage + 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($sort) ? '&sort=' . urlencode($sort) : '' ?>" class="next-btn">
                                <span>Next</span>
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9 18L15 12L9 6" stroke="#6C757D" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>

        <!-- SIDEBAR -->
        <aside class="sidebar">
            <div class="filter-section">
                <div class="filter-title">Danh mục</div>
                <div class="filter-list">
                    <div class="filter-item">
                        <label class="filter-checkbox-label">
                            <input type="checkbox" class="filter-checkbox">
                            <span class="filter-text">Chia sẻ kinh nghiệm</span>
                        </label>
                        <span class="count-badge">21</span>
                    </div>
                    <div class="filter-item">
                        <label class="filter-checkbox-label">
                            <input type="checkbox" class="filter-checkbox">
                            <span class="filter-text">Gióc giải trí</span>
                        </label>
                        <span class="count-badge">28</span>
                    </div>
                    <div class="filter-item">
                        <label class="filter-checkbox-label">
                            <input type="checkbox" class="filter-checkbox">
                            <span class="filter-text">Dịch vụ tại nhà</span>
                        </label>
                        <span class="count-badge">12</span>
                    </div>
                </div>
            </div>

            <div class="filter-section">
                <div class="filter-title">Bài viết mới</div>
                <div class="recent-posts">
                    <?php
                    // Get 4 latest posts for sidebar
                    $recentPosts = array_slice($posts, 0, 4);
                    foreach ($recentPosts as $recentPost):
                    ?>
                        <div class="recent-post">
                            <h4 class="recent-post-title">
                                <a href="<?= BASE_URL ?>posts/show?slug=<?= urlencode($recentPost['slug']) ?>" style="color: inherit; text-decoration: none;">
                                    <?= htmlspecialchars($recentPost['title']) ?>
                                </a>
                            </h4>
                            <p class="recent-post-date"><?= date('d/m/Y', strtotime($recentPost['published_at'] ?? $recentPost['created_at'])) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </aside>
    </div>
</div>
