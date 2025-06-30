<?php 
$page_title = 'Admin Dashboard';
include 'views/layouts/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header('Location: index.php?page=login');
    exit;
}

require_once 'model/User.php';
require_once 'model/Order.php';
require_once 'model/Message.php';
require_once 'model/Gallery.php';
require_once 'model/Package.php';

$userModel = new User();
$orderModel = new Order();
$messageModel = new Message();
$galleryModel = new Gallery();
$packageModel = new Package();

// Get statistics
$totalUsers = count($userModel->getAll());
$orderStats = $orderModel->getStats();
$unrepliedMessages = $messageModel->getUnrepliedCount();
$totalGallery = count($galleryModel->getAll());
$totalPackages = count($packageModel->getAll());
?>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="fw-bold mb-1">Dashboard Admin</h1>
                    <p class="text-muted mb-0">Selamat datang, <?php echo htmlspecialchars($_SESSION['user_name']); ?>! Kelola sistem LPK Fujisan Plus</p>
                </div>
                <div>
                    <span class="badge bg-success fs-6 px-3 py-2">
                        <i class="fas fa-clock me-1"></i>
                        <?php echo date('d M Y, H:i'); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1"><?php echo $totalUsers; ?></h3>
                            <p class="mb-0 small">Total Users</p>
                        </div>
                        <i class="fas fa-users fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1"><?php echo $orderStats['total_orders'] ?? 0; ?></h3>
                            <p class="mb-0 small">Total Pesanan</p>
                        </div>
                        <i class="fas fa-shopping-cart fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1"><?php echo $unrepliedMessages; ?></h3>
                            <p class="mb-0 small">Pesan Baru</p>
                        </div>
                        <i class="fas fa-envelope fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1">Rp <?php echo number_format($orderStats['total_revenue'] ?? 0, 0, ',', '.'); ?></h3>
                            <p class="mb-0 small">Total Revenue</p>
                        </div>
                        <i class="fas fa-money-bill-wave fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-secondary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1"><?php echo $totalGallery; ?></h3>
                            <p class="mb-0 small">Foto Galeri</p>
                        </div>
                        <i class="fas fa-images fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
            <div class="card bg-dark text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-1"><?php echo $totalPackages; ?></h3>
                            <p class="mb-0 small">Paket Aktif</p>
                        </div>
                        <i class="fas fa-box fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Menu -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Aksi Cepat - Menu Manajemen</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3">
                        <!-- Kelola Users -->
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="index.php?page=admin_users" class="text-decoration-none">
                                <div class="card border-0 shadow-sm h-100 hover-card">
                                    <div class="card-body text-center p-4">
                                        <div class="mb-3">
                                            <div class="icon-circle bg-primary text-white mx-auto">
                                                <i class="fas fa-users fa-2x"></i>
                                            </div>
                                        </div>
                                        <h6 class="card-title text-primary fw-bold">Kelola Users</h6>
                                        <p class="card-text small text-muted">Manajemen pengguna sistem</p>
                                        <span class="badge bg-primary"><?php echo $totalUsers; ?> Users</span>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Kelola Paket -->
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="index.php?page=admin_packages" class="text-decoration-none">
                                <div class="card border-0 shadow-sm h-100 hover-card">
                                    <div class="card-body text-center p-4">
                                        <div class="mb-3">
                                            <div class="icon-circle bg-success text-white mx-auto">
                                                <i class="fas fa-box fa-2x"></i>
                                            </div>
                                        </div>
                                        <h6 class="card-title text-success fw-bold">Kelola Paket</h6>
                                        <p class="card-text small text-muted">Manajemen paket pelatihan</p>
                                        <span class="badge bg-success"><?php echo $totalPackages; ?> Paket</span>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Kelola Transaksi -->
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="index.php?page=admin_transactions" class="text-decoration-none">
                                <div class="card border-0 shadow-sm h-100 hover-card">
                                    <div class="card-body text-center p-4">
                                        <div class="mb-3">
                                            <div class="icon-circle bg-info text-white mx-auto">
                                                <i class="fas fa-credit-card fa-2x"></i>
                                            </div>
                                        </div>
                                        <h6 class="card-title text-info fw-bold">Kelola Transaksi</h6>
                                        <p class="card-text small text-muted">Manajemen pembayaran</p>
                                        <span class="badge bg-info"><?php echo $orderStats['total_orders'] ?? 0; ?> Transaksi</span>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Kelola Pesan -->
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="index.php?page=admin_messages" class="text-decoration-none">
                                <div class="card border-0 shadow-sm h-100 hover-card">
                                    <div class="card-body text-center p-4">
                                        <div class="mb-3">
                                            <div class="icon-circle bg-warning text-white mx-auto">
                                                <i class="fas fa-envelope fa-2x"></i>
                                            </div>
                                        </div>
                                        <h6 class="card-title text-warning fw-bold">Kelola Pesan</h6>
                                        <p class="card-text small text-muted">Manajemen komunikasi</p>
                                        <span class="badge bg-warning"><?php echo $unrepliedMessages; ?> Belum Dibalas</span>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Kelola Galeri -->
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="index.php?page=admin_gallery" class="text-decoration-none">
                                <div class="card border-0 shadow-sm h-100 hover-card">
                                    <div class="card-body text-center p-4">
                                        <div class="mb-3">
                                            <div class="icon-circle bg-secondary text-white mx-auto">
                                                <i class="fas fa-images fa-2x"></i>
                                            </div>
                                        </div>
                                        <h6 class="card-title text-secondary fw-bold">Kelola Galeri</h6>
                                        <p class="card-text small text-muted">Manajemen foto kegiatan</p>
                                        <span class="badge bg-secondary"><?php echo $totalGallery; ?> Foto</span>
                                    </div>
                                </div>
                            </a>
                        </div>

                        <!-- Lihat Website -->
                        <div class="col-lg-2 col-md-4 col-sm-6">
                            <a href="index.php" target="_blank" class="text-decoration-none">
                                <div class="card border-0 shadow-sm h-100 hover-card">
                                    <div class="card-body text-center p-4">
                                        <div class="mb-3">
                                            <div class="icon-circle bg-dark text-white mx-auto">
                                                <i class="fas fa-globe fa-2x"></i>
                                            </div>
                                        </div>
                                        <h6 class="card-title text-dark fw-bold">Lihat Website</h6>
                                        <p class="card-text small text-muted">Preview website publik</p>
                                        <span class="badge bg-dark">Public View</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Summary -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-chart-line me-2"></i>Aktivitas Terbaru</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Pesanan Pending</span>
                        <span class="badge bg-warning"><?php echo $orderStats['pending_orders'] ?? 0; ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span>Pesanan Dikonfirmasi</span>
                        <span class="badge bg-success"><?php echo $orderStats['confirmed_orders'] ?? 0; ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Pesan Belum Dibalas</span>
                        <span class="badge bg-danger"><?php echo $unrepliedMessages; ?></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informasi Sistem</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Versi Sistem:</strong> 1.0.0</p>
                    <p class="mb-2"><strong>Database:</strong> MySQL</p>
                    <p class="mb-2"><strong>Server:</strong> <?php echo $_SERVER['SERVER_NAME']; ?></p>
                    <p class="mb-0"><strong>Last Update:</strong> <?php echo date('d M Y'); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.icon-circle {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}

.hover-card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, var(--primary-color) 0%, #5a8bc4 100%);
}
</style>

<?php include 'views/layouts/footer.php'; ?>
