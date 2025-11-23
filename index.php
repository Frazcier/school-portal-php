<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="icon" type="image/x-icon" href="assets/img/LOGO/logo.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header>
        <div class="nav-container">
            <div class="logo-section">
                <img src="assets/img/LOGO/logo.png" alt="CIS Logo" class="nav-logo">
                <div class="logo-text">Ikinamada Designs</div>
            </div>
            <nav>
                <ul>
                    <li><a href="#" class="active-link">Home</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="contact.php">Contact</a></li>
                    <li><a class="button login-btn" href="pages/auth/login.php">Portal Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section class="hero">
            <div class="hero-bg-shape shape-1"></div>
            <div class="hero-bg-shape shape-2"></div>
            
            <div class="hero-content">
                <span class="badge">2025 Academic Year</span>
                <h1>Innovating the Future of <br> <span class="gradient-text">Information Sciences</span></h1>
                <p>Empowering students with cutting-edge technology and a seamless digital campus experience. Manage your academic journey with ease.</p>
                <div class="hero-buttons">
                    <a href="pages/auth/login.php" class="button primary-btn pulse">Get Started <i class="fas fa-arrow-right"></i></a>
                    <a href="about.php" class="button secondary-btn">Learn More</a>
                </div>
            </div>
            
            <div class="hero-visual">
                <div class="hero-icon-wrapper">
                    <div class="main-icon-glass">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    
                    <div class="satellite-icon sat-1" title="Library">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="satellite-icon sat-2" title="Technology">
                        <i class="fas fa-laptop-code"></i>
                    </div>
                    <div class="satellite-icon sat-3" title="Analytics">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="satellite-icon sat-4" title="Community">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>
        </section>

        <section class="stats-bar">
            <div class="stat-item">
                <h3>1,200+</h3>
                <p>Students Enrolled</p>
            </div>
            <div class="stat-item">
                <h3>3</h3>
                <p>Major Programs</p>
            </div>
            <div class="stat-item">
                <h3>100%</h3>
                <p>Online Integration</p>
            </div>
        </section>

        <section class="features">
            <div class="section-title">
                <h2>Everything you need in one place</h2>
                <p>Our digital ecosystem provides students and staff with powerful tools.</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="icon-box"><i class="fas fa-book"></i></div>
                    <h3>Subject Management</h3>
                    <p>View enrolled subjects, schedules, and instructor details effortlessly.</p>
                </div>
                <div class="feature-card">
                    <div class="icon-box"><i class="fas fa-chart-pie"></i></div>
                    <h3>Grade Reports</h3>
                    <p>Track your academic performance with real-time grade updates and analytics.</p>
                </div>
                <div class="feature-card">
                    <div class="icon-box"><i class="fas fa-layer-group"></i></div>
                    <h3>Digital Library</h3>
                    <p>Access thousands of e-books, research papers, and course materials.</p>
                </div>
                <div class="feature-card">
                    <div class="icon-box"><i class="fas fa-wallet"></i></div>
                    <h3>Easy Payments</h3>
                    <p>Manage tuition and fees securely through our integrated payment gateway.</p>
                </div>
            </div>
        </section>

        <?php require_once 'components/footer.php'; ?>
    </main>
</body>
</html>