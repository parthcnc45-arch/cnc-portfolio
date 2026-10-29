<?php
include_once('db.php');
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

function otpJsonResponse($success, $message, $extra = [], $status = 200) {
    http_response_code($status);
    echo json_encode(array_merge(['success' => $success, 'message' => $message], $extra));
    exit();
}

function smtpReadResponse($socket) {
    $response = '';
    while (($line = fgets($socket, 515)) !== false) {
        $response .= $line;
        if (strlen($line) < 4 || $line[3] === ' ') {
            break;
        }
    }
    return $response;
}

function smtpExpect($socket, $expectedCodes) {
    $response = smtpReadResponse($socket);
    $code = (int)substr($response, 0, 3);
    if (!in_array($code, $expectedCodes, true)) {
        throw new RuntimeException('SMTP server rejected a mail command (' . $code . ').');
    }
    return $response;
}

function smtpCommand($socket, $command, $expectedCodes) {
    if (fwrite($socket, $command . "\r\n") === false) {
        throw new RuntimeException('Could not write to SMTP server.');
    }
    return smtpExpect($socket, $expectedCodes);
}

function sendOtpEmail($recipient, $otp) {
    $config = require __DIR__ . '/otp-config.php';
    foreach (['host', 'port', 'encryption', 'username', 'password', 'from_email', 'from_name'] as $key) {
        if (!isset($config[$key]) || trim((string)$config[$key]) === '' ||
            strpos((string)$config[$key], 'YOUR_') !== false) {
            throw new RuntimeException('Email is not configured yet. Add your Gmail SMTP details in otp-config.php.');
        }
    }

    $host = (string)$config['host'];
    $port = (int)$config['port'];
    $encryption = strtolower((string)$config['encryption']);
    $timeout = max(5, (int)($config['timeout'] ?? 15));
    $target = $encryption === 'ssl' ? 'ssl://' . $host . ':' . $port : 'tcp://' . $host . ':' . $port;
    $errno = 0;
    $errstr = '';
    $socket = @stream_socket_client($target, $errno, $errstr, $timeout, STREAM_CLIENT_CONNECT);
    if (!$socket) {
        throw new RuntimeException('Could not connect to the SMTP server. Check the host and port in otp-config.php.');
    }

    stream_set_timeout($socket, $timeout);
    try {
        smtpExpect($socket, [220]);
        $hostname = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $hostname = preg_replace('/[^a-zA-Z0-9.\-]/', '', $hostname) ?: 'localhost';
        smtpCommand($socket, 'EHLO ' . $hostname, [250]);

        if ($encryption === 'tls') {
            smtpCommand($socket, 'STARTTLS', [220]);
            if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                throw new RuntimeException('Could not establish encrypted SMTP connection.');
            }
            smtpCommand($socket, 'EHLO ' . $hostname, [250]);
        } elseif ($encryption !== 'ssl' && $encryption !== 'none') {
            throw new RuntimeException('Unsupported SMTP encryption. Use tls or ssl.');
        }

        smtpCommand($socket, 'AUTH LOGIN', [334]);
        smtpCommand($socket, base64_encode((string)$config['username']), [334]);
        smtpCommand($socket, base64_encode((string)$config['password']), [235]);
        smtpCommand($socket, 'MAIL FROM:<' . $config['from_email'] . '>', [250]);
        smtpCommand($socket, 'RCPT TO:<' . $recipient . '>', [250, 251]);
        smtpCommand($socket, 'DATA', [354]);

        $subject = 'Your CodeandCore login OTP';
        $body = "Your CodeandCore login OTP is: {$otp}\r\n\r\n"
            . "This code expires in 10 minutes. Never share this code with anyone.\r\n"
            . "If you did not request this code, you can ignore this email.";
        $fromName = '=?UTF-8?B?' . base64_encode((string)$config['from_name']) . '?=';
        $headers = [
            'From: ' . $fromName . ' <' . $config['from_email'] . '>',
            'To: <' . $recipient . '>',
            'Subject: ' . $subject,
            'Date: ' . date(DATE_RFC2822),
            'MIME-Version: 1.0',
            'Content-Type: text/plain; charset=UTF-8',
            'Content-Transfer-Encoding: 8bit',
        ];
        $message = implode("\r\n", $headers) . "\r\n\r\n" . $body;
        $message = preg_replace('/\r?\n\./', "\r\n..", $message);
        if (fwrite($socket, $message . "\r\n.\r\n") === false) {
            throw new RuntimeException('Could not send the email message.');
        }
        smtpExpect($socket, [250]);
        smtpCommand($socket, 'QUIT', [221]);
    } finally {
        fclose($socket);
    }
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    otpJsonResponse(false, 'Method not allowed.', [], 405);
}

