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
        <!-- Flash messages -->
        <?php if (isset($_SESSION['flash_success'])): ?>
            <div class="alert alert-success alert-dismissible">
                <?= htmlspecialchars($_SESSION['flash_success']) ?>
                <a class="btn-close" data-bs-dismiss="alert"></a>
            </div>
            <?php unset($_SESSION['flash_success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['flash_error'])): ?>
            <div class="alert alert-danger alert-dismissible">
                <?= htmlspecialchars($_SESSION['flash_error']) ?>
                <a class="btn-close" data-bs-dismiss="alert"></a>
            </div>
            <?php unset($_SESSION['flash_error']); ?>
        <?php endif; ?>
        
        <div class="row">
            <div class="col-lg-8">
                <!-- Contact Message -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Tin nhắn</h3>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($contact['subject'])): ?>
                            <div class="mb-4">
                                <label class="form-label">Chủ đề</label>
                                <div class="font-weight-semibold mt-1"><?= htmlspecialchars($contact['subject']) ?></div>
                            </div>
                        <?php endif; ?>
                        
                        <div class="mb-4">
                            <label class="form-label">Nội dung</label>
                            <div class="text-muted mt-2 p-3 bg-light rounded" style="white-space: pre-wrap; min-height: 100px;"><?= htmlspecialchars($contact['message']) ?></div>
                        </div>
                        
                        <div class="text-muted text-xs pt-2 border-top">
                            <div class="mb-1">Gửi lúc: <?= date('d/m/Y H:i', strtotime($contact['created_at'])) ?></div>
                            <?php if (!empty($contact['ip_address'])): ?>
                                <div>IP: <?= htmlspecialchars($contact['ip_address']) ?></div>
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
                                
                                <div class="mb-4">
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
                            <div class="mb-4">
                                <div class="form-label mb-1"><strong>Người phản hồi</strong></div>
                                <div><?= htmlspecialchars($contact['replied_by_name'] ?? 'Admin') ?></div>
                            </div>
                            <div class="mb-4">
                                <div class="form-label mb-1"><strong>Thời gian</strong></div>
                                <div><?= $contact['replied_at'] ? date('d/m/Y H:i', strtotime($contact['replied_at'])) : '-' ?></div>
                            </div>
                            <div>
                                <div class="form-label mb-2"><strong>Nội dung</strong></div>
                                <div class="text-muted p-3 bg-light rounded" style="white-space: pre-wrap; min-height: 80px;"><?= htmlspecialchars($contact['reply_message'] ?? '') ?></div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="col-lg-4">
                <!-- Contact Info -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Thông tin người gửi</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <div class="form-label mb-1"><strong>Họ tên</strong></div>
                            <div><?= htmlspecialchars($contact['name']) ?></div>
                        </div>
                        <div class="mb-4">
                            <div class="form-label mb-1"><strong>Email</strong></div>
                            <div>
                                <a href="mailto:<?= htmlspecialchars($contact['email']) ?>">
                                    <?= htmlspecialchars($contact['email']) ?>
                                </a>
                            </div>
                        </div>
                        <?php if (!empty($contact['phone'])): ?>
                            <div class="mb-4">
                                <div class="form-label mb-1"><strong>SĐT</strong></div>
                                <div>
                                    <a href="tel:<?= htmlspecialchars($contact['phone']) ?>">
                                        <?= htmlspecialchars($contact['phone']) ?>
                                    </a>
                                </div>
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
                        <form method="post" action="<?= BASE_URL ?>admin/contacts/update-status" class="mb-4">
                            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
                            <input type="hidden" name="id" value="<?= $contact['id'] ?>">
                            <label class="form-label">Cập nhật trạng thái</label>
                            <select name="status" class="form-select mb-3" onchange="this.form.submit()">
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

