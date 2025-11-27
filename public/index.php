<?php
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

// Posts (Public)
$router->get('/posts', 'PostController@index');
$router->get('/posts/show', 'PostController@show');
$router->post('/posts/comment', 'PostController@submitComment');

// Admin routes
$router->get('/admin', 'Admin\\PageController@dashboard');

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
$router->post('/admin/comments/spam', 'Admin\\CommentController@spam');
$router->post('/admin/comments/delete', 'Admin\\CommentController@delete');

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
