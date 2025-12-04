<div class="container mt-4">
    <h2 class="mb-4">Giỏ hàng của bạn</h2>

    <?php if (empty($cartItems)): ?>
        <div class="alert alert-info">Giỏ hàng trống. <a href="<?= BASE_URL ?>products">Mua sắm ngay</a></div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Sản phẩm</th>
                        <th>Giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $total = 0;
                    foreach ($cartItems as $item):
                        // [FIX LỖI] Lấy số lượng bất kể hoa/thường
                        $qty = $item['Quantity'] ?? $item['quantity'] ?? 0;
                        $price = $item['price'];
                        $subtotal = $qty * $price;
                        $total += $subtotal;
                        ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <?php if (!empty($item['image'])): ?>
                                        <img src="<?= BASE_URL . (strpos($item['image'], 'images/') === 0 ? 'public/' : 'public/uploads/') . $item['image'] ?>"
                                            style="width: 50px; height: 50px; object-fit: cover;" class="me-2">
                                    <?php endif; ?>
                                    <strong><?= htmlspecialchars($item['name']) ?></strong>
                                </div>
                            </td>
                            <td><?= number_format($price, 0, ',', '.') ?> đ</td>
                            <td style="width: 150px;">
                                <form action="<?= BASE_URL ?>cart/update" method="POST" class="d-flex">
                                    <input type="hidden" name="csrf" value="<?= \Core\Auth::csrfToken() ?>">
                                    <input type="hidden" name="product_id"
                                        value="<?= $item['PRODUCT_id'] ?? $item['product_id'] ?>">
                                    <input type="number" name="quantity" value="<?= $qty ?>" min="1"
                                        class="form-control form-control-sm me-2">
                                    <button type="submit" class="btn btn-sm btn-secondary"><i
                                            class="fa-solid fa-sync"></i></button>
                                </form>
                            </td>
                            <td><?= number_format($subtotal, 0, ',', '.') ?> đ</td>
                            <td>
                                <form action="<?= BASE_URL ?>cart/update" method="POST"
                                    onsubmit="return confirm('Xóa sản phẩm này?');">
                                    <input type="hidden" name="csrf" value="<?= \Core\Auth::csrfToken() ?>">
                                    <input type="hidden" name="product_id"
                                        value="<?= $item['PRODUCT_id'] ?? $item['product_id'] ?>">
                                    <input type="hidden" name="quantity" value="0"> <button type="submit"
                                        class="btn btn-sm btn-danger">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end fw-bold">Tổng cộng:</td>
                        <td colspan="2" class="fw-bold text-danger"><?= number_format($total, 0, ',', '.') ?> đ</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="d-flex justify-content-end gap-2 mt-3">
            <a href="<?= BASE_URL ?>products" class="btn btn-secondary">Tiếp tục mua sắm</a>
            <form action="<?= BASE_URL ?>cart/checkout" method="POST">
                <input type="hidden" name="csrf" value="<?= \Core\Auth::csrfToken() ?>">
                <button type="submit" class="btn btn-success">Đặt hàng</button>
            </form>
        </div>
    <?php endif; ?>
</div>