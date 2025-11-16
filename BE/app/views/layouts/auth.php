<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($title ?? APP_NAME) ?></title>
  <?= \Core\Assets::vite('app') ?>
</head>
<body>
  <?php $content(); ?>
</body>
</html>
