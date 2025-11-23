<?php 
    session_start();

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher' && $_SESSION['role'] !== 'admin') {
        header("Location: ../auth/login.php?error=LOLOLOL");
        exit();
    }

    if (!isset($_SESSION['profile_data'])) {
        session_destroy();
        header("Location: ../auth/login.php?error=Session expired. Please login again");
        exit();
    }

    $first_name = $_SESSION['first_name'];
    $last_name = $_SESSION['last_name'];
    $full_name = htmlspecialchars($first_name . ' ' . $last_name);
    $academic_rank = $_SESSION['academic_rank'];
    $date_today = date("F j, Y");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/management-common.css"/>
    <link rel="stylesheet" href="../../assets/css/staff-dashboard.css"/>
    <link rel="icon" type="image/x-icon" href="../../assets/img/logo/logo.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="../../assets/js/main.js" defer></script>
    <title>Staff Dashboard</title>
</head>
<body>
    <?php include '../../components/splash.php'; ?>
    <?php require_once '../../components/header.php';?>

    <div class="container">

        <?php require_once '../../components/sidebar.php'?>

        <div class="content">
            
            <div class="section-header">
                <div class="header-details">
                    <h1>Welcome, <?= $full_name ?>!</h1>
                    <h3><?= $academic_rank ?> &bullet; <?= $date_today ?></h3>
                </div>
                <div class="header-actions">
                    <button class="btn-primary">
                        <i class="fas fa-bullhorn"></i> Create Announcement
                    </button>
                </div>
            </div>

            <div class="dashboard-grid">
                
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Quick Actions</h3>
                        <div class="search-wrapper small">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" placeholder="Find subject...">
                        </div>
                    </div>
                    <div class="quick-actions-grid">
                        <button class="action-tile">
                            <div class="icon-box purple"><i class="fas fa-plus"></i></div>
                            <span>Add Subject</span>
                        </button>
                        <button class="action-tile">
                            <div class="icon-box blue"><i class="fas fa-users"></i></div>
                            <span>Instructors</span>
                        </button>
                        <button class="action-tile">
                            <div class="icon-box orange"><i class="fas fa-file-alt"></i></div>
                            <span>Subject Reports</span>
                        </button>
                        <button class="action-tile">
                            <div class="icon-box green"><i class="far fa-calendar-alt"></i></div>
                            <span>Schedules</span>
                        </button>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Announcements</h3>
                        <a href="#" class="view-link">View All</a>
                    </div>
                    <div class="announcement-list">
                        <div class="announcement-item">
                            <div class="ann-icon">
                                <img src="../../assets/img/icons/announcement-title-icon.svg" alt="Icon">
                            </div>
                            <div class="ann-content">
                                <h4>New BYTE Officers Elected</h4>
                                <p>Published: April 27, 2025 &bullet; <span class="status-text active">Active</span></p>
                            </div>
                            <button class="icon-btn edit"><i class="fas fa-pen"></i></button>
                        </div>
                        <div class="announcement-item">
                            <div class="ann-icon">
                                <img src="../../assets/img/icons/announcement-title-icon.svg" alt="Icon">
                            </div>
                            <div class="ann-content">
                                <h4>Midterm Schedule Updates</h4>
                                <p>Published: April 25, 2025 &bullet; <span class="status-text active">Active</span></p>
                            </div>
                            <button class="icon-btn edit"><i class="fas fa-pen"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <h3 class="section-title">Recent System Activity</h3>
            <div class="data-card">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Action Type</th>
                                <th>User</th>
                                <th>Subject / Item</th>
                                <th>Date & Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="badge-soft blue">Grade Update</span></td>
                                <td>
                                    <div class="user-cell">
                                        <img src="../../assets/img/profile-pictures/profile-picture-2.jpg" alt="User">
                                        <span class="name">Henson Sagorsor</span>
                                    </div>
                                </td>
                                <td>Computer Programming 2</td>
                                <td>Apr 20, 2025 &bullet; 9:45 AM</td>
                                <td><span class="status-pill active">Completed</span></td>
                            </tr>
                            <tr>
                                <td><span class="badge-soft purple">Assignment</span></td>
                                <td>
                                    <div class="user-cell">
                                        <img src="../../assets/img/profile-pictures/profile-picture-8.jpg" alt="User">
                                        <span class="name">Delia Leon</span>
                                    </div>
                                </td>
                                <td>Introduction to HCI</td>
                                <td>Apr 22, 2025 &bullet; 8:40 AM</td>
                                <td><span class="status-pill active">Completed</span></td>
                            </tr>
                            <tr>
                                <td><span class="badge-soft orange">Deadline</span></td>
                                <td>
                                    <div class="user-cell">
                                        <img src="../../assets/img/profile-pictures/profile-picture-8.jpg" alt="User">
                                        <span class="name">Delia Leon</span>
                                    </div>
                                </td>
                                <td>Mid-Fidelity Project</td>
                                <td>Apr 26, 2025 &bullet; 10:00 PM</td>
                                <td><span class="status-pill active">Completed</span></td>
                            </tr>
                            <tr>
                                <td><span class="badge-soft green">Enrollment</span></td>
                                <td>
                                    <div class="user-cell">
                                        <img src="../../assets/img/profile-pictures/profile-staff.svg" alt="User">
                                        <span class="name">Dean's Office</span>
                                    </div>
                                </td>
                                <td>Timothy Dionela</td>
                                <td>Apr 27, 2025 &bullet; 6:00 PM</td>
                                <td><span class="status-pill active">Completed</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</body>
</html>