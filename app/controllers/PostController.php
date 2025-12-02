<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\Post;
use App\Models\PostComment;
use App\Models\Category;

class PostController extends Controller
{
    /**
     * List all published posts (public page)
     */
    public function index()
    {
        // Pagination
        $page = isset($_GET['p']) ? max(1, (int)$_GET['p']) : 1;
        $perPage = 12;
        $offset = ($page - 1) * $perPage;

        // Search
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        
        // Sort
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'latest';

        // Filters
        $filters = [
            'status' => 'published',
            'limit' => $perPage,
            'offset' => $offset,
        ];

        if (!empty($search)) {
            $filters['search'] = $search;
        }
        
        // Apply sort
        if ($sort === 'oldest') {
            $filters['order_by'] = 'published_at ASC';
        } else {
            $filters['order_by'] = 'published_at DESC';
        }

        // Get posts and total count
        $posts = Post::getAll($filters);
        $totalPosts = Post::count($filters);
        $totalPages = ceil($totalPosts / $perPage);

        // Get categories with post counts
        $categories = Category::countPostsByCategory();

        // Render view with public layout
        $this->view('posts/index', [
            'posts' => $posts,
            'search' => $search,
            'sort' => $sort,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'totalPosts' => $totalPosts,
            'categories' => $categories,
            'activeMenu' => 'blog',
        ], 'Blog - Tin tức thú cưng', 'public');
    }

    /**
     * View single post detail
     */
    public function show()
    {
        $slug = $_GET['slug'] ?? '';
        
        if (empty($slug)) {
            header('Location: ' . BASE_URL . 'posts');
            exit;
        }

        $post = Post::findBySlug($slug);

        if (!$post || $post['status'] !== 'published') {
            http_response_code(404);
            echo '404 - Bài viết không tồn tại';
            exit;
        }

        // Get approved comments
        $comments = PostComment::getByPost($post['id'], ['status' => 'approved']);
        $commentCount = count($comments);
        
        // Get rating stats
        $avgRating = PostComment::getAverageRating($post['id']);
        $ratingCount = PostComment::getRatingCount($post['id']);

        // Get prev/next posts
        $prevPost = Post::getPrevious($post['id'], $post['published_at']);
        $nextPost = Post::getNext($post['id'], $post['published_at']);

        // Render view with public layout
        $this->view('posts/show', [
            'post' => $post,
            'comments' => $comments,
            'commentCount' => $commentCount,
            'avgRating' => $avgRating,
            'ratingCount' => $ratingCount,
            'prevPost' => $prevPost,
            'nextPost' => $nextPost,
            'activeMenu' => 'blog',
            'hideBlogHero' => true, // Hide the hero section with 4 dogs
        ], $post['title'], 'public');
    }

    /**
     * Submit comment (public)
     */
    public function submitComment()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit;
        }

        // CSRF protection
        if (!isset($_POST['csrf']) || !isset($_SESSION['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
            $_SESSION['flash_error'] = 'Token bảo mật không hợp lệ';
            header('Location: ' . $_SERVER['HTTP_REFERER'] ?? BASE_URL . 'posts');
            exit;
        }

        // Validate input
        $postId = isset($_POST['post_id']) ? (int)$_POST['post_id'] : 0;
        $content = isset($_POST['content']) ? trim($_POST['content']) : '';
        $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : null;
        $authorName = isset($_POST['author_name']) ? trim($_POST['author_name']) : '';
        $authorEmail = isset($_POST['author_email']) ? trim($_POST['author_email']) : '';

        $errors = [];

        if ($postId <= 0) {
            $errors[] = 'Bài viết không hợp lệ';
        }

        if (empty($content) || mb_strlen($content) < 10) {
            $errors[] = 'Nội dung bình luận phải có ít nhất 10 ký tự';
        }

        if (mb_strlen($content) > 1000) {
            $errors[] = 'Nội dung bình luận không được quá 1000 ký tự';
        }

        if ($rating !== null && ($rating < 1 || $rating > 5)) {
            $errors[] = 'Đánh giá phải từ 1-5 sao';
        }

        // If not logged in, require name and email
        if (!isset($_SESSION['user_id'])) {
            if (empty($authorName) || mb_strlen($authorName) < 2) {
                $errors[] = 'Vui lòng nhập tên (ít nhất 2 ký tự)';
            }

            if (empty($authorEmail) || !filter_var($authorEmail, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Vui lòng nhập email hợp lệ';
            }
        }

        if (!empty($errors)) {
            $_SESSION['flash_error'] = implode('<br>', $errors);
            header('Location: ' . $_SERVER['HTTP_REFERER'] ?? BASE_URL . 'posts');
            exit;
        }

        // Create comment
        $commentData = [
            'post_id' => $postId,
            'user_id' => $_SESSION['user_id'] ?? null,
            'author_name' => $authorName,
            'author_email' => $authorEmail,
            'content' => $content,
            'rating' => $rating,
            'status' => 'pending', // Admin must approve
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
        ];

        try {
            PostComment::create($commentData);
            $_SESSION['flash_success'] = 'Bình luận của bạn đã được gửi và đang chờ duyệt';
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'Có lỗi xảy ra khi gửi bình luận';
            error_log('Comment creation error: ' . $e->getMessage());
        }

        header('Location: ' . $_SERVER['HTTP_REFERER'] ?? BASE_URL . 'posts');
        exit;
    }
}
