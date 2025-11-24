<?php
require_once __DIR__ . '/Database.php';
class FAQModel {
    public static function countAll($q = '') {
        $db = Database::get();
        if ($q) {
            $stmt = $db->prepare("SELECT COUNT(*) FROM faq WHERE question LIKE :q OR answer LIKE :q");
            $stmt->execute([':q'=>'%'.$q.'%']);
        } else {
            $stmt = $db->query("SELECT COUNT(*) FROM faq");
        }
        return (int)$stmt->fetchColumn();
    }
    public static function getAll($limit, $offset, $q='') {
        $db = Database::get();
        if ($q) {
            $stmt = $db->prepare("SELECT * FROM faq WHERE question LIKE :q OR answer LIKE :q ORDER BY id DESC LIMIT :offset, :limit");
            $stmt->bindValue(':q','%'.$q.'%', PDO::PARAM_STR);
            $stmt->bindValue(':offset',(int)$offset, PDO::PARAM_INT);
            $stmt->bindValue(':limit',(int)$limit, PDO::PARAM_INT);
            $stmt->execute();
        } else {
            $stmt = $db->prepare("SELECT * FROM faq ORDER BY id DESC LIMIT :offset, :limit");
            $stmt->bindValue(':offset',(int)$offset, PDO::PARAM_INT);
            $stmt->bindValue(':limit',(int)$limit, PDO::PARAM_INT);
            $stmt->execute();
        }
        return $stmt->fetchAll();
    }
    public static function get($id) {
        $db = Database::get();
        $stmt = $db->prepare("SELECT * FROM faq WHERE id = :id");
        $stmt->execute([':id'=>$id]);
        return $stmt->fetch();
    }
    public static function insert($data) {
        $db = Database::get();
        $stmt = $db->prepare("INSERT INTO faq (question, answer, created_at) VALUES (:q, :a, NOW())");
        return $stmt->execute([':q'=>$data['question'], ':a'=>$data['answer']]);
    }
    public static function update($id, $data) {
        $db = Database::get();
        $stmt = $db->prepare("UPDATE faq SET question = :q, answer = :a WHERE id = :id");
        return $stmt->execute([':q'=>$data['question'], ':a'=>$data['answer'], ':id'=>$id]);
    }
    public static function delete($id) {
        $db = Database::get();
        $stmt = $db->prepare("DELETE FROM faq WHERE id = :id");
        return $stmt->execute([':id'=>$id]);
    }
}
