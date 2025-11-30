<?php
/**
 * Database Initialization Script
 * Access via: http://localhost/LTW/public/init-db.php
 */

require_once __DIR__ . '/../app/config.php';

// Prevent re-running in production
if (isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
    // Continue
} else {
    ?>
    <!DOCTYPE html>
    <html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Khởi tạo Database</title>
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body {
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 20px;
            }
            .container {
                background: white;
                border-radius: 16px;
                padding: 40px;
                max-width: 600px;
                width: 100%;
                box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            }
            h1 {
                color: #333;
                margin-bottom: 10px;
                font-size: 28px;
            }
            .subtitle {
                color: #666;
                margin-bottom: 30px;
                font-size: 14px;
            }
            .info-box {
                background: #f8f9fa;
                border-left: 4px solid #667eea;
                padding: 20px;
                margin-bottom: 30px;
                border-radius: 4px;
            }
            .info-box h3 {
                color: #667eea;
                margin-bottom: 15px;
                font-size: 16px;
            }
            .info-box ul {
                list-style: none;
                padding: 0;
            }
            .info-box li {
                padding: 8px 0;
                color: #555;
                display: flex;
                align-items: center;
            }
            .info-box li:before {
                content: "✓";
                color: #667eea;
                font-weight: bold;
                margin-right: 10px;
                font-size: 18px;
            }
            .warning {
                background: #fff3cd;
                border-left-color: #ffc107;
                color: #856404;
            }
            .warning h3 { color: #856404; }
            .warning li:before { color: #ffc107; content: "⚠"; }
            
            .config-info {
                background: #e7f3ff;
                padding: 15px;
                border-radius: 4px;
                margin-bottom: 20px;
                font-family: 'Courier New', monospace;
                font-size: 13px;
            }
            .config-info strong {
                color: #0066cc;
            }
            .btn {
                display: inline-block;
                padding: 14px 32px;
                background: #667eea;
                color: white;
                text-decoration: none;
                border-radius: 8px;
                font-weight: 600;
                transition: all 0.3s;
                border: none;
                cursor: pointer;
                font-size: 16px;
            }
            .btn:hover {
                background: #5568d3;
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            }
            .btn-secondary {
                background: #6c757d;
            }
            .btn-secondary:hover {
                background: #5a6268;
            }
            .button-group {
                display: flex;
                gap: 15px;
                margin-top: 30px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>🗄️ Khởi tạo Database</h1>
            <p class="subtitle">Pet's Choice - Hệ thống quản lý tin tức</p>
            
            <div class="config-info">
                <strong>Database Host:</strong> <?= DB_HOST ?><br>
                <strong>Database Name:</strong> <?= DB_NAME ?><br>
                <strong>Database User:</strong> <?= DB_USER ?><br>
                <strong>Database Charset:</strong> <?= DB_CHARSET ?>
            </div>
            
            <div class="info-box">
                <h3>📋 Script này sẽ thực hiện:</h3>
                <ul>
                    <li>Tạo database <strong><?= DB_NAME ?></strong> (nếu chưa tồn tại)</li>
                    <li>Tạo các bảng: users, posts, post_comments</li>
                    <li>Tạo tài khoản admin mặc định</li>
                    <li>Thêm 2 bài viết mẫu với bình luận</li>
                </ul>
            </div>
            
            <div class="info-box warning">
                <h3>⚠️ Lưu ý quan trọng:</h3>
                <ul>
                    <li>Đảm bảo XAMPP MySQL đang chạy</li>
                    <li>Kiểm tra thông tin kết nối database trong config.php</li>
                    <li>Script có thể chạy nhiều lần (idempotent)</li>
                    <li>Dữ liệu cũ sẽ KHÔNG bị xóa nếu đã tồn tại</li>
                </ul>
            </div>
            
            <div class="info-box">
                <h3>👤 Thông tin đăng nhập Admin:</h3>
                <ul>
                    <li><strong>Email:</strong> admin@example.com</li>
                    <li><strong>Password:</strong> admin123</li>
                    <li><strong>Role:</strong> Administrator</li>
                </ul>
            </div>
            
            <div class="button-group">
                <a href="?confirm=yes" class="btn">
                    🚀 Bắt đầu khởi tạo
                </a>
                <a href="<?= BASE_URL ?>" class="btn btn-secondary">
                    ← Quay lại trang chủ
                </a>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

echo '<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đang khởi tạo...</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 16px;
            padding: 40px;
            max-width: 700px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        h1 {
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }
        .log {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 20px;
            font-family: "Courier New", monospace;
            font-size: 13px;
            max-height: 400px;
            overflow-y: auto;
            margin-bottom: 20px;
        }
        .log-item {
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .log-item:last-child {
            border-bottom: none;
        }
        .success { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .info { color: #17a2b8; }
        .warning { color: #ffc107; }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s;
        }
        .btn:hover {
            background: #5568d3;
            transform: translateY(-2px);
        }
        .actions {
            text-align: center;
            margin-top: 20px;
        }
        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>⚙️ Đang khởi tạo Database...</h1>
        <div class="log">';

function logMessage($message, $type = 'info') {
    $icons = [
        'success' => '✓',
        'error' => '✗',
        'info' => 'ℹ',
        'warning' => '⚠'
    ];
    $icon = $icons[$type] ?? 'ℹ';
    echo '<div class="log-item ' . $type . '">' . $icon . ' ' . htmlspecialchars($message) . '</div>';
    flush();
    ob_flush();
}

// Start initialization
logMessage('Bắt đầu khởi tạo database...', 'info');

// Step 1: Connect to MySQL server
try {
    logMessage('Kết nối tới MySQL server...', 'info');
    $dsnServer = 'mysql:host=' . DB_HOST . ';charset=' . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    $pdoServer = new PDO($dsnServer, DB_USER, DB_PASS, $options);
    logMessage('Kết nối MySQL server thành công!', 'success');
    
    // Create database
    logMessage('Tạo database "' . DB_NAME . '"...', 'info');
    $dbName = DB_NAME;
    $pdoServer->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    logMessage('Database đã sẵn sàng!', 'success');
} catch (Exception $e) {
    logMessage('LỖI kết nối MySQL: ' . $e->getMessage(), 'error');
    echo '</div></div></body></html>';
    exit;
}

// Step 2: Connect to specific database
try {
    logMessage('Kết nối tới database "' . DB_NAME . '"...', 'info');
    $dsnDb = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
    $pdo = new PDO($dsnDb, DB_USER, DB_PASS, $options);
    logMessage('Kết nối database thành công!', 'success');
} catch (Exception $e) {
    logMessage('LỖI kết nối database: ' . $e->getMessage(), 'error');
    echo '</div></div></body></html>';
    exit;
}

// Step 3: Execute schema
logMessage('Đọc file schema.sql...', 'info');
$sqlFile = __DIR__ . '/../database/schema.sql';
if (!is_file($sqlFile)) {
    logMessage('LỖI: Không tìm thấy file schema.sql', 'error');
    echo '</div></div></body></html>';
    exit;
}

$sql = file_get_contents($sqlFile);
if ($sql === false) {
    logMessage('LỖI: Không đọc được file schema.sql', 'error');
    echo '</div></div></body></html>';
    exit;
}

try {
    logMessage('Thực thi schema.sql...', 'info');
    $pdo->exec($sql);
    logMessage('Tạo bảng thành công!', 'success');
} catch (PDOException $e) {
    $info = $e->errorInfo;
    $driverCode = isset($info[1]) ? $info[1] : null;
    if ($driverCode == 1061 || $driverCode == 1050) {
        logMessage('Lưu ý: Bảng đã tồn tại (bỏ qua)', 'warning');
    } else {
        logMessage('LỖI schema: ' . $e->getMessage(), 'error');
    }
}

// Step 4: Create admin user
logMessage('Tạo tài khoản admin...', 'info');
$email = 'admin@example.com';
$password = 'admin123';
$name = 'Administrator';

$stmt = $pdo->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
$stmt->execute([$email]);
$exists = $stmt->fetchColumn();

if ($exists) {
    $adminId = (int)$exists;
    logMessage('Tài khoản admin đã tồn tại (ID: ' . $adminId . ')', 'warning');
} else {
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $ins = $pdo->prepare('INSERT INTO users (name,email,password_hash,role,status,created_at) VALUES (?,?,?,?,?,NOW())');
    $ins->execute([$name, $email, $hash, 'admin', 'active']);
    $adminId = $pdo->lastInsertId();
    logMessage('Tạo admin thành công! Email: ' . $email . ', Password: admin123', 'success');
}

// Step 5: Create sample posts
logMessage('Tạo bài viết mẫu...', 'info');
$samples = [
    [
        'title' => 'Chăm sóc thú cưng mùa hè - Những điều cần lưu ý',
        'slug' => 'cham-soc-thu-cung-mua-he-nhung-dieu-can-luu-y',
        'summary' => 'Trong mùa hè, nhiệt độ cao có thể gây nguy hiểm cho thú cưng. Đảm bảo chúng luôn có nước uống mát và nơi trú ẩn thoáng mát.',
        'content' => '<h2>Giữ cho thú cưng luôn mát mẻ</h2><p>Trong mùa hè, nhiệt độ cao có thể gây nguy hiểm cho thú cưng. Đảm bảo chúng luôn có nước uống mát và nơi trú ẩn thoáng mát.</p><h3>Lời khuyên quan trọng:</h3><ul><li>Không để thú cưng trong xe kín vào mùa hè</li><li>Tránh cho chúng ra ngoài vào giữa trưa</li><li>Cung cấp nhiều nước uống</li><li>Sử dụng thảm làm mát nếu cần</li></ul><div style="background: #f0f8ff; padding: 20px; border-left: 4px solid #1976d2; margin: 20px 0;"><p style="margin: 0;"><strong>Nếu bạn là người bận rộn, thường không có nhiều thời gian quan tâm đến vật nuôi thì hãy liên hệ ngay cho PET SERVICE – Dịch vụ thú cưng tại nhà thông qua:</strong></p><p style="margin: 10px 0 0 0;"><strong>Hotline:</strong> <a href="tel:0898520760">0898 520 760</a><br><strong>Address:</strong> 217 Lâm Văn Bền, Phường Bình Thuận, Quận 7<br><strong>Facebook:</strong> <a href="https://www.facebook.com/petserviceclub/">https://www.facebook.com/petserviceclub/</a></p><p style="margin: 10px 0 0 0; font-weight: 600;">PET SERVICE - TRỌN VẸN TRẢI NGHIỆM</p></div>'
    ],
    [
        'title' => 'Cắt tỉa lông chó lông dài tại nhà? Đã có Pet Service lo!',
        'slug' => 'cat-tia-long-cho-long-dai-tai-nha-pet-service',
        'summary' => 'Tại Pet Service, chúng tôi hiểu rằng việc chăm sóc một chú chó lông dài có thể là một thách thức, đặc biệt là khi cần cắt tỉa lông định kỳ.',
        'content' => '<h2>Dịch vụ cắt tỉa lông chuyên nghiệp</h2><p>Tại Pet Service, chúng tôi hiểu rằng việc chăm sóc một chú chó lông dài có thể là một thách thức, đặc biệt là khi cần cắt tỉa lông định kỳ.</p><h3>Quy trình cắt tỉa của chúng tôi:</h3><ol><li>Kiểm tra sức khỏe tổng quát</li><li>Tắm rửa và sấy khô</li><li>Cắt tỉa theo yêu cầu</li><li>Vệ sinh tai, móng, răng</li><li>Tư vấn chăm sóc tại nhà</li></ol><p>Với đội ngũ groomer chuyên nghiệp, chúng tôi cam kết mang đến dịch vụ tốt nhất cho thú cưng của bạn.</p><div style="background: #f0f8ff; padding: 20px; border-left: 4px solid #1976d2; margin: 20px 0;"><p style="margin: 0;"><strong>Liên hệ ngay với PET SERVICE để đặt lịch:</strong></p><p style="margin: 10px 0 0 0;"><strong>Hotline:</strong> 0898 520 760<br><strong>Địa chỉ:</strong> 217 Lâm Văn Bền, Phường Bình Thuận, Quận 7</p></div>'
    ]
];

$postsCreated = 0;
foreach ($samples as $s) {
    $check = $pdo->prepare('SELECT id FROM posts WHERE slug = ? LIMIT 1');
    $check->execute([$s['slug']]);
    $found = $check->fetchColumn();
    
    if ($found) {
        logMessage('Bài viết "' . $s['title'] . '" đã tồn tại', 'warning');
        continue;
    }
    
    $ins = $pdo->prepare('INSERT INTO posts (author_user_id,title,slug,summary,content_html,cover_image_url,status,published_at,created_at) VALUES (?,?,?,?,?,?,?,NOW(),NOW())');
    $ins->execute([$adminId, $s['title'], $s['slug'], $s['summary'], $s['content'], null, 'published']);
    $postId = $pdo->lastInsertId();
    $postsCreated++;
    logMessage('Tạo bài viết: "' . $s['title'] . '" (ID: ' . $postId . ')', 'success');
    
    // Add sample comment
    $cins = $pdo->prepare('INSERT INTO post_comments (post_id, user_id, author_name, author_email, rating, content, status, ip_address, created_at) VALUES (?,?,?,?,?,?,?,?,NOW())');
    $cins->execute([$postId, $adminId, 'Khách hàng hài lòng', 'guest@example.com', 5, 'Bài viết rất hữu ích! Cảm ơn Pet Service đã chia sẻ.', 'approved', '127.0.0.1']);
    logMessage('Thêm bình luận mẫu cho bài viết ID: ' . $postId, 'success');
}

// Summary
echo '</div>';
echo '<div style="background: #d4edda; border: 1px solid #c3e6cb; border-radius: 8px; padding: 20px; margin-top: 20px;">';
echo '<h3 style="color: #155724; margin-bottom: 15px;">✓ Khởi tạo hoàn tất!</h3>';
echo '<ul style="color: #155724; line-height: 1.8;">';
echo '<li>✓ Database: <strong>' . DB_NAME . '</strong></li>';
echo '<li>✓ Admin Email: <strong>admin@example.com</strong></li>';
echo '<li>✓ Admin Password: <strong>admin123</strong></li>';
echo '<li>✓ Bài viết đã tạo: <strong>' . $postsCreated . '</strong></li>';
echo '</ul>';
echo '</div>';

echo '<div class="actions">';
echo '<a href="' . BASE_URL . 'login" class="btn">🔐 Đăng nhập Admin</a> ';
echo '<a href="' . BASE_URL . 'admin" class="btn" style="background: #28a745;">📊 Vào Dashboard</a> ';
echo '<a href="' . BASE_URL . 'posts" class="btn" style="background: #17a2b8;">📰 Xem tin tức</a>';
echo '</div>';

echo '</div></body></html>';
