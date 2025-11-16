<?php
// One-time seed script for XAMPP environment.
// Usage: start Apache & MySQL, then open in browser: http://localhost/LTW/database/seed.php

require_once __DIR__ . '/../app/config.php';
// Bootstrap: connect to server first (no dbname) to ensure database exists
try {
    $dsnServer = 'mysql:host=' . DB_HOST . ';charset=' . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    $pdoServer = new PDO($dsnServer, DB_USER, DB_PASS, $options);
    // Create database if not exists
    $dbName = DB_NAME;
    $pdoServer->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
} catch (Exception $e) {
    echo "Database server connection failed: " . htmlspecialchars($e->getMessage());
    exit;
}

// Now connect to the specific database
try {
    $dsnDb = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
    $pdo = new PDO($dsnDb, DB_USER, DB_PASS, $options);
} catch (Exception $e) {
    echo "Database connection failed: " . htmlspecialchars($e->getMessage());
    exit;
}

// Execute schema SQL
$sqlFile = __DIR__ . '/schema.sql';
if (!is_file($sqlFile)) {
    echo "schema.sql not found at $sqlFile";
    exit;
}
$sql = file_get_contents($sqlFile);
if ($sql === false) {
    echo "Failed to read schema.sql";
    exit;
}

try {
    // PDO::exec can execute multiple statements in many setups
    $pdo->exec($sql);
} catch (PDOException $e) {
    // MySQL error code 1061 = Duplicate key name (index already exists)
    $info = $e->errorInfo;
    $driverCode = isset($info[1]) ? $info[1] : null;
    if ($driverCode == 1061) {
        // Not fatal for idempotent seed — ignore and continue
        echo "Note: duplicate index detected and ignored (1061).<br>";
    } else {
        echo "Error executing schema: " . htmlspecialchars($e->getMessage()) . '<br>';
    }
    // continue — maybe parts already created
} catch (Exception $e) {
    echo "Error executing schema: " . htmlspecialchars($e->getMessage()) . '<br>';
}

// Create an admin user (email: admin@example.com, password: admin123)
$email = 'admin@example.com';
$password = 'admin123';
$name = 'Administrator';

$stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
$exists = $stmt->fetchColumn();
if ($exists) {
    $adminId = (int)$exists;
    echo "Admin user already exists (id={$adminId}).<br>";
} else {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $ins = $pdo->prepare('INSERT INTO users (name,email,password_hash,role,status,created_at) VALUES (?,?,?,?,?,NOW())');
    $ins->execute([$name, $email, $hash, 'admin', 'active']);
    $adminId = $pdo->lastInsertId();
    echo "Created admin user: {$email} with password 'admin123' (id={$adminId}).<br>";
}

// Ensure at least two sample posts exist (insert specific sample posts if missing)
$samples = [
    [
        'title' => 'Bí Quyết Chăm Sóc Mèo Con Mới Nhận Nuôi Tại TPHCM',
        'slug' => 'bi-quyet-cham-soc-meo-con-moi-nhan-nuoi-tai-tphcm',
        'summary' => 'Hành trình bắt đầu với mèo con dành cho người mới nuôi. Nhận nuôi một chú mèo con là một...'
    ],
    [
        'title' => 'Cắt tỉa lông chó lông dài tại nhà? Đã có Pet Service lo!',
        'slug' => 'cat-tia-long-cho-long-dai-tai-nha-pet-service',
        'summary' => 'Tại Pet Service, chúng tôi hiểu rằng việc chăm sóc một chú chó lông dài có thể là một thách thức...'
    ]
];

foreach ($samples as $s) {
    $check = $pdo->prepare('SELECT id FROM posts WHERE slug = ? LIMIT 1');
    $check->execute([$s['slug']]);
    $found = $check->fetchColumn();
    if ($found) {
        echo "Sample post '{$s['slug']}' already exists (id={$found}).<br>";
        continue;
    }

    $content = '<p>' . htmlspecialchars($s['summary']) . '</p>';
    // Note: use 7 placeholders for the first 7 columns, then NOW() for published_at and created_at
    $ins = $pdo->prepare('INSERT INTO posts (author_user_id,title,slug,summary,content_html,cover_image_url,status,published_at,created_at) VALUES (?,?,?,?,?,?,?,NOW(),NOW())');
    // cover_image_url left NULL for placeholder; status published
    $ins->execute([ $adminId ?? null, $s['title'], $s['slug'], $s['summary'], $content, null, 'published' ]);
    $postId = $pdo->lastInsertId();
    echo "Inserted sample post '{$s['slug']}' id={$postId}.<br>";

    // Add a sample approved comment for each inserted sample
    $cins = $pdo->prepare('INSERT INTO post_comments (post_id, user_id, author_name, author_email, rating, content, status, ip_address, created_at) VALUES (?,?,?,?,?,?,?,?,NOW())');
    $cins->execute([ $postId, $adminId ?? null, 'Khách', 'guest@example.com', 5, 'Bình luận mẫu - tốt!', 'approved', '127.0.0.1' ]);
    echo "Inserted sample comment for post id={$postId}.<br>";
}

echo '<br>Done. You can now open the public site and admin panel.';

// Helpful links (attempt to guess public path)
$base = BASE_URL;
echo '<ul>';
echo '<li><a href="' . htmlspecialchars($base . 'posts') . '">Danh sách tin tức (public)</a></li>';
echo '<li><a href="' . htmlspecialchars($base . 'post?slug=bai-viet-mau') . '">Xem bài viết mẫu</a></li>';
echo '<li><a href="' . htmlspecialchars($base . 'admin/posts') . '">Admin - Quản lý bài viết (đăng nhập yêu cầu)</a></li>';
echo '</ul>';

?>
