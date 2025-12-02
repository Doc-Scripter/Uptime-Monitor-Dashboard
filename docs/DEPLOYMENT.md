# Deployment Guide

## Requirements

- **PHP**: 8.2+
- **Node.js**: 20+
- **Database**: MySQL 8.0+ or MariaDB 10.5+
- **Web Server**: Nginx or Apache
- **Composer**: 2.x

## Backend Setup

1. **Clone the repository**
   ```bash
   git clone <repo-url>
   cd backend
   ```

2. **Install Dependencies**
   ```bash
   composer install --optimize-autoloader --no-dev
   ```

3. **Environment Configuration**
   ```bash
   cp .env.example .env
   # Edit .env with your database credentials
   php artisan key:generate
   ```

4. **Database Migration**
   ```bash
   php artisan migrate --force
   ```

5. **Directory Permissions**
   Ensure `storage` and `bootstrap/cache` are writable:
   ```bash
   chmod -R 775 storage bootstrap/cache
   chown -R www-data:www-data storage bootstrap/cache
   ```

6. **Scheduler**
   Add the following cron entry to run the scheduler every minute:
   ```bash
   * * * * * cd /path/to/backend && php artisan schedule:run >> /dev/null 2>&1
   ```

## Frontend Setup

1. **Navigate to frontend directory**
   ```bash
   cd frontend
   ```

2. **Install Dependencies**
   ```bash
   npm install
   ```

3. **Build Assets**
   ```bash
   npm run build
   ```

4. **Serve Assets**
   The built files in `frontend/dist` should be served by your web server. You can copy them to the Laravel `public` directory or configure Nginx to serve them directly.

## Web Server Configuration (Nginx Example)

```nginx
server {
    listen 80;
    server_name monitor.example.com;
    root /path/to/backend/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # Serve frontend assets if copied to public/assets
    # or configure a separate location block

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
