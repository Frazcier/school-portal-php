<?php 
    session_start();

    require_once '../../backend/controller.php';
    require_once '../../backend/algorithms/MergeSort.php';

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
        header("Location: ../auth/login.php");
        exit();
    }

    $controller = new controller();
    
    $raw_resources = $controller->get_student_resources($_SESSION['user_id']);
    
    $subjects_list = [];
    foreach ($raw_resources as $r) {
        $subjects_list[$r['subject_code']] = $r['subject_code']; 
    }

    $filtered_resources = [];
    $filter_sub = $_GET['subject'] ?? '';
    $filter_cat = $_GET['category'] ?? '';
    $sort_by = $_GET['sort'] ?? 'date';

    foreach ($raw_resources as $res) {
        $include = true;
        if (!empty($filter_sub) && $res['subject_code'] !== $filter_sub) $include = false;
        if (!empty($filter_cat) && $res['category'] !== $filter_cat) $include = false;
        
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
    <link rel="stylesheet" href="../../assets/css/libraries.css"/>
    <link rel="icon" type="image/x-icon" href="../../assets/img/logo/logo.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="../../assets/js/main.js" defer></script>
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
                    <h3>Access course materials, e-books, and research papers.</h3>
                </div>
                <div class="header-actions">
                    <button class="btn-secondary">
                        <i class="fas fa-bookmark"></i> Saved Items
                    </button>
                </div>
            </div>

            <div class="toolbar">
                <div class="search-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="realTimeSearch" onkeyup="searchCards()" 
                           placeholder="Search for title, subject, or topic...">
                </div>
                
                <form method="GET" class="filter-group">
                    <div class="select-wrapper">
                        <select name="subject" onchange="this.form.submit()">
                            <option value="">All Subjects</option>
                            <?php foreach($subjects_list as $sub_code): ?>
                                <option value="<?= $sub_code ?>" <?= $filter_sub === $sub_code ? 'selected' : '' ?>>
                                    <?= $sub_code ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <i class="fas fa-chevron-down chevron"></i>
                    </div>
                    
                    <div class="select-wrapper">
                        <select name="category" onchange="this.form.submit()">
                            <option value="">All Categories</option>
                            <option value="Lecture" <?= $filter_cat === 'Lecture' ? 'selected' : '' ?>>Lecture</option>
                            <option value="Assignment" <?= $filter_cat === 'Assignment' ? 'selected' : '' ?>>Assignment</option>
                            <option value="Reference" <?= $filter_cat === 'Reference' ? 'selected' : '' ?>>Reference</option>
                            <option value="Video" <?= $filter_cat === 'Video' ? 'selected' : '' ?>>Video</option>
                        </select>
                        <i class="fas fa-chevron-down chevron"></i>
                    </div>
                </form>
            </div>

            <div class="library-container">
                <div id="resourceGrid" class="resources-grid fadeIn">
                    
                    <?php if (empty($resources)): ?>
                        <div class="empty-state" style="grid-column: 1/-1; text-align: center; padding: 3rem; color: #888;">
                            <img src="../../assets/img/icons/search-icon.svg" style="width: 50px; opacity: 0.3; margin-bottom: 1rem">
                            <h3>No resources found.</h3>
                        </div>
                    <?php else: ?>
                        <?php foreach ($resources as $res): 
                            $ext = strtolower($res['file_type']);
                            $icon = 'file-icon.svg';
                            $styleClass = 'pdf';

                            if (in_array($ext, ['pdf'])) { 
                                $styleClass = 'pdf'; 
                                $icon = 'file-icon.svg';
                            } elseif (in_array($ext, ['doc', 'docx'])) { 
                                $styleClass = 'research'; 
                                $icon = 'research-papers-icon.svg'; 
                            } elseif (in_array($ext, ['ppt', 'pptx'])) { 
                                $styleClass = 'slides'; 
                                $icon = 'course-materials-2-icon.svg';
                            } elseif (in_array($ext, ['mp4', 'mov'])) { 
                                $styleClass = 'ebook'; 
                                $icon = 'e-books-1-icon.svg'; 
                            } elseif ($ext == 'link') {
                                $styleClass = 'link';
                                $icon = 'visit-link-icon.svg';
                            }
                            
                            $fileSize = strtoupper($ext);
                            $realPath = __DIR__ . "/../../assets/uploads/" . $res['file_name'];
                            if (file_exists($realPath)) {
                                $bytes = filesize($realPath);
                                if ($bytes >= 1048576) $fileSize .= ' • ' . number_format($bytes / 1048576, 1) . ' MB';
                                elseif ($bytes >= 1024) $fileSize .= ' • ' . number_format($bytes / 1024, 0) . ' KB';
                            }
                        ?>
                        <div class="resource-card">
                            <div class="card-icon <?= $styleClass ?>">
                                <img src="../../assets/img/icons/<?= $icon ?>" alt="Icon">
                            </div>
                            
                            <div class="card-body">
                                <span class="tag"><?= htmlspecialchars($res['subject_code']) ?></span>
                                <h4><?= htmlspecialchars($res['title']) ?></h4>
                                <p><?= htmlspecialchars($res['subject_description']) ?></p>
                            </div>
                            
                            <div class="card-footer">
                                <span class="file-info"><?= $fileSize ?></span>
                                <a href="<?= htmlspecialchars($res['file_path']) ?>" download class="icon-btn">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                </div>
                
                <div id="noResultsMsg" style="display:none; text-align:center; padding: 2rem; color: #888;">
                    No resources match your search.
                </div>
            </div>

        </div>
    </div>
</body>
</html>