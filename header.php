<?php include_once('db.php');

// Revoke the persistent login token when the user logs out.
if (isset($_GET['logout']) && $_GET['logout'] === 'true' && !empty($_COOKIE['cnc_portfolio_remember']) && isset($conn) && $conn) {
    $logoutRememberHash = hash('sha256', (string)$_COOKIE['cnc_portfolio_remember']);
    $logoutStmt = mysqli_prepare($conn, 'UPDATE users SET remember_token = NULL, remember_expires_at = NULL WHERE remember_token = ?');
    if ($logoutStmt) {
        mysqli_stmt_bind_param($logoutStmt, 's', $logoutRememberHash);
        mysqli_stmt_execute($logoutStmt);
        mysqli_stmt_close($logoutStmt);
    }
    setcookie('cnc_portfolio_remember', '', ['expires' => time() - 3600, 'path' => '/', 'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off', 'httponly' => true, 'samesite' => 'Lax']);
}

// Restore a remembered login when the PHP session is no longer available.
if (!isset($_SESSION['username']) && !empty($_COOKIE['cnc_portfolio_remember']) && isset($conn) && $conn) {
    $rememberHash = hash('sha256', (string)$_COOKIE['cnc_portfolio_remember']);
    $rememberStmt = mysqli_prepare($conn, 'SELECT id, username, email FROM users WHERE remember_token = ? AND remember_expires_at > NOW() LIMIT 1');
    if ($rememberStmt) {
        mysqli_stmt_bind_param($rememberStmt, 's', $rememberHash);
        mysqli_stmt_execute($rememberStmt);
        $rememberResult = mysqli_stmt_get_result($rememberStmt);
        $rememberUser = $rememberResult ? mysqli_fetch_assoc($rememberResult) : null;
        mysqli_stmt_close($rememberStmt);
        if ($rememberUser) {
            session_regenerate_id(true);
            $_SESSION['username'] = (string)($rememberUser['email'] ?: $rememberUser['username']);
            $_SESSION['user_id'] = (int)$rememberUser['id'];
        } else {
            setcookie('cnc_portfolio_remember', '', ['expires' => time() - 3600, 'path' => '/', 'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off', 'httponly' => true, 'samesite' => 'Lax']);
        }
    }
}

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Codeandcore - Portfolio Management</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.ico">
    <!-- Custom Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
    <link href="./plugins/select2/css/select2.min.css" rel="stylesheet">
    <link href="./plugins/tables/css/datatable/dataTables.bootstrap4.min.css" rel="stylesheet">

</head>

<body>
<style>
    .dataTables_wrapper mark{padding: 0 !important;background-color: #efd447;}
    .copy_links_i{position: absolute; top: 30px; right: 20px; font-size: 20px;}
</style>
    <!--*******************
        Preloader start
    ********************-->
    <div id="preloader">
        <div class="loader">
            <svg class="circular" viewBox="25 25 50 50">
                <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="3" stroke-miterlimit="10" />
            </svg>
        </div>
    </div>
    <!--*******************
        Preloader end
    ********************-->

    
    <!--**********************************
        Main wrapper start
    ***********************************-->
    <div id="main-wrapper">

        <!--**********************************
            Nav header start
        ***********************************-->
        <div class="nav-header">
            <div class="brand-logo">
                <a href="index.php">
                    <b class="logo-abbr" style="display: none !important;"><img src="images/cnc-logo new.png" alt=""> </b>
                    <span class="logo-compact" style="display: none !important;"><img src="./images/cnc-logo new.png" alt=""></span>
                    <span class="brand-title">
                        <img src="images/cnc-logo new.png" alt="">
                    </span>
                </a>
            </div>
        </div>
        <!--**********************************
            Nav header end
        ***********************************-->


    