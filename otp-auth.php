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

        $subject = 'Your CodeandCore login code';
        $plainBody = "CODEANDCORE | SECURE SIGN-IN\r\n\r\n"
            . "Use the verification code below to sign in to your CodeandCore account.\r\n\r\n"
            . "Your verification code: {$otp}\r\n\r\n"
            . "This code expires in 10 minutes. For your security, never share this code with anyone.\r\n\r\n"
            . "If you did not request this code, you can safely ignore this email. No changes have been made to your account.\r\n\r\n"
            . "— CodeandCore Security Team";

        $safeOtp = htmlspecialchars($otp, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $htmlBody = '<!doctype html><html lang="en"><head><meta charset="UTF-8">'
            . '<meta name="viewport" content="width=device-width, initial-scale=1.0">'
            . '<title>Your CodeandCore login code</title></head>'
            . '<body style="margin:0;padding:0;background:#f4f5fb;font-family:Arial,Helvetica,sans-serif;color:#182033;">'
            . '<div style="display:none;max-height:0;overflow:hidden;opacity:0;">Your secure CodeandCore sign-in code. Expires in 10 minutes.</div>'
            . '<table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#f4f5fb;padding:32px 12px;">'
            . '<tr><td align="center">'
            . '<table role="presentation" width="600" cellspacing="0" cellpadding="0" border="0" style="width:100%;max-width:600px;background:#ffffff;border:1px solid #e7e9f2;border-radius:16px;overflow:hidden;">'
            . '<tr><td style="background:#6547f5;padding:26px 32px;text-align:left;">'
            . '<div style="font-size:12px;font-weight:700;letter-spacing:2px;color:#e8e4ff;">CODEANDCORE</div>'
            . '<div style="font-size:23px;line-height:1.35;font-weight:700;color:#ffffff;margin-top:8px;">Secure sign-in</div>'
            . '</td></tr>'
            . '<tr><td style="padding:32px;">'
            . '<p style="font-size:16px;line-height:1.6;margin:0 0 12px;">Hello,</p>'
            . '<p style="font-size:15px;line-height:1.7;color:#4b5568;margin:0 0 24px;">We received a request to sign in to your CodeandCore account. Enter this one-time verification code in the login page to continue.</p>'
            . '<div style="border:1px solid #e6e2ff;background:#f8f7ff;border-radius:12px;padding:22px 16px;text-align:center;">'
            . '<div style="font-size:11px;font-weight:700;letter-spacing:1.6px;color:#6b62a8;text-transform:uppercase;">Your verification code</div>'
            . '<div style="font-family:Consolas,Monaco,monospace;font-size:34px;line-height:1.5;font-weight:700;letter-spacing:8px;color:#5637e8;padding-left:8px;">' . $safeOtp . '</div>'
            . '<div style="font-size:12px;color:#6b7280;margin-top:4px;">Expires in 10 minutes</div>'
            . '</div>'
            . '<p style="font-size:14px;line-height:1.7;color:#374151;margin:24px 0 8px;"><strong>Keep this code private.</strong> CodeandCore will never ask you to share your verification code with another person.</p>'
            . '<p style="font-size:13px;line-height:1.7;color:#6b7280;margin:0;">If you did not request this code, you can ignore this email. Your account will remain unchanged.</p>'
            . '</td></tr>'
            . '<tr><td style="background:#fafaff;border-top:1px solid #ececf4;padding:20px 32px;">'
            . '<p style="font-size:12px;line-height:1.6;color:#777b8c;margin:0;">Sent automatically by the CodeandCore web app. Please do not reply to this email.</p>'
            . '<p style="font-size:12px;font-weight:700;color:#6547f5;margin:8px 0 0;">CodeandCore Security Team</p>'
            . '</td></tr></table>'
            . '<p style="font-size:11px;line-height:1.6;color:#9296a5;text-align:center;margin:18px 0 0;">This is a transactional security email for a requested login.</p>'
            . '</td></tr></table></body></html>';

        $boundary = '=_CodeandCore_OTP_' . bin2hex(random_bytes(12));
        $fromName = '=?UTF-8?B?' . base64_encode((string)$config['from_name']) . '?=';
        $headers = [
            'From: ' . $fromName . ' <' . $config['from_email'] . '>',
            'To: <' . $recipient . '>',
            'Subject: ' . $subject,
            'Date: ' . date(DATE_RFC2822),
            'MIME-Version: 1.0',
            'Content-Type: multipart/alternative; boundary="' . $boundary . '"',
        ];
        $body = '--' . $boundary . "\r\n"
            . "Content-Type: text/plain; charset=UTF-8\r\n"
            . "Content-Transfer-Encoding: 8bit\r\n\r\n"
            . $plainBody . "\r\n\r\n"
            . '--' . $boundary . "\r\n"
            . "Content-Type: text/html; charset=UTF-8\r\n"
            . "Content-Transfer-Encoding: 8bit\r\n\r\n"
            . $htmlBody . "\r\n\r\n"
            . '--' . $boundary . "--\r\n";
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
