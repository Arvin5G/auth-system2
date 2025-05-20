<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';
require_once 'includes/2fa.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // CSRF protection
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        redirect('register.php?error=Invalid CSRF token');
    }

    // Get and sanitize input
    $firstname = sanitizeInput($_POST['firstname']);
    $lastname = sanitizeInput($_POST['lastname']);
    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $course = sanitizeInput($_POST['course']);
    $year_level = sanitizeInput($_POST['year_level']);
    $password = sanitizeInput($_POST['password']);
    $confirm_password = sanitizeInput($_POST['confirm_password']);

    // Handle profile picture upload
    $profilePicPath = null;
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/profile_pics/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $file = $_FILES['profile_pic'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $fileType = mime_content_type($file['tmp_name']);
        
        if (!in_array($fileType, $allowedTypes)) {
            redirect('register.php?error=Invalid file type. Only JPG, PNG, GIF allowed.');
        }

        if ($file['size'] > 2097152) { // 2MB
            redirect('register.php?error=File too large. Max 2MB allowed.');
        }

        $fileName = uniqid('profile_') . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
        $destination = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            $profilePicPath = $destination;
            
            // Resize image (optional)
            require_once 'includes/image_resizer.php';
            resizeImage($destination, 300, 300);
        }
    }
    
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
    // $stmt = $pdo->prepare("INSERT INTO users (firstname, lastname, username, email, course, year_level, password, secret_key) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    // $stmt->execute([$firstname, $lastname, $username, $email, $course, $year_level, $hashed_password, $secret_key]);
    $stmt = $pdo->prepare("INSERT INTO users (firstname, lastname, username, email, course, year_level, password, secret_key, profile_pic) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$firstname, $lastname, $username, $email, $course, $year_level, $hashed_password, $secret_key, $profilePicPath]);
    
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