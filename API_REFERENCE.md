# PlayScore API Reference

## Base URL
```
http://CS383Project.local/api
or
http://localhost/api
```

## Authentication Endpoints

### Register User
```
POST /auth/register
Content-Type: application/json

{
  "username": "john_doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}

Response (201):
{
  "success": true,
  "message": "User registered successfully",
  "user": {
    "user_id": 1,
    "username": "john_doe",
    "email": "john@example.com",
    "is_admin": false
  }
}
```

### Login
```
POST /auth/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123"
}

Response (200):
{
  "success": true,
  "message": "Login successful",
  "user": {
    "id": 1,
    "username": "john_doe",
    "email": "john@example.com",
    "isAdmin": false
  }
}
```

### Logout
```
POST /auth/logout
Content-Type: application/json
Authorization: Bearer {token}

Response (200):
{
  "success": true
}
```

### Check Session
```
GET /session-check

Response (200):
{
  "loggedIn": true,
  "user_id": 1,
  "username": "john_doe",
  "email": "john@example.com",
  "isAdmin": false
}

Response (200) if not logged in:
{
  "loggedIn": false
}
```

## Game Endpoints

### Get Popular Games (Highest Rated)
```
GET /games/popular?page=1&limit=10

Response (200):
{
  "success": true,
  "data": [
    {
      "game_id": 1,
      "rawg_id": 3498,
      "game_title": "Grand Theft Auto V",
      "rating": 95,
      "review_text": "Excellent game...",
      "created_at": "2025-11-26T10:30:00Z",
      "admin_username": "reviewer1",
      "game_image": "https://media.rawg.io/media/..."
    },
    ...
  ]
}
```

### Get Recent Games
```
GET /games/recent?page=1&limit=10

Response (200):
{
  "success": true,
  "data": [
    {
      "game_id": 5,
      "rawg_id": 13536,
      "game_title": "Baldur's Gate 3",
      "rating": 96,
      "review_text": "Amazing RPG...",
      "created_at": "2025-11-26T15:45:00Z",
      "admin_username": "reviewer2",
      "game_image": "https://media.rawg.io/media/..."
    },
    ...
  ]
}
```

### Search Games
```
GET /games/search?q=minecraft

Response (200):
{
  "success": true,
  "data": [
    {
      "game_id": 2,
      "rawg_id": 1,
      "game_title": "Minecraft",
      "rating": 88,
      "review_text": "Classic sandbox game...",
      "created_at": "2025-11-25T12:00:00Z",
      "admin_username": "reviewer1",
      "game_image": "https://media.rawg.io/media/..."
    },
    ...
  ]
}
```

### Get Game by ID
```
GET /games/3

Response (200):
{
  "success": true,
  "review": {
    "game_id": 3,
    "rawg_id": 5286,
    "game_title": "Tomb Raider",
    "rating": 82,
    "review_text": "Great adventure...",
    "created_at": "2025-11-24T08:15:00Z",
    "username": "reviewer1",
    "user_id": 1
  }
}

Response (404):
{
  "success": false,
  "error": "Review not found"
}
```

### Get RAWG API Key
```
GET /games/rawg/key

Response (200):
{
  "success": true,
  "key": "6113a375538a4cc5ad7b7a896ae8a5a2"
}
```

## Admin Endpoints (Requires Admin Privileges)

### Create Game Review
```
POST /games
Content-Type: application/json
Authorization: Bearer {token}

{
  "rawgId": 3498,
  "rating": 95,
  "review": "This is an excellent game with great gameplay and story."
}

Response (201):
{
  "success": true,
  "insertId": 6,
  "gameTitle": "Grand Theft Auto V",
  "message": "Review added successfully"
}

Response (403):
{
  "success": false,
  "error": "Unauthorized"
}
```

### Update Game Review
```
PUT /games/3
Content-Type: application/json
Authorization: Bearer {token}

{
  "rating": 85,
  "review": "Updated review text..."
}

Response (200):
{
  "success": true,
  "message": "Review updated successfully"
}
```

### Delete Game Review
```
DELETE /games/3
Authorization: Bearer {token}

Response (200):
{
  "success": true,
  "message": "Review deleted successfully"
}
```

## Comment Endpoints

### Get Comments for a Game
```
GET /games/{gameId}/comments

Response (200):
{
  "success": true,
  "comments": [
    {
      "id": 1,
      "comment": "Great game! Really enjoyed it.",
      "username": "john_doe",
      "user_id": 5,
      "created_at": "2 hours ago"
    },
    {
      "id": 2,
      "comment": "One of the best games I've played.",
      "username": "jane_smith",
      "user_id": 8,
      "created_at": "1 day ago"
    }
  ]
}

Response (404):
{
  "success": false,
  "message": "Game not found"
}
```

### Post a Comment (Requires Authentication)
```
POST /games/{gameId}/comments
Content-Type: application/json
Authorization: Bearer {token}

{
  "comment": "This is my comment about the game."
}

Response (201):
{
  "success": true,
  "comment": {
    "id": 15,
    "comment": "This is my comment about the game.",
    "username": "john_doe",
    "user_id": 5,
    "created_at": "just now"
  }
}

Response (401):
{
  "success": false,
  "message": "You must be logged in to comment."
}

Response (422):
{
  "success": false,
  "errors": {
    "comment": ["The comment field is required."]
  }
}
```

