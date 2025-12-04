<div class="container mt-5">
    <div class="row">
        <div class="col-12 text-center">
            <h1 class="display-4 mb-4"><?php echo htmlspecialchars($message); ?></h1>
            <p class="lead mb-4">Hệ thống quản lý sản phẩm và đơn hàng</p>

            <div class="row mt-5">
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-box fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">Quản lý sản phẩm</h5>
                            <p class="card-text">Thêm, sửa, xóa và tìm kiếm sản phẩm với đầy đủ thông tin</p>
                            <a href="<?php echo BASE_URL; ?>products" class="btn btn-primary">Xem sản phẩm</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-shopping-cart fa-3x text-success mb-3"></i>
                            <h5 class="card-title">Giỏ hàng</h5>
                            <p class="card-text">Thêm sản phẩm vào giỏ hàng và quản lý đơn hàng</p>
                            <a href="<?php echo BASE_URL; ?>cart" class="btn btn-success">Xem giỏ hàng</a>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-cog fa-3x text-warning mb-3"></i>
                            <h5 class="card-title">Admin Panel</h5>
                            <p class="card-text">Quản trị sản phẩm và đơn hàng từ giao diện admin</p>
                            <a href="<?php echo BASE_URL; ?>admin" class="btn btn-warning">Vào Admin</a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5">
                <h3>Tính năng chính</h3>
                <div class="row mt-4">
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><i class="fas fa-check text-success me-2"></i> Danh sách sản
                                phẩm với tìm kiếm</li>
                            <li class="list-group-item"><i class="fas fa-check text-success me-2"></i> Chi tiết sản phẩm
                            </li>
                            <li class="list-group-item"><i class="fas fa-check text-success me-2"></i> Giỏ hàng và thanh
                                toán</li>
                            <li class="list-group-item"><i class="fas fa-check text-success me-2"></i> Quản lý đơn hàng
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><i class="fas fa-check text-success me-2"></i> Admin quản lý sản
                                phẩm</li>
                            <li class="list-group-item"><i class="fas fa-check text-success me-2"></i> Admin quản lý đơn
                                hàng</li>
                            <li class="list-group-item"><i class="fas fa-check text-success me-2"></i> Upload hình ảnh
                                sản phẩm</li>
                            <li class="list-group-item"><i class="fas fa-check text-success me-2"></i> Responsive design
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>