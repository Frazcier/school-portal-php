<?php
    session_start();
    require_once '../../backend/controller.php';
    require_once '../../backend/algorithms/MergeSort.php';

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher' && $_SESSION['role'] !== 'admin') {
        header("Location: ../auth/login.php?error=You don't have permission to access this page");
        exit();
    }

    $controller = new controller();
    $raw_subjects = $controller->get_all_subjects(); 
    $teachers = $controller->get_users_by_role('teacher', 'active');

    $filtered_subjects = [];
    $filter_dept = $_GET['dept'] ?? '';
    $filter_year = $_GET['year'] ?? '';
    $filter_inst = $_GET['instructor'] ?? '';

    foreach ($raw_subjects as $sub) {
        $include = true;
        if (!empty($filter_dept) && strpos($sub['section_assigned'], $filter_dept) !== 0) $include = false;
        if (!empty($filter_year) && strpos($sub['section_assigned'], $filter_year) === false) $include = false;
        if (!empty($filter_inst) && $sub['first_name'] !== $filter_inst) $include = false;

        if ($include) $filtered_subjects[] = $sub;
    }

    $sorter = new MergeSort();
    $subjects = $sorter->sort($filtered_subjects, 'subject_code'); 
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

    <title>Subject Management</title>
</head>
<body>
    <?php require_once '../../components/header.php'?>

    <div class="container">
        <?php require_once '../../components/sidebar.php'?>

        <div class="content">
            <div class="section-header">
                <div class="header-details">
                    <h1>Subject Management</h1>
                    <h3>Oversee curriculum, assign instructors, and manage schedules.</h3>
                </div>
                <div class="header-actions">
                    <button class="btn-secondary"><i class="fas fa-file-export"></i> Export List</button>
                    <button class="btn-primary" onclick="openSubjectModal()"><i class="fas fa-plus"></i> Add New Subject</button>
                </div>
            </div>

            <div class="toolbar" style="align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
                
                <div class="search-wrapper" style="flex: 1 1 300px;">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="realTimeSearch" onkeyup="searchTable()" 
                           placeholder="Type to search (Code, Name, Section, Instructor)...">
                </div>
                
                <form method="GET" class="filter-group" style="display:flex; flex-wrap: wrap; gap: 0.5rem;">
                    <div class="select-wrapper">
                        <select name="dept">
                            <option value="">All Departments</option>
                            <option value="BSIT" <?= $filter_dept === 'BSIT' ? 'selected' : '' ?>>BS Info. Tech</option>
                            <option value="BSDC" <?= $filter_dept === 'BSDC' ? 'selected' : '' ?>>BS Dev. Comm</option>
                            <option value="BLIS" <?= $filter_dept === 'BLIS' ? 'selected' : '' ?>>BL Info. Science</option>
                        </select>
                        <i class="fas fa-chevron-down chevron"></i>
                    </div>
                    <div class="select-wrapper">
                        <select name="year">
                            <option value="">All Years</option>
                            <option value="1" <?= $filter_year === '1' ? 'selected' : '' ?>>1st Year</option>
                            <option value="2" <?= $filter_year === '2' ? 'selected' : '' ?>>2nd Year</option>
                            <option value="3" <?= $filter_year === '3' ? 'selected' : '' ?>>3rd Year</option>
                            <option value="4" <?= $filter_year === '4' ? 'selected' : '' ?>>4th Year</option>
                        </select>
                        <i class="fas fa-chevron-down chevron"></i>
                    </div>
                    <div class="select-wrapper">
                        <select name="instructor">
                            <option value="">All Instructors</option>
                            <?php foreach($teachers as $t): ?>
                                <option value="<?= htmlspecialchars($t['first_name']) ?>" <?= $filter_inst === $t['first_name'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($t['first_name'] . ' ' . $t['last_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <i class="fas fa-chevron-down chevron"></i>
                    </div>
                    <button type="submit" class="btn-filter">Filter</button>
                </form>
            </div>

            <div class="data-card">
                <div class="table-responsive">
                    <table id="subjectsTable">
                        <thead>
                            <tr>
                                <th>Subject Code</th>
                                <th>Subject Name</th>
                                <th>Assigned Section</th>
                                <th>Instructor</th>
                                <th>Schedule</th>
                                <th>Room</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($subjects)): ?>
                                <tr><td colspan="7" style="text-align: center; padding: 2rem">No subjects found.</td></tr>
                            <?php else: ?>
                                <?php foreach ($subjects as $sub):
                                    $instructor_name = $sub['first_name'] ? htmlspecialchars($sub['first_name'] . ' ' . $sub['last_name']) : 'Unassigned';
                                    $instructor_img = $sub['profile_picture'] ?? '../../assets/img/profile-pictures/profile-staff.svg';
                                    $sub_json = htmlspecialchars(json_encode($sub), ENT_QUOTES, 'UTF-8');
                                ?>
                                <tr class="subject-row">
                                    <td><span class="code-badge"><?= htmlspecialchars($sub['subject_code']) ?></span></td>
                                    <td class="subject-title"><?= htmlspecialchars($sub['subject_description']) ?></td>
                                    <td class="section-cell"><?= htmlspecialchars($sub['section_assigned']) ?></td>
                                    <td>
                                        <div class="instructor-cell">
                                            <img src="<?= htmlspecialchars($instructor_img) ?>" alt="Instructor">
                                            <span class="instructor-name"><?= $instructor_name ?></span>
                                        </div>
                                    </td>
                                    <td style="font-size: 0.85rem; color: #555;"><?= htmlspecialchars($sub['schedule_day'] . ' ' . $sub['schedule_time']) ?></td>
                                    <td style="font-size: 0.85rem;"><?= htmlspecialchars($sub['room']) ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="icon-btn" title="View Details" onclick="openSubjectViewModal('<?= $sub_json ?>')"><i class="far fa-eye"></i></button>
                                            <button class="icon-btn edit" title="Edit" onclick="openSubjectEditModal('<?= $sub_json ?>')"><i class="fas fa-pen"></i></button>
                                            
                                            <form action="../../backend/controller.php?method_finder=delete_subject" method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this subject?');">
                                                <input type="hidden" name="subject_id" value="<?= $sub['subject_id'] ?>">
                                                <button class="icon-btn delete" title="Delete"><i class="fas fa-trash"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <div id="noResultsMsg" style="display:none; text-align:center; padding: 2rem; color: #888;">No subjects match your search.</div>
                </div>
            </div>
        </div>
    </div>

    <div id="subject-modal" class="modal-overlay" onclick="closeModals(event)">
        <div class="modal-box">
            <button class="close-btn" onclick="closeModals(event)">&times;</button>
            <div class="modal-header"><h3>Add New Subject</h3></div>
            <form action="../../backend/controller.php?method_finder=add_subject" method="POST">
                <div class="form-grid">
                    <div class="input-group"><label>Subject Code</label><input type="text" name="subject_code" placeholder="e.g. IT 111" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 0.5rem;"></div>
                    <div class="input-group"><label>Description</label><input type="text" name="subject_description" placeholder="e.g. Intro to Computing" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 0.5rem;"></div>
                    <div class="input-group"><label>Assigned Section</label><select name="section_assigned" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 0.5rem;"><option value="" disabled selected>Select Section</option><optgroup label="BS Info Technology"><option value="BSIT 1A">BSIT 1A</option><option value="BSIT 1B">BSIT 1B</option><option value="BSIT 2A">BSIT 2A</option><option value="BSIT 2B">BSIT 2B</option><option value="BSIT 3A">BSIT 3A</option><option value="BSIT 3B">BSIT 3B</option><option value="BSIT 4A">BSIT 4A</option><option value="BSIT 4B">BSIT 4B</option></optgroup><optgroup label="BS Dev Communication"><option value="BSDC 1A">BSDC 1A</option><option value="BSDC 1B">BSDC 1B</option><option value="BSDC 2A">BSDC 2A</option><option value="BSDC 2B">BSDC 2B</option><option value="BSDC 3A">BSDC 3A</option><option value="BSDC 3B">BSDC 3B</option><option value="BSDC 4A">BSDC 4A</option><option value="BSDC 4B">BSDC 4B</option></optgroup><optgroup label="BL Info Science"><option value="BLIS 1A">BLIS 1A</option><option value="BLIS 1B">BLIS 1B</option><option value="BLIS 2A">BLIS 2A</option><option value="BLIS 2B">BLIS 2B</option><option value="BLIS 3A">BLIS 3A</option><option value="BLIS 3B">BLIS 3B</option><option value="BLIS 4A">BLIS 4A</option><option value="BLIS 4B">BLIS 4B</option></optgroup></select></div>
                    <div class="input-group"><label>Units</label><input type="number" name="units" value="3" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 0.5rem;"></div>
                    <div class="input-group"><label>Instructor</label><select name="instructor_id" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 0.5rem;"><option value="" disabled selected>Select Instructor</option><?php foreach($teachers as $teacher): ?><option value="<?= $teacher['user_id'] ?>"><?= htmlspecialchars($teacher['first_name'] . ' ' . $teacher['last_name']) ?></option><?php endforeach; ?></select></div>
                    <div class="input-group"><label>Schedule (Days)</label><select name="schedule_day" style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 0.5rem;"><option value="" disabled selected>Select Days</option><option value="Mon">Mon</option><option value="Tue">Tue</option><option value="Wed">Wed</option><option value="Thu">Thu</option><option value="Fri">Fri</option><option value="Sat">Sat</option><optgroup label="Combinations"><option value="Mon/Wed">Mon/Wed</option><option value="Tue/Thu">Tue/Thu</option><option value="Fri/Sat">Fri/Sat</option></optgroup></select></div>
                    <div class="input-group"><label>Schedule (Time)</label><input type="text" name="schedule_time" placeholder="e.g. 9:00 AM - 10:30 AM" style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 0.5rem;"></div>
                    <div class="input-group"><label>Room</label><select name="room" style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 0.5rem;"><option value="" disabled selected>Select Room</option><optgroup label="Lecture Rooms"><option value="CIS 301">CIS 301</option><option value="CIS 302">CIS 302</option><option value="CIS 303">CIS 303</option><option value="CIS 304">CIS 304</option><option value="CIS 305">CIS 305</option></optgroup><optgroup label="Laboratories"><option value="CIS Lab 1">CIS Lab 1 (Comp)</option><option value="CIS Lab 2">CIS Lab 2 (Comp)</option><option value="CIS Lab 3">CIS Lab 3 (Comp)</option><option value="CIS Lab 4">CIS Lab 4 (Mac)</option><option value="CIS NetLab">CIS NetLab</option></optgroup><optgroup label="Others"><option value="CIS Conf Room">CIS Conf Room</option><option value="AVR">AVR</option><option value="DevCom Lab">DevCom Lab</option><option value="Radio Station">Radio Station</option><option value="Library Conf A">Library Conf A</option><option value="CAS 204">CAS 204</option><option value="CAS 205">CAS 205</option></optgroup></select></div>
                </div>
                <button type="submit" class="btn-primary" style="width: 100%; margin-top: 1rem;">Create Subject</button>
            </form>
        </div>
    </div>

    <div id="view-subject-modal" class="modal-overlay" onclick="closeModals(event)">
        <div class="modal-box">
            <button class="close-btn" onclick="closeModals(event)">&times;</button>
            <div class="modal-header"><h3>Subject Details</h3></div>
            <div class="view-content" style="display: flex; flex-direction: column; gap: 1rem;">
                <div style="background: #f9fafb; padding: 1rem; border-radius: 0.5rem; border: 1px solid #eee;">
                    <h2 id="view-code" style="color: var(--primary-color); margin-bottom: 0.2rem;"></h2>
                    <p id="view-desc" style="font-size: 1.1rem; font-weight: 600;"></p>
                    <span id="view-units" class="badge-soft purple" style="margin-top:0.5rem; display:inline-block;"></span>
                </div>
                <div class="form-grid">
                    <div class="input-group"><label>Assigned Section</label><p id="view-section" style="font-weight: 500;"></p></div>
                    <div class="input-group"><label>Room</label><p id="view-room" style="font-weight: 500;"></p></div>
                    <div class="input-group"><label>Days</label><p id="view-day" style="font-weight: 500;"></p></div>
                    <div class="input-group"><label>Time</label><p id="view-time" style="font-weight: 500;"></p></div>
                </div>
            </div>
        </div>
    </div>

    <div id="edit-modal" class="modal-overlay" onclick="closeModals(event)">
        <div class="modal-box">
            <button class="close-btn" onclick="closeModals(event)">&times;</button>
            <div class="modal-header"><h3>Edit Subject</h3></div>
            <form action="../../backend/controller.php?method_finder=update_subject" method="POST">
                <input type="hidden" name="subject_id" id="edit-id">
                <div class="form-grid">
                    <div class="input-group"><label>Subject Code</label><input type="text" name="subject_code" id="edit-code" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 0.5rem;"></div>
                    <div class="input-group"><label>Description</label><input type="text" name="subject_description" id="edit-desc" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 0.5rem;"></div>
                    <div class="input-group"><label>Assigned Section</label><select name="section_assigned" id="edit-section" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 0.5rem;"><option value="" disabled>Select Section</option><optgroup label="BS Info Technology"><option value="BSIT 1A">BSIT 1A</option><option value="BSIT 1B">BSIT 1B</option><option value="BSIT 2A">BSIT 2A</option><option value="BSIT 2B">BSIT 2B</option><option value="BSIT 3A">BSIT 3A</option><option value="BSIT 3B">BSIT 3B</option><option value="BSIT 4A">BSIT 4A</option><option value="BSIT 4B">BSIT 4B</option></optgroup><optgroup label="BS Dev Communication"><option value="BSDC 1A">BSDC 1A</option><option value="BSDC 1B">BSDC 1B</option><option value="BSDC 2A">BSDC 2A</option><option value="BSDC 2B">BSDC 2B</option><option value="BSDC 3A">BSDC 3A</option><option value="BSDC 3B">BSDC 3B</option><option value="BSDC 4A">BSDC 4A</option><option value="BSDC 4B">BSDC 4B</option></optgroup><optgroup label="BL Info Science"><option value="BLIS 1A">BLIS 1A</option><option value="BLIS 1B">BLIS 1B</option><option value="BLIS 2A">BLIS 2A</option><option value="BLIS 2B">BLIS 2B</option><option value="BLIS 3A">BLIS 3A</option><option value="BLIS 3B">BLIS 3B</option><option value="BLIS 4A">BLIS 4A</option><option value="BLIS 4B">BLIS 4B</option></optgroup></select></div>
                    <div class="input-group"><label>Units</label><input type="number" name="units" id="edit-units" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 0.5rem;"></div>
                    <div class="input-group"><label>Instructor</label><select name="instructor_id" id="edit-instructor" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 0.5rem;"><option value="" disabled>Select Instructor</option><?php foreach($teachers as $teacher): ?><option value="<?= $teacher['user_id'] ?>"><?= htmlspecialchars($teacher['first_name'] . ' ' . $teacher['last_name']) ?></option><?php endforeach; ?></select></div>
                    <div class="input-group"><label>Schedule (Days)</label><select name="schedule_day" id="edit-day" style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 0.5rem;"><option value="" disabled>Select Days</option><option value="Mon">Mon</option><option value="Tue">Tue</option><option value="Wed">Wed</option><option value="Thu">Thu</option><option value="Fri">Fri</option><option value="Sat">Sat</option><optgroup label="Combinations"><option value="Mon/Wed">Mon/Wed</option><option value="Tue/Thu">Tue/Thu</option><option value="Fri/Sat">Fri/Sat</option></optgroup></select></div>
                    <div class="input-group"><label>Schedule (Time)</label><input type="text" name="schedule_time" id="edit-time" style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 0.5rem;"></div>
                    <div class="input-group"><label>Room</label><select name="room" id="edit-room" style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 0.5rem;"><option value="" disabled>Select Room</option><optgroup label="Lecture Rooms"><option value="CIS 301">CIS 301</option><option value="CIS 302">CIS 302</option><option value="CIS 303">CIS 303</option><option value="CIS 304">CIS 304</option><option value="CIS 305">CIS 305</option></optgroup><optgroup label="Laboratories"><option value="CIS Lab 1">CIS Lab 1 (Comp)</option><option value="CIS Lab 2">CIS Lab 2 (Comp)</option><option value="CIS Lab 3">CIS Lab 3 (Comp)</option><option value="CIS Lab 4">CIS Lab 4 (Mac)</option><option value="CIS NetLab">CIS NetLab</option></optgroup><optgroup label="Others"><option value="CIS Conf Room">CIS Conf Room</option><option value="AVR">AVR</option><option value="DevCom Lab">DevCom Lab</option><option value="Radio Station">Radio Station</option><option value="Library Conf A">Library Conf A</option><option value="CAS 204">CAS 204</option><option value="CAS 205">CAS 205</option></optgroup></select></div>
                </div>
                <button type="submit" class="btn-primary" style="width: 100%; margin-top: 1rem;">Save Changes</button>
            </form>
        </div>
    </div>
</body>
</html>