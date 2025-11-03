<?php
namespace Core;

class Controller
{
    protected function view(string $view, array $data = []): void
    {
        $viewFile = __DIR__ . '/../views/' . str_replace('.', '/', $view) . '.php';
        extract($data);
        $title = $data['title'] ?? APP_NAME;
        $content = function () use ($viewFile, $data) {
            extract($data);
            require $viewFile;
        };
        require __DIR__ . '/../views/layouts/main.php';
    }
}
