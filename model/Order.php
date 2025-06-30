<?php
require_once 'config/database.php';

class Order {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function create($data) {
        try {
            $this->db->query('INSERT INTO orders (user_id, package_id, total_amount, status) VALUES (:user_id, :package_id, :total_amount, :status)');
            $this->db->bind(':user_id', $data['user_id']);
            $this->db->bind(':package_id', $data['package_id']);
            $this->db->bind(':total_amount', $data['total_amount']);
            $this->db->bind(':status', $data['status']);
            
            return $this->db->execute();
        } catch (Exception $e) {
            error_log("Order create error: " . $e->getMessage());
            return false;
        }
    }

    public function getByUserId($user_id) {
        try {
            $this->db->query('SELECT o.*, p.name as package_name FROM orders o 
                             JOIN packages p ON o.package_id = p.id 
                             WHERE o.user_id = :user_id AND o.is_deleted = 0 
                             ORDER BY o.created_at DESC');
            $this->db->bind(':user_id', $user_id);
            
            return $this->db->resultset();
        } catch (Exception $e) {
            error_log("Order getByUserId error: " . $e->getMessage());
            return [];
        }
    }

    public function getAll() {
        try {
            $this->db->query('SELECT o.*, u.name as user_name, u.email, p.name as package_name 
                             FROM orders o 
                             JOIN users u ON o.user_id = u.id 
                             JOIN packages p ON o.package_id = p.id 
                             WHERE o.is_deleted = 0 
                             ORDER BY o.created_at DESC');
            
            return $this->db->resultset();
        } catch (Exception $e) {
            error_log("Order getAll error: " . $e->getMessage());
            return [];
        }
    }

    public function updateStatus($id, $status) {
        try {
            // Validasi status
            $validStatuses = ['pending', 'confirmed', 'cancelled'];
            if (!in_array($status, $validStatuses)) {
                return false;
            }
            
            $this->db->query('UPDATE orders SET status = :status WHERE id = :id AND is_deleted = 0');
            $this->db->bind(':status', $status);
            $this->db->bind(':id', $id);
            
            return $this->db->execute();
        } catch (Exception $e) {
            error_log("Order updateStatus error: " . $e->getMessage());
            return false;
        }
    }

    public function getStats() {
        try {
            $this->db->query('SELECT 
                             COUNT(*) as total_orders,
                             SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending_orders,
                             SUM(CASE WHEN status = "confirmed" THEN 1 ELSE 0 END) as confirmed_orders,
                             SUM(total_amount) as total_revenue
                             FROM orders WHERE is_deleted = 0');
            
            return $this->db->single();
        } catch (Exception $e) {
            error_log("Order getStats error: " . $e->getMessage());
            return [
                'total_orders' => 0,
                'pending_orders' => 0,
                'confirmed_orders' => 0,
                'total_revenue' => 0
            ];
        }
    }
}
?>
