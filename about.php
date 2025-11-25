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
</head>
<body>
    <header>
        <div class="nav-container">
            <div class="logo-section">
                <img src="assets/img/logo/for-guthib.png" alt="CIS Logo" class="nav-logo">
                <div class="logo-text">Ikinamada Designs</div>
            </div>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="#" class="active-link">About</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a class="button login-btn" href="pages/auth/login.php">Portal Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section class="page-hero about-hero">
            <div class="hero-bg-shape shape-2"></div>
            <div class="hero-content center-text">
                <span class="badge">Who We Are</span>
                <h1>Shaping the Future of <span class="gradient-text">Digital Innovation</span></h1>
                <p>The College of Information Sciences at Benguet State University is dedicated to producing globally competitive professionals in IT, Development Communication, and Library Science.</p>
            </div>
        </section>

        <section class="mission-vision">
            <div class="mv-container">
                <div class="mv-card glass-card">
                    <div class="icon-box"><i class="fas fa-bullseye"></i></div>
                    <h3>Our Mission</h3>
                    <p>To provide quality education in information sciences that fosters creativity, critical thinking, and technical proficiency, preparing students to become leaders in the rapidly evolving digital landscape.</p>
                </div>
                <div class="mv-card glass-card">
                    <div class="icon-box"><i class="fas fa-eye"></i></div>
                    <h3>Our Vision</h3>
                    <p>A premier college of information sciences recognized for excellence in instruction, research, and extension services, contributing to sustainable development in the Cordillera region and beyond.</p>
                </div>
            </div>
        </section>

        <section class="features">
            <div class="section-title">
                <h2>Academic Programs</h2>
                <p>We offer specialized degree programs designed to equip you with industry-ready skills.</p>
            </div>
            <div class="features-grid programs-grid">
                <div class="feature-card program-card">
                    <div class="program-icon"><i class="fas fa-laptop-code"></i></div>
                    <h3>BS Information Technology</h3>
                    <p>Focuses on the study of computer utilization and software development. Students learn to design, develop, and manage secure information systems.</p>
                    <ul class="program-list">
                        <li><i class="fas fa-check"></i> Web & Mobile Development</li>
                        <li><i class="fas fa-check"></i> Network Administration</li>
                        <li><i class="fas fa-check"></i> Systems Integration</li>
                    </ul>
                </div>

                <div class="feature-card program-card">
                    <div class="program-icon"><i class="fas fa-bullhorn"></i></div>
                    <h3>BS Development Communication</h3>
                    <p>Trains students to use communication media to facilitate social change and development, focusing on community broadcasting and science communication.</p>
                    <ul class="program-list">
                        <li><i class="fas fa-check"></i> Community Broadcasting</li>
                        <li><i class="fas fa-check"></i> Science Journalism</li>
                        <li><i class="fas fa-check"></i> Educational Communication</li>
                    </ul>
                </div>

                <div class="feature-card program-card">
                    <div class="program-icon"><i class="fas fa-book-reader"></i></div>
                    <h3>Bachelor of Library & Information Science</h3>
                    <p>Prepares students for the management of libraries and information centers, focusing on the systematic organization, preservation, and dissemination of information.</p>
                    <ul class="program-list">
                        <li><i class="fas fa-check"></i> Archives Management</li>
                        <li><i class="fas fa-check"></i> Digital Librarianship</li>
                        <li><i class="fas fa-check"></i> Information Governance</li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="cta-section">
            <div class="cta-content">
                <h2>Ready to start your journey?</h2>
                <p>Join the CIS community today and build your future with us.</p>
                <a href="contact.html" class="button primary-btn">Contact Admissions</a>
            </div>
        </section>

        <?php require_once 'components/footer.php'; ?>
    </main>
</body>
</html>