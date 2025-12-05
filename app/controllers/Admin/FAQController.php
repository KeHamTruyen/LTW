<?php
namespace App\Controllers\Admin;

use Core\Controller;
use Core\Auth;
use App\Models\FAQ;

class FAQController extends Controller
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

        $filters = [
            'limit' => $perPage,
            'offset' => $offset,
        ];

        if (!empty($search)) {
            $filters['search'] = $search;
        }

        $faqs = FAQ::getAll($filters);
        $total = FAQ::count($filters);
        $totalPages = ceil($total / $perPage);

        $this->view('admin/faqs/index', [
            'title' => 'Quản lý FAQ',
            'faqs' => $faqs,
            'search' => $search,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
        ], 'admin');
    }

    public function create(): void
    {
        $this->view('admin/faqs/form', [
            'title' => 'Thêm FAQ mới',
            'faq' => null,
        ], 'admin');
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'admin/faqs/create');
            exit;
        }

        // CSRF protection
        if (!isset($_POST['csrf']) || !isset($_SESSION['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
            $_SESSION['flash_error'] = 'Token bảo mật không hợp lệ';
            header('Location: ' . BASE_URL . 'admin/faqs/create');
            exit;
        }

        $question = isset($_POST['question']) ? trim($_POST['question']) : '';
        $answer = isset($_POST['answer']) ? trim($_POST['answer']) : '';
        $displayOrder = isset($_POST['display_order']) ? (int)$_POST['display_order'] : 0;
        $status = isset($_POST['status']) ? $_POST['status'] : 'active';

        if (empty($question) || empty($answer)) {
            $_SESSION['flash_error'] = 'Vui lòng điền đầy đủ thông tin';
            header('Location: ' . BASE_URL . 'admin/faqs/create');
            exit;
        }

        if (FAQ::create([
            'question' => $question,
            'answer' => $answer,
            'display_order' => $displayOrder,
            'status' => $status,
        ])) {
            $_SESSION['flash_success'] = 'Thêm FAQ thành công!';
            header('Location: ' . BASE_URL . 'admin/faqs');
        } else {
            $_SESSION['flash_error'] = 'Có lỗi xảy ra khi thêm FAQ';
            header('Location: ' . BASE_URL . 'admin/faqs/create');
        }
        exit;
    }

    public function edit(): void
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) {
            header('Location: ' . BASE_URL . 'admin/faqs');
            exit;
        }

        $faq = FAQ::findById($id);
        if (!$faq) {
            header('Location: ' . BASE_URL . 'admin/faqs');
            exit;
        }

        $this->view('admin/faqs/form', [
            'title' => 'Chỉnh sửa FAQ',
            'faq' => $faq,
        ], 'admin');
    }

    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'admin/faqs');
            exit;
        }

        // CSRF protection
        if (!isset($_POST['csrf']) || !isset($_SESSION['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
            $_SESSION['flash_error'] = 'Token bảo mật không hợp lệ';
            header('Location: ' . BASE_URL . 'admin/faqs');
            exit;
        }

        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $question = isset($_POST['question']) ? trim($_POST['question']) : '';
        $answer = isset($_POST['answer']) ? trim($_POST['answer']) : '';
        $displayOrder = isset($_POST['display_order']) ? (int)$_POST['display_order'] : 0;
        $status = isset($_POST['status']) ? $_POST['status'] : 'active';

        if ($id <= 0 || empty($question) || empty($answer)) {
            $_SESSION['flash_error'] = 'Vui lòng điền đầy đủ thông tin';
            header('Location: ' . BASE_URL . 'admin/faqs');
            exit;
        }

        if (FAQ::update($id, [
            'question' => $question,
            'answer' => $answer,
            'display_order' => $displayOrder,
            'status' => $status,
        ])) {
            $_SESSION['flash_success'] = 'Cập nhật FAQ thành công!';
        } else {
            $_SESSION['flash_error'] = 'Có lỗi xảy ra khi cập nhật FAQ';
        }

        header('Location: ' . BASE_URL . 'admin/faqs');
        exit;
    }

    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'admin/faqs');
            exit;
        }

        // CSRF protection
        if (!isset($_POST['csrf']) || !isset($_SESSION['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
            $_SESSION['flash_error'] = 'Token bảo mật không hợp lệ';
            header('Location: ' . BASE_URL . 'admin/faqs');
            exit;
        }

        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        if ($id > 0) {
            if (FAQ::delete($id)) {
                $_SESSION['flash_success'] = 'Xóa FAQ thành công!';
            } else {
                $_SESSION['flash_error'] = 'Có lỗi xảy ra khi xóa FAQ';
            }
        }

        header('Location: ' . BASE_URL . 'admin/faqs');
        exit;
    }
}

