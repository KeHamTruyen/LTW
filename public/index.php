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
$router->get('/register', 'AuthController@register');
$router->post('/register', 'AuthController@doRegister');
$router->get('/logout', 'AuthController@logout');

// About
$router->get('/about', 'AboutController@index');

// Products
$router->get('/shop', 'ProductController@index');
$router->get('/products', 'ProductController@index');
$router->get('/products/show', 'ProductController@show');

// Cart
$router->get('/cart', 'CartController@index');
$router->post('/cart/add', 'CartController@add');
$router->post('/cart/update', 'CartController@update');
$router->post('/cart/remove', 'CartController@remove');
$router->get('/cart/checkout', 'CartController@checkout');

// Orders
$router->get('/orders', 'OrderController@index');
$router->get('/orders/show', 'OrderController@show');
$router->post('/orders/checkout', 'OrderController@checkout');

// Profile
$router->get('/profile', 'ProfileController@index');
$router->get('/profile/edit', 'ProfileController@edit');
$router->post('/profile/update', 'ProfileController@update');
$router->get('/profile/change-password', 'ProfileController@changePassword');
$router->post('/profile/update-password', 'ProfileController@updatePassword');

// FAQ
$router->get('/faq', 'FAQController@index');

// Page
$router->get('/page/show', 'PageController@show');

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

// Admin - About Management
$router->get('/admin/about', 'Admin\\AboutController@index');
$router->post('/admin/about/update', 'Admin\\AboutController@update');

// Admin - Products Management
$router->get('/admin/products', 'Admin\\ProductController@index');
$router->get('/admin/products/create', 'Admin\\ProductController@create');
$router->post('/admin/products/store', 'Admin\\ProductController@store');
$router->get('/admin/products/edit', 'Admin\\ProductController@edit');
$router->post('/admin/products/update', 'Admin\\ProductController@update');
$router->post('/admin/products/delete', 'Admin\\ProductController@delete');

// Admin - Orders Management
$router->get('/admin/orders', 'Admin\\OrderController@index');
$router->get('/admin/orders/show', 'Admin\\OrderController@show');
$router->post('/admin/orders/update-status', 'Admin\\OrderController@updateStatus');

// Admin - Users Management
$router->get('/admin/users', 'Admin\\UserController@index');
$router->get('/admin/users/show', 'Admin\\UserController@show');
$router->post('/admin/users/update-status', 'Admin\\UserController@updateStatus');
$router->post('/admin/users/reset-password', 'Admin\\UserController@resetPassword');
$router->post('/admin/users/delete', 'Admin\\UserController@delete');

// Admin - FAQ Management
$router->get('/admin/faqs', 'Admin\\FAQController@index');
$router->get('/admin/faqs/create', 'Admin\\FAQController@create');
$router->post('/admin/faqs/store', 'Admin\\FAQController@store');
$router->get('/admin/faqs/edit', 'Admin\\FAQController@edit');
$router->post('/admin/faqs/update', 'Admin\\FAQController@update');
$router->post('/admin/faqs/delete', 'Admin\\FAQController@delete');

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

// Admin - Pages Management
$router->get('/admin/pages', 'Admin\\PageController@index');
$router->get('/admin/pages/create', 'Admin\\PageController@create');
$router->post('/admin/pages/store', 'Admin\\PageController@store');
$router->get('/admin/pages/edit', 'Admin\\PageController@edit');
$router->post('/admin/pages/update', 'Admin\\PageController@update');
$router->post('/admin/pages/delete', 'Admin\\PageController@delete');

// Admin - Contacts Management
$router->get('/admin/contacts', 'Admin\\ContactController@index');
$router->get('/admin/contacts/show', 'Admin\\ContactController@show');
$router->post('/admin/contacts/update-status', 'Admin\\ContactController@updateStatus');
$router->post('/admin/contacts/reply', 'Admin\\ContactController@reply');
$router->post('/admin/contacts/delete', 'Admin\\ContactController@delete');

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
