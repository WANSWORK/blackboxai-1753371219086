<?php
require_once 'includes/auth.php';
require_once 'includes/functions.php';

// Redirect if already logged in
if (isLoggedIn()) {
    $user = getCurrentUser();
    switch ($user['role']) {
        case 'admin':
            header('Location: admin/dashboard.php');
            break;
        case 'kasir':
            header('Location: kasir/dashboard.php');
            break;
        case 'marketing':
            header('Location: marketing/dashboard.php');
            break;
    }
    exit();
}

$error = '';
$success = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error = 'Username dan password harus diisi';
    } else {
        if (login($username, $password)) {
            $user = getCurrentUser();
            switch ($user['role']) {
                case 'admin':
                    header('Location: admin/dashboard.php?success=login');
                    break;
                case 'kasir':
                    header('Location: kasir/dashboard.php?success=login');
                    break;
                case 'marketing':
                    header('Location: marketing/dashboard.php?success=login');
                    break;
            }
            exit();
        } else {
            $error = 'Username atau password salah';
        }
    }
}

// Handle logout
if (isset($_GET['logout'])) {
    $success = 'Anda telah berhasil logout';
}

$storeName = getStoreSetting('store_name') ?: 'Daily Chicken';
$logoPath = getStoreSetting('store_logo');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo $storeName; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-logo">
                <?php if ($logoPath && file_exists("uploads/logo/" . $logoPath)): ?>
                    <img src="uploads/logo/<?php echo $logoPath; ?>" alt="<?php echo $storeName; ?>">
                <?php endif; ?>
                <h1 class="login-title"><?php echo $storeName; ?></h1>
                <p style="color: #6c757d; margin-bottom: 2rem;">Sistem Kasir Web</p>
            </div>
            
            <?php if ($error): ?>
                <div class="alert alert-error">
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-error">
                    <?php
                    switch ($_GET['error']) {
                        case 'access_denied':
                            echo 'Akses ditolak. Anda tidak memiliki izin untuk mengakses halaman tersebut.';
                            break;
                        default:
                            echo 'Terjadi kesalahan. Silakan coba lagi.';
                    }
                    ?>
                </div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username" class="form-label">Username</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        class="form-control" 
                        required 
                        autocomplete="username"
                        value="<?php echo htmlspecialchars($username ?? ''); ?>"
                        placeholder="Masukkan username Anda"
                    >
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-control" 
                        required 
                        autocomplete="current-password"
                        placeholder="Masukkan password Anda"
                    >
                </div>
                
                <button type="submit" class="btn btn-primary btn-large w-100">
                    Login
                </button>
            </form>
            
            <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #dee2e6; text-align: center;">
                <h4 style="color: #007bff; margin-bottom: 1rem;">Demo Login:</h4>
                <div style="background: #f8f9fa; padding: 1rem; border-radius: 10px; font-size: 0.9rem;">
                    <strong>Admin:</strong> admin / admin123<br>
                    <em style="color: #6c757d;">Akses penuh ke semua fitur sistem</em>
                </div>
            </div>
            
            <div style="text-align: center; margin-top: 2rem; color: #6c757d; font-size: 0.9rem;">
                <p>© <?php echo date('Y'); ?> <?php echo $storeName; ?></p>
                <p>Sistem Kasir Web - Versi 1.0</p>
            </div>
        </div>
    </div>
    
    <script src="js/script.js"></script>
    <script>
        // Auto-focus on username field
        document.getElementById('username').focus();
        
        // Show/hide password functionality
        document.addEventListener('DOMContentLoaded', function() {
            const passwordField = document.getElementById('password');
            const toggleButton = document.createElement('button');
            toggleButton.type = 'button';
            toggleButton.innerHTML = '👁️';
            toggleButton.style.position = 'absolute';
            toggleButton.style.right = '10px';
            toggleButton.style.top = '50%';
            toggleButton.style.transform = 'translateY(-50%)';
            toggleButton.style.border = 'none';
            toggleButton.style.background = 'none';
            toggleButton.style.cursor = 'pointer';
            toggleButton.style.fontSize = '1.2rem';
            
            passwordField.parentNode.style.position = 'relative';
            passwordField.parentNode.appendChild(toggleButton);
            
            toggleButton.addEventListener('click', function() {
                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    toggleButton.innerHTML = '🙈';
                } else {
                    passwordField.type = 'password';
                    toggleButton.innerHTML = '👁️';
                }
            });
        });
    </script>
</body>
</html>
