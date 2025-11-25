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

    $full_name = htmlspecialchars($_SESSION['first_name'] . ' ' . $_SESSION['last_name']);
    $date_today = date("F j, Y");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/student-dashboard.css"/>
    <link rel="icon" type="image/x-icon" href="../../assets/img/logo/logo.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200">
    <script src="../../assets/js/calendar.js" defer></script>
    <script src="../../assets/js/main.js" defer></script>
    <title>STUDENT DASHBOARD UI</title>
</head>
<body>
    <?php include '../../components/splash.php'; ?>
    <?php require_once '../../components/header.php';?>
    
    <div class="container">
        <?php require_once '../../components/sidebar.php';?>

        <div class="content">
            <div class="section-1">
                <div class="welcome-section item-1">
                    <h2 class="title">Welcome, <span>Timothy Dionela!</span></h2>
                    <p class="date"><?= $date_today ?></p>
                </div>
                <div class="progress-section item-2">
                    <p>Semester <span>2</span> of 8</p>
                    <div class="progress-bar"></div>
                </div>
            </div>

            <div class="section-2">
                <div class="announcement-section item-1">
                    <div class="background">
                        <div class="blob"></div>
                        <div class="blob"></div>
                        <div class="blob"></div>
                    </div>
                    <div class="announcement-content">
                        <div class="announcement-header">
                            <div class="announcement-title">
                                <img class="icon" src="../../assets/img/icons/announcement-title-icon.svg" alt="Icon">
                                <h2>Announcement</h2>
                            </div>
                            <p class="date">April 28, 2025</p>
                        </div>
                        <div class="announcement-details">
                            <img class="icon" src="../../assets/img/icons/announcement-desc-icon.svg" alt="Icon">
                            <h2>New Officers are elected for the BYTE Organization.</h2>
                        </div>
                    </div>
                    <div class="announcement-button">
                        <p><a href="#">View Details</a></p>
                    </div>
                </div>
                
                <div class="calendar-section item-2">
                    <div class="wrapper">
                        <header>
                            <span id="prev" class="material-symbols-rounded">chevron_left</span>
                            <p class="current-date"></p>
                            <span id="next" class="material-symbols-rounded">chevron_right</span>
                        </header>
                        <div class="calendar">
                            <ul class="weeks">
                                <li>Mo</li>
                                <li>Tu</li>
                                <li>We</li>
                                <li>Th</li>
                                <li>Fr</li>
                                <li>Sa</li>
                                <li>Su</li>
                            </ul>
                            <ul class="days"></ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="section-3">
                <div class="left-side item-1">
                    <div class="enrolled-subjects-section">
                        <div class="dashboard-header">
                            <div class="title">
                                <img src="../../assets/img/icons/enrolled-subjects-icon.svg" alt="Icon">
                                <h3>Enrolled Subjects</h3>
                            </div>
                            <div class="link">
                                <p><a href="">View All</a></p>
                                <img src="../../assets/img/icons/arrow-right-icon.svg" alt="Right Arrow Icon">
                            </div>
                        </div>
                        <div class="divider"></div>
                        <div class="subjects">
                            <div class="subject-1"></div>
                            <div class="subject-2"></div>
                            <div class="subject-3"></div>
                            <div class="subject-4"></div>
                        </div>
                        <div class="contents">
                            <div class="subject">
                                <div class="subject-header">
                                    <p>Computer Programming 2 -</p>
                                    <p>IT 114</p>
                                </div>
                                <div class="divider"></div>
                                <div class="subject-content">
                                    <div class="item">
                                        <img class="icon" src="../../assets/img/icons/profile-icon.svg" alt="Icon">
                                        <p>Henson Sagorsor</p>
                                    </div>
                                    <div class="item">
                                        <img class="icon" src="../../assets/img/icons/calendar-icon.svg" alt="Icon">
                                        <p>Monday and Wednesday</p>
                                    </div>
                                    <div class="item">
                                        <img class="icon" src="../../assets/img/icons/time-icon.svg" alt="Icon">
                                        <p>9:00 AM to 10:00 AM</p>
                                    </div>
                                    <div class="item">
                                        <img class="icon" src="../../assets/img/icons/location-icon.svg" alt="Icon">
                                        <p>CIS 305</p>
                                    </div>
                                </div>
                            </div>
                            <div class="subject">
                                <div class="subject-header">
                                    <p>Introduction to HCI -</p>
                                    <p>IT 115</p>
                                </div>
                                <div class="divider"></div>
                                <div class="subject-content">
                                    <div class="item">
                                        <img class="icon" src="../../assets/img/icons/profile-icon.svg" alt="Icon">
                                        <p>Delia Leon</p>
                                    </div>
                                    <div class="item">
                                        <img class="icon" src="../../assets/img/icons/calendar-icon.svg" alt="Icon">
                                        <p>Tuesday and Thursday</p>
                                    </div>
                                    <div class="item">
                                        <img class="icon" src="../../assets/img/icons/time-icon.svg" alt="Icon">
                                        <p>8:00 AM to 9:00 AM</p>
                                    </div>
                                    <div class="item">
                                        <img class="icon" src="../../assets/img/icons/location-icon.svg" alt="Icon">
                                        <p>CIS 302</p>
                                    </div>
                                </div>
                            </div>
                            <div class="subject">
                                <div class="subject-header">
                                    <p>Web Systems -</p>
                                    <p>IT 116</p>
                                </div>
                                <div class="divider"></div>
                                <div class="subject-content">
                                    <div class="item">
                                        <img class="icon" src="../../assets/img/icons/profile-icon.svg" alt="Icon">
                                        <p>Chris Paza</p>
                                    </div>
                                    <div class="item">
                                        <img class="icon" src="../../assets/img/icons/calendar-icon.svg" alt="Icon">
                                        <p>Monday and Wednesday</p>
                                    </div>
                                    <div class="item">
                                        <img class="icon" src="../../assets/img/icons/time-icon.svg" alt="Icon">
                                        <p>3:00 PM to 4:30 PM</p>
                                    </div>
                                    <div class="item">
                                        <img class="icon" src="../../assets/img/icons/location-icon.svg" alt="Icon">
                                        <p>CIS 302</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="exam-board-section">
                        <div class="dashboard-header">
                            <div class="title fadeIn">
                                <img class="icon" src="../../assets/img/icons/exam-board-icon.svg" alt="Icon">
                                <h3>Exam Board</h3>
                            </div>
                            <div class="link">
                                <p><a href="">View All</a></p>
                                <img class="icon" src="../../assets/img/icons/arrow-right-icon.svg" alt="Right Arrow Icon">
                            </div>
                        </div>
                        <div class="divider"></div>
                        <table class="exam-board-table fadeIn">
                            <thead>
                                <tr>
                                    <th>
                                        <p>Subject Name</p>
                                        <img class="icon" src="../../assets/img/icons/sort-icon.svg" alt="Icon">
                                    </th>
                                    <th>
                                        <p>Subject Code</p>
                                        <img class="icon" src="../../assets/img/icons/sort-icon.svg" alt="Icon">
                                    </th>
                                    <th>
                                        <p>Date</p>
                                        <img class="icon" src="../../assets/img/icons/sort-icon.svg" alt="Icon">
                                    </th>
                                    <th>
                                        <p>Location</p>
                                        <img class="icon" src="../../assets/img/icons/sort-icon.svg" alt="Icon">
                                    </th>
                                    <th>
                                        <p>Status</p>
                                        <img class="icon" src="../../assets/img/icons/sort-icon.svg" alt="Icon">
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Web Systems and Technology 1</td>
                                    <td>IT 116.1</td>
                                    <td>April 28, 2025</td>
                                    <td>CIS 302</td>
                                    <td><span class="status upcoming">Upcoming</span></td>
                                </tr>
                                <tr>
                                    <td>Statistical Computing</td>
                                    <td>STATS 22</td>
                                    <td>April 29, 2025</td>
                                    <td>CAS AN 211</td>
                                    <td><span class="status upcoming">Upcoming</span></td>
                                </tr>
                                <tr>
                                    <td>Introduction to Human Computer</td>
                                    <td>IT 114</td>
                                    <td>May 7, 2025</td>
                                    <td>CIS 305</td>
                                    <td><span class="status upcoming">Upcoming</span></td>
                                </tr>
                                <tr>
                                    <td>Computer Programming 2</td>
                                    <td>IT 115</td>
                                    <td>May 8, 2025</td>
                                    <td>CIS 305</td>
                                    <td><span class="status upcoming">Upcoming</span></td>
                                </tr>
                                <tr>
                                    <td>Web Systems and Technology 1</td>
                                    <td>IT 116</td>
                                    <td>May 9, 2025</td>
                                    <td>CIS 302</td>
                                    <td><span class="status upcoming">Upcoming</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="right-side item-2">
                    <div class="homeworks-section">
                        <div class="item-box">
                            <div class="item-header">
                                <p>Computer Programming 2</p>
                                <div class="status"><span>Completed</span></div>
                            </div>
                            <div class="divider"></div>
                            <div class="item-content">
                                <p>Assignment: <span>Minesweeper Program</span></p>
                                <p>Date: <span>April 24, 2025</span></p>
                            </div>
                        </div>
                        <div class="item-box">
                            <div class="item-header">
                                <p>Introduction to HCI</p>
                                <div class="status"><span>In Progress</span></div>
                            </div>
                            <div class="divider"></div>
                            <div class="item-content">
                                <p>Assignment: <span>Mid Fidelity Final Projecy</span></p>
                                <p>Date: <span>April 28, 2025</span></p>
                            </div>
                        </div>
                        <div class="item-box">
                            <div class="item-header">
                                <p>Introduction to HCI</p>
                                <div class="status"><span>In Progress</span></div>
                            </div>
                            <div class="divider"></div>
                            <div class="item-content">
                                <p>Assignment: <span>Mid Fidelity Final Project</span></p>
                                <p>Date: <span>April 28, 2025</span></p>
                            </div>
                        </div>
                        <div class="item-box">
                            <div class="item-header">
                                <p>Introduction to HCI</p>
                                <div class="status"><span>In Progress</span></div>
                            </div>
                            <div class="divider"></div>
                            <div class="item-content">
                                <p>Assignment: <span>Mid Fidelity Final Project</span></p>
                                <p>Date: <span>April 28, 2025</span></p>
                            </div>
                        </div>
                        <div class="item-box">
                            <div class="item-header">
                                <p>Introduction to HCI</p>
                                <div class="status"><span>In Progress</span></div>
                            </div>
                            <div class="divider"></div>
                            <div class="item-content">
                                <p>Assignment: <span>Mid Fidelity Final Project</span></p>
                                <p>Date: <span>April 28, 2025</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>