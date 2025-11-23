<?php 

    if (!isset($_SESSION['firstName'])) {
        $fullName = "Guest User";
        $role = "Student";
        $profilePic = "../assets/img/profile-pictures/profile.svg";
    } else {
        $fullName = htmlspecialchars($_SESSION['firstName'] . ' ' . $_SESSION['lastName']);
        $role = ucfirst(htmlspecialchars($_SESSION['role']));
        $profilePic = htmlspecialchars($_SESSION['profileData']['profile_picture'] ?? '../assets/img/profile-pictures/profile.svg');
    }

    $accountSettingsLink = ($role === 'Student') ? '../pages/student/account-settings-student.php' : '../pages/staff/account-settings-staff.php'
?>

<link rel="stylesheet" href="../../assets/css/header.css">

<header class="modern-header">
    <div class="brand-container">
        <div class="menu-toggle" id="mobile-menu-btn">
            <img src="../../assets/img/icons/burger-menu-icon.svg" alt="Menu">
        </div>
        <img src="../../assets/img/logo/logo.png" alt="Ikinamada Designs" class="logo">
        <div class="brand-text">
            <h4>Ikinamada</h4>
            <span>Designs</span>
        </div>
    </div>

    <div class="header-actions">
        <div class="search-container">
            <img src="../../assets/img/icons/search-icon.svg" alt="Search" class="search-icon">
            <input type="text" placeholder="Type to search...">
        </div>

        <div class="action-icons">
            <a href="#notification-pop-up" class="notif-btn">
                <img src="../../assets/img/icons/notif-icon.svg" alt="Notifications">
                <span class="badge-dot"></span>
            </a>
        </div>

        <div class="vertical-divider"></div>

        <div class="user-pill" onclick="location.href='#dropdown-menu'">
            <img src="../../assets/img/profile-pictures/profile.svg" alt="Profile" class="avatar">
            <div class="user-info">
                <span class="user-name">Timothy Dionela</span>
                <span class="user-role">Student</span>
            </div>
            <img src="../../assets/img/icons/arrow-down-icon.svg" alt="Dropdown" class="chevron">
        </div>
    </div>
</header>

<div id="notification-pop-up" class="overlay-header">
    <a href="#" class="overlay-cancel"></a>
    <div class="pop-up-box slide-down">
        <div class="pop-up-header">
            <h3>Notifications</h3>
            <a href="#">Mark all as read</a>
        </div>
        <div class="pop-up-list">
            <div class="notif-item unread">
                <div class="notif-icon-box">
                    <img src="../../assets/img/icons/notif-1-icon.svg" alt="Alert">
                </div>
                <div class="notif-text">
                    <p class="title">Welcome to CIS!</p>
                    <p class="desc">Setup your profile to get started.</p>
                    <span class="time">2 mins ago</span>
                </div>
            </div>
            <div class="notif-item">
                <div class="notif-icon-box">
                    <img src="../../assets/img/icons/notif-2-icon.svg" alt="Alert">
                </div>
                <div class="notif-text">
                    <p class="title">Grade Posted</p>
                    <p class="desc">Your grade for IT 114 is available.</p>
                    <span class="time">1 hour ago</span>
                </div>
            </div>
        </div>
        <div class="pop-up-footer">
            <a href="#">View All Notifications</a>
        </div>
    </div>
</div>

<div id="dropdown-menu" class="overlay-header">
    <a href="#" class="overlay-cancel"></a>
    <div class="pop-up-box dropdown-box slide-down">
        <div class="dropdown-header">
            <p>Signed in as</p>
            <strong>Timothy Dionela</strong>
        </div>
        <ul>
            <a href="../student/account-settings-student.html">
                <li><img src="../../assets/img/icons/account-settings-icon.svg"> Account Settings</li>
            </a>
            <a href="#">
                <li><img src="../../assets/img/icons/dark-mode-icon.svg"> Appearance</li>
            </a>
            <div class="menu-divider"></div>
            <a href="../auth/login.html" class="logout-link">
                <li><img src="../../assets/img/icons/logout-icon.svg"> Logout</li>
            </a>
        </ul>
    </div>
</div>