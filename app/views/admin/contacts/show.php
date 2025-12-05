<?php
$contact = $contact ?? null;
if (!$contact) {
    echo '<div class="container-xl"><p>Liên hệ không tồn tại.</p></div>';
    return;
}
?>
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Chi tiết liên hệ</h2>
            </div>
            <div class="col-auto">
                <a href="<?= BASE_URL ?>admin/contacts" class="btn">Quay lại</a>
            </div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-lg-8">
                <!-- Contact Message -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Tin nhắn</h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($contact['subject'])): ?>
                            <div class="mb-3">
                                <label class="form-label">Chủ đề</label>
                                <div class="font-weight-semibold"><?= htmlspecialchars($contact['subject']) ?></div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label class="form-label">Nội dung</label>
                            <div class="text-muted" style="white-space: pre-wrap;"><?= htmlspecialchars($contact['message']) ?></div>
                        </div>
                        
                        <div class="text-muted text-xs">
                            Gửi lúc: <?= date('d/m/Y H:i', strtotime($contact['created_at'])) ?>
                            <?php if (!empty($contact['ip_address'])): ?>
                                | IP: <?= htmlspecialchars($contact['ip_address']) ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Reply Form -->
                <?php if ($contact['status'] !== 'replied'): ?>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Phản hồi</h3>
                        </div>
                        <div class="card-body">
                            <form method="post" action="<?= BASE_URL ?>admin/contacts/reply">
                                <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
                                <input type="hidden" name="id" value="<?= $contact['id'] ?>">
                                
                                <div class="mb-3">
                                    <label class="form-label">Nội dung phản hồi</label>
                                    <textarea name="reply_message" class="form-control" rows="5" required></textarea>
                                    <small class="form-hint">Nội dung phản hồi sẽ được lưu lại trong hệ thống</small>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Gửi phản hồi</button>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Phản hồi đã gửi</h3>
                        </div>
                        <div class="card-body">
                            <div class="mb-2">
                                <strong>Người phản hồi:</strong> <?= htmlspecialchars($contact['replied_by_name'] ?? 'Admin') ?>
                            </div>
                            <div class="mb-2">
                                <strong>Thời gian:</strong> <?= $contact['replied_at'] ? date('d/m/Y H:i', strtotime($contact['replied_at'])) : '-' ?>
                            </div>
                            <div>
                                <strong>Nội dung:</strong>
                                <div class="text-muted mt-2" style="white-space: pre-wrap;"><?= htmlspecialchars($contact['reply_message'] ?? '') ?></div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="col-lg-4">
                <!-- Contact Info -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Thông tin người gửi</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <strong>Họ tên:</strong><br>
                            <?= htmlspecialchars($contact['name']) ?>
                        </div>
                        <div class="mb-2">
                            <strong>Email:</strong><br>
                            <a href="mailto:<?= htmlspecialchars($contact['email']) ?>">
                                <?= htmlspecialchars($contact['email']) ?>
                            </a>
                        </div>
                        <?php if (!empty($contact['phone'])): ?>
                            <div class="mb-2">
                                <strong>SĐT:</strong><br>
                                <a href="tel:<?= htmlspecialchars($contact['phone']) ?>">
                                    <?= htmlspecialchars($contact['phone']) ?>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Status Actions -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Thao tác</h3>
                    </div>
                    <div class="card-body">
                        <form method="post" action="<?= BASE_URL ?>admin/contacts/update-status" class="mb-2">
                            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
                            <input type="hidden" name="id" value="<?= $contact['id'] ?>">
                            <label class="form-label">Cập nhật trạng thái</label>
                            <select name="status" class="form-select mb-2" onchange="this.form.submit()">
                                <option value="unread" <?= $contact['status'] === 'unread' ? 'selected' : '' ?>>Chưa đọc</option>
                                <option value="read" <?= $contact['status'] === 'read' ? 'selected' : '' ?>>Đã đọc</option>
                                <option value="replied" <?= $contact['status'] === 'replied' ? 'selected' : '' ?>>Đã phản hồi</option>
                            </select>
                        </form>
                        
                        <form method="post" action="<?= BASE_URL ?>admin/contacts/delete" 
                              onsubmit="return confirm('Bạn có chắc muốn xóa liên hệ này?');">
                            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
                            <input type="hidden" name="id" value="<?= $contact['id'] ?>">
                            <button type="submit" class="btn btn-danger w-100">Xóa liên hệ</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

