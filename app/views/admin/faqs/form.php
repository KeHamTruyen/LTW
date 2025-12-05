<?php
$faq = $faq ?? null;
$isEdit = $faq !== null;
?>
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title"><?= $isEdit ? 'Chỉnh sửa FAQ' : 'Thêm FAQ mới' ?></h2>
            </div>
            <div class="col-auto">
                <a href="<?= BASE_URL ?>admin/faqs" class="btn">Quay lại</a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-lg-8">
                <form method="post" action="<?= BASE_URL ?>admin/faqs/<?= $isEdit ? 'update' : 'store' ?>" id="faqForm">
                    <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
                    <?php if ($isEdit): ?>
                        <input type="hidden" name="id" value="<?= $faq['id'] ?>">
                    <?php endif; ?>

                    <div class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label required">Câu hỏi</label>
                                <input type="text" name="question" class="form-control" 
                                       value="<?= htmlspecialchars($faq['question'] ?? '') ?>" 
                                       required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label required">Câu trả lời</label>
                                <textarea name="answer" class="form-control" rows="6" 
                                          required><?= htmlspecialchars($faq['answer'] ?? '') ?></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Thứ tự hiển thị</label>
                                        <input type="number" name="display_order" class="form-control" 
                                               value="<?= $faq['display_order'] ?? 0 ?>" 
                                               min="0">
                                        <small class="form-hint">Số nhỏ hơn sẽ hiển thị trước</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label">Trạng thái</label>
                                        <select name="status" class="form-select">
                                            <option value="active" <?= ($faq['status'] ?? 'active') === 'active' ? 'selected' : '' ?>>Hiển thị</option>
                                            <option value="inactive" <?= ($faq['status'] ?? '') === 'inactive' ? 'selected' : '' ?>>Ẩn</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Cập nhật' : 'Thêm mới' ?></button>
                                <a href="<?= BASE_URL ?>admin/faqs" class="btn">Hủy</a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Form validation
document.getElementById('faqForm')?.addEventListener('submit', function(e) {
    const question = this.querySelector('[name="question"]').value.trim();
    const answer = this.querySelector('[name="answer"]').value.trim();
    
    if (question.length < 3) {
        e.preventDefault();
        alert('Câu hỏi phải có ít nhất 3 ký tự');
        return false;
    }
    
    if (answer.length < 5) {
        e.preventDefault();
        alert('Câu trả lời phải có ít nhất 5 ký tự');
        return false;
    }
});
</script>

