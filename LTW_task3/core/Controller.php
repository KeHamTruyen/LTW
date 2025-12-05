<?php
namespace Core;

class Controller
{
    protected function view(string $view, array $data = [], string $title = null, string $layout = 'public'): void
    {
        $viewFile = __DIR__ . '/../views/' . str_replace('.', '/', $view) . '.php';
        extract($data);
        $title = $title ?? APP_NAME;
        $content = function () use ($viewFile, $data) {
            extract($data);
            require $viewFile;
        };
        $layoutFile = __DIR__ . '/../views/layouts/' . $layout . '.php';
        if (!is_file($layoutFile)) {
            $layoutFile = __DIR__ . '/../views/layouts/public.php';
        }
        require $layoutFile;
    }
}
