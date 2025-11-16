# PetCare — PHP BE + Vite/React FE (CSS-only)

Kiến trúc tách 2 phần: `BE/` (PHP thuần, chạy trên XAMPP/Apache) và `FE/` (Vite/React, dùng Tabler CSS). FE build ra bundle, BE tự nạp qua manifest hoặc HMR khi chạy dev server.

## Yêu cầu

- XAMPP (Apache + MySQL) trên Windows.
- Node.js 18+ và npm.

## Cấu trúc ngắn gọn

```
LTW/
├─ BE/
│  ├─ app/                 # core (Router, Controller, Database, Auth, Assets), controllers, views
│  └─ public/              # DocumentRoot của Apache
│     └─ assets/build/     # FE build output (manifest.json + bundle)
├─ FE/
│  ├─ resources/js/main.tsx    # Entry Vite (import Tabler + app.css)
│  ├─ resources/js/components/  # React components
│  ├─ resources/css/app.css     # CSS tuỳ biến
│  ├─ vendor/tabler/dist/       # Tabler CSS/JS (đã copy vào FE)
│  └─ vite.config.ts
└─ database/schema.sql
```

## Cài đặt nhanh

1. Database

- Mở phpMyAdmin → import `database/schema.sql`.

2. Cấu hình BE

- Mở `BE/app/config.php` → chỉnh thông tin MySQL nếu cần (XAMPP mặc định: user `root`, password trống).

3. Cài FE dependencies

```cmd
cd C:\xampp\htdocs\LTW\FE
npm install
```

## Chạy dự án

Chọn 1 trong 2 cách dưới đây.

1. Dev nhanh (HMR)

- Start Vite dev server:

```cmd
cd C:\xampp\htdocs\LTW\FE
npm run dev
```

- Mở BE (Apache):
  - Mặc định: `http://localhost/LTW/BE/public/`
  - Hoặc VirtualHost: `http://petcare.local/`
- BE tự phát hiện dev server (port 5173) và nạp `@vite/client` + `resources/js/main.tsx`. Sửa FE sẽ tự reload.

2. Build production

```cmd
cd C:\xampp\htdocs\LTW\FE
npm run build
```

- Reload trang BE. BE sẽ nạp bundle hash qua `BE/public/assets/build/manifest.json`.

## Nơi viết FE

- JS entry: `FE/resources/js/main.tsx`
- Components: `FE/resources/js/components/*` (ví dụ `HelloReact.tsx`)
- CSS: `FE/resources/css/app.css`
- Tabler: đã nằm ở `FE/vendor/tabler/dist` và được import trong `main.tsx`.
- Mount vào view BE: chèn `<div id="react-root" data-name="..."></div>` trong file view (ví dụ trong `BE/app/views/...`) — React sẽ render vào đây.

## URL chính

- BE: `http://localhost/LTW/BE/public/` (hoặc `http://petcare.local/`)
- Admin (nếu có): thêm `/admin` vào cuối URL BE.

## Gỡ lỗi nhanh

- Không có style/JS ở BE: kiểm tra đã chạy `npm run build` (có `manifest.json` trong `BE/public/assets/build/`) hoặc Vite dev server đang chạy.
- HMR không hoạt động: giữ terminal `npm run dev` đang mở, vào DevTools → Network xem `/@vite/client` và `/resources/js/main.tsx` trả về 200.

Hoàn tất. Bạn có thể bắt đầu code FE trong `FE/resources/*` và xem kết quả trực tiếp trên trang BE.
