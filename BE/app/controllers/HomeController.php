<?php
namespace App\Controllers;

use Core\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        $this->view('home.index', [
            'title' => 'Trang chủ - ' . APP_NAME,
            'message' => 'Khởi tạo kiến trúc BE/FE đã hoàn tất.',
        ]);
    }
}
