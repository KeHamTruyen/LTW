<?php
namespace App\Controllers\Admin;

use Core\Controller;
use Core\Auth;
use App\Models\PostComment;

class CommentController extends Controller
{
    public function __construct()
    {
        // Check admin authentication using Auth class
        Auth::requireAdmin();
    }

    /**
     * Admin comment list page
     */
    public function index()
    {
        // Pagination
        $page = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
        $perPage = 30;
        $offset = ($page - 1) * $perPage;

        // Filters
        $status = isset($_GET['status']) ? trim($_GET['status']) : '';
        $postId = isset($_GET['post_id']) ? (int)$_GET['post_id'] : 0;

        $filters = [
            'limit' => $perPage,
            'offset' => $offset,
        ];

        if (!empty($status) && in_array($status, ['pending', 'approved', 'rejected'])) {
            $filters['status'] = $status;
        }

        if ($postId > 0) {
            $filters['post_id'] = $postId;
        }

        $comments = PostComment::getAll($filters);
        $totalComments = PostComment::count($filters);
        $totalPages = ceil($totalComments / $perPage);

        // Count by status for filter tabs
        $pendingCount = PostComment::count(['status' => 'pending']);
        $approvedCount = PostComment::count(['status' => 'approved']);
        $rejectedCount = PostComment::count(['status' => 'rejected']);

        $this->view('admin/comments/index', [
            'comments' => $comments,
            'status' => $status,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalComments' => $totalComments,
            'pendingCount' => $pendingCount,
            'approvedCount' => $approvedCount,
            'rejectedCount' => $rejectedCount,
        ], 'Quản lý bình luận', 'admin');
    }

    /**
     * Approve comment
     */
    public function approve()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'admin/comments');
            exit;
        }

        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

        // CSRF check
        if (!isset($_POST['csrf']) || $_POST['csrf'] !== ($_SESSION['csrf'] ?? '')) {
            $_SESSION['flash_error'] = 'Token bảo mật không hợp lệ';
            header('Location: ' . BASE_URL . 'admin/comments');
            exit;
        }

        $comment = PostComment::findById($id);
        if (!$comment) {
            $_SESSION['flash_error'] = 'Bình luận không tồn tại';
            header('Location: ' . BASE_URL . 'admin/comments');
            exit;
        }

        try {
            PostComment::updateStatus($id, 'approved');
            $_SESSION['flash_success'] = 'Đã duyệt bình luận';
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'Có lỗi xảy ra';
        }

        header('Location: ' . $_SERVER['HTTP_REFERER'] ?? BASE_URL . 'admin/comments');
        exit;
    }

    /**
     * Reject comment
     */
    public function reject()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'admin/comments');
            exit;
        }

        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

        // CSRF check
        if (!isset($_POST['csrf']) || $_POST['csrf'] !== ($_SESSION['csrf'] ?? '')) {
            $_SESSION['flash_error'] = 'Token bảo mật không hợp lệ';
            header('Location: ' . BASE_URL . 'admin/comments');
            exit;
        }

        $comment = PostComment::findById($id);
        if (!$comment) {
            $_SESSION['flash_error'] = 'Bình luận không tồn tại';
            header('Location: ' . BASE_URL . 'admin/comments');
            exit;
        }

        try {
            PostComment::updateStatus($id, 'rejected');
            $_SESSION['flash_success'] = 'Đã từ chối bình luận';
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'Có lỗi xảy ra';
        }

        header('Location: ' . $_SERVER['HTTP_REFERER'] ?? BASE_URL . 'admin/comments');
        exit;
    }

    /**
     * Delete comment
     */
    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'admin/comments');
            exit;
        }

        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;

        // CSRF check
        if (!isset($_POST['csrf']) || $_POST['csrf'] !== ($_SESSION['csrf'] ?? '')) {
            $_SESSION['flash_error'] = 'Token bảo mật không hợp lệ';
            header('Location: ' . BASE_URL . 'admin/comments');
            exit;
        }

        $comment = PostComment::findById($id);
        if (!$comment) {
            $_SESSION['flash_error'] = 'Bình luận không tồn tại';
            header('Location: ' . BASE_URL . 'admin/comments');
            exit;
        }

        try {
            PostComment::delete($id);
            $_SESSION['flash_success'] = 'Đã xóa bình luận';
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'Có lỗi xảy ra';
        }

        header('Location: ' . $_SERVER['HTTP_REFERER'] ?? BASE_URL . 'admin/comments');
        exit;
    }
}
