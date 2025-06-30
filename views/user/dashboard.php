<?php 
$page_title = 'Dashboard User';
include 'views/layouts/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit;
}

require_once 'model/Order.php';
require_once 'model/Message.php';

$orderModel = new Order();
$messageModel = new Message();

$orders = $orderModel->getByUserId($_SESSION['user_id']);
$messages = $messageModel->getByUserId($_SESSION['user_id']);
?>

<div class="container py-5">
    <div class="row">
        <div class="col-12 mb-4">
            <h1 class="fw-bold">Dashboard</h1>
            <p class="text-muted">Selamat datang, <?php echo htmlspecialchars($_SESSION['user_name']); ?>!</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4><?php echo count($orders); ?></h4>
                            <p class="mb-0">Total Pesanan</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-shopping-cart fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4><?php echo count(array_filter($orders, function($o) { return $o['status'] == 'confirmed'; })); ?></h4>
                            <p class="mb-0">Pesanan Dikonfirmasi</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4><?php echo count($messages); ?></h4>
                            <p class="mb-0">Total Pesan</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fas fa-envelope fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Riwayat Pesanan</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($orders)): ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Paket</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($order['package_name']); ?></td>
                                    <td>Rp <?php echo number_format($order['total_amount'], 0, ',', '.'); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $order['status'] == 'confirmed' ? 'success' : ($order['status'] == 'pending' ? 'warning' : 'danger'); ?>">
                                            <?php echo ucfirst($order['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d/m/Y', strtotime($order['created_at'])); ?></td>
                                    <td>
                                        <?php if ($order['status'] == 'pending'): ?>
                                        <a href="index.php?page=pembayaran" class="btn btn-sm btn-primary">Bayar</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-shopping-cart text-muted" style="font-size: 3rem;"></i>
                        <h5 class="mt-3 text-muted">Belum ada pesanan</h5>
                        <p class="text-muted">Mulai dengan memesan paket pelatihan kami</p>
                        <a href="index.php?page=paket" class="btn btn-primary">Lihat Paket</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-envelope me-2"></i>Pesan Terbaru</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($messages)): ?>
                    <?php foreach (array_slice($messages, 0, 3) as $message): ?>
                    <div class="border-bottom pb-2 mb-2">
                        <h6 class="mb-1"><?php echo htmlspecialchars($message['subject']); ?></h6>
                        <p class="text-muted small mb-1"><?php echo substr(htmlspecialchars($message['message']), 0, 50) . '...'; ?></p>
                        <small class="text-muted"><?php echo date('d/m/Y', strtotime($message['created_at'])); ?></small>
                        <?php if ($message['is_replied']): ?>
                        <span class="badge bg-success ms-2">Dibalas</span>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                    <div class="text-center mt-3">
                        <a href="index.php?page=user_messages" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-3">
                        <i class="fas fa-envelope text-muted" style="font-size: 2rem;"></i>
                        <p class="mt-2 text-muted">Belum ada pesan</p>
                        <a href="index.php?page=kontak" class="btn btn-sm btn-primary">Kirim Pesan</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>
