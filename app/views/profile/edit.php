<h2>Hồ sơ cá nhân</h2>
<?php if (!empty($error)): ?>
  <div style="color:#b91c1c; margin:.5rem 0;"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<div style="display:flex; gap:2rem; align-items:flex-start; flex-wrap:wrap;">
  <form method="post" action="<?= BASE_URL ?>profile" enctype="multipart/form-data" style="flex:1; min-width:260px;">
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars(\Core\Auth::csrfToken()) ?>">
    <div style="display:flex; gap:1rem; align-items:center;">
      <img src="<?= isset($user['avatar_url']) && $user['avatar_url'] ? BASE_URL . $user['avatar_url'] : 'https://via.placeholder.com/96' ?>" alt="Avatar" style="width:96px; height:96px; border-radius:50%; object-fit:cover; border:1px solid #e5e7eb;">
      <div>
        <label>Ảnh đại diện (jpg/png, <=2MB)</label><br>
        <input type="file" name="avatar" accept="image/*">
      </div>
    </div>
    <div style="margin-top:1rem;">
      <label>Họ và tên</label><br>
      <input name="name" value="<?= htmlspecialchars($user['name'] ?? '') ?>" required style="width:100%; padding:.5rem;">
    </div>
    <div style="margin-top:.5rem;">
      <label>Email</label><br>
      <input value="<?= htmlspecialchars($user['email'] ?? '') ?>" disabled style="width:100%; padding:.5rem; background:#f3f4f6;">
    </div>
    <div style="margin-top:.5rem;">
      <label>Số điện thoại</label><br>
      <input name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" style="width:100%; padding:.5rem;">
    </div>
    <button type="submit" class="btn" style="margin-top:1rem;">Lưu thông tin</button>
  </form>

  <form method="post" action="<?= BASE_URL ?>profile/password" style="flex:1; min-width:260px;">
    <input type="hidden" name="_csrf" value="<?= htmlspecialchars(\Core\Auth::csrfToken()) ?>">
    <h3>Đổi mật khẩu</h3>
    <div style="margin:.5rem 0;">
      <label>Mật khẩu hiện tại</label><br>
      <input name="current_password" type="password" required style="width:100%; padding:.5rem;">
    </div>
    <div style="margin:.5rem 0; display:flex; gap:1rem;">
      <div style="flex:1">
        <label>Mật khẩu mới</label><br>
        <input name="new_password" type="password" required style="width:100%; padding:.5rem;">
      </div>
      <div style="flex:1">
        <label>Nhập lại mật khẩu</label><br>
        <input name="new_password2" type="password" required style="width:100%; padding:.5rem;">
      </div>
    </div>
    <button type="submit" class="btn">Cập nhật mật khẩu</button>
  </form>
</div>