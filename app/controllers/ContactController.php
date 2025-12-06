<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\Contact;

class ContactController extends Controller
{
    /**
     * Show contact page
     */
    public function index(): void
    {
        $this->view('contact/index', [
            'title' => 'Liên hệ - ' . APP_NAME,
            'activeMenu' => 'contact',
            'hideBlogHero' => true, 
        ], 'Liên hệ với chúng tôi', 'public');
    }

    /**
     * Handle contact form submission
     */
    public function submit(): void
    {
        // Validate CSRF token
        if (!isset($_POST['csrf']) || $_POST['csrf'] !== ($_SESSION['csrf'] ?? '')) {
            $_SESSION['flash_error'] = 'Token bảo mật không hợp lệ';
            header('Location: ' . BASE_URL . 'contact');
            exit;
        }

        // Get and validate input
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        $errors = [];

        // Validation
        if (empty($name)) {
            $errors[] = 'Vui lòng nhập tên của bạn';
        } elseif (strlen($name) < 2) {
            $errors[] = 'Tên phải có ít nhất 2 ký tự';
        }

        if (empty($email)) {
            $errors[] = 'Vui lòng nhập email';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email không hợp lệ';
        }

        if (empty($message)) {
            $errors[] = 'Vui lòng nhập nội dung tin nhắn';
        } elseif (strlen($message) < 10) {
            $errors[] = 'Nội dung tin nhắn phải có ít nhất 10 ký tự';
        }

        if (!empty($phone) && !preg_match('/^[0-9+\-\s()]+$/', $phone)) {
            $errors[] = 'Số điện thoại không hợp lệ';
        }

        // If there are errors, redirect back with errors
        if (!empty($errors)) {
            $_SESSION['flash_error'] = implode('<br>', $errors);
            $_SESSION['form_data'] = [
                'name' => htmlspecialchars($name),
                'email' => htmlspecialchars($email),
                'phone' => htmlspecialchars($phone),
                'subject' => htmlspecialchars($subject),
                'message' => htmlspecialchars($message),
            ];
            header('Location: ' . BASE_URL . 'contact');
            exit;
        }

        // Save contact
        try {
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
            
            Contact::create([
                'name' => $name,
                'email' => $email,
                'phone' => $phone ?: null,
                'subject' => $subject ?: null,
                'message' => $message,
                'ip_address' => $ipAddress,
            ]);

            $_SESSION['flash_success'] = 'Cảm ơn bạn đã liên hệ! Chúng tôi sẽ phản hồi sớm nhất có thể.';
            unset($_SESSION['form_data']);
            header('Location: ' . BASE_URL . 'contact');
        } catch (\Exception $e) {
            error_log('Contact submission error: ' . $e->getMessage());
            $_SESSION['flash_error'] = 'Có lỗi xảy ra. Vui lòng thử lại sau.';
            header('Location: ' . BASE_URL . 'contact');
        }
        exit;
    }
}

