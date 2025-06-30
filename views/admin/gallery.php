<?php 
$page_title = 'Kelola Galeri';
include 'views/layouts/header.php';

// Perbaiki pengecekan session admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header('Location: index.php?page=login');
    exit;
}

require_once 'model/Gallery.php';
require_once 'controller/AdminController.php';

$galleryModel = new Gallery();
$adminController = new AdminController();

// Handle upload
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['upload_image'])) {
    $adminController->uploadGallery();
    header('Location: index.php?page=admin_gallery');
    exit;
}

// Handle delete
if (isset($_GET['delete_gallery'])) {
    $galleryModel->softDelete($_GET['delete_gallery']);
    $_SESSION['success'] = 'Foto berhasil dihapus';
    header('Location: index.php?page=admin_gallery');
    exit;
}

$galleries = $galleryModel->getAll();
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="fw-bold">Kelola Galeri</h1>
                    <p class="text-muted">Manajemen foto galeri kegiatan</p>
                </div>
                <a href="index.php?page=admin_dashboard" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Gallery Statistics -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card bg-secondary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4><?php echo count($galleries); ?></h4>
                            <p class="mb-0">Total Foto</p>
                        </div>
                        <i class="fas fa-images fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4><?php echo count(array_filter($galleries, function($g) { return strtotime($g['created_at']) > strtotime('-30 days'); })); ?></h4>
                            <p class="mb-0">Foto Bulan Ini</p>
                        </div>
                        <i class="fas fa-calendar fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Upload Form -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-upload me-2"></i>Upload Foto Baru</h5>
                </div>
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data" id="uploadForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Judul Foto</label>
                                    <input type="text" class="form-control" id="title" name="title" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Pilih Foto</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*" required onchange="previewImage(this)">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div id="imagePreview" class="mb-3" style="display: none;">
                                    <img id="preview" src="/placeholder.svg" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                                </div>
                            </div>
                        </div>
                        <button type="submit" name="upload_image" class="btn btn-primary">
                            <i class="fas fa-upload me-2"></i>Upload Foto
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Gallery List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-images me-2"></i>Daftar Foto Galeri</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($galleries)): ?>
                    <div class="row">
                        <?php foreach ($galleries as $gallery): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <img src="assets/uploads/gallery/<?php echo htmlspecialchars($gallery['filename']); ?>" 
                                     class="card-img-top" alt="<?php echo htmlspecialchars($gallery['title']); ?>"
                                     style="height: 200px; object-fit: cover;">
                                <div class="card-body">
                                    <h6 class="card-title"><?php echo htmlspecialchars($gallery['title']); ?></h6>
                                    <p class="card-text text-muted">
                                        <small><?php echo date('d M Y', strtotime($gallery['created_at'])); ?></small>
                                    </p>
                                    <a href="index.php?page=admin_gallery&delete_gallery=<?php echo $gallery['id']; ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Yakin ingin menghapus foto ini?')">
                                        <i class="fas fa-trash"></i> Hapus
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-images text-muted" style="font-size: 3rem;"></i>
                        <h5 class="mt-3 text-muted">Belum ada foto di galeri</h5>
                        <p class="text-muted">Upload foto pertama untuk memulai galeri</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php include 'views/layouts/footer.php'; ?>
