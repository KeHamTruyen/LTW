<?php
namespace App\Controllers\Admin;

use Core\Controller;
use Core\Auth;
use App\Models\User;

class UserController extends Controller
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
        $role = isset($_GET['role']) ? trim($_GET['role']) : '';
        $status = isset($_GET['status']) ? trim($_GET['status']) : '';

        $filters = [
            'limit' => $perPage,
            'offset' => $offset,
        ];

        if (!empty($search)) {
            $filters['search'] = $search;
        }

        if (!empty($role)) {
            $filters['role'] = $role;
        }

        if (!empty($status)) {
            $filters['status'] = $status;
        }

        $users = User::getAll($filters);
        $total = User::count($filters);
        $totalPages = ceil($total / $perPage);

        $this->view('admin/users/index', [
            'title' => 'Quản lý người dùng',
            'users' => $users,
            'search' => $search,
            'role' => $role,
            'status' => $status,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
        ], 'admin');
    }

    public function show(): void
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) {
            header('Location: ' . BASE_URL . 'admin/users');
            exit;
        }

        $user = User::findById($id);
        if (!$user) {
            header('Location: ' . BASE_URL . 'admin/users');
            exit;
        }

        $this->view('admin/users/show', [
            'title' => 'Chi tiết người dùng',
            'user' => $user,
        ], 'admin');
    }

    public function updateStatus(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'admin/users');
            exit;
        }

        // CSRF protection
        if (!isset($_POST['csrf']) || !isset($_SESSION['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
            $_SESSION['flash_error'] = 'Token bảo mật không hợp lệ';
            header('Location: ' . BASE_URL . 'admin/users');
            exit;
        }

        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $status = isset($_POST['status']) ? trim($_POST['status']) : '';

        if ($id > 0 && in_array($status, ['active', 'inactive', 'banned'])) {
            if (User::updateStatus($id, $status)) {
                $_SESSION['flash_success'] = 'Cập nhật trạng thái thành công!';
            } else {
                $_SESSION['flash_error'] = 'Có lỗi xảy ra khi cập nhật';
            }
        }

        header('Location: ' . BASE_URL . 'admin/users');
        exit;
    }

    public function resetPassword(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'admin/users');
            exit;
        }

        // CSRF protection
        if (!isset($_POST['csrf']) || !isset($_SESSION['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
            $_SESSION['flash_error'] = 'Token bảo mật không hợp lệ';
            header('Location: ' . BASE_URL . 'admin/users');
            exit;
        }

        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $newPassword = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';

        if ($id > 0 && !empty($newPassword) && mb_strlen($newPassword) >= 6) {
            if (User::updatePassword($id, password_hash($newPassword, PASSWORD_DEFAULT))) {
                $_SESSION['flash_success'] = 'Reset mật khẩu thành công!';
            } else {
                $_SESSION['flash_error'] = 'Có lỗi xảy ra khi reset mật khẩu';
            }
        } else {
            $_SESSION['flash_error'] = 'Mật khẩu phải có ít nhất 6 ký tự';
        }

        header('Location: ' . BASE_URL . 'admin/users/show?id=' . $id);
        exit;
    }

    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'admin/users');
            exit;
        }

        // CSRF protection
        if (!isset($_POST['csrf']) || !isset($_SESSION['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
            $_SESSION['flash_error'] = 'Token bảo mật không hợp lệ';
            header('Location: ' . BASE_URL . 'admin/users');
            exit;
        }

        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        
        // Prevent deleting yourself
        if ($id == Auth::id()) {
            $_SESSION['flash_error'] = 'Không thể xóa chính mình';
            header('Location: ' . BASE_URL . 'admin/users');
            exit;
        }

        if ($id > 0) {
            if (User::delete($id)) {
                $_SESSION['flash_success'] = 'Xóa người dùng thành công!';
            } else {
                $_SESSION['flash_error'] = 'Có lỗi xảy ra khi xóa người dùng';
            }
        }

        header('Location: ' . BASE_URL . 'admin/users');
        exit;
    }
}

