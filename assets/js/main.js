function manageSplash() {
    const splash = document.getElementById('splash-overlay');

    if (!splash) return;

    if (sessionStorage.getItem('splashShown')) {
        splash.remove();
        
        const simpleOverlay = document.createElement('div');
        simpleOverlay.className = 'simple-fade-overlay';
        document.body.appendChild(simpleOverlay);

        setTimeout(() => {
            simpleOverlay.remove();
        }, 600);

    } else {
        sessionStorage.setItem('splashShown', 'true');

        setTimeout(() => {
            splash.classList.add('hide');

            setTimeout(() => {
                splash.remove();
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
        }, 5000);
    }
});