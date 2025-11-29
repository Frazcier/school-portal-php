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

    const themeBtn = document.getElementById('theme-toggle');
    const themeIcon = themeBtn ? themeBtn.querySelector('i') : null;

    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        document.documentElement.setAttribute('data-theme', 'dark');
        if(themeIcon) themeIcon.className = 'fas fa-sun';
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

    const progButtons = document.querySelectorAll('.prog-tab-btn');
    const progContents = document.querySelectorAll('.program-content');

    progButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            progButtons.forEach(b => b.classList.remove('active'));
            progContents.forEach(c => c.classList.remove('active'));

            btn.classList.add('active');

            const targetId = btn.getAttribute('data-target');
            document.getElementById(targetId).classList.add('active');
        });
    });

    const yearButtons = document.querySelectorAll('.year-btn');
    
    yearButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const parentContent = this.closest('.program-content');

            parentContent.querySelectorAll('.year-btn').forEach(b => b.classList.remove('active'));
            parentContent.querySelectorAll('.subject-group').forEach(g => g.classList.remove('active'));

            this.classList.add('active');

            const targetGrid = this.getAttribute('data-year');
            parentContent.querySelector('#' + targetGrid).classList.add('active');
        });
    });

    const mobileBtn = document.getElementById('mobile-menu-btn');
    const nav = document.querySelector('nav');

    if (mobileBtn && nav) {
        mobileBtn.addEventListener('click', () => {
            nav.classList.toggle('mobile-active');
        });
    }
});

function openCurriculum(progId) {
    const modal = document.getElementById('curriculum-modal');

    document.querySelectorAll('.program-detail').forEach(el => {
        el.style.display = 'none';
    });

    const target = document.getElementById(progId + '-detail');

    if(target) {
        target.style.display = 'block';
        modal.classList.add('active');
        document.body.classList.add('no-scroll');
    }
}

function closeCurriculum() {
    const modal = document.getElementById('curriculum-modal');
    modal.classList.remove('active');
    document.body.classList.remove('no-scroll');
}

window.onclick = function(event) {
    const modal = document.getElementById('curriculum-modal');
    if (event.target == modal) {
        closeCurriculum();
    }
}

function switchYear(progId, yearId) {
    const container = document.getElementById(progId + '-detail');
    
    const tabs = container.querySelectorAll('.year-tab');
    tabs.forEach(tab => tab.classList.remove('active'));
    
    event.target.classList.add('active');

    const lists = container.querySelectorAll('.subject-list');
    lists.forEach(list => list.classList.remove('active'));
    
    const targetList = document.getElementById(progId + '-' + yearId);
    if(targetList) {
        targetList.classList.add('active');
    }
}