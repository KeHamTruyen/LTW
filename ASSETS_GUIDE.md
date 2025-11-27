# 📦 Hướng dẫn sử dụng Tabler Assets

## 🎨 Cấu trúc Assets

```
LTW/
├── public/
│   └── assets/
│       └── tabler/           # Tabler Framework (từ dashboard/dist/)
│           ├── css/
│           │   ├── tabler.min.css          # CSS chính
│           │   ├── tabler-flags.min.css    # Icons flags
│           │   ├── tabler-payments.min.css # Payment icons
│           │   └── tabler-vendors.min.css  # Vendor styles
│           ├── js/
│           │   └── tabler.min.js           # JS chính
│           └── libs/                       # Third-party libraries
│               ├── hugerte/                # WYSIWYG Editor
│               ├── litepicker/             # Date picker
│               ├── tom-select/             # Select dropdown
│               └── ...
│
└── dashboard/                # Template tham khảo (giữ lại)
    ├── *.html                # 100+ HTML examples
    └── static/               # Images, logos
```

---

## 🔗 Cách sử dụng trong Views

### 1. **Layout Admin** (`app/views/layouts/admin.php`)

```php
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Dashboard' ?> - PetCare</title>
    
    <!-- Tabler CSS -->
    <link href="<?= BASE_URL ?>assets/tabler/css/tabler.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/tabler/css/tabler-flags.min.css" rel="stylesheet">
    <link href="<?= BASE_URL ?>assets/tabler/css/tabler-payments.min.css" rel="stylesheet">
    
    <!-- Custom CSS (nếu có) -->
    <link href="<?= BASE_URL ?>assets/css/admin.css" rel="stylesheet">
</head>
<body>
    <div class="page">
        <!-- Sidebar -->
        <aside class="navbar navbar-vertical navbar-expand-lg">
            <!-- Menu content -->
        </aside>
        
        <!-- Main Content -->
        <div class="page-wrapper">
            <?php include $content; ?>
        </div>
    </div>
    
    <!-- Tabler JS -->
    <script src="<?= BASE_URL ?>assets/tabler/js/tabler.min.js"></script>
    
    <!-- Custom JS (nếu có) -->
    <script src="<?= BASE_URL ?>assets/js/admin.js"></script>
</body>
</html>
```

### 2. **Sử dụng WYSIWYG Editor** (cho Post form)

```php
<!-- Include HugeRTE (TinyMCE alternative trong Tabler) -->
<link href="<?= BASE_URL ?>assets/tabler/libs/hugerte/skins/ui/hugerte-5/skin.min.css" rel="stylesheet">
<script src="<?= BASE_URL ?>assets/tabler/libs/hugerte/hugerte.min.js"></script>

<textarea id="content" name="content"><?= htmlspecialchars($post['content'] ?? '') ?></textarea>

<script>
hugerte.init({
    selector: '#content',
    height: 400,
    menubar: false,
    plugins: ['lists', 'link', 'image', 'code'],
    toolbar: 'undo redo | bold italic | bullist numlist | link image | code'
});
</script>
```

### 3. **Sử dụng Date Picker**

```php
<!-- Litepicker CSS/JS -->
<link href="<?= BASE_URL ?>assets/tabler/libs/litepicker/dist/css/litepicker.css" rel="stylesheet">
<script src="<?= BASE_URL ?>assets/tabler/libs/litepicker/dist/litepicker.js"></script>

<input type="text" id="published_at" class="form-control" placeholder="Chọn ngày">

<script>
new Litepicker({
    element: document.getElementById('published_at'),
    format: 'YYYY-MM-DD HH:mm'
});
</script>
```

### 4. **Sử dụng Tom-Select** (Advanced dropdown)

```php
<!-- Tom-Select CSS/JS -->
<link href="<?= BASE_URL ?>assets/tabler/libs/tom-select/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<script src="<?= BASE_URL ?>assets/tabler/libs/tom-select/dist/js/tom-select.complete.min.js"></script>

<select id="tags" name="tags[]" multiple>
    <option>Tag 1</option>
    <option>Tag 2</option>
</select>

<script>
new TomSelect('#tags', {
    create: true,
    plugins: ['remove_button']
});
</script>
```

---

## 📁 Tham khảo Template HTML

Bạn có thể xem các **file HTML mẫu** trong folder `dashboard/`:

```bash
# Ví dụ:
dashboard/cards.html          # Card components
dashboard/form-elements.html  # Form controls
dashboard/tables.html         # Table styles
dashboard/modals.html         # Modal dialogs
dashboard/buttons.html        # Button styles
```

**Cách tham khảo:**
1. Mở file HTML trong browser: `http://localhost/LTW/dashboard/cards.html`
2. Copy HTML structure bạn thích
3. Chuyển đổi thành PHP view với dynamic data

---

## 🎯 Best Practices

### ✅ DO:
- Dùng `tabler.min.css` và `tabler.min.js` (minified)
- Load CSS trong `<head>`, JS cuối `</body>`
- Sử dụng `BASE_URL` constant cho paths
- Giữ `dashboard/` folder để tham khảo

### ❌ DON'T:
- Không load toàn bộ libs nếu không dùng
- Không edit trực tiếp files trong `assets/tabler/` (sẽ mất khi update)
- Không commit uploaded files (`uploads/`) vào Git

---

## 🔧 Custom CSS/JS

Nếu cần custom thêm:

```
public/
└── assets/
    ├── css/
    │   ├── admin.css         # Your custom admin styles
    │   └── public.css        # Your custom public styles
    └── js/
        ├── admin.js          # Your custom admin scripts
        └── public.js         # Your custom public scripts
```

Load **sau** Tabler để override:

```php
<link href="<?= BASE_URL ?>assets/tabler/css/tabler.min.css" rel="stylesheet">
<link href="<?= BASE_URL ?>assets/css/admin.css" rel="stylesheet"> <!-- Your custom -->
```

---

## 📚 Tài liệu Tabler

- **Official Docs:** https://tabler.io/docs
- **Components:** https://tabler.io/docs/components
- **Icons:** https://tabler.io/icons
- **Local Templates:** `dashboard/*.html` (trong project)

---

✅ **Xong!** Giờ bạn có thể dùng Tabler design system cho toàn bộ admin dashboard!
