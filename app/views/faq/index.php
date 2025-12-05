<?php
$faqs = $faqs ?? [];
$search = $search ?? '';
$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;
?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl md:text-4xl font-bold mb-8 text-center text-gray-800">Câu hỏi thường gặp</h1>
        
        <!-- Search Form -->
        <form method="get" action="<?= BASE_URL ?>faq" class="mb-8">
            <div class="flex gap-2">
                <input type="text" 
                       name="search" 
                       value="<?= htmlspecialchars($search) ?>" 
                       placeholder="Tìm kiếm câu hỏi..." 
                       class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                    Tìm kiếm
                </button>
            </div>
        </form>
        
        <!-- FAQ Accordion -->
        <?php if (!empty($faqs)): ?>
            <div class="space-y-4">
                <?php foreach ($faqs as $index => $faq): ?>
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <button class="w-full px-6 py-4 text-left flex justify-between items-center hover:bg-gray-50 transition focus:outline-none focus:ring-2 focus:ring-blue-500"
                                onclick="toggleFAQ(<?= $faq['id'] ?>)"
                                aria-expanded="false"
                                aria-controls="faq-<?= $faq['id'] ?>">
                            <span class="font-semibold text-gray-800"><?= htmlspecialchars($faq['question']) ?></span>
                            <svg class="w-5 h-5 text-gray-500 transform transition-transform" 
                                 id="icon-<?= $faq['id'] ?>" 
                                 fill="none" 
                                 stroke="currentColor" 
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div id="faq-<?= $faq['id'] ?>" 
                             class="hidden px-6 pb-4 text-gray-700">
                            <div class="prose max-w-none">
                                <?= nl2br(htmlspecialchars($faq['answer'])) ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="mt-8 flex justify-center gap-2">
                    <?php if ($currentPage > 1): ?>
                        <a href="<?= BASE_URL ?>faq?p=<?= $currentPage - 1 ?>&search=<?= urlencode($search) ?>" 
                           class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                            Trước
                        </a>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <?php if ($i == $currentPage): ?>
                            <span class="px-4 py-2 bg-blue-600 text-white rounded-lg"><?= $i ?></span>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>faq?p=<?= $i ?>&search=<?= urlencode($search) ?>" 
                               class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                                <?= $i ?>
                            </a>
                        <?php endif; ?>
                    <?php endfor; ?>
                    
                    <?php if ($currentPage < $totalPages): ?>
                        <a href="<?= BASE_URL ?>faq?p=<?= $currentPage + 1 ?>&search=<?= urlencode($search) ?>" 
                           class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
                            Sau
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="bg-white rounded-lg shadow-md p-8 text-center">
                <p class="text-gray-600">
                    <?= !empty($search) ? 'Không tìm thấy câu hỏi nào phù hợp.' : 'Chưa có câu hỏi nào.' ?>
                </p>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function toggleFAQ(id) {
    const content = document.getElementById('faq-' + id);
    const icon = document.getElementById('icon-' + id);
    const button = content.previousElementSibling;
    
    if (content.classList.contains('hidden')) {
        content.classList.remove('hidden');
        icon.classList.add('rotate-180');
        button.setAttribute('aria-expanded', 'true');
    } else {
        content.classList.add('hidden');
        icon.classList.remove('rotate-180');
        button.setAttribute('aria-expanded', 'false');
    }
}
</script>

