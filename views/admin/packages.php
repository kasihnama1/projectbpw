<?php 
$page_title = 'Kelola Paket';
include 'views/layouts/header.php';

// Perbaiki pengecekan session admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header('Location: index.php?page=login');
    exit;
}

require_once 'model/Package.php';
require_once 'controller/AdminController.php';

$packageModel = new Package();
$adminController = new AdminController();

// Handle actions
if (isset($_GET['action'])) {
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

$packages = $packageModel->getAll();
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="fw-bold">Kelola Paket</h1>
                    <p class="text-muted">Manajemen paket pelatihan</p>
                </div>
                <div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPackageModal">
                        <i class="fas fa-plus me-2"></i>Tambah Paket
                    </button>
                    <a href="index.php?page=admin_dashboard" class="btn btn-secondary ms-2">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0"><i class="fas fa-box me-2"></i>Daftar Paket Pelatihan</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($packages)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Paket</th>
                                    <th>Harga</th>
                                    <th>Deskripsi</th>
                                    <th>Tanggal Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($packages as $package): ?>
                                <tr>
                                    <td><?php echo $package['id']; ?></td>
                                    <td><?php echo htmlspecialchars($package['name']); ?></td>
                                    <td>Rp <?php echo number_format($package['price'], 0, ',', '.'); ?></td>
                                    <td><?php echo htmlspecialchars(substr($package['description'], 0, 100)) . '...'; ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($package['created_at'])); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-warning" onclick="editPackage(<?php echo htmlspecialchars(json_encode($package)); ?>)">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="index.php?page=admin_packages&action=delete&id=<?php echo $package['id']; ?>" 
                                           class="btn btn-sm btn-danger ms-1"
                                           onclick="return confirm('Yakin ingin menghapus paket ini?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-box text-muted" style="font-size: 3rem;"></i>
                        <h5 class="mt-3 text-muted">Belum ada paket tersedia</h5>
                        <p class="text-muted">Tambahkan paket pelatihan pertama Anda</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Package Modal -->
<div class="modal fade" id="addPackageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Paket Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="index.php?page=admin_packages&action=create">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Paket</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Harga</label>
                        <input type="number" class="form-control" id="price" name="price" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah Paket</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Package Modal -->
<div class="modal fade" id="editPackageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Paket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="index.php?page=admin_packages&action=update">
                <div class="modal-body">
                    <input type="hidden" id="edit_package_id" name="package_id">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Nama Paket</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_price" class="form-label">Harga</label>
                        <input type="number" class="form-control" id="edit_price" name="price" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="4" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Update Paket</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editPackage(package) {
    document.getElementById('edit_package_id').value = package.id;
    document.getElementById('edit_name').value = package.name;
    document.getElementById('edit_price').value = package.price;
    document.getElementById('edit_description').value = package.description;
    
    new bootstrap.Modal(document.getElementById('editPackageModal')).show();
}
</script>

<?php include 'views/layouts/footer.php'; ?>
