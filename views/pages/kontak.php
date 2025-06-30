<?php
$page_title = 'Kontak';
include 'views/layouts/header.php';
?>

<div class="container py-5">
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h1 class="fw-bold">Hubungi Kami</h1>
            <p class="lead text-muted">Kami siap membantu menjawab pertanyaan Anda</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4">Informasi Kontak</h4>

                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Alamat</h6>
                            <p class="text-muted mb-0">Jl. Raya Jakarta No. 123<br>Jakarta Pusat, DKI Jakarta 10110</p>
                        </div>
                    </div>

                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fas fa-phone"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Telepon</h6>
                            <p class="text-muted mb-0">+62 812-3456-7890</p>
                        </div>
                    </div>

                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fas fa-envelope"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Email</h6>
                            <p class="text-muted mb-0">info@lpkfujisanplus.com</p>
                        </div>
                    </div>

                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="fas fa-clock"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Jam Operasional</h6>
                            <p class="text-muted mb-0">Senin - Jumat: 08:00 - 17:00<br>Sabtu: 08:00 - 12:00</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <?php include 'views/layouts/alert.php'; ?>
                    <h4 class="fw-bold mb-4">Kirim Pesan</h4>

                    <?php if (isset($_SESSION['user_id'])): ?>
                        <form method="POST" id="contactForm" action="index.php?page=kontak&action=create">
                            <div class="mb-3">
                                <label for="subject" class="form-label">Subjek</label>
                                <input type="text" class="form-control" id="subject" name="subject" required>
                            </div>
                            <div class="mb-3">
                                <label for="message" class="form-label">Pesan</label>
                                <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Kirim Pesan
                            </button>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Anda harus <a href="index.php?page=login">login</a> terlebih dahulu untuk mengirim pesan.
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('contactForm')?.addEventListener('submit', function() {
        const submitBtn = this.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mengirim...';
    });
</script>

<?php include 'views/layouts/footer.php'; ?>