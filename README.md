# PHP Blog Application - Modern OOP MVC Architecture

## 📋 Project Overview

A modern, feature-rich blog application built with PHP following Object-Oriented Programming principles and MVC (Model-View-Controller) architectural pattern. This application demonstrates best practices in PHP development, security, and user experience.

## 🎯 Features

### Core Features
- ✅ **User Authentication System**
  - Secure registration with password hashing (bcrypt)
  - Login/Logout functionality
  - Session management
  - Password reset capability
  - Email verification (optional)

- ✅ **Blog Management**
  - Create, Read, Update, Delete (CRUD) operations
  - Rich text editor (TinyMCE) for content creation
  - Image upload with validation and optimization
  - Draft and publish status
  - Categories and tags
  - SEO-friendly URLs (slugs)

- ✅ **Social Interactions**
  - Upvote/Downvote system
  - Comment system with nested replies
  - Like/Unlike posts
  - Share functionality
  - View counter

- ✅ **User Features**
  - User profiles with avatars
  - Personal dashboard
  - Activity history
  - Saved/Bookmarked posts

### Additional Features
- 🔍 Search functionality with filters
- 📱 Responsive design (Mobile-first approach)
- 🎨 Dark/Light mode toggle
- 🔔 Real-time notifications
- 📊 Admin dashboard with analytics
- 🛡️ CSRF protection
- 🔒 XSS prevention
- 🚀 Performance optimization (lazy loading, caching)

## 🏗️ Architecture

### MVC Pattern Implementation

```
┌─────────────────────────────────────────────┐
│              Client Browser                  │
└─────────────┬───────────────────────────────┘
              │
              ▼
┌─────────────────────────────────────────────┐
│         Router (Front Controller)           │
│  - URL Parsing                              │
│  - Route Matching                           │
│  - Middleware Execution                     │
└─────────────┬───────────────────────────────┘
              │
              ▼
┌─────────────────────────────────────────────┐
│            Controllers                       │
│  - Handle HTTP Requests                     │
│  - Business Logic Coordination              │
│  - Input Validation                         │
└─────────────┬───────────────────────────────┘
              │
    ┌─────────┴─────────┐
    ▼                   ▼
┌─────────┐         ┌─────────┐
│ Models  │◄────────┤  Views  │
│         │         │         │
│ - DB    │         │ - HTML  │
│   Logic │         │ - CSS   │
│ - Data  │         │ - JS    │
│   Valid.│         │         │
└─────────┘         └─────────┘
```

### Directory Structure

```
Blog/
├── app/
│   ├── Controllers/          # Application controllers
│   │   ├── AuthController.php
│   │   ├── BlogController.php
│   │   ├── CommentController.php
│   │   └── UserController.php
│   ├── Models/               # Data models
│   │   ├── User.php
│   │   ├── Blog.php
│   │   ├── Comment.php
│   │   ├── Like.php
│   │   └── Vote.php
│   ├── Views/                # View templates
│   │   ├── layouts/
│   │   │   ├── header.php
│   │   │   ├── footer.php
│   │   │   └── navbar.php
│   │   ├── auth/
│   │   │   ├── login.php
│   │   │   └── register.php
│   │   ├── blog/
│   │   │   ├── index.php
│   │   │   ├── show.php
│   │   │   ├── create.php
│   │   │   └── edit.php
│   │   └── user/
│   │       ├── profile.php
│   │       └── dashboard.php
│   └── Middleware/           # Middleware classes
│       ├── AuthMiddleware.php
│       └── CsrfMiddleware.php
├── core/                     # Core framework classes
│   ├── Application.php       # Application bootstrap
│   ├── Router.php           # Routing system
│   ├── Controller.php       # Base controller
│   ├── Model.php            # Base model
│   ├── Database.php         # Database connection
│   ├── Request.php          # HTTP request handler
│   ├── Response.php         # HTTP response handler
│   ├── Session.php          # Session management
│   ├── Validation.php       # Input validation
│   └── View.php             # View renderer
├── public/                   # Public directory (document root)
│   ├── index.php            # Entry point
│   ├── css/
│   │   └── style.css        # Compiled Tailwind CSS
│   ├── js/
│   │   ├── app.js
│   │   └── editor.js
│   ├── images/              # Static images
│   └── uploads/             # User uploaded files
│       ├── avatars/
│       └── blog-images/
├── config/                   # Configuration files
│   ├── database.php
│   ├── app.php
│   └── routes.php
├── database/
│   ├── migrations/          # Database migrations
│   │   ├── 001_create_users_table.sql
│   │   ├── 002_create_blogs_table.sql
│   │   ├── 003_create_comments_table.sql
│   │   ├── 004_create_likes_table.sql
│   │   └── 005_create_votes_table.sql
│   └── seeds/               # Database seeders
│       └── sample_data.sql
├── helpers/                  # Helper functions
│   ├── functions.php
│   └── sanitize.php
├── storage/
│   ├── logs/                # Application logs
│   └── cache/               # Cache files
├── .env                      # Environment variables
├── .env.example             # Example environment file
├── .gitignore
├── .htaccess                # Apache configuration
├── composer.json            # PHP dependencies
├── package.json             # Node.js dependencies
├── tailwind.config.js       # Tailwind configuration
└── README.md
```

