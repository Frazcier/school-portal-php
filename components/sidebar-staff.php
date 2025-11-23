<?php

    if (!isset($_SESSION['firstName'])) {
        $fullName = "Giga Chad";
        $uniqueID = "ADM-2025-0001";
        $role = "Administrator";
        $profilePic = "../../assets/img/profile-pictures/profile-staff.svg";
    } else {
        $fullName = htmlspecialchars($_SESSION['firstName'] . ' ' . $_SESSION['lastName']);
        $uniqueID = htmlspecialchars($_SESSION['uniqueID']);
        $role = ucfirst(htmlspecialchars($_SESSION['role']));
        $profilePic = htmlspecialchars($_SESSION['profileData']['profile_picture'] ?? '../../assets/img/profile-pictures/profile-staff.svg');
    }

    $settingsLink = '../staff/account-settings-staff.php';
    
?>

<link rel="stylesheet" href="../../assets/css/sidebar.css">

<aside class="modern-sidebar">
    <div class="sidebar-profile">
        <div class="profile-card">
            <div class="profile-img-wrapper">
                <img src="../../assets/img/profile-pictures/profile-staff.svg" alt="Profile" class="avatar">
                <span class="status-indicator"></span>
            </div>
            
            <div class="profile-info">
                <h3>Giga Chad</h3>
                <span class="role-label">System Role</span>
                <p class="user-id">Administrator</p>
            </div>
            
            <div class="profile-menu-btn" role="button" onclick="toggleSidebarMenu()">
                <img src="../../assets/img/icons/kebab-menu-icon.svg" alt="Menu">
            </div>
        </div>

        <div id="sidebar-user-menu" class="sidebar-menu-wrapper">
            <div class="pop-up-box dropdown-box sidebar-dropdown">
                <ul>
                    <a href="../staff/account-settings-staff.html">
                        <li><img src="../../assets/img/icons/account-settings-icon.svg"> Account Settings</li>
                    </a>
                    <a href="#">
                        <li><img src="../../assets/img/icons/kebab-menu-2-icon.svg"> Change Password</li>
                    </a>
                    <div class="menu-divider"></div>
                    <a href="../auth/login.php" class="logout-link">
                        <li><img src="../../assets/img/icons/kebab-menu-3-icon.svg"> Logout</li>
                    </a>
                </ul>
            </div>
        </div>
    </div>

    <div class="sidebar-divider"></div>

    <nav class="sidebar-nav">
        <ul class="nav-list">
            <li class="nav-item">
                <a href="../staff/staff-dashboard.html" class="nav-link active">
                    <div class="icon-box">
                        <img src="../../assets/img/icons/dashboard-icon.svg" alt="Dashboard">
                    </div>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="../staff/user-management.html" class="nav-link">
                    <div class="icon-box">
                        <img src="../../assets/img/icons/username-icon.svg" alt="Users">
                    </div>
                    <span>User Management</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="../staff/subject-management.html" class="nav-link">
                    <div class="icon-box">
                        <img src="../../assets/img/icons/subject-management-icon.svg" alt="Subjects">
                    </div>
                    <span>Sub Management</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="../staff/content-library.html" class="nav-link">
                    <div class="icon-box">
                        <img src="../../assets/img/icons/content-library-icon.svg" alt="Library">
                    </div>
                    <span>Content Library</span>
                </a>
            </li>

            <div class="sidebar-spacer"></div>

            <li class="nav-item">
                <a href="../staff/account-settings-staff.html" class="nav-link">
                    <div class="icon-box">
                        <img src="../../assets/img/icons/account-settings-icon.svg" alt="Settings">
                    </div>
                    <span>Account Settings</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>