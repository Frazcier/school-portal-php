<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $role = $_SESSION['role'] ?? 'guest';
    $first_name = $_SESSION['first_name'] ?? 'Guest';
    $last_name = $_SESSION['last_name'] ?? 'User';
    $full_name = htmlspecialchars($first_name . ' ' . $last_name);

    $profile_data = $_SESSION['profile_data'] ?? [];
    
    if ($role === 'student') {
        $label = "Student ID";
        $default_pic = '../../assets/img/profile-pictures/profile.svg';
        $display_id = htmlspecialchars($_SESSION['unique_id'] ?? 'N/A');
        $profile_pic = $profile_data['profile_picture'] ?? $default_pic;
        $settings_link = "../student/account-settings-student.php";
    } else {
        $label = "System Role";
        $display_id = ucfirst($role);
        $profile_pic = $profile_data['profile_picture'] ?? $default_pic;
        $settings_link = "../staff/account-settings-staff.php";
        $default_pic = '../../assets/img/profile-pictures/profile-staff.svg';
    }
?>

<link rel="stylesheet" href="../../assets/css/sidebar.css">

<aside class="sidebar">
    <div class="sidebar-profile">
        <div class="profile-card">
            <div class="profile-img-wrapper">
                <img src="<?= htmlspecialchars($profile_pic) ?>" alt="Profile" class="avatar">
                <span class="status-indicator"></span>
            </div>
            
            <div class="profile-info">
                <h3><?= $full_name ?></h3>
                <span class="role-label"><?= $label ?></span>
                <p class="user-id"><?= $display_id ?></p>
            </div>
            
            <div class="profile-menu-btn" role="button" onclick="toggleSidebarMenu()">
                <img src="../../assets/img/icons/kebab-menu-icon.svg" alt="Menu">
            </div>
        </div>

        <div id="sidebar-user-menu" class="sidebar-menu-wrapper">
            <div class="pop-up-box dropdown-box sidebar-dropdown">
                <ul>
                    <a href="<?= $settings_link ?>">
                        <li><img src="../../assets/img/icons/account-settings-icon.svg"> Edit Profile</li>
                    </a>
                    <a href="<?= $settings_link ?>">
                        <li><img src="../../assets/img/icons/kebab-menu-2-icon.svg"> Change Password</li>
                    </a>
                    <div class="menu-divider"></div>
                    <a href="../../backend/controller.php?method_finder=logout" class="logout-link">
                        <li><img src="../../assets/img/icons/kebab-menu-3-icon.svg"> Logout</li>
                    </a>
                </ul>
            </div>
        </div>
    </div>

    <div class="sidebar-divider"></div>

    <nav class="sidebar-nav">
        <ul class="nav-list">
            
            <?php if ($role === 'student'): ?>
                <li class="nav-item">
                    <a href="../student/student-dashboard.php" class="nav-link">
                        <div class="icon-box"><img src="../../assets/img/icons/dashboard-icon.svg" alt="Dashboard"></div>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../student/enrolled-subjects.php" class="nav-link">
                        <div class="icon-box"><img src="../../assets/img/icons/enrolled-subjects-icon.svg" alt="Subjects"></div>
                        <span>Enrolled Subjects</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../student/grade-report.php" class="nav-link">
                        <div class="icon-box"><img src="../../assets/img/icons/grade-report-icon.svg" alt="Grades"></div>
                        <span>Grade Report</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../student/libraries.php" class="nav-link">
                        <div class="icon-box"><img src="../../assets/img/icons/libraries-icon.svg" alt="Library"></div>
                        <span>Libraries</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="../student/payments.php" class="nav-link">
                        <div class="icon-box"><img src="../../assets/img/icons/payments-icon.svg" alt="Payments"></div>
                        <span>Payments</span>
                    </a>
                </li>
                <div class="sidebar-spacer"></div>
                <li class="nav-item">
                    <a href="../student/account-settings-student.php" class="nav-link">
                        <div class="icon-box"><img src="../../assets/img/icons/account-settings-icon.svg" alt="Settings"></div>
                        <span>Account Settings</span>
                    </a>
                </li>

            <?php else: ?>
                <li class="nav-item">
                    <a href="../staff/staff-dashboard.php" class="nav-link">
                        <div class="icon-box"><img src="../../assets/img/icons/dashboard-icon.svg" alt="Dashboard"></div>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                <li class="nav-item">
                    <a href="../staff/user-management.php" class="nav-link">
                        <div class="icon-box"><img src="../../assets/img/icons/username-icon.svg" alt="Users"></div>
                        <span>User Management</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="../staff/subject-management.php" class="nav-link">
                        <div class="icon-box"><img src="../../assets/img/icons/subject-management-icon.svg" alt="Subjects"></div>
                        <span>Sub Management</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="../staff/content-library.php" class="nav-link">
                        <div class="icon-box"><img src="../../assets/img/icons/content-library-icon.svg" alt="Library"></div>
                        <span>Content Library</span>
                    </a>
                </li>

                <div class="sidebar-spacer"></div>

                <li class="nav-item">
                    <a href="../staff/account-settings-staff.php" class="nav-link">
                        <div class="icon-box"><img src="../../assets/img/icons/account-settings-icon.svg" alt="Settings"></div>
                        <span>Account Settings</span>
                    </a>
                </li>
            <?php endif; ?>

        </ul>
    </nav>
</aside>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const currentPath = window.location.pathname.split("/").pop();
        const navLinks = document.querySelectorAll('.nav-link');
        
        navLinks.forEach(link => {
            if (link.getAttribute('href').includes(currentPath)) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });
    });
</script>