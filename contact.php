<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="icon" type="image/x-icon" href="assets/img/logo/logo.ico">    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="assets/js/landing.js" defer></script>
    <title>Contact Us</title>
</head>
<body>
    <div id="backToTop" class="back-to-top">
        <i class="fas fa-arrow-up"></i>
    </div>
    
    <?php require_once 'components/public-header.php'; ?>

    <main>
        <section class="page-hero contact-hero">
            <div class="hero-bg-shape shape-1"></div>
            <div class="hero-content center-text reveal">
                <span class="badge">Get in Touch</span>
                <h1>We'd Love to <span class="gradient-text">Hear From You</span></h1>
                <p>Have questions about enrollment, programs, or student services? Reach out to us.</p>
            </div>
        </section>

        <section class="contact-section reveal">
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

                    <div class="faq-section">
                        <h3>Frequently Asked Questions</h3>

                        <div class="faq-item">
                            <button class="faq-question">How do I reset my portal password?</button>
                            <div class="faq-answer">
                                <p>Go to the login page and click "Forgot Password". A reset link will be sent to your registered BSU email address.</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <button class="faq-question">When is the enrollment period?</button>
                            <div class="faq-answer">
                                <p>Bruhhh HAHAHAHHA</p>
                            </div>
                        </div>

                        <div class="faq-item">
                            <button class="faq-question">Where is the Dean's Office located?</button>
                            <div class="faq-answer">
                                <p>The CIS Dean's Office is located on the 2nd Floor of the College of Information Sciences building, Rm. 201.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="contact-info reveal-delay-2">
                    <div class="map-container ">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3826.664669672465!2d120.58987131486333!3d16.44182998865171!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3391a3c056770679%3A0x6b3060a227233261!2sBenguet%20State%20University!5e0!3m2!1sen!2sph!4v1625551234567!5m2!1sen!2sph" allowfullscreen="" loading="lazy"></iframe>
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