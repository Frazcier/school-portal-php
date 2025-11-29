function manageSplash() {
    const splash = document.getElementById('splash-overlay');

    if (!splash) return;

    if (sessionStorage.getItem('splashShown')) {
        splash.style.display = 'none';
        splash.remove();

    } else {
        document.body.classList.add('no-scroll');

        sessionStorage.setItem('splashShown', 'true');

        setTimeout(() => {
            splash.classList.add('hide');

            setTimeout(() => {
                splash.remove();
                document.body.classList.remove('no-scroll');
            }, 800)
        }, 3500)
    }
}

manageSplash();

function toggleSidebarMenu() {
    const menu = document.getElementById('sidebar-user-menu');

    if (menu) {
        menu.classList.toggle('show');
    }
}

document.addEventListener('click', function(event) {
    const menu = document.getElementById('sidebar-user-menu');
    const btn = document.querySelector('.profile-menu-btn');

    if (menu && menu.classList.contains('show')) {
        if (!menu.contains(event.target) && !btn.contains(event.target)) {
            menu.classList.remove('show');
        }
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const alertBox = document.querySelector('.alert');
    if (alertBox) {
        setTimeout(() => {
            alertBox.style.transition = 'opacity 0.5s ease';
            alertBox.style.opacity = '0';
            
            setTimeout(() => {
                alertBox.remove();
            }, 500);
        }, 3000);
    }
});

document.addEventListener("DOMContentLoaded", () => {
    
    const overlay = document.getElementById('transition-overlay');
    if (overlay) {
        
        setTimeout(() => {
            document.body.classList.add('page-loaded');
        }, 100);
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
    }

    const appearanceBtn = document.getElementById('dark-mode-toggle');

    if (appearanceBtn) {
        appearanceBtn.addEventListener('click', (e) => {
            e.preventDefault();

            if (document.documentElement.getAttribute('data-theme') === 'dark') {
                document.documentElement.removeAttribute('data-theme');
                localStorage.setItem('theme', 'light');
            } else {
                document.documentElement.setAttribute('data-theme', 'dark');
                localStorage.setItem('theme', 'dark');
            }
        });
    }
});