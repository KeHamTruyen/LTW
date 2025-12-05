<?php
$users = $users ?? [];
$search = $search ?? '';
$role = $role ?? '';
$status = $status ?? '';
$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;
?>
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Quản lý</div>
                <h2 class="page-title">Người dùng</h2>
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
                <form method="get" action="<?= BASE_URL ?>admin/users" class="row g-2">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..." value="<?= htmlspecialchars($search) ?>">
                    </div>
                    <div class="col-md-3">
                        <select name="role" class="form-select">
                            <option value="">Tất cả vai trò</option>
                            <option value="customer" <?= $role === 'customer' ? 'selected' : '' ?>>Khách hàng</option>
                            <option value="staff" <?= $role === 'staff' ? 'selected' : '' ?>>Nhân viên</option>
                            <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>Quản trị viên</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Tất cả trạng thái</option>
                            <option value="active" <?= $status === 'active' ? 'selected' : '' ?>>Hoạt động</option>
                            <option value="inactive" <?= $status === 'inactive' ? 'selected' : '' ?>>Không hoạt động</option>
                            <option value="banned" <?= $status === 'banned' ? 'selected' : '' ?>>Đã khóa</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">Tìm kiếm</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Users Table -->
        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th style="width: 60px;">ID</th>
                            <th>Người dùng</th>
                            <th>Email</th>
                            <th>Vai trò</th>
                            <th>Trạng thái</th>
                            <th>Ngày đăng ký</th>
                            <th style="width: 150px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Không có người dùng nào</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= $user['id'] ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="avatar me-2" style="background-image: url(<?= htmlspecialchars($user['avatar_url'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($user['name']) . '&background=206bc4&color=fff') ?>)"></span>
                                            <div>
                                                <div class="font-weight-medium"><?= htmlspecialchars($user['name']) ?></div>
                                                <?php if (!empty($user['phone'])): ?>
                                                    <div class="text-muted text-xs"><?= htmlspecialchars($user['phone']) ?></div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div><?= htmlspecialchars($user['email']) ?></div>
                                    </td>
                                    <td>
                                        <?php
                                        $roleBadges = [
                                            'admin' => 'bg-purple',
                                            'staff' => 'bg-blue',
                                            'customer' => 'bg-gray',
                                        ];
                                        $roleLabels = [
                                            'admin' => 'Quản trị viên',
                                            'staff' => 'Nhân viên',
                                            'customer' => 'Khách hàng',
                                        ];
                                        ?>
                                        <span class="badge <?= $roleBadges[$user['role']] ?? 'bg-secondary' ?>">
                                            <?= $roleLabels[$user['role']] ?? $user['role'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php
                                        $statusBadges = [
                                            'active' => 'bg-green',
                                            'inactive' => 'bg-yellow',
                                            'banned' => 'bg-red',
                                        ];
                                        $statusLabels = [
                                            'active' => 'Hoạt động',
                                            'inactive' => 'Không hoạt động',
                                            'banned' => 'Đã khóa',
                                        ];
                                        ?>
                                        <span class="badge <?= $statusBadges[$user['status']] ?? 'bg-secondary' ?>">
                                            <?= $statusLabels[$user['status']] ?? $user['status'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-muted text-xs"><?= date('d/m/Y', strtotime($user['created_at'])) ?></div>
                                    </td>
                                    <td>
                                        <div class="btn-list">
                                            <a href="<?= BASE_URL ?>admin/users/show?id=<?= $user['id'] ?>" 
                                               class="btn btn-sm btn-icon" title="Xem chi tiết">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                            </a>
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
                        Hiển thị <span><?= count($users) ?></span> / <span><?= $total ?? 0 ?></span> người dùng
                    </p>
                    <ul class="pagination m-0 ms-auto">
                        <?php if ($currentPage > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?p=<?= $currentPage - 1 ?>&search=<?= urlencode($search) ?>&role=<?= urlencode($role) ?>&status=<?= urlencode($status) ?>">Trước</a>
                            </li>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                                <a class="page-link" href="?p=<?= $i ?>&search=<?= urlencode($search) ?>&role=<?= urlencode($role) ?>&status=<?= urlencode($status) ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if ($currentPage < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?p=<?= $currentPage + 1 ?>&search=<?= urlencode($search) ?>&role=<?= urlencode($role) ?>&status=<?= urlencode($status) ?>">Sau</a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

