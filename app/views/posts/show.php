<!-- Blog Hero Section -->
<section class="blog-hero">
    <div class="blog-detail-container">
        <h1 class="blog-hero-title"><?= htmlspecialchars($post['title']) ?></h1>
        <div class="blog-meta">
            <div class="blog-meta-item">
                <span>👤 <?= htmlspecialchars($post['author_name'] ?? 'Admin') ?></span>
            </div>
            <div class="blog-meta-item">
                <span>📅 <?= date('d/m/Y', strtotime($post['published_at'] ?? $post['created_at'])) ?></span>
            </div>
            <div class="blog-meta-item">
                <span>⏱️ <?= ceil(str_word_count(strip_tags($post['content_html'])) / 200) ?> phút đọc</span>
            </div>
            <div class="blog-meta-item">
                <span>💬 <?= $commentCount ?> bình luận</span>
            </div>
        </div>
        <div class="blog-hero-divider"></div>
    </div>
</section>

<!-- Blog Article -->
<div class="blog-detail-container">
    <article class="blog-article">
                <?php if ($post['cover_image_url']): ?>
                    <img src="<?= BASE_URL ?>uploads/<?= htmlspecialchars($post['cover_image_url']) ?>" 
                         alt="<?= htmlspecialchars($post['title']) ?>"
                         class="blog-article-image">
                <?php endif; ?>

                <div class="blog-article-content">
                    <?= $post['content_html'] ?>
                </div>

                <!-- Contact Box -->
                <div class="blog-contact-box">
                    <p class="blog-contact-title">Nếu bạn là người bận rộn, thường không có nhiều thời gian quan tâm đến vật nuôi thì hãy liên hệ ngay cho PET SERVICE – Dịch vụ thú cưng tại nhà thông qua:</p>
                    <div class="blog-contact-info">
                        <p><strong>Hotline:</strong> <a href="tel:0898520760">0898 520 760</a></p>
                        <p><strong>Address:</strong> 217 Lâm Văn Bền, Phường Bình Thuận, Quận 7</p>
                        <p><strong>Facebook:</strong> <a href="https://www.facebook.com/petserviceclub/">https://www.facebook.com/petserviceclub/</a></p>
                    </div>
                    <p class="blog-contact-tagline">PET SERVICE - TRỌN VẸN TRẢI NGHIỆM</p>
                    <p class="blog-contact-services">Những dịch vụ mà PET SERVICE đáp ứng: <a href="#">Dịch vụ thú y tại nhà</a>, <a href="#">Dịch vụ cắt tỉa lông tại nhà</a>, <a href="#">Dịch vụ tắm cho cún tại nhà</a>, <a href="#">Dịch vụ đặt cọc đi dạo...</a></p>
                </div>

                <!-- Social Share -->
                <div class="blog-social-share">
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(BASE_URL . 'posts/show?slug=' . $post['slug']) ?>" 
                       target="_blank" 
                       class="blog-social-facebook">
                        📘 Facebook
                    </a>
                    <a href="https://twitter.com/intent/tweet?url=<?= urlencode(BASE_URL . 'posts/show?slug=' . $post['slug']) ?>&text=<?= urlencode($post['title']) ?>" 
                       target="_blank" 
                       class="blog-social-twitter">
                        🐦 Twitter
                    </a>
                    <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= urlencode(BASE_URL . 'posts/show?slug=' . $post['slug']) ?>&title=<?= urlencode($post['title']) ?>" 
                       target="_blank" 
                       class="blog-social-linkedin">
                        💼 LinkedIn
                    </a>
                    <a href="https://pinterest.com/pin/create/button/?url=<?= urlencode(BASE_URL . 'posts/show?slug=' . $post['slug']) ?>&description=<?= urlencode($post['title']) ?>" 
                       target="_blank" 
                       class="blog-social-pinterest">
                        📌 Pinterest
                    </a>
                </div>

                <!-- Author Bio -->
                <div class="blog-author-bio">
                    <div class="blog-author-avatar">👤</div>
                    <div class="blog-author-info">
                        <h3 class="blog-author-name"><?= htmlspecialchars($post['author_name'] ?? 'Admin') ?></h3>
                        <p>Chuyên gia chăm sóc thú cưng với hơn 10 năm kinh nghiệm. Đam mê chia sẻ kiến thức và kinh nghiệm nuôi dưỡng, chăm sóc thú cưng khỏe mạnh và hạnh phúc.</p>
                    </div>
                </div>

                <!-- Post Navigation -->
                <?php if ($prevPost || $nextPost): ?>
                <div class="blog-post-nav">
                    <?php if ($prevPost): ?>
                        <a href="<?= BASE_URL ?>posts/show?slug=<?= $prevPost['slug'] ?>" class="blog-nav-link">
                            <div>
                                <div class="blog-nav-label">BÀI TRƯỚC</div>
                                <div class="blog-nav-text"><?= htmlspecialchars($prevPost['title']) ?></div>
                            </div>
                        </a>
                    <?php else: ?>
                        <div></div>
                    <?php endif; ?>

                    <?php if ($nextPost): ?>
                        <a href="<?= BASE_URL ?>posts/show?slug=<?= $nextPost['slug'] ?>" class="blog-nav-link blog-nav-link-next">
                            <div>
                                <div class="blog-nav-label">BÀI TIẾP</div>
                                <div class="blog-nav-text"><?= htmlspecialchars($nextPost['title']) ?></div>
                            </div>
                        </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </article>
</div>
