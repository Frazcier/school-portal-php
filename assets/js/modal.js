function showModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
        document.body.classList.add('no-scroll');
    } else {
        console.warn(`Modal with ID '${modalId}' not found.`);
    }
}

// This function ensures any modal closes correctly and handles the body scroll.
function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.remove('active');
        // Only remove no-scroll if no other modals are active
        if (document.querySelectorAll('.modal-overlay.active').length === 0) {
            document.body.classList.remove('no-scroll');
        }
    }
}
// --- END NEW GENERIC MODAL UTILITIES ---


// Function to close all modals (handles clicking outside or clicking the close button)
function closeAllModals(e) {
    // Check if the click target is the overlay itself or a modal's close button
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

    if (hiddenInput && displayImg) {
        hiddenInput.value = path;
        displayImg.src = path;
        
        document.querySelectorAll('.avatar-option').forEach(img => {
            img.classList.remove('selected');
            if (img.getAttribute('src') === path) {
                img.classList.add('selected');
                if (parentWrapper) parentWrapper.classList.add('selected');
            } else {
                if (parentWrapper) parentWrapper.classList.remove('selected');
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

function initPayment(method, amount) {
    const methodSelect = document.getElementById('pay-method');
    const amountInput = document.getElementById('pay-amount');

    if (methodSelect && amountInput) {
        methodSelect.value = method ? method : "";

        amountInput.value = amount ? amount : "";

        showModal('payment-modal');
    } else {
        console.error("Payment modal inputs not found.");
    }
}

function applyFeePreset() {
    const preset = document.getElementById('fee-preset').value;
    const titleInput = document.getElementById('fee-title');
    const amountInput = document.getElementById('fee-amount');
    const categoryInput = document.getElementById('fee-category');


    const fees = {
        'BYTE': { title: "BYTE Membership Fee", amount: 50.00, cat: "Org Fees" },
        'SSG': { title: "SSG Membership Fee", amount: 50.00, cat: "Org Fees" },
        'Uniform_M': { title: "BSU Uniform Set (Upper)", amount: 500.00, cat: "Uniforms" },
        'Uniform_F': { title: "BSU Uniform Set (Bottom)", amount: 500.00, cat: "Uniforms" },
        'DeptShirt': { title: "CIS Department Shirt", amount: 350.00, cat: "Uniforms" },
        'ID_Replace': { title: "ID Replacement Fee", amount: 150.00, cat: "Misc Fees" }
    };

    if (fees[preset]) {
        titleInput.value = fees[preset].title;
        amountInput.value = fees[preset].amount;
        categoryInput.value = fees[preset].cat;
    }
}
