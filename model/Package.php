<?php
require_once 'config/database.php';

class Package {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAll() {
        $this->db->query('SELECT * FROM packages WHERE is_deleted = 0 ORDER BY created_at DESC');
        
        return $this->db->resultset();
    }

    public function findById($id) {
        $this->db->query('SELECT * FROM packages WHERE id = :id AND is_deleted = 0');
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }

    public function create($data) {
        $this->db->query('INSERT INTO packages (name, description, price) VALUES (:name, :description, :price)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        
        return $this->db->execute();
    }

    public function update($id, $data) {
        $this->db->query('UPDATE packages SET name = :name, description = :description, price = :price WHERE id = :id');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }

    public function softDelete($id) {
        $this->db->query('UPDATE packages SET is_deleted = 1 WHERE id = :id');
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }
}
?>
