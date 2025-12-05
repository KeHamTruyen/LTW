<?php
namespace App\Controllers;

use Core\Controller;
use Core\Auth;
use App\Models\Order;

class AdminOrderController extends Controller
{
    public function __construct()
    {
        Auth::requireAdmin();
    }

    /**
     * List all orders (admin)
     */
    public function index()
    {
        // Pagination
        $page = isset($_GET['p']) ? max(1, (int) $_GET['p']) : 1;
        $perPage = 20;
        $offset = ($page - 1) * $perPage;

        // Filters
        $filters = [
            'limit' => $perPage,
            'offset' => $offset,
        ];

        // Status filter
        $status = isset($_GET['status']) ? $_GET['status'] : '';
        if (!empty($status)) {
            $filters['status'] = $status;
        }

        // Get orders and total count
        $orders = Order::getAll($filters);
        $totalOrders = Order::count($filters);
        $totalPages = ceil($totalOrders / $perPage);

        // Render view with admin layout
        $this->view('admin/orders/index', [
            'orders' => $orders,
            'status' => $status,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalOrders' => $totalOrders,
        ], 'Quản lý đơn hàng - ' . APP_NAME, 'admin');
    }

    /**
     * Show order detail (admin)
     */
    public function show()
    {
        $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

        if ($id <= 0) {
            http_response_code(404);
            echo '404 - Đơn hàng không tồn tại';
            exit;
        }

        $order = Order::findById($id);

        if (!$order) {
            http_response_code(404);
            echo '404 - Đơn hàng không tồn tại';
            exit;
        }

        $orderDetails = Order::getDetails($id);
        $payment = Order::getPayment($id);

        $this->view('admin/orders/show', [
            'order' => $order,
            'orderDetails' => $orderDetails,
            'payment' => $payment,
        ], 'Chi tiết đơn hàng #' . $id . ' - ' . APP_NAME, 'admin');
    }

    /**
     * Update order status
     */
    public function updateStatus()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit;
        }

        // CSRF protection
        if (!isset($_POST['csrf']) || !isset($_SESSION['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
            $_SESSION['flash_error'] = 'Token bảo mật không hợp lệ';
            header('Location: ' . BASE_URL . 'admin/orders');
            exit;
        }

        $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
        $status = isset($_POST['status']) ? $_POST['status'] : '';

        if ($id <= 0) {
            $_SESSION['flash_error'] = 'ID đơn hàng không hợp lệ';
            header('Location: ' . BASE_URL . 'admin/orders');
            exit;
        }

        $validStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        if (!in_array($status, $validStatuses)) {
            $_SESSION['flash_error'] = 'Trạng thái không hợp lệ';
            header('Location: ' . BASE_URL . 'admin/orders');
            exit;
        }

        try {
            Order::updateStatus($id, $status);
            $_SESSION['flash_success'] = 'Cập nhật trạng thái đơn hàng thành công';
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'Có lỗi xảy ra khi cập nhật trạng thái';
            error_log('Order status update error: ' . $e->getMessage());
        }

        header('Location: ' . BASE_URL . 'admin/orders/show?id=' . $id);
        exit;
    }
}
