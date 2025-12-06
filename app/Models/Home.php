<?php
namespace App\Models;

use Core\Database;
use PDO;

class Home
{
    /**
     * Get home page content 
     */
    public static function get(): ?array
    {
        $sql = "SELECT * FROM home_page ORDER BY id DESC LIMIT 1";
        $stmt = Database::conn()->query($sql);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    /**
     * Update home page content
     */
    public static function update(array $data): bool
    {
        $home = self::get();
        
        if ($home) {
            // Update existing
            $sql = "UPDATE home_page SET 
                    hero_title = :hero_title, 
                    hero_subtitle = :hero_subtitle,
                    hero_button_text = :hero_button_text,
                    hero_image_url = :hero_image_url,
                    company_name = :company_name,
                    company_phone = :company_phone,
                    company_email = :company_email,
                    company_address = :company_address,
                    company_logo_url = :company_logo_url,
                    service_title = :service_title,
                    service_subtitle = :service_subtitle,
                    service_combos = :service_combos,
                    about_title = :about_title,
                    about_subtitle = :about_subtitle,
                    about_description = :about_description,
                    about_button_text = :about_button_text,
                    about_image_url = :about_image_url,
                    products_title = :products_title,
                    products_subtitle = :products_subtitle,
                    products_button_text = :products_button_text,
                    posts_title = :posts_title,
                    posts_subtitle = :posts_subtitle,
                    updated_at = NOW() 
                    WHERE id = :id";
            $stmt = Database::conn()->prepare($sql);
            return $stmt->execute([
                ':id' => $home['id'],
                ':hero_title' => $data['hero_title'] ?? '',
                ':hero_subtitle' => $data['hero_subtitle'] ?? '',
                ':hero_button_text' => $data['hero_button_text'] ?? '',
                ':hero_image_url' => $data['hero_image_url'] ?? null,
                ':company_name' => $data['company_name'] ?? '',
                ':company_phone' => $data['company_phone'] ?? '',
                ':company_email' => $data['company_email'] ?? '',
                ':company_address' => $data['company_address'] ?? null,
                ':company_logo_url' => $data['company_logo_url'] ?? null,
                ':service_title' => $data['service_title'] ?? '',
                ':service_subtitle' => $data['service_subtitle'] ?? '',
                ':service_combos' => $data['service_combos'] ?? null,
                ':about_title' => $data['about_title'] ?? '',
                ':about_subtitle' => $data['about_subtitle'] ?? '',
                ':about_description' => $data['about_description'] ?? null,
                ':about_button_text' => $data['about_button_text'] ?? '',
                ':about_image_url' => $data['about_image_url'] ?? null,
                ':products_title' => $data['products_title'] ?? '',
                ':products_subtitle' => $data['products_subtitle'] ?? '',
                ':products_button_text' => $data['products_button_text'] ?? '',
                ':posts_title' => $data['posts_title'] ?? '',
                ':posts_subtitle' => $data['posts_subtitle'] ?? '',
            ]);
        } else {
            // Insert new
            $sql = "INSERT INTO home_page (
                    hero_title, hero_subtitle, hero_button_text, hero_image_url,
                    company_name, company_phone, company_email, company_address, company_logo_url,
                    service_title, service_subtitle, service_combos,
                    about_title, about_subtitle, about_description, about_button_text, about_image_url,
                    products_title, products_subtitle, products_button_text,
                    posts_title, posts_subtitle, updated_at
                    ) VALUES (
                    :hero_title, :hero_subtitle, :hero_button_text, :hero_image_url,
                    :company_name, :company_phone, :company_email, :company_address, :company_logo_url,
                    :service_title, :service_subtitle, :service_combos,
                    :about_title, :about_subtitle, :about_description, :about_button_text, :about_image_url,
                    :products_title, :products_subtitle, :products_button_text,
                    :posts_title, :posts_subtitle, NOW()
                    )";
            $stmt = Database::conn()->prepare($sql);
            return $stmt->execute([
                ':hero_title' => $data['hero_title'] ?? '',
                ':hero_subtitle' => $data['hero_subtitle'] ?? '',
                ':hero_button_text' => $data['hero_button_text'] ?? '',
                ':hero_image_url' => $data['hero_image_url'] ?? null,
                ':company_name' => $data['company_name'] ?? '',
                ':company_phone' => $data['company_phone'] ?? '',
                ':company_email' => $data['company_email'] ?? '',
                ':company_address' => $data['company_address'] ?? null,
                ':company_logo_url' => $data['company_logo_url'] ?? null,
                ':service_title' => $data['service_title'] ?? '',
                ':service_subtitle' => $data['service_subtitle'] ?? '',
                ':service_combos' => $data['service_combos'] ?? null,
                ':about_title' => $data['about_title'] ?? '',
                ':about_subtitle' => $data['about_subtitle'] ?? '',
                ':about_description' => $data['about_description'] ?? null,
                ':about_button_text' => $data['about_button_text'] ?? '',
                ':about_image_url' => $data['about_image_url'] ?? null,
                ':products_title' => $data['products_title'] ?? '',
                ':products_subtitle' => $data['products_subtitle'] ?? '',
                ':products_button_text' => $data['products_button_text'] ?? '',
                ':posts_title' => $data['posts_title'] ?? '',
                ':posts_subtitle' => $data['posts_subtitle'] ?? '',
            ]);
        }
    }
}



