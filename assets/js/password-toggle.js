// assets/js/password-toggle.js

document.addEventListener('DOMContentLoaded', () => {
    // Define the paths to your icons
    // Note: These paths are relative to the PAGE where the script is run (e.g., pages/auth/login.php)
    const eyeOpenPath = "../../assets/img/icons/eye-icon.svg";
    const eyeClosedPath = "../../assets/img/icons/eye-off-icon.svg";

    const toggleButtons = document.querySelectorAll('.toggle-password');

    toggleButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const container = this.parentElement;
            const input = container.querySelector('input');

            if (input) {
                // 1. Toggle Password Visibility
                const isPassword = input.getAttribute('type') === 'password';
                input.setAttribute('type', isPassword ? 'text' : 'password');

                // 2. Toggle Icon Image Source
                // If it was password (hidden), we are now showing text -> use Open Eye
                // If it was text (shown), we are now hiding it -> use Closed Eye
                // Note: Logic depends on which icon you want for "Show". 
                // Standard: Closed Eye = Hidden, Open Eye = Visible.
                
                if (isPassword) {
                    this.setAttribute('src', eyeOpenPath);
                } else {
                    this.setAttribute('src', eyeClosedPath);
                }
            }
        });
    });
});