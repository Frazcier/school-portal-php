<?php 
    session_start();
    require_once '../../backend/controller.php';

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher' && $_SESSION['role'] !== 'admin') {
        header("Location: ../auth/login.php?error=You don't have permission to access this page");
        exit();
    }

    $controller = new controller();
    $announcements = $controller->get_announcements();
    $logs = array_slice($controller->get_recent_logs(), 0, 5); 

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
    <link rel="icon" type="image/x-icon" href="../../assets/img/logo/logo.ico">
    <link rel="stylesheet" href="../../assets/css/management-common.css"/>
    <link rel="stylesheet" href="../../assets/css/staff-dashboard.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="../../assets/js/main.js" defer></script>
    <script src="../../assets/js/modal.js" defer></script>
    
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
                    <button class="btn-primary" onclick="openAnnouncementModal()">
                        <i class="fas fa-bullhorn"></i> Create Announcement
                    </button>
                </div>
            </div>

            <div class="dashboard-grid">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Quick Actions</h3>
                    </div>
                    <div class="quick-actions-grid">
                        <a href="subject-management.php" class="action-tile" style="text-decoration: none;">
                            <div class="icon-box purple"><i class="fas fa-plus"></i></div>
                            <span>Add Subject</span>
                        </a>
                        <a href="user-management.php" class="action-tile" style="text-decoration: none;">
                            <div class="icon-box blue"><i class="fas fa-users"></i></div>
                            <span>Manage Users</span>
                        </a>
                        <a href="grading.php" class="action-tile" style="text-decoration: none;">
                            <div class="icon-box orange"><i class="fas fa-file-alt"></i></div>
                            <span>Grading</span>
                        </a>
                        <a href="content-library.php" class="action-tile" style="text-decoration: none;">
                            <div class="icon-box green"><i class="fas fa-cloud-upload-alt"></i></div>
                            <span>Upload File</span>
                        </a>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Announcements</h3>
                    </div>
                    <div class="announcement-list">
                        <?php if(empty($announcements)): ?>
                            <p style="color: #888; text-align:center; padding: 1rem;">No announcements yet.</p>
                        <?php else: ?>
                            <?php foreach($announcements as $ann): 
                                $ann_json = htmlspecialchars(json_encode($ann), ENT_QUOTES, 'UTF-8');
                            ?>
                            <div class="announcement-item">
                                <div class="ann-icon">
                                    <img src="../../assets/img/icons/announcement-title-icon.svg" alt="Icon">
                                </div>
                                <div class="ann-content">
                                    <h4><?= htmlspecialchars($ann['title']) ?></h4>
                                    <p><?= date("M d", strtotime($ann['created_at'])) ?> &bullet; 
                                       <span class="status-text active"><?= $ann['status'] ?></span>
                                    </p>
                                </div>
                                <div class="action-buttons" style="gap: 0.5rem;">
                                    <button class="icon-btn edit" title="Edit" onclick="openAnnouncementEditModal('<?= $ann_json ?>')">
                                        <i class="fas fa-pen"></i>
                                    </button>
                                    <form action="../../backend/controller.php?method_finder=delete_announcement" method="POST" onsubmit="return confirm('Delete this announcement?');">
                                        <input type="hidden" name="announcement_id" value="<?= $ann['announcement_id'] ?>">
                                        <button type="submit" class="icon-btn delete" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="data-card">
                <div class="card-header" style="border-bottom: 1px solid #eee; padding: 1.5rem; display:flex; justify-content:space-between; align-items:center;">
                    <h3 style="margin:0; font-size:1.1rem;">Recent System Activity</h3>
                    <a href="../../backend/controller.php?method_finder=export_logs" class="btn-secondary" style="height: 2.5rem; padding: 0 1rem; font-size: 0.85rem;">
                        <i class="fas fa-download"></i> Export Log
                    </a>
                </div>
                
                <div class="table-responsive">
                    <table id="activityTable">
                        <thead>
                            <tr>
                                <th>Action Type</th>
                                <th>User</th>
                                <th>Description</th>
                                <th>Date & Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($logs)): ?>
                                <tr><td colspan="5" style="text-align:center; padding:2rem;">No recent activity logged.</td></tr>
                            <?php else: ?>
                                <?php foreach($logs as $log): 
                                    $type = $log['action_type'];
                                    $badge = 'badge-soft blue';
                                    if(strpos($type, 'Delete') !== false) $badge = 'badge-soft orange';
                                    if(strpos($type, 'Upload') !== false) $badge = 'badge-soft green';
                                    if(strpos($type, 'Announcement') !== false) $badge = 'badge-soft purple';
                                    if(strpos($type, 'Security') !== false) $badge = 'badge-soft orange'; // Red/Orange for security
                                    
                                    $pic = $log['profile_picture'] ?? '../../assets/img/profile-pictures/profile-staff.svg';
                                    $name = $log['first_name'] ? htmlspecialchars($log['first_name'] . ' ' . $log['last_name']) : 'System User';
                                ?>
                                <tr>
                                    <td><span class="<?= $badge ?>"><?= htmlspecialchars($type) ?></span></td>
                                    <td>
                                        <div class="user-cell">
                                            <img src="<?= htmlspecialchars($pic) ?>" alt="User">
                                            <span class="name"><?= $name ?></span>
                                        </div>
                                    </td>
                                    <td><?= htmlspecialchars($log['description']) ?></td>
                                    <td><?= date("M d, Y • h:i A", strtotime($log['created_at'])) ?></td>
                                    <td><span class="status-pill active">Completed</span></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div style="text-align: center; padding: 1.5rem; border-top: 1px solid #f0f0f0;">
                    <button id="loadMoreBtn" onclick="loadMoreLogs()" class="btn-secondary" style="margin: 0 auto; width: auto; min-width: 200px;">
                        Load More Activity
                    </button>
                </div>
            </div>

        </div>
    </div>

    <div id="announcement-modal" class="modal-overlay" onclick="closeModals(event)">
        <div class="modal-box">
            <button class="close-btn" onclick="closeModals(event)">&times;</button>
            <div class="modal-header"><h3>Create Announcement</h3></div>
            <form action="../../backend/controller.php?method_finder=create_announcement" method="POST">
                <div class="form-grid single-col">
                    <div class="input-group">
                        <label>Title</label>
                        <input type="text" name="title" placeholder="e.g. Midterm Schedule Updates" required 
                               style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 0.5rem;">
                    </div>
                    <div class="input-group">
                        <label>Content</label>
                        <textarea name="content" rows="5" placeholder="Enter announcement details..." required
                                  style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 0.5rem; font-family: inherit;"></textarea>
                    </div>
                </div>
                <button type="submit" class="btn-primary" style="width: 100%; margin-top: 1rem;">Post Announcement</button>
            </form>
        </div>
    </div>

    <div id="edit-announcement-modal" class="modal-overlay" onclick="closeModals(event)">
        <div class="modal-box">
            <button class="close-btn" onclick="closeModals(event)">&times;</button>
            <div class="modal-header"><h3>Edit Announcement</h3></div>
            <form action="../../backend/controller.php?method_finder=update_announcement" method="POST">
                <input type="hidden" name="announcement_id" id="edit-ann-id">
                <div class="form-grid single-col">
                    <div class="input-group">
                        <label>Title</label>
                        <input type="text" name="title" id="edit-ann-title" required 
                               style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 0.5rem;">
                    </div>
                    <div class="input-group">
                        <label>Status</label>
                        <select name="status" id="edit-ann-status" style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 0.5rem;">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label>Content</label>
                        <textarea name="content" id="edit-ann-content" rows="5" required
                                  style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 0.5rem; font-family: inherit;"></textarea>
                    </div>
                </div>
                <button type="submit" class="btn-primary" style="width: 100%; margin-top: 1rem;">Save Changes</button>
            </form>
        </div>
    </div>

</body>
</html>