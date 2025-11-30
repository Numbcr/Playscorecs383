# Team Setup Guide

## For Teammates: Getting Started Locally

### 1. Clone the Repository
```bash
git clone https://github.com/Numbcr/Playscorecs383.git
cd Playscorecs383
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure Database
Edit `.env` file:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=playscorecs383
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Run Migrations & Seeders
```bash
# Create database tables
php artisan migrate

# Seed with test data
php artisan db:seed
```

### 6. Get RAWG API Key
1. Go to https://rawg.io/api
2. Get your free API key
3. Add to `.env`:
```
RAWG_API_KEY=your_api_key_here
```

### 7. Start Development Server
```bash
php artisan serve
# Visit http://localhost:8000
```

### 8. Login with Test Credentials
- **Admin**: admin@playscorecs383.test / password
- **User**: test@playscorecs383.test / password

## Database Sharing Options

### Option A: Via Database Dump (SQL File)
```bash
# Export database
mysqldump -u root -p playscorecs383 > database.sql

# Share via GitHub/Drive, then import:
mysql -u root -p playscorecs383 < database.sql
```

### Option B: Via Git + Seeders (Recommended)
All teammates just run:
```bash
php artisan migrate:fresh --seed
```

This automatically:
- Creates all tables
- Adds 2 test users (admin + regular)
- Seeds 5 sample games with reviews

### Option C: Via GitHub Actions
Team members can pull latest changes:
```bash
git pull origin main
php artisan migrate
php artisan db:seed
```

## Collaboration Tips

1. **Always pull latest changes**: `git pull origin main`
2. **Create feature branches**: `git checkout -b feature/your-feature`
3. **Test locally before pushing**
4. **Keep .env files private** (add to .gitignore)
5. **Share database exports only when needed**

## Troubleshooting

### Database connection error
- Check MySQL is running
- Verify DB credentials in .env
- Ensure database exists: `CREATE DATABASE playscorecs383;`

### Migration fails
```bash
# Rollback and try again
php artisan migrate:rollback
php artisan migrate:fresh --seed
```

### API key issues
- Make sure RAWG_API_KEY is in .env
- Check it's valid at https://rawg.io/api

