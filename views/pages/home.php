<?php 
$page_title = 'Beranda';
include 'views/layouts/header.php'; 
?>

<!-- Hero Section -->
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-4 fw-bold mb-4">Wujudkan Impian Bekerja di Korea</h1>
                <p class="lead mb-4">LPK Fujisan Plus adalah lembaga pelatihan kerja terpercaya yang mempersiapkan Anda untuk bekerja di Korea Selatan dengan program pelatihan bahasa dan budaya Korea yang komprehensif.</p>
                <a href="index.php?page=paket" class="btn btn-light btn-lg">Lihat Paket Pelatihan</a>
            </div>
            <div class="col-md-6 text-center">
                <img src="assets/images/logo.png" alt="LPK Fujisan Plus" class="img-fluid" style="max-height: 300px;">
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-12">
                <h2 class="fw-bold">Mengapa Memilih LPK Fujisan Plus?</h2>
                <p class="text-muted">Kami memberikan pelayanan terbaik untuk kesuksesan karir Anda di Korea</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-language fa-lg"></i>
                        </div>
                        <h5>Pelatihan Bahasa Korea</h5>
                        <p class="text-muted">Program pelatihan bahasa Korea yang intensif dan terstruktur untuk mempersiapkan Anda berkomunikasi dengan lancar.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-heart fa-lg"></i>
                        </div>
                        <h5>Medical Check Up</h5>
                        <p class="text-muted">Pemeriksaan kesehatan lengkap sesuai standar yang dipersyaratkan untuk bekerja di Korea Selatan.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="feature-icon bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 60px; height: 60px;">
                            <i class="fas fa-certificate fa-lg"></i>
                        </div>
                        <h5>Ujian Sertifikasi</h5>
                        <p class="text-muted">Ujian kompetensi untuk mendapatkan sertifikat yang diakui oleh pemerintah Korea Selatan.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="bg-light py-5">
    <div class="container text-center">
        <h2 class="fw-bold mb-4">Siap Memulai Perjalanan Anda?</h2>
        <p class="lead mb-4">Bergabunglah dengan ribuan alumni yang telah sukses bekerja di Korea Selatan</p>
        <a href="index.php?page=paket" class="btn btn-primary btn-lg me-3">Daftar Sekarang</a>
        <a href="index.php?page=kontak" class="btn btn-outline-primary btn-lg">Hubungi Kami</a>
    </div>
</section>

<?php include 'views/layouts/footer.php'; ?>
