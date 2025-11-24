<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/controllers/AboutController.php';
require_once __DIR__ . '/controllers/FAQController.php';

// simple router by "page" param
$page = $_GET['page'] ?? 'home';
$admin = isset($_GET['admin']) ? true : false;

if ($admin) {
    if ($page === 'about') {
        (new AboutController())->adminIndex();
    } elseif ($page === 'faq') {
        (new FAQController())->adminIndex();
    } elseif ($page === 'faq_add') {
        (new FAQController())->add();
    } elseif ($page === 'faq_edit') {
        (new FAQController())->edit();
    } elseif ($page === 'faq_delete') {
        (new FAQController())->delete();
    } else {
        echo 'Admin: Unknown page';
    }
    exit;
}

// Public pages
if ($page === 'about') {
    (new AboutController())->index();
} elseif ($page === 'faq') {
    (new FAQController())->index();
} else {
    header('Location: ?page=about'); exit;
}