## 🔧 Technology Stack

### Backend
- **PHP 8.0+** - Server-side programming language
- **MySQL 8.0+** - Relational database
- **PDO** - Database abstraction layer
- **Composer** - Dependency management

### Frontend
- **Tailwind CSS 3.x** - Utility-first CSS framework
- **Alpine.js** - Lightweight JavaScript framework
- **TinyMCE** - Rich text editor
- **Font Awesome** - Icon library

### Development Tools
- **Git** - Version control
- **Node.js & NPM** - Frontend tooling
- **Apache/Nginx** - Web server

## 🔐 Security Features

### Implemented Security Measures

1. **Authentication Security**
   - Password hashing using `password_hash()` with bcrypt
   - Secure session management
   - Session regeneration on login
   - HTTP-only and secure cookies

2. **Input Validation & Sanitization**
   - Server-side validation for all inputs
   - XSS prevention using `htmlspecialchars()`
   - SQL injection prevention using prepared statements
   - File upload validation (type, size, extension)

3. **CSRF Protection**
   - Token-based CSRF protection
   - Token regeneration per form
   - Token validation on state-changing operations

4. **Database Security**
   - PDO prepared statements
   - Parameterized queries
   - Limited database user privileges
   - Environment-based credentials

5. **File Upload Security**
   - File type validation
   - File size limits
   - Sanitized file names
   - Stored outside document root
   - Random filename generation

6. **Headers & Configuration**
   - X-Frame-Options: DENY
   - X-XSS-Protection: 1; mode=block
   - X-Content-Type-Options: nosniff
   - Content-Security-Policy
   - Secure error handling (production vs development)

## 📊 Database Schema

### Users Table
```sql
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    avatar VARCHAR(255),
    bio TEXT,
    role ENUM('user', 'admin') DEFAULT 'user',
    is_verified BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### Blogs Table
```sql
CREATE TABLE blogs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    content LONGTEXT NOT NULL,
    excerpt TEXT,
    featured_image VARCHAR(255),
    status ENUM('draft', 'published') DEFAULT 'draft',
    views INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### Comments Table
```sql
CREATE TABLE comments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    blog_id INT NOT NULL,
    user_id INT NOT NULL,
    parent_id INT DEFAULT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (blog_id) REFERENCES blogs(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_id) REFERENCES comments(id) ON DELETE CASCADE
);
```

### Likes Table
```sql
CREATE TABLE likes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    blog_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_like (user_id, blog_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (blog_id) REFERENCES blogs(id) ON DELETE CASCADE
);
```

