<?php 
include_once('db.php'); 
if (isset($_SESSION['username'])) {
    header("Location: portfolios.php");
    exit();
}
$saved_username = $_COOKIE['remember_username'] ?? '';
$is_remembered = !empty($_COOKIE['remember_user']) || !empty($_COOKIE['remember_username']);
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
        body {
            background-color: #f7f8fb;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            border: 1.5px solid #e8ecf4;
            border-radius: 14px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            background: #ffffff;
        }
        .login-card-header {
            background: #ffffff;
            border-bottom: 1px solid #f1f5f9;
            padding: 30px 24px 18px;
            text-align: center;
        }
        .login-card-header img {
            max-height: 44px;
            margin-bottom: 12px;
        }
        .login-card-header h3 {
            font-size: 20px;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
            letter-spacing: -0.2px;
        }
        .login-card-body {
            padding: 24px 28px 30px;
        }
        .form-group label {
            font-size: 13.5px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 6px;
        }
        .form-control {
            height: 42px;
            border-radius: 8px;
            border: 1.5px solid #e2e8f0;
            font-size: 14px;
            color: #1e293b;
            transition: all 0.2s ease;
        }
        .form-control:focus {
            border-color: #5b52f9;
            box-shadow: 0 0 0 3px rgba(91, 82, 249, 0.12);
        }
        .remember-checkbox-wrap {
            display: flex;
            align-items: center;
            margin-top: 10px;
            margin-bottom: 20px;
            user-select: none;
        }
        .remember-checkbox-wrap input[type="checkbox"] {
            width: 17px;
            height: 17px;
            cursor: pointer;
            accent-color: #5b52f9;
            margin: 0 8px 0 0;
        }
        .remember-checkbox-wrap label {
            font-size: 13.5px;
            color: #475569;
            font-weight: 500;
            margin: 0;
            cursor: pointer;
        }
        .btn-login {
            background-color: #5b52f9;
            border-color: #5b52f9;
            color: #ffffff !important;
            font-size: 14.5px;
            font-weight: 600;
            height: 42px;
            border-radius: 8px;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px rgba(91, 82, 249, 0.25);
        }
        .btn-login:hover {
            background-color: #483fe6;
            border-color: #483fe6;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(91, 82, 249, 0.35);
        }
        .login-error-alert {
            background-color: #fef2f2;
            border: 1px solid #fee2e2;
            color: #b91c1c;
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 9px;
            line-height: 1.4;
            animation: fadeInAlert 0.2s ease-in-out;
        }
        .login-error-alert i {
            font-size: 15px;
            color: #ef4444;
            flex-shrink: 0;
        }
        @keyframes fadeInAlert {
            from {
                opacity: 0;
                transform: translateY(-4px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5 col-lg-4">
                <div class="card login-card">
                    <div class="login-card-header">
                        <img src="images/cnc-logo-new.svg" alt="CodeandCore">
                        <h3>Login</h3>
                    </div>
                    <div class="login-card-body">
                        <?php if (!empty($login_error)): ?>
                            <div class="login-error-alert" role="alert">
                                <i class="fas fa-exclamation-circle"></i>
                                <span><?php echo htmlspecialchars($login_error); ?></span>
                            </div>
                        <?php endif; ?>

                        <form action="" method="post">
                            <input type="hidden" name="login_submit" value="1">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($saved_username); ?>" placeholder="Enter your username" required autocomplete="username">
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required autocomplete="current-password">
                            </div>
                            <div class="remember-checkbox-wrap">
                                <input type="checkbox" id="remember" name="remember" <?php echo $is_remembered ? 'checked' : ''; ?>>
                                <label for="remember">Remember me</label>
                            </div>
                            <button type="submit" name="login_submit" class="btn btn-login btn-block">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
