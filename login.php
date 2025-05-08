<?php require_once 'includes/config.php'; ?>
<?php require_once 'includes/functions.php'; ?>
<?php checkRememberMe(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="alert error"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert success"><?php echo htmlspecialchars($_GET['success']); ?></div>
        <?php endif; ?>
        
        <form action="authenticate.php" method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <div class="form-group">
                <div class="remember_me">
                    <label for ="check">Remember me </label>
                    <input class="rm" type="checkbox" id="check" name="remember_me">
                </div>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn">Login</button>
            </div>
            
            <div class="form-footer">
                <a href="forgot_password.php">Forgot Password?</a>
                <span>Don't have an account? <a href="register.php">Register</a></span>
            </div>
        </form>
    </div>
</body>
</html>