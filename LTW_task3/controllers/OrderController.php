<?php
namespace App\Controllers;

use Core\Controller;
use Core\Auth;
use App\Models\Order;

class OrderController extends Controller
{
    /**
     * List user's orders
     */
    public function index()
    {
        Auth::requireLogin();

        $userId = Auth::id();

        // Pagination
        $page = isset($_GET['p']) ? max(1, (int) $_GET['p']) : 1;
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        // Filters
        $filters = [
            'user_id' => $userId,
            'limit' => $perPage,
            'offset' => $offset,
        ];

        // Get orders and total count
        $orders = Order::getAll($filters);
        $totalOrders = Order::count($filters);
        $totalPages = ceil($totalOrders / $perPage);

        $this->view('orders/index', [
            'orders' => $orders,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalOrders' => $totalOrders,
        ], 'Đơn hàng của tôi - ' . APP_NAME, 'public');
    }

    /**
     * Show order detail
     */
    public function show()
    {
        Auth::requireLogin();

        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if ($id <= 0) {
            http_response_code(404);
            echo '404 - Đơn hàng không tồn tại';
            exit;
        }

        $order = Order::findById($id);

        if (!$order || $order['USER_id'] !== Auth::id()) {
            http_response_code(404);
            echo '404 - Đơn hàng không tồn tại';
            exit;
        }

        $orderDetails = Order::getDetails($id);
        $payment = Order::getPayment($id);

        $this->view('orders/show', [
            'order' => $order,
            'orderDetails' => $orderDetails,
            'payment' => $payment,
        ], 'Chi tiết đơn hàng #' . $id . ' - ' . APP_NAME, 'public');
    }
}
