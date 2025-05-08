<?php
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

function setRememberMeCookie($user_id) {
    $token = generateToken();
    $expiry = time() + 60 * 60 * 24 * 30; // 30 days
    
    setcookie('remember_me', $token, $expiry, '/');
    
    // Store token in database
    global $pdo;
    $stmt = $pdo->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
    $stmt->execute([$token, $user_id]);
}

function checkRememberMe() {
    if (isset($_COOKIE['remember_me']) && !isLoggedIn()) {
        global $pdo;
        $token = $_COOKIE['remember_me'];
        
        $stmt = $pdo->prepare("SELECT id FROM users WHERE remember_token = ?");
        $stmt->execute([$token]);
        
        if ($user = $stmt->fetch()) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['2fa_required'] = true;
            redirect('verify_2fa.php');
        }
    }
}
?>