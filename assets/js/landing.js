document.addEventListener("DOMContentLoaded", () => {
    const revealElements = document.querySelectorAll('.reveal');
    const revealObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('active');
                observer.unobserve(entry.target);
            }
        });
    }, {
        root: null,
        threshold: 0.15,
        rootMargin: "0px 0px -50px 0px"
    });

    revealElements.forEach(el => revealObserver.observe(el));

    const faqQuestions = document.querySelectorAll('.faq-question');

    faqQuestions.forEach(question => {
        question.addEventListener('click', () => {
            const answer = question.nextElementSibling;

            question.classList.toggle('open');

            if (question.classList.contains('open')) {
                answer.style.maxHeight = answer.scrollHeight + "px";
            } else {
                answer.style.maxHeight = null;
            }

            faqQuestions.forEach(otherQuestion => {
                if (otherQuestion !== question && otherQuestion.classList.contains('open')) {
                    otherQuestion.classList.remove('open');
                    otherQuestion.nextElementSibling.style.maxHeight = null;
                }
            });
        });
    });
});

document.addEventListener('DOMContentLoaded', () => {
    
    /* --- 1. DARK MODE TOGGLE LOGIC --- */
    const themeBtn = document.getElementById('theme-toggle');
    const themeIcon = themeBtn ? themeBtn.querySelector('i') : null;
    
    // Check saved theme on load
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
        if(themeIcon) themeIcon.className = 'fas fa-sun'; // Switch to Sun icon
    }

    if (themeBtn) {
        themeBtn.addEventListener('click', (e) => {
            e.preventDefault();
            const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
            
            if (isDark) {
                document.documentElement.removeAttribute('data-theme');
                localStorage.setItem('theme', 'light');
                themeIcon.className = 'fas fa-moon';
            } else {
                document.documentElement.setAttribute('data-theme', 'dark');
                localStorage.setItem('theme', 'dark');
                themeIcon.className = 'fas fa-sun';
            }
        });
    }

    /* --- 2. BACK TO TOP BUTTON LOGIC --- */
    const backToTopBtn = document.getElementById('backToTop');

    if (backToTopBtn) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                backToTopBtn.classList.add('show');
            } else {
                backToTopBtn.classList.remove('show');
            }
        });

        backToTopBtn.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }

/* --- 3. CURRICULUM TABS LOGIC --- */
    // Main Program Switching
    const progButtons = document.querySelectorAll('.prog-tab-btn');
    const progContents = document.querySelectorAll('.program-content');

    progButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            // Remove active from all buttons and contents
            progButtons.forEach(b => b.classList.remove('active'));
            progContents.forEach(c => c.classList.remove('active'));

            // Add active to clicked button
            btn.classList.add('active');

            // Show corresponding content
            const targetId = btn.getAttribute('data-target');
            document.getElementById(targetId).classList.add('active');
        });
    });

    // Year Level Switching (Delegated event for multiple groups)
    const yearButtons = document.querySelectorAll('.year-btn');
    
    yearButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            // Find the parent container (the specific program content)
            const parentContent = this.closest('.program-content');
            
            // Remove active from buttons IN THIS CONTAINER only
            parentContent.querySelectorAll('.year-btn').forEach(b => b.classList.remove('active'));
            parentContent.querySelectorAll('.subject-group').forEach(g => g.classList.remove('active'));

            // Activate clicked button
            this.classList.add('active');

            // Activate target grid
            const targetGrid = this.getAttribute('data-year');
            parentContent.querySelector('#' + targetGrid).classList.add('active');
        });
    });

    // --- MOBILE MENU TOGGLE ---
    const mobileBtn = document.getElementById('mobile-menu-btn');
    const nav = document.querySelector('nav');

    if (mobileBtn && nav) {
        mobileBtn.addEventListener('click', () => {
            nav.classList.toggle('mobile-active');
            
            // Optional: Animate hamburger icon
            const icon = mobileBtn.querySelector('img');
            if(nav.classList.contains('mobile-active')){
               // You can swap the icon src here if you have a 'close' icon
               // icon.src = 'assets/img/icons/close-icon.svg'; 
            } else {
               // icon.src = 'assets/img/icons/burger-menu-icon.svg';
            }
        });
    }
});

/* --- CURRICULUM MODAL LOGIC --- */

function openCurriculum(progId) {
    const modal = document.getElementById('curriculum-modal');
    
    // Hide all program details first
    document.querySelectorAll('.program-detail').forEach(el => {
        el.style.display = 'none';
    });

    // Show the selected program detail
    const target = document.getElementById(progId + '-detail');
    if(target) {
        target.style.display = 'block';
        modal.classList.add('active');
        document.body.classList.add('no-scroll'); // Prevent background scrolling
    }
}

function closeCurriculum() {
    const modal = document.getElementById('curriculum-modal');
    modal.classList.remove('active');
    document.body.classList.remove('no-scroll');
}

// Close when clicking outside the box
window.onclick = function(event) {
    const modal = document.getElementById('curriculum-modal');
    if (event.target == modal) {
        closeCurriculum();
    }
}

function switchYear(progId, yearId) {
    const container = document.getElementById(progId + '-detail');
    
    // Update Tab Buttons
    const tabs = container.querySelectorAll('.year-tab');
    tabs.forEach(tab => tab.classList.remove('active'));
    // (In a real scenario you'd target the specific clicked button more robustly, 
    // but relying on event.target in the inline onclick is simplest for PHP mix)
    event.target.classList.add('active');

    // Update Subject Lists
    const lists = container.querySelectorAll('.subject-list');
    lists.forEach(list => list.classList.remove('active'));
    
    const targetList = document.getElementById(progId + '-' + yearId);
    if(targetList) {
        targetList.classList.add('active');
    }
}