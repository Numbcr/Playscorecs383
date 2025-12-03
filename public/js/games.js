let popularPage = 1;
let recentPage = 1;
const gamesPerPage = 4;

// Cache to store fetched pages
const gameCache = {};
let isLoadingPopular = false;
let isLoadingRecent = false;

async function fetchPopularGames(page) {
    const cacheKey = `popular_${page}`;
    
    // Return cached data if available
    if (gameCache[cacheKey]) {
        return gameCache[cacheKey];
    }
    
    try {
        const response = await fetch(`/api/games/popular?page=${page}&limit=${gamesPerPage}`);
        if (!response.ok) throw new Error('Failed to fetch popular games');
        const result = await response.json();
        const data = result.success ? result.data : [];
        
        // Cache the result
        gameCache[cacheKey] = data;
        return data;
    } catch (error) {
        console.error('Error fetching popular games:', error);
        return [];
    }
}

async function fetchRecentGames(page) {
    const cacheKey = `recent_${page}`;
    
    // Return cached data if available
    if (gameCache[cacheKey]) {
        return gameCache[cacheKey];
    }
    
    try {
        const response = await fetch(`/api/games/recent?page=${page}&limit=${gamesPerPage}`);
        if (!response.ok) throw new Error('Failed to fetch recent games');
        const result = await response.json();
        const data = result.success ? result.data : [];
        
        // Cache the result
        gameCache[cacheKey] = data;
        return data;
    } catch (error) {
        console.error('Error fetching recent games:', error);
        return [];
    }
}

// Preload next pages in the background
function preloadNextPages() {
    // Preload next popular page
    fetchPopularGames(popularPage + 1).catch(() => {});
    
    // Preload next recent page
    fetchRecentGames(recentPage + 1).catch(() => {});
}

function getScoreClass(rating) {
    rating = Number(rating);
    if (rating >= 80) return 'score-high';
    if (rating >= 60) return 'score-mid';
    return 'score-low';
}

function createSkeletonCard() {
    return `
        <div class="col-md-3 mb-4">
            <div class="skeleton-card">
                <div class="skeleton-image"></div>
                <div class="skeleton-body">
                    <div class="skeleton skeleton-title"></div>
                    <div class="skeleton skeleton-text"></div>
                    <div class="skeleton skeleton-text-short"></div>
                    <div class="skeleton skeleton-button"></div>
                </div>
            </div>
        </div>
    `;
}

function createGameCard(game) {
    const scoreClass = getScoreClass(game.rating);
    return `
        <div class="col-md-3 mb-4">
            <div class="game-card position-relative">
                <div class="score-badge ${scoreClass}">${Number(game.rating)}</div>
                <img src="${game.game_image}" 
                     alt="${game.game_title}" 
                     class="card-img-top"
                     loading="lazy">
                <div class="card-body">
                    <h5 class="card-title">${game.game_title}</h5>
                    <p class="text-muted">${window.translations?.reviewed_by || 'Reviewed by'} ${game.admin_username}</p>
                    <p class="text-muted">${new Date(game.created_at).toLocaleDateString()}</p>
                    <a href="/games?gameId=${game.game_id}" class="btn btn-outline-light w-100">
                        ${window.translations?.read_review || 'Read Review'}
                    </a>
                </div>
            </div>
        </div>
    `;
}

async function displayPopularGames() {
    const container = document.getElementById('popularGamesContainer');
    if (!container) return;
    
    // Show skeleton loading
    container.innerHTML = `
        <div class="row">
            ${Array(4).fill('').map(() => createSkeletonCard()).join('')}
        </div>
    `;
    
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
        // Preload next page in background
        setTimeout(preloadNextPages, 500);
    } else {
        container.innerHTML = `<p class="text-center">${window.translations?.no_popular_games || 'No popular games found'}</p>`;
    }
}

async function displayRecentGames() {
    const container = document.getElementById('recentGamesContainer');
    if (!container) return;
    
    // Show skeleton loading
    container.innerHTML = `
        <div class="row">
            ${Array(4).fill('').map(() => createSkeletonCard()).join('')}
        </div>
    `;
    
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
        // Preload next page in background
        setTimeout(preloadNextPages, 500);
    } else {
        container.innerHTML = `<p class="text-center">${window.translations?.no_recent_games || 'No recent games found'}</p>`;
    }
}

async function navigatePopular(direction) {
    if (isLoadingPopular) return;
    
    const nextPage = popularPage + direction;
    if (nextPage < 1) return;
    
    isLoadingPopular = true;
    const games = await fetchPopularGames(nextPage);
    isLoadingPopular = false;
    
    if (games.length > 0) {
        popularPage = nextPage;
        displayPopularGames();
    }
}

async function navigateRecent(direction) {
    if (isLoadingRecent) return;
    
    const nextPage = recentPage + direction;
    if (nextPage < 1) return;
    
    isLoadingRecent = true;
    const games = await fetchRecentGames(nextPage);
    isLoadingRecent = false;
    
    if (games.length > 0) {
        recentPage = nextPage;
        displayRecentGames();
    }
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
