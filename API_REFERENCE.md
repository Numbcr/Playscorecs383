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

## Rate Limiting

No rate limiting is currently implemented. Add using Laravel's `throttle` middleware if needed.

## CORS

CORS is configured to allow same-origin requests. For cross-origin requests, update `config/cors.php`.

## Versioning

Current version: v1 (implicit)
Future versions should use routes like `/api/v2/...`
