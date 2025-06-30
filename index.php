<?php
session_start();
require_once 'config/config.php';
require_once 'config/database.php';

// Enable error logging untuk debugging
error_reporting(E_ALL);
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');

$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$action = isset($_GET['action']) ? $_GET['action'] : '';

// Include controllers
require_once 'controller/UserController.php';
require_once 'controller/AdminController.php';
require_once 'controller/ContactController.php';

$userController = new UserController();
$adminController = new AdminController();
$contactController = new ContactController();

// Handle authentication actions
if ($action == 'login') {
    $userController->login();
} elseif ($action == 'register') {
    $userController->register();
} elseif ($action == 'logout') {
    $userController->logout();
} elseif ($action == 'create_order') {
    $userController->createOrder();
}

// Handle message sending
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_message'])) {
    if (isset($_SESSION['user_id'])) {
        $result = $userController->sendMessage();
        if ($result) {
            header('Location: index.php?page=kontak&sent=1');
            exit;
        }
    } else {
        $_SESSION['error'] = 'Anda harus login terlebih dahulu untuk mengirim pesan';
    }
}

// Handle admin actions
if (isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'admin') {

    // Handle admin user actions
    if ($page == 'admin_users' && isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'create':
                $adminController->createUser();
                break;
            case 'update':
                $adminController->updateUser();
                break;
            case 'delete':
                $adminController->deleteUser();
                header('Location: index.php?page=admin_users');
                exit;
        }
    }

    // Handle admin package actions
    if ($page == 'admin_packages' && isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'create':
                $adminController->createPackage();
                break;
            case 'update':
                $adminController->updatePackage();
                break;
            case 'delete':
                $adminController->deletePackage();
                header('Location: index.php?page=admin_packages');
                exit;
        }
    }

    // Handle admin transaction actions
    if ($page == 'admin_transactions' && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
        $adminController->updateOrderStatus();
    }

    // Handle admin message actions
    if ($page == 'admin_messages' && $_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_reply'])) {
        $adminController->replyMessage();
    }

    // Handle admin gallery actions
    if ($page == 'admin_gallery') {
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload_image'])) {
            $adminController->uploadGallery();
        }
        if (isset($_GET['delete_gallery'])) {
            $adminController->deleteGallery();
            header('Location: index.php?page=admin_gallery');
            exit;
        }
    }
}
if (isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'user') {
    if ($page == 'kontak' && isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'create':
                $contactController->storeMessage();
                break;
        }
    }
}

// Route pages
switch ($page) {
    case 'login':
        include 'views/auth/login.php';
        break;
    case 'register':
        include 'views/auth/register.php';
        break;
    case 'paket':
        include 'views/pages/paket.php';
        break;
    case 'pembayaran':
        if (isset($_SESSION['user_id'])) {
            include 'views/pages/pembayaran.php';
        } else {
            header('Location: index.php?page=login');
        }
        break;
    case 'tentang':
        include 'views/pages/tentang.php';
        break;
    case 'kontak':
        include 'views/pages/kontak.php';
        break;
    case 'galeri':
        include 'views/pages/galeri.php';
        break;
    case 'user_dashboard':
        if (isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'user') {
            include 'views/user/dashboard.php';
        } else {
            header('Location: index.php?page=login');
        }
        break;
    case 'user_messages':
        if (isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'user') {
            include 'views/user/messages.php';
        } else {
            header('Location: index.php?page=login');
        }
        break;
    case 'admin_dashboard':
        if (isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'admin') {
            include 'views/admin/dashboard.php';
        } else {
            header('Location: index.php?page=login');
        }
        break;
    case 'admin_users':
        if (isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'admin') {
            include 'views/admin/users.php';
        } else {
            header('Location: index.php?page=login');
        }
        break;
    case 'admin_packages':
        if (isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'admin') {
            include 'views/admin/packages.php';
        } else {
            header('Location: index.php?page=login');
        }
        break;
    case 'admin_transactions':
        if (isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'admin') {
            include 'views/admin/transactions.php';
        } else {
            header('Location: index.php?page=login');
        }
        break;
    case 'admin_messages':
        if (isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'admin') {
            include 'views/admin/messages.php';
        } else {
            header('Location: index.php?page=login');
        }
        break;
    case 'admin_gallery':
        if (isset($_SESSION['user_id']) && $_SESSION['user_role'] == 'admin') {
            include 'views/admin/gallery.php';
        } else {
            header('Location: index.php?page=login');
        }
        break;
    default:
        include 'views/pages/home.php';
        break;
}
