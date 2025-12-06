<?php
// Set timezone
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Start session
session_start();

// Generate CSRF token if not exists
if (!isset($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

// Front controller (BE)
require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/Controller.php';
require_once __DIR__ . '/../app/core/Router.php';
require_once __DIR__ . '/../app/core/Auth.php';
require_once __DIR__ . '/../app/core/Assets.php';

use Core\Router;

$headers = [
    'X-Frame-Options' => 'SAMEORIGIN',
    'X-Content-Type-Options' => 'nosniff',
    'Referrer-Policy' => 'strict-origin-when-cross-origin',
];
foreach ($headers as $k => $v) { header($k . ': ' . $v); }

$baseDir = __DIR__ . '/../app/';
spl_autoload_register(function ($class) use ($baseDir) {
    $class = ltrim($class, '\\');
    if (str_starts_with($class, 'App\\')) {
        $relative = str_replace('App\\', '', $class);
        $path = $baseDir . str_replace('\\', '/', $relative) . '.php';
        if (is_file($path)) require_once $path;
    } elseif (str_starts_with($class, 'Core\\')) {
        $relative = str_replace('Core\\', 'core\\', $class);
        $path = $baseDir . str_replace('\\', '/', $relative) . '.php';
        if (is_file($path)) require_once $path;
    }
});

$router = new Router();

// Public routes
$router->get('/', 'HomeController@index');
$router->get('/login', 'AuthController@login');
$router->post('/login', 'AuthController@doLogin');
$router->get('/logout', 'AuthController@logout');

// Contact
$router->get('/contact', 'ContactController@index');
$router->post('/contact/submit', 'ContactController@submit');

// Posts (Public)
$router->get('/posts', 'PostController@index');
$router->get('/posts/show', 'PostController@show');
$router->post('/posts/comment', 'PostController@submitComment');

// Admin routes
$router->get('/admin', 'Admin\\PageController@dashboard');

// Admin - Upload
$router->post('/admin/upload/image', 'Admin\\UploadController@image');

// Admin - Posts Management
$router->get('/admin/posts', 'Admin\\PostController@index');
$router->get('/admin/posts/create', 'Admin\\PostController@create');
$router->post('/admin/posts/store', 'Admin\\PostController@store');
$router->get('/admin/posts/edit', 'Admin\\PostController@edit');
$router->post('/admin/posts/update', 'Admin\\PostController@update');
$router->post('/admin/posts/delete', 'Admin\\PostController@delete');

// Admin - Comments Management
$router->get('/admin/comments', 'Admin\\CommentController@index');
$router->post('/admin/comments/approve', 'Admin\\CommentController@approve');
$router->post('/admin/comments/reject', 'Admin\\CommentController@reject');
$router->post('/admin/comments/delete', 'Admin\\CommentController@delete');

// Admin - Home Management
$router->get('/admin/home', 'Admin\\HomeController@index');
$router->post('/admin/home/update', 'Admin\\HomeController@update');

// Admin - Contacts Management
$router->get('/admin/contacts', 'Admin\\ContactController@index');
$router->get('/admin/contacts/show', 'Admin\\ContactController@show');
$router->post('/admin/contacts/update-status', 'Admin\\ContactController@updateStatus');
$router->post('/admin/contacts/reply', 'Admin\\ContactController@reply');
$router->post('/admin/contacts/delete', 'Admin\\ContactController@delete');

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
