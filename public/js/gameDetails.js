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
        // Show skeleton loading immediately
        this.showSkeletonLoading();
        
        try {
            const gameDetails = await this.fetchGameDetails();
            this.renderGameDetails(gameDetails);
            await this.loadComments();
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

        // Check if user is logged in
        const user = JSON.parse(localStorage.getItem('user') || '{}');
        const isLoggedIn = user && user.id;

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
                            <strong>${window.translations?.release_date || 'Release Date'}</strong>
                            <span>${game.released || (window.translations?.tba || 'TBA')}</span>
                        </div>
                        <div class="meta-item">
                            <strong>${window.translations?.genres || 'Genres'}</strong>
                            <span>${game.genres?.map(g => g.name).join(', ') || (window.translations?.na || 'N/A')}</span>
                        </div>
                        <div class="meta-item">
                            <strong>${window.translations?.platforms || 'Platforms'}</strong>
                            <span>${game.platforms?.map(p => p.platform.name).join(', ') || (window.translations?.na || 'N/A')}</span>
                        </div>
                        <div class="meta-item">
                            <strong>${window.translations?.developers || 'Developers'}</strong>
                            <span>${game.developers?.map(d => d.name).join(', ') || (window.translations?.na || 'N/A')}</span>
                        </div>
                    </div>
                </div>

                <div class="review-section">
                    <div class="review-header">
                        <h2>${window.translations?.review || 'Review'}</h2>
                        <span class="review-date">${window.translations?.reviewed_at || 'Reviewed at'}: ${new Date(game.review.published_at).toLocaleDateString()}</span>
                    </div>
                    <div class="review-text">
                        ${game.review.review_text || (window.translations?.no_review_available || 'No review available')}
                    </div>
                    <div id="reviewerName">
                        ${window.translations?.reviewed_by || 'Reviewed by'}: ${game.review.username || 'Anonymous'}
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="comments-section">
                    <h3>Comments</h3>
                    <div id="commentForm" class="comment-form mb-4">
                        ${isLoggedIn ? `
                            <textarea id="commentInput" class="form-control mb-2" placeholder="Add a comment..." rows="3" maxlength="1000"></textarea>
                            <button onclick="gameDetailManager.postComment()" class="btn btn-primary">Post Comment</button>
                        ` : `
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Please <a href="/login" class="alert-link">login</a> to add a comment.
                            </div>
                        `}
                    </div>
                    <div id="commentsContainer">
                        <div class="text-center">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
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

    renderError(message = window.translations?.error_loading_game || 'Error loading game details. Please try again later.') {
        this.container.innerHTML = `
            <div class="container mt-5">
                <div class="alert alert-danger">
                    ${message}
                </div>
            </div>
        `;
    }

    showSkeletonLoading() {
        this.container.innerHTML = `
            <div class="media-section" style="background-color: #2d2d2d;">
                <div class="skeleton skeleton-detail-image"></div>
            </div>

            <div class="game-info">
                <div class="game-header">
                    <div class="skeleton skeleton-detail-header"></div>
                </div>
                <div class="skeleton-meta-grid">
                    <div class="skeleton skeleton-meta-item"></div>
                    <div class="skeleton skeleton-meta-item"></div>
                    <div class="skeleton skeleton-meta-item"></div>
                    <div class="skeleton skeleton-meta-item"></div>
                </div>
            </div>

            <div class="review-section">
                <div class="review-header">
                    <div class="skeleton skeleton-title" style="width: 150px;"></div>
                </div>
                <div class="skeleton skeleton-review-text"></div>
                <div class="skeleton skeleton-text" style="width: 200px;"></div>
            </div>
        `;
    }

    async loadComments() {
        try {
            const response = await fetch(`/api/games/${this.gameId}/comments`);
            const data = await response.json();
            
            if (data.success) {
                this.renderComments(data.comments);
            }
        } catch (error) {
            console.error('Error loading comments:', error);
            document.getElementById('commentsContainer').innerHTML = '<p class="text-danger">Failed to load comments.</p>';
        }
    }

    renderComments(comments) {
        const container = document.getElementById('commentsContainer');
        
        if (comments.length === 0) {
            container.innerHTML = '<p class="text-muted">No comments yet. Be the first to comment!</p>';
            return;
        }

        container.innerHTML = comments.map(comment => `
            <div class="comment-item" data-comment-id="${comment.id}">
                <div class="comment-header">
                    <strong>${comment.username}</strong>
                    <span class="text-muted ms-2">${comment.created_at}</span>
                    ${this.canDeleteComment(comment) ? `
                        <button onclick="gameDetailManager.deleteComment(${comment.id})" class="btn btn-sm btn-outline-danger ms-auto">
                            <i class="fas fa-trash"></i>
                        </button>
                    ` : ''}
                </div>
                <div class="comment-text">${this.escapeHtml(comment.comment)}</div>
            </div>
        `).join('');
    }

    canDeleteComment(comment) {
        const user = JSON.parse(localStorage.getItem('user') || '{}');
        return user.id === comment.user_id || user.is_admin;
    }

    async postComment() {
        const input = document.getElementById('commentInput');
        const comment = input.value.trim();

        if (!comment) {
            alert('Please enter a comment.');
            return;
        }

        try {
            const response = await fetch(`/api/games/${this.gameId}/comments`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ comment })
            });

            const data = await response.json();

            if (response.status === 401) {
                alert('Please login to post a comment.');
                window.location.href = '/login';
                return;
            }

            if (data.success) {
                input.value = '';
                await this.loadComments();
            } else {
                alert(data.message || 'Failed to post comment.');
            }
        } catch (error) {
            console.error('Error posting comment:', error);
            alert('Failed to post comment. Please try again.');
        }
    }

    async deleteComment(commentId) {
        if (!confirm('Are you sure you want to delete this comment?')) {
            return;
        }

        try {
            const response = await fetch(`/api/games/${this.gameId}/comments/${commentId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const data = await response.json();

            if (data.success) {
                await this.loadComments();
            } else {
                alert(data.message || 'Failed to delete comment.');
            }
        } catch (error) {
            console.error('Error deleting comment:', error);
            alert('Failed to delete comment. Please try again.');
        }
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

let gameDetailManager;

document.addEventListener('DOMContentLoaded', () => {
    gameDetailManager = new GameDetailManager();
    gameDetailManager.initialize();
});
