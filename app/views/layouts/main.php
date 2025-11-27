<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($title ?? APP_NAME) ?></title>
  <?= \Core\Assets::vite('app') ?>
  <style>
    body{margin:0;background:#f6f7f9}
    .container{max-width:1060px;margin:0 auto;padding:1rem}
    .card{background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:1.25rem}
  </style>
</head>
<body>
<header class="navbar navbar-light bg-white border-bottom">
  <div class="container"><strong><?= APP_NAME ?></strong></div>
</header>
<main class="container" style="margin-top:1.5rem">
  <div class="card">
    <?php $content(); ?>
  </div>
</main>
<footer class="container"><p>© <?= date('Y') ?> <?= APP_NAME ?></p></footer>
<!-- Tabler JS bundled via Vite entry import -->
</body>
</html>
