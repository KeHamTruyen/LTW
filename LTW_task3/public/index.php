<?php

// 1. Config & Session
require_once __DIR__ . '/../config.php';

ini_set('session.gc_maxlifetime', 1800);
session_set_cookie_params(1800);
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Load Core
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Router.php';
require_once __DIR__ . '/../core/Auth.php';

use Core\Router;

// 3. AUTOLOADER (Cấu hình cho cấu trúc thư mục của bạn)
$baseDir = __DIR__ . '/../';

spl_autoload_register(function ($class) use ($baseDir) {
    $class = ltrim($class, '\\');

    // Ánh xạ Namespace -> Thư mục thực tế
    $map = [
        'App\\Controllers\\' => 'controllers/', // Tìm trong thư mục controllers/
        'App\\Models\\' => 'models/',      // Tìm trong thư mục models/
        'Core\\' => 'core/',        // Tìm trong thư mục core/
    ];

    foreach ($map as $namespace => $folder) {
        if (str_starts_with($class, $namespace)) {
            $className = substr($class, strlen($namespace));
            $file = $baseDir . $folder . str_replace('\\', '/', $className) . '.php';
            if (file_exists($file)) {
                require_once $file;
                return;
            }
        }
    }
});

$router = new Router();

// --- ĐỊNH TUYẾN (ROUTES) ---

// Auth
$router->get('/login', 'AuthController@login');
$router->post('/login', 'AuthController@authenticate');
$router->get('/logout', 'AuthController@logout');

// Public
$router->get('/', 'HomeController@index');
$router->get('/products', 'ProductController@index');
$router->get('/products/show', 'ProductController@show');

// Cart
$router->get('/cart', 'CartController@index');
$router->post('/cart/add', 'CartController@add');
$router->post('/cart/update', 'CartController@update');
$router->post('/cart/checkout', 'CartController@checkout');

// Orders
$router->get('/orders', 'OrderController@index');
$router->get('/orders/show', 'OrderController@show');

// Admin Routes
$router->get('/admin', 'AdminProductController@index');
$router->get('/admin/products', 'AdminProductController@index');
$router->get('/admin/products/create', 'AdminProductController@create');
$router->post('/admin/products/store', 'AdminProductController@store');
$router->get('/admin/products/edit', 'AdminProductController@edit');
$router->post('/admin/products/update', 'AdminProductController@update');
$router->post('/admin/products/delete', 'AdminProductController@delete');
$router->get('/admin/orders', 'AdminOrderController@index');
$router->get('/admin/orders/show', 'AdminOrderController@show');
$router->post('/admin/orders/update-status', 'AdminOrderController@updateStatus');

// Dispatch
try {
    $router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
} catch (Exception $e) {
    echo "Lỗi hệ thống: " . $e->getMessage();
}