<?php
// config.php
define('DB_HOST','127.0.0.1');
define('DB_NAME','ltw');
define('DB_USER','root');
define('DB_PASS','');

define('BASE_URL','/'); // adjust if in subfolder
define('UPLOAD_DIR', __DIR__ . '/uploads/');
define('UPLOAD_URL', BASE_URL . 'uploads/');

if (!file_exists(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);
