<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Quản lý đơn hàng</h2>
</div>

<form method="GET" class="mb-4">
    <div class="row">
        <div class="col-md-4">
            <select name="status" class="form-select">
                <option value="">Tất cả trạng thái</option>
                <option value="pending" <?php echo ($status ?? '') === 'pending' ? 'selected' : ''; ?>>Chờ xử lý</option>
                <option value="processing" <?php echo ($status ?? '') === 'processing' ? 'selected' : ''; ?>>Đang xử lý
                </option>
                <option value="shipped" <?php echo ($status ?? '') === 'shipped' ? 'selected' : ''; ?>>Đã giao</option>
                <option value="delivered" <?php echo ($status ?? '') === 'delivered' ? 'selected' : ''; ?>>Đã nhận
                </option>
                <option value="cancelled" <?php echo ($status ?? '') === 'cancelled' ? 'selected' : ''; ?>>Đã hủy</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-outline-primary">
                <i class="fas fa-filter me-1"></i>Lọc
            </button>
        </div>
    </div>
</form>

<?php if (empty($orders)): ?>
    <div class="text-center py-5">
        <i class="fas fa-receipt fa-4x text-muted mb-3"></i>
        <h4 class="text-muted">Không tìm thấy đơn hàng nào</h4>
        <p class="text-muted">Chưa có đơn hàng nào được đặt</p>
    </div>
<?php else: ?>
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày đặt</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orders as $order): ?>
                            <tr>
                                <td>#<?php echo $order['ID']; ?></td>
                                <td>
                                    <div class="fw-bold"><?php echo htmlspecialchars($order['user_name'] ?? 'Guest'); ?></div>
                                    <small
                                        class="text-muted"><?php echo htmlspecialchars($order['user_email'] ?? ''); ?></small>
                                </td>
                                <td class="text-danger fw-bold">
                                    <?php echo number_format($order['total_amount'], 0, ',', '.'); ?> đ
                                </td>
                                <td>
                                    <?php
                                    $statusBadge = [
                                        'pending' => ['warning', 'Chờ xử lý'],
                                        'processing' => ['info', 'Đang xử lý'],
                                        'shipped' => ['primary', 'Đã giao'],
                                        'delivered' => ['success', 'Đã nhận'],
                                        'cancelled' => ['danger', 'Đã hủy'],
                                    ];
                                    $s = $order['Status'];
                                    $color = $statusBadge[$s][0] ?? 'secondary';
                                    $label = $statusBadge[$s][1] ?? $s;
                                    ?>
                                    <span class="badge bg-<?= $color ?>"><?= $label ?></span>
                                </td>
                                <td><?php echo date('d/m/Y H:i', strtotime($order['Date'])); ?></td>
                                <td>
                                    <a href="<?php echo BASE_URL; ?>admin/orders/show?id=<?php echo $order['ID']; ?>"
                                        class="btn btn-sm btn-outline-primary me-1" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    <div class="dropdown d-inline">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown">
                                            <i class="fas fa-cog"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <h6 class="dropdown-header">Cập nhật trạng thái</h6>
                                            </li>

                                            <li>
                                                <form method="POST" action="<?= BASE_URL ?>admin/orders/update-status">
                                                    <input type="hidden" name="csrf" value="<?= \Core\Auth::csrfToken() ?>">
                                                    <input type="hidden" name="id" value="<?= $order['ID'] ?>">
                                                    <input type="hidden" name="status" value="processing">
                                                    <button type="submit" class="dropdown-item"
                                                        <?= $order['Status'] === 'processing' ? 'disabled' : '' ?>>
                                                        <i class="fas fa-spinner text-info me-2"></i>Đang xử lý
                                                    </button>
                                                </form>
                                            </li>

                                            <li>
                                                <form method="POST" action="<?= BASE_URL ?>admin/orders/update-status">
                                                    <input type="hidden" name="csrf" value="<?= \Core\Auth::csrfToken() ?>">
                                                    <input type="hidden" name="id" value="<?= $order['ID'] ?>">
                                                    <input type="hidden" name="status" value="shipped">
                                                    <button type="submit" class="dropdown-item" <?= $order['Status'] === 'shipped' ? 'disabled' : '' ?>>
                                                        <i class="fas fa-truck text-primary me-2"></i>Đang giao hàng
                                                    </button>
                                                </form>
                                            </li>

                                            <li>
                                                <form method="POST" action="<?= BASE_URL ?>admin/orders/update-status">
                                                    <input type="hidden" name="csrf" value="<?= \Core\Auth::csrfToken() ?>">
                                                    <input type="hidden" name="id" value="<?= $order['ID'] ?>">
                                                    <input type="hidden" name="status" value="delivered">
                                                    <button type="submit" class="dropdown-item"
                                                        <?= $order['Status'] === 'delivered' ? 'disabled' : '' ?>>
                                                        <i class="fas fa-check-circle text-success me-2"></i>Đã nhận hàng
                                                    </button>
                                                </form>
                                            </li>

                                            <li>
                                                <hr class="dropdown-divider">
                                            </li>

                                            <li>
                                                <form method="POST" action="<?= BASE_URL ?>admin/orders/update-status"
                                                    onsubmit="return confirm('Bạn chắc chắn muốn hủy đơn này?');">
                                                    <input type="hidden" name="csrf" value="<?= \Core\Auth::csrfToken() ?>">
                                                    <input type="hidden" name="id" value="<?= $order['ID'] ?>">
                                                    <input type="hidden" name="status" value="cancelled">
                                                    <button type="submit" class="dropdown-item text-danger"
                                                        <?= $order['Status'] === 'cancelled' ? 'disabled' : '' ?>>
                                                        <i class="fas fa-times-circle me-2"></i>Hủy đơn
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php if (isset($totalPages) && $totalPages > 1): ?>
        <nav aria-label="Order pagination" class="mt-4">
            <ul class="pagination justify-content-center">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?php echo $i === $currentPage ? 'active' : ''; ?>">
                        <a class="page-link"
                            href="?p=<?php echo $i; ?><?php echo !empty($status) ? '&status=' . urlencode($status) : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php endif; ?>
<?php endif; ?>