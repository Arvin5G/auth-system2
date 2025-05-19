<?php
require_once 'config.php';
require_once 'vendor/autoload.php'; // Load Composer autoloader once

// function generateSecretKey() {
//     require_once 'vendor/autoload.php'; // You'll need to install "sonata-project/google-authenticator"
//     $g = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
//     return $g->generateSecret();
// }

// function getQRCode($username, $secret) {
//     $issuer = SITE_NAME;
//     $qrCodeUrl = "otpauth://totp/{$issuer}:{$username}?secret={$secret}&issuer={$issuer}";
//     return "https://chart.googleapis.com/chart?chs=200x200&chld=M|0&cht=qr&chl=" . urlencode($qrCodeUrl);
// }

function getQRCode($username, $secret) {
    $issuer = SITE_NAME;
    $data = urlencode("otpauth://totp/{$issuer}:{$username}?secret={$secret}&issuer={$issuer}");
    
    // Using a different free QR generator
    return "https://quickchart.io/qr?text={$data}&size=200";
}

function verify2FACode($secret, $code) {
    require_once 'vendor/autoload.php';
    $g = new \Sonata\GoogleAuthenticator\GoogleAuthenticator();
    return $g->checkCode($secret, $code);
}
?>