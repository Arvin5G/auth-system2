<?php require_once 'includes/config.php'; ?>
<?php require_once 'includes/functions.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Forgot Password</h1>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="alert error"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert success"><?php echo htmlspecialchars($_GET['success']); ?></div>
        <?php endif; ?>
        
        <form action="reset_password.php" method="post">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn">Reset Password</button>
            </div>
            
            <div class="form-footer">
                <span>Remember your password? <a href="login.php">Login</a></span>
            </div>
        </form>
    </div>
</body>
</html>