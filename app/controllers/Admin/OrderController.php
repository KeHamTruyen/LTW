<?php
namespace App\Controllers\Admin;

use Core\Controller;
use Core\Auth;
use App\Models\Order;

class OrderController extends Controller
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
        $status = isset($_GET['status']) ? trim($_GET['status']) : '';
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';

        $filters = [
            'limit' => $perPage,
            'offset' => $offset,
        ];

        if (!empty($status)) {
            $filters['status'] = $status;
        }

        if (!empty($search)) {
            $filters['search'] = $search;
        }

        $orders = Order::getAll($filters);
        $total = Order::count($filters);
        $totalPages = ceil($total / $perPage);

        $this->view('admin/orders/index', [
            'title' => 'Quản lý đơn hàng',
            'orders' => $orders,
            'status' => $status,
            'search' => $search,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
        ], 'Quản lý đơn hàng', 'admin');
    }

    public function show(): void
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) {
            header('Location: ' . BASE_URL . 'admin/orders');
            exit;
        }

        $order = Order::findById($id);
        if (!$order) {
            header('Location: ' . BASE_URL . 'admin/orders');
            exit;
        }

        $orderItems = Order::getItems($id);

        $this->view('admin/orders/show', [
            'title' => 'Chi tiết đơn hàng #' . $order['order_number'],
            'order' => $order,
            'orderItems' => $orderItems,
        ], 'Chi tiết đơn hàng #' . $order['order_number'], 'admin');
    }

    public function updateStatus(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'admin/orders');
            exit;
        }

        // CSRF protection
        if (!isset($_POST['csrf']) || !isset($_SESSION['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
            $_SESSION['flash_error'] = 'Token bảo mật không hợp lệ';
            header('Location: ' . BASE_URL . 'admin/orders');
            exit;
        }

        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $status = isset($_POST['status']) ? trim($_POST['status']) : '';

        if ($id > 0 && in_array($status, ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])) {
            if (Order::updateStatus($id, $status)) {
                $_SESSION['flash_success'] = 'Cập nhật trạng thái đơn hàng thành công!';
            } else {
                $_SESSION['flash_error'] = 'Có lỗi xảy ra khi cập nhật';
            }
        }

        header('Location: ' . BASE_URL . 'admin/orders/show?id=' . $id);
        exit;
    }
}

