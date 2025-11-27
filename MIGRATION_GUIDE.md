# PlayScore - Laravel Migration Guide

## Overview
Your PlayScore project has been successfully converted from a plain PHP/JS/HTML application to a modern Laravel framework-based application. All functionality has been preserved while gaining the benefits of Laravel's structure, security, and maintainability.

## What Was Changed

### 1. **Database**
- **Old**: `db.php` with mysqli connection class
- **New**: Laravel migrations with Eloquent ORM
  - Created two migrations:
    - `2025_11_26_create_users_table.php` - Users table
    - `2025_11_26_create_games_table.php` - Games/Reviews table

### 2. **Models**
- **User Model** (`app/Models/User.php`)
  - Uses `user_id` as primary key (matches your old schema)
  - Includes relationships to games
  - Password hashing with Laravel's security

- **Game Model** (`app/Models/Game.php`)
  - Uses `game_id` as primary key
  - Relationship to User (admin)
  - Automatic timestamp management

### 3. **Controllers**
- **AuthController** (`app/Http/Controllers/AuthController.php`)
  - Handles login, register, logout
  - Session management
  - Both form and JSON API responses

- **GameController** (`app/Http/Controllers/GameController.php`)
  - RESTFUL API endpoints for games
  - Search, sort, paginate functionality
  - RAWG API integration
  - Admin-only create/update/delete

### 4. **Routes**
- **Web Routes** (`routes/web.php`)
  - Page routes for all views
  - Authentication routes
  - Admin dashboard

- **API Routes** (`routes/api.php`)
  - JSON endpoints for JavaScript frontend
  - Session check endpoint
  - RESTful game management

### 5. **Views** (Blade Templates)
- `layouts/app.blade.php` - Main layout
- `layouts/navbar.blade.php` - Navigation
- `layouts/footer.blade.php` - Footer
- `index.blade.php` - Home page
- `games.blade.php` - Game details
- `search.blade.php` - Search page
- `auth/login.blade.php` - Login page
- `auth/register.blade.php` - Register page
- `admin/dashboard.blade.php` - Admin dashboard

### 6. **Assets**
- CSS files migrated to `public/css/`
  - `style.css` - Main stylesheet
  - `LoginStyle.css` - Authentication styling
  
- JavaScript files migrated to `public/js/` with updated API endpoints
  - `auth.js` - Authentication functions
  - `games.js` - Game loading and display
  - `search.js` - Search functionality
  - `gameDetails.js` - Game detail page

### 7. **Configuration**
- `.env` file configured with:
  - MySQL database settings pointing to `playscoredb`
  - RAWG API key
  - Laravel app configuration

## Setup Instructions

### Step 1: Install Dependencies
```bash
cd c:\laragon\www\CS383Project
composer install
npm install
```

### Step 2: Generate App Key
```bash
php artisan key:generate
```

### Step 3: Run Migrations
```bash
php artisan migrate
```

This will create the `users` and `games` tables in your `playscoredb` database.

### Step 4: Build Frontend Assets
```bash
npm run build
# or for development with watch:
npm run dev
```

### Step 5: Start the Application
Using Laragon's built-in server (recommended):
1. The app should be accessible at `http://CS383Project.local` or `http://localhost/CS383Project`

Or using Laravel's development server:
```bash
php artisan serve
```

## API Endpoints

### Authentication
- `POST /api/auth/register` - Register new user
- `POST /api/auth/login` - Login user
- `POST /api/auth/logout` - Logout user
- `GET /api/session-check` - Check if user is logged in

### Games (Public)
- `GET /api/games/popular` - Get popular games (sorted by rating)
- `GET /api/games/recent` - Get recent games
- `GET /api/games/search?q=query` - Search games
- `GET /api/games/{id}` - Get specific game review
- `GET /api/games/rawg/key` - Get RAWG API key

### Games (Admin Only)
- `POST /api/games` - Create new game review
- `PUT /api/games/{id}` - Update game review
- `DELETE /api/games/{id}` - Delete game review

## Database Schema

### Users Table
```sql
- user_id (PRIMARY KEY, AUTO INCREMENT)
- username (VARCHAR 50, UNIQUE)
- email (VARCHAR 100, UNIQUE)
- password (VARCHAR 255)
- is_admin (BOOLEAN, DEFAULT FALSE)
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)
```

### Games Table
```sql
- game_id (PRIMARY KEY, AUTO INCREMENT)
- rawg_id (INTEGER)
- game_title (VARCHAR 255)
- rating (INTEGER, CHECK 0-100)
- review_text (TEXT)
- admin_id (FOREIGN KEY -> users.user_id)
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)
```

## File Structure

```
CS383Project/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   └── GameController.php
│   │   └── Middleware/
│   │       └── EnsureUserIsAdmin.php
│   └── Models/
│       ├── User.php
│       └── Game.php
├── config/
│   └── services.php (RAWG API config)
├── database/
│   └── migrations/
│       ├── 2025_11_26_create_users_table.php
│       └── 2025_11_26_create_games_table.php
├── public/
│   ├── css/
│   │   ├── style.css
│   │   └── LoginStyle.css
│   └── js/
│       ├── auth.js
│       ├── games.js
│       ├── search.js
│       └── gameDetails.js
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── app.blade.php
│       │   ├── navbar.blade.php
│       │   └── footer.blade.php
│       ├── auth/
│       │   ├── login.blade.php
│       │   └── register.blade.php
│       ├── admin/
│       │   └── dashboard.blade.php
│       ├── index.blade.php
│       ├── games.blade.php
│       └── search.blade.php
├── routes/
│   ├── web.php
│   └── api.php
├── .env
└── ...
```

## Key Features Preserved

✅ User authentication (register/login/logout)
✅ Role-based access (admin vs regular user)
✅ Game review management
✅ Search functionality with filters
✅ Pagination
✅ RAWG API integration for game data
✅ Responsive UI with Bootstrap
✅ Dark theme design
✅ RESTful API endpoints

## New Features Added

✨ Database migrations for easy deployment
✨ Eloquent ORM for cleaner database queries
✨ Middleware for admin authorization
✨ CSRF protection on all forms
✨ API token support (Laravel Sanctum ready)
✨ Session management via Laravel
✨ Better error handling and logging
✨ Environment-based configuration

## Next Steps for Development

1. **Data Migration**: If you have existing data in `playscoredb`, you may need to migrate it manually or write a seeder.

2. **Testing**: Create tests for controllers and models using Laravel's testing framework.

3. **Deployment**: Use Laravel deployment best practices for production.

4. **Additional Features**:
   - Add user profiles
   - Add ratings/reviews on reviews
   - Add favorites functionality
   - Integrate AI chatbot (as per requirements)
   - Add multi-language support
   - Add accessibility features
   - Set up n8n automation

5. **Customization**: Update admin dashboard with more features as needed.

## Troubleshooting

### 404 errors on routes
- Make sure you've run `php artisan migrate`
- Clear the route cache: `php artisan route:clear`

### API endpoints returning 401/403
- Make sure you're logged in before accessing admin-only endpoints
- Check that your CSRF token is being sent with requests

### Database connection errors
- Verify `playscoredb` exists in MySQL
- Check `.env` database credentials match your setup

### JavaScript not loading
- Run `npm run build` to compile assets
- Check browser console for actual errors

## Support

For Laravel documentation: https://laravel.com/docs
For RAWG API: https://rawg.io/apidocs
