<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title ?? APP_NAME); ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        main {
            flex: 1;
            display: flex;
            align-items: center;
            /* Căn giữa theo chiều dọc */
            justify-content: center;
            padding-top: 40px;
            padding-bottom: 40px;
            width: 100%;
        }

        .auth-footer {
            margin-top: auto;
            padding: 20px 0;
            text-align: center;
            color: #6c757d;
            font-size: 0.875rem;
            background-color: white;
            border-top: 1px solid #e9ecef;
        }

        .navbar-brand {
            font-weight: 600;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand text-primary" href="<?php echo BASE_URL; ?>">
                <i class="fas fa-paw me-2"></i><?php echo APP_NAME; ?>
            </a>
            <a href="<?php echo BASE_URL; ?>" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i> Về trang chủ
            </a>
        </div>
    </nav>

    <main>
        <?php
        if (isset($content) && is_callable($content)) {
            $content();
        }
        ?>
    </main>

    <footer class="auth-footer">
        <div class="container">
            <small>&copy; <?php echo date('Y'); ?> <?php echo APP_NAME; ?>. All rights reserved.</small>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        setTimeout(function () {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
</body>

</html>