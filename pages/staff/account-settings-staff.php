<?php 
    session_start();

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher' && $_SESSION['role'] !== 'admin') {
        header("Location: ../auth/login.php");
        exit();
    }

    if (!isset($_SESSION['profile_data'])) {
        session_destroy();
        header("Location: ../auth/login.php?error=Session expired. Please login again");
        exit();
    }

    $profile = $_SESSION['profile_data'];
    $email = $_SESSION['email'];
    $full_name = htmlspecialchars($profile['first_name'] . ' ' . $profile['last_name']);
    $staff_id = htmlspecialchars($_SESSION['unique_id'] ?? 'N/A');
    $pic = htmlspecialchars($profile['profile_picture'] ?? '../../assets/img/profile-pictures/profile-staff.svg');

    if ($_SESSION['role'] === 'teacher') {
        $id_label = 'Teacher';
    } else {
        $id_label = "Administrator";
    }
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
    <?php require_once '../../components/header.php'?>

    <div class="container">
        <?php require_once '../../components/sidebar.php'?>

        <div class="content">
            
            <div class="section-header">
                <div class="header-details">
                    <h1>Account Settings</h1>
                    <h3>Manage your personal details and security preferences.</h3>
                </div>
                <div class="header-actions">
                    <button class="btn-primary">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </div>

            <?php if(isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    <img src="../../assets/img/icons/notif-1-icon.svg" alt="Success">
                    <span><?= htmlspecialchars($_GET['success']) ?></span>
                </div>
            <?php endif; ?>

            <?php if(isset($_GET['error'])): ?>
                <div class="alert alert-error">
                    <img src="../../assets/img/icons/notif-3-icon.svg" alt="Error">
                    <span><?= htmlspecialchars($_GET['error']) ?></span>
                </div>
            <?php endif; ?>

            <div class="settings-card profile-section">
                <div class="profile-header">
                    <div class="profile-img-container">
                        <img src="<?= $pic ?>" alt="Profile">
                        <button class="edit-icon" title="Change Picture">
                            <i class="fas fa-camera"></i>
                        </button>
                    </div>
                    <div class="profile-info-text">
                        <h2><?= $full_name ?></h2>
                        <p><?= $id_label ?> &bullet; ID: <?= $staff_id ?></p>
                    </div>
                </div>
                <div class="profile-actions">
                    <button class="btn-secondary small">Change Picture</button>
                </div>
            </div>

            <div class="settings-card">
                <div class="card-title-row">
                    <h3>Personal Information</h3>
                    <p>Update your official identification details.</p>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label>First Name</label>
                        <div class="input-box">
                            <i class="fas fa-user icon"></i>
                            <input type="text" value="<?= htmlspecialchars($profile['first_name']) ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Middle Name</label>
                        <div class="input-box">
                            <i class="fas fa-user icon"></i>
                            <input type="text" placeholder="<?= htmlspecialchars($profile['middle_name']) ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <div class="input-box">
                            <i class="fas fa-user icon"></i>
                            <input type="text" value="<?= htmlspecialchars($profile['last_name']) ?>" required>
                        </div>
                    </div>
                </div>

                <div class="form-grid single-col">
                    <div class="form-group">
                        <label>Contact Email</label>
                        <div class="input-box">
                            <i class="fas fa-envelope icon"></i>
                            <input type="email" value="<?= htmlspecialchars($email) ?>" required>
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
                            <input type="password" placeholder="••••••••">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>New Password</label>
                        <div class="input-box">
                            <i class="fas fa-key icon"></i>
                            <input type="password" placeholder="Enter new password">
                        </div>
                    </div>
                </div>

                <div class="security-footer">
                    <div class="danger-zone">
                        <h4>Danger Zone</h4>
                        <p>Once you delete your account, there is no going back.</p>
                        <button class="btn-danger">Request Account Deletion</button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>
</html>