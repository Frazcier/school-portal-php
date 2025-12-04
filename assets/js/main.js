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

document.addEventListener('DOMContentLoaded', () => {
    const mobileBtn = document.getElementById('mobile-menu-btn');
    const sidebar = document.querySelector('.sidebar');

    if (mobileBtn && sidebar) {
        let overlay = document.querySelector('.sidebar-overlay');

        if (!overlay) {
            overlay = document.createElement('div');
            overlay.className = 'sidebar-overlay';
            document.body.appendChild(overlay);
        };

        const toggleMenu = (e) => {
            if (e) e.stopPropagation();
            sidebar.classList.toggle('active');
            overlay.classList.toggle('active');

            if (sidebar.classList.contains('active')) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        };

        mobileBtn.addEventListener('click', toggleMenu);
        overlay.addEventListener('click', toggleMenu);

        sidebar.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
                document.body.style.overflow = '';
            });
        });
    }
});

function searchTable() {
    const input = document.getElementById("realTimeSearch");
    const table = document.getElementById("subjectsTable");
    const noResultDiv = document.getElementById("noResultsMsg");
    
    if (!input || !table) return;

    const filter = input.value.toLowerCase();
    const rows = table.getElementsByTagName("tr");
    let hasMatch = false;

    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        if(row.innerText.includes("No subjects found")) continue; 

        const textContent = row.textContent || row.innerText;

        if (textContent.toLowerCase().indexOf(filter) > -1) {
            row.style.display = ""; 
            hasMatch = true;
        } else {
            row.style.display = "none"; 
        }
    }

    if (noResultDiv) {
        if (!hasMatch) {
            noResultDiv.style.display = "block";
            table.style.display = "none";
        } else {
            noResultDiv.style.display = "none";
            table.style.display = "table";
        }
    }
}

let currentLogOffset = 5;

function loadMoreLogs() {
    const btn = document.getElementById('loadMoreBtn');
    const tableBody = document.querySelector('#activityTable tbody');
    
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
    btn.disabled = true;

    fetch(`../../backend/controller.php?method_finder=get_more_logs&offset=${currentLogOffset}`)
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                data.forEach(log => {
                    const pic = log.profile_picture || '../../assets/img/profile-pictures/profile.svg';
                    const name = (log.first_name && log.last_name) ? `${log.first_name} ${log.last_name}` : 'Unknown User';
                    
                    let badgeClass = 'badge-soft blue';
                    if (log.action_type.includes('Delete')) badgeClass = 'badge-soft orange';
                    if (log.action_type.includes('Upload')) badgeClass = 'badge-soft green';
                    if (log.action_type.includes('Announcement')) badgeClass = 'badge-soft purple';
                    if (log.action_type.includes('Security')) badgeClass = 'badge-soft red';

                    const dateObj = new Date(log.created_at);
                    const dateStr = dateObj.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                    const timeStr = dateObj.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });

                    const row = `
                        <tr class="fadeIn">
                            <td><span class="${badgeClass}">${log.action_type}</span></td>
                            <td>
                                <div class="user-cell">
                                    <img src="${pic}" alt="User">
                                    <span class="name">${name}</span>
                                </div>
                            </td>
                            <td>${log.description}</td>
                            <td>${dateStr} • ${timeStr}</td>
                            <td><span class="status-pill active">Completed</span></td>
                        </tr>
                    `;
                    tableBody.insertAdjacentHTML('beforeend', row);
                });

                currentLogOffset += data.length;
                btn.innerHTML = originalText;
                btn.disabled = false;
            } else {
                btn.innerHTML = 'No More Logs';
                btn.style.display = 'none';
            }
        })
        .catch(error => {
            console.error('Error fetching logs:', error);
            btn.innerHTML = 'Error Loading';
        });
}

let subjectOffset = 3;

