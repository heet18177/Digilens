# Deployment Guide: InfinityFree Hosting

This guide will help you deploy your PHP Blog Application to InfinityFree hosting.

## Prerequisites

Before starting, ensure you have:
- ✅ A free InfinityFree account ([sign up here](https://www.infinityfree.com/))
- ✅ FTP client (FileZilla, WinSCP, or similar)
- ✅ All project files ready
- ✅ Database backup ready

---

## Step 1: Prepare Your Project for Deployment

### 1.1 Build Tailwind CSS for Production

On your local machine, run:

```bash
npm run build
```

This creates a minified `public/css/style.css` file that will be used in production.

### 1.2 Create `.env` File (if it doesn't exist)

Create a `.env` file in the project root with a template. You'll update it with InfinityFree credentials later:

```env
# Application Configuration
APP_NAME="Blog Application"
APP_URL=https://yourdomain.infinityfreeapp.com
APP_ENV=production
APP_DEBUG=false

# Database Configuration
DB_HOST=sqlXXX.infinityfree.com
DB_NAME=epiz_XXXXXXX_dbname
DB_USER=epiz_XXXXXXX
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

### 1.3 Update Error Reporting for Production

Make sure `public/index.php` has production-safe error settings (already configured, but verify):

```php
// In production, these should be:
error_reporting(E_ALL);
ini_set('display_errors', 0); // Hide errors from users
ini_set('log_errors', 1);
```

### 1.4 Prepare Files to Upload

Files to upload:
- ✅ All PHP files (app/, core/, config/, helpers/, public/)
- ✅ `vendor/` folder (Composer dependencies)
- ✅ `composer.json` and `composer.lock`
- ✅ `.htaccess` files
- ✅ `.env` file (keep secure!)
- ✅ `storage/` folder (must be writable)
- ✅ `public/uploads/` folder (must be writable)

Files NOT to upload:
- ❌ `node_modules/` (not needed on server)
- ❌ `.git/` folder
- ❌ Development files

---

## Step 2: Create InfinityFree Account & Database

### 2.1 Sign Up / Login

1. Go to [InfinityFree](https://www.infinityfree.com/)
2. Sign up for a free account (or log in if you already have one)
3. Verify your email address

### 2.2 Create a Hosting Account

1. Log into your InfinityFree Control Panel (https://infinityfree.net/)
2. Click **"Create Account"** or **"Add Website"**
3. Choose a subdomain (e.g., `myblog.infinityfreeapp.com`) or use your own domain
4. Note your FTP credentials (you'll need these later)

### 2.3 Create MySQL Database

1. In your InfinityFree control panel, go to **"MySQL Databases"**
2. Click **"Create MySQL Database"**
3. Note down these details (you'll need them for `.env`):
   - **Database Host:** `sqlXXX.infinityfree.com` (usually sqlXXX.infinityfree.com)
   - **Database Name:** `epiz_XXXXXXX_dbname` (format: epiz_ followed by numbers)
   - **Database Username:** `epiz_XXXXXXX` (usually same as database name)
   - **Database Password:** (the one you created)
   - **Port:** `3306` (default)

**Important:** Write down these database credentials! You'll need them in Step 4.

---

## Step 3: Upload Files via FTP

### 3.1 Get FTP Credentials

From your InfinityFree control panel:
1. Go to **"FTP Accounts"**
2. Note your:
   - **FTP Host:** `ftpupload.net` or similar
   - **FTP Username:** `epiz_XXXXXXX_username`
   - **FTP Password:** (your FTP password)
   - **FTP Port:** `21` (or `21`)

### 3.2 Connect via FTP Client

#### Using FileZilla (Recommended)

1. Download and install [FileZilla](https://filezilla-project.org/)
2. Open FileZilla
3. Enter your FTP credentials:
   - **Host:** `ftpupload.net` (or your FTP host)
   - **Username:** Your FTP username
   - **Password:** Your FTP password
   - **Port:** `21`
4. Click **"Quickconnect"**

#### Using WinSCP (Windows)

1. Download [WinSCP](https://winscp.net/)
2. Enter your FTP details and connect

### 3.3 Upload Project Files

**Important Directory Structure:**
- InfinityFree's web root is usually `htdocs/` or `public_html/`
- You need to upload your `public/` folder contents to the web root
- Upload other folders (app/, core/, vendor/, etc.) to a parent directory

**Recommended Structure on Server:**
```
htdocs/
├── index.php (from public/index.php)
├── css/
│   └── style.css
├── js/
├── uploads/
└── .htaccess (from public/.htaccess)

htdocs_parent/
├── app/
├── core/
├── config/
├── vendor/
├── storage/
├── helpers/
├── database/
├── .env
├── .htaccess (root .htaccess)
└── composer.json
```

**Upload Steps:**

1. Navigate to your web root folder (`htdocs/` or `public_html/`)

2. **Option A: Upload public/ contents directly to web root**
   - Upload all files from `public/` to the web root
   - Upload `app/`, `core/`, `config/`, `vendor/`, `helpers/`, `storage/`, `database/`, `composer.json`, `.env`, and root `.htaccess` to the parent directory or same level

3. **Option B: Upload entire project structure**
   - Upload the entire project maintaining folder structure
   - Adjust paths in `public/index.php` if needed

**Verify Upload:**
- Check that `vendor/autoload.php` exists
- Check that `.env` file exists
- Check that `storage/` and `public/uploads/` folders exist

---

## Step 4: Configure Environment File

### 4.1 Update `.env` File on Server

After uploading, edit the `.env` file on your server with your InfinityFree database credentials:

```env
# Application Configuration
APP_NAME="Blog Application"
APP_URL=https://yourdomain.infinityfreeapp.com
APP_ENV=production
APP_DEBUG=false

# Database Configuration (Use your InfinityFree MySQL credentials)
DB_HOST=sqlXXX.infinityfree.com
DB_NAME=epiz_XXXXXXX_dbname
DB_USER=epiz_XXXXXXX
DB_PASS=your_database_password
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

**Replace:**
- `sqlXXX.infinityfree.com` with your database host
- `epiz_XXXXXXX_dbname` with your database name
- `epiz_XXXXXXX` with your database username
- `your_database_password` with your database password
- `https://yourdomain.infinityfreeapp.com` with your actual domain

### 4.2 Set File Permissions

Using FTP client or File Manager:
- Set `storage/` folder permissions to **755** or **777** (writable)
- Set `public/uploads/` folder permissions to **755** or **777** (writable)
- Set `.env` file permissions to **644** (readable, but secure)

---

## Step 5: Set Up Database

### 5.1 Import Database Schema

You have two options:

#### Option A: Using phpMyAdmin (Recommended)

1. Log into InfinityFree control panel
2. Go to **"phpMyAdmin"** or **"MySQL Databases"** → **"phpMyAdmin"**
3. Select your database from the left sidebar
4. Click **"Import"** tab
5. Click **"Choose File"** and select all migration files:
   - `database/migrations/001_create_users_table.sql`
   - `database/migrations/002_create_blogs_table.sql`
   - `database/migrations/003_create_comments_table.sql`
   - `database/migrations/004_create_likes_table.sql`
   - `database/migrations/005_create_votes_table.sql`
   - `database/migrations/006_create_categories_table.sql`
   - `database/migrations/007_create_blog_categories_table.sql`
   - `database/migrations/008_create_bookmarks_table.sql`
6. Run them in order (001, then 002, then 003, etc.)

Or use the migrate script if accessible:

#### Option B: Using Migration Script (if accessible)

1. Access your site via browser: `https://yourdomain.infinityfreeapp.com/database/migrate.php`
2. Run the migration script (you may need to adjust paths)

### 5.2 (Optional) Import Sample Data

If you want sample data:

1. Open phpMyAdmin
2. Select your database
3. Go to **"Import"**
4. Upload `database/seeds/sample_data.sql`
5. Click **"Go"**

Default admin credentials (if you imported sample data):
- Email: `admin@blog.com`
- Password: `admin123`

**⚠️ Change this immediately after first login!**

---

## Step 6: Configure .htaccess Files

### 6.1 Root .htaccess (if needed)

If your project structure requires routing from root, ensure `.htaccess` in root contains:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

### 6.2 Public .htaccess

Ensure `public/.htaccess` (or web root `.htaccess`) contains:

```apache
RewriteEngine On

# Handle Authorization Header
RewriteCond %{HTTP:Authorization} .
RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

# Redirect Trailing Slashes If Not A Folder...
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_URI} (.+)/$
RewriteRule ^ %1 [L,R=301]

# Send Requests To Front Controller...
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^ index.php [L]
```

---

## Step 7: Test Your Deployment

### 7.1 Access Your Site

Visit your domain:
- `https://yourdomain.infinityfreeapp.com`

### 7.2 Common Issues & Fixes

#### Issue: "500 Internal Server Error"
**Solutions:**
- Check `.htaccess` file syntax
- Verify file permissions (folders: 755, files: 644)
- Check error logs in InfinityFree control panel
- Verify `.env` file exists and has correct credentials

#### Issue: "Database Connection Failed"
**Solutions:**
- Double-check database credentials in `.env`
- Verify database host is correct (usually `sqlXXX.infinityfree.com`)
- Ensure database exists in phpMyAdmin
- Check database user has proper permissions

#### Issue: "File Upload Not Working"
**Solutions:**
- Check `public/uploads/` folder permissions (set to 777)
- Verify `storage/` folder permissions (set to 777)
- Check PHP upload limits in InfinityFree control panel

#### Issue: "Composer Autoload Error"
**Solutions:**
- Ensure `vendor/` folder was uploaded completely
- Re-upload `vendor/` folder if missing
- Run `composer install` via SSH if available

#### Issue: "CSS Not Loading"
**Solutions:**
- Verify `npm run build` was run before upload
- Check `public/css/style.css` exists and is uploaded
- Clear browser cache
- Verify file paths are correct

---

## Step 8: Enable SSL/HTTPS (Recommended)

InfinityFree provides free SSL certificates:

1. Log into InfinityFree control panel
2. Go to **"SSL Certificates"** or **"Security"**
3. Enable **"Free SSL"** or **"Let's Encrypt SSL"**
4. Wait a few minutes for certificate activation
5. Update `.env` file: `APP_URL=https://yourdomain.infinityfreeapp.com`
6. Uncomment HTTPS redirect in `.htaccess`:

```apache
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

---

## Step 9: Post-Deployment Checklist

- [ ] Site is accessible via browser
- [ ] Database connection works
- [ ] Can register/login users
- [ ] Can create blog posts
- [ ] File uploads work (images)
- [ ] CSS styling loads correctly
- [ ] All routes work (no 404 errors)
- [ ] SSL/HTTPS enabled
- [ ] Default passwords changed
- [ ] Error logging configured
- [ ] `APP_DEBUG=false` in production

---

## Additional Notes

### InfinityFree Limitations (Free Tier)

- ⚠️ **No SSH Access:** Cannot run `composer install` directly on server
- ⚠️ **Upload Limit:** Max file size uploads may be limited
- ⚠️ **Inactivity:** Site may go offline after 6 months of inactivity
- ⚠️ **Database Size:** Limited database size (usually 50-100MB)
- ⚠️ **Bandwidth:** Limited monthly bandwidth

### Updating Your Site

When making updates:

1. **Local changes:** Make changes on your local machine
2. **Build assets:** Run `npm run build` for CSS changes
3. **Upload files:** Upload only changed files via FTP
4. **Clear cache:** Clear browser cache if needed

### Backing Up

Regular backups are essential:

1. **Database:** Export via phpMyAdmin regularly
2. **Files:** Download `storage/` and `public/uploads/` folders
3. **Code:** Keep a Git repository or local backup

---

## Troubleshooting Resources

- **InfinityFree Support:** https://forum.infinityfree.com/
- **Control Panel:** https://infinityfree.net/
- **Documentation:** Check your project's README.md and INSTALLATION.md

---

## Security Recommendations

1. ✅ Set `APP_DEBUG=false` in production
2. ✅ Use strong database passwords
3. ✅ Change default admin credentials immediately
4. ✅ Enable HTTPS/SSL
5. ✅ Keep `.env` file secure (permissions 644)
6. ✅ Regularly update dependencies
7. ✅ Back up database regularly

---

**🎉 Congratulations! Your blog is now live on InfinityFree!**

If you encounter any issues, check the error logs in your InfinityFree control panel or refer to the troubleshooting section above.

