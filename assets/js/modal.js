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