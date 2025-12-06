<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\FAQ;
use App\Models\Home;

class FAQController extends Controller
{
    public function index(): void
    {
        $page = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
        $perPage = 10;
        $offset = ($page - 1) * $perPage;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        $filters = [
            'limit' => $perPage,
            'offset' => $offset,
            'status' => 'active',
        ];

        if (!empty($search)) {
            $filters['search'] = $search;
        }

        $faqs = FAQ::getAll($filters);
        $total = FAQ::count($filters);
        $totalPages = ceil($total / $perPage);
        $homeData = Home::get();

        $this->view('faq.index', [
            'title' => 'Hỏi & Đáp - ' . APP_NAME,
            'faqs' => $faqs,
            'search' => $search,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
            'homeData' => $homeData,
            'activeMenu' => 'faq',
            'hideBlogHero' => true,
        ], null, 'public');
    }
}

