<?php 
    session_start();
    require_once '../../backend/controller.php';

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
        header("Location: ../auth/login.php?error=You don't have permission to access this page");
        exit();
    }

    if (!isset($_SESSION['profile_data'])) {
        session_destroy();
        header("Location: ../auth/login.php?error=Session expired. Please login again");
        exit();
    }

    $controller = new controller();
    $my_subjects = $controller->get_student_subjects($_SESSION['user_id']);
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
                <?php if (empty($my_subjects)): ?>
                    <div class="empty-state" style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: #888;">
                        <img src="../../assets/img/icons/search-icon.svg" style="width: 50px; opacity: 0.3; margin-bottom: 1rem">
                        <h3>No enrolled subject found.</h3>
                        <p>You might not be assigned to a section yet, or no subjects are added for your section.</p>
                    </div>
                <?php else: ?>
                    <?php
                        $colors = ['accent-purple', 'accent-blue', 'accent-orange', 'accent-green'];
                        $i = 0;

                        foreach ($my_subjects as $sub):
                            $color_class = $colors[$i % count($colors)];
                            $i++;

                            $instructor_name = $sub['first_name'] ? htmlspecialchars($sub['first_name'] . ' ' . $sub['last_name']) : "TBA";
                            $instructor_img = $sub['profile_picture'] ?? '../../assets/img/profile-pictures/profile-staff.svg'; 
                    ?>
                    <div class="subject-card <?= $color_class ?>">
                        <div class="card-top">
                            <div class="subject-badges">
                                <span class="badge"><?= htmlspecialchars($sub['subject_code']) ?></span>
                                <span class="units"><?= htmlspecialchars($sub['units']) ?> Units</span>
                            </div>
                            <button class="more-btn">
                                <i class="fas fa-ellipsis-h"></i>
                            </button>
                        </div>
                            
                        <div class="card-body">
                            <h3 class="subject-name"><?= htmlspecialchars($sub['subject_description']) ?></h3>
                            <p class="section-name">Section: <?= htmlspecialchars($sub['section_assigned']) ?></p>

                            <div class="instructor-row">
                                <img src="<?= htmlspecialchars($instructor_img) ?>" alt="Instructor">
                                <div>
                                    <p class="label">Instructor</p>
                                    <p class="name"><?= $instructor_name ?></p>
                                </div>
                            </div>

                            <div class="schedule-info">
                                <div class="schedule-item">
                                    <i class="far fa-clock"></i>
                                    <span><?= htmlspecialchars($sub['schedule_time']) ?? 'TBA'?></span>
                                </div>
                                <div class="schedule-item">
                                    <i class="far fa-calendar"></i>
                                    <span><?= htmlspecialchars($sub['schedule_day']) ?? 'TBA'?></span>
                                </div>
                                <div class="schedule-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span><?= htmlspecialchars($sub['room']) ?? 'TBA'?></span>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer">
                            <a href="libraries.php?subject=<?= $sub['subject_id'] ?>" class="btn-outline" stlye="display: block; text-align: center;">
                                View Materials
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>