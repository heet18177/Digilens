# Quick Deployment Checklist for InfinityFree

Use this checklist to deploy your PHP Blog to InfinityFree step by step.

## Pre-Deployment Preparation

- [ ] Run `npm run build` to compile Tailwind CSS
- [ ] Test your site locally one last time
- [ ] Prepare all files for upload (exclude `node_modules/`, `.git/`)

## Step 1: InfinityFree Account Setup

- [ ] Sign up / Log in at https://www.infinityfree.com/
- [ ] Create a new hosting account (choose subdomain or use your domain)
- [ ] Create MySQL database
- [ ] **Write down database credentials:**
  - [ ] Database Host: `sqlXXX.infinityfree.com`
  - [ ] Database Name: `epiz_XXXXXXX_dbname`
  - [ ] Database Username: `epiz_XXXXXXX`
  - [ ] Database Password: `_________________`
- [ ] **Write down FTP credentials:**
  - [ ] FTP Host: `ftpupload.net`
  - [ ] FTP Username: `epiz_XXXXXXX`
  - [ ] FTP Password: `_________________`

## Step 2: Upload Files

- [ ] Install FileZilla (or similar FTP client)
- [ ] Connect to FTP server
- [ ] Upload all files to web root (`htdocs/` or `public_html/`)
- [ ] Verify `vendor/` folder is uploaded
- [ ] Verify `.env` file is uploaded
- [ ] Verify `storage/` and `public/uploads/` folders exist

## Step 3: Configure Environment

- [ ] Edit `.env` file on server
- [ ] Update `APP_URL` to your InfinityFree domain
- [ ] Update `DB_HOST` with your database host
- [ ] Update `DB_NAME` with your database name
- [ ] Update `DB_USER` with your database username
- [ ] Update `DB_PASS` with your database password
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`

## Step 4: Set File Permissions

- [ ] Set `storage/` folder to **755** or **777**
- [ ] Set `public/uploads/` folder to **755** or **777**
- [ ] Set `.env` file to **644**

## Step 5: Set Up Database

- [ ] Open phpMyAdmin from InfinityFree control panel
- [ ] Select your database
- [ ] Import migration files in order (001, 002, 003, ...)
- [ ] (Optional) Import sample data
- [ ] Verify tables are created

## Step 6: Test Website

- [ ] Visit your website URL
- [ ] Check if homepage loads
- [ ] Test user registration
- [ ] Test user login
- [ ] Test creating a blog post
- [ ] Test image upload
- [ ] Verify CSS is loading
- [ ] Check all routes work

## Step 7: Enable SSL

- [ ] Go to InfinityFree control panel
- [ ] Enable free SSL certificate
- [ ] Wait for activation (5-10 minutes)
- [ ] Update `.env` to use `https://`
- [ ] Test HTTPS connection

## Step 8: Security

- [ ] Change default admin password (if sample data was imported)
- [ ] Verify `APP_DEBUG=false`
- [ ] Verify `.env` permissions are secure (644)
- [ ] Test that errors don't display to users

## Troubleshooting

If something doesn't work:

- [ ] Check InfinityFree control panel error logs
- [ ] Verify database credentials in `.env`
- [ ] Check file permissions on `storage/` and `uploads/`
- [ ] Verify `.htaccess` files are uploaded correctly
- [ ] Check if `vendor/` folder is complete
- [ ] Clear browser cache

---

**✅ Deployment Complete!**

Your blog should now be live at: `https://yourdomain.infinityfreeapp.com`

