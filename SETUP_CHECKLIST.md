# ✅ Setup Checklist

Use this checklist to ensure your blog application is properly set up.

## Prerequisites Check

- [ ] PHP 8.0 or higher installed
  ```bash
  php -v
  ```

- [ ] MySQL 8.0 or higher installed
  ```bash
  mysql --version
  ```

- [ ] Composer installed
  ```bash
  composer --version
  ```

- [ ] Node.js & NPM installed
  ```bash
  node -v && npm -v
  ```

- [ ] Apache/Nginx with mod_rewrite enabled

## Installation Steps

### 1. Dependencies
- [ ] Run `composer install`
- [ ] Verify vendor directory was created
- [ ] Run `npm install`
- [ ] Verify node_modules directory was created

### 2. Environment Configuration
- [ ] `.env` file exists in project root
- [ ] Updated `DB_HOST` in .env
- [ ] Updated `DB_NAME` in .env
- [ ] Updated `DB_USER` in .env
- [ ] Updated `DB_PASS` in .env
- [ ] Updated `APP_URL` if not using localhost:8000

### 3. CSS Build
- [ ] Run `npm run build`
- [ ] Verify `public/css/style.css` was created
- [ ] File size should be > 100KB

### 4. Database Setup
- [ ] MySQL service is running
- [ ] Can connect to MySQL with credentials
- [ ] Run `php database/migrate.php`
- [ ] See success messages for all 8 migrations
- [ ] Verify database `blog_db` was created
- [ ] Verify all 8 tables exist in database

### 5. Optional: Sample Data
- [ ] Run `mysql -u root -p blog_db < database/seeds/sample_data.sql`
- [ ] Verify categories were created
- [ ] Verify admin user was created
- [ ] Can login with admin@blog.com / admin123

### 6. Permissions
- [ ] Set storage/ permissions: `chmod -R 755 storage/`
- [ ] Set uploads/ permissions: `chmod -R 755 public/uploads/`
- [ ] Verify files can be created in storage/logs/
- [ ] Verify files can be uploaded to public/uploads/

### 7. Server Start
- [ ] Run `php -S localhost:8000 -t public`
- [ ] Server starts without errors
- [ ] Can access http://localhost:8000
- [ ] Homepage loads correctly

## Verification Tests

### Visual Checks
- [ ] Homepage displays without errors
- [ ] Tailwind CSS styles are loaded
- [ ] Font Awesome icons display
- [ ] Dark mode toggle works
- [ ] Navbar displays correctly
- [ ] Footer displays correctly

### Authentication Tests
- [ ] Can access /register page
- [ ] Can create new account
- [ ] Registration validation works
- [ ] Can login with created account
- [ ] Can logout
- [ ] Can access /login page

### Blog Post Tests
- [ ] Can access "New Post" when logged in
- [ ] TinyMCE editor loads
- [ ] Can create a blog post
- [ ] Can upload featured image
- [ ] Can select categories
- [ ] Post appears on homepage after creation
- [ ] Can view single post
- [ ] Can edit own post
- [ ] Can delete own post

### Social Features Tests
- [ ] Upvote button works
- [ ] Downvote button works
- [ ] Vote counts update correctly
- [ ] Like button works
- [ ] Like count updates
- [ ] Can add comment
- [ ] Comment appears immediately
- [ ] Can reply to comment
- [ ] Can delete own comment
- [ ] Bookmark button works

### Profile Tests
- [ ] Can access /profile
- [ ] User stats display correctly
- [ ] User's posts are listed
- [ ] Can edit profile
- [ ] Can update username
- [ ] Can update email
- [ ] Can upload avatar
- [ ] Changes save correctly

### Search & Navigation
- [ ] Search box works
- [ ] Search returns results
- [ ] Can browse by category
- [ ] Category filtering works
- [ ] Pagination works
- [ ] Related posts show on single post
- [ ] Trending posts sidebar works

### Mobile Responsiveness
- [ ] Mobile menu button works
- [ ] Layout is responsive on mobile
- [ ] All features work on mobile
- [ ] Touch interactions work

## Common Issues Resolution

### Issue: Can't connect to database
- [ ] Checked MySQL is running
- [ ] Verified credentials in .env
- [ ] Tested connection with mysql command
- [ ] Database name is correct

### Issue: Styles not loading
- [ ] Ran `npm run build`
- [ ] Checked public/css/style.css exists
- [ ] Cleared browser cache
- [ ] Checked for console errors

### Issue: 404 on all routes
- [ ] .htaccess exists in public/
- [ ] .htaccess exists in root/
- [ ] mod_rewrite is enabled
- [ ] Server is pointing to public/ directory

### Issue: File upload fails
- [ ] Permissions set on public/uploads/
- [ ] Directory exists and is writable
- [ ] PHP upload settings are correct
- [ ] File size is within limits

### Issue: Sessions not working
- [ ] PHP session directory is writable
- [ ] Session cookies are enabled in browser
- [ ] No errors in storage/logs/

## Production Deployment Checklist

Before deploying to production:

- [ ] Change `APP_ENV=production` in .env
- [ ] Change `APP_DEBUG=false` in .env
- [ ] Update `APP_URL` to production URL
- [ ] Use strong database password
- [ ] Change all default passwords
- [ ] Run `npm run build` (minified)
- [ ] Set proper file permissions
- [ ] Configure SSL/HTTPS
- [ ] Set up automatic backups
- [ ] Configure error logging
- [ ] Test all features in production
- [ ] Set up monitoring

## Security Checklist

- [ ] All default passwords changed
- [ ] .env file is not publicly accessible
- [ ] Strong database password used
- [ ] File upload validation working
- [ ] CSRF protection on all forms
- [ ] SQL injection prevention verified
- [ ] XSS prevention working
- [ ] Sessions are secure
- [ ] Error messages don't reveal sensitive info

## Performance Checklist

- [ ] Database has proper indexes
- [ ] Images are optimized
- [ ] CSS is minified (production)
- [ ] Caching headers configured
- [ ] No N+1 query issues
- [ ] Pagination working correctly

## Final Verification

- [ ] All features tested
- [ ] No console errors
- [ ] No PHP errors in logs
- [ ] Documentation reviewed
- [ ] README.md read
- [ ] INSTALLATION.md followed
- [ ] All tests passed

---

## Congratulations! 🎉

If all items are checked, your blog application is fully set up and ready to use!

### Next Steps:

1. ✍️ Create your first blog post
2. 🎨 Customize the design
3. 📝 Add more categories
4. 👥 Invite users
5. 🚀 Deploy to production

### Need Help?

- 📖 Read README.md
- 📋 Check INSTALLATION.md  
- 🚀 See QUICKSTART.md
- 📊 Review PROJECT_SUMMARY.md
- 🔍 Check storage/logs/ for errors

---

**Happy Blogging! 🎊**

