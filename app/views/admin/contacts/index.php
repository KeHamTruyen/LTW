<?php
$contacts = $contacts ?? [];
$status = $status ?? '';
$search = $search ?? '';
$unreadCount = $unreadCount ?? 0;
$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;
?>
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Quản lý</div>
                <h2 class="page-title">Liên hệ</h2>
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

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="get" action="<?= BASE_URL ?>admin/contacts" class="row g-3">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..." value="<?= htmlspecialchars($search) ?>">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Tất cả trạng thái</option>
                            <option value="unread" <?= $status === 'unread' ? 'selected' : '' ?>>
                                Chưa đọc <?= $status === 'unread' || $status === '' ? '(' . $unreadCount . ')' : '' ?>
                            </option>
                            <option value="read" <?= $status === 'read' ? 'selected' : '' ?>>Đã đọc</option>
                            <option value="replied" <?= $status === 'replied' ? 'selected' : '' ?>>Đã phản hồi</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Tìm kiếm</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Contacts Table -->
        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th style="width: 60px; padding: 1rem 0.75rem;">ID</th>
                            <th style="padding: 1rem 0.75rem;">Người gửi</th>
                            <th style="padding: 1rem 0.75rem;">Chủ đề</th>
                            <th style="padding: 1rem 0.75rem;">Trạng thái</th>
                            <th style="padding: 1rem 0.75rem;">Ngày gửi</th>
                            <th style="width: 150px; padding: 1rem 0.75rem;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($contacts)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">Không có liên hệ nào</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($contacts as $contact): ?>
                                <tr class="<?= $contact['status'] === 'unread' ? 'table-active' : '' ?>">
                                    <td style="padding: 1rem 0.75rem; vertical-align: middle;"><?= $contact['id'] ?></td>
                                    <td style="padding: 1rem 0.75rem; vertical-align: middle;">
                                        <div class="font-weight-medium mb-1"><?= htmlspecialchars($contact['name']) ?></div>
                                        <div class="text-muted text-xs mb-1"><?= htmlspecialchars($contact['email']) ?></div>
                                        <?php if (!empty($contact['phone'])): ?>
                                            <div class="text-muted text-xs"><?= htmlspecialchars($contact['phone']) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td style="padding: 1rem 0.75rem; vertical-align: middle;">
                                        <div class="mb-1"><?= htmlspecialchars($contact['subject'] ?? 'Không có chủ đề') ?></div>
                                        <div class="text-muted text-xs">
                                            <?= htmlspecialchars(mb_substr($contact['message'], 0, 80)) ?><?= mb_strlen($contact['message']) > 80 ? '...' : '' ?>
                                        </div>
                                    </td>
                                    <td style="padding: 1rem 0.75rem; vertical-align: middle;">
                                        <?php
                                        $statusBadges = [
                                            'unread' => 'bg-yellow',
                                            'read' => 'bg-blue',
                                            'replied' => 'bg-green',
                                        ];
                                        $statusLabels = [
                                            'unread' => 'Chưa đọc',
                                            'read' => 'Đã đọc',
                                            'replied' => 'Đã phản hồi',
                                        ];
                                        ?>
                                        <span class="badge text-white <?= $statusBadges[$contact['status']] ?? 'bg-secondary' ?>">
                                            <?= $statusLabels[$contact['status']] ?? $contact['status'] ?>
                                        </span>
                                    </td>
                                    <td style="padding: 1rem 0.75rem; vertical-align: middle;">
                                        <div class="text-muted text-xs mb-1"><?= date('d/m/Y', strtotime($contact['created_at'])) ?></div>
                                        <div class="text-muted text-xs"><?= date('H:i', strtotime($contact['created_at'])) ?></div>
                                    </td>
                                    <td style="padding: 1rem 0.75rem; vertical-align: middle;">
                                        <div class="btn-list flex-nowrap">
                                            <a href="<?= BASE_URL ?>admin/contacts/show?id=<?= $contact['id'] ?>" 
                                            class="btn btn-outline-primary btn-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                                Chi tiết
                                            </a>

                                            <div class="dropdown">
                                                <button class="btn btn-white btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                                                    Tùy chọn
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end">
                                                    
                                                    <span class="dropdown-header">Trạng thái</span>
                                                    
                                                    <?php if ($contact['status'] !== 'unread'): ?>
                                                        <form method="post" action="<?= BASE_URL ?>admin/contacts/update-status" class="d-block">
                                                            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
                                                            <input type="hidden" name="id" value="<?= $contact['id'] ?>">
                                                            <input type="hidden" name="status" value="unread">
                                                            <button type="submit" class="dropdown-item">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-muted me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><rect x="3" y="5" width="18" height="14" rx="2" /><polyline points="3 7 12 13 21 7" /></svg>
                                                                Đánh dấu chưa đọc
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>

                                                    <?php if ($contact['status'] !== 'read'): ?>
                                                        <form method="post" action="<?= BASE_URL ?>admin/contacts/update-status" class="d-block">
                                                            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
                                                            <input type="hidden" name="id" value="<?= $contact['id'] ?>">
                                                            <input type="hidden" name="status" value="read">
                                                            <button type="submit" class="dropdown-item">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-blue me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l5 5l10 -10" /></svg>
                                                                Đánh dấu đã đọc
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>

                                                    <?php if ($contact['status'] !== 'replied'): ?>
                                                        <form method="post" action="<?= BASE_URL ?>admin/contacts/update-status" class="d-block">
                                                            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
                                                            <input type="hidden" name="id" value="<?= $contact['id'] ?>">
                                                            <input type="hidden" name="status" value="replied">
                                                            <button type="submit" class="dropdown-item">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon text-green me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 13l-4 -4l4 -4m-4 4h11a4 4 0 0 1 0 8h-1" /></svg>
                                                                Đánh dấu đã phản hồi
                                                            </button>
                                                        </form>
                                                    <?php endif; ?>

                                                    <div class="dropdown-divider"></div>

                                                    <form method="post" action="<?= BASE_URL ?>admin/contacts/delete" class="d-block" 
                                                        onsubmit="return confirm('Bạn có chắc muốn xóa liên hệ này? Hành động này không thể hoàn tác.');">
                                                        <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
                                                        <input type="hidden" name="id" value="<?= $contact['id'] ?>">
                                                        <button type="submit" class="dropdown-item text-danger">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>
                                                            Xóa liên hệ
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="card-footer d-flex align-items-center">
                    <p class="m-0 text-muted">
                        Hiển thị <span><?= count($contacts) ?></span> / <span><?= $total ?? 0 ?></span> liên hệ
                    </p>
                    <ul class="pagination m-0 ms-auto">
                        <?php if ($currentPage > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?p=<?= $currentPage - 1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>">Trước</a>
                            </li>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                                <a class="page-link" href="?p=<?= $i ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if ($currentPage < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?p=<?= $currentPage + 1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>">Sau</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

