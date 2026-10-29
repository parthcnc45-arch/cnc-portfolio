<?php
include_once('db.php');

// Revoke the remember-me token for this browser before ending the session.
if (!empty($_COOKIE['cnc_portfolio_remember']) && isset($conn) && $conn) {
    $rememberHash = hash('sha256', (string)$_COOKIE['cnc_portfolio_remember']);
    $stmt = mysqli_prepare($conn, 'UPDATE users SET remember_token = NULL, remember_expires_at = NULL WHERE remember_token = ?');
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 's', $rememberHash);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
}

$cookieOptions = [
    'expires' => time() - 3600,
    'path' => '/',
    'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
    'httponly' => true,
    'samesite' => 'Lax',
];
setcookie('cnc_portfolio_remember', '', $cookieOptions);
setcookie('remember_user', '', $cookieOptions);
setcookie('remember_username', '', $cookieOptions);

$_SESSION = [];
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', [
        'expires' => time() - 3600,
        'path' => $params['path'] ?: '/',
        'domain' => $params['domain'],
        'secure' => $params['secure'],
        'httponly' => $params['httponly'],
        'samesite' => 'Lax',
    ]);
}
session_destroy();
header('Location: login.php');
exit();
