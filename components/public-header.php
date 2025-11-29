<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<header>
    <div class="nav-container">
        <div class="logo-section">
            <img src="assets/img/logo/logo.png" alt="CIS Logo" class="nav-logo">
            <div class="logo-text">Ikinamada Designs</div>
        </div>

        <div class="menu-toggle" id="mobile-menu-btn">
            <i class="fas fa-bars"></i>
        </div>

        <nav>
            <ul>
                <li>
                    <a href="index.php" class="<?= ($current_page == 'index.php') ? 'active-link' : '' ?>">Home</a>
                </li>
                <li>
                    <a href="about.php" class="<?= ($current_page == 'about.php') ? 'active-link' : '' ?>">About</a>
                </li>
                <li>
                    <a href="contact.php" class="<?= ($current_page == 'contact.php') ? 'active-link' : '' ?>">Contact</a>
                </li>
                <li>
                    <button id="theme-toggle" class="theme-toggle-btn" title="Toggle Dark Mode">
                        <i class="fas fa-moon"></i>
                    </button>
                </li>
                <li>
                    <a class="button login-btn" href="pages/auth/login.php">Portal Login</a>
                </li>
            </ul>
        </nav>
    </div>
</header>