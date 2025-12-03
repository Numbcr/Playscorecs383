// Global search functionality for all pages
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('searchForm');
    if (!searchForm) return;
    
    const searchInput = searchForm.querySelector('input[type="search"]');
    const searchButton = searchForm.querySelector('button[type="submit"]');

    searchForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const query = searchInput.value.trim();
        
        if (query.length < 2) {
            alert('Please enter at least 2 characters to search.');
            return;
        }

        // Show loading state
        if (searchButton) {
            const originalHTML = searchButton.innerHTML;
            searchButton.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            searchButton.disabled = true;
        }

        // Redirect to search page
        window.location.href = `/search?q=${encodeURIComponent(query)}`;
    });
});
