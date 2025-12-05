<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\Page;

class PageController extends Controller
{
    public function show(): void
    {
        $slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';
        
        if (empty($slug)) {
            http_response_code(404);
            echo '404 - Trang không tồn tại';
            exit;
        }

        $page = Page::findBySlug($slug);
        
        if (!$page) {
            http_response_code(404);
            echo '404 - Trang không tồn tại';
            exit;
        }

        $this->view('page.show', [
            'title' => htmlspecialchars($page['title']) . ' - ' . APP_NAME,
            'page' => $page,
        ]);
    }
}

