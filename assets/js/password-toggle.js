
document.addEventListener('DOMContentLoaded', () => {
    const eyeOpenPath = "../../assets/img/icons/eye-icon.svg";
    const eyeClosedPath = "../../assets/img/icons/eye-off-icon.svg";

    const toggleButtons = document.querySelectorAll('.toggle-password');

    toggleButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const container = this.parentElement;
            const input = container.querySelector('input');

            if (input) {
                const isPassword = input.getAttribute('type') === 'password';
                input.setAttribute('type', isPassword ? 'text' : 'password');
                
                if (isPassword) {
                    this.setAttribute('src', eyeOpenPath);
                } else {
                    this.setAttribute('src', eyeClosedPath);
                }
            }
        });
    });
});