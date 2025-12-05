<?php
namespace App\Models;

use Core\Database;
use PDO;

class About
{
    /**
     * Get about page content (single row table)
     */
    public static function get(): ?array
    {
        $sql = "SELECT * FROM about_page ORDER BY id DESC LIMIT 1";
        $stmt = Database::conn()->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Update about page content
     */
    public static function update(array $data): bool
    {
        $about = self::get();
        
        if ($about) {
            // Update existing
            $sql = "UPDATE about_page SET title = :title, description = :description, 
                    mission = :mission, vision = :vision, image = :image, updated_at = NOW() 
                    WHERE id = :id";
            $stmt = Database::conn()->prepare($sql);
            return $stmt->execute([
                ':id' => $about['id'],
                ':title' => $data['title'],
                ':description' => $data['description'],
                ':mission' => $data['mission'],
                ':vision' => $data['vision'],
                ':image' => $data['image'] ?? null,
            ]);
        } else {
            // Insert new
            $sql = "INSERT INTO about_page (title, description, mission, vision, image, updated_at) 
                    VALUES (:title, :description, :mission, :vision, :image, NOW())";
            $stmt = Database::conn()->prepare($sql);
            return $stmt->execute([
                ':title' => $data['title'],
                ':description' => $data['description'],
                ':mission' => $data['mission'],
                ':vision' => $data['vision'],
                ':image' => $data['image'] ?? null,
            ]);
        }
    }
}

