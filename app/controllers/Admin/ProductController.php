<?php
namespace App\Controllers\Admin;

use Core\Controller;
use Core\Auth;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function __construct()
    {
        Auth::requireAdmin();
    }

    public function index(): void
    {
        $page = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
        $perPage = 20;
        $offset = ($page - 1) * $perPage;
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $status = isset($_GET['status']) ? trim($_GET['status']) : '';

        $filters = [
            'limit' => $perPage,
            'offset' => $offset,
        ];

        if (!empty($search)) {
            $filters['search'] = $search;
        }

        if (!empty($status)) {
            $filters['status'] = $status;
        }

        $products = Product::getAll($filters);
        $total = Product::count($filters);
        $totalPages = ceil($total / $perPage);

        $this->view('admin/products/index', [
            'title' => 'Quản lý sản phẩm',
            'products' => $products,
            'search' => $search,
            'status' => $status,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
        ], 'admin');
    }

    public function create(): void
    {
        $categories = Category::getAll();
        $this->view('admin/products/form', [
            'title' => 'Thêm sản phẩm mới',
            'product' => null,
            'categories' => $categories,
        ], 'admin');
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'admin/products/create');
            exit;
        }

        // CSRF protection
        if (!isset($_POST['csrf']) || !isset($_SESSION['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
            $_SESSION['flash_error'] = 'Token bảo mật không hợp lệ';
            header('Location: ' . BASE_URL . 'admin/products/create');
            exit;
        }

        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $slug = isset($_POST['slug']) ? trim($_POST['slug']) : '';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $price = isset($_POST['price']) ? (float)$_POST['price'] : 0;
        $salePrice = isset($_POST['sale_price']) ? (float)$_POST['sale_price'] : null;
        $stockQuantity = isset($_POST['stock_quantity']) ? (int)$_POST['stock_quantity'] : 0;
        $sku = isset($_POST['sku']) ? trim($_POST['sku']) : '';
        $categoryId = isset($_POST['category_id']) ? (int)$_POST['category_id'] : null;
        $status = isset($_POST['status']) ? $_POST['status'] : 'draft';
        $featured = isset($_POST['featured']) ? 1 : 0;

        // Generate slug if empty
        if (empty($slug)) {
            $slug = $this->generateSlug($name);
        }

        // Handle image upload
        $imageUrl = null;
        if (!empty($_FILES['image']['name'])) {
            $imageUrl = $this->handleImageUpload($_FILES['image']);
        }

        if (empty($name) || $price <= 0) {
            $_SESSION['flash_error'] = 'Vui lòng điền đầy đủ thông tin';
            header('Location: ' . BASE_URL . 'admin/products/create');
            exit;
        }

        if (Product::create([
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'price' => $price,
            'sale_price' => $salePrice,
            'stock_quantity' => $stockQuantity,
            'sku' => $sku,
            'category_id' => $categoryId,
            'image_url' => $imageUrl,
            'status' => $status,
            'featured' => $featured,
        ])) {
            $_SESSION['flash_success'] = 'Thêm sản phẩm thành công!';
            header('Location: ' . BASE_URL . 'admin/products');
        } else {
            $_SESSION['flash_error'] = 'Có lỗi xảy ra khi thêm sản phẩm';
            header('Location: ' . BASE_URL . 'admin/products/create');
        }
        exit;
    }

    public function edit(): void
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) {
            header('Location: ' . BASE_URL . 'admin/products');
            exit;
        }

        $product = Product::findById($id);
        if (!$product) {
            header('Location: ' . BASE_URL . 'admin/products');
            exit;
        }

        $categories = Category::getAll();
        $this->view('admin/products/form', [
            'title' => 'Chỉnh sửa sản phẩm',
            'product' => $product,
            'categories' => $categories,
        ], 'admin');
    }

    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'admin/products');
            exit;
        }

        // CSRF protection
        if (!isset($_POST['csrf']) || !isset($_SESSION['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
            $_SESSION['flash_error'] = 'Token bảo mật không hợp lệ';
            header('Location: ' . BASE_URL . 'admin/products');
            exit;
        }

        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $name = isset($_POST['name']) ? trim($_POST['name']) : '';
        $slug = isset($_POST['slug']) ? trim($_POST['slug']) : '';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $price = isset($_POST['price']) ? (float)$_POST['price'] : 0;
        $salePrice = isset($_POST['sale_price']) ? (float)$_POST['sale_price'] : null;
        $stockQuantity = isset($_POST['stock_quantity']) ? (int)$_POST['stock_quantity'] : 0;
        $sku = isset($_POST['sku']) ? trim($_POST['sku']) : '';
        $categoryId = isset($_POST['category_id']) ? (int)$_POST['category_id'] : null;
        $status = isset($_POST['status']) ? $_POST['status'] : 'draft';
        $featured = isset($_POST['featured']) ? 1 : 0;

        // Generate slug if empty
        if (empty($slug)) {
            $slug = $this->generateSlug($name);
        }

        $product = Product::findById($id);
        $data = [
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'price' => $price,
            'sale_price' => $salePrice,
            'stock_quantity' => $stockQuantity,
            'sku' => $sku,
            'category_id' => $categoryId,
            'status' => $status,
            'featured' => $featured,
        ];

        // Handle image upload if new image provided
        if (!empty($_FILES['image']['name'])) {
            $imageUrl = $this->handleImageUpload($_FILES['image']);
            if ($imageUrl) {
                $data['image_url'] = $imageUrl;
            }
        }

        if ($id <= 0 || empty($name) || $price <= 0) {
            $_SESSION['flash_error'] = 'Vui lòng điền đầy đủ thông tin';
            header('Location: ' . BASE_URL . 'admin/products');
            exit;
        }

        if (Product::update($id, $data)) {
            $_SESSION['flash_success'] = 'Cập nhật sản phẩm thành công!';
        } else {
            $_SESSION['flash_error'] = 'Có lỗi xảy ra khi cập nhật sản phẩm';
        }

        header('Location: ' . BASE_URL . 'admin/products');
        exit;
    }

    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'admin/products');
            exit;
        }

        // CSRF protection
        if (!isset($_POST['csrf']) || !isset($_SESSION['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
            $_SESSION['flash_error'] = 'Token bảo mật không hợp lệ';
            header('Location: ' . BASE_URL . 'admin/products');
            exit;
        }

        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        if ($id > 0) {
            if (Product::delete($id)) {
                $_SESSION['flash_success'] = 'Xóa sản phẩm thành công!';
            } else {
                $_SESSION['flash_error'] = 'Có lỗi xảy ra khi xóa sản phẩm';
            }
        }

        header('Location: ' . BASE_URL . 'admin/products');
        exit;
    }

    private function generateSlug(string $text): string
    {
        $text = mb_strtolower($text, 'UTF-8');
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
        $text = preg_replace('/[\s-]+/', '-', $text);
        return trim($text, '-');
    }

    private function handleImageUpload(array $file): ?string
    {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        
        if (!in_array($ext, $allowed) || $file['size'] > 5 * 1024 * 1024) {
            return null;
        }

        $uploadDir = __DIR__ . '/../../../public/uploads/products/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $filename = uniqid('product_') . '.' . $ext;
        $filepath = $uploadDir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return BASE_URL . 'uploads/products/' . $filename;
        }
        
        return null;
    }
}

