<?php
    session_start();
    require_once '../../backend/controller.php';
    require_once '../../backend/algorithms/MergeSort.php';

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'teacher') {
            header("Location: staff-dashboard.php");
        } else {
            header("Location: ../auth/login.php");
        }
        exit();
    }

    $controller = new controller();

    $students = $controller->get_users_by_role('student', 'active');
    $teachers = $controller->get_users_by_role('teacher', 'active');
    $admins = $controller->get_users_by_role('admin', 'active');

    $pending_stu = $controller->get_users_by_role('student', 'pending');
    $pending_tch = $controller->get_users_by_role('teacher', 'pending');
    $pending_adm = $controller->get_users_by_role('admin', 'pending');
    $all_pending = array_merge($pending_stu, $pending_tch, $pending_adm);

    $inactive_stu = $controller->get_users_by_role('student', 'inactive');
    $inactive_tch = $controller->get_users_by_role('teacher', 'inactive');
    $inactive_adm = $controller->get_users_by_role('admin', 'inactive');
    $all_inactive = array_merge($inactive_stu, $inactive_tch, $inactive_adm);

    $sections_list = [];
    $courses = ['BSIT', 'BSDC', 'BLIS'];
    foreach ($courses as $course) {
        for ($i = 1; $i <= 4; $i++) {
            $sections_list[$course][] = "$course {$i}A";
            $sections_list[$course][] = "$course {$i}B"; 
        }
    }

    $sorter = new MergeSort();
    $sort_by = $_GET['sort'] ?? 'name'; 
    $sort_key = ($sort_by === 'date') ? 'created_at' : 'last_name';

    $students = $sorter->sort($students, $sort_key);
    $teachers = $sorter->sort($teachers, $sort_key);
    $admins = $sorter->sort($admins, $sort_key);
    $all_pending = $sorter->sort($all_pending, $sort_key);
    $all_inactive = $sorter->sort($all_inactive, $sort_key);

    $count_stu = count($students);
    $count_tch = count($teachers);
    $count_adm = count($admins);
    $count_pen = count($all_pending);
    $count_ina = count($all_inactive);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../../assets/img/logo/logo.ico">
    <link rel="stylesheet" href="../../assets/css/management-common.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="../../assets/js/main.js" defer></script>
    <script src="../../assets/js/modal.js" defer></script>
    <title>User Management</title>
