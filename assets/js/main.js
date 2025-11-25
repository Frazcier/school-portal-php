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
        }, 3500);
    }
});

document.addEventListener("DOMContentLoaded", () => {
    
    const overlay = document.getElementById('transition-overlay');
    if (overlay) {
        // Small delay to ensure the browser is ready to animate
        setTimeout(() => {
            document.body.classList.add('page-loaded');
        }, 100);
    }
});