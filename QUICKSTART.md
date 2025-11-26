# Quick Start Guide

Get your blog up and running in 5 minutes!

## Prerequisites

- PHP 8.0+
- MySQL 8.0+
- Composer
- Node.js & NPM

## Installation Steps

### 1. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 2. Build CSS

```bash
npm run build
```

### 3. Configure Environment

The `.env` file is already created. Update your database credentials:

```env
DB_HOST=localhost
DB_NAME=blog_db
DB_USER=root
DB_PASS=your_password
```

### 4. Create Database & Run Migrations

```bash
php database/migrate.php
```

### 5. (Optional) Seed Sample Data

```bash
mysql -u root -p blog_db < database/seeds/sample_data.sql
```

### 6. Start Server

```bash
php -S localhost:8000 -t public
```

### 7. Open Browser

Navigate to: `http://localhost:8000`

## Default Credentials (if seeded)

**Admin:**
- Email: admin@blog.com
- Password: admin123

**User:**
- Email: john@example.com
- Password: user123

## Features

✅ User registration and login  
✅ Create, edit, delete blog posts  
✅ Rich text editor (TinyMCE)  
✅ Image uploads  
✅ Upvote/Downvote system  
✅ Like posts  
✅ Comment system with nested replies  
✅ Bookmark posts  
✅ Categories and tags  
✅ Search functionality  
✅ User profiles  
✅ Dark mode  
✅ Responsive design  
✅ Social sharing  

## Project Structure

```
Blog/
├── app/               # Application code
│   ├── Controllers/   # Route controllers
│   ├── Models/        # Database models
│   ├── Views/         # View templates
│   └── Middleware/    # Middleware classes
├── core/              # Core framework
├── config/            # Configuration files
├── database/          # Migrations and seeds
├── public/            # Public assets (entry point)
└── storage/           # Logs and cache
```

## Common Tasks

### Create a New Blog Post

1. Register/Login
2. Click "New Post"
3. Fill in title and content
4. Upload featured image (optional)
5. Select categories
6. Choose "Published" or "Draft"
7. Click "Create Post"

### Customize Styling

Edit `src/input.css` and rebuild:
```bash
npm run build
```

### Add New Routes

Edit `config/routes.php`:
```php
$router->get('/my-route', 'MyController@method');
```

### Create New Controller

```bash
# Create file: app/Controllers/MyController.php
```

```php
<?php
namespace App\Controllers;
use Core\Controller;

class MyController extends Controller
{
    public function method()
    {
        return $this->view('my-view', ['data' => 'value']);
    }
}
```

## Troubleshooting

**Can't connect to database?**
- Check credentials in `.env`
- Ensure MySQL is running

**Styles not loading?**
- Run `npm run build`
- Check `public/css/style.css` exists

**File upload not working?**
- Check permissions: `chmod -R 755 public/uploads/`

**404 on all routes?**
- Ensure `.htaccess` exists in `public/`
- Enable mod_rewrite on Apache

## Next Steps

1. Change default passwords
2. Add more categories
3. Customize the theme
4. Add your logo
5. Configure production settings

## Need Help?

- Read the full [README.md](README.md)
- Check [INSTALLATION.md](INSTALLATION.md)
- Review code comments
- Check `storage/logs/` for errors

---

**Happy Blogging! 🚀**

