<?php
namespace App\Controllers;

use Core\Controller;
use Core\Auth;
use App\Models\Cart;
use App\Models\Product;

class CartController extends Controller
{
    /**
     * View cart
     */
    public function index()
    {
        Auth::requireLogin();

        $userId = Auth::id();
        $cartId = Cart::getOrCreate($userId);
        $cartItems = Cart::getItems($cartId);
        $total = Cart::getTotal($cartId);

        $this->view('cart/index', [
            'cartItems' => $cartItems,
            'total' => $total,
        ], 'Giỏ hàng - ' . APP_NAME, 'public');
    }

    /**
     * Add product to cart
     */
    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit;
        }

        Auth::requireLogin();

        // CSRF protection
        if (!isset($_POST['csrf']) || !isset($_SESSION['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
            $_SESSION['flash_error'] = 'Token bảo mật không hợp lệ';
            header('Location: ' . $_SERVER['HTTP_REFERER'] ?? BASE_URL . 'products');
            exit;
        }

        // Validate input
        $productId = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;
        $quantity = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 1;

        if ($productId <= 0 || $quantity <= 0) {
            $_SESSION['flash_error'] = 'Dữ liệu không hợp lệ';
            header('Location: ' . $_SERVER['HTTP_REFERER'] ?? BASE_URL . 'products');
            exit;
        }

        // Check if product exists and has stock
        $product = Product::findById($productId);
        if (!$product || $product['stock_quantity'] < $quantity) {
            $_SESSION['flash_error'] = 'Sản phẩm không đủ hàng';
            header('Location: ' . $_SERVER['HTTP_REFERER'] ?? BASE_URL . 'products');
            exit;
        }

        // Add to cart
        $userId = Auth::id();
        $cartId = Cart::getOrCreate($userId);

        try {
            Cart::addItem($cartId, $productId, $quantity);
            $_SESSION['flash_success'] = 'Đã thêm sản phẩm vào giỏ hàng';
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'Có lỗi xảy ra khi thêm vào giỏ hàng';
            error_log('Cart add error: ' . $e->getMessage());
        }

        header('Location: ' . $_SERVER['HTTP_REFERER'] ?? BASE_URL . 'products');
        exit;
    }

    /**
     * Update cart item quantity
     */
    public function update()
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

        $productId = isset($_POST['product_id']) ? (int) $_POST['product_id'] : 0;
        $quantity = isset($_POST['quantity']) ? (int) $_POST['quantity'] : 0;

        if ($productId <= 0 || $quantity < 0) {
            $_SESSION['flash_error'] = 'Dữ liệu không hợp lệ';
            header('Location: ' . BASE_URL . 'cart');
            exit;
        }

        $userId = Auth::id();
        $cartId = Cart::getOrCreate($userId);

        try {
            if ($quantity === 0) {
                Cart::removeItem($cartId, $productId);
                $_SESSION['flash_success'] = 'Đã xóa sản phẩm khỏi giỏ hàng';
            } else {
                // Check stock
                $product = Product::findById($productId);
                if (!$product || $product['stock_quantity'] < $quantity) {
                    $_SESSION['flash_error'] = 'Số lượng vượt quá tồn kho';
                } else {
                    Cart::updateItem($cartId, $productId, $quantity);
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

    /**
     * Checkout cart
     */
    public function checkout()
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

        $userId = Auth::id();
        $cartId = Cart::getOrCreate($userId);

        // Check if cart is empty
        $cartItems = Cart::getItems($cartId);
        if (empty($cartItems)) {
            $_SESSION['flash_error'] = 'Giỏ hàng trống';
            header('Location: ' . BASE_URL . 'cart');
            exit;
        }

        try {
            $orderId = \App\Models\Order::createFromCart($userId, $cartId);
            $_SESSION['flash_success'] = 'Đặt hàng thành công! Mã đơn hàng: #' . $orderId;
            header('Location: ' . BASE_URL . 'orders/show?id=' . $orderId);
            exit;
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'Có lỗi xảy ra khi đặt hàng: ' . $e->getMessage();
            error_log('Checkout error: ' . $e->getMessage());
            header('Location: ' . BASE_URL . 'cart');
            exit;
        }
    }
}
