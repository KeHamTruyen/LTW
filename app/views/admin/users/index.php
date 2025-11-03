<h2>Quản lý người dùng</h2>
<?php if (!empty($_SESSION['flash'])): ?>
  <div style="background:#ecfeff;border:1px solid #22d3ee;color:#155e75;padding:.5rem 1rem;border-radius:.5rem;margin-bottom:1rem;">
    <?= htmlspecialchars($_SESSION['flash']); unset($_SESSION['flash']); ?>
  </div>
<?php endif; ?>
<form method="get" action="<?= BASE_URL ?>admin/users" style="margin:.5rem 0 1rem;">
  <input name="q" value="<?= htmlspecialchars($q ?? '') ?>" placeholder="Tìm theo tên/email" style="padding:.5rem; width:260px;">
  <button type="submit">Tìm</button>
</form>
<table border="0" cellpadding="8" cellspacing="0" style="width:100%; border:1px solid #e5e7eb; border-radius:8px; overflow:hidden;">
  <thead style="background:#f9fafb;">
  <tr>
    <th align="left">ID</th>
    <th align="left">Tên</th>
    <th align="left">Email</th>
    <th align="left">Điện thoại</th>
    <th align="left">Vai trò</th>
    <th align="left">Trạng thái</th>
    <th align="left">Hành động</th>
  </tr>
  </thead>
  <tbody>
  <?php foreach ($users as $u): ?>
    <tr style="border-top:1px solid #e5e7eb;">
      <td>#<?= (int)$u['id'] ?></td>
      <td><?= htmlspecialchars($u['name']) ?></td>
      <td><?= htmlspecialchars($u['email']) ?></td>
      <td><?= htmlspecialchars($u['phone']) ?></td>
      <td>
        <form method="post" action="<?= BASE_URL ?>admin/users/role" style="display:inline;">
          <input type="hidden" name="_csrf" value="<?= htmlspecialchars(\Core\Auth::csrfToken()) ?>">
          <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
          <select name="role" onchange="this.form.submit()">
            <?php foreach (['customer','staff','admin'] as $r): ?>
              <option value="<?= $r ?>" <?= $u['role']===$r?'selected':'' ?>><?= $r ?></option>
            <?php endforeach; ?>
          </select>
        </form>
      </td>
      <td>
        <form method="post" action="<?= BASE_URL ?>admin/users/status" style="display:inline;">
          <input type="hidden" name="_csrf" value="<?= htmlspecialchars(\Core\Auth::csrfToken()) ?>">
          <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
          <select name="status" onchange="this.form.submit()">
            <?php foreach (['active','inactive','banned'] as $s): ?>
              <option value="<?= $s ?>" <?= $u['status']===$s?'selected':'' ?>><?= $s ?></option>
            <?php endforeach; ?>
          </select>
        </form>
      </td>
      <td>
        <form method="post" action="<?= BASE_URL ?>admin/users/reset" onsubmit="return confirm('Reset mật khẩu người dùng #<?= (int)$u['id'] ?>?');" style="display:inline;">
          <input type="hidden" name="_csrf" value="<?= htmlspecialchars(\Core\Auth::csrfToken()) ?>">
          <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
          <button type="submit">Reset mật khẩu</button>
        </form>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>