$postedToken = (string)($_POST['csrf_token'] ?? '');
$sessionToken = (string)($_SESSION['otp_csrf_token'] ?? '');
if ($sessionToken === '' || $postedToken === '' || !hash_equals($sessionToken, $postedToken)) {
    otpJsonResponse(false, 'Your session expired. Refresh the page and try again.', [], 403);
}
if (!isset($conn) || !$conn || mysqli_connect_errno()) {
    otpJsonResponse(false, 'Database connection failed. Please contact the administrator.', [], 500);
}

$action = (string)($_POST['action'] ?? '');
$email = strtolower(trim((string)($_POST['email'] ?? '')));

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    otpJsonResponse(false, 'Please enter a valid email address.', [], 422);
}

// The users table is the source of truth for OTP-enabled accounts.
// No hardcoded email allowlist: an email must already exist in users.email.
$userStmt = mysqli_prepare($conn, 'SELECT id, username FROM users WHERE LOWER(email) = ? LIMIT 1');
if (!$userStmt) {
    otpJsonResponse(false, 'Could not verify the account. Please contact the administrator.', [], 500);
}
mysqli_stmt_bind_param($userStmt, 's', $email);
mysqli_stmt_execute($userStmt);
$userResult = mysqli_stmt_get_result($userStmt);
$user = $userResult ? mysqli_fetch_assoc($userResult) : null;
mysqli_stmt_close($userStmt);
if (!$user) {
    otpJsonResponse(false, 'Email not found. Please use an email registered in the users table.', [], 404);
}
$userId = (int)$user['id'];

if ($action === 'send_otp') {
    $existingStmt = mysqli_prepare($conn, 'SELECT created_at FROM login_email_otps WHERE user_id = ? LIMIT 1');
    if (!$existingStmt) {
        otpJsonResponse(false, 'OTP storage is not ready. Run the OTP database migration first.', [], 500);
    }
    mysqli_stmt_bind_param($existingStmt, 'i', $userId);
    mysqli_stmt_execute($existingStmt);
    $existingResult = mysqli_stmt_get_result($existingStmt);
    $existingOtp = $existingResult ? mysqli_fetch_assoc($existingResult) : null;
    mysqli_stmt_close($existingStmt);
    if ($existingOtp) {
        $lastSent = strtotime((string)$existingOtp['created_at']);
        $secondsLeft = 60 - (time() - $lastSent);
        if ($secondsLeft > 0) {
            otpJsonResponse(false, 'Please wait ' . $secondsLeft . ' seconds before requesting another OTP.', ['resend_after' => $secondsLeft], 429);
        }
    }

    $otp = (string)random_int(100000, 999999);
    $otpHash = password_hash($otp, PASSWORD_DEFAULT);
    $expiresAt = date('Y-m-d H:i:s', time() + 600);
    $ip = substr((string)($_SERVER['REMOTE_ADDR'] ?? ''), 0, 45);
    $createdAt = date('Y-m-d H:i:s');
    $insertStmt = mysqli_prepare($conn, 'INSERT INTO login_email_otps (user_id, otp_hash, expires_at, attempts, created_at, request_ip) VALUES (?, ?, ?, 0, ?, ?) ON DUPLICATE KEY UPDATE otp_hash = VALUES(otp_hash), expires_at = VALUES(expires_at), attempts = 0, created_at = VALUES(created_at), request_ip = VALUES(request_ip), used_at = NULL');
    if (!$insertStmt) {
        otpJsonResponse(false, 'OTP storage is not ready. Run the OTP database migration first.', [], 500);
    }
    mysqli_stmt_bind_param($insertStmt, 'issss', $userId, $otpHash, $expiresAt, $createdAt, $ip);
    $stored = mysqli_stmt_execute($insertStmt);
    mysqli_stmt_close($insertStmt);
    if (!$stored) {
        otpJsonResponse(false, 'Could not create an OTP. Please try again.', [], 500);
    }

    try {
        sendOtpEmail($email, $otp);
    } catch (Throwable $e) {
        $deleteStmt = mysqli_prepare($conn, 'DELETE FROM login_email_otps WHERE user_id = ?');
        if ($deleteStmt) {
            mysqli_stmt_bind_param($deleteStmt, 'i', $userId);
            mysqli_stmt_execute($deleteStmt);
            mysqli_stmt_close($deleteStmt);
        }
        error_log('OTP email send failed: ' . $e->getMessage());
        otpJsonResponse(false, $e->getMessage(), [], 503);
    }
    otpJsonResponse(true, 'OTP sent to your company email. It expires in 10 minutes.', ['resend_after' => 60]);
}

