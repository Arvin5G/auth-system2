<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/2fa.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get and sanitize input
    $firstname = sanitizeInput($_POST['firstname']);
    $lastname = sanitizeInput($_POST['lastname']);
    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $course = sanitizeInput($_POST['course']);
    $year_level = sanitizeInput($_POST['year_level']);
    $password = sanitizeInput($_POST['password']);
    $confirm_password = sanitizeInput($_POST['confirm_password']);
    
    // Validate inputs
    if ($password !== $confirm_password) {
        redirect('register.php?error=Passwords do not match');
    }
    
    if (strlen($password) < 8) {
        redirect('register.php?error=Password must be at least 8 characters');
    }
    
    // Check if username or email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    
    if ($stmt->fetch()) {
        redirect('register.php?error=Username or email already exists');
    }
    
    // Generate 2FA secret
    $secret_key = generateSecretKey();
    
    // Hash password
    $hashed_password = hashPassword($password);
    
    // Insert new user
    $stmt = $pdo->prepare("INSERT INTO users (firstname, lastname, username, email, course, year_level, password, secret_key) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$firstname, $lastname, $username, $email, $course, $year_level, $hashed_password, $secret_key]);
    
    // Get the new user ID
    $user_id = $pdo->lastInsertId();
    
    // Set session for 2FA setup
    $_SESSION['temp_user_id'] = $user_id;
    $_SESSION['2fa_secret'] = $secret_key;
    $_SESSION['2fa_setup'] = true;
    
    redirect('verify_2fa.php?setup=1');
} else {
    redirect('register.php');
}
?>