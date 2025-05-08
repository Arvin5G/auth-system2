<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['token'])) {
    // Handle initial password reset request
    $email = sanitizeInput($_POST['email']);
    
    // Check if email exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    
    if ($user) {
        // Generate reset token
        $token = generateToken();
        $expiry = date('Y-m-d H:i:s', time() + 3600); // 1 hour expiry
        
        // Store token in database
        $stmt = $pdo->prepare("UPDATE users SET reset_token = ?, reset_expiry = ? WHERE id = ?");
        if (!$stmt->execute([$token, $expiry, $user['id']])) {
            error_log("Failed to store reset token");
            redirect("forgot_password.php?error=Failed to process your request");
        }
        
        // Create reset link
        $reset_link = SITE_URL . "/reset_password.php?token=$token";
        
        // In production, you would send an email here
        // mail($email, "Password Reset", "Click here to reset: $reset_link");
        
        // For development, show the link
        redirect("forgot_password.php?success=Password reset link generated. <a href='$reset_link'>Click here to reset</a> (In production, this would be emailed)");
    } else {
        redirect("forgot_password.php?error=Email not found");
    }
} 
elseif (isset($_GET['token'])) {
    // Show password reset form
    $token = sanitizeInput($_GET['token']);
    
    // Verify token
    $stmt = $pdo->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_expiry > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch();
    
    if (!$user) {
        redirect("forgot_password.php?error=Invalid or expired token");
    }
    
    // Show form
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Reset Password</title>
        <link rel="stylesheet" href="assets/css/style.css">
    </head>
    <body>
        <div class="container">
            <h1>Reset Password</h1>
            <form method="POST">
                <input type="hidden" name="token" value="<?php echo $token; ?>">
                <div class="form-group">
                    <label for="password">New Password</label>
                    <input type="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn">Reset Password</button>
            </form>
        </div>
    </body>
    </html>
    <?php
} 
elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['token'])) {
    // Process password reset
    $token = sanitizeInput($_POST['token']);
    $password = sanitizeInput($_POST['password']);
    $confirm_password = sanitizeInput($_POST['confirm_password']);
    
    if ($password !== $confirm_password) {
        redirect("reset_password.php?token=$token&error=Passwords don't match");
    }
    
    // Verify token again
    $stmt = $pdo->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_expiry > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch();
    
    if (!$user) {
        redirect("forgot_password.php?error=Invalid or expired token");
    }
    
    // Update password
    $hashed_password = hashPassword($password);
    $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expiry = NULL WHERE id = ?");
    if ($stmt->execute([$hashed_password, $user['id']])) {
        redirect("login.php?success=Password updated successfully");
    } else {
        redirect("reset_password.php?token=$token&error=Failed to update password");
    }
} 
else {
    redirect("forgot_password.php");
}
?>