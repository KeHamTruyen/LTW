<?php
$orders = $orders ?? [];
$status = $status ?? '';
$search = $search ?? '';
$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;
?>
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <div class="page-pretitle">Quản lý</div>
                <h2 class="page-title">Đơn hàng</h2>
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
                <form method="get" action="<?= BASE_URL ?>admin/orders" class="row g-2">
                    <div class="col-md-4">
                        <input type="text" name="search" class="form-control" placeholder="Tìm kiếm theo mã đơn, tên, email..." value="<?= htmlspecialchars($search) ?>">
                    </div>
                    <div class="col-md-3">
                        <select name="status" class="form-select">
                            <option value="">Tất cả trạng thái</option>
                            <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>Chờ xử lý</option>
                            <option value="processing" <?= $status === 'processing' ? 'selected' : '' ?>>Đang xử lý</option>
                            <option value="shipped" <?= $status === 'shipped' ? 'selected' : '' ?>>Đã giao hàng</option>
                            <option value="delivered" <?= $status === 'delivered' ? 'selected' : '' ?>>Đã nhận hàng</option>
                            <option value="cancelled" <?= $status === 'cancelled' ? 'selected' : '' ?>>Đã hủy</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">Tìm kiếm</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="card">
            <div class="table-responsive">
                <table class="table table-vcenter card-table">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Thanh toán</th>
                            <th>Ngày đặt</th>
                            <th style="width: 100px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($orders)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">Không có đơn hàng nào</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>
                                        <div class="font-weight-medium">#<?= htmlspecialchars($order['order_number']) ?></div>
                                    </td>
                                    <td>
                                        <div><?= htmlspecialchars($order['customer_name']) ?></div>
                                        <div class="text-muted text-xs"><?= htmlspecialchars($order['customer_email']) ?></div>
                                    </td>
                                    <td>
                                        <span class="font-weight-semibold text-red">
                                            <?= number_format($order['total_amount'], 0, ',', '.') ?> đ
                                        </span>
                                    </td>
                                    <td>
                                        <?php
                                        $statusBadges = [
                                            'pending' => 'bg-yellow',
                                            'processing' => 'bg-blue',
                                            'shipped' => 'bg-purple',
                                            'delivered' => 'bg-green',
                                            'cancelled' => 'bg-red',
                                        ];
                                        $statusLabels = [
                                            'pending' => 'Chờ xử lý',
                                            'processing' => 'Đang xử lý',
                                            'shipped' => 'Đã giao hàng',
                                            'delivered' => 'Đã nhận hàng',
                                            'cancelled' => 'Đã hủy',
                                        ];
                                        ?>
                                        <span class="badge <?= $statusBadges[$order['status']] ?? 'bg-secondary' ?>">
                                            <?= $statusLabels[$order['status']] ?? $order['status'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php
                                        $paymentBadges = [
                                            'paid' => 'bg-green',
                                            'failed' => 'bg-red',
                                            'pending' => 'bg-yellow',
                                        ];
                                        $paymentLabels = [
                                            'paid' => 'Đã thanh toán',
                                            'failed' => 'Thất bại',
                                            'pending' => 'Chờ thanh toán',
                                        ];
                                        ?>
                                        <span class="badge <?= $paymentBadges[$order['payment_status']] ?? 'bg-secondary' ?>">
                                            <?= $paymentLabels[$order['payment_status']] ?? $order['payment_status'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="text-muted"><?= date('d/m/Y', strtotime($order['created_at'])) ?></div>
                                        <div class="text-xs text-muted"><?= date('H:i', strtotime($order['created_at'])) ?></div>
                                    </td>
                                    <td>
                                        <a href="<?= BASE_URL ?>admin/orders/show?id=<?= $order['id'] ?>" 
                                           class="btn btn-sm btn-icon" title="Xem chi tiết">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                                        </a>
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
                        Hiển thị <span><?= count($orders) ?></span> / <span><?= $total ?? 0 ?></span> đơn hàng
                    </p>
                    <ul class="pagination m-0 ms-auto">
                        <?php if ($currentPage > 1): ?>
                            <li class="page-item">
                                <a class="page-link" href="?p=<?= $currentPage - 1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>">
                                    Trước
                                </a>
                            </li>
                        <?php endif; ?>
                        
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                                <a class="page-link" href="?p=<?= $i ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                        
                        <?php if ($currentPage < $totalPages): ?>
                            <li class="page-item">
                                <a class="page-link" href="?p=<?= $currentPage + 1 ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>">
                                    Sau
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

