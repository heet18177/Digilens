# Installation Guide - PHP Blog Application

This guide will walk you through the complete setup process for the PHP Blog Application.

## Prerequisites

Before you begin, ensure you have the following installed on your system:

- **PHP 8.0 or higher**
- **MySQL 8.0 or higher**
- **Composer** (PHP dependency manager)
- **Node.js & NPM** (for Tailwind CSS compilation)
- **Apache/Nginx** web server with mod_rewrite enabled

## Step-by-Step Installation

### 1. Clone or Download the Project

```bash
# If using Git
git clone https://github.com/yourusername/php-blog-mvc.git
cd php-blog-mvc

# Or extract the downloaded ZIP file and navigate to the directory
```

### 2. Install PHP Dependencies

```bash
composer install
```

This will install the necessary PHP packages including:
- vlucas/phpdotenv for environment variable management

### 3. Install Frontend Dependencies

```bash
npm install
```

This will install Tailwind CSS and its dependencies.

### 4. Build Tailwind CSS

```bash
# For development (with watching)
npm run dev

# For production (minified)
npm run build
```

### 5. Configure Environment

```bash
# The .env file should already exist, but if not:
cp .env.example .env
```

Edit the `.env` file with your database credentials:

```env
# Application Configuration
APP_NAME="Blog Application"
APP_URL=http://localhost:8000
APP_ENV=development
APP_DEBUG=true

# Database Configuration
DB_HOST=localhost
DB_NAME=blog_db
DB_USER=root
DB_PASS=your_password_here
DB_PORT=3306

# Session Configuration
SESSION_LIFETIME=7200
SESSION_NAME=blog_session

# Upload Configuration
MAX_UPLOAD_SIZE=5242880
ALLOWED_IMAGE_TYPES=jpg,jpeg,png,gif,webp

# Pagination
POSTS_PER_PAGE=10
COMMENTS_PER_PAGE=20

# Security
CSRF_TOKEN_NAME=csrf_token
HASH_COST=12
```

### 6. Create Database

Option 1: Using MySQL Command Line
```bash
mysql -u root -p
```

```sql
CREATE DATABASE blog_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
exit;
```

Option 2: Using phpMyAdmin
- Open phpMyAdmin
- Click "New" to create a new database
- Name it `blog_db`
- Select `utf8mb4_unicode_ci` as collation
- Click "Create"

### 7. Run Database Migrations

```bash
php database/migrate.php
```

You should see output like:
```
Creating database 'blog_db'...
Database created/selected successfully.

Running migration: 001_create_users_table.sql...
✓ Migration completed: 001_create_users_table.sql

Running migration: 002_create_blogs_table.sql...
✓ Migration completed: 002_create_blogs_table.sql

...

🎉 All migrations completed successfully!
```

### 8. (Optional) Seed Sample Data

To populate the database with sample categories and admin user:

```bash
mysql -u root -p blog_db < database/seeds/sample_data.sql
```

Default admin credentials:
- **Email:** admin@blog.com
- **Password:** admin123

Default user credentials:
- **Email:** john@example.com
- **Password:** user123

### 9. Set Proper Permissions

```bash
# On Linux/Mac
chmod -R 755 storage/
chmod -R 755 public/uploads/

# On Windows (using PowerShell as Administrator)
icacls storage /grant Users:F /T
icacls public\uploads /grant Users:F /T
```

### 10. Start Development Server

```bash
# Using PHP built-in server
php -S localhost:8000 -t public

# Or configure Apache/Nginx (see below)
```

### 11. Access the Application

Open your browser and navigate to:
```
http://localhost:8000
```

You should see the blog homepage!

## Web Server Configuration

### Apache Configuration

Create a `.htaccess` file in the project root (if not already present):

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

The `public/.htaccess` file is already configured.

#### Virtual Host Configuration

Add this to your Apache virtual hosts configuration:

```apache
<VirtualHost *:80>
    ServerName blog.local
    DocumentRoot "/path/to/php-blog-mvc/public"
    
    <Directory "/path/to/php-blog-mvc/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    ErrorLog "/path/to/php-blog-mvc/storage/logs/error.log"
    CustomLog "/path/to/php-blog-mvc/storage/logs/access.log" combined
</VirtualHost>
```

Don't forget to add to `/etc/hosts`:
```
127.0.0.1   blog.local
```

### Nginx Configuration

Add this server block to your Nginx configuration:

```nginx
server {
    listen 80;
    server_name blog.local;
    root /path/to/php-blog-mvc/public;
    
    index index.php index.html;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    location ~ /\.ht {
        deny all;
    }
    
    error_log /path/to/php-blog-mvc/storage/logs/error.log;
    access_log /path/to/php-blog-mvc/storage/logs/access.log;
}
```

## Troubleshooting

### Common Issues

#### 1. Database Connection Error

**Error:** `Database connection failed`

**Solution:**
- Verify your database credentials in `.env`
- Ensure MySQL is running: `sudo systemctl status mysql`
- Test connection: `mysql -u root -p`

#### 2. File Upload Issues

**Error:** `Failed to upload file`

**Solution:**
- Check directory permissions: `chmod -R 755 public/uploads/`
- Verify PHP upload settings in `php.ini`:
  ```ini
  upload_max_filesize = 10M
  post_max_size = 10M
  ```

#### 3. Tailwind CSS Not Loading

**Error:** Styles not appearing

**Solution:**
```bash
# Rebuild Tailwind CSS
npm run build

# Check if style.css exists
ls -la public/css/style.css
```

#### 4. mod_rewrite Not Working

**Error:** 404 errors on all pages except homepage

**Solution:**
```bash
# Enable mod_rewrite on Apache
sudo a2enmod rewrite
sudo systemctl restart apache2

# Verify .htaccess is being read
```

#### 5. Composer Install Fails

**Error:** `composer: command not found` or package errors

**Solution:**
```bash
# Install Composer
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"
sudo mv composer.phar /usr/local/bin/composer

# Then run install again
composer install
```

#### 6. Session Issues

**Error:** Not staying logged in

**Solution:**
- Check session permissions
- Verify `session.save_path` in `php.ini`
- Clear browser cookies

## Development Workflow

### Running in Development

```bash
# Terminal 1: Watch Tailwind CSS changes
npm run dev

# Terminal 2: Start PHP server
php -S localhost:8000 -t public
```

### Production Deployment

1. **Build assets:**
   ```bash
   npm run build
   ```

2. **Update `.env`:**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   ```

3. **Set proper permissions:**
   ```bash
   chmod -R 755 storage/
   chmod -R 755 public/uploads/
   ```

4. **Configure your web server** (Apache/Nginx)

5. **Enable HTTPS** (recommended)

## Next Steps

After installation:

1. **Change default passwords** if you seeded sample data
2. **Create your first blog post**
3. **Customize the design** by editing Tailwind classes
4. **Add more categories** through the database
5. **Configure email settings** (for future email features)

## Support

If you encounter any issues:

1. Check the `storage/logs/` directory for error logs
2. Enable debug mode in `.env`: `APP_DEBUG=true`
3. Refer to the [README.md](README.md) for more information
4. Open an issue on GitHub

## Security Recommendations

1. **Change default admin password immediately**
2. **Use strong database passwords**
3. **Enable HTTPS in production**
4. **Keep PHP and dependencies updated**
5. **Set `APP_DEBUG=false` in production**
6. **Restrict database user privileges**
7. **Regular backups of database and uploads**

---

**Congratulations! Your blog is now up and running! 🎉**

Start creating amazing content and enjoy your new blogging platform!

