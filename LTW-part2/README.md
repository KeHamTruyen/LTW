# LTW - Part 2 (About + FAQ)

This package contains a minimal PHP MVC implementation for the **About** page and **FAQ** with admin CRUD.
- PHP 7.0+ (PDO)
- Uses Bootstrap 5 via CDN for quick UI
- Stores uploads in `/uploads`
- DB schema in `db/schema.sql`

## Quick setup
1. Import `db/schema.sql` into MySQL.
2. Put project in your web root.
3. Adjust `config.php` DB credentials.
4. Access:
   - Public: `http://yourhost/index.php?page=about` and `?page=faq`
   - Admin: `http://yourhost/index.php?admin=1&page=about` and `?admin=1&page=faq`

## Notes
- Basic CSRF protection via session token for admin forms.
- Image upload checks for extension and size (max 2MB).
