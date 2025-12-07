<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\Product;
use App\Models\Post;
use App\Models\Home;

class HomeController extends Controller
{
    public function index(): void
    {
        // Lấy nội dung trang home từ database
        $homeData = Home::get();
        
        // Lấy sản phẩm nổi bật
        $featuredProducts = Product::getFeatured(12);
        
        // Lấy nhiều bài viết mới nhất 
        $latestPosts = Post::getAll([
            'status' => 'published',
            'limit' => 12,
            'offset' => 0
        ]);
        
        // Parse service combos từ JSON
        $serviceCombos = [];
        if ($homeData && !empty($homeData['service_combos'])) {
            $serviceCombos = json_decode($homeData['service_combos'], true) ?: [];
        }
        
        $this->view('home.index', [
            'title' => 'Trang chủ - ' . APP_NAME,
            'hideBlogHero' => true,
            'activeMenu' => 'home',
            'homeData' => $homeData,
            'serviceCombos' => $serviceCombos,
            'featuredProducts' => $featuredProducts,
            'latestPosts' => $latestPosts,
        ], null, 'public');
    }
}
