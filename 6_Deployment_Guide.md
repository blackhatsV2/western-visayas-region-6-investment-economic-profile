[⏮️ Previous: Database Documentation](5_Database_Documentation.md) &nbsp; | &nbsp; [🏠 Home / README](README.md) &nbsp; | &nbsp; [⏭️ Next: User Manual](7_User_Manual.md)

---

# Deployment Guide

## 1. Prerequisites
Before deploying the application, ensure the server meets the following requirements:
- PHP >= 8.2
- A Database Management System like MySQL / MariaDB or SQLite
- Web Server (Apache or Nginx)
- Composer
- Node.js & NPM (for frontend asset compilation)

## 2. Server Setup (Ubuntu / Debian Example)

### Step 1: Clone Repository
```bash
git clone <repository_url> /var/www/investment-economic-profile
cd /var/www/investment-economic-profile
```

### Step 2: Install PHP Dependencies
```bash
composer install --optimize-autoloader --no-dev
```

### Step 3: Install Frontend Dependencies & Compile
```bash
npm install
npm run build
```

### Step 4: Environment Configuration
Copy the `.env.example` file and configure your environment variables.
```bash
cp .env.example .env
```
Ensure you update the follow variables:
- `APP_ENV=production`
- `APP_URL=https://your-domain.com`
- `DB_*` (Database connection details)

### Step 5: Application Keys and Migrations
Generate the app key and migrate database structures.
```bash
php artisan key:generate
php artisan migrate --force
```

### Step 6: File Permissions
Ensure your webserver user (e.g., `www-data` or `nginx`) has ownership and read/write access to storage.
```bash
chown -R www-data:www-data storage/ bootstrap/cache/
```

### Step 7: Optimizations
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 3. Web Server Configuration (Nginx Example)
Point the root directory to the `/public` folder of your Laravel application.

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/investment-economic-profile/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-XSS-Protection "1; mode=block";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```
Reload Nginx service to finalize the settings.
