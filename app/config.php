<?php
// Application config

define('APP_NAME', 'PetCare');

// Database config (update to your local settings)
const DB_HOST = '127.0.0.1';
const DB_NAME = 'petcare_db';
const DB_USER = 'root';
const DB_PASS = '';
const DB_CHARSET = 'utf8mb4';

// Base URL auto detection
if (!defined('BASE_URL')) {
    $base = null;
    $docRoot = isset($_SERVER['DOCUMENT_ROOT']) ? str_replace('\\', '/', rtrim($_SERVER['DOCUMENT_ROOT'], '/')) : '';
    $publicReal = str_replace('\\', '/', realpath(__DIR__ . '/../public') ?: '');
    if ($docRoot && $publicReal && str_starts_with($publicReal, $docRoot)) {
        $base = substr($publicReal, strlen($docRoot));
        if ($base === false || $base === '') { $base = '/'; }
        else { if ($base[0] !== '/') { $base = '/' . $base; } if ($base[strlen($base)-1] !== '/') { $base .= '/'; } }
    }
    if ($base === null) {
        $scriptName = isset($_SERVER['SCRIPT_NAME']) ? str_replace('\\', '/', $_SERVER['SCRIPT_NAME']) : '/index.php';
        if (basename($scriptName) !== 'index.php' && isset($_SERVER['PHP_SELF']) && str_contains($_SERVER['PHP_SELF'], '/index.php')) {
            $scriptName = $_SERVER['PHP_SELF'];
        }
        $base = rtrim(dirname($scriptName), '/');
        if ($base === '' || $base === '.') { $base = '/'; }
        if ($base !== '/') { $base .= '/'; }
    }
    define('BASE_URL', $base);
}

// Session
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);
session_start();