if ($action === 'verify_otp') {
    $otp = trim((string)($_POST['otp'] ?? ''));
    if (!preg_match('/^[0-9]{6}$/', $otp)) {
        otpJsonResponse(false, 'Enter the 6-digit OTP.', [], 422);
    }
    $otpStmt = mysqli_prepare($conn, 'SELECT otp_hash, expires_at, attempts, used_at FROM login_email_otps WHERE user_id = ? LIMIT 1');
    if (!$otpStmt) {
        otpJsonResponse(false, 'OTP storage is not ready. Run the OTP database migration first.', [], 500);
    }
    mysqli_stmt_bind_param($otpStmt, 'i', $userId);
    mysqli_stmt_execute($otpStmt);
    $otpResult = mysqli_stmt_get_result($otpStmt);
    $otpRow = $otpResult ? mysqli_fetch_assoc($otpResult) : null;
    mysqli_stmt_close($otpStmt);
    if (!$otpRow || !empty($otpRow['used_at'])) {
        otpJsonResponse(false, 'Request a new OTP before signing in.', [], 422);
    }
    if (strtotime((string)$otpRow['expires_at']) < time()) {
        otpJsonResponse(false, 'That OTP has expired. Request a new one.', [], 422);
    }
    if ((int)$otpRow['attempts'] >= 5) {
        otpJsonResponse(false, 'Too many incorrect attempts. Request a new OTP.', [], 429);
    }
    if (!password_verify($otp, (string)$otpRow['otp_hash'])) {
        $attemptStmt = mysqli_prepare($conn, 'UPDATE login_email_otps SET attempts = attempts + 1 WHERE user_id = ?');
        if ($attemptStmt) {
            mysqli_stmt_bind_param($attemptStmt, 'i', $userId);
            mysqli_stmt_execute($attemptStmt);
            mysqli_stmt_close($attemptStmt);
        }
        otpJsonResponse(false, 'Incorrect OTP. Please check the code and try again.', [], 422);
    }

    $usedAt = date('Y-m-d H:i:s');
    $useStmt = mysqli_prepare($conn, 'UPDATE login_email_otps SET used_at = ? WHERE user_id = ? AND used_at IS NULL');
    if (!$useStmt) {
        otpJsonResponse(false, 'Could not verify the OTP. Please try again.', [], 500);
    }
    mysqli_stmt_bind_param($useStmt, 'si', $usedAt, $userId);
    mysqli_stmt_execute($useStmt);
    $used = mysqli_stmt_affected_rows($useStmt) === 1;
    mysqli_stmt_close($useStmt);
    if (!$used) {
        otpJsonResponse(false, 'This OTP has already been used. Request a new one.', [], 422);
    }

    session_regenerate_id(true);
    $_SESSION['username'] = $email;
    $_SESSION['user_id'] = $userId;
    unset($_SESSION['otp_csrf_token']);

    // Clear legacy remember-me cookies so they cannot be reused by older code.
    setcookie('remember_user', '', time() - 3600, '/');
    setcookie('remember_username', '', time() - 3600, '/');

    // Keep the existing login-log integration, but identify the authentication method accurately.
    if (function_exists('getUserIP') && function_exists('getLocationByIP') && function_exists('parseUserAgent')) {
        $userIp = getUserIP();
        $location = getLocationByIP($userIp);
        $country = (string)($location['country'] ?? '');
        $city = (string)($location['city'] ?? '');
        $loginTime = date('Y-m-d H:i:s');
        $userAgent = (string)($_SERVER['HTTP_USER_AGENT'] ?? '');
        $ua = parseUserAgent($userAgent);
        $deviceType = (string)($ua['device_type'] ?? '');
        $browser = (string)($ua['browser'] ?? '');
        $os = (string)($ua['os'] ?? '');
        $method = 'Email OTP';
        $status = 'Active';
        $logStmt = mysqli_prepare($conn, 'INSERT INTO login_logs (username, ip_address, country, city, login_time, device_type, browser, os, login_method, session_status, user_agent) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        if ($logStmt) {
            mysqli_stmt_bind_param($logStmt, 'sssssssssss', $email, $userIp, $country, $city, $loginTime, $deviceType, $browser, $os, $method, $status, $userAgent);
            mysqli_stmt_execute($logStmt);
            $_SESSION['login_log_id'] = mysqli_insert_id($conn);
            mysqli_stmt_close($logStmt);
        }
    }

    otpJsonResponse(true, 'Login successful.', ['redirect' => 'portfolios.php']);
}

otpJsonResponse(false, 'Unknown OTP action.', [], 400);
