<h2>Đăng ký</h2>
<?php if (!empty($error)): ?>
  <div style="color:#b91c1c; margin:.5rem 0;"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<form method="post" action="<?= BASE_URL ?>register">
  <input type="hidden" name="_csrf" value="<?= htmlspecialchars(\Core\Auth::csrfToken()) ?>">
  <div style="margin:.5rem 0;">
    <label>Họ và tên</label><br>
    <input name="name" required style="width:100%; padding:.5rem;">
  </div>
  <div style="margin:.5rem 0;">
    <label>Email</label><br>
    <input name="email" type="email" required style="width:100%; padding:.5rem;">
  </div>
  <div style="margin:.5rem 0;">
    <label>Số điện thoại</label><br>
    <input name="phone" style="width:100%; padding:.5rem;">
  </div>
  <div style="margin:.5rem 0; display:flex; gap:1rem;">
    <div style="flex:1">
      <label>Mật khẩu</label><br>
      <input name="password" type="password" required style="width:100%; padding:.5rem;">
    </div>
    <div style="flex:1">
      <label>Nhập lại mật khẩu</label><br>
      <input name="password2" type="password" required style="width:100%; padding:.5rem;">
    </div>
  </div>
  <button type="submit" class="btn">Tạo tài khoản</button>
  <p style="margin-top:.5rem">Đã có tài khoản? <a href="<?= BASE_URL ?>login">Đăng nhập</a></p>
</form>