<?php 
session_start(); 

$return_url = 'index.php'; 

if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'student') {
        $return_url = 'pages/student/student-dashboard.php';
    } elseif ($_SESSION['role'] === 'teacher' || $_SESSION['role'] === 'admin') {
        $return_url = 'pages/staff/staff-dashboard.php';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <base href="/School_Portal/"> 

    <link rel="icon" type="image/x-icon" href="assets/img/logo/logo.ico">
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="assets/css/error.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script src="assets/js/main.js" defer></script>
    <script src="assets/js/landing.js" defer></script>

    <title>Page Not Found</title>
</head>
<body>
    
    <?php require_once 'components/public-header.php'; ?>

    <main class="error-main">
        
        <div class="bg-overlay"></div>

        <div class="debris-field">
            <img src="assets/img/icons/file-icon.svg" class="debris d1" alt="">
            <img src="assets/img/icons/search-icon.svg" class="debris d2" alt="">
            <img src="assets/img/icons/degree-icon.svg" class="debris d3" alt="">
            <img src="assets/img/icons/wifi-icon.svg" class="debris d4" alt="">
            <div class="debris d5"><i class="fas fa-question"></i></div>
        </div>

        <div class="error-container">
            
            <div class="visual-wrapper">
                <div class="shockwave"></div>
                <h1 class="glitch-text" data-text="404">404</h1>

                <div class="blob-system">
                    <div class="trail-wrapper t1"><div class="blob-carrier c1"><div class="blob b1"></div></div></div>
                    <div class="trail-wrapper t2"><div class="blob-carrier c2"><div class="blob b2"></div></div></div>
                    <div class="trail-wrapper t3"><div class="blob-carrier c3"><div class="blob b3"></div></div></div>
                    <div class="trail-wrapper t4"><div class="blob-carrier c4"><div class="blob b4"></div></div></div>
                    <div class="trail-wrapper t5"><div class="blob-carrier c5"><div class="blob b5"></div></div></div>
                    <div class="trail-wrapper t6"><div class="blob-carrier c6"><div class="blob b6"></div></div></div>
                    <div class="trail-wrapper t7"><div class="blob-carrier c7"><div class="blob b7"></div></div></div>
                    <div class="trail-wrapper t8"><div class="blob-carrier c8"><div class="blob b8"></div></div></div>
                </div>
            </div>

            <div class="text-content reveal active">
                <h2>Lost in the <span class="gradient-text">Digital Void?</span></h2>
                <p>The page you are looking for seems to have vanished into the ether. It might have been moved, deleted, or never existed in this dimension.</p>
                
                <div class="action-buttons">
                    <a href="<?php echo $return_url; ?>" onclick="handleExit(event, 'back')" class="button primary-btn pulse">
                        <i class="fas fa-arrow-left"></i> Go Back
                    </a>
                    <a href="contact.php" onclick="handleExit(event, 'contact.php')" class="button secondary-btn">
                        <i class="fas fa-headset"></i> Report Problem
                    </a>
                </div>
            </div>

        </div>
    </main>

    <?php require_once 'components/footer.php'; ?>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            setTimeout(() => {
                document.body.classList.add('page-loaded');
            }, 50);
        });

        function handleExit(e, destination) {
            e.preventDefault(); 
            document.body.classList.add('page-exiting');
            const fallbackUrl = e.currentTarget.href; 

            setTimeout(() => {
                if (destination === 'back') {
                    if (window.history.length > 1 && document.referrer.indexOf(window.location.hostname) !== -1) {
                        window.history.back();
                    } else {
                        window.location.href = fallbackUrl;
                    }
                } else {
                    window.location.href = destination;
                }
            }, 400); 
        }
    </script>
</body>
</html>