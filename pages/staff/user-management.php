<?php
    session_start();
    require_once '../../backend/controller.php';

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'teacher') {
            header("Location: staff-dashboard.php");
        } else {
            header("Location: ../auth/login.php");
        }

        exit();
    }

    if (!isset($_SESSION['profile_data'])) {
        session_destroy();
        header("Location: ../auth/login.php?error=Session expired. Please login again");
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
                    <button class="btn-secondary">
                        <i class="fas fa-file-export"></i> Export
                    </button>
                    <button class="btn-primary">
                        <i class="fas fa-plus"></i> Add New User
                    </button>
                </div>
            </div>

            <?php if(isset($_GET['success'])): ?>
                <div class="alert alert-success" style="margin-top: 1rem;">
                    <img src="../../assets/img/icons/success-icon.svg" alt="Success">
                    <span style="display: flex; flex-direction: row;"><?= htmlspecialchars($_GET['success']) ?></span>
                </div>
            <?php endif; ?>

            
            <div class="toolbar">
                <div class="search-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" placeholder="Search by name, ID, or email...">
                </div>
                
                <div class="filter-group">
                    <div class="select-wrapper">
                        <select>
                            <option value="" disabled selected>Status</option>
                            <option>Active</option>
                            <option>On Leave</option>
                            <option>Inactive</option>
                        </select>
                        <i class="fas fa-chevron-down chevron"></i>
                    </div>
                    <div class="select-wrapper">
                        <select>
                            <option value="" disabled selected>Sort By</option>
                            <option>Name (A-Z)</option>
                            <option>Date Added</option>
                        </select>
                        <i class="fas fa-chevron-down chevron"></i>
                    </div>
                    <button class="btn-filter">
                        Filter
                    </button>
                </div>
            </div>

            <div class="data-card">
                <input type="radio" id="tab-students" name="user-tabs" checked hidden>
                <input type="radio" id="tab-instructors" name="user-tabs" hidden>
                <input type="radio" id="tab-admins" name="user-tabs" hidden>
                <input type="radio" id="tab-pending" name="user-tabs" hidden>
                <input type="radio" id="tab-inactive" name="user-tabs" hidden>

                <div class="tabs-header">
                    <label for="tab-students" class="tab-item">Students <span class="badge"><?= $count_stu ?></span></label>
                    <label for="tab-instructors" class="tab-item">Instructors <span class="badge"><?= $count_tch ?></span></label>
                    <label for="tab-admins" class="tab-item">Admins <span class="badge"><?= $count_adm ?></span></label>
                    
                    <?php $pen_class = ($count_pen > 0) ? 'warning' : '' ?>

                    <label for="tab-pending" class="tab-item">Pending <span class="badge <?= $pen_class ?>"><?= $count_pen ?></span></label>
                    <label for="tab-inactive" class="tab-item">Inactive <span class="badge"><?= $count_ina ?></span></label>
                </div>

                <div class="tab-content-wrapper">
                    
                    <div id="view-students" class="tab-view fadeIn">
                        <?php if ($count_stu > 0):?>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>User Profile</th>
                                        <th>Student ID</th>
                                        <th>Email Address</th>
                                        <th>Section</th>
                                        <th>Enrolled</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($students as $stu):
                                        $name = htmlspecialchars($stu['first_name'] . ' ' . $stu['last_name']);
                                        $pic = htmlspecialchars($stu['profile_picture'] ?? '../../assets/img/profile-pictures/profile.svg');
                                        if ($stu['year_level'] == 1) {
                                            $year_level = "1st";
                                        } else if ($stu['year_level'] == 2) {
                                            $year_level = "2nd";
                                        } else if ($stu['year_level'] == 3) {
                                            $year_level = "3rd";
                                        } else if ($stu['year_level'] == 4) {
                                            $year_level = "4th";
                                        } else {
                                            $year_level = $stu['year_level'];
                                        }
                                        $info = htmlspecialchars($stu['course'] . ' ' . $year_level . ' Year');
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
                                        <td>4 Subjects</td>
                                        <td><span class="status-pill active">Active</span></td>
                                        <td>
                                            <form action="../../backend/controller.php?method_finder=manage_users" method="POST" style="display: inline;">
                                                <input type="hidden" name="sub_action" value="deactivate">
                                                <input type="hidden" name="target_id" value="<?= $stu['user_id'] ?>">
                                                <button type="submit" class="icon-btn delete" title="Deactivate User" onclick="return confirm('Deactivate this student?');">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <p>No active students found.</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div id="view-instructors" class="tab-view fadeIn">
                        <?php if ($count_tch > 0): ?>
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Profile</th>
                                        <th>Employee ID</th>
                                        <th>Email Address</th>
                                        <th>Department</th>
                                        <th>Rank</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($teachers as $tch):
                                        $name = htmlspecialchars($tch['first_name'] . ' ' . $tch['last_name']);
                                        $pic = htmlspecialchars($tch['profile_picture'] ?? '../../assets/img/profile-pictures/profile-staff.svg');    
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="user-cell">
                                                <img src="<?= $pic ?>" alt="User">
                                                <p class="name"><?= $name ?></p>
                                            </div>
                                        </td>
                                        <td><?= htmlspecialchars($tch['unique_id']) ?></td>
                                        <td><?= htmlspecialchars($tch['email']) ?></td>
                                        <td><?= htmlspecialchars($tch['department']) ?></td>
                                        <td><?= htmlspecialchars($tch['academic_rank']) ?></td>
                                        <td><span class="status-pill active">Active</span></td>
                                        <td>
                                            <form action="../../backend/controller.php?method_finder=manage_users" method="POST" style="display: inline;">
                                                <input type="hidden" name="sub_action" value="deactivate">
                                                <input type="hidden" name="target_id" value="<?= $tch['user_id'] ?>">
                                                <button type="submit" class="icon-btn delete" title="Deactivate" onclick="return confirm('Deactivate this teacher?')">
                                                    <i class="fas fa-ban"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <p>No active teachers found.</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div id="view-admins" class="tab-view fadeIn">
                        <?php if ($count_adm > 0): ?>
                            <div class="table-responsive">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Profile</th>
                                            <th>Admin ID</th>
                                            <th>Department</th>
                                            <th>Role</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($admins as $adm):
                                            $name = htmlspecialchars($adm['first_name'] . ' ' . $adm['last_name']);
                                            $pic = htmlspecialchars($adm['profile_picture'] ?? '../../assets/img/profile-pictures/profile-staff.svg');

                                            $rank = $adm['academic_rank'];
                                            $is_vip = ($rank === 'Head Administrator');
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="user-cell">
                                                    <?php if ($is_vip): ?>
                                                        <div class="avatar-wrapper exclusive-admin table-size">
                                                            <img src="<?= $pic ?>" alt="User" class="avatar">
                                                            <i class="fas fa-crown crown-badge"></i>
                                                        </div>
                                                    <?php else: ?>
                                                        <img src="<?= $pic ?>" alt="User">
                                                    <?php endif; ?>

                                                    <p class="name"><?= $name ?></p>
                                                </div>
                                            </td>
                                            <td><?= htmlspecialchars($adm['unique_id']) ?></td>
                                            <td><?= htmlspecialchars($adm['department']) ?></td>
                                            <td><?= htmlspecialchars($adm['academic_rank']) ?></td>
                                            <td><span class="status-pill active">Active</span></td>
                                            <td>
                                                <?php if ($adm['user_id'] == $_SESSION['user_id']): ?>
                                                    <span style="font-size: 0.8rem; color: #aaa">[You]</span>
                                                <?php else: ?>
                                                    <form action="../../backend/controller.php?method_finder=manage_users" method="POST" style="display: inline;">
                                                        <input type="hidden" name="sub_action" value="deactivate">
                                                        <input type="hidden" name="target_id" value="<?= $tch['user_id'] ?>">
                                                        <button type="submit" class="icon-btn delete" title="Deactivate" onclick="return confirm('Deactivate this admin?')">
                                                            <i class="fas fa-ban"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>    
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <p>No active admin found.</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div id="view-pending" class="tab-view fadeIn">
                        <?php if ($count_pen > 0): ?>
                            <div class="table-responsive">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Applicant</th>
                                            <th>Role</th>
                                            <th>Details</th>
                                            <th>Date Requested</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($all_pending as $pen):
                                            $name = htmlspecialchars($pen['first_name'] . ' ' . $pen['last_name']);

                                            if ($pen['role'] === 'student') {
                                                $detail = $pen['course'] . ' - ' . $pen['year_level'];
                                                $role_badge = 'badge-soft blue';
                                            } else {
                                                $detail = $pen['department'] . ' - ' . $pen['academic_rank'];
                                                $role_badge = 'badge-soft purple';
                                            }
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="user-cell">
                                                    <p class="name"><?= $name ?></p>
                                                </div>
                                            </td>
                                            <td><span class="<?= $role_badge ?>"><?= ucfirst($pen['role']) ?></span></td>
                                            <td><?= htmlspecialchars($detail) ?></td>
                                            <td>Today</td>
                                            <td>
                                                <div class="action-buttons">
                                                    <form action="../../backend/controller.php?method_finder=manage_users" method="POST">
                                                        <input type="hidden" name="sub_action" value="approve">
                                                        <input type="hidden" name="target_id" value="<?= $pen['user_id'] ?>">
                                                        <button type="submit" class="btn-primary small" style="padding: 0.4rem 0.8rem; font-size: 0.8rem;">
                                                            Approve
                                                        </button>
                                                    </form>

                                                    <form action="../../backend/controller.php?method_finder=manage_users" method="POST">
                                                        <input type="hidden" name="sub_action" value="delete">
                                                        <input type="hidden" name="target_id" value="<?= $pen['user_id'] ?>">
                                                        <button type="submit" class="icon-btn delete" title="Reject" onclick="return confirm('Reject application?')">
                                                            <div class="fas fa-times"></div>
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
                            <div class="empty-state">
                                <p>No pending user found.</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div id="view-inactive" class="tab-view fadeIn">
                        <?php if ($count_ina > 0): ?>
                            <div class="table-responsive">
                                <table>
                                    <thead>
                                        <tr>
                                            <th>User Profile</th>
                                            <th>Role</th>
                                            <th>Email Address</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($all_inactive as $ina):
                                            $name = htmlspecialchars($ina['first_name'] . ' ' . $ina['last_name']);

                                            if ($ina['role'] === 'student') {
                                                $pic = htmlspecialchars($adm['profile_picture'] ?? '../../assets/img/profile-pictures/profile.svg');
                                                $role_badge = 'badge-soft blue';
                                            } else if ($ina['role'] === 'teacher') {
                                                $pic = htmlspecialchars($adm['profile_picture'] ?? '../../assets/img/profile-pictures/profile-staff.svg');
                                                $role_badge = 'badge-soft purple';
                                            } else {
                                                $pic = htmlspecialchars($adm['profile_picture'] ?? '../../assets/img/profile-pictures/profile-staff.svg');
                                                $role_badge = 'badge-soft yellow';
                                            }
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="user-cell">
                                                    <img src="<?= $pic ?>" alt="User">
                                                    <div>
                                                        <p class="name"><?= $name ?></p>
                                                        <p class="sub-text">Deactivated</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><span class="<?= $role_badge ?>"><?= ucfirst($ina['role']) ?></span></td>
                                            <td><?= htmlspecialchars($ina['email']) ?></td>
                                            <td><span class="status-pill inactive">Inactive</span></td>
                                            <td>
                                                <form action="../../backend/controller.php?method_finder=manage_users" method="POST" style="display: inline;">
                                                    <input type="hidden" name="sub_action" value="reactivate">
                                                    <input type="hidden" name="target_id" value="<?= $ina['user_id'] ?>">
                                                    <button type="submit" class="icon-btn edit" title="Reactivate Account">
                                                        <i class="fas fa-undo"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="empty-state">
                                <img src="../../assets/img/icons/error-icon.svg" style="width:40px; opacity:0.3; margin-bottom:1rem; filter: grayscale(100%);">
                                <p>No deactivated accounts.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>