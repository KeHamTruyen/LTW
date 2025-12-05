<?php
$page = $page ?? null;
if (!$page) {
    http_response_code(404);
    echo '<div class="container mx-auto px-4 py-8"><p>Trang không tồn tại.</p></div>';
    return;
}
?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6 md:p-8">
                <h1 class="text-3xl md:text-4xl font-bold mb-6 text-gray-800">
                    <?= htmlspecialchars($page['title']) ?>
                </h1>
                
                <?php if (!empty($page['content_html'])): ?>
                    <div class="prose max-w-none text-gray-700">
                        <?= $page['content_html'] ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-600">Nội dung đang được cập nhật...</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

