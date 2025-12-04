function openViewModal() {
    const modal = document.getElementById('view-modal');
    const displayImg = document.getElementById('current-avatar-display');
    const enlargedImg = document.getElementById('enlarged-image');
    
    // Check if elements exist before interacting
    if(modal && displayImg && enlargedImg) {
        enlargedImg.src = displayImg.src;
        // Uses new generic helper
        openModal('view-modal');
    }
}

function openSelectorModal() {
    // Uses new generic helper
    openModal('selector-modal');
}

// --- NEW GENERIC MODAL UTILITIES (CRITICAL FOR GRADES/SUBJECTS) ---
// This function ensures any modal opens correctly and handles the body scroll.
function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.add('active');
        document.body.classList.add('no-scroll');
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
        let activeModals = document.querySelectorAll('.modal-overlay.active');
        
        activeModals.forEach(el => {
            // Check if the clicked target is this modal or inside its close button
            if (el === e.target || el.contains(e.target)) {
                el.classList.remove('active');
            }
        });

        // Re-check for active modals to manage scroll state
        if (document.querySelectorAll('.modal-overlay.active').length === 0) {
            document.body.classList.remove('no-scroll');
        }
    }
}

function selectAvatar(path) {
    const hiddenInput = document.getElementById('selected-avatar-input');
    const displayImg = document.getElementById('current-avatar-display');

    if(hiddenInput && displayImg) {
        hiddenInput.value = path;
        displayImg.src = path;
        
        document.querySelectorAll('.avatar-option').forEach(img => {
            img.classList.remove('selected');

            // Logic to handle the complex exclusive admin wrapper click event
            const parentWrapper = img.closest('.avatar-wrapper.exclusive-admin');
            const isTarget = parentWrapper ? parentWrapper.contains(event.target) : img === event.target;

            if (img.getAttribute('src') === path && isTarget) {
                img.classList.add('selected');
                if (parentWrapper) parentWrapper.classList.add('selected');
            } else {
                if (parentWrapper) parentWrapper.classList.remove('selected');
            }
        });

        // Uses new generic helper
        closeModal('selector-modal');
        
    } else {
        console.error("Error: Could not find input or display image elements.");
    }
}

function openImportModal() {
    // Uses new generic helper
    openModal('import-modal');
}

// --- NEW FUNCTION: OPENS AND POPULATES GRADE EDIT MODAL ---
function openEditGradeModal(periodId, studentName, studentId, scoreA1, scoreA2, scoreExam) {
    // Hidden ID for DB update (period_id is the Primary Key)
    document.getElementById('edit-period-id').value = periodId;

    // Display fields
    document.getElementById('student-name-display').textContent = studentName;
    document.getElementById('student-id-display').textContent = studentId;

    // Input fields for editing
    document.getElementById('edit-activity-1').value = scoreA1;
    document.getElementById('edit-activity-2').value = scoreA2;
    document.getElementById('edit-exam-score').value = scoreExam;
    
    // Uses new generic helper
    openModal('edit-grade-modal');
}

// Global click listener for closing modals when clicking outside
window.onclick = closeAllModals;