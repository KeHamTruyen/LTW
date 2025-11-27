<?php
namespace App\Controllers\Admin;

use Core\Controller;
use App\Models\Post;

class PostController extends Controller
{
    public function __construct()
    {
        // Check admin authentication
        if (!isset($_SESSION['user_id']) || ($_SESSION['user_role'] ?? '') !== 'admin') {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }
    }

    /**
     * Admin post list page
     */
    public function index()
    {
        // Pagination
        $page = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
        $perPage = 20;
        $offset = ($page - 1) * $perPage;

        // Search and filters
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $status = isset($_GET['status']) ? trim($_GET['status']) : '';

        $filters = [
            'limit' => $perPage,
            'offset' => $offset,
        ];

        if (!empty($search)) {
            $filters['search'] = $search;
        }

        if (!empty($status) && in_array($status, ['draft', 'published'])) {
            $filters['status'] = $status;
        }

        $posts = Post::getAll($filters);
        $totalPosts = Post::count($filters);
        $totalPages = ceil($totalPosts / $perPage);

        $this->view('admin/posts/index', [
            'posts' => $posts,
            'search' => $search,
            'status' => $status,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalPosts' => $totalPosts,
        ], 'Quản lý tin tức', 'admin');
    }

    /**
     * Show create form
     */
    public function create()
    {
        // Generate CSRF token
        $_SESSION['csrf'] = bin2hex(random_bytes(32));

        $this->view('admin/posts/form', [
            'post' => null,
            'isEdit' => false,
        ], 'Thêm tin tức mới', 'admin');
    }

    /**
     * Handle create post
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'admin/posts');
            exit;
        }

        // CSRF check
        if (!isset($_POST['csrf']) || $_POST['csrf'] !== ($_SESSION['csrf'] ?? '')) {
            $_SESSION['flash_error'] = 'Token bảo mật không hợp lệ';
            header('Location: ' . BASE_URL . 'admin/posts/create');
            exit;
        }

        // Validate and sanitize input
        $errors = $this->validatePostData($_POST);

        if (!empty($errors)) {
            $_SESSION['flash_error'] = implode('<br>', $errors);
            $_SESSION['old_input'] = $_POST;
            header('Location: ' . BASE_URL . 'admin/posts/create');
            exit;
        }

        // Handle image upload
        $coverImageUrl = null;
        if (!empty($_FILES['cover_image']['name'])) {
            $uploadResult = $this->handleImageUpload($_FILES['cover_image']);
            if ($uploadResult['success']) {
                $coverImageUrl = $uploadResult['filename'];
            } else {
                $_SESSION['flash_error'] = $uploadResult['error'];
                $_SESSION['old_input'] = $_POST;
                header('Location: ' . BASE_URL . 'admin/posts/create');
                exit;
            }
        }

        // Generate slug
        $slug = Post::generateSlug($_POST['title']);
        
        // Ensure slug is unique
        $originalSlug = $slug;
        $counter = 1;
        while (Post::slugExists($slug)) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Prepare data
        $postData = [
            'author_user_id' => $_SESSION['user_id'],
            'title' => trim($_POST['title']),
            'slug' => $slug,
            'summary' => trim($_POST['summary']),
            'content_html' => $_POST['content_html'], // Will be sanitized in view
            'cover_image_url' => $coverImageUrl,
            'status' => $_POST['status'] ?? 'draft',
            'published_at' => ($_POST['status'] === 'published') ? date('Y-m-d H:i:s') : null,
        ];

        try {
            $postId = Post::create($postData);
            $_SESSION['flash_success'] = 'Đã tạo bài viết thành công';
            header('Location: ' . BASE_URL . 'admin/posts/edit?id=' . $postId);
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
            $_SESSION['old_input'] = $_POST;
            header('Location: ' . BASE_URL . 'admin/posts/create');
        }
        exit;
    }

    /**
     * Show edit form
     */
    public function edit()
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $post = Post::findById($id);

        if (!$post) {
            $_SESSION['flash_error'] = 'Bài viết không tồn tại';
            header('Location: ' . BASE_URL . 'admin/posts');
            exit;
        }

        // Generate CSRF token
        $_SESSION['csrf'] = bin2hex(random_bytes(32));

