<?php
// Basic front controller
require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/Controller.php';
require_once __DIR__ . '/../app/core/Router.php';
require_once __DIR__ . '/../app/core/Auth.php';

use Core\Router;

$baseDir = __DIR__ . '/../app/';
// Simple autoloader for App\* and Core\*
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

// Routes
$router->get('/', 'HomeController@index');
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'AuthController@showRegister');
$router->post('/register', 'AuthController@register');
$router->get('/logout', 'AuthController@logout');

$router->get('/profile', 'ProfileController@edit');
$router->post('/profile', 'ProfileController@update');
$router->post('/profile/password', 'ProfileController@updatePassword');

// Admin routes (use query param for actions with id)
$router->get('/admin/users', 'Admin\\UserController@index');
$router->post('/admin/users/status', 'Admin\\UserController@setStatus');
$router->post('/admin/users/role', 'Admin\\UserController@setRole');
$router->post('/admin/users/reset', 'Admin\\UserController@resetPassword');

// Dispatch
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
