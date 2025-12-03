class SearchManager {
    constructor() {
        this.currentFilters = {
            rating: '',
            sort: 'relevance'
        };
        
        this.elements = {
            searchForm: null,
            searchInput: null,
            ratingFilter: null,
            sortFilter: null,
            searchStatus: null,
            searchQueryDisplay: null,
            resultsContainer: null
        };
        
        this.isSearchPage = window.location.pathname.includes('search');
    }

    init() {
        this.initializeElements();
        this.setupEventListeners();
        
        if (this.isSearchPage) {
            this.handleInitialSearch();
        }
    }

    initializeElements() {
        this.elements = {
            searchForm: document.getElementById('searchForm'),
            searchInput: document.querySelector('input[name="q"]'),
            ratingFilter: document.getElementById('ratingFilter'),
            sortFilter: document.getElementById('sortFilter'),
            searchStatus: document.getElementById('searchStatus'),
            searchQueryDisplay: document.getElementById('searchQueryDisplay'),
            resultsContainer: document.getElementById('searchResultsContainer')
        };
    }

    setupEventListeners() {
        if (this.elements.searchForm) {
            this.elements.searchForm.addEventListener('submit', (e) => this.handleSearchSubmit(e));
        }

        ['ratingFilter', 'sortFilter'].forEach(filterId => {
            if (this.elements[filterId]) {
                this.elements[filterId].addEventListener('change', (e) => this.handleFilterChange(e));
            }
        });
    }

    handleSearchSubmit = async (e) => {
        e.preventDefault();
        const searchQuery = this.elements.searchInput.value.trim();

        if (searchQuery.length < 2) {
            alert('Please enter at least 2 characters to search');
            return;
        }

        if (this.isSearchPage) {
            await this.performSearch(searchQuery);
            this.updateSearchQueryDisplay(searchQuery);
        } else {
            window.location.href = `/search?q=${encodeURIComponent(searchQuery)}`;
        }
    }

    handleFilterChange = (e) => {
        const filterId = e.target.id;
        const filterValue = e.target.value;
        const filterKey = filterId.replace('Filter', '');
        this.currentFilters[filterKey] = filterValue;

        const currentQuery = this.elements.searchInput.value.trim();
        if (currentQuery.length >= 2) {
            this.performSearch(currentQuery);
        }
    }

    handleInitialSearch() {
        const urlParams = new URLSearchParams(window.location.search);
        const initialQuery = urlParams.get('q');
        
        if (initialQuery && this.elements.searchInput) {
            this.elements.searchInput.value = initialQuery;
            this.updateSearchQueryDisplay(initialQuery);
            this.performSearch(initialQuery);
        }
    }

    updateSearchQueryDisplay(query) {
        if (this.elements.searchQueryDisplay) {
            this.elements.searchQueryDisplay.textContent = `Results for "${query}"`;
        }
    }

    async performSearch(query) {
        if (this.elements.searchStatus) {
            this.elements.searchStatus.style.display = 'block';
        }

        // Show skeleton loading
        if (this.elements.resultsContainer) {
            this.elements.resultsContainer.innerHTML = `
                <div class="game-grid">
                    ${Array(6).fill('').map(() => this.createSkeletonCard()).join('')}
                </div>
            `;
        }

        try {
            const response = await fetch(`/api/games/search?q=${encodeURIComponent(query)}`);
            
            if (!response.ok) {
                throw new Error('Failed to fetch search results');
            }

            const data = await response.json();
            
            if (!data.success) {
                throw new Error(data.error || 'Search failed');
            }

            let results = data.data;

            // Apply rating filter
            if (this.currentFilters.rating) {
                results = results.filter(game => {
                    const rating = Number(game.rating);
                    switch(this.currentFilters.rating) {
                        case 'high': return rating >= 80;
                        case 'mid': return rating >= 60 && rating < 80;
                        case 'low': return rating < 60;
                        default: return true;
                    }
                });
            }

            // Apply sorting
            if (this.currentFilters.sort) {
                results.sort((a, b) => {
                    switch(this.currentFilters.sort) {
                        case 'rating':
                            return Number(b.rating) - Number(a.rating);
                        case 'date':
                            return new Date(b.created_at) - new Date(a.created_at);
                        default:
                            return 0;
                    }
                });
            }

            this.displaySearchResults({ ...data, data: results });
        } catch (error) {
            this.displayError(error);
        } finally {
            if (this.elements.searchStatus) {
                this.elements.searchStatus.style.display = 'none';
            }
        }
    }

    displaySearchResults(data) {
        if (!this.elements.resultsContainer) return;

        if (!data.data || data.data.length === 0) {
            this.elements.resultsContainer.innerHTML = `
                <div class="alert alert-info" role="alert">
                    No results found.
                </div>`;
            return;
        }

        const resultsHtml = data.data.map(game => this.createGameCard(game)).join('');
        this.elements.resultsContainer.innerHTML = `
            <div class="game-grid">
                ${resultsHtml}
            </div>
        `;
    }

    createGameCard(game) {
        const scoreClass = SearchManager.getScoreClass(game.rating);
        return `
            <div class="game-card position-relative">
                <div class="score-badge ${scoreClass}">${Number(game.rating)}</div>
                <img src="${game.game_image || 'images/default-image.jpg'}" 
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
        `;
    }

    createSkeletonCard() {
        return `
            <div class="skeleton-card">
                <div class="skeleton-image"></div>
                <div class="skeleton-body">
                    <div class="skeleton skeleton-title"></div>
                    <div class="skeleton skeleton-text"></div>
                    <div class="skeleton skeleton-text-short"></div>
                    <div class="skeleton skeleton-button"></div>
                </div>
            </div>
        `;
    }

    displayError(error) {
        if (this.elements.resultsContainer) {
            this.elements.resultsContainer.innerHTML = `
                <div class="alert alert-danger" role="alert">
                    <p>${error.message}</p>
                </div>`;
        }
    }

    static getScoreClass(score) {
        if (score >= 80) return 'score-high';
        if (score >= 60) return 'score-mid';
        return 'score-low';
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const searchManager = new SearchManager();
    searchManager.init();
});
