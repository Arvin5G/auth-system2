<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username']);
    $password = sanitizeInput($_POST['password']);
    $remember_me = isset($_POST['remember_me']);
    
    // Find user by username
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && verifyPassword($password, $user['password'])) {
        // Check if 2FA is enabled
        if (!empty($user['secret_key'])) {
            $_SESSION['temp_user_id'] = $user['id'];
            $_SESSION['2fa_required'] = true;
            
            if ($remember_me) {
                setRememberMeCookie($user['id']);
            }
            
            redirect('verify_2fa.php');
        } else {
            // No 2FA, log in directly
            $_SESSION['user_id'] = $user['id'];
            
            if ($remember_me) {
                setRememberMeCookie($user['id']);
            }
            
            redirect('dashboard.php');
        }
    } else {
        redirect('login.php?error=Invalid username or password');
    }
} else {
    redirect('login.php');
}
?>