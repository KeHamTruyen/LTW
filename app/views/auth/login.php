<div class="page page-center">
  <div class="container container-tight py-4">
    <div class="text-center mb-4">
      <a href="<?= htmlspecialchars(BASE_URL, ENT_QUOTES) ?>" aria-label="<?= APP_NAME ?>" class="navbar-brand navbar-brand-autodark">
        <img src="<?= htmlspecialchars($homeData['company_logo_url'] ?? BASE_URL . 'assets/images/logo.png') ?>" 
             alt="Pet's Choice Logo" 
             class="navbar-brand-image"
             style="height: 60px; width: auto;"
             onerror="this.src='<?= BASE_URL ?>assets/images/logo.png'">
      </a>
    </div>

    <div class="card card-md">
      <div class="card-body">
        <h2 class="h2 text-center mb-4">Đăng nhập hệ thống</h2>

        <!-- Flash messages -->
        <?php if (isset($_SESSION['flash_success'])): ?>
            <div class="alert alert-success alert-dismissible">
                <?= htmlspecialchars($_SESSION['flash_success']) ?>
                <a class="btn-close" data-bs-dismiss="alert"></a>
            </div>
            <?php unset($_SESSION['flash_success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['flash_error'])): ?>
            <div class="alert alert-danger alert-dismissible">
                <?= htmlspecialchars($_SESSION['flash_error']) ?>
                <a class="btn-close" data-bs-dismiss="alert"></a>
            </div>
            <?php unset($_SESSION['flash_error']); ?>
        <?php endif; ?>

        <form action="<?= BASE_URL ?>login" method="post" autocomplete="off">
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" 
                   name="email" 
                   class="form-control" 
                   placeholder="your@email.com" 
                   autocomplete="off" 
                   required />
          </div>
          <div class="mb-2">
            <label class="form-label">Mật khẩu</label>
            <div class="input-group input-group-flat">
              <input type="password" 
                     name="password" 
                     class="form-control" 
                     placeholder="Nhập mật khẩu" 
                     autocomplete="off"
                     required />
            </div>
          </div>
          <div class="mb-2">
            <label class="form-check">
              <input type="checkbox" name="remember" class="form-check-input" />
              <span class="form-check-label">Ghi nhớ đăng nhập (30 ngày)</span>
            </label>
          </div>
          <div class="form-footer">
            <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
          </div>
        </form>
      </div>
      <div class="card-footer text-center py-3">
        <div class="text-secondary">
          Tài khoản demo: <code>admin@petcare.com</code> / <code>password</code>
        </div>
      </div>
    </div>

    <div class="text-center text-secondary mt-3">
      <a href="<?= BASE_URL ?>">← Về trang chủ</a>
    </div>
  </div>
</div>
