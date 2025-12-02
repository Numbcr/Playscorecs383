@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endsection

@section('content')
<div class="container mt-4 mb-5">
    <!-- Welcome Banner -->
    <div class="card bg-dark text-light border-info border-start border-5 mb-4 dashboard-card dashboard-card-welcome">
        <div class="card-body">
            <h4 class="card-title text-info">{{ __('messages.admin_dashboard') }}</h4>
            <p class="card-text text-secondary">{{ __('messages.dashboard_description') }}</p>
        </div>
    </div>

    <!-- Add New Games Section -->
    <div class="card bg-dark text-light mb-4 border-info" style="background: linear-gradient(135deg, #1f1f1f 0%, #2d2d2d 100%); border-color: rgba(0, 123, 255, 0.2) !important;">
        <div class="card-header bg-dark border-info" style="border-color: rgba(0, 123, 255, 0.2) !important;">
            <h5 class="mb-0 text-info">{{ __('messages.add_new_review') }}</h5>
        </div>
        <div class="card-body">
            <!-- RAWG ID Input for Preview -->
            <div class="input-group mb-3">
                <input type="text" id="rawgSearchInput" class="form-control dashboard-input" placeholder="{{ __('messages.enter_rawg_id') }}" />
                <button class="btn btn-outline-info" type="button" onclick="previewRawgGame()">{{ __('messages.preview_game') }}</button>
            </div>

            <!-- Message Area -->
            <div id="messageArea" class="alert d-none"></div>

            <!-- Game Preview -->
            <div id="gamePreviewContainer" class="card bg-secondary d-none mb-3 border-info game-preview-card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <img id="gamePreviewImage" src="" alt="Game" class="img-fluid rounded">
                        </div>
                        <div class="col-md-8">
                            <h5 id="gamePreviewTitle" class="mb-3 text-light"></h5>
                            <p class="mb-2 text-light"><strong class="text-info">{{ __('messages.released') }}:</strong> <span id="gamePreviewReleased"></span></p>
                            <p class="mb-2 text-light"><strong class="text-info">{{ __('messages.genres') }}:</strong> <span id="gamePreviewGenres"></span></p>
                            <p class="mb-2 text-light"><strong class="text-info">{{ __('messages.developers') }}:</strong> <span id="gamePreviewDevelopers"></span></p>
                            <p class="mb-2 text-light"><strong class="text-info">{{ __('messages.publishers') }}:</strong> <span id="gamePreviewPublishers"></span></p>
                            <div class="mt-3 mb-3 text-light game-preview-description" id="gamePreviewDescription"></div>
                            <button type="button" onclick="addReviewFromPreview()" class="btn btn-info">{{ __('messages.add_review_for_game') }}</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search Results (for RAWG search) -->
            <div id="searchResults"></div>
        </div>
    </div>

    <!-- Review Form Section (Hidden) -->
    <div id="reviewFormContainer" class="card bg-dark text-light d-none mb-4 border-info dashboard-card">
        <div class="card-header bg-dark border-info d-flex justify-content-between align-items-center dashboard-card-header">
            <h5 id="formTitle" class="mb-0 text-info">{{ __('messages.add_new_review') }}</h5>
            <button type="button" class="btn-close btn-close-white" onclick="closeReviewForm()"></button>
        </div>
        <div class="card-body">
            <form id="reviewForm">
                <input type="hidden" id="gameId">
                <input type="hidden" id="selectedRawgId">
                <div class="mb-3">
                    <label for="gameTitle" class="form-label">{{ __('messages.game_title') }}</label>
                    <input type="text" id="gameTitle" name="game_title" class="form-control dashboard-input" placeholder="{{ __('messages.game_title') }}..." readonly>
                </div>
                <div class="mb-3">
                    <label for="rating" class="form-label">{{ __('messages.your_rating') }}</label>
                    <input type="number" id="rating" name="rating" class="form-control dashboard-input" min="1" max="100" placeholder="{{ __('messages.enter_rating') }}" required>
                </div>
                <div class="mb-3">
                    <label for="reviewText" class="form-label">{{ __('messages.your_review') }}</label>
                    <textarea id="reviewText" name="review_text" class="form-control dashboard-input" rows="5" placeholder="{{ __('messages.write_review') }}" required></textarea>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-info flex-grow-1" id="submitBtnText">{{ __('messages.add_review') }}</button>
                    <button type="button" class="btn btn-secondary" onclick="closeReviewForm()">{{ __('messages.cancel') }}</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Manage Games Section (Search by Game ID) -->
    <div class="card bg-dark text-light mb-4 border-info dashboard-card">
        <div class="card-header bg-dark border-info dashboard-card-header">
            <h5 class="mb-0 text-info">{{ __('messages.manage_reviews') }}</h5>
        </div>
        <div class="card-body">
            <div class="input-group mb-3">
                <input type="text" id="searchGameId" class="form-control dashboard-input" placeholder="{{ __('messages.enter_game_id') }}" />
                <button class="btn btn-outline-info" type="button" onclick="searchReviewById()">{{ __('messages.search') }}</button>
            </div>

            <div id="reviewMessageArea" class="alert d-none"></div>

            <!-- Review Details (shown after search) -->
            <div id="reviewDetails" class="d-none mb-4">
                <div class="card bg-secondary border-info game-preview-card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <img id="managedGameImage" src="" alt="Game" class="img-fluid rounded">
                            </div>
                            <div class="col-md-8">
                                <h5 id="managedGameTitle" class="mb-3 text-light"></h5>
                                <form id="editForm">
                                    <input type="hidden" id="managedGameId">
                                    <div class="mb-3">
                                        <label for="managedRating" class="form-label">{{ __('messages.rating') }} (1-100)</label>
                                        <input type="number" id="managedRating" class="form-control dashboard-input" min="1" max="100" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="managedReviewText" class="form-label">{{ __('messages.review') }}</label>
                                        <textarea id="managedReviewText" class="form-control dashboard-input" rows="5" required></textarea>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-info flex-grow-1" onclick="updateManagedReview()">{{ __('messages.update_review') }}</button>
                                        <button type="button" class="btn btn-outline-danger" onclick="deleteManagedReview()">{{ __('messages.delete_review') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Your Reviews Table -->
            <div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0 text-light">{{ __('messages.all_reviews') }}</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover text-light table-dark-custom">
                        <thead>
                            <tr>
                                <th>{{ __('messages.game_id') }}</th>
                                <th>{{ __('messages.game') }}</th>
                                <th>{{ __('messages.rating') }}</th>
                                <th>{{ __('messages.review') }}</th>
                                <th>{{ __('messages.added') }}</th>
                                <th>{{ __('messages.action') }}</th>
                            </tr>
                        </thead>
                        <tbody id="gamesTableBody">
                            <tr>
                                <td colspan="6" class="text-center text-muted">{{ __('messages.loading_reviews') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination Controls -->
                <div id="paginationControls" class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-light">
                        <span id="paginationInfo"></span>
                    </div>
                    <nav>
                        <ul class="pagination pagination-sm mb-0" id="paginationButtons">
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('extra-js')
<script>
let selectedGame = null;
let editingGameId = null;

// Preview RAWG game by ID
function previewRawgGame() {
    const rawgId = document.getElementById('rawgSearchInput').value.trim();
    if (!rawgId) {
        showMessage('messageArea', 'Please enter a RAWG Game ID', 'danger');
        return;
    }

    const messageArea = document.getElementById('messageArea');
    messageArea.textContent = 'Loading...';
    messageArea.classList.remove('d-none');

    // First get API key from our server
    fetch('/api/games/rawg/key')
        .then(r => r.json())
        .then(response => {
            // Then fetch from RAWG using native fetch (avoids jQuery CSRF header issues)
            return fetch(`https://api.rawg.io/api/games/${rawgId}?key=${response.key}`);
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(gameData => {
            if (gameData.error) {
                showMessage('messageArea', 'Game not found', 'danger');
                return;
            }

            // Populate preview
            document.getElementById('gamePreviewImage').src = gameData.background_image || 'https://via.placeholder.com/300x200';
            document.getElementById('gamePreviewTitle').textContent = gameData.name || 'No title';
            document.getElementById('gamePreviewReleased').textContent = gameData.released || 'Unknown';
            document.getElementById('gamePreviewGenres').textContent = gameData.genres?.map(g => g.name).join(', ') || 'N/A';
            document.getElementById('gamePreviewDevelopers').textContent = gameData.developers?.map(d => d.name).join(', ') || 'N/A';
            document.getElementById('gamePreviewPublishers').textContent = gameData.publishers?.map(p => p.name).join(', ') || 'N/A';
            document.getElementById('gamePreviewDescription').textContent = gameData.description_raw || gameData.description || 'No description available';

            document.getElementById('gamePreviewContainer').classList.remove('d-none');
            messageArea.classList.add('d-none');

            // Store for form submission
            selectedGame = gameData;
        })
        .catch(error => {
            console.error('Error:', error);
            showMessage('messageArea', 'Error fetching game from RAWG API: ' + error.message, 'danger');
        });
}

// Add review from preview
function addReviewFromPreview() {
    if (!selectedGame) {
        showMessage('messageArea', 'Please preview a game first', 'danger');
        return;
    }

    editingGameId = null;
    document.getElementById('gameId').value = '';
    document.getElementById('selectedRawgId').value = selectedGame.id;
    document.getElementById('gameTitle').value = selectedGame.name;
    document.getElementById('rating').value = '';
    document.getElementById('reviewText').value = '';
    document.getElementById('formTitle').textContent = 'Add New Game Review';
    document.getElementById('submitBtnText').textContent = 'Add Review';

    document.getElementById('reviewFormContainer').classList.remove('d-none');
    document.getElementById('reviewFormContainer').scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function showAddForm() {
    editingGameId = null;
    document.getElementById('gameId').value = '';
    document.getElementById('gameTitle').value = '';
    document.getElementById('rating').value = '';
    document.getElementById('reviewText').value = '';
    document.getElementById('formTitle').textContent = 'Add New Game Review';
    document.getElementById('submitBtnText').textContent = 'Add Review';
    document.getElementById('reviewFormContainer').classList.remove('d-none');
    document.getElementById('reviewFormContainer').scrollIntoView({ behavior: 'smooth', block: 'center' });
}

function closeReviewForm() {
    document.getElementById('reviewFormContainer').classList.add('d-none');
    editingGameId = null;
    selectedGame = null;
}

// Search review by game ID
function searchReviewById() {
    const gameId = document.getElementById('searchGameId').value.trim();
    if (!gameId) {
        showMessage('reviewMessageArea', 'Please enter a Game ID', 'danger');
        return;
    }

    $.ajax({
        url: `/api/games/${gameId}`,
        method: 'GET',
        success: function(response) {
            if (response.success && response.review) {
                const review = response.review;
                document.getElementById('managedGameId').value = review.game_id;
                document.getElementById('managedGameTitle').textContent = review.game_title;
                document.getElementById('managedGameImage').src = review.game_image || 'https://via.placeholder.com/300x200';
                document.getElementById('managedRating').value = review.rating;
                document.getElementById('managedReviewText').value = review.review_text;

                document.getElementById('reviewDetails').classList.remove('d-none');
                showMessage('reviewMessageArea', '', 'success');
            } else {
                showMessage('reviewMessageArea', 'Review not found', 'warning');
                document.getElementById('reviewDetails').classList.add('d-none');
            }
        },
        error: function() {
            showMessage('reviewMessageArea', 'Error finding review', 'danger');
            document.getElementById('reviewDetails').classList.add('d-none');
        }
    });
}

function updateManagedReview() {
    const gameId = document.getElementById('managedGameId').value;
    const rating = document.getElementById('managedRating').value;
    const review = document.getElementById('managedReviewText').value;

    if (!rating || !review) {
        alert('Rating and review are required');
        return;
    }

    $.ajax({
        url: `/api/games/${gameId}`,
        method: 'PUT',
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: JSON.stringify({
            game_title: document.getElementById('managedGameTitle').textContent,
            rating: parseInt(rating),
            review_text: review
        }),
        success: function() {
            showMessage('reviewMessageArea', 'Review updated successfully!', 'success');
            loadGames();
            searchReviewById(); // Refresh
        },
        error: function(xhr) {
            showMessage('reviewMessageArea', 'Error updating review', 'danger');
        }
    });
}

function deleteManagedReview() {
    if (!confirm('Are you sure you want to delete this review?')) return;

    const gameId = document.getElementById('managedGameId').value;
    $.ajax({
        url: `/api/games/${gameId}`,
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function() {
            showMessage('reviewMessageArea', 'Review deleted successfully!', 'success');
            document.getElementById('reviewDetails').classList.add('d-none');
            loadGames();
        },
        error: function() {
            showMessage('reviewMessageArea', 'Error deleting review', 'danger');
        }
    });
}

// Form submission
document.getElementById('reviewForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const gameId = document.getElementById('gameId').value;
    const rawgId = document.getElementById('selectedRawgId').value;
    const gameTitle = document.getElementById('gameTitle').value;
    const rating = document.getElementById('rating').value;
    const review = document.getElementById('reviewText').value;

    const isUpdate = gameId && editingGameId;
    const url = isUpdate ? `/api/games/${gameId}` : '/api/games';
    const method = isUpdate ? 'PUT' : 'POST';

    $.ajax({
        url: url,
        method: method,
        contentType: 'application/json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: JSON.stringify({
            rawgId: parseInt(rawgId) || null,
            game_title: gameTitle,
            rating: parseInt(rating),
            review_text: review
        }),
        success: function(response) {
            showMessage('messageArea', isUpdate ? 'Review updated successfully!' : 'Review added successfully!', 'success');
            closeReviewForm();
            loadGames();
            document.getElementById('rawgSearchInput').value = '';
            document.getElementById('gamePreviewContainer').classList.add('d-none');
            document.getElementById('searchGameId').value = '';
            document.getElementById('reviewDetails').classList.add('d-none');
        },
        error: function(xhr) {
            const error = xhr.responseJSON?.error || 'Error saving review';
            showMessage('messageArea', error, 'danger');
        }
    });
});

// Pagination variables
let currentPage = 1;
let itemsPerPage = 5;
let allReviews = [];

function loadGames() {
    $.ajax({
        url: '/api/games/popular?limit=1000',
        method: 'GET',
        success: function(response) {
            if (response.data && response.data.length > 0) {
                allReviews = response.data.sort((a, b) => a.game_id - b.game_id);
                displayPaginatedReviews();
            } else {
                const tbody = document.getElementById('gamesTableBody');
                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted">No reviews yet. Add your first review!</td></tr>';
                document.getElementById('paginationControls').classList.add('d-none');
            }
        },
        error: function() {
            document.getElementById('gamesTableBody').innerHTML = '<tr><td colspan="6" class="text-center text-muted">Error loading reviews</td></tr>';
            document.getElementById('paginationControls').classList.add('d-none');
        }
    });
}

function displayPaginatedReviews() {
    const tbody = document.getElementById('gamesTableBody');
    tbody.innerHTML = '';
    
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = Math.min(startIndex + itemsPerPage, allReviews.length);
    const paginatedReviews = allReviews.slice(startIndex, endIndex);
    
    paginatedReviews.forEach(game => {
        const row = document.createElement('tr');
        row.style.backgroundColor = '#1a1a1a';
        row.style.color = '#ffffff';
        const createdDate = new Date(game.created_at).toLocaleDateString();
        row.innerHTML = `
            <td style="color: #ffffff;">${game.game_id}</td>
            <td style="color: #ffffff;"><strong>${game.game_title}</strong></td>
            <td><strong class="text-danger">${game.rating}/100</strong></td>
            <td style="color: #cccccc;">${game.review_text ? game.review_text.substring(0, 50) + '...' : 'No review'}</td>
            <td style="color: #ffffff;">${createdDate}</td>
            <td>
                <button class="btn btn-sm btn-outline-primary" onclick="loadReviewForEdit(${game.game_id})">Edit</button>
                <button class="btn btn-sm btn-outline-danger" onclick="deleteGame(${game.game_id})">Delete</button>
            </td>
        `;
        tbody.appendChild(row);
    });
    
    updatePaginationControls();
}

function updatePaginationControls() {
    const totalPages = Math.ceil(allReviews.length / itemsPerPage);
    const paginationInfo = document.getElementById('paginationInfo');
    const paginationButtons = document.getElementById('paginationButtons');
    
    // Always show pagination controls if there are any reviews
    if (allReviews.length === 0) {
        document.getElementById('paginationControls').classList.add('d-none');
        return;
    } else {
        document.getElementById('paginationControls').classList.remove('d-none');
    }
    
    // Update info text
    const startIndex = (currentPage - 1) * itemsPerPage + 1;
    const endIndex = Math.min(currentPage * itemsPerPage, allReviews.length);
    paginationInfo.textContent = `Showing ${startIndex}-${endIndex} of ${allReviews.length} reviews`;
    
    // Build pagination buttons
    paginationButtons.innerHTML = '';
    
    // Previous button (always show, disabled on first page or single page)
    const prevLi = document.createElement('li');
    prevLi.className = `page-item ${currentPage === 1 || totalPages === 1 ? 'disabled' : ''}`;
    prevLi.innerHTML = `<a class="page-link" href="#" onclick="changePage(${currentPage - 1}); return false;">Previous</a>`;
    paginationButtons.appendChild(prevLi);
    
    // Page numbers
    if (totalPages === 1) {
        // Show single page number
        const singlePageLi = document.createElement('li');
        singlePageLi.className = 'page-item active';
        singlePageLi.innerHTML = `<a class="page-link" href="#">1</a>`;
        paginationButtons.appendChild(singlePageLi);
    } else {
        // Show multiple page numbers
        const maxVisiblePages = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
        let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
    
    if (endPage - startPage < maxVisiblePages - 1) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }
    
    if (startPage > 1) {
        const li = document.createElement('li');
        li.className = 'page-item';
        li.innerHTML = `<a class="page-link" href="#" onclick="changePage(1); return false;">1</a>`;
        paginationButtons.appendChild(li);
        
        if (startPage > 2) {
            const dots = document.createElement('li');
            dots.className = 'page-item disabled';
            dots.innerHTML = `<span class="page-link">...</span>`;
            paginationButtons.appendChild(dots);
        }
    }
    
    for (let i = startPage; i <= endPage; i++) {
        const li = document.createElement('li');
        li.className = `page-item ${i === currentPage ? 'active' : ''}`;
        li.innerHTML = `<a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a>`;
        paginationButtons.appendChild(li);
    }
    
    if (endPage < totalPages) {
        if (endPage < totalPages - 1) {
            const dots = document.createElement('li');
            dots.className = 'page-item disabled';
            dots.innerHTML = `<span class="page-link">...</span>`;
            paginationButtons.appendChild(dots);
        }
        
        const li = document.createElement('li');
        li.className = 'page-item';
        li.innerHTML = `<a class="page-link" href="#" onclick="changePage(${totalPages}); return false;">${totalPages}</a>`;
        paginationButtons.appendChild(li);
    }
    }
    
    // Next button (always show, disabled on last page or single page)
    const nextLi = document.createElement('li');
    nextLi.className = `page-item ${currentPage === totalPages || totalPages === 1 ? 'disabled' : ''}`;
    nextLi.innerHTML = `<a class="page-link" href="#" onclick="changePage(${currentPage + 1}); return false;">Next</a>`;
    paginationButtons.appendChild(nextLi);
}

function changePage(page) {
    const totalPages = Math.ceil(allReviews.length / itemsPerPage);
    if (page < 1 || page > totalPages) return;
    currentPage = page;
    displayPaginatedReviews();
}

function changeItemsPerPage() {
    itemsPerPage = parseInt(document.getElementById('itemsPerPage').value);
    currentPage = 1;
    displayPaginatedReviews();
}


function loadReviewForEdit(gameId) {
    $.ajax({
        url: `/api/games/${gameId}`,
        method: 'GET',
        success: function(response) {
            if (response.success && response.review) {
                const review = response.review;
                editingGameId = gameId;
                document.getElementById('gameId').value = review.game_id;
                document.getElementById('selectedRawgId').value = review.rawg_id || '';
                document.getElementById('gameTitle').value = review.game_title;
                document.getElementById('rating').value = review.rating;
                document.getElementById('reviewText').value = review.review_text;
                document.getElementById('formTitle').textContent = 'Update Game Review';
                document.getElementById('submitBtnText').textContent = 'Update Review';

                document.getElementById('reviewFormContainer').classList.remove('d-none');
                document.getElementById('reviewFormContainer').scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        },
        error: function() {
            alert('Error loading review');
        }
    });
}

function deleteGame(id) {
    if (confirm('Are you sure you want to delete this review?')) {
        $.ajax({
            url: '/api/games/' + id,
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function() {
                alert('Review deleted!');
                loadGames();
            },
            error: function() {
                alert('Error deleting review');
            }
        });
    }
}

function showMessage(elementId, message, type) {
    const element = document.getElementById(elementId);
    if (message) {
        element.className = `alert alert-${type === 'danger' ? 'danger' : type === 'success' ? 'success' : 'warning'} d-block`;
        element.textContent = message;
    } else {
        element.classList.add('d-none');
    }
}

$(document).ready(function() {
    loadGames();
});

// Enter key support
document.getElementById('rawgSearchInput')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') previewRawgGame();
});

document.getElementById('searchGameId')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') searchReviewById();
});
</script>
@endsection
