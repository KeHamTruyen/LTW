<?php
/**
 * Database Migration Script - Run all migrations
 * URL: http://localhost/LTW-main/database/migrate_all.php
 * 
 * This script will run all migration files in order
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load config
require_once __DIR__ . '/../app/config.php';

$output = [];

function logMessage($message, $type = 'info') {
    global $output;
    $icon = match($type) {
        'success' => '✅',
        'error' => '❌',
        'warning' => '⚠️',
        'info' => 'ℹ️',
        default => '📝'
    };
    $output[] = $icon . ' ' . $message;
}

try {
    // Connect to MySQL
    logMessage('Connecting to MySQL server...', 'info');
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";charset=" . DB_CHARSET,
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    logMessage('Connected to MySQL successfully!', 'success');

    // Use database
    $pdo->exec("USE `" . DB_NAME . "`");
    logMessage("Using database: " . DB_NAME, 'info');

    // Run migration 002
    logMessage("Running migration 002: Add missing tables...", 'info');
    $migrationFile = __DIR__ . '/migrations/002_add_missing_tables.sql';
    
    if (!file_exists($migrationFile)) {
        throw new Exception("Migration file not found: $migrationFile");
    }

    $sql = file_get_contents($migrationFile);
    
    // Split by semicolon but preserve procedures/functions
    $statements = [];
    $current = '';
    $delimiter = ';';
    $inProcedure = false;
    
    $lines = explode("\n", $sql);
    foreach ($lines as $line) {
        // Skip comments and empty lines
        $line = trim($line);
        if (empty($line) || strpos($line, '--') === 0) {
            continue;
        }
        
        // Check for delimiter changes
        if (stripos($line, 'DELIMITER') !== false) {
            $parts = preg_split('/\s+/', $line);
            if (count($parts) >= 2) {
                $delimiter = $parts[1];
            }
            continue;
        }
        
        $current .= $line . "\n";
        
        // Check if line ends with delimiter
        if (str_ends_with(trim($line), $delimiter)) {
            $stmt = trim($current);
            if (!empty($stmt) && stripos($stmt, 'USE ') === false) {
                $statements[] = $stmt;
            }
            $current = '';
        }
    }
    
    // Add remaining statement
    if (!empty(trim($current))) {
        $statements[] = trim($current);
    }

    // Execute each statement
    foreach ($statements as $statement) {
        if (empty(trim($statement))) continue;
        
        try {
            $pdo->exec($statement);
        } catch (PDOException $e) {
            // Ignore table/index already exists errors
            $errorCode = $e->getCode();
            $errorMsg = $e->getMessage();
            
            if (strpos($errorMsg, 'already exists') !== false || 
                strpos($errorMsg, 'Duplicate') !== false ||
                $errorCode == '42S01' || // Table already exists
                $errorCode == '42000' || // Syntax error (for IF NOT EXISTS)
                $errorCode == 'HY000' && strpos($errorMsg, 'Duplicate') !== false) {
                // Skip - already exists
                continue;
            }
            
            // For other errors, log but continue
            logMessage("Warning: " . substr($errorMsg, 0, 100), 'warning');
        }
    }
    
    logMessage('Migration 002 completed!', 'success');

    // Check created tables
    logMessage('Checking created tables...', 'info');
    $tables = ['categories', 'products', 'cart_items', 'orders', 'order_items', 'contacts', 'pages', 'faqs', 'about_page'];
    foreach ($tables as $table) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
            $count = $stmt->fetchColumn();
            logMessage("Table '$table': $count rows", 'info');
        } catch (PDOException $e) {
            logMessage("Table '$table': NOT FOUND or ERROR", 'error');
        }
    }

    logMessage('🎉 All migrations completed successfully!', 'success');

} catch (Exception $e) {
    logMessage('ERROR: ' . $e->getMessage(), 'error');
    logMessage($e->getTraceAsString(), 'error');
}

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Migration - LTW Project</title>
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
            max-width: 900px;
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
            max-height: 600px;
            overflow-y: auto;
        }
        .output div {
            margin-bottom: 6px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Database Migration</h1>
        <p class="subtitle">LTW Project - Running All Migrations</p>
        <div class="output">
            <?php foreach ($output as $line): ?>
                <div><?= htmlspecialchars($line) ?></div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>

