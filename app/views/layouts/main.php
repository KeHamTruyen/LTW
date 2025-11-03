<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($title ?? APP_NAME) ?></title>
  <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/app.css">
  <style>
    body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;margin:0;background:#f6f7f9;color:#1f2937}
    header{background:#fff;border-bottom:1px solid #e5e7eb;position:sticky;top:0;z-index:10}
    .container{max-width:1060px;margin:0 auto;padding:1rem}
    nav a{margin-right:1rem;color:#111827;text-decoration:none}
    .card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:1.25rem;box-shadow:0 1px 2px rgba(0,0,0,.04)}
    footer{margin-top:3rem;color:#6b7280}
  </style>
</head>
<body>
<header>
  <div class="container" style="display:flex;justify-content:space-between;align-items:center">
    <div><strong><?= APP_NAME ?></strong></div>
    <nav>
      <a href="<?= BASE_URL ?>">Trang chủ</a>
      <a href="#">Dịch vụ</a>
      <a href="#">Đặt lịch</a>
      <?php if (\Core\Auth::check()): ?>
        <?php if (\Core\Auth::isAdmin()): ?>
          <a href="<?= BASE_URL ?>admin/users">Admin</a>
        <?php endif; ?>
        <a href="<?= BASE_URL ?>profile">Hồ sơ</a>
        <a href="<?= BASE_URL ?>logout">Đăng xuất</a>
      <?php else: ?>
        <a href="<?= BASE_URL ?>login">Đăng nhập</a>
        <a href="<?= BASE_URL ?>register">Đăng ký</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
<main class="container" style="margin-top:1.5rem">
  <div class="card">
    <?php $content(); ?>
  </div>
</main>
<footer class="container">
  <p>© <?= date('Y') ?> <?= APP_NAME ?>. All rights reserved.</p>
</footer>
<script src="<?= BASE_URL ?>assets/js/app.js"></script>
</body>
</html>