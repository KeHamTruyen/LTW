<?php
namespace Core;

class Router
{
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];
    private string $basePath = '';

    public function __construct()
    {
        $base = parse_url(BASE_URL, PHP_URL_PATH) ?: '';
        $this->basePath = rtrim($base, '/');
    }

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
        $path = parse_url($path, PHP_URL_PATH) ?: '/';
        $path = preg_replace('#/+#','/',$path);
        if (str_ends_with($path, '/index.php')) {
            $path = substr($path, 0, -strlen('/index.php'));
        }
        if (preg_match('#/(index\.php)(/|$)#i', $path)) {
            $path = preg_replace('#^/index\.php/?#i','/',$path);
            $path = preg_replace('#/(index\.php)/#i','/',$path);
        }
        if ($this->basePath !== '' && $this->basePath !== '/') {
            if (str_starts_with($path, $this->basePath)) {
                $path = substr($path, strlen($this->basePath));
            }
        }
        $path = '/' . trim($path, '/');
        return $path === '//' ? '/' : $path;
    }

    public function dispatch(string $method, string $uri): void
    {
        $path = $this->normalize($uri);
        $handler = $this->routes[$method][$path] ?? null;
        if (!$handler) {
            http_response_code(404);
            header('Content-Type: text/html; charset=utf-8');
            $tablerCss = BASE_URL . 'vendor-asset.php?f=dashboard/dist/css/tabler.min.css';
            echo '<!doctype html><html lang="vi"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>404 Not Found</title>';
            echo '<link rel="stylesheet" href="' . htmlspecialchars($tablerCss, ENT_QUOTES) . '">';
            echo '</head><body>';
            echo '<div class="page"><div class="container-xl" style="margin-top:4rem">';
            echo '<div class="card"><div class="card-body">';
            echo '<h1 class="h2">404 - Không tìm thấy trang</h1><p>Đường dẫn bạn truy cập không tồn tại.</p>';
            echo '<a class="btn btn-primary" href="' . htmlspecialchars(BASE_URL, ENT_QUOTES) . '">Về trang chủ</a>';
            echo '</div></div></div></div>';
            echo '</body></html>';
            return;
        }

        [$controllerName, $action] = explode('@', $handler);
        $controllerClass = '\\App\\Controllers\\' . $controllerName;
        $file = __DIR__ . '/../controllers/' . str_replace('\\\\', '/', $controllerName) . '.php';
        if (is_file($file)) { require_once $file; }

        if (!class_exists($controllerClass)) { http_response_code(500); echo 'Controller not found.'; return; }
        $controller = new $controllerClass();
        if (!method_exists($controller, $action)) { http_response_code(500); echo 'Action not found.'; return; }
        $controller->$action();
    }
}
