<?php
namespace App\Controllers\Admin;

use Core\Controller;
use Core\Auth;
use App\Models\Contact;

class ContactController extends Controller
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

        $contacts = Contact::getAll($filters);
        $total = Contact::count($filters);
        $totalPages = ceil($total / $perPage);
        $unreadCount = Contact::getUnreadCount();

        $this->view('admin/contacts/index', [
            'title' => 'Quản lý liên hệ',
            'contacts' => $contacts,
            'status' => $status,
            'search' => $search,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
            'unreadCount' => $unreadCount,
        ], 'admin');
    }

    public function show(): void
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) {
            header('Location: ' . BASE_URL . 'admin/contacts');
            exit;
        }

        $contact = Contact::findById($id);
        if (!$contact) {
            header('Location: ' . BASE_URL . 'admin/contacts');
            exit;
        }

        // Mark as read if unread
        if ($contact['status'] === 'unread') {
            Contact::updateStatus($id, 'read');
        }

        $this->view('admin/contacts/show', [
            'title' => 'Chi tiết liên hệ',
            'contact' => $contact,
        ], 'admin');
    }

    public function updateStatus(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'admin/contacts');
            exit;
        }

        // CSRF protection
        if (!isset($_POST['csrf']) || !isset($_SESSION['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
            $_SESSION['flash_error'] = 'Token bảo mật không hợp lệ';
            header('Location: ' . BASE_URL . 'admin/contacts');
            exit;
        }

        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $status = isset($_POST['status']) ? trim($_POST['status']) : '';

        if ($id > 0 && in_array($status, ['unread', 'read', 'replied'])) {
            if (Contact::updateStatus($id, $status)) {
                $_SESSION['flash_success'] = 'Cập nhật trạng thái thành công!';
            } else {
                $_SESSION['flash_error'] = 'Có lỗi xảy ra khi cập nhật';
            }
        }

        header('Location: ' . BASE_URL . 'admin/contacts');
        exit;
    }

    public function reply(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'admin/contacts');
            exit;
        }

        // CSRF protection
        if (!isset($_POST['csrf']) || !isset($_SESSION['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
            $_SESSION['flash_error'] = 'Token bảo mật không hợp lệ';
            header('Location: ' . BASE_URL . 'admin/contacts');
            exit;
        }

        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $replyMessage = isset($_POST['reply_message']) ? trim($_POST['reply_message']) : '';

        if ($id > 0 && !empty($replyMessage)) {
            if (Contact::reply($id, Auth::id(), $replyMessage)) {
                $_SESSION['flash_success'] = 'Đã gửi phản hồi thành công!';
            } else {
                $_SESSION['flash_error'] = 'Có lỗi xảy ra khi gửi phản hồi';
            }
        }

        header('Location: ' . BASE_URL . 'admin/contacts/show?id=' . $id);
        exit;
    }

    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'admin/contacts');
            exit;
        }

        // CSRF protection
        if (!isset($_POST['csrf']) || !isset($_SESSION['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
            $_SESSION['flash_error'] = 'Token bảo mật không hợp lệ';
            header('Location: ' . BASE_URL . 'admin/contacts');
            exit;
        }

        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        if ($id > 0) {
            if (Contact::delete($id)) {
                $_SESSION['flash_success'] = 'Xóa liên hệ thành công!';
            } else {
                $_SESSION['flash_error'] = 'Có lỗi xảy ra khi xóa liên hệ';
            }
        }

        header('Location: ' . BASE_URL . 'admin/contacts');
        exit;
    }
}

