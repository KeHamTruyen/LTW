<?php
namespace App\Controllers\Admin;

use Core\Controller;
use Core\Auth;
use App\Models\Home;

class HomeController extends Controller
{
    public function __construct()
    {
        Auth::requireAdmin();
    }

    public function index(): void
    {
        $home = Home::get();
        $this->view('admin/home/index', [
            'title' => 'Quản lý trang Home',
            'home' => $home,
        ], null, 'admin');
    }

    public function update(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . 'admin/home');
            exit;
        }

        // CSRF protection
        if (!isset($_POST['csrf']) || !isset($_SESSION['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
            $_SESSION['flash_error'] = 'Token bảo mật không hợp lệ';
            header('Location: ' . BASE_URL . 'admin/home');
            exit;
        }

        // Get form data
        $data = [
            'hero_title' => isset($_POST['hero_title']) ? trim(str_replace(['<br>', '<br />', '<br/>'], "\n", $_POST['hero_title'])) : '',
            'hero_subtitle' => isset($_POST['hero_subtitle']) ? trim($_POST['hero_subtitle']) : '',
            'hero_button_text' => isset($_POST['hero_button_text']) ? trim($_POST['hero_button_text']) : '',
            'company_name' => isset($_POST['company_name']) ? trim($_POST['company_name']) : '',
            'company_phone' => isset($_POST['company_phone']) ? trim($_POST['company_phone']) : '',
            'company_email' => isset($_POST['company_email']) ? trim($_POST['company_email']) : '',
            'company_address' => isset($_POST['company_address']) ? trim($_POST['company_address']) : null,
            'service_title' => isset($_POST['service_title']) ? trim($_POST['service_title']) : '',
            'service_subtitle' => isset($_POST['service_subtitle']) ? trim($_POST['service_subtitle']) : '',
            'about_title' => isset($_POST['about_title']) ? trim($_POST['about_title']) : '',
            'about_subtitle' => isset($_POST['about_subtitle']) ? trim($_POST['about_subtitle']) : '',
            'about_description' => isset($_POST['about_description']) ? trim($_POST['about_description']) : null,
            'about_button_text' => isset($_POST['about_button_text']) ? trim($_POST['about_button_text']) : '',
        ];

        // Handle service combos (JSON)
        if (isset($_POST['service_combos']) && is_array($_POST['service_combos'])) {
            $combos = [];
            foreach ($_POST['service_combos'] as $combo) {
                if (!empty($combo['name']) && !empty($combo['price'])) {
                    $items = [];
                    if (isset($combo['items']) && is_array($combo['items'])) {
                        foreach ($combo['items'] as $item) {
                            if (!empty($item['name'])) {
                                // Checkbox: if present and equals '1', it's checked; otherwise false
                                $included = isset($item['included']) && $item['included'] === '1';
                                $items[] = [
                                    'name' => $item['name'],
                                    'included' => $included
                                ];
                            }
                        }
                    }
                    $combos[] = [
                        'name' => $combo['name'],
                        'price' => $combo['price'],
                        'items' => $items
                    ];
                }
            }
            $data['service_combos'] = !empty($combos) ? json_encode($combos, JSON_UNESCAPED_UNICODE) : null;
        }

        // Handle image uploads
        $home = Home::get();
        
        // Keep existing products and posts data (not editable anymore)
        if ($home) {
            $data['products_title'] = $home['products_title'] ?? '';
            $data['products_subtitle'] = $home['products_subtitle'] ?? '';
            $data['products_button_text'] = $home['products_button_text'] ?? '';
            $data['posts_title'] = $home['posts_title'] ?? '';
            $data['posts_subtitle'] = $home['posts_subtitle'] ?? '';
        } else {
            $data['products_title'] = '';
            $data['products_subtitle'] = '';
            $data['products_button_text'] = '';
            $data['posts_title'] = '';
            $data['posts_subtitle'] = '';
        }
        
        // Hero image
        if (!empty($_FILES['hero_image']['name'])) {
            $image = $this->handleImageUpload('hero_image', 'home');
            if ($image) {
                $data['hero_image_url'] = $image;
            } elseif ($home && !empty($home['hero_image_url'])) {
                $data['hero_image_url'] = $home['hero_image_url'];
            }
        } elseif ($home && !empty($home['hero_image_url'])) {
            $data['hero_image_url'] = $home['hero_image_url'];
        }

        // Company logo
        if (!empty($_FILES['company_logo']['name'])) {
            $logo = $this->handleImageUpload('company_logo', 'home');
            if ($logo) {
                $data['company_logo_url'] = $logo;
            } elseif ($home && !empty($home['company_logo_url'])) {
                $data['company_logo_url'] = $home['company_logo_url'];
            }
        } elseif ($home && !empty($home['company_logo_url'])) {
            $data['company_logo_url'] = $home['company_logo_url'];
        }

        // About image
        if (!empty($_FILES['about_image']['name'])) {
            $image = $this->handleImageUpload('about_image', 'home');
            if ($image) {
                $data['about_image_url'] = $image;
            } elseif ($home && !empty($home['about_image_url'])) {
                $data['about_image_url'] = $home['about_image_url'];
            }
        } elseif ($home && !empty($home['about_image_url'])) {
            $data['about_image_url'] = $home['about_image_url'];
        }

        if (Home::update($data)) {
            $_SESSION['flash_success'] = 'Cập nhật trang Home thành công!';
        } else {
            $_SESSION['flash_error'] = 'Có lỗi xảy ra khi cập nhật';
        }

        header('Location: ' . BASE_URL . 'admin/home');
        exit;
    }

    private function handleImageUpload(string $fieldName, string $subfolder = 'home'): ?string
    {
        if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $file = $_FILES[$fieldName];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        
        if (!in_array($ext, $allowed) || $file['size'] > 5 * 1024 * 1024) {
            return null;
        }

        $uploadDir = __DIR__ . '/../../../public/uploads/' . $subfolder . '/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $filename = uniqid($fieldName . '_') . '.' . $ext;
        $filepath = $uploadDir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return BASE_URL . 'uploads/' . $subfolder . '/' . $filename;
        }

        return null;
    }
}


