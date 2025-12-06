<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Home;

class ProductController extends Controller
{
    public function index(): void
    {
        $page = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
        $perPage = 12;
        $offset = ($page - 1) * $perPage;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $categoryId = isset($_GET['category']) ? (int)$_GET['category'] : 0;

        $filters = [
            'limit' => $perPage,
            'offset' => $offset,
            'status' => 'published',
        ];

        if (!empty($search)) {
            $filters['search'] = $search;
        }

        if ($categoryId > 0) {
            $filters['category_id'] = $categoryId;
        }

        $products = Product::getAll($filters);
        $total = Product::count($filters);
        $totalPages = ceil($total / $perPage);
        $categories = Category::getAll();
        $homeData = Home::get();

        $this->view('products.index', [
            'title' => 'Sản phẩm - ' . APP_NAME,
            'products' => $products,
            'categories' => $categories,
            'search' => $search,
            'categoryId' => $categoryId,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
            'activeMenu' => 'shop',
            'hideBlogHero' => true,
            'homeData' => $homeData,
        ], null, 'public');
    }

    public function show(): void
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        if ($id <= 0) {
            http_response_code(404);
            echo '404 - Sản phẩm không tồn tại';
            exit;
        }

        $product = Product::findById($id);

        if (!$product || $product['status'] !== 'published') {
            http_response_code(404);
            echo '404 - Sản phẩm không tồn tại';
            exit;
        }

        // Get related products
        $relatedProducts = Product::getAll([
            'limit' => 4,
            'status' => 'published',
            'category_id' => $product['category_id'],
        ]);
        
        $homeData = Home::get();

        $this->view('products.show', [
            'title' => htmlspecialchars($product['name']) . ' - ' . APP_NAME,
            'product' => $product,
            'relatedProducts' => $relatedProducts,
            'activeMenu' => 'shop',
            'hideBlogHero' => true,
            'homeData' => $homeData,
        ], null, 'public');
    }
}

