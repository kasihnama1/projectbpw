<?php 
$page_title = 'Paket Pelatihan';
include 'views/layouts/header.php';
require_once 'model/Package.php';

$packageModel = new Package();
$packages = $packageModel->getAll();
?>

<div class="container py-5">
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h1 class="fw-bold">Paket Pelatihan</h1>
            <p class="lead text-muted">Pilih paket pelatihan yang sesuai dengan kebutuhan Anda</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <?php foreach ($packages as $package): ?>
        <div class="col-md-8 mb-4">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-4">
                    <h3 class="mb-0"><?php echo htmlspecialchars($package['name']); ?></h3>
                </div>
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <h2 class="display-4 fw-bold text-primary">Rp <?php echo number_format($package['price'], 0, ',', '.'); ?></h2>
                        <p class="text-muted">Paket Lengkap</p>
                    </div>
                    
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3">Yang Anda Dapatkan:</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Pendaftaran pelatihan Bahasa & Budaya Korea</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Pemeriksaan kesehatan (Medical Check Up)</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Ujian sertifikasi kompetensi</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Materi pembelajaran lengkap</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Bimbingan dari instruktur berpengalaman</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Sertifikat resmi</li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <p class="text-muted"><?php echo nl2br(htmlspecialchars($package['description'])); ?></p>
                    </div>

                    <div class="text-center">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <form method="POST" action="index.php?action=create_order" class="d-inline">
                                <input type="hidden" name="package_id" value="<?php echo $package['id']; ?>">
                                <button type="submit" class="btn btn-primary btn-lg px-5">Pesan Sekarang</button>
                            </form>
                        <?php else: ?>
                            <a href="index.php?page=login" class="btn btn-primary btn-lg px-5">Login untuk Memesan</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>
