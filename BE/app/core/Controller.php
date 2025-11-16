<?php
namespace Core;

class Controller
{
    protected function view(string $view, array $data = []): void
    {
        $viewFile = __DIR__ . '/../views/' . str_replace('.', '/', $view) . '.php';
        extract($data);
        $title = $data['title'] ?? APP_NAME;
        $layout = $data['_layout'] ?? 'main';
        $content = function () use ($viewFile, $data) {
            extract($data);
            require $viewFile;
        };
        $layoutFile = __DIR__ . '/../views/layouts/' . $layout . '.php';
        if (!is_file($layoutFile)) {
            $layoutFile = __DIR__ . '/../views/layouts/main.php';
        }
        require $layoutFile;
    }
}
