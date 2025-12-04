<?php 
    session_start();
    require_once '../../backend/controller.php';

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher' && $_SESSION['role'] !== 'admin') {
        header("Location: ../auth/login.php");
        exit();
    }

    $controller = new controller();
    $exams = $controller->get_all_exams();
    $subjects = $controller->get_all_subjects(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/management-common.css"/>
    <link rel="stylesheet" href="../../assets/css/enrolled-subjects.css"/>
    <link rel="icon" type="image/x-icon" href="../../assets/img/logo/logo.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="../../assets/js/main.js" defer></script>
    <script src="../../assets/js/modal.js" defer></script>
    <title>Exam Management</title>
    <style>
        .card-action-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            margin-top: 0.5rem;
        }
        .exam-date-badge {
            font-size: 0.8rem;
            font-weight: 600;
            color: #555;
            background: #f0f0f0;
            padding: 0.2rem 0.6rem;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <?php require_once '../../components/header.php'?>

    <div class="container">
        <?php require_once '../../components/sidebar.php'?>

        <div class="content">
            <div class="section-header">
                <div class="header-details">
                    <h1>Exam Management</h1>
                    <h3>Schedule and manage upcoming examinations.</h3>
                </div>
                <div class="header-actions">
                    <button class="btn-primary" onclick="document.getElementById('exam-modal').classList.add('active')">
                        <i class="fas fa-plus"></i> Schedule Exam
                    </button>
                </div>
            </div>

            <?php if(isset($_GET['success'])): ?>
                <div class="alert alert-success" style="margin-bottom: 1.5rem; padding: 1rem; background: #dcfce7; color: #166534; border-radius: 0.5rem; border: 1px solid #bbf7d0;">
                    <i class="fas fa-check-circle"></i> <span><?= htmlspecialchars($_GET['success']) ?></span>
                </div>
            <?php endif; ?>
            <?php if(isset($_GET['error'])): ?>
                <div class="alert alert-error" style="margin-bottom: 1.5rem; padding: 1rem; background: #fee2e2; color: #991b1b; border-radius: 0.5rem; border: 1px solid #fecaca;">
                    <i class="fas fa-exclamation-circle"></i> <span><?= htmlspecialchars($_GET['error']) ?></span>
                </div>
            <?php endif; ?>

            <div class="subjects-grid">
                <?php if(empty($exams)): ?>
                    <div class="empty-state" style="grid-column: 1/-1; background:white; border-radius:1rem; border:1px solid #eee; padding: 2rem; text-align: center;">
                        <p style="color:#888;">No exams scheduled yet.</p>
                    </div>
                <?php else: ?>
                    <?php 
                    $colors = ['accent-purple', 'accent-blue', 'accent-orange', 'accent-green'];
                    $i = 0;

                    foreach($exams as $ex): 
                        $color_class = $colors[$i % count($colors)];
                        $i++;
                        
                        $statusClass = 'active'; 
                        $statusText = $ex['status'];
                        if($ex['status'] === 'Completed') { $statusClass = 'inactive'; $statusText = 'Done'; }
                        if($ex['status'] === 'Cancelled') { $statusClass = 'failed'; }
                    ?>
                    
                    <div class="subject-card <?= $color_class ?>">
                        <div class="card-top">
                            <div class="subject-badges">
                                <span class="badge"><?= htmlspecialchars($ex['subject_code']) ?></span>
                                <span class="exam-date-badge">
                                    <i class="far fa-calendar-alt"></i> <?= date("M d", strtotime($ex['exam_date'])) ?>
                                </span>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <h3 class="subject-name"><?= htmlspecialchars($ex['subject_description']) ?></h3>
                            <p class="section-name">Section: <strong><?= htmlspecialchars($ex['section_assigned']) ?></strong></p>

                            <div class="schedule-info">
                                <div class="schedule-item">
                                    <i class="far fa-clock"></i>
                                    <span><?= htmlspecialchars($ex['start_time']) ?></span>
                                </div>
                                <div class="schedule-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span><?= htmlspecialchars($ex['room']) ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer" style="flex-direction: column; gap: 0.5rem;">
                            <div class="card-action-row">
                                <span class="status-pill <?= $statusClass ?>"><?= $statusText ?></span>
                                
                                <div class="action-buttons">
                                    <?php if($ex['status'] === 'Upcoming'): ?>
                                        <form action="../../backend/controller.php?method_finder=complete_exam" method="POST" style="display:inline;">
                                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                                            <input type="hidden" name="exam_id" value="<?= $ex['exam_id'] ?>">
                                            <button type="submit" class="icon-btn" title="Mark as Completed">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    
                                    <form action="../../backend/controller.php?method_finder=delete_exam" method="POST" style="display:inline;" onsubmit="return confirm('Cancel this exam?');">
                                        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                                        <input type="hidden" name="exam_id" value="<?= $ex['exam_id'] ?>">
                                        <button type="submit" class="icon-btn delete" title="Delete Schedule">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div id="exam-modal" class="modal-overlay" onclick="closeModals(event)">
        <div class="modal-box" style="max-width: 700px;">
            <button class="close-btn" onclick="closeModals(event)">&times;</button>
            <div class="modal-header"><h3>Schedule New Exam</h3></div>
            
            <form action="../../backend/controller.php?method_finder=add_exam" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <div class="form-grid single-col" style="margin-bottom: 1rem;">
                    <div class="input-group">
                        <label>Subject</label>
                        <select name="subject_id" required style="width:100%; padding:0.8rem; border:1px solid #ddd; border-radius:0.5rem;">
                            <option value="" disabled selected>Select Subject</option>
                            <?php foreach($subjects as $sub): ?>
                                <option value="<?= $sub['subject_id'] ?>">
                                    <?= htmlspecialchars($sub['subject_code'] . " - " . $sub['section_assigned']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-grid" style="grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                    <div class="input-group">
                        <label>Date</label>
                        <input type="date" name="exam_date" required style="width:100%; padding:0.8rem; border:1px solid #ddd; border-radius:0.5rem;">
                    </div>
                    <div class="input-group">
                        <label>Time</label>
                        <input type="text" name="start_time" placeholder="e.g. 10:00 AM" required style="width:100%; padding:0.8rem; border:1px solid #ddd; border-radius:0.5rem;">
                    </div>
                    <div class="input-group">
                        <label>Room</label>
                        <select name="room" style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 0.5rem;">
                            <option value="" disabled selected>Select Room</option>
                            <optgroup label="Lecture Rooms">
                                <option value="CIS 301">CIS 301</option>
                                <option value="CIS 302">CIS 302</option>
                                <option value="CIS 303">CIS 303</option>
                            </optgroup>
                            <optgroup label="Laboratories">
                                <option value="CIS Lab 1">CIS Lab 1</option>
                                <option value="CIS Lab 2">CIS Lab 2</option>
                                <option value="CIS Lab 3">CIS Lab 3</option>
                            </optgroup>
                        </select>
                    </div>
                </div>
                
                <button type="submit" class="btn-primary" style="width:100%; margin-top:1.5rem;">Schedule Exam</button>
            </form>
        </div>
    </div>
</body>
</html>