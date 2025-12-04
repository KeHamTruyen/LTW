<?php
namespace App\Controllers\Admin;

use Core\Auth;
use Core\Controller;
use Core\Database;

class PageController extends Controller
{
    public function dashboard(): void
    {
        Auth::requireAdmin();
        $pdo = Database::conn();
        $stats = [ 
            'posts' => 0, 
            'drafts' => 0, 
            'comments_pending' => 0, 
            'total_comments' => 0,
            'users' => 0 
        ];
        try { $stats['posts'] = (int)$pdo->query("SELECT COUNT(*) FROM posts")->fetchColumn(); } catch (\Throwable $e) {}
        try { $stats['drafts'] = (int)$pdo->query("SELECT COUNT(*) FROM posts WHERE status='draft'")->fetchColumn(); } catch (\Throwable $e) {}
        try { $stats['comments_pending'] = (int)$pdo->query("SELECT COUNT(*) FROM post_comments WHERE status='pending'")->fetchColumn(); } catch (\Throwable $e) {}
        try { $stats['total_comments'] = (int)$pdo->query("SELECT COUNT(*) FROM post_comments")->fetchColumn(); } catch (\Throwable $e) {}
        try { $stats['users'] = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(); } catch (\Throwable $e) {}
        $this->view('admin.dashboard', [ '_layout' => 'admin', 'title' => 'Tổng quan', 'stats' => $stats ]);
    }
}
