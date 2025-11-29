<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="icon" type="image/x-icon" href="assets/img/logo/logo.ico">    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>About Us | College of Information Sciences</title>
    <script src="assets/js/landing.js"></script>
</head>
<body>
    <div id="backToTop" class="back-to-top">
        <i class="fas fa-arrow-up"></i>
    </div>
    
    <?php require_once 'components/public-header.php'; ?>

    <main>
        <section class="page-hero about-hero">
            <div class="hero-bg-shape shape-2"></div>
            <div class="hero-content center-text reveal">
                <span class="badge">Who We Are</span>
                <h1>Shaping the Future of <span class="gradient-text">Digital Innovation</span></h1>
                <p>The College of Information Sciences at Benguet State University is dedicated to producing globally competitive professionals in IT, Development Communication, and Library Science.</p>
            </div>
        </section>

        <section class="mission-vision reveal">
            <div class="mv-container">
                <div class="mv-card glass-card reveal">
                    <div class="icon-box"><i class="fas fa-bullseye"></i></div>
                    <h3>Our Mission</h3>
                    <p>To provide quality education in information sciences that fosters creativity, critical thinking, and technical proficiency, preparing students to become leaders in the rapidly evolving digital landscape.</p>
                </div>
                <div class="mv-card glass-card reveal">
                    <div class="icon-box"><i class="fas fa-eye"></i></div>
                    <h3>Our Vision</h3>
                    <p>A premier college of information sciences recognized for excellence in instruction, research, and extension services, contributing to sustainable development in the Cordillera region and beyond.</p>
                </div>
            </div>
        </section>

        <section class="features">
            <div class="section-title reveal">
                <h2>Academic Programs</h2>
                <p>We offer specialized degree programs designed to equip you with industry-ready skills.</p>
            </div>
            
            <div class="features-grid programs-grid reveal-delay-1">
                <div class="feature-card program-card" onclick="openCurriculum('bsit')" style="cursor: pointer;">
                    <div class="program-icon"><i class="fas fa-laptop-code"></i></div>
                    <h3>BS Information Technology</h3>
                    <p>Focuses on the study of computer utilization and software development.</p>
                    <ul class="program-list">
                        <li><i class="fas fa-check"></i> Web & Mobile Development</li>
                        <li><i class="fas fa-check"></i> Network Administration</li>
                    </ul>
                    <p class="click-hint">Click to view curriculum <i class="fas fa-arrow-right"></i></p>
                </div>

                <div class="feature-card program-card reveal-delay-2" onclick="openCurriculum('bsdc')" style="cursor: pointer;">
                    <div class="program-icon"><i class="fas fa-bullhorn"></i></div>
                    <h3>BS Dev. Communication</h3>
                    <p>Trains students to use communication media to facilitate social change.</p>
                    <ul class="program-list">
                        <li><i class="fas fa-check"></i> Community Broadcasting</li>
                        <li><i class="fas fa-check"></i> Science Journalism</li>
                    </ul>
                    <p class="click-hint">Click to view curriculum <i class="fas fa-arrow-right"></i></p>
                </div>

                <div class="feature-card program-card reveal-delay-3" onclick="openCurriculum('blis')" style="cursor: pointer;">
                    <div class="program-icon"><i class="fas fa-book-reader"></i></div>
                    <h3>BL Info. Science</h3>
                    <p>Prepares students for the management of libraries and digital archives.</p>
                    <ul class="program-list">
                        <li><i class="fas fa-check"></i> Archives Management</li>
                        <li><i class="fas fa-check"></i> Digital Librarianship</li>
                    </ul>
                    <p class="click-hint">Click to view curriculum <i class="fas fa-arrow-right"></i></p>
                </div>
            </div>
        </section>

        <div id="curriculum-modal" class="modal-overlay">
            <div class="modal-box curriculum-box">
                <button class="close-btn" onclick="closeCurriculum()">&times;</button>
                
                <div id="bsit-detail" class="program-detail" style="display: none;">
                    <div class="modal-header">
                        <div class="icon-box"><i class="fas fa-laptop-code"></i></div>
                        <h2>BS Information Technology</h2>
                        <p>Curriculum based on College of Applied Tech & CIS Standards</p>
                    </div>
                    
                    <div class="year-tabs-container">
                        <button class="year-tab active" onclick="switchYear('bsit', 'y1')">1st Year</button>
                        <button class="year-tab" onclick="switchYear('bsit', 'y2')">2nd Year</button>
                        <button class="year-tab" onclick="switchYear('bsit', 'y3')">3rd Year</button>
                        <button class="year-tab" onclick="switchYear('bsit', 'y4')">4th Year</button>
                    </div>

                    <div id="bsit-y1" class="subject-list active">
                        <div class="sub-item"><span>IT 111</span> Introduction to Computing</div>
                        <div class="sub-item"><span>IT 112</span> Computer Programming 1</div>
                        <div class="sub-item"><span>IT 113</span> Computer Programming 2</div>
                        <div class="sub-item"><span>MATH 1</span> Mathematics in the Modern World</div>
                        <div class="sub-item"><span>GEC 1</span> Understanding the Self</div>
                        <div class="sub-item"><span>NSTP 1</span> National Service Training Program 1</div>
                    </div>

                    <div id="bsit-y2" class="subject-list">
                        <div class="sub-item"><span>IT 211</span> Data Structures & Algorithms</div>
                        <div class="sub-item"><span>IT 212</span> Object Oriented Programming</div>
                        <div class="sub-item"><span>IT 213</span> Information Management</div>
                        <div class="sub-item"><span>IT 214</span> Networking 1 (Fundamentals)</div>
                        <div class="sub-item"><span>IT 215</span> Platform Technologies</div>
                        <div class="sub-item"><span>MATH 2</span> Discrete Mathematics</div>
                    </div>

                    <div id="bsit-y3" class="subject-list">
                        <div class="sub-item"><span>IT 311</span> Information Assurance & Security</div>
                        <div class="sub-item"><span>IT 312</span> Web Systems & Technologies</div>
                        <div class="sub-item"><span>IT 313</span> Human Computer Interaction</div>
                        <div class="sub-item"><span>IT 314</span> Systems Integration & Architecture</div>
                        <div class="sub-item"><span>IT 315</span> Mobile Programming</div>
                        <div class="sub-item"><span>RES 1</span> Methods of Research</div>
                    </div>

                    <div id="bsit-y4" class="subject-list">
                        <div class="sub-item"><span>IT 411</span> Capstone Project 1</div>
                        <div class="sub-item"><span>IT 412</span> Capstone Project 2</div>
                        <div class="sub-item"><span>IT 413</span> System Administration & Maintenance</div>
                        <div class="sub-item"><span>IT 414</span> Seminars and Field Trips</div>
                        <div class="sub-item"><span>PRAC</span> Internship / OJT (486 Hours)</div>
                    </div>
                </div>

                <div id="bsdc-detail" class="program-detail" style="display: none;">
                    <div class="modal-header">
                        <div class="icon-box"><i class="fas fa-bullhorn"></i></div>
                        <h2>BS Development Communication</h2>
                        <p>Majors: Community Broadcasting & Dev. Journalism</p>
                    </div>

                    <div class="year-tabs-container">
                        <button class="year-tab active" onclick="switchYear('bsdc', 'y1')">1st Year</button>
                        <button class="year-tab" onclick="switchYear('bsdc', 'y2')">2nd Year</button>
                        <button class="year-tab" onclick="switchYear('bsdc', 'y3')">3rd Year</button>
                        <button class="year-tab" onclick="switchYear('bsdc', 'y4')">4th Year</button>
                    </div>

                    <div id="bsdc-y1" class="subject-list active">
                        <div class="sub-item"><span>DEVC 10</span> Intro to Development Comm</div>
                        <div class="sub-item"><span>DEVC 11</span> Media Writing for Development</div>
                        <div class="sub-item"><span>DEVC 20</span> Fundamentals of Dev Journalism</div>
                        <div class="sub-item"><span>GEC 2</span> Purposive Communication</div>
                        <div class="sub-item"><span>SOCSCI 1</span> Communication & Society</div>
                    </div>

                    <div id="bsdc-y2" class="subject-list">
                        <div class="sub-item"><span>DEVC 30</span> Community Broadcasting</div>
                        <div class="sub-item"><span>DEVC 40</span> Educational Comm & Tech</div>
                        <div class="sub-item"><span>DEVC 50</span> Science Communication</div>
                        <div class="sub-item"><span>DEVC 60</span> Interpersonal Comm in Dev</div>
                        <div class="sub-item"><span>DEVC 70</span> Visual & Audiovisual Production</div>
                    </div>

                    <div id="bsdc-y3" class="subject-list">
                        <div class="sub-item"><span>DEVC 101</span> Communication Theory</div>
                        <div class="sub-item"><span>DEVC 126</span> Participatory Dev Journalism</div>
                        <div class="sub-item"><span>DEVC 135</span> Multimedia Materials Design</div>
                        <div class="sub-item"><span>DEVC 140</span> Communication Research</div>
                        <div class="sub-item"><span>DEVC 150</span> Comm Campaigns & Programs</div>
                    </div>

                    <div id="bsdc-y4" class="subject-list">
                        <div class="sub-item"><span>DEVC 198</span> Development Comm Internship</div>
                        <div class="sub-item"><span>DEVC 199</span> Undergraduate Seminar</div>
                        <div class="sub-item"><span>DEVC 200</span> Thesis / Special Project</div>
                        <div class="sub-item"><span>DEVC 160</span> Knowledge Management</div>
                        <div class="sub-item"><span>ETHICS</span> Communication Ethics & Laws</div>
                    </div>
                </div>

                <div id="blis-detail" class="program-detail" style="display: none;">
                    <div class="modal-header">
                        <div class="icon-box"><i class="fas fa-book-reader"></i></div>
                        <h2>BL Information Science</h2>
                        <p>Focus on Info. Literacy & Indigenous Knowledge</p>
                    </div>

                    <div class="year-tabs-container">
                        <button class="year-tab active" onclick="switchYear('blis', 'y1')">1st Year</button>
                        <button class="year-tab" onclick="switchYear('blis', 'y2')">2nd Year</button>
                        <button class="year-tab" onclick="switchYear('blis', 'y3')">3rd Year</button>
                        <button class="year-tab" onclick="switchYear('blis', 'y4')">4th Year</button>
                    </div>

                    <div id="blis-y1" class="subject-list active">
                        <div class="sub-item"><span>LIS 101</span> Intro to Library & Info Science</div>
                        <div class="sub-item"><span>LIS 102</span> Collection Management of Resources</div>
                        <div class="sub-item"><span>ICT 11</span> Fundamentals of Computer Software</div>
                        <div class="sub-item"><span>GEC 4</span> Readings in Philippine History</div>
                        <div class="sub-item"><span>LIS 103</span> School & Academic Librarianship</div>
                    </div>

                    <div id="blis-y2" class="subject-list">
                        <div class="sub-item"><span>LIS 201</span> Information Resources & Services 1</div>
                        <div class="sub-item"><span>LIS 202</span> Organization of Info Resources 1</div>
                        <div class="sub-item"><span>LIS 203</span> Library Materials for Children</div>
                        <div class="sub-item"><span>ICT 21</span> Web Technologies in Libraries</div>
                        <div class="sub-item"><span>LIS 204</span> Preservation of Info Resources</div>
                    </div>

                    <div id="blis-y3" class="subject-list">
                        <div class="sub-item"><span>LIS 301</span> Abstracting and Indexing</div>
                        <div class="sub-item"><span>LIS 302</span> Management of Libraries & Info Centers</div>
                        <div class="sub-item"><span>LIS 303</span> Research Methods in LIS</div>
                        <div class="sub-item"><span>LIS 304</span> Digital Libraries & Resources</div>
                        <div class="sub-item"><span>LIS 305</span> Reference & Information Services</div>
                    </div>

                    <div id="blis-y4" class="subject-list">
                        <div class="sub-item"><span>LPR 1</span> Library Practice I (In-Campus)</div>
                        <div class="sub-item"><span>LPR 2</span> Library Practice II (Off-Campus)</div>
                        <div class="sub-item"><span>LIS 400</span> Thesis / Research Project</div>
                        <div class="sub-item"><span>LIS 401</span> Ethics in Information Profession</div>
                        <div class="sub-item"><span>LIS 402</span> Indigenous Knowledge Systems</div>
                    </div>
                </div>

            </div>
        </div>

        <section class="cta-section reveal">
            <div class="cta-content">
                <h2>Ready to start your journey?</h2>
                <p>Join the CIS community today and build your future with us.</p>
                <a href="contact.php" class="button primary-btn">Contact Admissions</a>
            </div>
        </section>

        <?php require_once 'components/footer.php'; ?>
    </main>
</body>
</html>