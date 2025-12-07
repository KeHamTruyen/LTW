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

            <!-- Comments Section -->
            <div class="mb-12 pt-8 border-t border-gray-300">
                <h2 class="text-3xl font-bold text-black mb-8">Bình Luận (<?= $commentCount ?>)</h2>

                <!-- Comment Form -->
                <div class="bg-gray-50 p-8 rounded-lg border border-gray-200 mb-12">
                    <h3 class="text-2xl font-bold text-black mb-6">Để lại bình luận</h3>

                    <?php if (isset($_SESSION['flash_success'])): ?>
                        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-4">
                            <?= $_SESSION['flash_success'] ?>
                        </div>
                        <?php unset($_SESSION['flash_success']); ?>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['flash_error'])): ?>
                        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-4">
                            <?= $_SESSION['flash_error'] ?>
                        </div>
                        <?php unset($_SESSION['flash_error']); ?>
                    <?php endif; ?>

                    <form action="<?= BASE_URL ?>posts/comment" method="POST" class="space-y-6">
                        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                        <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
                        
                        <?php if (!isset($_SESSION['user_id'])): ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Author Name Input -->
                            <div>
                                <label for="author_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Tên của bạn <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="text"
                                    id="author_name"
                                    name="author_name"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-colors"
                                    placeholder="Nhập tên của bạn"
                                    required
                                />
                            </div>

                            <!-- Email Input -->
                            <div>
                                <label for="author_email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email của bạn <span class="text-red-500">*</span>
                                </label>
                                <input
                                    type="email"
                                    id="author_email"
                                    name="author_email"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-colors"
                                    placeholder="Nhập email của bạn"
                                    required
                                />
                            </div>
                        </div>
                        <?php else: ?>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                            <p class="text-sm text-gray-700">
                                Đăng nhập với tài khoản: <strong><?= htmlspecialchars($_SESSION['user_name'] ?? $_SESSION['user_email']) ?></strong>
                                <span class="text-gray-500">(<?= htmlspecialchars($_SESSION['user_email']) ?>)</span>
                            </p>
                        </div>
                        <?php endif; ?>

                        <!-- Comment Textarea -->
                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                                Bình luận của bạn <span class="text-red-500">*</span>
                            </label>
                            <textarea
                                id="content"
                                name="content"
                                rows="5"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none transition-colors resize-none"
                                placeholder="Nhập bình luận của bạn"
                                required
                            ></textarea>
                            <p class="text-xs text-gray-500 mt-2">Bình luận của bạn sẽ được đăng tải sau khi xét duyệt.</p>
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            Gửi bình luận
                        </button>
                    </form>
                </div>

                <!-- Comments List -->
                <?php if (!empty($comments)): ?>
                    <div>
                        <h3 class="text-2xl font-bold text-black mb-8">Các bình luận gần đây</h3>

                        <?php foreach ($comments as $index => $comment): ?>
                            <div class="mb-8 pb-8 <?= $index < count($comments) - 1 ? 'border-b border-gray-200' : '' ?>">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-lg font-semibold text-white">
                                            <?= strtoupper(mb_substr($comment['author_name'], 0, 1)) ?>
                                        </span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="font-bold text-black text-lg"><?= htmlspecialchars($comment['author_name']) ?></h4>
                                            <span class="text-sm text-gray-500"><?= date('d \t\h\á\n\g n, Y', strtotime($comment['created_at'])) ?></span>
                                        </div>
                                        
                                        <p class="text-gray-700 leading-relaxed"><?= nl2br(htmlspecialchars($comment['content'])) ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-12">
                        <p class="text-gray-500 text-lg">Chưa có bình luận nào. Hãy là người đầu tiên bình luận!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
</div>

