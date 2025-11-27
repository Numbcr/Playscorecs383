let popularPage = 1;
let recentPage = 1;
const gamesPerPage = 4;

async function fetchPopularGames(page) {
    try {
        const response = await fetch(`/api/games/popular?page=${page}&limit=${gamesPerPage}`);
        if (!response.ok) throw new Error('Failed to fetch popular games');
        const result = await response.json();
        return result.success ? result.data : [];
    } catch (error) {
        console.error('Error fetching popular games:', error);
        return [];
    }
}

async function fetchRecentGames(page) {
    try {
        const response = await fetch(`/api/games/recent?page=${page}&limit=${gamesPerPage}`);
        if (!response.ok) throw new Error('Failed to fetch recent games');
        const result = await response.json();
        return result.success ? result.data : [];
    } catch (error) {
        console.error('Error fetching recent games:', error);
        return [];
    }
}

function getScoreClass(rating) {
    rating = Number(rating);
    if (rating >= 80) return 'score-high';
    if (rating >= 60) return 'score-mid';
    return 'score-low';
}

function createGameCard(game) {
    const scoreClass = getScoreClass(game.rating);
    return `
        <div class="col-md-3 mb-4">
            <div class="game-card position-relative">
                <div class="score-badge ${scoreClass}">${Number(game.rating)}</div>
                <img src="${game.game_image}" 
                     alt="${game.game_title}" 
                     class="card-img-top">
                <div class="card-body">
                    <h5 class="card-title">${game.game_title}</h5>
                    <p class="text-muted">Reviewed by ${game.admin_username}</p>
                    <p class="text-muted">${new Date(game.created_at).toLocaleDateString()}</p>
                    <a href="/games?gameId=${game.game_id}" class="btn btn-outline-light w-100">
                        Read Review
                    </a>
                </div>
            </div>
        </div>
    `;
}

async function displayPopularGames() {
    const container = document.getElementById('popularGamesContainer');
    if (!container) return;
    
    const games = await fetchPopularGames(popularPage);
    if (games.length > 0) {
        container.innerHTML = `
            <div class="row">
                ${games.map(game => createGameCard(game)).join('')}
            </div>
            <button class="nav-button prev" onclick="navigatePopular(-1)">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="nav-button next" onclick="navigatePopular(1)">
                <i class="fas fa-chevron-right"></i>
            </button>
        `;
    } else {
        container.innerHTML = '<p class="text-center">No popular games found</p>';
    }
}

async function displayRecentGames() {
    const container = document.getElementById('recentGamesContainer');
    if (!container) return;
    
    const games = await fetchRecentGames(recentPage);
    if (games.length > 0) {
        container.innerHTML = `
            <div class="row">
                ${games.map(game => createGameCard(game)).join('')}
            </div>
            <button class="nav-button prev" onclick="navigateRecent(-1)">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="nav-button next" onclick="navigateRecent(1)">
                <i class="fas fa-chevron-right"></i>
            </button>
        `;
    } else {
        container.innerHTML = '<p class="text-center">No recent games found</p>';
    }
}

async function navigatePopular(direction) {
    const nextPage = popularPage + direction;
    if (nextPage < 1) return;
    popularPage = nextPage;
    const games = await fetchPopularGames(popularPage);
    if (games.length > 0) displayPopularGames();
    else popularPage -= direction;
}

async function navigateRecent(direction) {
    const nextPage = recentPage + direction;
    if (nextPage < 1) return;
    recentPage = nextPage;
    const games = await fetchRecentGames(recentPage);
    if (games.length > 0) displayRecentGames();
    else recentPage -= direction;
}

async function searchGames(query) {
    try {
        const response = await fetch(`/api/games/search?q=${encodeURIComponent(query)}`);
        if (!response.ok) throw new Error('Search failed');
        return await response.json();
    } catch (error) {
        console.error('Search error:', error);
        return [];
    }
}

function initializeSearch() {
    const searchForm = document.querySelector('form.d-flex');
    if (!searchForm) return;
    
    const searchInput = searchForm.querySelector('input[type="search"]');

    searchForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const query = searchInput.value.trim();
        
        if (query.length < 2) {
            const alertHtml = `
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    Please enter at least 2 characters to search.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>`;
            document.querySelector('main').insertAdjacentHTML('afterbegin', alertHtml);
            return;
        }

        const searchButton = searchForm.querySelector('button');
        const originalButtonText = searchButton.innerHTML;
        searchButton.innerHTML = `
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            Searching...`;
        searchButton.disabled = true;

        try {
            window.location.href = `/search?q=${encodeURIComponent(query)}`;
        } finally {
            searchButton.innerHTML = originalButtonText;
            searchButton.disabled = false;
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    displayPopularGames();
    displayRecentGames();
    initializeSearch();
});
