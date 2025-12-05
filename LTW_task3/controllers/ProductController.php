<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * List all products (public)
     */
    public function index()
    {
        // Pagination
        $page = isset($_GET['p']) ? max(1, (int) $_GET['p']) : 1;
        $perPage = 12;
        $offset = ($page - 1) * $perPage;

        // Search
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        // Filters
        $filters = [
            'limit' => $perPage,
            'offset' => $offset,
        ];

        if (!empty($search)) {
            $filters['search'] = $search;
        }

        // Get products and total count
        $products = Product::getAll($filters);
        $totalProducts = Product::count($filters);
        $totalPages = ceil($totalProducts / $perPage);

        // Render view with public layout
        $this->view('products/index', [
            'products' => $products,
            'search' => $search,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalProducts' => $totalProducts,
        ], 'Sản phẩm - ' . APP_NAME, 'public');
    }

    /**
     * Show product detail
     */
    public function show()
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if ($id <= 0) {
            http_response_code(404);
            echo '404 - Sản phẩm không tồn tại';
            exit;
        }

        $product = Product::findById($id);

        if (!$product) {
            http_response_code(404);
            echo '404 - Sản phẩm không tồn tại';
            exit;
        }

        // Render view
        $this->view('products/show', [
            'product' => $product,
        ], htmlspecialchars($product['name']) . ' - ' . APP_NAME, 'public');
    }
}
