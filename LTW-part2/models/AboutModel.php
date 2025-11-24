<?php
require_once __DIR__ . '/Database.php';
class AboutModel {
    public static function get() {
        $db = Database::get();
        $stmt = $db->query('SELECT * FROM about_page ORDER BY id DESC LIMIT 1');
        return $stmt->fetch();
    }
    public static function update($data) {
        $db = Database::get();
        $sql = "UPDATE about_page SET title = :title, description = :description, mission = :mission, vision = :vision, image = :image, updated_at = NOW() WHERE id = :id";
        $stmt = $db->prepare($sql);
        return $stmt->execute([
            ':title'=>$data['title'],
            ':description'=>$data['description'],
            ':mission'=>$data['mission'],
            ':vision'=>$data['vision'],
            ':image'=>$data['image'],
            ':id'=>$data['id']
        ]);
    }
}
