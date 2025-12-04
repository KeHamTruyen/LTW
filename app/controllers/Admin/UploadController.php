<?php
namespace App\Controllers\Admin;

use Core\Controller;
use Core\Auth;

class UploadController extends Controller
{
    public function __construct()
    {
        Auth::requireAdmin();
    }

    /**
     * Handle image upload for TinyMCE
     */
    public function image()
    {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['error' => 'Invalid request method']);
            exit;
        }

        if (empty($_FILES['file'])) {
            echo json_encode(['error' => 'No file uploaded']);
            exit;
        }

        $file = $_FILES['file'];
        $result = $this->handleImageUpload($file);

        if ($result['success']) {
            echo json_encode(['location' => BASE_URL . 'uploads/' . $result['filename']]);
        } else {
            echo json_encode(['error' => $result['error']]);
        }
        exit;
    }

    /**
     * Handle image upload
     */
    private function handleImageUpload(array $file): array
    {
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        // Check errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'error' => 'Upload error: ' . $file['error']];
        }

        // Check file type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedTypes)) {
            return ['success' => false, 'error' => 'Only JPG, PNG, WEBP, GIF images allowed'];
        }

        // Check file size
        if ($file['size'] > $maxSize) {
            return ['success' => false, 'error' => 'File size cannot exceed 5MB'];
        }

        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'content_' . uniqid() . '_' . time() . '.' . $extension;
        
        // Create uploads directory if not exists
        $uploadDir = dirname(__DIR__, 3) . '/public/uploads/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $uploadPath = $uploadDir . $filename;

        // Move uploaded file
        if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return ['success' => false, 'error' => 'Failed to save file'];
        }

        return ['success' => true, 'filename' => $filename];
    }
}
