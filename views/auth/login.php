<?php 
$page_title = 'Login';
include 'views/layouts/header.php'; 
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-soft">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <img src="assets/images/logo.png" alt="LPK Fujisan Plus" height="60">
                        <h3 class="mt-3">Login</h3>
                        <p class="text-muted">Masuk ke akun Anda</p>
                    </div>

                    <!-- Login Error Alert -->
                    <?php if (isset($_SESSION['login_error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?php echo $_SESSION['login_error']; unset($_SESSION['login_error']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="index.php?action=login">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?php echo isset($_SESSION['login_email']) ? htmlspecialchars($_SESSION['login_email']) : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <p>Belum punya akun? <a href="index.php?page=register" class="text-primary">Daftar di sini</a></p>
                    </div>

                    <!-- Demo Accounts Info -->
                    <div class="mt-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title text-center mb-3">
                                    <i class="fas fa-info-circle text-primary"></i> Akun Demo
                                </h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-primary">Admin 1:</h6>
                                        <small class="text-muted">
                                            Email: admin@lpkfujisanplus.com<br>
                                            Password: admin123
                                        </small>
                                        <button type="button" class="btn btn-sm btn-outline-primary mt-1 w-100" 
                                                onclick="fillLogin('admin@lpkfujisanplus.com', 'admin123')">
                                            Auto Fill
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-primary">Admin 2:</h6>
                                        <small class="text-muted">
                                            Email: superadmin@lpkfujisanplus.com<br>
                                            Password: admin456
                                        </small>
                                        <button type="button" class="btn btn-sm btn-outline-primary mt-1 w-100" 
                                                onclick="fillLogin('superadmin@lpkfujisanplus.com', 'admin456')">
                                            Auto Fill
                                        </button>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-12">
                                        <h6 class="text-success">User:</h6>
                                        <small class="text-muted">
                                            Email: user@lpkfujisanplus.com<br>
                                            Password: user123
                                        </small>
                                        <button type="button" class="btn btn-sm btn-outline-success mt-1 w-100" 
                                                onclick="fillLogin('user@lpkfujisanplus.com', 'user123')">
                                            Auto Fill
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Reset Button for Testing -->
                    <div class="text-center mt-3">
                        <a href="simple_reset.php" class="btn btn-sm btn-outline-warning">
                            <i class="fas fa-tools me-1"></i>Reset Database (Testing)
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function fillLogin(email, password) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = password;
}
</script>

<?php 
// Clear login email after displaying
if (isset($_SESSION['login_email'])) {
    unset($_SESSION['login_email']);
}
include 'views/layouts/footer.php'; 
?>
