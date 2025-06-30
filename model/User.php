<?php
require_once 'config/database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function create($data) {
        $this->db->query('INSERT INTO users (name, email, phone, password, role) VALUES (:name, :email, :phone, :password, :role)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':role', isset($data['role']) ? $data['role'] : 'user');
        
        return $this->db->execute();
    }

    public function update($id, $data) {
        if (isset($data['password'])) {
            $this->db->query('UPDATE users SET name = :name, email = :email, phone = :phone, password = :password, role = :role WHERE id = :id');
            $this->db->bind(':password', $data['password']);
        } else {
            $this->db->query('UPDATE users SET name = :name, email = :email, phone = :phone, role = :role WHERE id = :id');
        }
        
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':role', $data['role']);
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }

    public function findByEmail($email) {
        $this->db->query('SELECT * FROM users WHERE email = :email AND is_deleted = 0');
        $this->db->bind(':email', $email);
        
        return $this->db->single();
    }

    public function findById($id) {
        $this->db->query('SELECT * FROM users WHERE id = :id AND is_deleted = 0');
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }

    public function emailExists($email) {
        $this->db->query('SELECT id FROM users WHERE email = :email AND is_deleted = 0');
        $this->db->bind(':email', $email);
        
        return $this->db->single() ? true : false;
    }

    public function getAll() {
        $this->db->query('SELECT * FROM users WHERE role = "user" AND is_deleted = 0 ORDER BY created_at DESC');
        
        return $this->db->resultset();
    }

    public function getAllIncludingAdmins() {
        $this->db->query('SELECT * FROM users WHERE is_deleted = 0 ORDER BY role DESC, created_at DESC');
        
        return $this->db->resultset();
    }

    public function softDelete($id) {
        $this->db->query('UPDATE users SET is_deleted = 1 WHERE id = :id');
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }
}
?>
