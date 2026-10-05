-- ========================================================
-- Lahore Catering Database Schema for MySQL / MariaDB
-- Converted from WordPress to Custom PHP Architecture
-- ========================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- 1. Pages Table
CREATE TABLE IF NOT EXISTS `pages` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(191) NOT NULL,
  `content` LONGTEXT NULL,
  `excerpt` TEXT NULL,
  `featured_image` VARCHAR(255) NULL,
  `featured_image_alt` VARCHAR(255) NULL,
  `template` VARCHAR(100) DEFAULT 'default',
  `status` ENUM('published', 'draft') DEFAULT 'published',
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_pages_slug` (`slug`),
  KEY `idx_pages_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. Posts Table (Blog)
CREATE TABLE IF NOT EXISTS `posts` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(191) NOT NULL,
  `content` LONGTEXT NULL,
  `excerpt` TEXT NULL,
  `author` VARCHAR(100) DEFAULT 'Lahore Catering',
  `featured_image` VARCHAR(255) NULL,
  `featured_image_alt` VARCHAR(255) NULL,
  `featured_image_caption` TEXT NULL,
  `status` ENUM('published', 'draft') DEFAULT 'published',
  `views` INT UNSIGNED DEFAULT 0,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_posts_slug` (`slug`),
  KEY `idx_posts_status` (`status`),
  KEY `idx_posts_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Categories Table
CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(191) NOT NULL,
  `description` TEXT NULL,
  `parent_id` INT UNSIGNED DEFAULT 0,
  `count` INT UNSIGNED DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_categories_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 4. Tags Table
CREATE TABLE IF NOT EXISTS `tags` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(191) NOT NULL,
  `count` INT UNSIGNED DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_tags_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 5. Post Categories Pivot
CREATE TABLE IF NOT EXISTS `post_categories` (
  `post_id` INT UNSIGNED NOT NULL,
  `category_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`post_id`, `category_id`),
  KEY `idx_pc_category` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 6. Post Tags Pivot
CREATE TABLE IF NOT EXISTS `post_tags` (
  `post_id` INT UNSIGNED NOT NULL,
  `tag_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`post_id`, `tag_id`),
  KEY `idx_pt_tag` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 7. Media Library Table
CREATE TABLE IF NOT EXISTS `media` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `filename` VARCHAR(255) NOT NULL,
  `filepath` VARCHAR(255) NOT NULL,
  `url` VARCHAR(255) NOT NULL,
  `alt_text` VARCHAR(255) NULL,
  `caption` TEXT NULL,
  `mime_type` VARCHAR(100) NULL,
  `file_size` INT UNSIGNED DEFAULT 0,
  `width` INT UNSIGNED DEFAULT 0,
  `height` INT UNSIGNED DEFAULT 0,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_media_filename` (`filename`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 8. Menus Table
CREATE TABLE IF NOT EXISTS `menus` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `location` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 9. Menu Items Table
CREATE TABLE IF NOT EXISTS `menu_items` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `menu_id` INT UNSIGNED NOT NULL,
  `title` VARCHAR(100) NOT NULL,
  `url` VARCHAR(255) NOT NULL,
  `target` VARCHAR(20) DEFAULT '_self',
  `order_index` INT UNSIGNED DEFAULT 0,
  `parent_id` INT UNSIGNED DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_menu_id` (`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 10. Food Items Table
CREATE TABLE IF NOT EXISTS `food_items` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(191) NOT NULL,
  `description` TEXT NULL,
  `price` DECIMAL(10,2) DEFAULT NULL,
  `category` VARCHAR(100) DEFAULT 'General',
  `image_url` VARCHAR(255) NULL,
  `image_alt` VARCHAR(255) NULL,
  `is_featured` TINYINT(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_food_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 11. Settings Table
CREATE TABLE IF NOT EXISTS `settings` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `setting_key` VARCHAR(100) NOT NULL,
  `setting_value` LONGTEXT NULL,
  `setting_group` VARCHAR(50) DEFAULT 'general',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_settings_key` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 12. Forms Table
CREATE TABLE IF NOT EXISTS `forms` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `form_code` VARCHAR(50) NOT NULL,
  `fields_json` LONGTEXT NULL,
  `recipient_email` VARCHAR(100) NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_form_code` (`form_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 13. Form Submissions Table
CREATE TABLE IF NOT EXISTS `form_submissions` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `form_id` INT UNSIGNED DEFAULT 1,
  `form_type` VARCHAR(50) NOT NULL,
  `data_json` LONGTEXT NOT NULL,
  `ip_address` VARCHAR(45) NULL,
  `user_agent` VARCHAR(255) NULL,
  `status` ENUM('new', 'read', 'archived') DEFAULT 'new',
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_fs_type` (`form_type`),
  KEY `idx_fs_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 14. Admins / Users Table
CREATE TABLE IF NOT EXISTS `admins` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `password_hash` VARCHAR(255) NOT NULL,
  `role` VARCHAR(20) DEFAULT 'admin',
  `last_login` DATETIME NULL,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_admins_username` (`username`),
  UNIQUE KEY `uk_admins_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 15. Redirects Table
CREATE TABLE IF NOT EXISTS `redirects` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `source_url` VARCHAR(191) NOT NULL,
  `target_url` VARCHAR(255) NOT NULL,
  `status_code` INT UNSIGNED DEFAULT 301,
  `hits` INT UNSIGNED DEFAULT 0,
  `created_at` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_redirects_source` (`source_url`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 16. SEO Metadata Table
CREATE TABLE IF NOT EXISTS `seo_metadata` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `entity_type` VARCHAR(50) NOT NULL,
  `entity_id` INT UNSIGNED NOT NULL,
  `meta_title` VARCHAR(255) NULL,
  `meta_description` TEXT NULL,
  `canonical_url` VARCHAR(255) NULL,
  `og_title` VARCHAR(255) NULL,
  `og_description` TEXT NULL,
  `og_image` VARCHAR(255) NULL,
  `twitter_title` VARCHAR(255) NULL,
  `twitter_description` TEXT NULL,
  `twitter_image` VARCHAR(255) NULL,
  `schema_json` LONGTEXT NULL,
  `robots` VARCHAR(100) DEFAULT 'index, follow',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_seo_entity` (`entity_type`, `entity_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;
