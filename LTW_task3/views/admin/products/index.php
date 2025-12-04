<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Quản lý sản phẩm</h1>
        <a href="<?= BASE_URL ?>admin/products/create" class="btn btn-primary">
            <i class="fas fa-plus me-1"></i> Thêm sản phẩm mới
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="<?= BASE_URL ?>admin/products" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Tìm kiếm sản phẩm..."
                    value="<?= htmlspecialchars($search ?? '') ?>">
                <button type="submit" class="btn btn-outline-primary">Tìm</button>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách sản phẩm</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 50px;">ID</th>
                            <th style="width: 80px;">Ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th>Giá</th>
                            <th>Tồn kho</th>
                            <th style="width: 120px;">Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($products)): ?>
                            <tr>
                                <td colspan="6" class="text-center">Không có dữ liệu</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><?= $product['ID'] ?></td>
                                    <td>
                                        <?php if (!empty($product['image'])):
                                            $imgUrl = strpos($product['image'], 'images/') === 0 ? BASE_URL . 'public/' . $product['image'] : BASE_URL . 'public/uploads/' . $product['image'];
                                            ?>
                                            <img src="<?= $imgUrl ?>" style="width: 50px; height: 50px; object-fit: cover;"
                                                class="rounded border">
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($product['name']) ?></td>
                                    <td><?= number_format($product['price'], 0, ',', '.') ?> đ</td>
                                    <td>
                                        <span class="badge bg-<?= $product['stock_quantity'] > 0 ? 'success' : 'danger' ?>">
                                            <?= $product['stock_quantity'] ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?= BASE_URL ?>admin/products/edit?id=<?= $product['ID'] ?>"
                                            class="btn btn-sm btn-info text-white" title="Sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <form action="<?= BASE_URL ?>admin/products/delete" method="POST" class="d-inline"
                                            onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');">
                                            <input type="hidden" name="csrf" value="<?= \Core\Auth::csrfToken() ?>">

                                            <input type="hidden" name="id" value="<?= $product['ID'] ?>">
                                            <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if (isset($totalPages) && $totalPages > 1): ?>
                <nav class="mt-3">
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= ($i == $currentPage) ? 'active' : '' ?>">
                                <a class="page-link"
                                    href="?p=<?= $i ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        </div>
    </div>
</div>