function loadMoreSubjects() {
    const btn = document.getElementById('loadSubjectsBtn');
    const container = document.getElementById('subjectGrid');
    
    if (!btn || !container) return;

    const originalText = btn.innerText;
    btn.innerText = 'Loading...';
    btn.disabled = true;

    fetch(`../../backend/controller.php?method_finder=get_more_subjects&offset=${subjectOffset}`)
        .then(response => response.json())
        .then(data => {
            const subjects = data.subjects;
            
            if (subjects.length > 0) {
                subjects.forEach(sub => {
                    const instructor = (sub.first_name && sub.last_name) ? `${sub.first_name} ${sub.last_name}` : 'TBA';
                    const day = sub.schedule_day || 'TBA';
                    const time = sub.schedule_time || 'TBA';
                    const room = sub.room || 'TBA';

                    const html = `
                    <div class="subject fadeIn">
                        <div class="subject-header">
                            <p>${sub.subject_description} -</p>
                            <p>${sub.subject_code}</p>
                        </div>
                        <div class="divider"></div>
                        <div class="subject-content">
                            <div class="item">
                                <img class="icon" src="../../assets/img/icons/profile-icon.svg" alt="Icon">
                                <p>${instructor}</p>
                            </div>
                            <div class="item">
                                <img class="icon" src="../../assets/img/icons/calendar-icon.svg" alt="Icon">
                                <p>${day}</p>
                            </div>
                            <div class="item">
                                <img class="icon" src="../../assets/img/icons/time-icon.svg" alt="Icon">
                                <p>${time}</p>
                            </div>
                            <div class="item">
                                <img class="icon" src="../../assets/img/icons/location-icon.svg" alt="Icon">
                                <p>${room}</p>
                            </div>
                        </div>
                    </div>
                    `;
                    container.insertAdjacentHTML('beforeend', html);
                });

                subjectOffset += subjects.length;
                btn.innerText = originalText;
                btn.disabled = false;

                if (data.remaining <= 0) {
                    btn.style.display = 'none';
                }
            } else {
                btn.style.display = 'none';
            }
        })
        .catch(err => {
            console.error(err);
            btn.innerText = 'Error';
        });
}

function searchResourceTable() {
    const input = document.getElementById("realTimeSearch");
    const table = document.getElementById("resourcesTable");
    const noResultDiv = document.getElementById("noResultsMsg");
    
    if (!input || !table) return;

    const filter = input.value.toLowerCase();
    const rows = table.getElementsByTagName("tr");
    let hasMatch = false;

    for (let i = 1; i < rows.length; i++) {
        const row = rows[i];
        if(row.innerText.includes("No resources found")) continue; 

        const textContent = row.textContent || row.innerText;

        if (textContent.toLowerCase().indexOf(filter) > -1) {
            row.style.display = ""; 
            hasMatch = true;
        } else {
            row.style.display = "none"; 
        }
    }

    if (noResultDiv) {
        noResultDiv.style.display = hasMatch ? "none" : "block";
        table.style.display = hasMatch ? "table" : "none";
    }
}

function searchAllTables() {
    const input = document.getElementById("realTimeSearch");
    if (!input) return;
    
    const filter = input.value.toLowerCase();
    const tables = document.querySelectorAll(".searchable-table"); 

    tables.forEach(table => {
        const rows = table.getElementsByTagName("tr");
        
        for (let i = 1; i < rows.length; i++) {
            const row = rows[i];
            const textContent = row.textContent || row.innerText;
            
            if (textContent.toLowerCase().indexOf(filter) > -1) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        }
    });
}

function saveTab(tabId) {
    localStorage.setItem('activeUserTab', tabId);
}

document.addEventListener("DOMContentLoaded", function() {
    const activeTab = localStorage.getItem('activeUserTab');
    if (activeTab) {
        const tabInput = document.getElementById(activeTab);
        if (tabInput) tabInput.checked = true;
    }
});

function searchCards() {
    const input = document.getElementById("realTimeSearch");
    const grid = document.getElementById("resourceGrid");
    const noResultDiv = document.getElementById("noResultsMsg");
    
    if (!input || !grid) return;

    const cards = grid.getElementsByClassName("subject-card");
    let hasMatch = false;

    const filter = input.value.toLowerCase();

    for (let i = 0; i < cards.length; i++) {
        const card = cards[i];
        const textContent = card.innerText || card.textContent;

        if (textContent.toLowerCase().indexOf(filter) > -1) {
            card.style.display = "flex";
            hasMatch = true;
        } else {
            card.style.display = "none"; 
        }
    }

    if (noResultDiv) {
        noResultDiv.style.display = hasMatch ? "none" : "block";
    }
}