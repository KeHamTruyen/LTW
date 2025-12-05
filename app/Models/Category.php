<?php
namespace App\Models;

use Core\Database;

class Category
{
    /**
     * Get all categories
     */
    public static function getAll()
    {
        $stmt = Database::conn()->prepare("SELECT * FROM categories ORDER BY name ASC");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Get category by ID
     */
    public static function find($id)
    {
        $stmt = Database::conn()->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Get category by slug
     */
    public static function findBySlug($slug)
    {
        $stmt = Database::conn()->prepare("SELECT * FROM categories WHERE slug = ?");
        $stmt->execute([$slug]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Count posts by category
     */
    public static function countPostsByCategory()
    {
        $stmt = Database::conn()->prepare("
            SELECT c.id, c.name, c.slug, COUNT(p.id) as post_count
            FROM categories c
            LEFT JOIN posts p ON c.id = p.category_id AND p.status = 'published'
            GROUP BY c.id, c.name, c.slug
            ORDER BY c.name ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
