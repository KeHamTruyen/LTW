<?php
namespace App\Controllers\Admin;

use Core\Controller;
use Core\Auth;
use App\Models\About;

class AboutController extends Controller
{
    public function __construct()
    {
        Auth::requireAdmin();
    }

    public function index(): void
    {
        $about = About::get();
        $this->view('admin/about/index', [
            'title' => 'Quản lý trang Giới thiệu',
            'about' => $about,
        ], 'admin');
    }

    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'admin/about');
            exit;
        }

        // CSRF protection
        if (!isset($_POST['csrf']) || !isset($_SESSION['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
            $_SESSION['flash_error'] = 'Token bảo mật không hợp lệ';
            header('Location: ' . BASE_URL . 'admin/about');
            exit;
        }

        $title = isset($_POST['title']) ? trim($_POST['title']) : '';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $mission = isset($_POST['mission']) ? trim($_POST['mission']) : '';
        $vision = isset($_POST['vision']) ? trim($_POST['vision']) : '';

        // Handle image upload
        $image = null;
        $about = About::get();
        if ($about && !empty($about['image'])) {
            $image = $about['image'];
        }

        if (!empty($_FILES['image']['name'])) {
            $file = $_FILES['image'];
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            
            if (in_array($ext, $allowed) && $file['size'] <= 2 * 1024 * 1024) {
                $uploadDir = __DIR__ . '/../../../public/uploads/about/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                $filename = uniqid('about_') . '.' . $ext;
                $filepath = $uploadDir . $filename;
                
                if (move_uploaded_file($file['tmp_name'], $filepath)) {
                    $image = BASE_URL . 'uploads/about/' . $filename;
                }
            }
        }

        if (About::update([
            'title' => $title,
            'description' => $description,
            'mission' => $mission,
            'vision' => $vision,
            'image' => $image,
        ])) {
            $_SESSION['flash_success'] = 'Cập nhật trang Giới thiệu thành công!';
        } else {
            $_SESSION['flash_error'] = 'Có lỗi xảy ra khi cập nhật';
        }

        header('Location: ' . BASE_URL . 'admin/about');
        exit;
    }
}

