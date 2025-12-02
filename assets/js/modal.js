function openViewModal() {
    const modal = document.getElementById('view-modal');
    const displayImg = document.getElementById('current-avatar-display');
    const enlargedImg = document.getElementById('enlarged-image');
    
    if(modal && displayImg && enlargedImg) {
        enlargedImg.src = displayImg.src;
        modal.classList.add('active');
        document.body.classList.add('no-scroll')
    }
}

function openSelectorModal() {
    const modal = document.getElementById('selector-modal');
    if(modal) {
        modal.classList.add('active');
        document.body.classList.add('no-scroll')
    }
}

function closeModals(e) {
    if (e.target.classList.contains('modal-overlay') || e.target.classList.contains('close-btn')) {
        document.querySelectorAll('.modal-overlay').forEach(el => el.classList.remove('active'));
        document.body.classList.add('no-scroll')
    }
}

function selectAvatar(path) {
    const hiddenInput = document.getElementById('selected-avatar-input');
    const displayImg = document.getElementById('current-avatar-display');
    const modal = document.getElementById('selector-modal');

    if(hiddenInput && displayImg) {
        hiddenInput.value = path;
        displayImg.src = path;
        
        document.querySelectorAll('.avatar-option').forEach(img => {
            img.classList.remove('selected');

            if(img.getAttribute('src') === path) {
                img.classList.add('selected');
            }
        });

        if(modal) {
            modal.classList.remove('active');
        } else {
            document.body.classList.add('no-scroll')
        }
        
    } else {
        console.error("Error: Could not find input or display image elements.");
    }
}

function openImportModal() {
    document.getElementById('import-modal').classList.add('active');
}

function openSubjectModal() {
    const modal = document.getElementById('subject-modal');
    if(modal) {
        modal.classList.add('active');
        document.body.classList.add('no-scroll');
    }
}

function openSubjectViewModal(data) {
    const modal = document.getElementById('view-subject-modal'); // Renamed ID to avoid conflict
    if(!modal) return;

    const subject = JSON.parse(data);
    
    if(document.getElementById('view-code')) document.getElementById('view-code').textContent = subject.subject_code;
    if(document.getElementById('view-desc')) document.getElementById('view-desc').textContent = subject.subject_description;
    if(document.getElementById('view-units')) document.getElementById('view-units').textContent = subject.units + ' Units';
    if(document.getElementById('view-section')) document.getElementById('view-section').textContent = subject.section_assigned;
    if(document.getElementById('view-room')) document.getElementById('view-room').textContent = subject.room || 'TBA';
    if(document.getElementById('view-day')) document.getElementById('view-day').textContent = subject.schedule_day || 'TBA';
    if(document.getElementById('view-time')) document.getElementById('view-time').textContent = subject.schedule_time || 'TBA';

    modal.classList.add('active');
    document.body.classList.add('no-scroll');
}

function openSubjectEditModal(data) {
    const modal = document.getElementById('edit-modal');
    if(!modal) return;

    const subject = JSON.parse(data);

    if(document.getElementById('edit-id')) document.getElementById('edit-id').value = subject.subject_id;
    if(document.getElementById('edit-code')) document.getElementById('edit-code').value = subject.subject_code;
    if(document.getElementById('edit-desc')) document.getElementById('edit-desc').value = subject.subject_description;
    if(document.getElementById('edit-units')) document.getElementById('edit-units').value = subject.units;
    if(document.getElementById('edit-instructor')) document.getElementById('edit-instructor').value = subject.instructor_id;
    if(document.getElementById('edit-time')) document.getElementById('edit-time').value = subject.schedule_time;
    if(document.getElementById('edit-section')) document.getElementById('edit-section').value = subject.section_assigned;
    if(document.getElementById('edit-day')) document.getElementById('edit-day').value = subject.schedule_day;
    if(document.getElementById('edit-room')) document.getElementById('edit-room').value = subject.room;

    modal.classList.add('active');
    document.body.classList.add('no-scroll');
}

function openAnnouncementEditModal(data) {
    const modal = document.getElementById('edit-announcement-modal');
    if(!modal) return;

    const ann = JSON.parse(data);

    document.getElementById('edit-ann-id').value = ann.announcement_id;
    document.getElementById('edit-ann-title').value = ann.title;
    document.getElementById('edit-ann-content').value = ann.content;
    document.getElementById('edit-ann-status').value = ann.status;

    modal.classList.add('active');
    document.body.classList.add('no-scroll');
}

function openAnnouncementModal() {
    document.getElementById('announcement-modal').classList.add('active');
    document.body.classList.add('no-scroll');
}

function openUserModal() {
    const modal = document.getElementById('user-modal');
    if (modal) {
        modal.classList.add('active');
        document.body.classList.add('no-scroll');
    }
}

function openEditStudentModal(data) {
    const modal = document.getElementById('edit-student-modal');
    if (!modal) return;

    const student = JSON.parse(data);
    
    if(document.getElementById('edit-user-id')) document.getElementById('edit-user-id').value = student.id;
    if(document.getElementById('edit-student-name')) document.getElementById('edit-student-name').value = student.name;
    if(document.getElementById('edit-student-section')) document.getElementById('edit-student-section').value = student.section || "N/A";

    modal.classList.add('active');
    document.body.classList.add('no-scroll');
}

function toggleUserFields() {
    const roleSelect = document.getElementById('roleSelect');
    const stuFields = document.getElementById('student-fields');
    const staffFields = document.getElementById('staff-fields');

    if (roleSelect && stuFields && staffFields) {
        const role = roleSelect.value;
        if (role === 'student') {
            stuFields.style.display = 'block';
            staffFields.style.display = 'none';
        } else {
            stuFields.style.display = 'none';
            staffFields.style.display = 'block';
        }
    }
}