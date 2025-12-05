<?php
$about = $about ?? null;
?>
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

