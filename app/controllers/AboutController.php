<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\About;

class AboutController extends Controller
{
    public function index(): void
    {
        $about = About::get();
        $this->view('about.index', [
            'title' => 'Giới thiệu - ' . APP_NAME,
            'about' => $about,
        ]);
    }
}

