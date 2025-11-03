<h2>Đăng nhập</h2>
<?php if (!empty($error)): ?>
  <div style="color:#b91c1c; margin:.5rem 0;"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<form method="post" action="<?= BASE_URL ?>login">
  <input type="hidden" name="_csrf" value="<?= htmlspecialchars(\Core\Auth::csrfToken()) ?>">
  <div style="margin:.5rem 0;">
    <label>Email</label><br>
    <input name="email" type="email" required style="width:100%; padding:.5rem;">
  </div>
  <div style="margin:.5rem 0;">
    <label>Mật khẩu</label><br>
    <input name="password" type="password" required style="width:100%; padding:.5rem;">
  </div>
  <button type="submit" class="btn">Đăng nhập</button>
  <p style="margin-top:.5rem">Chưa có tài khoản? <a href="<?= BASE_URL ?>register">Đăng ký</a></p>
</form>