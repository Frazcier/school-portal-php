<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/management-common.css"/>
    <link rel="icon" type="image/x-icon" href="../../assets/img/logo/logo.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Content Library</title>
</head>
<body>
    <div id="header-placeholder"></div>

    <div class="container">
        <div id="sidebar-placeholder"></div>

        <div class="content">
            
            <div class="section-header">
                <div class="header-details">
                    <h1>Content Library</h1>
                    <h3>Manage and organize educational resources.</h3>
                </div>
                <div class="header-actions">
                    <button class="btn-secondary">
                        <i class="fas fa-file-export"></i> Export
                    </button>
                    <button class="btn-primary">
                        <i class="fas fa-cloud-upload-alt"></i> Upload Resource
                    </button>
                </div>
            </div>

            <div class="toolbar">
                <div class="search-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" placeholder="Search by file name or tag...">
                </div>
                
                <div class="filter-group">
                    <div class="select-wrapper">
                        <select>
                            <option value="" disabled selected>File Type</option>
                            <option>Documents (PDF/Doc)</option>
                            <option>Presentations</option>
                            <option>Videos</option>
                            <option>Images</option>
                        </select>
                        <i class="fas fa-chevron-down chevron"></i>
                    </div>

                    <div class="select-wrapper">
                        <select>
                            <option value="" disabled selected>Subject</option>
                            <option>IT 114</option>
                            <option>IT 115</option>
                            <option>IT 116</option>
                        </select>
                        <i class="fas fa-chevron-down chevron"></i>
                    </div>

                    <div class="select-wrapper">
                        <select>
                            <option value="" disabled selected>Status</option>
                            <option>Published</option>
                            <option>Draft</option>
                            <option>Archived</option>
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
                                <th>File Name</th>
                                <th>Category</th>
                                <th>Related Subject</th>
                                <th>Date Modified</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="file-cell">
                                        <div class="file-icon red"><i class="fas fa-file-pdf"></i></div>
                                        <div>
                                            <p class="name">Module 1 - Introduction to OOP</p>
                                            <p class="sub-text">2.4 MB &bullet; PDF</p>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge-soft orange">Learning Material</span></td>
                                <td><span class="code-badge">IT 114</span></td>
                                <td>Apr 28, 2025</td>
                                <td><span class="status-pill active">Published</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="icon-btn" title="Download"><i class="fas fa-download"></i></button>
                                        <button class="icon-btn edit" title="Edit"><i class="fas fa-pen"></i></button>
                                        <button class="icon-btn delete" title="Delete"><i class="fas fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="file-cell">
                                        <div class="file-icon blue"><i class="fas fa-file-powerpoint"></i></div>
                                        <div>
                                            <p class="name">HCI Design Principles</p>
                                            <p class="sub-text">15 MB &bullet; PPTX</p>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge-soft purple">Lecture Slides</span></td>
                                <td><span class="code-badge">IT 115</span></td>
                                <td>Apr 26, 2025</td>
                                <td><span class="status-pill active">Published</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="icon-btn" title="Download"><i class="fas fa-download"></i></button>
                                        <button class="icon-btn edit" title="Edit"><i class="fas fa-pen"></i></button>
                                        <button class="icon-btn delete" title="Delete"><i class="fas fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="file-cell">
                                        <div class="file-icon green"><i class="fas fa-video"></i></div>
                                        <div>
                                            <p class="name">Database Normalization Tutorial</p>
                                            <p class="sub-text">120 MB &bullet; MP4</p>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge-soft green">Video Recording</span></td>
                                <td><span class="code-badge">IT 116</span></td>
                                <td>Apr 25, 2025</td>
                                <td><span class="status-pill pending">Draft</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="icon-btn" title="Download"><i class="fas fa-download"></i></button>
                                        <button class="icon-btn edit" title="Edit"><i class="fas fa-pen"></i></button>
                                        <button class="icon-btn delete" title="Delete"><i class="fas fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="file-cell">
                                        <div class="file-icon gray"><i class="fas fa-file-archive"></i></div>
                                        <div>
                                            <p class="name">Midterm Reviewer 2024</p>
                                            <p class="sub-text">5 MB &bullet; ZIP</p>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="badge-soft orange">Reviewer</span></td>
                                <td><span class="code-badge">STATS 22</span></td>
                                <td>Jan 10, 2025</td>
                                <td><span class="status-pill inactive">Archived</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="icon-btn" title="Download"><i class="fas fa-download"></i></button>
                                        <button class="icon-btn edit" title="Edit"><i class="fas fa-pen"></i></button>
                                        <button class="icon-btn delete" title="Delete"><i class="fas fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script src="../../assets/js/component-staff.js"></script>
</body>
</html>