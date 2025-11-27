<?php
// Asset proxy for Tabler assets located under FE/dashboard/dist
// Usage: /vendor-asset.php?f=dashboard/dist/css/tabler.min.css

$rel = $_GET['f'] ?? '';
if (!$rel) { http_response_code(400); exit('Missing f'); }

$publicDir = realpath(__DIR__);
$projectRoot = realpath(__DIR__ . '/..'); // BE/
$feDashboard = realpath($projectRoot . '/../FE/dashboard/dist'); // FE/dashboard/dist
$legacyDashboard = realpath($projectRoot . '/../dashboard/dist'); // fallback if still present

$path = null;
if ($feDashboard && is_dir($feDashboard)) {
    $candidate = realpath($projectRoot . '/../FE/' . $rel);
    if ($candidate && str_starts_with($candidate, $feDashboard)) {
        $path = $candidate;
    }
}
if (!$path && $legacyDashboard && is_dir($legacyDashboard)) {
    $candidate = realpath($projectRoot . '/../' . $rel);
    if ($candidate && str_starts_with($candidate, $legacyDashboard)) {
        $path = $candidate;
    }
}

if (!$path || !is_file($path)) { http_response_code(404); exit('Not Found'); }

$ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
$mime = [
    'css' => 'text/css; charset=utf-8',
    'js'  => 'application/javascript; charset=utf-8',
    'map' => 'application/json; charset=utf-8',
    'svg' => 'image/svg+xml',
    'png' => 'image/png',
    'jpg' => 'image/jpeg',
    'jpeg'=> 'image/jpeg',
    'gif' => 'image/gif',
    'webp'=> 'image/webp',
    'woff'=> 'font/woff',
    'woff2'=>'font/woff2',
    'ttf' => 'font/ttf',
    'eot' => 'application/vnd.ms-fontobject',
][$ext] ?? 'application/octet-stream';

header('Content-Type: ' . $mime);
header('Cache-Control: public, max-age=31536000, immutable');
readfile($path);
