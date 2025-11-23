<?php 

    if (!isset($_SESSION['firstName'])) {
        $fullName = "TungTung Sahur";
        $uniqueID = "BRUHHH";
        $role = 'Student';
        $profilePic = "../assets/img/profile-pictures/profile.svg";
    } else {
        $fullName = htmlspecialchars($_SESSION['firstName'] . " " . $_SESSION['lastName']);
        $uniqueID = htmlspecialchars($_SESSION['uniqueID']);
        $role = ucfirst(htmlspecialchars($_SESSION['role']));
        $profilePic = htmlspecialchars($_SESSION['profileData']['profile_picture'] ?? '../assets/img/profile-pictures/profile.svg');
    }

    $settingsLink = '../pages/student/account-settings-student.php';
?>

<link rel="stylesheet" href="../../assets/css/sidebar.css">

<aside class="modern-sidebar">
    <div class="sidebar-profile">
        <div class="profile-card">
            <div class="profile-img-wrapper">
                <img src="../../assets/img/profile-pictures/profile.svg" alt="Profile" class="avatar">
                <span class="status-indicator"></span>
            </div>
            
            <div class="profile-info">
                <h3>Timothy Dionela</h3>
                <span class="role-label">Student ID</span>
                <p class="user-id">STU-2025-0001</p>
            </div>
            
            <div class="profile-menu-btn" role="button" onclick="toggleSidebarMenu()">
                <img src="../../assets/img/icons/kebab-menu-icon.svg" alt="Menu">
            </div>
        </div>

        <div id="sidebar-user-menu" class="sidebar-menu-wrapper">
            <div class="pop-up-box dropdown-box sidebar-dropdown">
                <ul>
                    <a href="../student/account-settings-student.html">
                        <li><img src="../../assets/img/icons/account-settings-icon.svg"> Edit Profile</li>
                    </a>
                    <a href="../student/account-settings-student.html">
                        <li><img src="../../assets/img/icons/kebab-menu-2-icon.svg"> Change Password</li>
                    </a>
                    <div class="menu-divider"></div>
                    <a href="../auth/login.html" class="logout-link">
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
                <a href="../student/student-dashboard.html" class="nav-link active">
                    <div class="icon-box">
                        <img src="../../assets/img/icons/dashboard-icon.svg" alt="Dashboard">
                    </div>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="../student/enrolled-subjects.html" class="nav-link">
                    <div class="icon-box">
                        <img src="../../assets/img/icons/enrolled-subjects-icon.svg" alt="Subjects">
                    </div>
                    <span>Enrolled Subjects</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="../student/grade-report.html" class="nav-link">
                    <div class="icon-box">
                        <img src="../../assets/img/icons/grade-report-icon.svg" alt="Grades">
                    </div>
                    <span>Grade Report</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="../student/libraries.html" class="nav-link">
                    <div class="icon-box">
                        <img src="../../assets/img/icons/libraries-icon.svg" alt="Library">
                    </div>
                    <span>Libraries</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="../student/payments.html" class="nav-link">
                    <div class="icon-box">
                        <img src="../../assets/img/icons/payments-icon.svg" alt="Payments">
                    </div>
                    <span>Payments</span>
                </a>
            </li>
            <div class="sidebar-spacer"></div>
            <li class="nav-item">
                <a href="../student/account-settings-student.html" class="nav-link">
                    <div class="icon-box">
                        <img src="../../assets/img/icons/account-settings-icon.svg" alt="Settings">
                    </div>
                    <span>Account Settings</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>