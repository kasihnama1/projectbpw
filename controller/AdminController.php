<?php
require_once 'model/User.php';
require_once 'model/Order.php';
require_once 'model/Package.php';
require_once 'model/Message.php';
require_once 'model/Gallery.php';

class AdminController {
    private $userModel;
    private $orderModel;
    private $packageModel;
    private $messageModel;
    private $galleryModel;

    public function __construct() {
        $this->userModel = new User();
        $this->orderModel = new Order();
        $this->packageModel = new Package();
        $this->messageModel = new Message();
        $this->galleryModel = new Gallery();
    }

    // PERBAIKAN: User Management dengan logging
    public function createUser() {
        error_log("=== CREATE USER FUNCTION CALLED ===");
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $this->isAdmin()) {
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone']);
            $password = $_POST['password'];
            $role = $_POST['role'];

            error_log("User data: Name=$name, Email=$email, Role=$role");

            if (empty($name) || empty($email) || empty($phone) || empty($password)) {
                $_SESSION['error'] = 'Semua field harus diisi';
                error_log("ERROR: Empty fields");
                return;
            }

            if ($this->userModel->emailExists($email)) {
                $_SESSION['error'] = 'Email sudah terdaftar';
                error_log("ERROR: Email already exists");
                return;
            }

            $userData = [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'role' => $role
            ];

            try {
                $result = $this->userModel->create($userData);
                if ($result) {
                    $_SESSION['success'] = 'User berhasil ditambahkan';
                    error_log("SUCCESS: User created");
                } else {
                    $_SESSION['error'] = 'Gagal menambahkan user';
                    error_log("ERROR: Failed to create user");
                }
            } catch (Exception $e) {
                $_SESSION['error'] = 'Error: ' . $e->getMessage();
                error_log("EXCEPTION in createUser: " . $e->getMessage());
            }
        }
    }

    public function updateUser() {
        error_log("=== UPDATE USER FUNCTION CALLED ===");
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $this->isAdmin()) {
            $id = $_POST['user_id'];
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone']);
            $role = $_POST['role'];
            $password = $_POST['password'];

            error_log("Updating user ID: $id");

            if (empty($name) || empty($email) || empty($phone)) {
                $_SESSION['error'] = 'Nama, email, dan telepon harus diisi';
                return;
            }

            $existingUser = $this->userModel->findByEmail($email);
            if ($existingUser && $existingUser['id'] != $id) {
                $_SESSION['error'] = 'Email sudah digunakan oleh user lain';
                return;
            }

            $userData = [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'role' => $role
            ];

            if (!empty($password)) {
                $userData['password'] = password_hash($password, PASSWORD_DEFAULT);
            }

            try {
                $result = $this->userModel->update($id, $userData);
                if ($result) {
                    $_SESSION['success'] = 'User berhasil diupdate';
                    error_log("SUCCESS: User updated");
                } else {
                    $_SESSION['error'] = 'Gagal mengupdate user';
                    error_log("ERROR: Failed to update user");
                }
            } catch (Exception $e) {
                $_SESSION['error'] = 'Error: ' . $e->getMessage();
                error_log("EXCEPTION in updateUser: " . $e->getMessage());
            }
        }
    }

    public function deleteUser() {
        if (isset($_GET['id']) && $this->isAdmin()) {
            $user_id = $_GET['id'];
            
            if ($user_id == $_SESSION['user_id']) {
                $_SESSION['error'] = 'Anda tidak dapat menghapus akun sendiri';
                return;
            }

            if ($this->userModel->softDelete($user_id)) {
                $_SESSION['success'] = 'User berhasil dihapus';
            } else {
                $_SESSION['error'] = 'Gagal menghapus user';
            }
        }
    }

    // PERBAIKAN: Package Management dengan anti-duplikasi
    public function createPackage() {
        error_log("=== CREATE PACKAGE FUNCTION CALLED ===");
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $this->isAdmin()) {
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);
            $price = $_POST['price'];

            error_log("Package data: Name=$name, Price=$price");

            if (empty($name) || empty($description) || empty($price)) {
                $_SESSION['error'] = 'Semua field harus diisi';
                error_log("ERROR: Empty fields");
                return;
            }

            if (!is_numeric($price) || $price <= 0) {
                $_SESSION['error'] = 'Harga harus berupa angka positif';
                error_log("ERROR: Invalid price");
                return;
            }

            $packageData = [
                'name' => $name,
                'description' => $description,
                'price' => $price
            ];

            try {
                $result = $this->packageModel->create($packageData);
                if ($result) {
                    $_SESSION['success'] = 'Paket berhasil ditambahkan';
                    error_log("SUCCESS: Package created");
                    // PENTING: Redirect untuk mencegah duplikasi
                    header('Location: index.php?page=admin_packages&created=1');
                    exit;
                } else {
                    $_SESSION['error'] = 'Gagal menambahkan paket';
                    error_log("ERROR: Failed to create package");
                }
            } catch (Exception $e) {
                $_SESSION['error'] = 'Error: ' . $e->getMessage();
                error_log("EXCEPTION in createPackage: " . $e->getMessage());
            }
        }
    }

    public function updatePackage() {
        error_log("=== UPDATE PACKAGE FUNCTION CALLED ===");
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $this->isAdmin()) {
            $id = $_POST['package_id'];
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);
            $price = $_POST['price'];

            error_log("Updating package ID: $id");

            if (empty($name) || empty($description) || empty($price)) {
                $_SESSION['error'] = 'Semua field harus diisi';
                return;
            }

            if (!is_numeric($price) || $price <= 0) {
                $_SESSION['error'] = 'Harga harus berupa angka positif';
                return;
            }

            $packageData = [
                'name' => $name,
                'description' => $description,
                'price' => $price
            ];

            try {
                $result = $this->packageModel->update($id, $packageData);
                if ($result) {
                    $_SESSION['success'] = 'Paket berhasil diupdate';
                    error_log("SUCCESS: Package updated");
                    header('Location: index.php?page=admin_packages&updated=1');
                    exit;
                } else {
                    $_SESSION['error'] = 'Gagal mengupdate paket';
                    error_log("ERROR: Failed to update package");
                }
            } catch (Exception $e) {
                $_SESSION['error'] = 'Error: ' . $e->getMessage();
                error_log("EXCEPTION in updatePackage: " . $e->getMessage());
            }
        }
    }

    public function deletePackage() {
        if (isset($_GET['id']) && $this->isAdmin()) {
            $package_id = $_GET['id'];
            if ($this->packageModel->softDelete($package_id)) {
                $_SESSION['success'] = 'Paket berhasil dihapus';
            } else {
                $_SESSION['error'] = 'Gagal menghapus paket';
            }
        }
    }

    // Order Management
    public function updateOrderStatus() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $this->isAdmin()) {
            $order_id = $_POST['order_id'];
            $status = $_POST['status'];

            if ($this->orderModel->updateStatus($order_id, $status)) {
                $_SESSION['success'] = 'Status pesanan berhasil diupdate';
            } else {
                $_SESSION['error'] = 'Gagal mengupdate status pesanan';
            }
        }
    }

    // Message Management
    public function replyMessage() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $this->isAdmin()) {
            $message_id = $_POST['message_id'];
            $reply = trim($_POST['reply']);

            if (empty($reply)) {
                $_SESSION['error'] = 'Balasan tidak boleh kosong';
                return;
            }

            if ($this->messageModel->reply($message_id, $reply)) {
                $_SESSION['success'] = 'Balasan berhasil dikirim';
            } else {
                $_SESSION['error'] = 'Gagal mengirim balasan';
            }
        }
    }

    // PERBAIKAN: Gallery Management dengan logging detail
    public function uploadGallery() {
        error_log("=== UPLOAD GALLERY FUNCTION CALLED ===");
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && $this->isAdmin()) {
            $title = trim($_POST['title']);
            
            error_log("Gallery title: $title");
            error_log("FILES data: " . print_r($_FILES, true));

            if (empty($title)) {
                $_SESSION['error'] = 'Judul harus diisi';
                error_log("ERROR: Empty title");
                return;
            }

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                $filename = $_FILES['image']['name'];
                $filetype = pathinfo($filename, PATHINFO_EXTENSION);

                error_log("File info: Name=$filename, Type=$filetype");

                if (in_array(strtolower($filetype), $allowed)) {
                    $uploadDir = 'assets/uploads/gallery/';
                    
                    // Pastikan direktori ada
                    if (!is_dir($uploadDir)) {
                        if (!mkdir($uploadDir, 0755, true)) {
                            $_SESSION['error'] = 'Gagal membuat direktori upload';
                            error_log("ERROR: Failed to create upload directory");
                            return;
                        }
                        error_log("SUCCESS: Created upload directory");
                    }
                    
                    $newname = time() . '_' . uniqid() . '.' . strtolower($filetype);
                    $upload_path = $uploadDir . $newname;

                    error_log("Upload path: $upload_path");

                    if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
                        error_log("SUCCESS: File uploaded to $upload_path");
                        
                        $galleryData = [
                            'title' => $title,
                            'filename' => $newname
                        ];

                        error_log("Gallery data: " . print_r($galleryData, true));

                        try {
                            $result = $this->galleryModel->create($galleryData);
                            if ($result) {
                                $_SESSION['success'] = 'Foto berhasil diupload';
                                error_log("SUCCESS: Gallery data saved to database");
                                header('Location: index.php?page=admin_gallery&uploaded=1');
                                exit;
                            } else {
                                $_SESSION['error'] = 'Gagal menyimpan data foto ke database';
                                error_log("ERROR: Failed to save gallery data");
                            }
                        } catch (Exception $e) {
                            $_SESSION['error'] = 'Database error: ' . $e->getMessage();
                            error_log("EXCEPTION in gallery upload: " . $e->getMessage());
                        }
                    } else {
                        $_SESSION['error'] = 'Gagal mengupload file';
                        error_log("ERROR: Failed to move uploaded file");
                    }
                } else {
                    $_SESSION['error'] = 'Format file tidak didukung. Gunakan: ' . implode(', ', $allowed);
                    error_log("ERROR: Unsupported file type");
                }
            } else {
                $_SESSION['error'] = 'Pilih file untuk diupload';
                if (isset($_FILES['image'])) {
                    error_log("File upload error code: " . $_FILES['image']['error']);
                }
            }
        }
    }

    public function deleteGallery() {
        if (isset($_GET['id']) && $this->isAdmin()) {
            $gallery_id = $_GET['id'];
            if ($this->galleryModel->softDelete($gallery_id)) {
                $_SESSION['success'] = 'Foto berhasil dihapus';
            } else {
                $_SESSION['error'] = 'Gagal menghapus foto';
            }
        }
    }

    private function isAdmin() {
        $isAdmin = isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'admin';
        error_log("Admin check: " . ($isAdmin ? 'TRUE' : 'FALSE'));
        return $isAdmin;
    }
}
?>
