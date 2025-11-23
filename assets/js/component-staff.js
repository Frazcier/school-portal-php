function loadSplash() {
    // 1. Check if we have already shown the extravagant splash
    if (sessionStorage.getItem('splashShown')) {
        
        // --- MODE B: SIMPLE BLUR (Already logged in) ---
        const simpleOverlay = document.createElement('div');
        simpleOverlay.className = 'simple-fade-overlay';
        document.body.appendChild(simpleOverlay);
        
        // Remove after animation
        setTimeout(() => {
            simpleOverlay.remove();
        }, 600); // Matches CSS animation time

    } else {
        // --- MODE A: EXTRAVAGANT SPLASH (First Login) ---
        
        // Mark session as "seen" IMMEDIATELY so refreshes trigger Mode B
        sessionStorage.setItem('splashShown', 'true');

        fetch('../../components/splash.html')
            .then(response => response.text())
            .then(data => {
                document.body.insertAdjacentHTML('afterbegin', data);
                const splash = document.getElementById('splash-overlay');
                
                // Wait for the loading bar animation (2.5s)
                setTimeout(() => {
                    if(splash) {
                        splash.classList.add('hide'); // Slide up
                        setTimeout(() => splash.remove(), 800);
                    }
                }, 2500); 
            })
            .catch(err => console.error('Splash error:', err));
    }
}


loadSplash();


// --- EXISTING COMPONENT LOADER ---
document.addEventListener("DOMContentLoaded", function() {
    loadSplash();
});

// --- 1. Sidebar Toggle Logic ---
function toggleSidebarMenu() {
    const menu = document.getElementById('sidebar-user-menu');
    if (menu) {
        menu.classList.toggle('show');
    }
}

// --- 2. Close Menu When Clicking Outside ---
document.addEventListener('click', function(event) {
    const menu = document.getElementById('sidebar-user-menu');
    const btn = document.querySelector('.profile-menu-btn');

    // If menu is open...
    if (menu && menu.classList.contains('show')) {
        // ...and the click was NOT inside the menu AND NOT on the toggle button
        if (!menu.contains(event.target) && !btn.contains(event.target)) {
            menu.classList.remove('show');
        }
    }
});