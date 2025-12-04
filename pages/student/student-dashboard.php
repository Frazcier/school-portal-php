<?php 
    session_start();
    require_once '../../backend/controller.php';

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
        header("Location: ../auth/login.php");
        exit();
    }

    if (!isset($_SESSION['profile_data'])) {
        session_destroy();
        header("Location: ../auth/login.php?error=Session expired. Please login again");
        exit();
    }

    $controller = new controller();
    
    $student_data = $_SESSION['profile_data'];
    $full_name = htmlspecialchars($student_data['first_name'] . ' ' . $student_data['last_name']);
    $year_level = intval($student_data['year_level']);
    
    $current_semester_num = $year_level * 2; 
    $total_semesters = 8;
    $progress_percent = ($current_semester_num / $total_semesters) * 100;

    $subjects = $controller->get_student_subjects($_SESSION['user_id']);
    $announcements = $controller->get_announcements();
    $my_exams = $controller->get_student_exams($_SESSION['user_id']);
    $assignments = $controller->get_student_assignments($_SESSION['user_id']);
    
    $latest_announcement = !empty($announcements) ? $announcements[0] : null;

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
    <title>Student Dashboard</title>
</head>
<body>
    <?php include '../../components/splash.php'; ?>
    <?php require_once '../../components/header.php';?>
    
    <div class="container">
        <?php require_once '../../components/sidebar.php';?>

        <div class="content">
            
            <div class="section-1">
                <div class="welcome-section item-1">
                    <h2 class="title">Welcome, <span><?= $full_name ?>!</span></h2>
                    <p class="date"><?= $date_today ?></p>
                </div>
                <div class="progress-section item-2">
                    <p>Semester <span><?= $current_semester_num ?></span> of <?= $total_semesters ?></p>
                    <div class="progress-bar">
                        <style>.progress-bar::before { width: <?= $progress_percent ?>% !important; }</style>
                    </div>
                </div>
            </div>

            <div class="section-2">
                <div class="announcement-section item-1">
                    <div class="background">
                        <div class="blob"></div>
                        <div class="blob"></div>
                        <div class="blob"></div>
                    </div>
                    
                    <?php if ($latest_announcement): ?>
                        <div class="announcement-content">
                            <div class="announcement-header">
                                <div class="announcement-title">
                                    <img class="icon" src="../../assets/img/icons/announcement-title-icon.svg" alt="Icon">
                                    <h2>Announcement</h2>
                                </div>
                                <p class="date"><?= date("F d, Y", strtotime($latest_announcement['created_at'])) ?></p>
                            </div>
                            <div class="announcement-details">
                                <img class="icon" src="../../assets/img/icons/announcement-desc-icon.svg" alt="Icon">
                                <h2><?= htmlspecialchars(substr($latest_announcement['title'], 0, 80)) . (strlen($latest_announcement['title']) > 80 ? '...' : '') ?></h2>
                            </div>
                            <p style="color: rgba(255,255,255,0.8); font-size: 0.9rem; margin-top: 0.5rem;">
                                <?= htmlspecialchars(substr($latest_announcement['content'], 0, 100)) ?>...
                            </p>
                        </div>
                        <div class="announcement-button">
                            <p><a href="#">View Details</a></p>
                        </div>
                    <?php else: ?>
                        <div class="announcement-content" style="justify-content: center; align-items: center; height: 100%;">
                            <h2 style="color: white; opacity: 0.8;">No new announcements</h2>
                        </div>
                    <?php endif; ?>
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
                                <p><a href="enrolled-subjects.php">View All</a></p>
                                <img src="../../assets/img/icons/arrow-right-icon.svg" alt="Right Arrow Icon">
                            </div>
                        </div>
                        <div class="divider"></div>
                        
                        <div class="contents" id="subjectGrid">
                            <?php if (empty($subjects)): ?>
                                <div style="grid-column: 1/-1; padding: 2rem; text-align: center; color: #888; background: #f9f9f9; border-radius: 0.5rem;">
                                    <p>No subjects enrolled yet.</p>
                                </div>
                            <?php else: ?>
                                <?php 
                                $display_subjects = array_slice($subjects, 0, 3); 
                                
                                foreach($display_subjects as $sub): 
                                    $instructor = $sub['first_name'] ? htmlspecialchars($sub['first_name'] . ' ' . $sub['last_name']) : "TBA";
                                ?>
                                <div class="subject">
                                    <div class="subject-header">
                                        <p><?= htmlspecialchars($sub['subject_description']) ?> -</p>
                                        <p><?= htmlspecialchars($sub['subject_code']) ?></p>
                                    </div>
                                    <div class="divider"></div>
                                    <div class="subject-content">
                                        <div class="item">
                                            <img class="icon" src="../../assets/img/icons/profile-icon.svg" alt="Icon">
                                            <p><?= $instructor ?></p>
                                        </div>
                                        <div class="item">
                                            <img class="icon" src="../../assets/img/icons/calendar-icon.svg" alt="Icon">
                                            <p><?= htmlspecialchars($sub['schedule_day'] ?? 'TBA') ?></p>
                                        </div>
                                        <div class="item">
                                            <img class="icon" src="../../assets/img/icons/time-icon.svg" alt="Icon">
                                            <p><?= htmlspecialchars($sub['schedule_time'] ?? 'TBA') ?></p>
                                        </div>
                                        <div class="item">
                                            <img class="icon" src="../../assets/img/icons/location-icon.svg" alt="Icon">
                                            <p><?= htmlspecialchars($sub['room'] ?? 'TBA') ?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <?php if (count($subjects) > 3): ?>
                            <div style="text-align: center; margin-top: 1.5rem;">
                                <button id="loadSubjectsBtn" onclick="loadMoreSubjects()" 
                                        style="background: none; border: 1px solid var(--primary-color); color: var(--primary-color); padding: 0.5rem 1.5rem; border-radius: 2rem; cursor: pointer; font-size: 0.85rem; font-weight: 600;">
                                    Load More Subjects
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="exam-board-section">
                        <div class="dashboard-header">
                            <div class="title fadeIn">
                                <img class="icon" src="../../assets/img/icons/exam-board-icon.svg" alt="Icon">
                                <h3>Exam Board</h3>
                            </div>
                            </div>
                        <div class="divider"></div>
                        
                        <table class="exam-board-table fadeIn">
                            <thead>
                                <tr>
                                    <th><p>Subject Name</p></th>
                                    <th><p>Subject Code</p></th>
                                    <th><p>Date</p></th>
                                    <th><p>Location</p></th>
                                    <th><p>Status</p></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($my_exams)): ?>
                                    <tr>
                                        <td colspan="5" style="text-align:center; color:#888; padding: 2rem;">
                                            No upcoming exams scheduled.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($my_exams as $exam): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($exam['subject_description']) ?></td>
                                        <td><?= htmlspecialchars($exam['subject_code']) ?></td>
                                        <td><?= date("M d, Y", strtotime($exam['exam_date'])) ?></td>
                                        <td>
                                            <div style="display:flex; gap:0.5rem; align-items:center;">
                                                <i class="fas fa-map-marker-alt" style="font-size:0.8rem; opacity:0.5;"></i>
                                                <?= htmlspecialchars($exam['room']) ?>
                                                <span style="opacity:0.3;">|</span>
                                                <i class="far fa-clock" style="font-size:0.8rem; opacity:0.5;"></i>
                                                <?= htmlspecialchars($exam['start_time']) ?>
                                            </div>
                                        </td>
                                        <td><span class="status upcoming">Upcoming</span></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="right-side item-2">
                    <div class="homeworks-section">
                        <h3 style="font-size:1.1rem; margin-bottom:1rem; color:var(--color-dark-text);">Recent Assignments</h3>
                        
                        <?php if (empty($assignments)): ?>
                            <div class="item-box" style="text-align:center; color:#888; border-style:dashed;">
                                <p style="margin:1rem;">No active assignments.</p>
                            </div>
                        <?php else: ?>
                            <?php foreach($assignments as $hw): ?>
                            <div class="item-box">
                                <div class="item-header">
                                    <p><?= htmlspecialchars($hw['subject_code']) ?></p>
                                    <?php 
                                        $date = strtotime($hw['created_at']);
                                        $dateStr = date("M d", $date);
                                    ?>
                                    <div class="status"><span>New</span></div>
                                </div>
                                <div class="divider"></div>
                                <div class="item-content">
                                    <p>Task: <span><?= htmlspecialchars($hw['title']) ?></span></p>
                                    <p>Posted: <span><?= $dateStr ?></span></p>
                                </div>
                                <a href="<?= htmlspecialchars($hw['file_path']) ?>" download style="font-size:0.8rem; color:var(--primary-color); margin-top:0.5rem; display:inline-block;">
                                    <i class="fas fa-download"></i> Download File
                                </a>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>