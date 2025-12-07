<section>
    <div class="max-w-6xl mx-auto px-4 py-16 grid gap-12 lg:grid-cols-2 items-center">
        <div class="space-y-6">
            <p class="text-[#f97316] font-semibold text-lg"><?= htmlspecialchars($homeData['hero_subtitle'] ?? 'Pet\'s Choice') ?></p>
            <h1 class="text-4xl md:text-5xl font-extrabold leading-tight text-[#1f2937]">
                <?= nl2br(htmlspecialchars($homeData['hero_title'] ?? "A pet store with\n everything you need")) ?>
            </h1>
            <button class="inline-flex items-center justify-center px-6 py-3 bg-[#111827] text-white rounded-full shadow-lg hover:bg-[#0b1220] transition" onclick="window.location='<?= BASE_URL ?>shop'">
                <?= htmlspecialchars($homeData['hero_button_text'] ?? 'Shop Now') ?>
            </button>
        </div>
        <div class="flex justify-center">
            <div class="relative">
                <img src="<?= htmlspecialchars($homeData['hero_image_url'] ?? BASE_URL . 'assets/images/Pet_home.png') ?>" alt="Happy pets" class="w-[360px] h-[360px] object-contain drop-shadow-lg" onerror="this.src='<?= BASE_URL ?>assets/images/Pet_home.png'">
            </div>
        </div>
    </div>
</section>

<section class="py-14 bg-white">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex flex-col items-center gap-2 text-center mb-10">
            <h2 class="text-2xl md:text-3xl font-extrabold text-[#111827]"><?= htmlspecialchars($homeData['service_title'] ?? 'Pet Service') ?></h2>
            <p class="text-2xl md:text-3xl font-extrabold text-[#111827]"><?= htmlspecialchars($homeData['service_subtitle'] ?? 'Price Combo') ?></p>
            <div class="w-24 h-1 bg-[#0ea5e9] rounded-full mt-2"></div>
        </div>
        <div class="grid gap-6 lg:grid-cols-3">
            <?php
            $combos = $serviceCombos ?? [];
            if (empty($combos)) {
                $combos = [
                    [
                        'name' => 'Combo 1',
                        'price' => '200.000 VNĐ',
                        'items' => [
                            ['name' => 'Tắm sấy', 'included' => true],
                            ['name' => 'Vệ sinh', 'included' => true],
                            ['name' => 'Cắt tỉa lông', 'included' => false],
                        ],
                    ],
                    [
                        'name' => 'Combo 2',
                        'price' => '350.000 VNĐ',
                        'items' => [
                            ['name' => 'Tắm sấy', 'included' => false],
                            ['name' => 'Vệ sinh', 'included' => true],
                            ['name' => 'Cắt tỉa lông', 'included' => true],
                        ],
                    ],
                    [
                        'name' => 'Combo 3',
                        'price' => '400.000 VNĐ',
                        'items' => [
                            ['name' => 'Tắm sấy', 'included' => true],
                            ['name' => 'Vệ sinh', 'included' => true],
                            ['name' => 'Cắt tỉa lông', 'included' => true],
                        ],
                    ],
                ];
            }
            foreach ($combos as $combo): 
                if (empty($combo['name'])) continue; 
            ?>
            <div class="bg-white border border-gray-200 rounded-2xl shadow hover:shadow-lg transition p-6 flex flex-col">
                <div class="text-center mb-6">
                    <p class="text-[#0ea5e9] font-semibold"><?= $combo['name'] ?></p>
                    <p class="text-3xl font-extrabold text-[#111827] mt-2"><?= $combo['price'] ?></p>
                    <p class="text-gray-500 text-sm mt-1">Giá sẽ thay đổi theo giống và trọng lượng</p>
                </div>
                <ul class="space-y-3 flex-1">
                    <?php foreach ($combo['items'] as $item): ?>
                    <li class="flex items-start gap-2 text-gray-700">
                        <?php if ($item['included']): ?>
                            <span class="mt-1 inline-flex h-5 w-5 items-center justify-center rounded-full bg-green-100 text-green-600">✓</span>
                        <?php else: ?>
                            <span class="mt-1 inline-flex h-5 w-5 items-center justify-center rounded-full bg-red-100 text-red-600">✗</span>
                        <?php endif; ?>
                        <span><?= $item['name'] ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
                <button class="mt-8 inline-flex items-center justify-center px-5 py-3 rounded-full bg-[#0ea5e9] text-white font-semibold hover:bg-[#0284c7] transition">
                    Đặt lịch ngay
                </button>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="bg-[#f8fbff] py-16">
    <div class="max-w-5xl mx-auto px-4 grid gap-10 md:grid-cols-2 items-center">
        <div class="flex justify-center">
            <img src="<?= htmlspecialchars($homeData['about_image_url'] ?? BASE_URL .'assets/images/Petcare.png') ?>" alt="Pet Care" class="w-72 h-72 object-contain" onerror="this.src='<?= BASE_URL ?>assets/images/4dogs.png'">
        </div>
        <div class="space-y-4">
            <p class="text-[#f97316] font-semibold"><?= htmlspecialchars($homeData['about_subtitle'] ?? 'Pet\'s Choice') ?></p>
            <h3 class="text-3xl font-extrabold text-[#111827] leading-tight"><?= htmlspecialchars($homeData['about_title'] ?? 'The smarter way to service for your pet') ?></h3>
            <p class="text-gray-600"><?= htmlspecialchars($homeData['about_description'] ?? 'Chăm sóc thú cưng tận tâm với đội ngũ chuyên nghiệp, quy trình khoa học và an toàn cho bé.') ?></p>
            <?php $aboutButtonText = $homeData['about_button_text'] ?? 'Xem thêm'; ?>
                <button class="inline-flex items-center justify-center px-6 py-3 bg-[#111827] text-white rounded-full shadow hover:bg-[#0b1220] transition" 
                onclick="window.location='<?= BASE_URL ?>posts'"><?= htmlspecialchars($aboutButtonText) ?></button>
            <?php ?>
        </div>
    </div>
