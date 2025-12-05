<!doctype html>
<html lang="vi" data-bs-theme="light">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title><?= htmlspecialchars($title ?? ('Admin - ' . APP_NAME)) ?></title>
  <!-- Tabler Core CSS -->
  <link href="<?= BASE_URL ?>assets/tabler/css/tabler.min.css" rel="stylesheet">
  <link href="<?= BASE_URL ?>assets/tabler/css/tabler-vendors.min.css" rel="stylesheet">
  <style>
    @import url("https://rsms.me/inter/inter.css");
    :root {
      --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;
    }
    body { font-family: var(--tblr-font-sans-serif); }
  </style>
</head>
<body>
  <script src="<?= BASE_URL ?>assets/tabler/js/tabler-theme.min.js"></script>
  <div class="page">
    <!-- Sidebar -->
    <aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark">
      <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu" aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <h1 class="navbar-brand navbar-brand-autodark">
          <a href="<?= BASE_URL ?>admin">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path d="M3 3h7v7H3z"></path><path d="M14 3h7v7h-7z"></path><path d="M14 14h7v7h-7z"></path><path d="M3 14h7v7H3z"></path></svg>
            Admin <?= APP_NAME ?>
          </a>
        </h1>
        <div class="navbar-nav flex-row d-lg-none">
          <div class="nav-item dropdown">
            <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
              <span class="avatar avatar-sm" style="background-image: url(https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user_name'] ?? 'Admin') ?>&background=206bc4&color=fff)"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
              <a href="<?= BASE_URL ?>" class="dropdown-item">Về trang chủ</a>
              <div class="dropdown-divider"></div>
              <a href="<?= BASE_URL ?>logout" class="dropdown-item">Đăng xuất</a>
            </div>
          </div>
        </div>
        <div class="collapse navbar-collapse" id="sidebar-menu">
          <ul class="navbar-nav pt-lg-3">
            <li class="nav-item <?= $_SERVER['REQUEST_URI'] == BASE_URL . 'admin' || $_SERVER['REQUEST_URI'] == BASE_URL . 'admin/' ? 'active' : '' ?>">
              <a class="nav-link" href="<?= BASE_URL ?>admin">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l-2 0l9 -9l9 9l-2 0" /><path d="M5 12v7a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-7" /><path d="M9 21v-6a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v6" /></svg>
                </span>
                <span class="nav-link-title">Tổng quan</span>
              </a>
            </li>
            <li class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/posts') !== false ? 'active' : '' ?>">
              <a class="nav-link" href="<?= BASE_URL ?>admin/posts">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 9l1 0" /><path d="M9 13l6 0" /><path d="M9 17l6 0" /></svg>
                </span>
                <span class="nav-link-title">Bài viết</span>
              </a>
            </li>
            <li class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/comments') !== false ? 'active' : '' ?>">
              <a class="nav-link" href="<?= BASE_URL ?>admin/comments">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 9h8" /><path d="M8 13h6" /><path d="M18 4a3 3 0 0 1 3 3v8a3 3 0 0 1 -3 3h-5l-5 3v-3h-2a3 3 0 0 1 -3 -3v-8a3 3 0 0 1 3 -3h12z" /></svg>
                </span>
                <span class="nav-link-title">Bình luận</span>
              </a>
            </li>
            <li class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/products') !== false ? 'active' : '' ?>">
              <a class="nav-link" href="<?= BASE_URL ?>admin/products">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 2l.01 6.553a3 3 0 0 1 -.245 1.207l-.112 .19a4.999 4.999 0 0 0 -.669 1.226l-1.341 2.584a2.107 2.107 0 0 0 -.018 .226l-.086 .19c-.059 .27 -.118 .558 -.185 .867a5.154 5.154 0 0 0 -.084 1.262l-.005 .066c0 .527 .095 1.096 .257 1.658l.019 .062c.301 1.118 1.303 2.007 2.482 2.55l.039 .015c.28 .093 .578 .154 .883 .181l.062 .007c.527 0 1.096 -.095 1.658 -.257l.062 -.019a4.978 4.978 0 0 0 1.226 -.669l2.584 -1.341a2.111 2.111 0 0 0 .226 -.018l.19 -.086c.27 -.059 .558 -.118 .867 -.185l.066 -.005a5.154 5.154 0 0 0 1.262 -.084l.19 -.112a4.999 4.999 0 0 0 1.226 -.669l2.584 -1.341a2.11 2.11 0 0 0 .226 -.018l.19 -.086a5.037 5.037 0 0 0 .867 -.185l.066 -.005a5.154 5.154 0 0 0 1.262 -.084l.19 -.112a5 5 0 0 0 1.11 -.644l.174 -.165l.553 .01h6.553v16h-16v-6.553l-6.01 -.01z" /></svg>
                </span>
                <span class="nav-link-title">Sản phẩm</span>
              </a>
            </li>
            <li class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/orders') !== false ? 'active' : '' ?>">
              <a class="nav-link" href="<?= BASE_URL ?>admin/orders">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 5a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2" /><path d="M9 12l.01 0" /><path d="M13 12l2 0" /><path d="M9 16l.01 0" /><path d="M13 16l2 0" /></svg>
                </span>
                <span class="nav-link-title">Đơn hàng</span>
              </a>
            </li>
            <li class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/users') !== false ? 'active' : '' ?>">
              <a class="nav-link" href="<?= BASE_URL ?>admin/users">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 7m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>
                </span>
                <span class="nav-link-title">Người dùng</span>
              </a>
            </li>
            <li class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/contacts') !== false ? 'active' : '' ?>">
              <a class="nav-link" href="<?= BASE_URL ?>admin/contacts">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z" /><path d="M3 7l9 6l9 -6" /></svg>
                </span>
                <span class="nav-link-title">Liên hệ</span>
              </a>
            </li>
            <li class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/pages') !== false ? 'active' : '' ?>">
              <a class="nav-link" href="<?= BASE_URL ?>admin/pages">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 9l1 0" /><path d="M9 13l6 0" /><path d="M9 17l6 0" /></svg>
                </span>
                <span class="nav-link-title">Trang</span>
              </a>
            </li>
            <li class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/faqs') !== false ? 'active' : '' ?>">
              <a class="nav-link" href="<?= BASE_URL ?>admin/faqs">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 8h8" /><path d="M8 12h8" /><path d="M8 16h8" /><path d="M3 3m0 2a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v14a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2z" /></svg>
                </span>
                <span class="nav-link-title">FAQ</span>
              </a>
            </li>
            <li class="nav-item <?= strpos($_SERVER['REQUEST_URI'], '/admin/about') !== false ? 'active' : '' ?>">
              <a class="nav-link" href="<?= BASE_URL ?>admin/about">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 8v4" /><path d="M12 16h.01" /></svg>
                </span>
                <span class="nav-link-title">Giới thiệu</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </aside>
    <!-- Navbar -->
    <header class="navbar navbar-expand-md d-print-none">
      <div class="container-xl">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-nav flex-row order-md-last">
          <div class="d-none d-md-flex">
            <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" title="Chế độ tối" data-bs-toggle="tooltip" data-bs-placement="bottom">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3c.132 0 .263 0 .393 0a7.5 7.5 0 0 0 7.92 12.446a9 9 0 1 1 -8.313 -12.454z" /></svg>
            </a>
            <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="Chế độ sáng" data-bs-toggle="tooltip" data-bs-placement="bottom">
              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 12h1m8 -9v1m8 8h1m-9 8v1m-6.4 -15.4l.7 .7m12.1 -.7l-.7 .7m0 11.4l.7 .7m-12.1 -.7l-.7 .7" /></svg>
            </a>
          </div>
          <div class="nav-item dropdown">
            <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
              <span class="avatar avatar-sm" style="background-image: url(https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user_name'] ?? 'Admin') ?>&background=206bc4&color=fff)"></span>
              <div class="d-none d-xl-block ps-2">
                <div><?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin') ?></div>
                <div class="mt-1 small text-secondary">Quản trị viên</div>
              </div>
            </a>
            <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
              <a href="<?= BASE_URL ?>" class="dropdown-item">Về trang chủ</a>
              <div class="dropdown-divider"></div>
              <a href="<?= BASE_URL ?>logout" class="dropdown-item">Đăng xuất</a>
            </div>
          </div>
        </div>
      </div>
    </header>

    <div class="page-wrapper">
      <?php $content(); ?>
      <footer class="footer footer-transparent d-print-none">
        <div class="container-xl">
          <div class="row text-center align-items-center flex-row-reverse">
            <div class="col-lg-auto ms-lg-auto">
              <ul class="list-inline list-inline-dots mb-0">
                <li class="list-inline-item">
                  <a href="https://tabler.io/admin-template" target="_blank" class="link-secondary" rel="noopener">
                    Powered by Tabler
                  </a>
                </li>
              </ul>
            </div>
            <div class="col-12 col-lg-auto mt-3 mt-lg-0">
              <ul class="list-inline list-inline-dots mb-0">
                <li class="list-inline-item">© <?= date('Y') ?> <a href="<?= BASE_URL ?>" class="link-secondary"><?= APP_NAME ?></a></li>
              </ul>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </div>
  
  <!-- Tabler Core -->
  <script src="<?= BASE_URL ?>assets/tabler/js/tabler.min.js"></script>
</body>
</html>
