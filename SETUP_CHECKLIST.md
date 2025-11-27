# PlayScore Laravel Setup Checklist

## Pre-Setup Requirements
- [x] Laravel 11+ installed
- [x] Composer installed
- [x] Node.js and npm installed
- [x] MySQL/Laragon running
- [x] Database `playscoredb` created

## Step 1: Install Dependencies
```bash
cd c:\laragon\www\CS383Project
composer install
npm install
```
- [ ] No errors during composer install
- [ ] No errors during npm install

## Step 2: Environment Configuration
- [ ] Verify `.env` file exists
- [ ] Check DB_CONNECTION=mysql
- [ ] Check DB_DATABASE=playscoredb
- [ ] Check DB_USERNAME=root (or your username)
- [ ] Check DB_PASSWORD is correct (empty if using default)
- [ ] Check SERVICES_RAWG_API_KEY is set

```bash
php artisan key:generate
```
- [ ] APP_KEY generated successfully

## Step 3: Database Setup
```bash
php artisan migrate
```
- [ ] `users` table created
- [ ] `games` table created
- [ ] No migration errors

Verify in MySQL:
```sql
USE playscoredb;
SHOW TABLES;
DESC users;
DESC games;
```
- [ ] Both tables exist
- [ ] Columns match schema

## Step 4: Build Frontend Assets
```bash
npm run build
```
Or for development:
```bash
npm run dev
```
- [ ] CSS compiled successfully
- [ ] JavaScript bundled successfully
- [ ] No build errors

## Step 5: Test the Application

### Via Laragon
- [ ] Application accessible at `http://CS383Project.local`
- [ ] Home page loads
- [ ] Navigation bar appears
- [ ] Search form visible

### Or via Laravel Server
```bash
php artisan serve
```
- [ ] Server starts on `http://127.0.0.1:8000`
- [ ] Home page loads
- [ ] No 404 errors

## Step 6: Test Features

### Authentication
- [ ] Register link works
- [ ] Can create new account
- [ ] Login page accessible
- [ ] Can login with credentials
- [ ] Logout button appears
- [ ] Can logout successfully

### Game Features
- [ ] Home page shows "Highest Reviews" section
- [ ] Home page shows "Recent Reviews" section
- [ ] Can see game cards with ratings
- [ ] Search bar is visible
- [ ] Can search for games (requires data in DB)
- [ ] Can click game to see details
- [ ] Game details page shows full info from RAWG API

### Admin Features (if logged in as admin)
- [ ] Admin dashboard accessible
- [ ] Can create new game reviews
- [ ] Can update reviews
- [ ] Can delete reviews

## Step 7: Browser Console Check
- [ ] No JavaScript errors
- [ ] No CORS errors
- [ ] API calls successful (check Network tab)

## Step 8: Database Verification

Test with sample data:
```bash
php artisan tinker
>>> User::create(['username' => 'admin', 'email' => 'admin@example.com', 'password' => Hash::make('password'), 'is_admin' => true])
>>> User::all()
```
- [ ] Can create users via tinker
- [ ] Can query users
- [ ] is_admin field working

## Step 9: API Testing

Test API endpoints:
```bash
curl http://CS383Project.local/api/games/popular
curl http://CS383Project.local/api/session-check
```
- [ ] API returns JSON
- [ ] Response structure correct
- [ ] No 404 errors

## Step 10: Performance Check
- [ ] Page loads in < 2 seconds
- [ ] Images load properly
- [ ] No console warnings
- [ ] Responsive design works on mobile view (F12 -> Device toolbar)

## Troubleshooting

### 404 Errors on Routes
```bash
php artisan route:clear
php artisan cache:clear
php artisan config:clear
```
- [ ] Errors resolved

### Database Connection Issues
```bash
php artisan migrate:status
php artisan db:seed
```
- [ ] Connection successful

### JavaScript Not Loading
```bash
npm run build
php artisan view:clear
```
- [ ] Assets loaded

### Session Issues
```bash
php artisan session:table
php artisan migrate
```
- [ ] Session table created

## Deployment Checklist (for later)

- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Generate new APP_KEY
- [ ] Configure proper database
- [ ] Set up HTTPS
- [ ] Configure email (MAIL_*)
- [ ] Test all features in production mode
- [ ] Set up monitoring/logging
- [ ] Configure backup strategy

## Optional: Seed Sample Data

Create a seeder:
```bash
php artisan make:seeder GameSeeder
```

Then run:
```bash
php artisan db:seed
```

## Documentation Files

- [ ] Read `CONVERSION_SUMMARY.md`
- [ ] Read `MIGRATION_GUIDE.md`
- [ ] Read `API_REFERENCE.md`
- [ ] Bookmark Laravel docs: https://laravel.com/docs

## Next Development Steps

After everything is working:

1. **Dashboard Customization**
   - [ ] Add stats to admin dashboard
   - [ ] Add user management
   - [ ] Add game management interface

2. **Feature Implementation**
   - [ ] Add ratings system
   - [ ] Add user profiles
   - [ ] Add favorites
   - [ ] Add comments

3. **Enhancement**
   - [ ] Add unit tests
   - [ ] Add API documentation (Swagger)
   - [ ] Optimize database queries
   - [ ] Add caching

4. **Advanced Features**
   - [ ] Multi-language support
   - [ ] AI chatbot integration
   - [ ] Automation workflows (n8n)
   - [ ] Analytics dashboard

## Support Resources

- Laravel Documentation: https://laravel.com/docs
- RAWG API: https://rawg.io/apidocs
- Bootstrap 5: https://getbootstrap.com/docs
- MySQL: https://dev.mysql.com/doc/

## Status

Start from Step 1 and work through each step, checking boxes as you go.

**Total estimated time**: 15-30 minutes for complete setup

---

Good luck! 🚀
