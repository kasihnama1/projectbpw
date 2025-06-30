<?php 
$page_title = 'Galeri';
include 'views/layouts/header.php';
require_once 'model/Gallery.php';

$galleryModel = new Gallery();
$galleries = $galleryModel->getAll();
?>

<div class="container py-5">
    <div class="row">
        <div class="col-12 text-center mb-5">
            <h1 class="fw-bold">Galeri Kegiatan</h1>
            <p class="lead text-muted">Dokumentasi kegiatan pelatihan dan aktivitas LPK Fujisan Plus</p>
        </div>
    </div>

    <?php if (!empty($galleries)): ?>
    <div class="row">
        <?php foreach ($galleries as $gallery): ?>
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm">
                <img src="assets/uploads/gallery/<?php echo htmlspecialchars($gallery['filename']); ?>" 
                     class="card-img-top" alt="<?php echo htmlspecialchars($gallery['title']); ?>"
                     style="height: 250px; object-fit: cover;">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($gallery['title']); ?></h5>
                    <p class="card-text text-muted">
                        <small><i class="fas fa-calendar me-1"></i><?php echo date('d M Y', strtotime($gallery['created_at'])); ?></small>
                    </p>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div class="row">
        <div class="col-12 text-center">
            <div class="py-5">
                <i class="fas fa-images text-muted" style="font-size: 4rem;"></i>
                <h4 class="mt-3 text-muted">Belum ada foto di galeri</h4>
                <p class="text-muted">Galeri akan segera diperbarui dengan foto-foto kegiatan terbaru.</p>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<?php include 'views/layouts/footer.php'; ?>
