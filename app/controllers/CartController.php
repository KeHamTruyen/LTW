<?php
namespace App\Controllers;

use Core\Controller;
use Core\Auth;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Home;

class CartController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();

        $userId = Auth::id();
        $cartItems = Cart::getItems($userId);
        $total = Cart::getTotal($userId);
        $homeData = Home::get();

        $this->view('cart.index', [
            'title' => 'Giỏ hàng - ' . APP_NAME,
            'cartItems' => $cartItems,
            'total' => $total,
            'activeMenu' => 'shop',
            'hideBlogHero' => true,
            'homeData' => $homeData,
        ], null, 'public');
    }

    public function add(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit;
        }

        Auth::requireLogin();

        // CSRF protection
        if (!isset($_POST['csrf']) || !isset($_SESSION['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
            $_SESSION['flash_error'] = 'Token bảo mật không hợp lệ';
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? BASE_URL . 'products'));
            exit;
        }

        $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

        if ($productId <= 0 || $quantity <= 0) {
            $_SESSION['flash_error'] = 'Dữ liệu không hợp lệ';
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? BASE_URL . 'products'));
            exit;
        }

        // Check product exists and has stock
        $product = Product::findById($productId);
        if (!$product || $product['status'] !== 'published' || $product['stock_quantity'] < $quantity) {
            $_SESSION['flash_error'] = 'Sản phẩm không đủ hàng';
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? BASE_URL . 'products'));
            exit;
        }

        $userId = Auth::id();
        try {
            Cart::addItem($userId, $productId, $quantity);
            $_SESSION['flash_success'] = 'Đã thêm sản phẩm vào giỏ hàng';
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'Có lỗi xảy ra khi thêm vào giỏ hàng';
            error_log('Cart add error: ' . $e->getMessage());
        }

        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? BASE_URL . 'products'));
        exit;
    }

    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit;
        }

        Auth::requireLogin();

        // CSRF protection
        if (!isset($_POST['csrf']) || !isset($_SESSION['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
            $_SESSION['flash_error'] = 'Token bảo mật không hợp lệ';
            header('Location: ' . BASE_URL . 'cart');
            exit;
        }

        $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;

        if ($productId <= 0 || $quantity < 0) {
            $_SESSION['flash_error'] = 'Dữ liệu không hợp lệ';
            header('Location: ' . BASE_URL . 'cart');
            exit;
        }

        $userId = Auth::id();

        try {
            if ($quantity === 0) {
                Cart::removeItem($userId, $productId);
                $_SESSION['flash_success'] = 'Đã xóa sản phẩm khỏi giỏ hàng';
            } else {
                // Check stock
                $product = Product::findById($productId);
                if (!$product || $product['stock_quantity'] < $quantity) {
                    $_SESSION['flash_error'] = 'Số lượng vượt quá tồn kho';
                } else {
                    Cart::updateItem($userId, $productId, $quantity);
                    $_SESSION['flash_success'] = 'Đã cập nhật giỏ hàng';
                }
            }
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'Có lỗi xảy ra khi cập nhật giỏ hàng';
            error_log('Cart update error: ' . $e->getMessage());
        }

        header('Location: ' . BASE_URL . 'cart');
        exit;
    }

    public function remove(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit;
        }

        Auth::requireLogin();

        // CSRF protection
        if (!isset($_POST['csrf']) || !isset($_SESSION['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
            $_SESSION['flash_error'] = 'Token bảo mật không hợp lệ';
            header('Location: ' . BASE_URL . 'cart');
            exit;
        }

        $productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;

        if ($productId <= 0) {
            $_SESSION['flash_error'] = 'Dữ liệu không hợp lệ';
            header('Location: ' . BASE_URL . 'cart');
            exit;
        }

        $userId = Auth::id();
        Cart::removeItem($userId, $productId);
        $_SESSION['flash_success'] = 'Đã xóa sản phẩm khỏi giỏ hàng';

        header('Location: ' . BASE_URL . 'cart');
        exit;
    }

    public function checkout(): void
    {
        Auth::requireLogin();

        $userId = Auth::id();
        $cartItems = Cart::getItems($userId);
        $total = Cart::getTotal($userId);

        if (empty($cartItems)) {
            $_SESSION['flash_error'] = 'Giỏ hàng trống';
            header('Location: ' . BASE_URL . 'cart');
            exit;
        }

        // Get user info
        $user = \App\Models\User::findById($userId);
        $homeData = Home::get();

        $this->view('cart.checkout', [
            'title' => 'Thanh toán - ' . APP_NAME,
            'cartItems' => $cartItems,
            'total' => $total,
            'user' => $user,
            'activeMenu' => 'shop',
            'hideBlogHero' => true,
            'homeData' => $homeData,
        ], null, 'public');
    }
}

