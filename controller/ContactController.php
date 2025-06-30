<?php
require_once 'model/User.php';
require_once 'model/Order.php';
require_once 'model/Message.php';

class ContactController
{
    private $messageModel;

    public function __construct()
    {
        $this->messageModel = new Message();
    }

    public function storeMessage()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_SESSION['user_id'])) {
                if (!isset($_POST['subject']) || !isset($_POST['message'])) {
                    $_SESSION['error'] = 'Data form tidak lengkap';
                    return false;
                }

                $subject = trim($_POST['subject']);
                $message = trim($_POST['message']);
                $user_id = $_SESSION['user_id'];

                if (empty($subject) || empty($message)) {
                    $_SESSION['error'] = 'Subjek dan pesan harus diisi';
                    return false;
                }

                $messageData = [
                    'user_id' => $user_id,
                    'subject' => $subject,
                    'message' => $message
                ];

                if ($this->messageModel->create($messageData)) {
                    $_SESSION['success'] = 'Pesan berhasil dikirim! Kami akan membalas segera.';
                    header('Location: index.php?page=kontak');
                    exit;
                } else {
                    $_SESSION['error'] = 'Gagal mengirim pesan. Silakan coba lagi.';
                    header('Location: index.php?page=kontak');
                    exit;
                }
            } else {
                $_SESSION['error'] = 'Anda harus login terlebih dahulu untuk mengirim pesan';
                header('Location: index.php?page=kontak');
                exit;
            }
        } else {
            $_SESSION['error'] = 'Method tidak diizinkan';
            header('Location: index.php?page=kontak');
            exit;
        }
    }
}
