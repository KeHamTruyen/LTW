<?php
/**
 * Database Setup Script
 * Chạy file này 1 lần để tự động tạo database, tables và demo data
 * URL: http://localhost/LTW/setup.php
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database config
define('DB_HOST', '127.0.0.1');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'petcare_db');
define('DB_CHARSET', 'utf8mb4');

$output = [];

try {
    // Connect to MySQL (without database)
    $output[] = "🔌 Connecting to MySQL...";
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    $output[] = "✅ Connected to MySQL successfully!";

    // Create database
    $output[] = "<br>📦 Creating database '" . DB_NAME . "'...";
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE `" . DB_NAME . "`");
    $output[] = "✅ Database created/selected!";

    // Read and execute schema.sql
    $output[] = "<br>📝 Reading schema.sql...";
    $schemaFile = __DIR__ . '/database/schema.sql';
    if (!file_exists($schemaFile)) {
        throw new Exception("File not found: $schemaFile");
    }
    
    $schema = file_get_contents($schemaFile);
    $output[] = "✅ Schema file loaded!";
    
    $output[] = "🔨 Creating tables...";
    // Split by semicolon and execute each statement
    $statements = array_filter(array_map('trim', explode(';', $schema)));
    foreach ($statements as $statement) {
        if (!empty($statement) && stripos($statement, 'USE ') === false && stripos($statement, 'CREATE DATABASE') === false) {
            try {
                $pdo->exec($statement);
            } catch (PDOException $e) {
                // Ignore table/index already exists errors
                if ($e->getCode() != '42S01' && $e->getCode() != '42000') {
                    throw $e;
                }
            }
        }
    }
    $output[] = "✅ Tables created/checked successfully!";

    // Insert demo posts
    $output[] = "<br>📰 Inserting demo posts...";
    $seedFile = __DIR__ . '/database/seed_posts.sql';
    if (file_exists($seedFile)) {
        $seed = file_get_contents($seedFile);
        $statements = array_filter(array_map('trim', explode(';', $seed)));
        foreach ($statements as $statement) {
            if (!empty($statement) && stripos($statement, 'USE ') === false) {
                try {
                    $pdo->exec($statement);
                } catch (PDOException $e) {
                    // Ignore duplicate entry errors
                    if ($e->getCode() != 23000) {
                        throw $e;
                    }
                }
            }
        }
        $output[] = "✅ Demo posts inserted!";
    } else {
        $output[] = "⚠️ seed_posts.sql not found, skipping demo data";
    }

    // Create admin user
    $output[] = "<br>👤 Creating admin user...";
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = 'admin@petcare.com'");
    $stmt->execute();
    $exists = $stmt->fetchColumn();

    if ($exists == 0) {
        $sql = "INSERT INTO users (name, email, password_hash, role, status, created_at) 
                VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'Admin',
            'admin@petcare.com',
            '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password: "password"
            'admin',
            'active'
        ]);
        $output[] = "✅ Admin user created!";
        $output[] = "   📧 Email: admin@petcare.com";
        $output[] = "   🔑 Password: password";
    } else {
        $output[] = "ℹ️ Admin user already exists, skipped";
    }

    // Check data
    $output[] = "<br>📊 Checking data...";
    $stmt = $pdo->query("SELECT COUNT(*) FROM posts");
    $postCount = $stmt->fetchColumn();
    $output[] = "   Posts: $postCount";

    $stmt = $pdo->query("SELECT COUNT(*) FROM post_comments");
    $commentCount = $stmt->fetchColumn();
    $output[] = "   Comments: $commentCount";

    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    $userCount = $stmt->fetchColumn();
    $output[] = "   Users: $userCount";

    // Success message
    $output[] = "<br><strong style='color: green; font-size: 20px;'>🎉 Setup completed successfully!</strong>";
    $output[] = "<br>📌 <strong>Next steps:</strong>";
    $output[] = "1. Delete this file (setup.php) for security";
    $output[] = "2. Visit: <a href='BE/public/posts' target='_blank'>http://localhost/LTW/BE/public/posts</a>";
    $output[] = "3. Login admin: <a href='BE/public/login' target='_blank'>http://localhost/LTW/BE/public/login</a>";
    $output[] = "4. Admin panel: <a href='BE/public/admin/posts' target='_blank'>http://localhost/LTW/BE/public/admin/posts</a>";

} catch (Exception $e) {
    $output[] = "<br><strong style='color: red;'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</strong>";
    $output[] = "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Setup - LTW Project</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 800px;
            width: 100%;
            padding: 40px;
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
        .output {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            border-radius: 8px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            line-height: 1.8;
            max-height: 500px;
            overflow-y: auto;
        }
        .output div {
            margin-bottom: 8px;
        }
        a {
            color: #667eea;
            text-decoration: none;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
        }
        pre {
            background: #fff;
            padding: 10px;
            border-radius: 4px;
            overflow-x: auto;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Database Setup</h1>
        <p class="subtitle">LTW Project - Automated Database Installer</p>
        <div class="output">
            <?php foreach ($output as $line): ?>
                <div><?= $line ?></div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
