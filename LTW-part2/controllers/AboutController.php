<?php
require_once __DIR__ . '/../models/AboutModel.php';
class AboutController {
    public function index() {
        $about = AboutModel::get();
        require __DIR__ . '/../templates/header.php';
        require __DIR__ . '/../public/pages/about.php';
        require __DIR__ . '/../templates/footer.php';
    }
    public function adminIndex() {
        // simple admin auth (placeholder) - in real app protect routes
        $about = AboutModel::get();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // CSRF token
            if (!isset($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
                $_SESSION['flash'] = 'Invalid CSRF token';
                header('Location: ?admin=1&page=about'); exit;
            }
            // validate
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $mission = trim($_POST['mission'] ?? '');
            $vision = trim($_POST['vision'] ?? '');
            $image = $about['image'] ?? '';
            // handle upload
            if (!empty($_FILES['image']['name'])) {
                $f = $_FILES['image'];
                $ext = strtolower(pathinfo($f['name'], PATHINFO_EXTENSION));
                $allowed = ['jpg','jpeg','png','webp'];
                if (!in_array($ext, $allowed) || $f['size'] > 2*1024*1024) {
                    $_SESSION['flash'] = 'Invalid image';
                    header('Location: ?admin=1&page=about'); exit;
                }
                $newname = uniqid('about_') . '.' . $ext;
                move_uploaded_file($f['tmp_name'], UPLOAD_DIR.$newname);
                $image = $newname;
            }
            $ok = AboutModel::update([
                'id' => $about['id'],
                'title'=>$title,
                'description'=>$description,
                'mission'=>$mission,
                'vision'=>$vision,
                'image'=>$image
            ]);
            $_SESSION['flash'] = $ok ? 'Updated' : 'Update failed';
            header('Location: ?admin=1&page=about'); exit;
        }
        // generate CSRF
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
        require __DIR__ . '/../templates/header.php';
        require __DIR__ . '/../admin/about/index.php';
        require __DIR__ . '/../templates/footer.php';
    }
}
