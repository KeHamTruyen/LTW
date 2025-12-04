<?php
namespace App\Controllers;

use Core\Controller;
use Core\Auth;
use App\Models\Product;

class AdminProductController extends Controller
{
    public function __construct()
    {
        Auth::requireAdmin();
    }

    /**
     * List all products (admin)
     */
    public function index()
    {
        // Pagination
        $page = isset($_GET['p']) ? max(1, (int) $_GET['p']) : 1;
        $perPage = 20;
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

        // Render view with admin layout
        $this->view('admin/products/index', [
            'products' => $products,
            'search' => $search,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalProducts' => $totalProducts,
        ], 'Quản lý sản phẩm - ' . APP_NAME, 'admin');
    }

    /**
     * Show create product form
     */
    public function create()
    {
        $this->view('admin/products/create', [], 'Thêm sản phẩm - ' . APP_NAME, 'admin');
    }

    /**
     * Store new product
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit;
        }

        // CSRF protection
        if (!isset($_POST['csrf']) || !isset($_SESSION['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
            $_SESSION['flash_error'] = 'Token bảo mật không hợp lệ';
            header('Location: ' . BASE_URL . 'admin/products/create');
            exit;
        }

        // Validate input
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $price = isset($_POST['price']) ? (float) $_POST['price'] : 0;
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $stockQuantity = isset($_POST['stock_quantity']) ? (int) $_POST['stock_quantity'] : 0;

        $errors = [];

        if (empty($name) || mb_strlen($name) < 2) {
            $errors[] = 'Tên sản phẩm phải có ít nhất 2 ký tự';
        }

        if ($price <= 0) {
            $errors[] = 'Giá sản phẩm phải lớn hơn 0';
        }

        if ($stockQuantity < 0) {
            $errors[] = 'Số lượng tồn kho không được âm';
        }

        if (!empty($errors)) {
            $_SESSION['flash_error'] = implode('<br>', $errors);
            header('Location: ' . BASE_URL . 'admin/products/create');
            exit;
        }

        // Handle image upload
        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../public/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $imagePath = $fileName;
            }
        }

        // Create product
        $productData = [
            'name' => $name,
            'price' => $price,
            'description' => $description,
            'image' => $imagePath,
            'stock_quantity' => $stockQuantity,
        ];

        try {
            Product::create($productData);
            $_SESSION['flash_success'] = 'Thêm sản phẩm thành công';
            header('Location: ' . BASE_URL . 'admin/products');
            exit;
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'Có lỗi xảy ra khi thêm sản phẩm';
            error_log('Product creation error: ' . $e->getMessage());
            header('Location: ' . BASE_URL . 'admin/products/create');
            exit;
        }
    }

    /**
     * Show edit product form
     */
    public function edit()
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

        $this->view('admin/products/edit', [
            'product' => $product,
        ], 'Sửa sản phẩm - ' . APP_NAME, 'admin');
    }

    /**
     * Update product
     */
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit;
        }

        // CSRF protection
        if (!isset($_POST['csrf']) || !isset($_SESSION['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
            $_SESSION['flash_error'] = 'Token bảo mật không hợp lệ';
            header('Location: ' . BASE_URL . 'admin/products');
            exit;
        }

        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

        if ($id <= 0) {
            $_SESSION['flash_error'] = 'ID sản phẩm không hợp lệ';
            header('Location: ' . BASE_URL . 'admin/products');
            exit;
        }

        // Validate input
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $price = isset($_POST['price']) ? (float) $_POST['price'] : 0;
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $stockQuantity = isset($_POST['stock_quantity']) ? (int) $_POST['stock_quantity'] : 0;

        $errors = [];

        if (empty($name) || mb_strlen($name) < 2) {
            $errors[] = 'Tên sản phẩm phải có ít nhất 2 ký tự';
        }

        if ($price <= 0) {
            $errors[] = 'Giá sản phẩm phải lớn hơn 0';
        }

        if ($stockQuantity < 0) {
            $errors[] = 'Số lượng tồn kho không được âm';
        }

        if (!empty($errors)) {
            $_SESSION['flash_error'] = implode('<br>', $errors);
            header('Location: ' . BASE_URL . 'admin/products/edit?id=' . $id);
            exit;
        }

        // Handle image upload
        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../public/uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileName = uniqid() . '_' . basename($_FILES['image']['name']);
            $targetPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $imagePath = $fileName;
            }
        }

        // Update product
        $productData = [
            'name' => $name,
            'price' => $price,
            'description' => $description,
            'stock_quantity' => $stockQuantity,
        ];

        if ($imagePath) {
            $productData['image'] = $imagePath;
        }

        try {
            Product::update($id, $productData);
            $_SESSION['flash_success'] = 'Cập nhật sản phẩm thành công';
            header('Location: ' . BASE_URL . 'admin/products');
            exit;
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'Có lỗi xảy ra khi cập nhật sản phẩm';
            error_log('Product update error: ' . $e->getMessage());
            header('Location: ' . BASE_URL . 'admin/products/edit?id=' . $id);
            exit;
        }
    }

    /**
     * Delete product
     */
    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit;
        }

        // CSRF protection
        if (!isset($_POST['csrf']) || !isset($_SESSION['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
            $_SESSION['flash_error'] = 'Token bảo mật không hợp lệ';
            header('Location: ' . BASE_URL . 'admin/products');
            exit;
        }

        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;

        if ($id <= 0) {
            $_SESSION['flash_error'] = 'ID sản phẩm không hợp lệ';
            header('Location: ' . BASE_URL . 'admin/products');
            exit;
        }

        try {
            Product::delete($id);
            $_SESSION['flash_success'] = 'Xóa sản phẩm thành công';
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'Có lỗi xảy ra khi xóa sản phẩm';
            error_log('Product delete error: ' . $e->getMessage());
        }

        header('Location: ' . BASE_URL . 'admin/products');
        exit;
    }
}
