function showModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
        document.body.classList.add('no-scroll');
    } else {
        console.warn(`Modal with ID '${modalId}' not found.`);
    }
}

function closeModals(e) {
    if (e.target.classList.contains('modal-overlay') || e.target.classList.contains('close-btn')) {
        document.querySelectorAll('.modal-overlay').forEach(el => el.classList.remove('active'));
        document.body.classList.remove('no-scroll');
    }
}

const openSelectorModal = () => showModal('selector-modal');
const openImportModal   = () => showModal('import-modal');
const openSubjectModal  = () => showModal('subject-modal');
const openAnnouncementModal = () => showModal('announcement-modal');
const openUserModal     = () => showModal('user-modal');


function openViewModal() {
    const displayImg = document.getElementById('current-avatar-display');
    const enlargedImg = document.getElementById('enlarged-image');
    
    if (displayImg && enlargedImg) {
        enlargedImg.src = displayImg.src;
        showModal('view-modal');
    }
}

function openSubjectViewModal(data) {
    const subject = JSON.parse(data);
    
    const fields = {
        'view-code': subject.subject_code,
        'view-desc': subject.subject_description,
        'view-units': subject.units + ' Units',
        'view-section': subject.section_assigned,
        'view-room': subject.room || 'TBA',
        'view-day': subject.schedule_day || 'TBA',
        'view-time': subject.schedule_time || 'TBA'
    };

    for (const [id, value] of Object.entries(fields)) {
        const el = document.getElementById(id);
        if (el) el.textContent = value;
    }

    showModal('view-subject-modal');
}

function openSubjectEditModal(data) {
    const subject = JSON.parse(data);

    const fields = {
        'edit-id': subject.subject_id,
        'edit-code': subject.subject_code,
        'edit-desc': subject.subject_description,
        'edit-units': subject.units,
        'edit-instructor': subject.instructor_id,
        'edit-time': subject.schedule_time,
        'edit-section': subject.section_assigned,
        'edit-day': subject.schedule_day,
        'edit-room': subject.room
    };

    for (const [id, value] of Object.entries(fields)) {
        const el = document.getElementById(id);
        if (el) el.value = value;
    }

    showModal('edit-modal');
}

function openAnnouncementEditModal(data) {
    const ann = JSON.parse(data);

    const fields = {
        'edit-ann-id': ann.announcement_id,
        'edit-ann-title': ann.title,
        'edit-ann-content': ann.content,
        'edit-ann-status': ann.status
    };

    for (const [id, value] of Object.entries(fields)) {
        const el = document.getElementById(id);
        if (el) el.value = value;
    }

    showModal('edit-announcement-modal');
}

function openEditStudentModal(data) {
    const student = JSON.parse(data);
    
    const fields = {
        'edit-user-id': student.id,
        'edit-student-name': student.name,
        'edit-student-section': student.section || "N/A"
    };

    for (const [id, value] of Object.entries(fields)) {
        const el = document.getElementById(id);
        if (el) el.value = value;
    }

    showModal('edit-student-modal');
}

function selectAvatar(path) {
    const hiddenInput = document.getElementById('selected-avatar-input');
    const displayImg = document.getElementById('current-avatar-display');
    const modal = document.getElementById('selector-modal');

    if (hiddenInput && displayImg) {
        hiddenInput.value = path;
        displayImg.src = path;
        
        document.querySelectorAll('.avatar-option').forEach(img => {
            img.classList.remove('selected');
            if (img.getAttribute('src') === path) {
                img.classList.add('selected');
            }
        });

        if (modal) {
            modal.classList.remove('active');
            document.body.classList.remove('no-scroll');
        }
    } else {
        console.error("Error: Input or Display Image not found.");
    }
}

function toggleUserFields() {
    const roleSelect = document.getElementById('roleSelect');
    const stuFields = document.getElementById('student-fields');
    const staffFields = document.getElementById('staff-fields');

    if (roleSelect && stuFields && staffFields) {
        const isStudent = roleSelect.value === 'student';
        stuFields.style.display = isStudent ? 'block' : 'none';
        staffFields.style.display = isStudent ? 'none' : 'block';
    }
}