        $this->view('admin/posts/form', [
            'post' => $post,
            'isEdit' => true,
        ], 'Chỉnh sửa tin tức', 'admin');
    }

    /**
     * Handle update post
     */
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'admin/posts');
            exit;
        }

        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $post = Post::findById($id);

        if (!$post) {
            $_SESSION['flash_error'] = 'Bài viết không tồn tại';
            header('Location: ' . BASE_URL . 'admin/posts');
            exit;
        }

        // CSRF check
        if (!isset($_POST['csrf']) || $_POST['csrf'] !== ($_SESSION['csrf'] ?? '')) {
            $_SESSION['flash_error'] = 'Token bảo mật không hợp lệ';
            header('Location: ' . BASE_URL . 'admin/posts/edit?id=' . $id);
            exit;
        }

        // Validate
        $errors = $this->validatePostData($_POST);

        if (!empty($errors)) {
            $_SESSION['flash_error'] = implode('<br>', $errors);
            header('Location: ' . BASE_URL . 'admin/posts/edit?id=' . $id);
            exit;
        }

        // Handle image upload
        $coverImageUrl = $post['cover_image_url'];
        if (!empty($_FILES['cover_image']['name'])) {
            $uploadResult = $this->handleImageUpload($_FILES['cover_image']);
            if ($uploadResult['success']) {
                // Delete old image
                if ($post['cover_image_url']) {
                    $oldPath = __DIR__ . '/../../public/uploads/' . $post['cover_image_url'];
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
                $coverImageUrl = $uploadResult['filename'];
            } else {
                $_SESSION['flash_error'] = $uploadResult['error'];
                header('Location: ' . BASE_URL . 'admin/posts/edit?id=' . $id);
                exit;
            }
        }

        // Generate slug if title changed
        $slug = $post['slug'];
        if (trim($_POST['title']) !== $post['title']) {
            $slug = Post::generateSlug($_POST['title']);
            
            // Ensure unique slug
            $originalSlug = $slug;
            $counter = 1;
            while (Post::slugExists($slug, $id)) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        // Prepare data
        $postData = [
            'title' => trim($_POST['title']),
            'slug' => $slug,
            'summary' => trim($_POST['summary']),
            'content_html' => $_POST['content_html'],
            'cover_image_url' => $coverImageUrl,
            'status' => $_POST['status'] ?? 'draft',
            'published_at' => ($_POST['status'] === 'published' && !$post['published_at']) 
                ? date('Y-m-d H:i:s') 
                : $post['published_at'],
        ];

        try {
            Post::update($id, $postData);
            $_SESSION['flash_success'] = 'Đã cập nhật bài viết thành công';
            header('Location: ' . BASE_URL . 'admin/posts/edit?id=' . $id);
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
            header('Location: ' . BASE_URL . 'admin/posts/edit?id=' . $id);
        }
        exit;
    }

    /**
     * Delete post
     */
    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'admin/posts');
            exit;
        }

        $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
        $post = Post::findById($id);

        if (!$post) {
            $_SESSION['flash_error'] = 'Bài viết không tồn tại';
            header('Location: ' . BASE_URL . 'admin/posts');
            exit;
        }

        // CSRF check
        if (!isset($_POST['csrf']) || $_POST['csrf'] !== ($_SESSION['csrf'] ?? '')) {
            $_SESSION['flash_error'] = 'Token bảo mật không hợp lệ';
            header('Location: ' . BASE_URL . 'admin/posts');
            exit;
        }

        // Delete image file
        if ($post['cover_image_url']) {
            $imagePath = __DIR__ . '/../../public/uploads/' . $post['cover_image_url'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        try {
            Post::delete($id);
            $_SESSION['flash_success'] = 'Đã xóa bài viết thành công';
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'Có lỗi xảy ra: ' . $e->getMessage();
        }

        header('Location: ' . BASE_URL . 'admin/posts');
        exit;
    }

    /**
     * Validate post data
     */
    private function validatePostData(array $data): array
    {
        $errors = [];

        // Title
        if (empty($data['title']) || mb_strlen(trim($data['title'])) < 5) {
            $errors[] = 'Tiêu đề phải có ít nhất 5 ký tự';
        }

        if (mb_strlen(trim($data['title'])) > 255) {
            $errors[] = 'Tiêu đề không được quá 255 ký tự';
        }

        // Summary
        if (empty($data['summary']) || mb_strlen(trim($data['summary'])) < 20) {
            $errors[] = 'Tóm tắt phải có ít nhất 20 ký tự';
        }

        // Content
        if (empty($data['content_html']) || mb_strlen(trim($data['content_html'])) < 50) {
            $errors[] = 'Nội dung phải có ít nhất 50 ký tự';
        }

        // Status
        if (!in_array($data['status'] ?? '', ['draft', 'published'])) {
            $errors[] = 'Trạng thái không hợp lệ';
        }

        return $errors;
    }

    /**
     * Handle image upload
     */
    private function handleImageUpload(array $file): array
    {
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        // Check errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'error' => 'Lỗi upload file'];
        }

        // Check file type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedTypes)) {
            return ['success' => false, 'error' => 'Chỉ chấp nhận file ảnh JPG, PNG, WEBP'];
        }

        // Check file size
        if ($file['size'] > $maxSize) {
            return ['success' => false, 'error' => 'Kích thước file không được vượt quá 5MB'];
        }

        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'post_' . uniqid() . '_' . time() . '.' . $extension;
        
        // Create uploads directory if not exists
        $uploadDir = __DIR__ . '/../../public/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $uploadPath = $uploadDir . $filename;

        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return ['success' => false, 'error' => 'Không thể lưu file'];
        }

        return ['success' => true, 'filename' => $filename];
    }
}
