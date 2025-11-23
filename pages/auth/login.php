<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/auth.css"/>
    <link rel="icon" type="image/x-icon" href="../../assets/img/logo/logo.ico">
    <script src="../../assets/js/password-toggle.js" defer></script>
    <script src="../../assets/js/main.js" defer></script>
    <title>Student Login - School Portal</title>
</head>
<body>

    <div class="auth-container fadeIn">
        <div class="brand-side">
            <img src="../../assets/img/logo/for-guthib.png" alt="School Logo" class="logo">
            <h1>Welcome Back!</h1>
            <p>To keep connected with us please login with your personal info.</p>
            <a href="signup.php" class="switch-btn">Register New Account</a>
        </div>

        <div class="form-side">
            <div class="form-header">
                <h2>Login</h2>
                <p>Enter your credentials to access your dashboard.</p>
            </div>

            <?php if(isset($_GET['error'])): ?>
                <div class="alert alert-error">
                    <img src="../../assets/img/icons/error-icon.svg" alt="Error">
                    <span><?= htmlspecialchars($_GET['error']) ?></span>
                </div>
            <?php endif; ?>

            <?php if(isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    <img src="../../assets/img/icons/success-icon.svg" alt="Success">
                    <div style="display:flex; flex-direction:column;">
                        <strong>Registration Successful!</strong>
                        <span style="font-size: 0.9rem;">Your School ID: <strong><?= htmlspecialchars($_GET['new_id']) ?></strong></span>
                        <span style="font-size: 0.8rem; opacity: 0.8;">(Please wait for admin approval)</span>
                    </div>
                </div>
            <?php endif; ?>

            <form action="../../backend/controller.php?method_finder=login" method="POST">
                <div class="input-group">
                    <div class="input-wrapper">
                        <input type="text" name="identifier" placeholder="Email or School ID" required>
                        <img class="icon" src="../../assets/img/icons/student-id-icon.svg" alt="User">
                    </div>
                </div>

                <div class="input-group">
                    <div class="input-wrapper">
                        <input type="password" name="password" class="password-field" placeholder="Password" required>
                        <img class="icon" src="../../assets/img/icons/password-icon.svg" alt="Lock">
                        
                        <img src="../../assets/img/icons/eye-off-icon.svg" class="toggle-password" alt="Show Password">
                    </div>
                </div>

                <div style="text-align: right; margin-bottom: 1rem;">
                    <a href="#" style="color: var(--primary-color); font-size: 0.9rem;">Forgot Password?</a>
                </div>

                <button type="submit" class="btn-submit">Sign In</button>

                <div class="extra-links">
                    <span class="mobile-only">Don't have an account? <a href="signup.php">Register</a></span>
                </div>
            </form>
        </div>
    </div>
</body>
</html>