<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/2fa.php';

// Check if 2FA is required
if (!isset($_SESSION['2fa_required']) && !isset($_SESSION['2fa_setup'])) {
    redirect('login.php');
}

// Get user ID
$user_id = $_SESSION['temp_user_id'] ?? null;

if (!$user_id) {
    redirect('login.php');
}

// Get user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    redirect('login.php');
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = sanitizeInput($_POST['code']);
    
    if (verify2FACode($user['secret_key'], $code)) {
        if (isset($_SESSION['2fa_setup'])) {
            // 2FA setup complete
            unset($_SESSION['2fa_setup']);
            unset($_SESSION['temp_user_id']);
            $_SESSION['user_id'] = $user_id;
            
            // Show QR code only once during setup
            if (isset($_GET['setup'])) {
                redirect('dashboard.php?success=2FA setup complete');
            } else {
                redirect('dashboard.php');
            }
        } else {
            // Regular 2FA verification
            unset($_SESSION['2fa_required']);
            unset($_SESSION['temp_user_id']);
            $_SESSION['user_id'] = $user_id;
            redirect('dashboard.php');
        }
    } else {
        $error = "Invalid verification code";
    }
}

// Generate QR code for setup
$qrCodeUrl = '';
if (isset($_SESSION['2fa_setup']) && isset($_GET['setup'])) {
    $qrCodeUrl = getQRCode($user['username'], $user['secret_key']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2FA Verification - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Two-Factor Authentication</h1>
        
        <?php if (isset($error)): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['2fa_setup']) && isset($_GET['setup'])): ?>
            <div class="alert info">
                <p>Scan this QR code with your authenticator app:</p>
                <img src="<?= getQRCode($user['username'], $user['secret_key']) ?>" alt="2FA QR Code">
                <p>Or manually enter this secret key: <strong><?php echo $user['secret_key']; ?></strong></p>
            </div>
        <?php endif; ?>
        
        <form action="verify_2fa.php" method="post">
            <div class="form-group">
                <label for="code">Enter 6-digit code from your authenticator app:</label>
                <input type="text" id="code" name="code" pattern="\d{6}" required>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn">Verify</button>
            </div>
        </form>
    </div>
</body>
</html>