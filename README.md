# 🍽️ Lahore Catering — Custom PHP 8.x Web Application

A modern, standalone, custom-coded **PHP 8.x** website and content management system for **Lahore Catering** ([lahorecatering.nl](https://lahorecatering.nl/)), fully converted from WordPress with **zero WordPress dependencies**.

---

## ✨ Features

- **100% Content Preservation:** All 21 Dutch pages, 258 blog articles, 436 media records, categories, tags, menus, and food items preserved exactly in Dutch.
- **Pure Custom PHP 8.x Architecture:** Lightweight MVC pattern with native PDO, HTML5, CSS3, and Vanilla JavaScript.
- **Dual Database Support:**
  - **SQLite:** Pre-populated in `database/lahore_catering.sqlite` for instant zero-configuration local execution.
  - **MySQL / MariaDB:** Production-ready schema in `database/schema_mysql.sql` and full export in `database/lahore_catering_mysql.sql`.
- **Custom Admin Dashboard:**
  - Manage Dutch Pages, Blog Posts, Categories, Tags.
  - Media Library with ALT text editor and secure file uploader.
  - Form Inquiries viewer for contact and table reservations.
  - 301 Permanent Redirects and Navigation Menus manager.
- **Enterprise Security:**
  - `PASSWORD_BCRYPT` authentication with cost 12.
  - Anti-CSRF token verification on all POST forms.
  - Brute-force rate limiting (15-min lock on 5 failed attempts).
  - Prepared PDO SQL statements preventing SQL injection.
  - Output escaping with `htmlspecialchars()`.
- **Dynamic SEO Engine:**
  - Automated `<title>`, `<meta name="description">`, and `<link rel="canonical">`.
  - Open Graph and Twitter Card tags.
  - Schema.org JSON-LD structured data (`Restaurant` & `Article`).
- **Responsive Culinary Light UI:** Warm Ivory (`#FAF7F2`), Saffron Gold (`#D97706`), Terracotta Accent (`#E26D5C`), and Dark Slate (`#1E293B`).

---

## 🚀 Quick Start (Local Development)

### 1. Requirements
- PHP 8.0 or higher with PDO and SQLite/MySQL extensions enabled.

### 2. Run the Development Server
No database setup is required for local testing — SQLite is pre-configured:

```bash
php -S 127.0.0.1:8080 router.php
```

### 3. Access URLs
- **Main Website:** [http://127.0.0.1:8080/](http://127.0.0.1:8080/)
- **Blog Archive:** [http://127.0.0.1:8080/blog/](http://127.0.0.1:8080/blog/)
- **Catering Diensten:** [http://127.0.0.1:8080/catering/](http://127.0.0.1:8080/catering/)
- **Contact & Reserveren:** [http://127.0.0.1:8080/contact/](http://127.0.0.1:8080/contact/)
- **Admin Panel:** [http://127.0.0.1:8080/admin/](http://127.0.0.1:8080/admin/)

---

## 🔐 Admin Panel Credentials

- **URL:** `/admin/login.php`
- **Username:** `admin`
- **Password:** `Lahore2026!Admin`

---

## 🗄️ Production MySQL Setup

1. Create a database:
   ```sql
   CREATE DATABASE lahore_catering CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
2. Import the data dump:
   ```bash
   mysql -u [username] -p lahore_catering < database/lahore_catering_mysql.sql
   ```
3. Update `config/database.php`:
   ```php
   'default' => 'mysql',
   'connections' => [
       'mysql' => [
           'host'     => '127.0.0.1',
           'port'     => 3306,
           'database' => 'lahore_catering',
           'username' => 'your_db_user',
           'password' => 'your_db_password',
           'charset'  => 'utf8mb4'
       ]
   ]
   ```

---

## 📂 Project Structure

```text
├── admin/            # Custom PHP Admin CMS Dashboard
├── api/              # AJAX endpoints for contact & reservations
├── assets/           # CSS, Vanilla JS, official brand icons & logos
├── config/           # App settings & database connection factory
├── controllers/      # MVC Controllers (Home, Page, Blog)
├── database/         # MySQL schema, full MySQL dump & SQLite file
├── models/           # Data models (Page, Post, Media, SEO, Menu, Admin, etc.)
├── uploads/          # Preserved website images and media library
├── views/            # Layouts, components (header, footer), and page templates
├── index.php         # Front Controller & dynamic router
├── router.php        # CLI server routing handler
└── .htaccess         # Apache rewrite rules & security headers
```

---

## 📄 License
Proprietary — Developed for Lahore Catering ([lahorecatering.nl](https://lahorecatering.nl/)).
