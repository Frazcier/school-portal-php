<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/management-common.css"/>
    <link rel="icon" type="image/x-icon" href="../../assets/img/logo/logo.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>User Management</title>
</head>
<body>
    <div id="header-placeholder"></div>

    <div class="container">
        <div id="sidebar-placeholder"></div>

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

                <div class="tabs-header">
                    <label for="tab-students" class="tab-item">Students <span class="badge">1,204</span></label>
                    <label for="tab-instructors" class="tab-item">Instructors <span class="badge">42</span></label>
                    <label for="tab-admins" class="tab-item">Admins <span class="badge">5</span></label>
                    <label for="tab-pending" class="tab-item">Pending <span class="badge warning">12</span></label>
                </div>

                <div class="tab-content-wrapper">
                    
                    <div id="view-students" class="tab-view fadeIn">
                        <div class="table-responsive">
                            <table>
                                <thead>
                                    <tr>
                                        <th>User Profile</th>
                                        <th>Student ID</th>
                                        <th>Email Address</th>
                                        <th>Enrolled</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <div class="user-cell">
                                                <img src="../../assets/img/profile-pictures/profile-picture-1.jpg" alt="User">
                                                <div>
                                                    <p class="name">Timothy Dionela</p>
                                                    <p class="sub-text">BSIT 2nd Year</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>STU-2025-001</td>
                                        <td>timothy.d@email.com</td>
                                        <td>4 Subjects</td>
                                        <td><span class="status-pill active">Active</span></td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="icon-btn edit" title="Edit"><i class="fas fa-pen"></i></button>
                                                <button class="icon-btn delete" title="Delete"><i class="fas fa-trash"></i></button>
                                            </div>
                                        </td>
                                    </tr>
                                    </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="view-instructors" class="tab-view fadeIn">
                        <div class="empty-state">
                            <p>Instructors table content goes here.</p>
                        </div>
                    </div>

                    <div id="view-admins" class="tab-view fadeIn">
                        <div class="empty-state">
                            <p>Admins table content goes here.</p>
                        </div>
                    </div>

                     <div id="view-pending" class="tab-view fadeIn">
                        <div class="empty-state">
                            <p>Pending applications go here.</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="../../assets/js/component-staff.js"></script>
</body>
</html>