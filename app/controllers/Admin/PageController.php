<?php
namespace App\Controllers\Admin;

use Core\Auth;
use Core\Controller;
use Core\Database;
use App\Models\Page;

class PageController extends Controller
{
    public function __construct()
    {
        Auth::requireAdmin();
    }

    public function dashboard(): void
    {
        $pdo = Database::conn();
        $stats = [ 
            'posts' => 0, 
            'drafts' => 0, 
            'comments_pending' => 0, 
            'total_comments' => 0,
            'users' => 0,
            'products' => 0,
            'orders' => 0,
            'contacts_unread' => 0,
        ];
        try { $stats['posts'] = (int)$pdo->query("SELECT COUNT(*) FROM posts")->fetchColumn(); } catch (\Throwable $e) {}
        try { $stats['drafts'] = (int)$pdo->query("SELECT COUNT(*) FROM posts WHERE status='draft'")->fetchColumn(); } catch (\Throwable $e) {}
        try { $stats['comments_pending'] = (int)$pdo->query("SELECT COUNT(*) FROM post_comments WHERE status='pending'")->fetchColumn(); } catch (\Throwable $e) {}
        try { $stats['total_comments'] = (int)$pdo->query("SELECT COUNT(*) FROM post_comments")->fetchColumn(); } catch (\Throwable $e) {}
        try { $stats['users'] = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(); } catch (\Throwable $e) {}
        try { $stats['products'] = (int)$pdo->query("SELECT COUNT(*) FROM products")->fetchColumn(); } catch (\Throwable $e) {}
        try { $stats['orders'] = (int)$pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn(); } catch (\Throwable $e) {}
        try { $stats['contacts_unread'] = (int)$pdo->query("SELECT COUNT(*) FROM contacts WHERE status='unread'")->fetchColumn(); } catch (\Throwable $e) {}
        $this->view('admin.dashboard', [ '_layout' => 'admin', 'title' => 'Tổng quan', 'stats' => $stats ]);
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

        $pages = Page::getAll($filters);
        $total = Page::count($filters);
        $totalPages = ceil($total / $perPage);

        $this->view('admin/pages/index', [
            'title' => 'Quản lý trang',
            'pages' => $pages,
            'search' => $search,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'total' => $total,
        ], 'admin');
    }

    public function create(): void
    {
        $this->view('admin/pages/form', [
            'title' => 'Thêm trang mới',
            'page' => null,
        ], 'admin');
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'admin/pages/create');
            exit;
        }

        // CSRF protection
        if (!isset($_POST['csrf']) || !isset($_SESSION['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
            $_SESSION['flash_error'] = 'Token bảo mật không hợp lệ';
            header('Location: ' . BASE_URL . 'admin/pages/create');
            exit;
        }

        $slug = isset($_POST['slug']) ? trim($_POST['slug']) : '';
        $title = isset($_POST['title']) ? trim($_POST['title']) : '';
        $contentHtml = isset($_POST['content_html']) ? trim($_POST['content_html']) : '';
        $metaTitle = isset($_POST['meta_title']) ? trim($_POST['meta_title']) : '';
        $metaDescription = isset($_POST['meta_description']) ? trim($_POST['meta_description']) : '';
        $status = isset($_POST['status']) ? $_POST['status'] : 'published';

        if (empty($slug) || empty($title)) {
            $_SESSION['flash_error'] = 'Vui lòng điền đầy đủ thông tin';
            header('Location: ' . BASE_URL . 'admin/pages/create');
            exit;
        }

        if (Page::create([
            'slug' => $slug,
            'title' => $title,
            'content_html' => $contentHtml,
            'meta_title' => $metaTitle,
            'meta_description' => $metaDescription,
            'status' => $status,
            'updated_by' => Auth::id(),
        ])) {
            $_SESSION['flash_success'] = 'Thêm trang thành công!';
            header('Location: ' . BASE_URL . 'admin/pages');
        } else {
            $_SESSION['flash_error'] = 'Có lỗi xảy ra khi thêm trang';
            header('Location: ' . BASE_URL . 'admin/pages/create');
        }
        exit;
    }

    public function edit(): void
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) {
            header('Location: ' . BASE_URL . 'admin/pages');
            exit;
        }

        $page = Page::findById($id);
        if (!$page) {
            header('Location: ' . BASE_URL . 'admin/pages');
            exit;
        }

        $this->view('admin/pages/form', [
            'title' => 'Chỉnh sửa trang',
            'page' => $page,
        ], 'admin');
    }

    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'admin/pages');
            exit;
        }

        // CSRF protection
        if (!isset($_POST['csrf']) || !isset($_SESSION['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
            $_SESSION['flash_error'] = 'Token bảo mật không hợp lệ';
            header('Location: ' . BASE_URL . 'admin/pages');
            exit;
        }

        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $slug = isset($_POST['slug']) ? trim($_POST['slug']) : '';
        $title = isset($_POST['title']) ? trim($_POST['title']) : '';
        $contentHtml = isset($_POST['content_html']) ? trim($_POST['content_html']) : '';
        $metaTitle = isset($_POST['meta_title']) ? trim($_POST['meta_title']) : '';
        $metaDescription = isset($_POST['meta_description']) ? trim($_POST['meta_description']) : '';
        $status = isset($_POST['status']) ? $_POST['status'] : 'published';

        if ($id <= 0 || empty($slug) || empty($title)) {
            $_SESSION['flash_error'] = 'Vui lòng điền đầy đủ thông tin';
            header('Location: ' . BASE_URL . 'admin/pages');
            exit;
        }

        if (Page::update($id, [
            'slug' => $slug,
            'title' => $title,
            'content_html' => $contentHtml,
            'meta_title' => $metaTitle,
            'meta_description' => $metaDescription,
            'status' => $status,
            'updated_by' => Auth::id(),
        ])) {
            $_SESSION['flash_success'] = 'Cập nhật trang thành công!';
        } else {
            $_SESSION['flash_error'] = 'Có lỗi xảy ra khi cập nhật trang';
        }

        header('Location: ' . BASE_URL . 'admin/pages');
        exit;
    }

    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'admin/pages');
            exit;
        }

        // CSRF protection
        if (!isset($_POST['csrf']) || !isset($_SESSION['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
            $_SESSION['flash_error'] = 'Token bảo mật không hợp lệ';
            header('Location: ' . BASE_URL . 'admin/pages');
            exit;
        }

        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        if ($id > 0) {
            if (Page::delete($id)) {
                $_SESSION['flash_success'] = 'Xóa trang thành công!';
            } else {
                $_SESSION['flash_error'] = 'Có lỗi xảy ra khi xóa trang';
            }
        }

        header('Location: ' . BASE_URL . 'admin/pages');
        exit;
    }
}
