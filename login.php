<?php
include_once('db.php');

// Restore a remembered login when the regular PHP session has expired.
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

if (isset($_SESSION['username'])) {
    header("Location: portfolios.php");
    exit();
}

if (empty($_SESSION['otp_csrf_token'])) {
    $_SESSION['otp_csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['otp_csrf_token'];
$login_error = $_SESSION['login_error'] ?? '';
unset($_SESSION['login_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Codeandcore</title>
    <link rel="icon" type="image/png" sizes="16x16" href="images/favicon.ico">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body{background:#f7f8fb;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;min-height:100vh;display:flex;align-items:center;justify-content:center}
        .login-card{border:1.5px solid #e8ecf4;border-radius:14px;box-shadow:0 4px 20px rgba(0,0,0,.04);overflow:hidden;background:#fff}
        .login-card-header{background:#fff;border-bottom:1px solid #f1f5f9;padding:30px 24px 18px;text-align:center}
        .login-card-header img{max-height:44px;margin-bottom:12px}.login-card-header h3{font-size:20px;font-weight:700;color:#0f172a;margin:0}
        .login-card-body{padding:24px 28px 30px}.form-group label{font-size:13.5px;font-weight:600;color:#334155;margin-bottom:6px}
        .form-control{height:44px;border-radius:8px;border:1.5px solid #e2e8f0;font-size:14px}
        .form-control:focus{border-color:#5b52f9;box-shadow:0 0 0 3px rgba(91,82,249,.12)}
        .btn-login{background:#5b52f9;border-color:#5b52f9;color:#fff!important;font-size:14.5px;font-weight:600;height:44px;border-radius:8px;box-shadow:0 2px 8px rgba(91,82,249,.25);transition:background .15s ease,opacity .15s ease}
        .btn-login:hover{background:#483fe6;border-color:#483fe6}
        .btn-login:disabled{background:#8b83fb;border-color:#8b83fb;opacity:1;cursor:wait;box-shadow:none}
        .btn-login .otp-spinner{margin-right:8px}
        .login-message{border-radius:8px;padding:10px 14px;font-size:13px;margin-bottom:18px;display:none}
        .login-message.error{display:block;background:#fef2f2;border:1px solid #fee2e2;color:#b91c1c}.login-message.success{display:block;background:#f0fdf4;border:1px solid #bbf7d0;color:#166534}
        .small-note{font-size:12.5px;color:#64748b;line-height:1.5}
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-10 col-md-6 col-lg-4">
            <div class="card login-card">
                <div class="login-card-header">
                    <img src="images/cnc-logo-new.svg" alt="CodeandCore">
                    <h3>Email OTP Login</h3>
                </div>
                <div class="login-card-body">
                    <div id="login-message" class="login-message" role="alert"><?php echo htmlspecialchars($login_error, ENT_QUOTES, 'UTF-8'); ?></div>
                    <form id="email-form" novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
                        <div class="form-group">
                            <label for="email">Company email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="name@codeandcore.com" required autocomplete="email" maxlength="255">
                        </div>
                        <button type="submit" class="btn btn-login btn-block" id="send-otp-btn"><i class="fas fa-paper-plane mr-1"></i> Send OTP</button>
                    </form>
                    <form id="otp-form" style="display:none" novalidate>
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token, ENT_QUOTES, 'UTF-8'); ?>">
                        <div class="form-group">
                            <label for="otp">6-digit OTP</label>
                            <input type="text" class="form-control" id="otp" name="otp" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" placeholder="Enter email OTP" autocomplete="one-time-code" required>
                        </div>
                        <div class="form-group mb-3">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="remember-me" name="remember_me" value="1">
                                <label class="custom-control-label" for="remember-me">Remember me for 2 months</label>
                            </div>
                            <small class="form-text text-muted">Only select this on a device you trust.</small>
                        </div>
                        <button type="submit" class="btn btn-login btn-block" id="verify-otp-btn"><i class="fas fa-lock-open mr-1"></i> Verify and Login</button>
                        <button type="button" class="btn btn-link btn-block" id="resend-otp-btn">Resend OTP</button>
                        <button type="button" class="btn btn-link btn-block" id="change-email-btn">Use a different email</button>
                        <p class="small-note text-center mb-0">The OTP expires in 10 minutes. Never share it with anyone.</p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
(function(){
    const emailForm=document.getElementById('email-form');
    const otpForm=document.getElementById('otp-form');
    const emailInput=document.getElementById('email');
    const otpInput=document.getElementById('otp');
    const message=document.getElementById('login-message');
    const sendBtn=document.getElementById('send-otp-btn');
    const verifyBtn=document.getElementById('verify-otp-btn');
    const resendBtn=document.getElementById('resend-otp-btn');
    let cooldownTimer=null, resendUntil=0;
    function showMessage(text,type){
        message.textContent=text;message.className='login-message '+(type||'error');
    }
    function clearMessage(){message.textContent='';message.className='login-message';}
    async function request(action,button){
        if(button&&button.disabled)return null;
        clearMessage();
        const originalButtonContent=button?button.innerHTML:'';
        if(button){
            button.disabled=true;
            button.setAttribute('aria-busy','true');
            if(action==='send_otp'){
                button.innerHTML='<i class="fas fa-circle-notch fa-spin otp-spinner" aria-hidden="true"></i> Sending OTP…';
                showMessage('Sending your verification code by email. Please wait…','success');
            }else if(action==='verify_otp'){
                button.innerHTML='<i class="fas fa-circle-notch fa-spin otp-spinner" aria-hidden="true"></i> Verifying…';
            }else if(button===resendBtn){
                button.innerHTML='<i class="fas fa-circle-notch fa-spin otp-spinner" aria-hidden="true"></i> Sending…';
            }
        }
        const payload=new URLSearchParams({action:action,email:emailInput.value.trim(),csrf_token:document.querySelector('#email-form [name="csrf_token"]').value});
        if(action==='verify_otp'){
            payload.set('otp',otpInput.value.trim());
            payload.set('remember_me',document.getElementById('remember-me').checked?'1':'0');
        }
        try{
            const response=await fetch('otp-auth.php',{method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded;charset=UTF-8','X-Requested-With':'XMLHttpRequest'},body:payload.toString(),credentials:'same-origin'});
            const data=await response.json();
            if(!response.ok||!data.success)throw new Error(data.message||'Request failed. Please try again.');
            if(action==='send_otp'){
                emailForm.style.display='none';otpForm.style.display='block';otpInput.focus();
                showMessage(data.message,'success');startCooldown(data.resend_after||60);
            }else if(action==='verify_otp'){
                showMessage('Login successful. Redirecting…','success');window.location.href=data.redirect||'portfolios.php';
            }
            return data;
        }catch(error){showMessage(error.message||'Network error. Please try again.','error');return null;}
        finally{
            if(button){
                button.disabled=false;
                button.removeAttribute('aria-busy');
                button.innerHTML=originalButtonContent;
            }
        }
    }
    function startCooldown(seconds){
        resendUntil=Date.now()+seconds*1000;resendBtn.disabled=true;
        if(cooldownTimer)clearInterval(cooldownTimer);
        const tick=()=>{const left=Math.max(0,Math.ceil((resendUntil-Date.now())/1000));resendBtn.textContent=left?'Resend OTP ('+left+'s)':'Resend OTP';resendBtn.disabled=left>0;if(!left)clearInterval(cooldownTimer);};
        tick();cooldownTimer=setInterval(tick,1000);
    }
    emailForm.addEventListener('submit',e=>{e.preventDefault();if(emailInput.reportValidity())request('send_otp',sendBtn);});
    otpForm.addEventListener('submit',e=>{e.preventDefault();if(otpInput.reportValidity())request('verify_otp',verifyBtn);});
    resendBtn.addEventListener('click',()=>{if(Date.now()>=resendUntil)request('send_otp',resendBtn);});
    document.getElementById('change-email-btn').addEventListener('click',()=>{otpForm.style.display='none';emailForm.style.display='block';otpInput.value='';clearMessage();emailInput.focus();});
    <?php if (!empty($login_error)): ?>showMessage(<?php echo json_encode($login_error); ?>,'error');<?php endif; ?>
})();
</script>
</body>
</html>
