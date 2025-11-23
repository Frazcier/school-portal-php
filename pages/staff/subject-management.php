<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/management-common.css"/>
    <link rel="icon" type="image/x-icon" href="../../assets/img/logo/logo.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Subject Management</title>
</head>
<body>
    <div id="header-placeholder"></div>

    <div class="container">
        <div id="sidebar-placeholder"></div>

        <div class="content">
            
            <div class="section-header">
                <div class="header-details">
                    <h1>Subject Management</h1>
                    <h3>Oversee curriculum, assign instructors, and manage schedules.</h3>
                </div>
                <div class="header-actions">
                    <button class="btn-secondary">
                        <i class="fas fa-file-export"></i> Export List
                    </button>
                    <button class="btn-primary">
                        <i class="fas fa-plus"></i> Add New Subject
                    </button>
                </div>
            </div>

            <div class="toolbar">
                <div class="search-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" placeholder="Search by subject code or name...">
                </div>
                
                <div class="filter-group">
                    <div class="select-wrapper">
                        <select>
                            <option value="" disabled selected>Department</option>
                            <option>Information Technology</option>
                            <option>Computer Science</option>
                            <option>Multimedia Arts</option>
                            <option>General Education</option>
                        </select>
                        <i class="fas fa-chevron-down chevron"></i>
                    </div>

                    <div class="select-wrapper">
                        <select>
                            <option value="" disabled selected>Status</option>
                            <option>Active</option>
                            <option>Pending</option>
                            <option>Inactive</option>
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
                                <th>Subject Code</th>
                                <th>Subject Name</th>
                                <th>Department</th>
                                <th>Instructor</th>
                                <th>Students</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="code-badge">IT 115</span></td>
                                <td class="subject-title">Introduction to HCI</td>
                                <td>Information Technology</td>
                                <td>
                                    <div class="instructor-cell">
                                        <img src="../../assets/img/profile-pictures/profile-picture-8.jpg" alt="Instructor">
                                        <span>Delia Leon</span>
                                    </div>
                                </td>
                                <td>124</td>
                                <td><span class="status-pill active">Active</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="icon-btn" title="View Schedule"><i class="far fa-calendar-alt"></i></button>
                                        <button class="icon-btn edit" title="Edit"><i class="fas fa-pen"></i></button>
                                        <button class="icon-btn delete" title="Delete"><i class="fas fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="code-badge">IT 114</span></td>
                                <td class="subject-title">Computer Programming 2</td>
                                <td>Information Technology</td>
                                <td>
                                    <div class="instructor-cell">
                                        <img src="../../assets/img/profile-pictures/profile-picture-2.jpg" alt="Instructor">
                                        <span>Henson Sagorsor</span>
                                    </div>
                                </td>
                                <td>84</td>
                                <td><span class="status-pill active">Active</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="icon-btn" title="View Schedule"><i class="far fa-calendar-alt"></i></button>
                                        <button class="icon-btn edit" title="Edit"><i class="fas fa-pen"></i></button>
                                        <button class="icon-btn delete" title="Delete"><i class="fas fa-trash"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="code-badge">IT 999</span></td>
                                <td class="subject-title">Web Design Fundamentals</td>
                                <td>Multimedia Arts</td>
                                <td>
                                    <div class="instructor-cell">
                                        <img src="../../assets/img/profile-pictures/profile-picture-6.jpg" alt="Instructor">
                                        <span>Maria Gonzales</span>
                                    </div>
                                </td>
                                <td>65</td>
                                <td><span class="status-pill pending">Pending</span></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="icon-btn" title="View Schedule"><i class="far fa-calendar-alt"></i></button>
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