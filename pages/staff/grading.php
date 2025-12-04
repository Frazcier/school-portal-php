<?php
    session_start();
    require_once '../../backend/controller.php'; // Include the controller

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher' && $_SESSION['role'] !== 'admin') {
        header("Location: ../auth/login.php");
        exit();
    }

    if (!isset($_SESSION['profile_data'])) {
        session_destroy();
        header("Location: ../auth/login.php?error=Session expired. Please login again");
        exit();
    }
    
    $controller = new controller();
    
    // --- CONTEXT: Fetch Subject ID and Name (Example Placeholder) ---
    // Assuming the user is teaching subject_id = 1
    $current_subject_id = 1; 
    $current_subject_name = "IT 115 - Introduction to HCI"; 
    // ------------------------------------------------------------------
    
    // Fetch all student grade records for the current subject
    $grade_records = $controller->get_grades_for_subject($current_subject_id);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/management-common.css"/>
    <link rel="icon" type="image/x-icon" href="../../assets/img/logo/logo.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="../../assets/js/main.js" defer></script>
    <script src="../../assets/js/modal.js" defer></script>
    <title>Subject Grading</title>
</head>
<body>
    <?php require_once '../../components/header.php'?>

    <div class="container">
        <?php require_once '../../components/sidebar.php'?>

        <div class="content">
            
            <div class="section-header">
                <div class="header-details">
                    <h1>Subject Grading</h1>
                    <h3>Grade Management for <?= $current_subject_name ?></h3>
                </div>
                <div class="header-actions">
                    <button class="btn-secondary">
                        <i class="fas fa-file-export"></i> Export Grades
                    </button>
                    <!-- This button could open a modal for adding a new student or assignment -->
                    <button class="btn-primary">
                        <i class="fas fa-plus"></i> Add Column
                    </button>
                </div>
            </div>
            
            <?php if(isset($_GET['success'])): ?>
                <div class="alert alert-success" style="margin-top: 1rem;">
                    <img src="../../assets/img/icons/success-icon.svg" alt="Success">
                    <span style="display: flex; flex-direction: row;"><?= htmlspecialchars($_GET['success']) ?></span>
                </div>
            <?php endif; ?>
            <?php if(isset($_GET['error'])): ?>
                <div class="alert alert-error" style="margin-top: 1rem;">
                    <img src="../../assets/img/icons/error-icon.svg" alt="Error">
                    <span style="display: flex; flex-direction: row;"><?= htmlspecialchars($_GET['error']) ?></span>
                </div>
            <?php endif; ?>

            <div class="toolbar">
                <div class="search-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" placeholder="Search by student name or ID...">
                </div>
                
                <div class="filter-group">
                    <div class="select-wrapper">
                        <select>
                            <option value="" disabled selected>Section</option>
                            <option>BSIT 4A</option>
                            <option>BSIT 4B</option>
                        </select>
                        <i class="fas fa-chevron-down chevron"></i>
                    </div>

                    <div class="select-wrapper">
                        <select>
                            <option value="" disabled selected>Sort By</option>
                            <option>Name (A-Z)</option>
                            <option>Final Grade</option>
                        </select>
                        <i class="fas fa-chevron-down chevron"></i>
                    </div>

                    <button class="btn-filter">Filter</button>
                </div>
            </div>

            <div class="data-card">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Student ID</th>
                                <th>Activity 1 (Max 50)</th>
                                <th>Activity 2 (Max 50)</th>
                                <th>Total Score</th>
                                <td></td>
                                <th>Exam Score (Max 50)</th>
                                <th></th>
                                <th>Class Standing (4.0)</th>
                                <th>Exam Standing (4.0)</th>
                                <td></td>
                                <th>Final Grade (4.0)</th>
                                <th>Rounded Grade</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if (!empty($grade_records)):
                                foreach ($grade_records as $record): 
                                $full_name = htmlspecialchars($record['first_name'] . ' ' . $record['last_name']);
                                $total_score = $record['activity_1'] + $record['activity_2'];
                                
                                // Recalculate standings for display consistency (matching controller logic)
                                $max_class_score = 100.0;
                                $max_exam_score = 50.0;

                                // Use DB values if they exist, otherwise recalculate based on scores
                                $db_final_grade = $record['final_grade'] ?? (($total_score / $max_class_score) * 4.0 * 0.6) + (($record['exam_score'] / $max_exam_score) * 4.0 * 0.4);
                                $db_rounded_grade = $record['rounded_grade'] ?? round($db_final_grade * 4) / 4; 

                                $class_standing = number_format(($total_score / $max_class_score) * 4.0, 2);
                                $exam_standing = number_format(($record['exam_score'] / $max_exam_score) * 4.0, 2);

                                // Determine grade color style
                                $grade_style = 'avg';
                                if ($db_rounded_grade <= 1.25) $grade_style = 'highest';
                                else if ($db_rounded_grade >= 3.0) $grade_style = 'fail';
                            ?>
                            <tr>
                                <td><span class="code-badge"><?= $full_name ?></span></td>
                                <td><span class="code-badge"><?= htmlspecialchars($record['unique_id'] ?? 'N/A') ?></span></td>
                                <td data-score="a1"><?= htmlspecialchars($record['activity_1']) ?></td>
                                <td data-score="a2"><?= htmlspecialchars($record['activity_2']) ?></td>
                                <td class="text-right"><?= $total_score ?></td>
                                <td></td>
                                <td data-score="exam"><?= htmlspecialchars($record['exam_score']) ?></td>
                                <td></td>
                                <td><?= $class_standing ?></td>
                                <td><?= $exam_standing ?></td>
                                <td></td>
                                <td><?= number_format($db_final_grade, 3) ?></td>
                                <td class="grade <?= $grade_style ?>"><?= number_format($db_rounded_grade, 2) ?></td>
                                <td>
                                    <!-- EDIT BUTTON: Calls the JS function with dynamic data -->
                                    <button class="icon-btn edit" title="Edit Grades" 
                                        onclick="openEditGradeModal(
                                            '<?= $record['period_id'] ?>',
                                            '<?= $full_name ?>',
                                            '<?= htmlspecialchars($record['unique_id'] ?? 'N/A') ?>',
                                            '<?= htmlspecialchars($record['activity_1']) ?>',
                                            '<?= htmlspecialchars($record['activity_2']) ?>',
                                            '<?= htmlspecialchars($record['exam_score']) ?>'
                                        )">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; 
                            else: ?>
                            <tr>
                                <td colspan="14">
                                    <div class="empty-state" style="padding: 2rem;">
                                        <p>No grade records found for this subject. Ensure data is inserted into the `grading_periods` table.</p>
                                    </div>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
    
    <!-- NEW: EDIT GRADE MODAL HTML (Correctly defined IDs used by JavaScript) -->
    <div class="modal-overlay" id="edit-grade-modal" onclick="closeAllModals(event)">
        <div class="modal-box modal-small">
            <button class="close-btn" onclick="closeModal('edit-grade-modal')">&times;</button>
            <h3 style="margin-bottom: 0.5rem; color: var(--primary-color);">Edit Student Grades</h3>
            <p style="color: #666; font-size: 0.9rem; margin-bottom: 1.5rem;">Update the core scores below. Final grades are recalculated automatically.</p>
            
            <form action="../../backend/controller.php?method_finder=update_grade_record" method="POST">
                
                <input type="hidden" name="period_id" id="edit-period-id">

                <div class="form-group" style="margin-bottom: 1rem; padding: 1rem; background: #F9FAFB; border-radius: 0.5rem; border: 1px solid #eee;">
                    <label style="font-weight: 600; font-size: 0.9rem; color: var(--color-grey-text);">Student:</label>
                    <p id="student-name-display" style="font-size: 1.1rem; font-weight: 700; color: var(--color-dark-text); margin-bottom: 0.2rem;"></p>
                    <p style="font-size: 0.8rem; color: #9CA3AF;">ID: <span id="student-id-display"></span></p>
                </div>

                <div class="form-group" style="margin-bottom: 1rem;">
                    <label for="edit-activity-1">Activity 1 Score (Max 50)</label>
                    <input type="number" name="activity_1" id="edit-activity-1" class="input-full" min="0" max="50" required>
                </div>

                <div class="form-group" style="margin-bottom: 1rem;">
                    <label for="edit-activity-2">Activity 2 Score (Max 50)</label>
                    <input type="number" name="activity_2" id="edit-activity-2" class="input-full" min="0" max="50" required>
                </div>
                
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="edit-exam-score">Exam Score (Max 50)</label>
                    <input type="number" name="exam_score" id="edit-exam-score" class="input-full" min="0" max="50" required>
                </div>

                <button type="submit" class="btn-primary" style="width: 100%; height: 3rem;">Save Grades</button>
            </form>
        </div>
    </div>
    <!-- END: EDIT GRADE MODAL HTML -->
</body>
</html>