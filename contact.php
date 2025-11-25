<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="icon" type="image/x-icon" href="assets/img/logo/logo.ico">    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Contact Us | College of Information Sciences</title>
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
                    <li><a href="about.php">About</a></li>
                    <li><a href="#" class="active-link">Contact</a></li>
                    <li><a class="button login-btn" href="pages/auth/login.php">Portal Login</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section class="page-hero contact-hero">
            <div class="hero-bg-shape shape-1"></div>
            <div class="hero-content center-text">
                <span class="badge">Get in Touch</span>
                <h1>We'd Love to <span class="gradient-text">Hear From You</span></h1>
                <p>Have questions about enrollment, programs, or student services? Reach out to us.</p>
            </div>
        </section>

        <section class="contact-section">
            <div class="contact-wrapper">
                
                <div class="contact-form-card glass-card">
                    <h3>Send us a Message</h3>
                    <form action="#" method="post">
                        <div class="input-group">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" name="name" placeholder="e.g. Juan Dela Cruz" required>
                        </div>
                        <div class="input-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" placeholder="e.g. juan@gmail.com" required>
                        </div>
                        <div class="input-group">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" rows="5" placeholder="How can we help you?" required></textarea>
                        </div>
                        <button class="button primary-btn full-width" type="submit">Send Message <i class="fas fa-paper-plane"></i></button>
                    </form>
                </div>

                <div class="contact-info">
                    <div class="info-card">
                        <div class="icon-box small"><i class="fas fa-map-marker-alt"></i></div>
                        <div>
                            <h4>Visit Us</h4>
                            <p>College of Information Sciences<br>Benguet State University<br>Km. 5, La Trinidad, Benguet</p>
                        </div>
                    </div>
                    
                    <div class="info-card">
                        <div class="icon-box small"><i class="fas fa-phone-alt"></i></div>
                        <div>
                            <h4>Call Us</h4>
                            <p>+63 960 896 8280<br>+63 74 422 2127</p>
                        </div>
                    </div>

                    <div class="info-card">
                        <div class="icon-box small"><i class="fas fa-envelope"></i></div>
                        <div>
                            <h4>Email Us</h4>
                            <p>cis.dean@bsu.edu.ph<br>admissions@bsu.edu.ph</p>
                        </div>
                    </div>

                    <div class="social-connect">
                        <h4>Follow our Updates</h4>
                        <div class="social-icons">
                            <a href="#"><i class="fab fa-facebook-f"></i></a>
                            <a href="#"><i class="fab fa-twitter"></i></a>
                            <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <?php require_once 'components/footer.php'; ?>
    </main>

</body>
</html>