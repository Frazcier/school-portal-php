<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/grade-report.css"/>
    <link rel="icon" type="image/x-icon" href="../../assets/img/logo/logo.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Grade Report</title>
</head>
<body>
    <div id="header-placeholder"></div>

    <div class="container">
        <div id="sidebar-placeholder"></div>

        <div class="content">
            
            <div class="section-header">
                <div class="header-details">
                    <h1>Grade Report</h1>
                    <h3>Track your academic performance and history.</h3>
                </div>
                <div class="header-actions">
                    <button class="btn-secondary">
                        <i class="fas fa-download"></i> Download Copy
                    </button>
                    <button class="btn-primary">
                        <i class="fas fa-print"></i> Print Grades
                    </button>
                </div>
            </div>

            <div class="summary-grid">
                <div class="stat-card purple">
                    <div class="icon-box"><i class="fas fa-chart-line"></i></div>
                    <div class="stat-details">
                        <p class="label">Semester GPA</p>
                        <h2 class="value">1.75</h2>
                        <p class="sub">Previous: 2.15</p>
                    </div>
                </div>

                <div class="stat-card blue">
                    <div class="icon-box"><i class="fas fa-award"></i></div>
                    <div class="stat-details">
                        <p class="label">Cumulative GPA</p>
                        <h2 class="value">1.92</h2>
                        <p class="sub">Consistent</p>
                    </div>
                </div>

                <div class="stat-card orange">
                    <div class="icon-box"><i class="fas fa-book-open"></i></div>
                    <div class="stat-details">
                        <p class="label">Units Earned</p>
                        <h2 class="value">21 / 26</h2>
                        <p class="sub">5 Units Dropped</p>
                    </div>
                </div>

                <div class="stat-card green">
                    <div class="icon-box"><i class="fas fa-user-graduate"></i></div>
                    <div class="stat-details">
                        <p class="label">Academic Standing</p>
                        <h2 class="value">Dean's List</h2>
                        <p class="sub">Excellent</p>
                    </div>
                </div>
            </div>

            <div class="data-card">
                <div class="card-header">
                    <div class="card-title">
                        <h3>Academic Records</h3>
                        <p>2nd Semester, A.Y. 2023-2024</p>
                    </div>
                    
                    <div class="select-wrapper">
                        <select>
                            <option selected>2nd Sem, 2023-2024</option>
                            <option>1st Sem, 2023-2024</option>
                            <option>Summer, 2023</option>
                        </select>
                        <i class="fas fa-chevron-down chevron"></i>
                    </div>
                </div>

                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Course Description</th>
                                <th>Instructor</th>
                                <th>Units</th>
                                <th>Final Grade</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="code">IT 114</td>
                                <td class="course-name">Computer Programming 2</td>
                                <td>
                                    <div class="instructor-cell">
                                        <img src="../../assets/img/profile-pictures/profile-picture-2.jpg" alt="Inst">
                                        <span>Henson Sagorsor</span>
                                    </div>
                                </td>
                                <td>3.0</td>
                                <td><span class="grade high">1.25</span></td>
                                <td><span class="status-pill passed">Passed</span></td>
                            </tr>
                            <tr>
                                <td class="code">IT 115</td>
                                <td class="course-name">Introduction to HCI</td>
                                <td>
                                    <div class="instructor-cell">
                                        <img src="../../assets/img/profile-pictures/profile-picture-8.jpg" alt="Inst">
                                        <span>Delia Leon</span>
                                    </div>
                                </td>
                                <td>3.0</td>
                                <td><span class="grade avg">2.00</span></td>
                                <td><span class="status-pill passed">Passed</span></td>
                            </tr>
                            <tr>
                                <td class="code">STATS 22</td>
                                <td class="course-name">Statistical Computing</td>
                                <td>
                                    <div class="instructor-cell">
                                        <img src="../../assets/img/profile-pictures/profile-picture-3.jpg" alt="Inst">
                                        <span>Maria Lubrica</span>
                                    </div>
                                </td>
                                <td>3.0</td>
                                <td><span class="grade highest">1.00</span></td>
                                <td><span class="status-pill passed">Passed</span></td>
                            </tr>
                            <tr>
                                <td class="code">ART 21</td>
                                <td class="course-name">Art Appreciation</td>
                                <td>
                                    <div class="instructor-cell">
                                        <img src="../../assets/img/profile-pictures/profile-picture-1.jpg" alt="Inst">
                                        <span>Revin Ignacio</span>
                                    </div>
                                </td>
                                <td>3.0</td>
                                <td><span class="grade low">3.00</span></td>
                                <td><span class="status-pill passed">Passed</span></td>
                            </tr>
                            <tr>
                                <td class="code">GEN 001</td>
                                <td class="course-name">Understanding the Self</td>
                                <td>
                                    <div class="instructor-cell">
                                        <img src="../../assets/img/profile-pictures/profile-picture-4.jpg" alt="Inst">
                                        <span>Soujee Ann</span>
                                    </div>
                                </td>
                                <td>3.0</td>
                                <td><span class="grade fail">5.00</span></td>
                                <td><span class="status-pill failed">Failed</span></td>
                            </tr>
                            </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-right"><strong>Term Averages:</strong></td>
                                <td><strong>15.0</strong></td>
                                <td><strong>1.75</strong></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script src="../../assets/js/component-student.js"></script>
</body>
</html>