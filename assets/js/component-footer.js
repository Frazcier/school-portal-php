function loadComponent(placeholderId, filePath) {
    fetch(filePath)
        .then(response => {
            if (!response.ok) throw new Error('Page not found: ' + filePath);
            return response.text();
        })
        .then(data => {
            const placeholder = document.getElementById(placeholderId);
            if (placeholder) {
                placeholder.outerHTML = data; 
            }
        })
        .catch(error => console.error('Error loading component:', error));
}

document.addEventListener("DOMContentLoaded", function() {
    loadComponent('footer-placeholder', '../../components/footer.html');
});