### Delete a Comment (Requires Authentication)
```
DELETE /games/{gameId}/comments/{commentId}
Authorization: Bearer {token}

Response (200):
{
  "success": true,
  "message": "Comment deleted successfully"
}

Response (401):
{
  "success": false,
  "message": "You must be logged in to delete comments."
}

Response (403):
{
  "success": false,
  "message": "You are not authorized to delete this comment."
}

Response (404):
{
  "success": false,
  "message": "Comment not found"
}
```

## AI Chat Endpoint (Requires Authentication)

### Send Message to AI Chatbot
```
POST /chat
Content-Type: application/json
Authorization: Bearer {token}

{
  "message": "Can you recommend some RPG games?",
  "conversationHistory": [
    {
      "role": "user",
      "parts": [{"text": "Hello"}]
    },
    {
      "role": "model",
      "parts": [{"text": "Hi! How can I help you?"}]
    }
  ]
}

Response (200):
{
  "success": true,
  "response": "Based on our reviews, I'd recommend checking out..."
}

Response (401):
{
  "success": false,
  "message": "Unauthorized. Please login to use the AI assistant."
}

Response (500):
{
  "success": false,
  "error": "Failed to get response from AI"
}
```

## Error Responses

### Bad Request (400)
```json
{
  "success": false,
  "error": "Search query is required"
}
```

### Unauthorized (401)
```json
{
  "success": false,
  "message": "Invalid email or password"
}
```

### Forbidden (403)
```json
{
  "success": false,
  "error": "Unauthorized"
}
```

### Not Found (404)
```json
{
  "success": false,
  "error": "Review not found"
}
```

### Server Error (500)
```json
{
  "success": false,
  "error": "Error message here"
}
```

## Request Headers

All JSON requests should include:
```
Content-Type: application/json
X-CSRF-TOKEN: {csrf_token}
```

The CSRF token can be obtained from:
- `<meta name="csrf-token">` tag in HTML
- Or passed automatically by the JavaScript framework

## Query Parameters

### Pagination
- `page` - Page number (default: 1)
- `limit` - Items per page (default: 10)

### Search
- `q` - Search query (minimum 2 characters)

### Sorting/Filtering
Handled client-side in the JavaScript

## Example Usage (JavaScript/jQuery)

### Login
```javascript
$.ajax({
  url: '/api/auth/login',
  method: 'POST',
  contentType: 'application/json',
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  },
  data: JSON.stringify({
    email: 'user@example.com',
    password: 'password'
  }),
  success: function(response) {
    console.log('Logged in as:', response.user.username);
  }
});
```

### Fetch Games
```javascript
fetch('/api/games/popular?page=1&limit=10')
  .then(res => res.json())
  .then(data => {
    console.log('Popular games:', data.data);
  });
```

### Search
```javascript
fetch('/api/games/search?q=minecraft')
  .then(res => res.json())
  .then(data => {
    console.log('Search results:', data.data);
  });
```

### Post a Comment
```javascript
fetch(`/api/games/${gameId}/comments`, {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
  },
  body: JSON.stringify({
    comment: 'Great game!'
  })
})
  .then(res => res.json())
  .then(data => {
    if (data.success) {
      console.log('Comment posted:', data.comment);
    }
  });
```

### Chat with AI
```javascript
fetch('/api/chat', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
  },
  body: JSON.stringify({
    message: 'Recommend some action games',
    conversationHistory: []
  })
})
  .then(res => res.json())
  .then(data => {
    console.log('AI Response:', data.response);
  });
```

## Rate Limiting

No rate limiting is currently implemented. Add using Laravel's `throttle` middleware if needed.

## CORS

CORS is configured to allow same-origin requests. For cross-origin requests, update `config/cors.php`.

## Versioning

Current version: v1 (implicit)
Future versions should use routes like `/api/v2/...`

## Notes

### Comments System
- Comments are tied to specific games via `game_id`
- Only authenticated users can post comments
- Users can delete their own comments
- Admins can delete any comment
- Comments are ordered by newest first (`created_at DESC`)
- Maximum comment length: 1000 characters

### AI Chatbot
- Requires user authentication
- Uses Google Gemini AI (gemini-2.0-flash model)
- Maintains conversation history for context-aware responses
- Conversation history should be sent with each request for continuity
- Provides game recommendations based on the review database

### Caching
- Popular games list: cached for 5 minutes
- Recent games list: cached for 5 minutes
- RAWG API responses: cached for 1 hour
- Cache automatically invalidated on create/update/delete operations

### Authentication
- Session-based authentication using Laravel's built-in auth system
- CSRF token required for all POST/PUT/DELETE requests
- Admin privileges required for game management (create/update/delete reviews)
- User authentication required for comments and AI chat

## Web Routes (Non-API)

```
GET  /                    → Homepage
GET  /login              → Login page
GET  /register           → Registration page
GET  /games              → Game details page (requires ?gameId parameter)
GET  /search             → Search results page (requires ?q parameter)
GET  /about              → About Us page
GET  /contact            → Contact Us page
GET  /admin/dashboard    → Admin dashboard (requires admin privileges)
POST /logout             → Logout (requires authentication)
GET  /language/{locale}  → Switch language (en/ar)
```
