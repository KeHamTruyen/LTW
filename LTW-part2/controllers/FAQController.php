<?php
require_once __DIR__ . '/../models/FAQModel.php';
class FAQController {
    public function index() {
        $q = trim($_GET['q'] ?? '');
        $page = max(1, (int)($_GET['p'] ?? 1));
        $limit = 6;
        $offset = ($page-1)*$limit;
        $total = FAQModel::countAll($q);
        $items = FAQModel::getAll($limit, $offset, $q);
        require __DIR__ . '/../templates/header.php';
        require __DIR__ . '/../public/pages/faq.php';
        require __DIR__ . '/../templates/footer.php';
    }

    // Admin functions
    public function adminIndex() {
        $q = trim($_GET['q'] ?? '');
        $page = max(1, (int)($_GET['p'] ?? 1));
        $limit = 10;
        $offset = ($page-1)*$limit;
        $total = FAQModel::countAll($q);
        $items = FAQModel::getAll($limit, $offset, $q);
        // flash
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        require __DIR__ . '/../templates/header.php';
        require __DIR__ . '/../admin/faq/index.php';
        require __DIR__ . '/../templates/footer.php';
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
                $_SESSION['flash'] = 'Invalid CSRF';
                header('Location: ?admin=1&page=faq'); exit;
            }
            $question = trim($_POST['question'] ?? '');
            $answer = trim($_POST['answer'] ?? '');
            if ($question === '' || $answer === '') {
                $_SESSION['flash'] = 'Missing fields';
                header('Location: ?admin=1&page=faq'); exit;
            }
            FAQModel::insert(['question'=>$question, 'answer'=>$answer]);
            $_SESSION['flash'] = 'Added';
            header('Location: ?admin=1&page=faq'); exit;
        }
        // show form
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
        require __DIR__ . '/../templates/header.php';
        require __DIR__ . '/../admin/faq/add.php';
        require __DIR__ . '/../templates/footer.php';
    }

    public function edit() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) { header('Location: ?admin=1&page=faq'); exit; }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf']) || $_POST['csrf'] !== $_SESSION['csrf']) {
                $_SESSION['flash'] = 'Invalid CSRF';
                header('Location: ?admin=1&page=faq'); exit;
            }
            $question = trim($_POST['question'] ?? '');
            $answer = trim($_POST['answer'] ?? '');
            if ($question === '' || $answer === '') {
                $_SESSION['flash'] = 'Missing fields';
                header('Location: ?admin=1&page=faq'); exit;
            }
            FAQModel::update($id, ['question'=>$question, 'answer'=>$answer]);
            $_SESSION['flash'] = 'Updated';
            header('Location: ?admin=1&page=faq'); exit;
        }
        $item = FAQModel::get($id);
        $_SESSION['csrf'] = bin2hex(random_bytes(16));
        require __DIR__ . '/../templates/header.php';
        require __DIR__ . '/../admin/faq/edit.php';
        require __DIR__ . '/../templates/footer.php';
    }

    public function delete() {
        $id = (int)($_GET['id'] ?? 0);
        if ($id > 0) {
            FAQModel::delete($id);
            $_SESSION['flash'] = 'Deleted';
        }
        header('Location: ?admin=1&page=faq'); exit;
    }
}
