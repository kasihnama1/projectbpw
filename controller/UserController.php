<?php
require_once 'model/User.php';
require_once 'model/Order.php';
require_once 'model/Message.php';

class UserController
{
    private $userModel;
    private $orderModel;
    private $messageModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->orderModel = new Order();
        $this->messageModel = new Message();
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone']);
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            if (empty($name) || empty($email) || empty($phone) || empty($password)) {
                $_SESSION['error'] = 'Semua field harus diisi';
                return;
            }

            if ($password !== $confirm_password) {
                $_SESSION['error'] = 'Password tidak cocok';
                return;
            }

            if ($this->userModel->emailExists($email)) {
                $_SESSION['error'] = 'Email sudah terdaftar';
                return;
            }

            $userData = [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'password' => password_hash($password, PASSWORD_DEFAULT)
            ];

            if ($this->userModel->create($userData)) {
                $_SESSION['success'] = 'Registrasi berhasil, silakan login';
                header('Location: index.php?page=login');
                exit;
            } else {
                $_SESSION['error'] = 'Registrasi gagal';
            }
        }
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = trim($_POST['email']);
            $password = $_POST['password'];

            if (empty($email) || empty($password)) {
                $_SESSION['login_error'] = 'Email dan password harus diisi';
                $_SESSION['login_email'] = $email;
                header('Location: index.php?page=login');
                exit;
            }
            $user = $this->userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_role'] = $user['role'];

                unset($_SESSION['login_error']);
                unset($_SESSION['login_email']);

                if ($user['role'] == 'admin') {
                    header('Location: index.php?page=admin_dashboard');
                } else {
                    header('Location: index.php?page=user_dashboard');
                }
                exit;
            } else {
                $_SESSION['login_error'] = 'Email atau password salah';
                $_SESSION['login_email'] = $email;
                header('Location: index.php?page=login');
                exit;
            }
        }
    }

    public function logout()
    {
        $userName = $_SESSION['user_name'] ?? 'User';
        session_destroy();
        session_start();
        $_SESSION['success'] = "Logout berhasil. Sampai jumpa $userName!";
        header('Location: index.php');
        exit;
    }

    public function createOrder()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
            $package_id = $_POST['package_id'];
            $user_id = $_SESSION['user_id'];

            $orderData = [
                'user_id' => $user_id,
                'package_id' => $package_id,
                'total_amount' => PACKAGE_PRICE,
                'status' => 'pending'
            ];

            if ($this->orderModel->create($orderData)) {
                $_SESSION['success'] = 'Pesanan berhasil dibuat';
                header('Location: index.php?page=pembayaran');
                exit;
            } else {
                $_SESSION['error'] = 'Gagal membuat pesanan';
            }
        }
    }

    // PERBAIKAN UTAMA: Fungsi sendMessage yang benar-benar berfungsi
    public function sendMessage()
    {
        // Log untuk debugging
        error_log("=== SEND MESSAGE FUNCTION CALLED ===");
        error_log("REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD']);
        error_log("POST data: " . print_r($_POST, true));
        error_log("SESSION user_id: " . ($_SESSION['user_id'] ?? 'NOT SET'));

        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {

            // Validasi input
            if (!isset($_POST['subject']) || !isset($_POST['message'])) {
                $_SESSION['error'] = 'Data form tidak lengkap';
                error_log("ERROR: Missing form data");
                return false;
            }

            $subject = trim($_POST['subject']);
            $message = trim($_POST['message']);
            $user_id = $_SESSION['user_id'];

            error_log("Processing message - Subject: $subject, User ID: $user_id");

            if (empty($subject) || empty($message)) {
                $_SESSION['error'] = 'Subjek dan pesan harus diisi';
                error_log("ERROR: Empty subject or message");
                return false;
            }

            $messageData = [
                'user_id' => $user_id,
                'subject' => $subject,
                'message' => $message
            ];

            error_log("Message data prepared: " . print_r($messageData, true));

            try {
                $result = $this->messageModel->create($messageData);
                error_log("Message model create result: " . ($result ? 'SUCCESS' : 'FAILED'));

                if ($result) {
                    $_SESSION['success'] = 'Pesan berhasil dikirim! Kami akan membalas segera.';
                    error_log("SUCCESS: Message sent successfully");
                    return true;
                } else {
                    $_SESSION['error'] = 'Gagal mengirim pesan. Silakan coba lagi.';
                    error_log("ERROR: Failed to send message");
                    return false;
                }
            } catch (Exception $e) {
                $_SESSION['error'] = 'Terjadi kesalahan sistem: ' . $e->getMessage();
                error_log("EXCEPTION in sendMessage: " . $e->getMessage());
                return false;
            }
        } else {
            if (!isset($_SESSION['user_id'])) {
                $_SESSION['error'] = 'Anda harus login terlebih dahulu';
                error_log("ERROR: User not logged in");
            } else {
                error_log("ERROR: Invalid request method or conditions not met");
            }
            return false;
        }
    }
}
