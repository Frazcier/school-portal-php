<?php 
    session_start();
    require_once '../../backend/controller.php';
    require_once '../../backend/algorithms/MergeSort.php';

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher' && $_SESSION['role'] !== 'admin') {
        header("Location: ../auth/login.php");
        exit();
    }

    $controller = new controller();
    $raw_resources = $controller->get_all_resources();
    $subjects = $controller->get_all_subjects(); 

    $filtered_resources = [];
    $filter_sub = $_GET['subject'] ?? '';
    $filter_cat = $_GET['category'] ?? '';
    $filter_stat = $_GET['status'] ?? '';
    $sort_by = $_GET['sort'] ?? 'date'; 

    foreach ($raw_resources as $res) {
        $include = true;
        if (!empty($filter_sub) && $res['subject_code'] !== $filter_sub) $include = false;
        if (!empty($filter_cat) && $res['category'] !== $filter_cat) $include = false;
        if (!empty($filter_stat) && $res['status'] !== $filter_stat) $include = false;
        if ($include) $filtered_resources[] = $res;
    }

    $sorter = new MergeSort();
    if ($sort_by === 'name') {
        $resources = $sorter->sort($filtered_resources, 'title');
    } else {
        $sorted = $sorter->sort($filtered_resources, 'created_at');
        $resources = array_reverse($sorted);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/management-common.css"/>
    <link rel="stylesheet" href="../../assets/css/content-library.css"/>
    <link rel="icon" type="image/x-icon" href="../../assets/img/logo/logo.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="../../assets/js/main.js" defer></script>
    <script src="../../assets/js/modal.js" defer></script>
    <title>Content Library</title>
</head>
<body>
    <?php require_once '../../components/header.php'?>

    <div class="container">
        <?php require_once '../../components/sidebar.php'?>

        <div class="content">
            
            <div class="section-header">
                <div class="header-details">
                    <h1>Content Library</h1>
                    <h3>Manage and organize educational resources.</h3>
                </div>
                <div class="header-actions">
                    <button class="btn-secondary"><i class="fas fa-file-export"></i> Export</button>
                    <button class="btn-primary" onclick="openImportModal()"><i class="fas fa-cloud-upload-alt"></i> Upload Resource</button>
                </div>
            </div>

            <div class="toolbar" style="flex-direction: column; align-items: stretch; gap: 1rem;">
                <div class="search-wrapper" style="width: 100%;">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="realTimeSearch" onkeyup="searchResourceTable()" placeholder="Type to search (Title, Subject, Category)..." style="width: 100%;">
                </div>
                
                <form method="GET" class="filter-group" style="display:flex; width: 100%; gap: 1rem;">
                    <div class="select-wrapper" style="flex: 1;"><select name="subject"><option value="">All Subjects</option><?php foreach($subjects as $sub): ?><option value="<?= $sub['subject_code'] ?>"><?= $sub['subject_code'] ?></option><?php endforeach; ?></select><i class="fas fa-chevron-down chevron"></i></div>
                    <div class="select-wrapper" style="flex: 1;"><select name="category"><option value="">All Categories</option><option value="Lecture">Lecture</option><option value="Assignment">Assignment</option></select><i class="fas fa-chevron-down chevron"></i></div>
                    <div class="select-wrapper" style="flex: 1;"><select name="status"><option value="">All Status</option><option value="Published">Published</option><option value="Draft">Draft</option></select><i class="fas fa-chevron-down chevron"></i></div>
                    <button type="submit" class="btn-filter" style="flex: 0 0 auto; width: auto; padding: 0 2rem;">Filter</button>
                </form>
            </div>

            <div class="data-card">
                <div class="table-responsive">
                    <table id="resourcesTable">
                        <thead>
                            <tr>
                                <th>File Name</th><th>Category</th><th>Subject</th><th>Modified</th><th>Status</th><th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($resources as $res): 
                                $ext = $res['file_type'];
                                $icon = 'fa-file'; $color = 'gray';
                                if(in_array($ext, ['pdf'])) { $icon = 'fa-file-pdf'; $color = 'red'; }
                                elseif(in_array($ext, ['doc','docx'])) { $icon = 'fa-file-word'; $color = 'blue'; }
                            ?>
                            <tr class="resource-row">
                                <td><div class="file-cell"><div class="file-icon <?= $color ?>"><i class="fas <?= $icon ?>"></i></div><div><p class="name"><?= htmlspecialchars($res['title']) ?></p><p class="sub-text"><?= strtoupper($ext) ?></p></div></div></td>
                                <td><span class="badge-soft <?= $color ?>"><?= $res['category'] ?></span></td>
                                <td><span class="code-badge"><?= $res['subject_code'] ?></span></td>
                                <td><?= date("M d, Y", strtotime($res['created_at'])) ?></td>
                                <td><span class="status-pill <?= ($res['status']=='Published'?'active':'pending') ?>"><?= $res['status'] ?></span></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?= $res['file_path'] ?>" download class="icon-btn"><i class="fas fa-download"></i></a>
                                        <form action="../../backend/controller.php?method_finder=toggle_resource_status" method="POST" style="display:inline;">
                                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                                            <input type="hidden" name="resource_id" value="<?= $res['resource_id'] ?>">
                                            <input type="hidden" name="current_status" value="<?= $res['status'] ?>">
                                            <button class="icon-btn">
                                                <i class="fas <?= ($res['status']=='Published'?'fa-eye-slash':'fa-eye') ?>"></i>
                                            </button>
                                        </form>

                                        <form action="../../backend/controller.php?method_finder=delete_resource" method="POST" style="display:inline;" onsubmit="return confirm('Delete?')">
                                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                                            <input type="hidden" name="resource_id" value="<?= $res['resource_id'] ?>">
                                            <button class="icon-btn delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="import-modal" class="modal-overlay" onclick="closeModals(event)">
        <div class="modal-box">
            <button class="close-btn" onclick="closeModals(event)">&times;</button>
            <div class="modal-header"><h3>Upload New Resource</h3></div>
            
            <form action="../../backend/controller.php?method_finder=upload_resource" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">

                <div class="form-grid single-col" style="margin-bottom: 1rem;">
                    <div class="input-group">
                        <label>Resource Title</label>
                        <input type="text" name="title" placeholder="e.g. Week 1 - Introduction" required 
                               style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 0.5rem;">
                    </div>
                </div>

                <div class="form-grid">
                    <div class="input-group">
                        <label>Category</label>
                        <select name="category" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 0.5rem;">
                            <option value="Lecture">Lecture Material</option>
                            <option value="Assignment">Assignment</option>
                            <option value="Reference">Reference/Book</option>
                            <option value="Video">Video Recording</option>
                        </select>
                    </div>
                    
                    <div class="input-group">
                        <label>Subject</label>
                        <select name="subject_code" required style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 0.5rem;">
                            <option value="" disabled selected>Select Subject</option>
                            <?php foreach($subjects as $sub): ?>
                                <option value="<?= $sub['subject_code'] ?>">
                                    <?= $sub['subject_code'] ?> - <?= $sub['section_assigned'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="input-group" style="margin-top: 1rem;">
                    <label>Status</label>
                    <select name="status" style="width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 0.5rem;">
                        <option value="Published">Published (Visible)</option>
                        <option value="Draft">Draft (Hidden)</option>
                    </select>
                </div>

                <div class="input-group" style="margin-top: 1rem; border: 2px dashed #ccc; padding: 1.5rem; text-align: center; border-radius: 0.5rem; background: #f9f9f9;">
                    <label for="file-upload" style="cursor: pointer; display: block;">
                        <i class="fas fa-cloud-upload-alt" style="font-size: 1.5rem; color: var(--primary-color); margin-bottom: 0.5rem;"></i>
                        <span style="font-weight:600; color: var(--primary-color);">Click to Select File</span>
                    </label>
                    <input type="file" name="resource_file" id="file-upload" required 
                           style="margin-top: 0.5rem; width: 100%; font-size: 0.8rem;">
                </div>
                
                <button type="submit" class="btn-primary" style="width: 100%; margin-top: 1rem;">Upload File</button>
            </form>
        </div>
    </div>
</body>
</html>