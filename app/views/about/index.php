<?php
$about = $about ?? null;
?>
<section class="py-16">
    <div class="max-w-6xl mx-auto px-4 grid gap-12 lg:grid-cols-2 items-center">
        <div class="space-y-6">
            <p class="text-[#f97316] font-semibold text-lg">Pet's Choice</p>
            <h1 class="text-4xl md:text-5xl font-extrabold leading-tight text-[#1f2937]">
                If animals could talk,<br>they'd talk about us!
            </h1>
            <p class="text-gray-600 text-lg leading-relaxed">
                At et vehicula sodales est proin turpis pellentesque sinulla a aliquam amet rhoncus quisque eget sit
            </p>
            <button class="inline-flex items-center justify-center px-6 py-3 bg-[#111827] text-white rounded-full shadow-lg hover:bg-[#0b1220] transition" onclick="window.location='<?= BASE_URL ?>shop'">
                Shop Now
            </button>
        </div>
        <div class="flex justify-center relative">
            <div class="relative w-full max-w-md">
                <div class="relative flex justify-around items-end h-64 z-10">
                    <img src="<?= BASE_URL ?>assets/images/4dogs.png" alt="Happy pets" class="m-top-100 pointer-events-none object-contain drop-shadow-lg" onerror="this.style.display='none'">
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <?php if ($about): ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <?php if (!empty($about['image'])): ?>
                    <div class="w-full h-64 md:h-96 overflow-hidden">
                        <img src="<?= htmlspecialchars($about['image']) ?>" 
                             alt="<?= htmlspecialchars($about['title']) ?>" 
                             class="w-full h-full object-cover lazy-load">
                    </div>
                <?php endif; ?>
                
                <div class="p-6 md:p-8">
                    <h1 class="text-3xl md:text-4xl font-bold mb-6 text-gray-800">
                        <?= htmlspecialchars($about['title'] ?? 'Về chúng tôi') ?>
                    </h1>
                    
                    <?php if (!empty($about['description'])): ?>
                        <div class="prose max-w-none mb-8 text-gray-700">
                            <?= nl2br(htmlspecialchars($about['description'])) ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($about['mission'])): ?>
                        <div class="mb-8 p-6 bg-blue-50 rounded-lg">
                            <h2 class="text-2xl font-semibold mb-4 text-blue-800">Sứ mệnh</h2>
                            <p class="text-gray-700 leading-relaxed">
                                <?= nl2br(htmlspecialchars($about['mission'])) ?>
                            </p>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($about['vision'])): ?>
                        <div class="p-6 bg-green-50 rounded-lg">
                            <h2 class="text-2xl font-semibold mb-4 text-green-800">Tầm nhìn</h2>
                            <p class="text-gray-700 leading-relaxed">
                                <?= nl2br(htmlspecialchars($about['vision'])) ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <p class="text-gray-600">Nội dung đang được cập nhật...</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Lazy loading images
if ('loading' in HTMLImageElement.prototype) {
    const images = document.querySelectorAll('img.lazy-load');
    images.forEach(img => {
        img.loading = 'lazy';
    });
} else {
    // Fallback for browsers that don't support lazy loading
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/lazysizes@5/lazysizes.min.js';
    document.body.appendChild(script);
}
</script>

