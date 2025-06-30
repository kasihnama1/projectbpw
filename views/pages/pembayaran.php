<?php 
$page_title = 'Pembayaran';
include 'views/layouts/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit;
}

require_once 'model/Order.php';
require_once 'model/User.php';

$orderModel = new Order();
$userModel = new User();

$user = $userModel->findById($_SESSION['user_id']);
$orders = $orderModel->getByUserId($_SESSION['user_id']);
$latestOrder = !empty($orders) ? $orders[0] : null;

// WhatsApp message
$whatsappMessage = "Halo LPK Fujisan Plus,\n\n";
$whatsappMessage .= "Saya ingin melakukan pembayaran untuk:\n";
$whatsappMessage .= "Nama: " . $user['name'] . "\n";
$whatsappMessage .= "Email: " . $user['email'] . "\n";
$whatsappMessage .= "No. HP: " . $user['phone'] . "\n";
if ($latestOrder) {
    $whatsappMessage .= "Paket: " . $latestOrder['package_name'] . "\n";
    $whatsappMessage .= "Total: Rp " . number_format($latestOrder['total_amount'], 0, ',', '.') . "\n";
}
$whatsappMessage .= "\nMohon informasi rekening untuk pembayaran.\n\nTerima kasih.";

$whatsappUrl = "https://wa.me/" . WHATSAPP_NUMBER . "?text=" . urlencode($whatsappMessage);
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h3 class="mb-0"><i class="fas fa-credit-card me-2"></i>Pembayaran</h3>
                </div>
                <div class="card-body p-5">
                    <?php if ($latestOrder): ?>
                    <div class="alert alert-info">
                        <h5><i class="fas fa-info-circle me-2"></i>Informasi Pesanan</h5>
                        <hr>
                        <div class="row">
                            <div class="col-sm-4"><strong>Paket:</strong></div>
                            <div class="col-sm-8"><?php echo htmlspecialchars($latestOrder['package_name']); ?></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><strong>Total:</strong></div>
                            <div class="col-sm-8">Rp <?php echo number_format($latestOrder['total_amount'], 0, ',', '.'); ?></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><strong>Status:</strong></div>
                            <div class="col-sm-8">
                                <span class="badge bg-<?php echo $latestOrder['status'] == 'confirmed' ? 'success' : ($latestOrder['status'] == 'pending' ? 'warning' : 'danger'); ?>">
                                    <?php echo ucfirst($latestOrder['status']); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="text-center mb-4">
                        <i class="fab fa-whatsapp text-success" style="font-size: 4rem;"></i>
                        <h4 class="mt-3">Pembayaran via WhatsApp</h4>
                        <p class="text-muted">Klik tombol di bawah untuk melanjutkan pembayaran melalui WhatsApp</p>
                    </div>

                    <div class="bg-light p-4 rounded mb-4">
                        <h5><i class="fas fa-info-circle text-info me-2"></i>Cara Pembayaran:</h5>
                        <ol class="mb-0">
                            <li>Klik tombol "Bayar via WhatsApp" di bawah</li>
                            <li>Anda akan diarahkan ke WhatsApp dengan pesan otomatis</li>
                            <li>Kirim pesan tersebut ke admin kami</li>
                            <li>Admin akan memberikan informasi rekening pembayaran</li>
                            <li>Lakukan transfer sesuai nominal yang tertera</li>
                            <li>Kirim bukti transfer ke admin via WhatsApp</li>
                            <li>Tunggu konfirmasi pembayaran dari admin</li>
                        </ol>
                    </div>

                    <div class="text-center">
                        <a href="<?php echo $whatsappUrl; ?>" target="_blank" class="btn btn-success btn-lg px-5">
                            <i class="fab fa-whatsapp me-2"></i>Bayar via WhatsApp
                        </a>
                    </div>

                    <div class="text-center mt-4">
                        <a href="index.php?page=user_dashboard" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>