### Votes Table (Upvote/Downvote System)
```sql
CREATE TABLE votes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    blog_id INT NOT NULL,
    vote_type ENUM('upvote', 'downvote') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_vote (user_id, blog_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (blog_id) REFERENCES blogs(id) ON DELETE CASCADE
);
```

### Categories Table
```sql
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) UNIQUE NOT NULL,
    slug VARCHAR(100) UNIQUE NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### Blog_Categories Table (Many-to-Many)
```sql
CREATE TABLE blog_categories (
    blog_id INT NOT NULL,
    category_id INT NOT NULL,
    PRIMARY KEY (blog_id, category_id),
    FOREIGN KEY (blog_id) REFERENCES blogs(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
);
```

### Bookmarks Table
```sql
CREATE TABLE bookmarks (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    blog_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_bookmark (user_id, blog_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (blog_id) REFERENCES blogs(id) ON DELETE CASCADE
);
```

## 🚀 Installation & Setup

### Prerequisites
- PHP >= 8.0
- MySQL >= 8.0
- Composer
- Node.js >= 14.x
- Apache/Nginx with mod_rewrite enabled

### Step 1: Clone Repository
```bash
git clone https://github.com/yourusername/php-blog-mvc.git
cd php-blog-mvc
```

### Step 2: Install PHP Dependencies
```bash
composer install
```

### Step 3: Install Frontend Dependencies
```bash
npm install
```

### Step 4: Build Tailwind CSS
```bash
npm run build
```

### Step 5: Configure Environment
```bash
cp .env.example .env
```

Edit `.env` file with your database credentials:
```env
DB_HOST=localhost
DB_NAME=blog_db
DB_USER=root
DB_PASS=
DB_PORT=3306

APP_URL=http://localhost
APP_ENV=development
APP_DEBUG=true

SESSION_LIFETIME=7200
```

### Step 6: Create Database
```bash
mysql -u root -p
```

```sql
CREATE DATABASE blog_db;
USE blog_db;
```

### Step 7: Run Migrations
```bash
php database/migrate.php
```

Or manually import:
```bash
mysql -u root -p blog_db < database/migrations/001_create_users_table.sql
mysql -u root -p blog_db < database/migrations/002_create_blogs_table.sql
# ... import all migration files
```

### Step 8: Set Permissions
```bash
chmod -R 755 storage/
chmod -R 755 public/uploads/
```

### Step 9: Start Development Server
```bash
php -S localhost:8000 -t public
```

Visit `http://localhost:8000` in your browser.

## 📖 Usage Guide

### For Users

1. **Registration**
   - Navigate to `/register`
   - Fill in username, email, and password
   - Submit to create account

2. **Login**
   - Navigate to `/login`
   - Enter credentials
   - Access authenticated features

3. **Creating a Blog Post**
   - Click "New Post" button
   - Enter title and content using rich text editor
   - Upload featured image
   - Select categories
   - Save as draft or publish

4. **Interacting with Posts**
   - Upvote/Downvote posts
   - Like posts
   - Add comments
   - Share posts
   - Bookmark for later

### For Developers

#### Creating a New Controller
```php
<?php

namespace App\Controllers;

use Core\Controller;

class MyController extends Controller
{
    public function index()
    {
        $data = [
            'title' => 'My Page',
            'items' => []
        ];
        
        return $this->view('my/index', $data);
    }
}
```

#### Creating a New Model
```php
<?php

namespace App\Models;

use Core\Model;

class MyModel extends Model
{
    protected $table = 'my_table';
    protected $fillable = ['column1', 'column2'];
    
    public function customMethod()
    {
        // Custom logic
    }
}
```

#### Adding a Route
```php
// config/routes.php
$router->get('/my-route', 'MyController@index');
$router->post('/my-route', 'MyController@store');
```

## 🎨 Design Patterns Used

1. **MVC Pattern** - Separation of concerns
2. **Singleton Pattern** - Database connection
3. **Factory Pattern** - Object creation
4. **Repository Pattern** - Data access layer
5. **Middleware Pattern** - Request filtering
6. **Observer Pattern** - Event handling
7. **Dependency Injection** - Loose coupling

## 🔄 API Endpoints

### Authentication
- `POST /register` - User registration
- `POST /login` - User login
- `POST /logout` - User logout

### Blogs
- `GET /blogs` - List all blogs
- `GET /blog/{slug}` - View single blog
- `POST /blog/create` - Create new blog
- `PUT /blog/{id}` - Update blog
- `DELETE /blog/{id}` - Delete blog

### Social Interactions
- `POST /blog/{id}/vote` - Upvote/Downvote
- `POST /blog/{id}/like` - Like/Unlike
- `POST /blog/{id}/comment` - Add comment
- `POST /blog/{id}/bookmark` - Bookmark post

## 📈 Performance Optimization

1. **Database Optimization**
   - Proper indexing on frequently queried columns
   - Query optimization with EXPLAIN
   - Pagination for large datasets
   - Eager loading to prevent N+1 queries

2. **Caching Strategy**
   - Page caching for static content
   - Query result caching
   - Redis/Memcached integration (optional)

3. **Frontend Optimization**
   - Lazy loading images
   - Minified CSS/JS
   - CDN for static assets
   - Browser caching headers

4. **Code Optimization**
   - Autoloading with Composer (PSR-4)
   - Opcode caching (OPcache)
   - Reduced database queries
   - Efficient algorithms

## 🧪 Testing

### Manual Testing Checklist
- [ ] User registration works correctly
- [ ] Login/logout functionality
- [ ] Blog CRUD operations
- [ ] Image upload validation
- [ ] Comment system
- [ ] Vote system accuracy
- [ ] Like functionality
- [ ] Search functionality
- [ ] Responsive design on mobile
- [ ] Security measures (XSS, CSRF, SQL injection)

### Automated Testing (Future Implementation)
- PHPUnit for unit testing
- Selenium for browser testing
- API testing with Postman

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📝 Coding Standards

- Follow PSR-12 coding standards
- Use meaningful variable and function names
- Comment complex logic
- Keep functions small and focused
- Write reusable code
- Follow SOLID principles

## 🐛 Known Issues & Future Enhancements

### Known Issues
- None currently

### Future Enhancements
- [ ] Email notifications
- [ ] Real-time chat
- [ ] Multi-language support
- [ ] Advanced search with Elasticsearch
- [ ] API versioning
- [ ] Mobile app (React Native)
- [ ] AI-powered content suggestions
- [ ] Analytics dashboard
- [ ] Content moderation tools
- [ ] RSS feed generation

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 👨‍💻 Author

**Your Name**
- GitHub: [@yourusername](https://github.com/yourusername)
- Email: your.email@example.com

## 🙏 Acknowledgments

- TinyMCE for the rich text editor
- Tailwind CSS for the amazing utility-first CSS framework
- Font Awesome for beautiful icons
- PHP community for best practices and patterns

## 📚 Resources & References

### PHP MVC Architecture
- [PHP The Right Way](https://phptherightway.com/)
- [PSR Standards](https://www.php-fig.org/psr/)
- [SOLID Principles in PHP](https://scotch.io/bar-talk/s-o-l-i-d-the-first-five-principles-of-object-oriented-design)

### Security
- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [PHP Security Cheat Sheet](https://cheatsheetseries.owasp.org/cheatsheets/PHP_Configuration_Cheat_Sheet.html)

### Design Patterns
- [Design Patterns in PHP](https://refactoring.guru/design-patterns/php)
- [PHP Design Patterns](https://designpatternsphp.readthedocs.io/)

### Tailwind CSS
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [Tailwind UI Components](https://tailwindui.com/)

---

## 🚦 Project Status

**Status:** Active Development 🟢

Last Updated: October 2025

---

**Happy Coding! 🚀**

#   D i g i l e n s  
 