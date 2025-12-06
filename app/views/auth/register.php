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
        <h2 class="h2 text-center mb-4">Đăng ký tài khoản</h2>

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

        <form action="<?= BASE_URL ?>register" method="post" id="registerForm" autocomplete="off">
          <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?? '' ?>">
          
          <div class="mb-3">
            <label class="form-label">Họ và tên <span class="text-danger">*</span></label>
            <input type="text" 
                   name="name" 
                   class="form-control" 
                   placeholder="Nhập họ và tên" 
                   required 
                   minlength="2"
                   autocomplete="name" />
            <small class="form-hint">Tối thiểu 2 ký tự</small>
          </div>
          
          <div class="mb-3">
            <label class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" 
                   name="email" 
                   class="form-control" 
                   placeholder="your@email.com" 
                   required 
                   autocomplete="email" />
          </div>
          
          <div class="mb-3">
            <label class="form-label">Số điện thoại</label>
            <input type="tel" 
                   name="phone" 
                   class="form-control" 
                   placeholder="0123456789" 
                   autocomplete="tel" />
          </div>
          
          <div class="mb-3">
            <label class="form-label">Mật khẩu <span class="text-danger">*</span></label>
            <input type="password" 
                   name="password" 
                   id="password"
                   class="form-control" 
                   placeholder="Tối thiểu 6 ký tự" 
                   required 
                   minlength="6"
                   autocomplete="new-password" />
            <small class="form-hint">Tối thiểu 6 ký tự</small>
          </div>
          
          <div class="mb-3">
            <label class="form-label">Xác nhận mật khẩu <span class="text-danger">*</span></label>
            <input type="password" 
                   name="password_confirm" 
                   id="password_confirm"
                   class="form-control" 
                   placeholder="Nhập lại mật khẩu" 
                   required 
                   minlength="6"
                   autocomplete="new-password" />
            <small class="form-hint text-danger" id="passwordMatch" style="display:none;">Mật khẩu không khớp</small>
          </div>
          
          <div class="form-footer">
            <button type="submit" class="btn btn-primary w-100">Đăng ký</button>
          </div>
        </form>
      </div>
      <div class="card-footer text-center py-3">
        <div class="text-secondary">
          Đã có tài khoản? <a href="<?= BASE_URL ?>login">Đăng nhập ngay</a>
        </div>
      </div>
    </div>

    <div class="text-center text-secondary mt-3">
      <a href="<?= BASE_URL ?>">← Về trang chủ</a>
    </div>
  </div>
</div>

<script>
// Client-side validation
document.getElementById('registerForm').addEventListener('submit', function(e) {
    const password = document.getElementById('password').value;
    const passwordConfirm = document.getElementById('password_confirm').value;
    const passwordMatch = document.getElementById('passwordMatch');
    
    if (password !== passwordConfirm) {
        e.preventDefault();
        passwordMatch.style.display = 'block';
        document.getElementById('password_confirm').focus();
        return false;
    } else {
        passwordMatch.style.display = 'none';
    }
});

// Real-time password match check
document.getElementById('password_confirm').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const passwordConfirm = this.value;
    const passwordMatch = document.getElementById('passwordMatch');
    
    if (passwordConfirm && password !== passwordConfirm) {
        passwordMatch.style.display = 'block';
        this.setCustomValidity('Mật khẩu không khớp');
    } else {
        passwordMatch.style.display = 'none';
        this.setCustomValidity('');
    }
});
</script>

