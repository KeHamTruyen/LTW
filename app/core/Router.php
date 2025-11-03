<?php
namespace Core;

class Router
{
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public function get(string $path, string $handler): void
    {
        $this->routes['GET'][$this->normalize($path)] = $handler;
    }

    public function post(string $path, string $handler): void
    {
        $this->routes['POST'][$this->normalize($path)] = $handler;
    }

    private function normalize(string $path): string
    {
        $path = '/' . trim(parse_url($path, PHP_URL_PATH), '/');
        return $path === '//' ? '/' : $path;
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = $this->normalize($uri);
        $handler = $this->routes[$method][$path] ?? null;
        if (!$handler) {
            http_response_code(404);
            echo '404 Not Found';
            return;
        }

    [$controllerName, $action] = explode('@', $handler);
    // Support Admin\\UserController style namespaced controllers
    $controllerClass = '\\App\\Controllers\\' . $controllerName;

        // Load controller file if not autoloaded
    $file = __DIR__ . '/../controllers/' . str_replace('\\\\', '/', $controllerName) . '.php';
        if (is_file($file)) {
            require_once $file;
        }

        if (!class_exists($controllerClass)) {
            http_response_code(500);
            echo 'Controller not found.';
            return;
        }

        $controller = new $controllerClass();
        if (!method_exists($controller, $action)) {
            http_response_code(500);
            echo 'Action not found.';
            return;
        }

        $controller->$action();
    }
}
