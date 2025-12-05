<?php
$user = $user ?? null;
if (!$user) {
    echo '<div class="container-xl"><p>Người dùng không tồn tại.</p></div>';
    return;
}
?>
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Chi tiết người dùng</h2>
            </div>
            <div class="col-auto">
                <a href="<?= BASE_URL ?>admin/users" class="btn">Quay lại</a>
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

        <div class="row">
            <div class="col-lg-8">
                <!-- User Info -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Thông tin người dùng</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Họ và tên</label>
                                    <div class="font-weight-semibold"><?= htmlspecialchars($user['name']) ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <div><?= htmlspecialchars($user['email']) ?></div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Số điện thoại</label>
                                    <div><?= htmlspecialchars($user['phone'] ?? 'Chưa cập nhật') ?></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Vai trò</label>
                                    <div>
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
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Trạng thái</label>
                                    <div>
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
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Ngày đăng ký</label>
                                    <div><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></div>
                                </div>
                            </div>
                        </div>
                        
                        <?php if (!empty($user['last_login'])): ?>
                            <div class="mb-3">
                                <label class="form-label">Lần đăng nhập cuối</label>
                                <div><?= date('d/m/Y H:i', strtotime($user['last_login'])) ?></div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4">
                <!-- Actions -->
                <div class="card mb-3">
                    <div class="card-header">
                        <h3 class="card-title">Thao tác</h3>
                    </div>
                    <div class="card-body">
                        <!-- Update Status -->
                        <form method="post" action="<?= BASE_URL ?>admin/users/update-status" class="mb-3">
                            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
                            <input type="hidden" name="id" value="<?= $user['id'] ?>">
                            <label class="form-label">Cập nhật trạng thái</label>
                            <select name="status" class="form-select mb-2" onchange="this.form.submit()">
                                <option value="active" <?= $user['status'] === 'active' ? 'selected' : '' ?>>Hoạt động</option>
                                <option value="inactive" <?= $user['status'] === 'inactive' ? 'selected' : '' ?>>Không hoạt động</option>
                                <option value="banned" <?= $user['status'] === 'banned' ? 'selected' : '' ?>>Khóa tài khoản</option>
                            </select>
                        </form>
                        
                        <!-- Reset Password -->
                        <form method="post" action="<?= BASE_URL ?>admin/users/reset-password" 
                              onsubmit="return confirm('Bạn có chắc muốn reset mật khẩu của người dùng này?');">
                            <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
                            <input type="hidden" name="id" value="<?= $user['id'] ?>">
                            <label class="form-label">Reset mật khẩu</label>
                            <div class="input-group mb-2">
                                <input type="password" name="new_password" class="form-control" 
                                       placeholder="Mật khẩu mới" 
                                       required 
                                       minlength="6">
                                <button type="submit" class="btn btn-warning">Reset</button>
                            </div>
                            <small class="form-hint">Mật khẩu mới tối thiểu 6 ký tự</small>
                        </form>
                        
                        <!-- Delete User -->
                        <?php if ($user['id'] != ($_SESSION['user_id'] ?? 0)): ?>
                            <form method="post" action="<?= BASE_URL ?>admin/users/delete" 
                                  onsubmit="return confirm('Bạn có chắc muốn xóa người dùng này? Hành động này không thể hoàn tác!');"
                                  class="mt-3">
                                <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                <button type="submit" class="btn btn-danger w-100">Xóa người dùng</button>
                            </form>
                        <?php else: ?>
                            <div class="alert alert-warning mt-3">
                                Bạn không thể xóa chính mình
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

