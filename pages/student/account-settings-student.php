<?php 
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../../auth/login.php");
    exit();
}

$p = $_SESSION['profile_data'];
$email = $_SESSION['email'];
$full_name = htmlspecialchars($p['first_name'] . ' ' . $p['last_name']);
$student_id = htmlspecialchars($_SESSION['unique_id']);
$pic = htmlspecialchars($p['profile_picture'] ?? '../../assets/img/profile-pictures/profile.svg');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/management-common.css"/>
    <link rel="stylesheet" href="../../assets/css/account-settings.css"/>
    <link rel="icon" type="image/x-icon" href="../../assets/img/logo/logo.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="../../assets/js/main.js" defer></script>
    <title>Account Settings</title>
</head>
<body>
    <?php require_once '../../components/header.php'; ?>

    <div class="container">
        <?php require_once '../../components/sidebar.php'; ?>

        <div class="content">
            
            <form action="../../backend/controller.php?method_finder=update_profile" method="POST">
                <div class="section-header">
                    <div class="header-details">
                        <h1>Account Settings</h1>
                        <h3>Manage your personal details and security preferences.</h3>
                    </div>
                    <div class="header-actions">
                        <button type="submit" class="btn-primary">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </div>
                </div>

                <?php if(isset($_GET['success'])): ?>
                    <div style="padding:1rem; background:#d4edda; color:#155724; border-radius:0.5rem; margin-bottom:1.5rem;">
                        <?= htmlspecialchars($_GET['success']) ?>
                    </div>
                <?php endif; ?>
                <?php if(isset($_GET['error'])): ?>
                    <div style="padding:1rem; background:#f8d7da; color:#721c24; border-radius:0.5rem; margin-bottom:1.5rem;">
                        <?= htmlspecialchars($_GET['error']) ?>
                    </div>
                <?php endif; ?>

                <div class="settings-card profile-section">
                    <div class="profile-header">
                        <div class="profile-img-container">
                            <img src="<?= $pic ?>" alt="Profile">
                            <button type="button" class="edit-icon" title="Change Picture">
                                <i class="fas fa-camera"></i>
                            </button>
                        </div>
                        <div class="profile-info-text">
                            <h2><?= $full_name ?></h2>
                            <p>Student &bullet; ID: <?= $student_id ?></p>
                        </div>
                    </div>
                    <div class="profile-actions">
                        <button type="button" class="btn-secondary small">Remove Picture</button>
                    </div>
                </div>

                <div class="settings-card">
                    <div class="card-title-row">
                        <h3>Personal Information</h3>
                        <p>Update your student records and contact info.</p>
                    </div>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label>First Name</label>
                            <div class="input-box">
                                <i class="fas fa-user icon"></i>
                                <input type="text" name="first_name" value="<?= htmlspecialchars($p['first_name']) ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Middle Name</label>
                            <div class="input-box">
                                <i class="fas fa-user icon"></i>
                                <input type="text" name="middle_name" value="<?= htmlspecialchars($p['middle_name']) ?>" placeholder="Optional">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Last Name</label>
                            <div class="input-box">
                                <i class="fas fa-user icon"></i>
                                <input type="text" name="last_name" value="<?= htmlspecialchars($p['last_name']) ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="form-grid single-col">
                        <div class="form-group">
                            <label>Contact Email</label>
                            <div class="input-box">
                                <i class="fas fa-envelope icon"></i>
                                <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="settings-card">
                    <div class="card-title-row">
                        <h3>Security & Login</h3>
                        <p>Manage your password and account access.</p>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label>Current Password</label>
                            <div class="input-box">
                                <i class="fas fa-lock icon"></i>
                                <input type="password" name="current_password" placeholder="••••••••">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>New Password</label>
                            <div class="input-box">
                                <i class="fas fa-key icon"></i>
                                <input type="password" name="new_password" placeholder="Enter new password">
                            </div>
                        </div>
                    </div>

                    <div class="security-footer">
                        <div class="danger-zone">
                            <h4>Danger Zone</h4>
                            <p>Deactivating your account will restrict access to portal services.</p>
                            <button type="button" class="btn-danger">Request Account Deactivation</button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</body>
</html>