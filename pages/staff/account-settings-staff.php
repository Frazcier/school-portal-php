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

    $role_display = ucfirst($_SESSION['role']);
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
    <script src="../../assets/js/modal.js" defer></script>
    <title>Account Settings</title>
</head>
<body>
    <?php require_once '../../components/header.php'; ?>

    <div class="container">
        <?php require_once '../../components/sidebar.php'; ?>

        <div class="content">
            
            <form action="../../backend/controller.php?method_finder=update_profile" method="POST">

                <input type="hidden" name="profile_picture" id="selected-avatar-input" value="<?= $pic ?>">

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
                    <div class="alert alert-success">
                        <img src="../../assets/img/icons/success-icon.svg" alt="Success">
                        <span><?= htmlspecialchars($_GET['success']) ?></span>
                    </div>
                <?php endif; ?>

                <?php if(isset($_GET['error'])): ?>
                    <div class="alert alert-error">
                        <img src="../../assets/img/icons/error-icon.svg" alt="Error">
                        <span><?= htmlspecialchars($_GET['error']) ?></span>
                    </div>
                <?php endif; ?>

                <div class="settings-card profile-section">
                    <div class="profile-header">
                        <div class="profile-img-container" onclick="openViewModal()">
                            <img src="<?= $pic ?>" alt="Profile" id="current-avatar-display" style="cursor: zoom-in;">
                        </div>
                        <div class="profile-info-text">
                            <h2><?= $full_name ?></h2>
                            <p><?= $role_display ?> &bullet; ID: <?= $staff_id ?></p>
                        </div>
                    </div>
                    <div class="profile-actions">
                        <button type="button" class="btn-secondary small" onclick="openSelectorModal()">Change Picture</button>
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
                                <input type="text" name="first_name" value="<?= htmlspecialchars($profile['first_name']) ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Middle Name</label>
                            <div class="input-box">
                                <i class="fas fa-user icon"></i>
                                <input type="text" name="middle_name" value="<?= htmlspecialchars($profile['middle_name']) ?>" placeholder="Optional">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Last Name</label>
                            <div class="input-box">
                                <i class="fas fa-user icon"></i>
                                <input type="text" name="last_name" value="<?= htmlspecialchars($profile['last_name']) ?>" required>
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
                            <p>Once you delete your account, there is no going back.</p>
                            <button type="button" class="btn-danger">Request Account Deletion</button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>

    <div class="modal-overlay" id="view-modal" onclick="closeModals(event)">
        <div class="modal-box view-image-box">
            <img src="<?= $pic ?>" id="enlarged-image" alt="Full Size Profile">
        </div>
    </div>

    <div class="modal-overlay" id="selector-modal" onclick="closeModals(event)">
        <div class="modal-box">
            <button class="close-btn" onclick="closeModals(event)">&times;</button>
            <h3 style="margin-bottom: 0.5rem;">Choose an Avatar</h3>
            <p style="color: #666; font-size: 0.9rem;">Select one of our preset avatars to personalize your profile.</p>
            
            <div class="avatar-grid">
                <?php 
                
                for ($i = 1; $i <= 21; $i++) {
                    $imgPath = "../../assets/img/profile-pictures/profile-picture-$i.jpg";
                    echo "<img src='$imgPath' class='avatar-option' onclick=\"selectAvatar('$imgPath')\">";
                }

                $extras = ['profile-picture.jpg', 'profile-picture-staff.jpg'];
                foreach($extras as $extra) {
                    $imgPath = "../../assets/img/profile-pictures/$extra";
                    echo "<img src='$imgPath' class='avatar-option' onclick=\"selectAvatar('$imgPath')\">";
                }

                $my_rank = $p['academic_rank'] ?? '';

                if ($my_rank === 'Head Administrator' || $my_rank === 'System Admin') {
                    
                    $exclusiveImg = "../../assets/img/profile-pictures/admin-special.jpg";
                    
                    echo "
                    <img src='$exclusiveImg' 
                         class='avatar-option' 
                         onclick=\"selectAvatar('$exclusiveImg')\" 
                         title='Exclusive Head Admin Avatar'
                         style='border: 2px solid #FFD700; box-shadow: 0 0 15px rgba(255, 215, 0, 0.6); transform: scale(1.05);'>
                    ";
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>