<?php require_once 'includes/config.php'; ?>
<?php require_once 'includes/functions.php'; ?>
<?php checkRememberMe(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="hero">
        <div class="sub-hero">
            <h1>Welcome to <?php echo SITE_NAME; ?></h1>
            
            <?php if (isLoggedIn()): ?>
                <p>You are already logged in. <a href="dashboard.php">Go to Dashboard</a> or <a href="logout.php">Logout</a></p>
            <?php else: ?>
                <div class="auth-options">
                    <a href="login.php" class="btn">Login</a>
                    <a href="register.php" class="btn">Register</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>