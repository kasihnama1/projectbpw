<?php 
$page_title = 'Kelola Pesan';
include 'views/layouts/header.php';

// Perbaiki pengecekan session admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
    header('Location: index.php?page=login');
    exit;
}

require_once 'model/Message.php';
require_once 'controller/AdminController.php';

$messageModel = new Message();
$adminController = new AdminController();

// Handle reply
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_reply'])) {
    $adminController->replyMessage();
    header('Location: index.php?page=admin_messages');
    exit;
}

$messages = $messageModel->getAll();
$unrepliedMessages = $messageModel->getUnrepliedCount();
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="fw-bold">Kelola Pesan</h1>
                    <p class="text-muted">Manajemen pesan dari pengguna</p>
                </div>
                <a href="index.php?page=admin_dashboard" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Message Statistics -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4><?php echo count($messages); ?></h4>
                            <p class="mb-0">Total Pesan</p>
                        </div>
                        <i class="fas fa-envelope fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4><?php echo $unrepliedMessages; ?></h4>
                            <p class="mb-0">Belum Dibalas</p>
                        </div>
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4><?php echo count($messages) - $unrepliedMessages; ?></h4>
                            <p class="mb-0">Sudah Dibalas</p>
                        </div>
                        <i class="fas fa-check fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <?php if (!empty($messages)): ?>
            <?php foreach ($messages as $message): ?>
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0"><?php echo htmlspecialchars($message['subject']); ?></h5>
                        <small class="text-muted">Dari: <?php echo htmlspecialchars($message['user_name']); ?> (<?php echo htmlspecialchars($message['email']); ?>)</small>
                    </div>
                    <div>
                        <?php if ($message['is_replied']): ?>
                        <span class="badge bg-success">Sudah Dibalas</span>
                        <?php else: ?>
                        <span class="badge bg-warning">Belum Dibalas</span>
                        <?php endif; ?>
                        <small class="text-muted ms-2"><?php echo date('d M Y H:i', strtotime($message['created_at'])); ?></small>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6>Pesan:</h6>
                        <p class="text-muted"><?php echo nl2br(htmlspecialchars($message['message'])); ?></p>
                    </div>
                    
                    <?php if ($message['is_replied'] && !empty($message['reply'])): ?>
                    <div class="bg-light p-3 rounded mb-3">
                        <h6 class="text-primary">Balasan Anda:</h6>
                        <p class="mb-1"><?php echo nl2br(htmlspecialchars($message['reply'])); ?></p>
                        <small class="text-muted">Dibalas pada: <?php echo date('d M Y H:i', strtotime($message['replied_at'])); ?></small>
                    </div>
                    <?php else: ?>
                    <form method="POST">
                        <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">
                        <div class="mb-3">
                            <label for="reply_<?php echo $message['id']; ?>" class="form-label">Balasan:</label>
                            <textarea class="form-control" id="reply_<?php echo $message['id']; ?>" name="reply" rows="3" required></textarea>
                        </div>
                        <button type="submit" name="send_reply" class="btn btn-primary">
                            <i class="fas fa-reply me-2"></i>Kirim Balasan
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
            <?php else: ?>
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="fas fa-envelope text-muted" style="font-size: 4rem;"></i>
                    <h4 class="mt-3 text-muted">Belum ada pesan</h4>
                    <p class="text-muted">Pesan dari pengguna akan muncul di sini</p>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>
