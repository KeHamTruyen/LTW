<?php
$faqs = $faqs ?? [];
$search = $search ?? '';
$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;
?>
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Quản lý</div>
                <h2 class="page-title">FAQ</h2>
            </div>
            <div class="col-auto ms-auto d-print-none">
                <a href="<?= BASE_URL ?>admin/faqs/create" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                    Thêm FAQ
                </a>
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

        <!-- Search -->
        <div class="card mb-3">
            <div class="card-body">
                <form method="get" action="<?= BASE_URL ?>admin/faqs" class="row g-2">
                    <div class="col-md-10">
                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm câu hỏi hoặc câu trả lời..." value="<?= htmlspecialchars($search) ?>">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Tìm kiếm</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- FAQs Table -->
        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th>Câu hỏi</th>
                            <th>Thứ tự</th>
                            <th>Trạng thái</th>
                            <th style="width: 150px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($faqs)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">Không có FAQ nào</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($faqs as $faq): ?>
                                <tr>
                                    <td><?= $faq['id'] ?></td>
                                    <td>
                                        <div class="font-weight-medium"><?= htmlspecialchars($faq['question']) ?></div>
                                        <div class="text-muted text-xs"><?= htmlspecialchars(mb_substr($faq['answer'], 0, 100)) ?><?= mb_strlen($faq['answer']) > 100 ? '...' : '' ?></div>
                                    </td>
                                    <td><?= $faq['display_order'] ?></td>
                                    <td>
                                        <span class="badge <?= $faq['status'] === 'active' ? 'bg-success' : 'bg-secondary' ?>">
                                            <?= $faq['status'] === 'active' ? 'Hiển thị' : 'Ẩn' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-list">
                                            <a href="<?= BASE_URL ?>admin/faqs/edit?id=<?= $faq['id'] ?>" 
                                               class="btn btn-sm btn-icon" title="Sửa">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>
                                            </a>
                                            <form method="post" action="<?= BASE_URL ?>admin/faqs/delete" class="d-inline" 
                                                  onsubmit="return confirm('Bạn có chắc muốn xóa FAQ này?');">
                                                <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
                                                <input type="hidden" name="id" value="<?= $faq['id'] ?>">
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
                        Hiển thị <span><?= count($faqs) ?></span> / <span><?= $total ?? 0 ?></span> FAQ
                    </p>
                    <ul class="pagination m-0 ms-auto">
                        <?php if ($currentPage > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?p=<?= $currentPage - 1 ?>&search=<?= urlencode($search) ?>">Trước</a>
                            </li>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                                <a class="page-link" href="?p=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if ($currentPage < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?p=<?= $currentPage + 1 ?>&search=<?= urlencode($search) ?>">Sau</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

