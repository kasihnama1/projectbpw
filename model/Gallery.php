<?php
require_once 'config/database.php';

class Gallery {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function create($data) {
        try {
            // Validasi data
            if (empty($data['title']) || empty($data['filename'])) {
                return false;
            }
            
            $this->db->query('INSERT INTO gallery (title, filename, created_at) VALUES (:title, :filename, NOW())');
            $this->db->bind(':title', $data['title']);
            $this->db->bind(':filename', $data['filename']);
            
            return $this->db->execute();
        } catch (Exception $e) {
            error_log("Gallery create error: " . $e->getMessage());
            return false;
        }
    }

    public function getAll() {
        try {
            $this->db->query('SELECT * FROM gallery WHERE is_deleted = 0 ORDER BY created_at DESC');
            
            return $this->db->resultset();
        } catch (Exception $e) {
            error_log("Gallery getAll error: " . $e->getMessage());
            return [];
        }
    }

    public function softDelete($id) {
        try {
            $this->db->query('UPDATE gallery SET is_deleted = 1 WHERE id = :id');
            $this->db->bind(':id', $id);
            
            return $this->db->execute();
        } catch (Exception $e) {
            error_log("Gallery softDelete error: " . $e->getMessage());
            return false;
        }
    }
}
?>