</section>

<section class="py-14 bg-white">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-10">
            <p class="text-3xl font-semibold text-[#111827]"><?= htmlspecialchars($homeData['products_title'] ?? 'Sản Phẩm') ?></p>
            <h3 class="text-3xl font-semibold text-[#111827]"><?= htmlspecialchars($homeData['products_subtitle'] ?? 'Nổi Bật') ?></h3>
        </div>
        <?php if (!empty($featuredProducts)): ?>
            <div class="relative">
                <!-- Scroll left -->
                <button id="productsPrevBtn" class="absolute left-0 top-1/2 -translate-y-1/2 md:-translate-x-4 -translate-x-2 z-10 bg-white rounded-full p-2 md:p-3 shadow-lg hover:bg-gray-50 transition disabled:opacity-50 disabled:cursor-not-allowed" aria-label="Previous products">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-[#0ea5e9]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                
                <!-- Container carousel -->
                <div id="productsCarousel" class="overflow-hidden mx-8 md:mx-12">
                    <div id="productsCarouselContainer" class="flex gap-6 transition-transform duration-300 ease-in-out" style="transform: translateX(0px);">
                        <?php foreach ($featuredProducts as $product): ?>
                        <div class="flex-shrink-0 w-full md:w-1/2 lg:w-1/3">
                            <div class="bg-white border border-gray-200 rounded-2xl shadow hover:shadow-lg transition overflow-hidden flex flex-col">
                                <div class="aspect-[4/3] bg-[#e0f2fe] flex items-center justify-center">
                                    <?php if (!empty($product['image_url'])): ?>
                                        <img src="<?= htmlspecialchars($product['image_url']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" class="h-full w-full object-cover">
                                    <?php else: ?>
                                        <img src="<?= BASE_URL ?>assets/images/4dogs.png" alt="<?= htmlspecialchars($product['name']) ?>" class="h-full w-full object-cover">
                                    <?php endif; ?>
                                </div>
                                <div class="p-5 flex flex-col gap-3 flex-1">
                                    <p class="text-sm font-semibold text-[#111827]"><?= htmlspecialchars($product['name']) ?></p>
                                    <?php if (!empty($product['description'])): ?>
                                        <p class="text-sm text-gray-500 leading-relaxed line-clamp-2"><?= htmlspecialchars(mb_substr($product['description'], 0, 80)) ?><?= mb_strlen($product['description']) > 80 ? '...' : '' ?></p>
                                    <?php endif; ?>
                                    <div class="mt-auto">
                                        <?php 
                                        $displayPrice = !empty($product['sale_price']) && $product['sale_price'] < $product['price'] 
                                            ? $product['sale_price'] 
                                            : $product['price'];
                                        $formattedPrice = number_format($displayPrice, 0, ',', '.');
                                        ?>
                                        <p class="text-[#0ea5e9] font-bold"><?= $formattedPrice ?> VNĐ</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Scroll right -->
                <button id="productsNextBtn" class="absolute right-0 top-1/2 -translate-y-1/2 md:translate-x-4 translate-x-2 z-10 bg-white rounded-full p-2 md:p-3 shadow-lg hover:bg-gray-50 transition disabled:opacity-50 disabled:cursor-not-allowed" aria-label="Next products">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-[#0ea5e9]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
            <div class="text-center mt-8">
                <button class="inline-flex items-center justify-center px-6 py-3 bg-[#0ea5e9] text-white rounded-full shadow hover:bg-[#0284c7] transition" 
                onclick="window.location='<?= BASE_URL ?>shop'">
                    <?= htmlspecialchars($homeData['products_button_text'] ?? 'Xem Shop') ?>
                </button>
            </div>
        <?php else: ?>
            <p class="text-center text-gray-500">Chưa có sản phẩm nổi bật</p>
        <?php endif; ?>
    </div>
</section>

<section class="bg-[#f8fbff] py-14">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-10">
            <p class="text-3xl font-semibold text-[#111827]"><?= htmlspecialchars($homeData['posts_title'] ?? 'Bài Viết') ?></p>
            <h3 class="text-3xl font-semibold text-[#111827]"><?= htmlspecialchars($homeData['posts_subtitle'] ?? 'Mới Nhất') ?></h3>
        </div>
        <?php if (!empty($latestPosts)): ?>
            <div class="relative">
                <!-- Scroll left -->
                <button id="postsPrevBtn" class="absolute left-0 top-1/2 -translate-y-1/2 md:-translate-x-4 -translate-x-2 z-10 bg-white rounded-full p-2 md:p-3 shadow-lg hover:bg-gray-50 transition disabled:opacity-50 disabled:cursor-not-allowed" aria-label="Previous posts">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-[#0ea5e9]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                
                <!-- Container carousel -->
                <div id="postsCarousel" class="overflow-hidden mx-8 md:mx-12">
                    <div id="postsCarouselContainer" class="flex gap-6 transition-transform duration-300 ease-in-out" style="transform: translateX(0px);">
                        <?php foreach ($latestPosts as $post): ?>
                        <div class="flex-shrink-0 w-full md:w-1/2 lg:w-1/3">
                            <article class="bg-white border border-gray-200 rounded-2xl shadow hover:shadow-lg transition overflow-hidden flex flex-col">
                                <div class="aspect-[4/3] bg-[#e0f2fe]">
                                    <?php if (!empty($post['cover_image_url'])): ?>
                                        <img src="<?= htmlspecialchars($post['cover_image_url']) ?>" alt="<?= htmlspecialchars($post['title']) ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <img src="<?= BASE_URL ?>assets/images/4dogs.png" alt="<?= htmlspecialchars($post['title']) ?>" class="w-full h-full object-cover">
                                    <?php endif; ?>
                                </div>
                                <div class="p-5 flex flex-col gap-3 flex-1">
                                    <?php 
                                    $postDate = !empty($post['published_at']) ? $post['published_at'] : $post['created_at'];
                                    $formattedDate = date('d/m/Y', strtotime($postDate));
                                    ?>
                                    <p class="text-sm text-gray-500"><?= $formattedDate ?></p>
                                    <p class="text-base font-semibold text-[#111827] leading-snug line-clamp-2"><?= htmlspecialchars($post['title']) ?></p>
                                    <?php if (!empty($post['summary'])): ?>
                                        <p class="text-sm text-gray-600 line-clamp-2"><?= htmlspecialchars(mb_substr($post['summary'], 0, 100)) ?><?= mb_strlen($post['summary']) > 100 ? '...' : '' ?></p>
                                    <?php endif; ?>
                                    <a class="mt-auto inline-flex items-center text-[#0ea5e9] font-semibold hover:underline" href="<?= BASE_URL ?>posts/show?slug=<?= urlencode($post['slug']) ?>">
                                        Đọc thêm
                                    </a>
                                </div>
                            </article>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Scroll right -->
                <button id="postsNextBtn" class="absolute right-0 top-1/2 -translate-y-1/2 md:translate-x-4 translate-x-2 z-10 bg-white rounded-full p-2 md:p-3 shadow-lg hover:bg-gray-50 transition disabled:opacity-50 disabled:cursor-not-allowed" aria-label="Next posts">
                    <svg class="w-5 h-5 md:w-6 md:h-6 text-[#0ea5e9]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>
        <?php else: ?>
            <p class="text-center text-gray-500">Chưa có bài viết nào</p>
        <?php endif; ?>
    </div>
</section>

<script>
(function() {
    function initCarousel(carouselId, containerId, prevBtnId, nextBtnId) {
        const carousel = document.getElementById(carouselId);
        const prevBtn = document.getElementById(prevBtnId);
        const nextBtn = document.getElementById(nextBtnId);
        const container = document.getElementById(containerId);
        
        if (!carousel || !prevBtn || !nextBtn || !container) return;
        
        let currentIndex = 0;
        const items = container.querySelectorAll('.flex-shrink-0');
        const totalItems = items.length;
        
        if (totalItems === 0) return;
        
        function getVisibleItems() {
            if (window.innerWidth >= 1024) return 3; 
            if (window.innerWidth >= 768) return 2;  
            return 1; 
        }
        
        function updateCarousel() {
            const visibleItems = getVisibleItems();
            const maxIndex = Math.max(0, totalItems - visibleItems);
            prevBtn.disabled = currentIndex === 0;
            if (totalItems <= visibleItems) {
                nextBtn.disabled = true;
                container.style.transform = `translateX(0px)`;
                return;
            }
            nextBtn.disabled = currentIndex >= maxIndex;
            if (items[0] && totalItems > visibleItems) {
                const rect = items[0].getBoundingClientRect();
                const itemWidth = rect.width;
                const gap = 24; 
                const translateX = -currentIndex * visibleItems * (itemWidth + gap);
                container.style.transform = `translateX(${translateX}px)`;
            }
        }
        
        prevBtn.addEventListener('click', () => {
            if (currentIndex > 0) {
                currentIndex--;
                updateCarousel();
            }
        });
        
        nextBtn.addEventListener('click', () => {
            const visibleItems = getVisibleItems();
            const maxIndex = Math.max(0, totalItems - visibleItems);
            if (currentIndex < maxIndex) {
                currentIndex++;
                updateCarousel();
            }
        });
        
        // Update on resize
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                const visibleItems = getVisibleItems();
                const maxIndex = Math.max(0, totalItems - visibleItems);
                if (currentIndex > maxIndex) {
                    currentIndex = maxIndex;
                }
                updateCarousel();
            }, 250);
        });
        
        function initUpdate() {
            const update = () => {
                requestAnimationFrame(() => {
                    updateCarousel();
                });
            };
        
            update();
            setTimeout(update, 50);
            const images = container.querySelectorAll('img');
            let loadedCount = 0;
            if (images.length > 0) {
                images.forEach(img => {
                    if (img.complete) {
                        loadedCount++;
                    } else {
                        img.addEventListener('load', () => {
                            loadedCount++;
                            if (loadedCount === images.length) {
                                setTimeout(update, 50);
                            }
                        }, { once: true });
                    }
                });
                if (loadedCount === images.length) {
                    setTimeout(update, 50);
                }
            }
            
            // Update khi window load hoàn tất
            if (document.readyState === 'complete') {
                setTimeout(update, 100);
            } else {
                window.addEventListener('load', () => {
                    setTimeout(update, 100);
                }, { once: true });
            }
        }
        
        initUpdate();
    }
    
    // Khởi tạo carousels khi DOM ready
    function initCarousels() {
        initCarousel('productsCarousel', 'productsCarouselContainer', 'productsPrevBtn', 'productsNextBtn');
        initCarousel('postsCarousel', 'postsCarouselContainer', 'postsPrevBtn', 'postsNextBtn');
    }
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCarousels);
    } else {
        initCarousels();
    }
})();
</script>
