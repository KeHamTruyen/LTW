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
        <div class="card mb-3">
            <div class="card-body">
                <form method="get" action="<?= BASE_URL ?>admin/contacts" class="row g-2">
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
                            <th style="width: 60px;">ID</th>
                            <th>Người gửi</th>
                            <th>Chủ đề</th>
                            <th>Trạng thái</th>
                            <th>Ngày gửi</th>
                            <th style="width: 150px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($contacts)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">Không có liên hệ nào</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($contacts as $contact): ?>
                                <tr class="<?= $contact['status'] === 'unread' ? 'table-active' : '' ?>">
                                    <td><?= $contact['id'] ?></td>
                                    <td>
                                        <div class="font-weight-medium"><?= htmlspecialchars($contact['name']) ?></div>
                                        <div class="text-muted text-xs"><?= htmlspecialchars($contact['email']) ?></div>
                                        <?php if (!empty($contact['phone'])): ?>
                                            <div class="text-muted text-xs"><?= htmlspecialchars($contact['phone']) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div><?= htmlspecialchars($contact['subject'] ?? 'Không có chủ đề') ?></div>
                                        <div class="text-muted text-xs">
                                            <?= htmlspecialchars(mb_substr($contact['message'], 0, 80)) ?><?= mb_strlen($contact['message']) > 80 ? '...' : '' ?>
                                        </div>
                                    </td>
                                    <td>
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
                                        <span class="badge <?= $statusBadges[$contact['status']] ?? 'bg-secondary' ?>">
                                            <?= $statusLabels[$contact['status']] ?? $contact['status'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-muted text-xs"><?= date('d/m/Y', strtotime($contact['created_at'])) ?></div>
                                        <div class="text-muted text-xs"><?= date('H:i', strtotime($contact['created_at'])) ?></div>
                                    </td>
                                    <td>
                                        <div class="btn-list">
                                            <a href="<?= BASE_URL ?>admin/contacts/show?id=<?= $contact['id'] ?>" 
                                               class="btn btn-sm btn-icon" title="Xem chi tiết">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                            </a>
                                            <form method="post" action="<?= BASE_URL ?>admin/contacts/delete" class="d-inline" 
                                                  onsubmit="return confirm('Bạn có chắc muốn xóa liên hệ này?');">
                                                <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
                                                <input type="hidden" name="id" value="<?= $contact['id'] ?>">
                                                <button type="submit" class="btn btn-sm btn-icon" title="Xóa">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon text-red" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 3v3" /></svg>
                                                </button>
                                            </form>
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