</head>
<body>
    <?php require_once '../../components/header.php'?>

    <div class="container">
        <?php require_once '../../components/sidebar.php'?>

        <div class="content">            
            
            <div class="section-header">
                <div class="header-details">
                    <h1>User Management</h1>
                    <h3>Manage students, instructors, and admins.</h3>
                </div>
                <div class="header-actions">
                    <a href="../../backend/controller.php?method_finder=export_users" class="btn-secondary" style="text-decoration:none;">
                        <i class="fas fa-file-export"></i> Export
                    </a>
                    <button class="btn-primary" onclick="openUserModal()">
                        <i class="fas fa-plus"></i> Add New User
                    </button>
                </div>
            </div>

            <?php if(isset($_GET['success'])): ?>
                <div class="alert alert-success">
                    <img src="../../assets/img/icons/success-icon.svg" alt="Success">
                    <span><?= htmlspecialchars($_GET['success']) ?></span>
                </div>
            <?php endif; ?>

            <?php if(isset($_GET['error'])): ?>
                <div class="alert alert-error">
                    <img src="../../assets/img/icons/error-icon.svg" alt="Error">
                    <span><?= htmlspecialchars($_GET['error']) ?></span>
                </div>
            <?php endif; ?>

            <div class="toolbar" style="align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
                <div class="search-wrapper" style="flex: 1 1 300px;">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="realTimeSearch" onkeyup="searchAllTables()" 
                           placeholder="Search by name, ID, or email...">
                </div>
                
                <form method="GET" class="filter-group" style="display:flex; gap:0.5rem;">
                    <div class="select-wrapper">
                        <select name="sort" onchange="this.form.submit()">
                            <option value="name" <?= $sort_by === 'name' ? 'selected' : '' ?>>Sort by Name (A-Z)</option>
                            <option value="date" <?= $sort_by === 'date' ? 'selected' : '' ?>>Sort by Date Added</option>
                        </select>
                        <i class="fas fa-chevron-down chevron"></i>
                    </div>
                </form>
            </div>

            <div class="data-card">
                <input type="radio" id="tab-students" name="user-tabs" checked hidden>
                <input type="radio" id="tab-instructors" name="user-tabs" hidden>
                <input type="radio" id="tab-admins" name="user-tabs" hidden>
                <input type="radio" id="tab-pending" name="user-tabs" hidden>
                <input type="radio" id="tab-inactive" name="user-tabs" hidden>

                <div class="tabs-header">
                    <label for="tab-students" class="tab-item" onclick="saveTab('tab-students')">Students <span class="badge"><?= $count_stu ?></span></label>
                    <label for="tab-instructors" class="tab-item" onclick="saveTab('tab-instructors')">Instructors <span class="badge"><?= $count_tch ?></span></label>
                    <label for="tab-admins" class="tab-item" onclick="saveTab('tab-admins')">Admins <span class="badge"><?= $count_adm ?></span></label>
                    <?php $pen_class = ($count_pen > 0) ? 'warning' : '' ?>
                    <label for="tab-pending" class="tab-item" onclick="saveTab('tab-pending')">Pending <span class="badge <?= $pen_class ?>"><?= $count_pen ?></span></label>
                    <label for="tab-inactive" class="tab-item" onclick="saveTab('tab-inactive')">Inactive <span class="badge"><?= $count_ina ?></span></label>
                </div>

                <div class="tab-content-wrapper">
                    
                    <div id="view-students" class="tab-view fadeIn">
                        <?php if ($count_stu > 0):?>
                        <div class="table-responsive">
                            <table class="searchable-table">
                                <thead>
                                    <tr>
                                        <th>User Profile</th>
                                        <th>Student ID</th>
                                        <th>Email Address</th>
                                        <th>Section</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($students as $stu):
                                        $name = htmlspecialchars($stu['first_name'] . ' ' . $stu['last_name']);
                                        $pic = htmlspecialchars($stu['profile_picture'] ?? '../../assets/img/profile-pictures/profile.svg');
                                        $info = htmlspecialchars($stu['course'] . ' ' . $stu['year_level']);
                                        $stu_data = ['id' => $stu['user_id'], 'name' => $name, 'section' => $stu['section']];
                                        $stu_json = htmlspecialchars(json_encode($stu_data), ENT_QUOTES, 'UTF-8');
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="user-cell">
                                                <img src="<?= $pic ?>" alt="User">
                                                <div>
                                                    <p class="name"><?= $name ?></p>
                                                    <p class="sub-text"><?= $info ?></p>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?= htmlspecialchars($stu['unique_id']) ?></td>
                                        <td><?= htmlspecialchars($stu['email']) ?></td>
                                        <td><?= htmlspecialchars($stu['section']) ?></td>
                                        <td><span class="status-pill active">Active</span></td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="icon-btn edit" title="Edit Section" onclick="openEditStudentModal('<?= $stu_json ?>')">
                                                    <i class="fas fa-pen"></i>
                                                </button>
                                                <form action="../../backend/controller.php?method_finder=manage_users" method="POST" style="display: inline;">
                                                    <input type="hidden" name="sub_action" value="deactivate">
                                                    <input type="hidden" name="target_id" value="<?= $stu['user_id'] ?>">
                                                    <button type="submit" class="icon-btn delete" title="Deactivate" onclick="return confirm('Deactivate this student?');">
                                                        <i class="fas fa-ban"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                            <div class="empty-state"><p>No active students found.</p></div>
                        <?php endif; ?>
                    </div>

                    <div id="view-instructors" class="tab-view fadeIn">
                        <?php if ($count_tch > 0): ?>
                        <div class="table-responsive">
                            <table class="searchable-table">
                                <thead><tr><th>Profile</th><th>ID</th><th>Email</th><th>Dept</th><th>Rank</th><th>Actions</th></tr></thead>
                                <tbody>
                                    <?php foreach($teachers as $tch): ?>
                                    <tr>
                                        <td><div class="user-cell"><img src="<?= htmlspecialchars($tch['profile_picture']) ?>" alt=""><p class="name"><?= htmlspecialchars($tch['first_name'].' '.$tch['last_name']) ?></p></div></td>
                                        <td><?= htmlspecialchars($tch['unique_id']) ?></td>
                                        <td><?= htmlspecialchars($tch['email']) ?></td>
                                        <td><?= htmlspecialchars($tch['department']) ?></td>
                                        <td><?= htmlspecialchars($tch['academic_rank']) ?></td>
                                        <td>
                                            <form action="../../backend/controller.php?method_finder=manage_users" method="POST" style="display:inline;">
                                                <input type="hidden" name="sub_action" value="deactivate"><input type="hidden" name="target_id" value="<?= $tch['user_id'] ?>">
                                                <button class="icon-btn delete" onclick="return confirm('Deactivate?')"><i class="fas fa-ban"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?><div class="empty-state"><p>No active teachers.</p></div><?php endif; ?>
                    </div>

                    <div id="view-admins" class="tab-view fadeIn">
                         <?php if ($count_adm > 0): ?>
                            <div class="table-responsive">
                                <table class="searchable-table">
                                    <thead><tr><th>Profile</th><th>ID</th><th>Dept</th><th>Role</th><th>Actions</th></tr></thead>
                                    <tbody>
                                        <?php foreach($admins as $adm): ?>
                                        <tr>
                                            <td><div class="user-cell"><img src="<?= htmlspecialchars($adm['profile_picture']) ?>" alt=""><p class="name"><?= htmlspecialchars($adm['first_name'].' '.$adm['last_name']) ?></p></div></td>
                                            <td><?= htmlspecialchars($adm['unique_id']) ?></td>
                                            <td><?= htmlspecialchars($adm['department']) ?></td>
                                            <td><?= htmlspecialchars($adm['academic_rank']) ?></td>
                                            <td>
                                                <?php if ($adm['user_id'] == $_SESSION['user_id']): ?><span style="font-size:0.8rem;color:#aaa">[You]</span><?php else: ?>
                                                <form action="../../backend/controller.php?method_finder=manage_users" method="POST" style="display:inline;">
                                                    <input type="hidden" name="sub_action" value="deactivate"><input type="hidden" name="target_id" value="<?= $adm['user_id'] ?>">
                                                    <button class="icon-btn delete" onclick="return confirm('Deactivate?')"><i class="fas fa-ban"></i></button>
                                                </form>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?><div class="empty-state"><p>No active admins.</p></div><?php endif; ?>
                    </div>

                    <div id="view-pending" class="tab-view fadeIn">
                         <?php if ($count_pen > 0): ?>
                            <div class="table-responsive">
                                <table class="searchable-table">
                                    <thead><tr><th>Applicant</th><th>Role</th><th>Details</th><th>Actions</th></tr></thead>
                                    <tbody>
                                        <?php foreach($all_pending as $pen): ?>
                                        <tr>
                                            <td><div class="user-cell"><p class="name"><?= htmlspecialchars($pen['first_name'].' '.$pen['last_name']) ?></p></div></td>
                                            <td><?= ucfirst($pen['role']) ?></td>
                                            <td><?= ($pen['role']=='student') ? $pen['course'] : $pen['department'] ?></td>
                                            <td>
                                                <div class="action-buttons">
                                                    <form action="../../backend/controller.php?method_finder=manage_users" method="POST"><input type="hidden" name="sub_action" value="approve"><input type="hidden" name="target_id" value="<?= $pen['user_id'] ?>"><button class="btn-primary small">Approve</button></form>
                                                    <form action="../../backend/controller.php?method_finder=manage_users" method="POST"><input type="hidden" name="sub_action" value="delete"><input type="hidden" name="target_id" value="<?= $pen['user_id'] ?>"><button class="icon-btn delete"><i class="fas fa-times"></i></button></form>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?><div class="empty-state"><p>No pending apps.</p></div><?php endif; ?>
                    </div>
                    
                    <div id="view-inactive" class="tab-view fadeIn">
                        <?php if ($count_ina > 0): ?>
                            <div class="table-responsive">
                                <table class="searchable-table">
                                    <thead><tr><th>Profile</th><th>Role</th><th>Status</th><th>Action</th></tr></thead>
                                    <tbody>
                                        <?php foreach($all_inactive as $ina): ?>
                                        <tr>
                                            <td><div class="user-cell"><img src="<?= ($ina['role']=='student')?'../../assets/img/profile-pictures/profile.svg':'../../assets/img/profile-pictures/profile-staff.svg' ?>" alt=""><p class="name"><?= htmlspecialchars($ina['first_name'].' '.$ina['last_name']) ?></p></div></td>
                                            <td><?= ucfirst($ina['role']) ?></td>
                                            <td><span class="status-pill inactive">Inactive</span></td>
                                            <td>
                                                <form action="../../backend/controller.php?method_finder=manage_users" method="POST"><input type="hidden" name="sub_action" value="reactivate"><input type="hidden" name="target_id" value="<?= $ina['user_id'] ?>"><button class="icon-btn edit"><i class="fas fa-undo"></i></button></form>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?><div class="empty-state"><p>No inactive users.</p></div><?php endif; ?>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <div id="user-modal" class="modal-overlay" onclick="closeModals(event)">
        <div class="modal-box" style="max-width: 650px;">
            <button class="close-btn" onclick="closeModals(event)">&times;</button>
            <div class="modal-header"><h3>Register New User</h3></div>
            
            <form action="../../backend/controller.php?method_finder=create_user" method="POST">
                <div class="form-grid single-col" style="margin-bottom: 1rem;">
                    <div class="input-group">
                        <label>Role</label>
                        <select name="role" id="roleSelect" onchange="toggleUserFields()" required style="width:100%;padding:0.8rem;border-radius:0.5rem;border:1px solid #ddd;">
                            <option value="student">Student</option>
                            <option value="teacher">Instructor</option>
                            <option value="admin">Administrator</option>
                        </select>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="input-group">
                        <label>First Name</label>
                        <input type="text" name="first_name" required style="width:100%;padding:0.8rem;border-radius:0.5rem;border:1px solid #ddd;">
                    </div>
                    <div class="input-group">
                        <label>Last Name</label>
                        <input type="text" name="last_name" required style="width:100%;padding:0.8rem;border-radius:0.5rem;border:1px solid #ddd;">
                    </div>
                </div>

                <div class="form-grid">
                    <div class="input-group">
                        <label>Email Address</label>
                        <input type="email" name="email" required style="width:100%;padding:0.8rem;border-radius:0.5rem;border:1px solid #ddd;">
                    </div>
                    <div class="input-group">
                        <label>Temp Password</label>
                        <input type="text" name="password" value="ChangeMe123!" required style="width:100%;padding:0.8rem;border-radius:0.5rem;border:1px solid #ddd;">
                    </div>
                </div>

                <div id="student-fields">
                    <div class="form-grid">
                        <div class="input-group"><label>Course</label><select name="course" style="width:100%;padding:0.8rem;border-radius:0.5rem;border:1px solid #ddd;"><option value="BSIT">BS Info Tech</option><option value="BSDC">BS Dev Comm</option><option value="BLIS">BL Info Science</option></select></div>
                        <div class="input-group"><label>Year</label><select name="year_level" style="width:100%;padding:0.8rem;border-radius:0.5rem;border:1px solid #ddd;"><option value="1">1st</option><option value="2">2nd</option><option value="3">3rd</option><option value="4">4th</option></select></div>
                    </div>
                </div>

                <div id="staff-fields" style="display:none;">
                    <div class="form-grid">
                        <div class="input-group"><label>Dept</label><select name="department" style="width:100%;padding:0.8rem;border-radius:0.5rem;border:1px solid #ddd;"><option value="DIT">DIT</option><option value="DDC">DDC</option><option value="DLS">DLS</option><option value="Administration">Administration</option></select></div>
                        <div class="input-group"><label>Rank</label><select name="academic_rank" style="width:100%;padding:0.8rem;border-radius:0.5rem;border:1px solid #ddd;"><option value="Instructor I">Instructor I</option><option value="Asst. Prof">Asst. Prof</option><option value="Professor">Professor</option><option value="System Admin">System Admin</option></select></div>
                    </div>
                </div>

                <button type="submit" class="btn-primary" style="width:100%; margin-top:1rem;">Create User</button>
            </form>
        </div>
    </div>

    <div id="edit-student-modal" class="modal-overlay" onclick="closeModals(event)">
        <div class="modal-box" style="max-width: 500px;">
            <button class="close-btn" onclick="closeModals(event)">&times;</button>
            <div class="modal-header"><h3>Edit Section</h3></div>
            
            <form action="../../backend/controller.php?method_finder=update_student_section" method="POST">
                <input type="hidden" name="user_id" id="edit-user-id">
                
                <div class="form-grid single-col">
                    <div class="input-group">
                        <label>Student Name</label>
                        <input type="text" id="edit-student-name" disabled 
                               style="width: 100%; padding: 0.8rem; background: #f3f4f6; border: 1px solid #ddd; border-radius: 0.5rem;">
                    </div>
                    <div class="input-group">
                        <label>Assign Section</label>
                        <select name="section" id="edit-student-section" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 0.5rem;">
                            <option value="N/A">N/A (Irregular/New)</option>
                            <?php foreach ($sections_list as $course_name => $secs): ?>
                                <optgroup label="<?= $course_name ?>">
                                    <?php foreach ($secs as $sec): ?>
                                        <option value="<?= $sec ?>"><?= $sec ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <button type="submit" class="btn-primary" style="width: 100%; margin-top: 1rem;">Update Section</button>
            </form>
        </div>
    </div>
</body>
</html>