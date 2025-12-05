<?php
namespace App\Controllers;

use Core\Controller;
use Core\Auth;
use App\Models\Order;
use App\Models\Cart;
use App\Models\User;

class OrderController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();

        $userId = Auth::id();
        $page = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        $filters = [
            'limit' => $perPage,
            'offset' => $offset,
            'user_id' => $userId,
        ];

        $orders = Order::getAll($filters);
        $total = Order::count($filters);
        $totalPages = ceil($total / $perPage);

        $this->view('orders.index', [
            'title' => 'Đơn hàng của tôi - ' . APP_NAME,
            'orders' => $orders,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
        ]);
    }

    public function show(): void
    {
        Auth::requireLogin();

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $userId = Auth::id();

        if ($id <= 0) {
            http_response_code(404);
            echo '404 - Đơn hàng không tồn tại';
            exit;
        }

        $order = Order::findById($id);

        if (!$order || ($order['user_id'] != $userId && Auth::user()['role'] !== 'admin')) {
            http_response_code(404);
            echo '404 - Đơn hàng không tồn tại';
            exit;
        }

        $orderItems = Order::getItems($id);

        $this->view('orders.show', [
            'title' => 'Chi tiết đơn hàng #' . $order['order_number'] . ' - ' . APP_NAME,
            'order' => $order,
            'orderItems' => $orderItems,
        ]);
    }

    public function checkout(): void
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

        // Check if cart is empty
        $cartItems = Cart::getItems($userId);
        if (empty($cartItems)) {
            $_SESSION['flash_error'] = 'Giỏ hàng trống';
            header('Location: ' . BASE_URL . 'cart');
            exit;
        }

        // Validate form data
        $customerName = isset($_POST['customer_name']) ? trim($_POST['customer_name']) : '';
        $customerEmail = isset($_POST['customer_email']) ? trim($_POST['customer_email']) : '';
        $customerPhone = isset($_POST['customer_phone']) ? trim($_POST['customer_phone']) : '';
        $shippingAddress = isset($_POST['shipping_address']) ? trim($_POST['shipping_address']) : '';
        $paymentMethod = isset($_POST['payment_method']) ? trim($_POST['payment_method']) : '';
        $notes = isset($_POST['notes']) ? trim($_POST['notes']) : '';

        $errors = [];
        if (empty($customerName)) $errors[] = 'Vui lòng nhập tên';
        if (empty($customerEmail) || !filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email không hợp lệ';
        if (empty($customerPhone)) $errors[] = 'Vui lòng nhập số điện thoại';
        if (empty($shippingAddress)) $errors[] = 'Vui lòng nhập địa chỉ giao hàng';

        if (!empty($errors)) {
            $_SESSION['flash_error'] = implode('<br>', $errors);
            header('Location: ' . BASE_URL . 'cart');
            exit;
        }

        // Get user info if logged in
        $user = User::findById($userId);
        if ($user) {
            if (empty($customerName)) $customerName = $user['name'];
            if (empty($customerEmail)) $customerEmail = $user['email'];
            if (empty($customerPhone)) $customerPhone = $user['phone'] ?? '';
        }

        try {
            $orderData = [
                'customer_name' => $customerName,
                'customer_email' => $customerEmail,
                'customer_phone' => $customerPhone,
                'shipping_address' => $shippingAddress,
                'payment_method' => $paymentMethod,
                'notes' => $notes,
            ];

            $orderId = Order::createFromCart($userId, $cartItems, $orderData);
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

