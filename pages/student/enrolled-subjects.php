<?php 
    session_start();

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
        header("Location: ../auth/login.php");
        exit();
    }

    if (!isset($_SESSION['profile_data'])) {
        session_destroy();
        header("Location: ../auth/login.php?error=Session expired. Please login again");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/enrolled-subjects.css"/>
    <link rel="icon" type="image/x-icon" href="../../assets/img/logo/logo.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="../../assets/js/main.js" defer></script>
    <title>Enrolled Subjects</title>
</head>
<body>
    <?php require_once '../../components/header.php';?>

    <div class="container">
        <?php require_once '../../components/sidebar.php';?>

        <div class="content">
            
            <div class="section-header">
                <div class="header-details">
                    <h1>Enrolled Subjects</h1>
                    <h3>Manage your classes, view schedules, and access materials.</h3>
                </div>
                <div class="header-actions">
                    <button class="btn-secondary">
                        <i class="fas fa-print"></i> Print Schedule
                    </button>
                </div>
            </div>

            <div class="toolbar">
                <div class="search-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" placeholder="Search subject...">
                </div>
                <div class="filter-group">
                    <div class="select-wrapper">
                        <select>
                            <option selected>All Days</option>
                            <option>Mon / Wed</option>
                            <option>Tue / Thu</option>
                            <option>Friday</option>
                        </select>
                        <i class="fas fa-chevron-down chevron"></i>
                    </div>
                </div>
            </div>

            <div class="subjects-grid">
                
                <div class="subject-card accent-purple">
                    <div class="card-top">
                        <div class="subject-badges">
                            <span class="badge">IT 114</span>
                            <span class="units">3.0 Units</span>
                        </div>
                        <button class="more-btn"><i class="fas fa-ellipsis-h"></i></button>
                    </div>
                    <div class="card-body">
                        <h3 class="subject-name">Computer Programming 2</h3>
                        <p class="section-name">Section: BSIT 1A</p>
                        
                        <div class="instructor-row">
                            <img src="../../assets/img/profile-pictures/profile-picture-2.jpg" alt="Instructor">
                            <div>
                                <p class="label">Instructor</p>
                                <p class="name">Henson Sagorsor</p>
                            </div>
                        </div>

                        <div class="schedule-info">
                            <div class="schedule-item">
                                <i class="far fa-clock"></i>
                                <span>9:00 AM - 10:00 AM</span>
                            </div>
                            <div class="schedule-item">
                                <i class="far fa-calendar"></i>
                                <span>Mon, Wed</span>
                            </div>
                            <div class="schedule-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Room CIS 305</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn-outline full-width">View Materials</button>
                    </div>
                </div>

                <div class="subject-card accent-blue">
                    <div class="card-top">
                        <div class="subject-badges">
                            <span class="badge">IT 116</span>
                            <span class="units">3.0 Units</span>
                        </div>
                        <button class="more-btn"><i class="fas fa-ellipsis-h"></i></button>
                    </div>
                    <div class="card-body">
                        <h3 class="subject-name">Web Systems & Tech</h3>
                        <p class="section-name">Section: BSIT 1A</p>
                        
                        <div class="instructor-row">
                            <img src="../../assets/img/profile-pictures/profile-picture-5.jpg" alt="Instructor">
                            <div>
                                <p class="label">Instructor</p>
                                <p class="name">Chris Paza</p>
                            </div>
                        </div>

                        <div class="schedule-info">
                            <div class="schedule-item">
                                <i class="far fa-clock"></i>
                                <span>3:00 PM - 4:30 PM</span>
                            </div>
                            <div class="schedule-item">
                                <i class="far fa-calendar"></i>
                                <span>Mon, Wed</span>
                            </div>
                            <div class="schedule-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Room CIS 302</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn-outline full-width">View Materials</button>
                    </div>
                </div>

                <div class="subject-card accent-orange">
                    <div class="card-top">
                        <div class="subject-badges">
                            <span class="badge">IT 115</span>
                            <span class="units">2.0 Units</span>
                        </div>
                        <button class="more-btn"><i class="fas fa-ellipsis-h"></i></button>
                    </div>
                    <div class="card-body">
                        <h3 class="subject-name">Intro to HCI</h3>
                        <p class="section-name">Section: BSIT 1A</p>
                        
                        <div class="instructor-row">
                            <img src="../../assets/img/profile-pictures/profile-picture-8.jpg" alt="Instructor">
                            <div>
                                <p class="label">Instructor</p>
                                <p class="name">Delia Leon</p>
                            </div>
                        </div>

                        <div class="schedule-info">
                            <div class="schedule-item">
                                <i class="far fa-clock"></i>
                                <span>8:00 AM - 9:00 AM</span>
                            </div>
                            <div class="schedule-item">
                                <i class="far fa-calendar"></i>
                                <span>Tue, Thu</span>
                            </div>
                            <div class="schedule-item">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>Room CIS 302</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button class="btn-outline full-width">View Materials</button>
                    </div>
                </div>

                </div>
        </div>
    </div>
</body>
</html>