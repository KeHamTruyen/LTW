<?php
namespace App\Controllers;

use Core\Controller;
use App\Models\About;
use App\Models\Home;

class AboutController extends Controller
{
    public function index(): void
    {
        $about = About::get();
        $homeData = Home::get();
        
        $this->view('about.index', [
            'title' => 'Giới thiệu - ' . APP_NAME,
            'about' => $about,
            'homeData' => $homeData,
            'activeMenu' => 'about',
            'hideBlogHero' => true,
        ], null, 'public');
    }
}

