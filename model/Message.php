<?php
require_once 'config/database.php';

class Message
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function create($data)
    {
        try {
            if (empty($data['user_id']) || empty($data['subject']) || empty($data['message'])) {
                return false;
            }

            $this->db->query('INSERT INTO messages (user_id, subject, message, created_at) VALUES (:user_id, :subject, :message, NOW())');
            $this->db->bind(':user_id', $data['user_id']);
            $this->db->bind(':subject', $data['subject']);
            $this->db->bind(':message', $data['message']);

            return $this->db->execute();
        } catch (Exception $e) {
            error_log("Message create error: " . $e->getMessage());
            return false;
        }
    }

    public function getByUserId($user_id)
    {
        try {
            $this->db->query('SELECT * FROM messages WHERE user_id = :user_id AND is_deleted = 0 ORDER BY created_at DESC');
            $this->db->bind(':user_id', $user_id);

            return $this->db->resultset();
        } catch (Exception $e) {
            error_log("Message getByUserId error: " . $e->getMessage());
            return [];
        }
    }

    public function getAll()
    {
        try {
            $this->db->query('SELECT m.*, u.name as user_name, u.email 
                             FROM messages m 
                             JOIN users u ON m.user_id = u.id 
                             WHERE m.is_deleted = 0 
                             ORDER BY m.created_at DESC');

            return $this->db->resultset();
        } catch (Exception $e) {
            error_log("Message getAll error: " . $e->getMessage());
            return [];
        }
    }

    public function reply($id, $reply)
    {
        try {
            $this->db->query('UPDATE messages SET reply = :reply, is_replied = 1, replied_at = NOW() WHERE id = :id');
            $this->db->bind(':reply', $reply);
            $this->db->bind(':id', $id);

            return $this->db->execute();
        } catch (Exception $e) {
            error_log("Message reply error: " . $e->getMessage());
            return false;
        }
    }

    public function getUnrepliedCount()
    {
        try {
            $this->db->query('SELECT COUNT(*) as count FROM messages WHERE is_replied = 0 AND is_deleted = 0');
            $result = $this->db->single();

            return $result['count'];
        } catch (Exception $e) {
            error_log("Message getUnrepliedCount error: " . $e->getMessage());
            return 0;
        }
    }

    public function softDelete($id)
    {
        try {
            $this->db->query('UPDATE messages SET is_deleted = 1 WHERE id = :id');
            $this->db->bind(':id', $id);

            return $this->db->execute();
        } catch (Exception $e) {
            error_log("Message softDelete error: " . $e->getMessage());
            return false;
        }
    }
}
