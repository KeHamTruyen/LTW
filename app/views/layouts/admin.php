<!doctype html>
<html lang="vi" data-bs-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($title ?? ('Admin - ' . APP_NAME)) ?></title>
  <?= \Core\Assets::vite('app') ?>
</head>
<body>
  <div class="page">
    <header class="navbar navbar-expand-md d-print-none" data-bs-theme="light">
      <div class="container-xl">
        <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
          <a href="<?= BASE_URL ?>admin">Admin - <?= APP_NAME ?></a>
        </h1>
      </div>
    </header>
    <div class="page-wrapper">
      <div class="container-xl">
        <div class="row g-0">
          <div class="col-12 col-md-3 col-xl-2 d-none d-md-block border-end">
            <div class="list-group list-group-flush">
              <a href="<?= BASE_URL ?>admin" class="list-group-item list-group-item-action">Tổng quan</a>
              <a href="#" class="list-group-item list-group-item-action">Bài viết</a>
              <a href="#" class="list-group-item list-group-item-action">Bình luận</a>
              <a href="#" class="list-group-item list-group-item-action">Người dùng</a>
            </div>
          </div>
          <div class="col">
            <div class="container-xl">
              <div class="page-header d-print-none">
                <div class="row align-items-center"><div class="col"><h2 class="page-title"><?= htmlspecialchars($title ?? 'Admin') ?></h2></div></div>
              </div>
            </div>
            <div class="page-body">
              <div class="container-xl">
                <?php $content(); ?>
              </div>
            </div>
            <footer class="footer footer-transparent d-print-none">
              <div class="container-xl">
                <div class="row text-center align-items-center flex-row-reverse">
                  <div class="col-12 col-lg-auto mt-3 mt-lg-0">© <?= date('Y') ?> <?= APP_NAME ?> Admin</div>
                </div>
              </div>
            </footer>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Tabler JS/CSS included via Vite bundle imports -->
</body>
</html>
