class GameDetailManager {
    constructor() {
        const urlParams = new URLSearchParams(window.location.search);
        this.gameId = urlParams.get('gameId');
        this.container = document.querySelector('.game-detail-container');
        if (!this.gameId) {
            throw new Error('No game ID provided');
        }
    }

    async initialize() {
        try {
            const gameDetails = await this.fetchGameDetails();
            this.renderGameDetails(gameDetails);
        } catch (error) {
            console.error('Error initializing game details:', error);
            this.renderError(error.message);
        }
    }

    async fetchGameDetails() {
        try {
            console.log('Fetching review for game ID:', this.gameId);
            const response = await fetch(`/api/games/${this.gameId}`);
            console.log('Response status:', response.status);
            const data = await response.json();
            console.log('Review data:', data);
            
            if (!data.success || !data.review) {
                throw new Error(data.error || 'Review not found');
            }

            const rawgId = data.review.rawg_id;
            if (!rawgId) {
                throw new Error('Invalid game reference');
            }

            console.log('Fetching RAWG data for ID:', rawgId);
            const rawgApiKey = await this.getRawgApiKey();
            const rawgResponse = await fetch(`https://api.rawg.io/api/games/${rawgId}?key=${rawgApiKey}`);
            
            if (!rawgResponse.ok) {
                throw new Error('Failed to fetch game details from RAWG');
            }
            
            const rawgData = await rawgResponse.json();
            console.log('RAWG data:', rawgData);

            return {
                ...rawgData,
                review: {
                    rating: data.review.rating,
                    review_text: data.review.review_text,
                    published_at: data.review.created_at,
                    username: data.review.username
                }
            };
        } catch (error) {
            console.error('Error fetching game details:', error);
            throw error;
        }
    }

    async getRawgApiKey() {
        try {
            const response = await fetch('/api/games/rawg/key');
            const data = await response.json();
            if (!data.success || !data.key) {
                throw new Error('Failed to get RAWG API key');
            }
            return data.key;
        } catch (error) {
            console.error('Error getting RAWG API key:', error);
            throw new Error('Failed to get RAWG API key');
        }
    }

    renderGameDetails(game) {
        if (!game) {
            this.renderError('No game data available');
            return;
        }

        this.container.innerHTML = `
            <div class="game-detail-container">
                <div class="game-info">
                    <div class="media-section">
                        <img src="${game.background_image || 'images/default-image.jpg'}" alt="${game.name}" class="media-item active">
                        <div id="reviewRating" class="score-badge ${this.getScoreClass(game.review.rating)}">
                            ${Number(game.review.rating)}
                        </div>
                    </div>

                    <div class="game-header">
                        <h1>${game.name}</h1>
                    </div>

                    <div class="meta-info">
                        <div class="meta-item">
                            <strong>Release Date</strong>
                            <span>${game.released || 'TBA'}</span>
                        </div>
                        <div class="meta-item">
                            <strong>Genres</strong>
                            <span>${game.genres?.map(g => g.name).join(', ') || 'N/A'}</span>
                        </div>
                        <div class="meta-item">
                            <strong>Platforms</strong>
                            <span>${game.platforms?.map(p => p.platform.name).join(', ') || 'N/A'}</span>
                        </div>
                        <div class="meta-item">
                            <strong>Developers</strong>
                            <span>${game.developers?.map(d => d.name).join(', ') || 'N/A'}</span>
                        </div>
                    </div>
                </div>

                <div class="review-section">
                    <div class="review-header">
                        <h2>Review</h2>
                        <span class="review-date">Reviewed at: ${new Date(game.review.published_at).toLocaleDateString()}</span>
                    </div>
                    <div class="review-text">
                        ${game.review.review_text || 'No review available'}
                    </div>
                    <div id="reviewerName">
                        Reviewed by: ${game.review.username || 'Anonymous'}
                    </div>
                </div>
            </div>
        `;
    }

    getScoreClass(rating) {
        rating = Number(rating);
        if (rating >= 80) return 'score-high';
        if (rating >= 60) return 'score-mid';
        return 'score-low';
    }

    renderError(message = 'Error loading game details. Please try again later.') {
        this.container.innerHTML = `
            <div class="container mt-5">
                <div class="alert alert-danger">
                    ${message}
                </div>
            </div>
        `;
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new GameDetailManager().initialize();
});
