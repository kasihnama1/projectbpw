<?php
$page_title = 'Pesan Saya';
include 'views/layouts/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit;
}

require_once 'model/Message.php';

$messageModel = new Message();
$messages = $messageModel->getByUserId($_SESSION['user_id']);
?>

<div class="container py-5">
    <div class="row">
        <div class="col-12 mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="fw-bold">Pesan Saya</h1>
                    <p class="text-muted">Kelola pesan dan komunikasi dengan admin</p>
                </div>
                <a href="index.php?page=kontak" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Kirim Pesan Baru
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <?php if (!empty($messages)): ?>
                <?php foreach ($messages as $message): ?>
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><?php echo htmlspecialchars($message['subject']); ?></h5>
                            <div>
                                <?php if ($message['is_replied']): ?>
                                    <span class="badge bg-success">Dibalas</span>
                                <?php else: ?>
                                    <span class="badge bg-warning">Menunggu Balasan</span>
                                <?php endif; ?>
                                <small class="text-muted ms-2"><?php echo date('d M Y H:i', strtotime($message['created_at'])); ?></small>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6>Pesan Anda:</h6>
                                <p class="text-muted"><?php echo nl2br(htmlspecialchars($message['message'])); ?></p>
                            </div>

                            <?php if ($message['is_replied'] && !empty($message['reply'])): ?>
                                <div class="bg-light p-3 rounded">
                                    <h6 class="text-primary">Balasan Admin:</h6>
                                    <p class="mb-1"><?php echo nl2br(htmlspecialchars($message['reply'])); ?></p>
                                    <small class="text-muted">Dibalas pada: <?php echo date('d M Y H:i', strtotime($message['replied_at'])); ?></small>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-envelope text-muted" style="font-size: 4rem;"></i>
                        <h4 class="mt-3 text-muted">Belum ada pesan</h4>
                        <p class="text-muted">Mulai berkomunikasi dengan admin dengan mengirim pesan pertama Anda</p>
                        <a href="index.php?page=kontak" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Kirim Pesan Pertama
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>