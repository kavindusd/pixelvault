SET FOREIGN_KEY_CHECKS = 0;

-- PixelVault Deployment Script for Hostinger
-- This script contains the table structure and essential seed data (Admin account & Site Config).
-- Existing user accounts, orders, and inquiries are excluded.

-- --------------------------------------------------------
-- CLEANUP: Drop existing tables in correct dependency order
-- --------------------------------------------------------
DROP TABLE IF EXISTS `purchases`;
DROP TABLE IF EXISTS `product_versions`;
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `products`;
DROP TABLE IF EXISTS `categories`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `admins`;
DROP TABLE IF EXISTS `site_configs`;
DROP TABLE IF EXISTS `inquiries`;
DROP TABLE IF EXISTS `schema_migrations`;

-- --------------------------------------------------------
-- Table structure for table `schema_migrations`
-- --------------------------------------------------------
CREATE TABLE `schema_migrations` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `migration` VARCHAR(255) NOT NULL UNIQUE,
    `executed_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `users`
-- --------------------------------------------------------
CREATE TABLE `users` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(120) NOT NULL,
    `email` VARCHAR(190) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `avatar_url` VARCHAR(255) NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 0,
    `verification_code` VARCHAR(10) NULL,
    `token_expires_at` DATETIME NULL,
    `reset_token` VARCHAR(255) NULL,
    `paypal_email` VARCHAR(255) NULL,
    `payhere_id` VARCHAR(255) NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `admins`
-- --------------------------------------------------------
CREATE TABLE `admins` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(120) NOT NULL,
    `email` VARCHAR(190) NOT NULL UNIQUE,
    `password_hash` VARCHAR(255) NOT NULL,
    `role` VARCHAR(50) NOT NULL DEFAULT 'Super Admin',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `categories`
-- --------------------------------------------------------
CREATE TABLE `categories` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(120) NOT NULL UNIQUE,
    `slug` VARCHAR(140) NOT NULL UNIQUE,
    `description` TEXT NULL,
    `icon` VARCHAR(50) DEFAULT 'Tag',
    `hue` VARCHAR(100) DEFAULT 'from-orange-400/20 to-orange-500/10',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `products`
-- --------------------------------------------------------
CREATE TABLE `products` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `category_id` BIGINT UNSIGNED NOT NULL,
    `title` VARCHAR(190) NOT NULL,
    `slug` VARCHAR(220) NOT NULL UNIQUE,
    `short_description` VARCHAR(280) NULL,
    `description` TEXT NOT NULL,
    `key_features` JSON NULL,
    `image_url` VARCHAR(255) NULL,
    `license_type` VARCHAR(120) NOT NULL DEFAULT 'GPLv3 - Unlimited Sites',
    `price` DECIMAL(10,2) NOT NULL,
    `discount_price` DECIMAL(10,2) NULL,
    `demo_url` VARCHAR(255) NULL,
    `file_path` VARCHAR(255) NOT NULL,
    `current_version` VARCHAR(50) NOT NULL,
    `last_updated_at` DATETIME NOT NULL,
    `technical_info` VARCHAR(255) NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `product_versions`
-- --------------------------------------------------------
CREATE TABLE `product_versions` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `product_id` BIGINT UNSIGNED NOT NULL,
    `version` VARCHAR(50) NOT NULL,
    `changelog` TEXT NULL,
    `file_path` VARCHAR(255) NOT NULL,
    `file_size_bytes` BIGINT UNSIGNED NULL,
    `uploaded_by` BIGINT UNSIGNED NULL,
    `is_current` TINYINT(1) NOT NULL DEFAULT 0,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT `fk_product_versions_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_product_versions_user` FOREIGN KEY (`uploaded_by`) REFERENCES `admins`(`id`),
    UNIQUE KEY `uq_product_version` (`product_id`, `version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `orders`
-- --------------------------------------------------------
CREATE TABLE `orders` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `order_number` VARCHAR(40) NOT NULL UNIQUE,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `total_amount` DECIMAL(10,2) NOT NULL,
    `payment_status` ENUM('pending', 'paid', 'failed', 'refunded') NOT NULL DEFAULT 'pending',
    `payment_method` VARCHAR(50) NULL,
    `purchase_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `purchases`
-- --------------------------------------------------------
CREATE TABLE `purchases` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `product_id` BIGINT UNSIGNED NOT NULL,
    `order_id` BIGINT UNSIGNED NOT NULL,
    `update_count` INT UNSIGNED NOT NULL DEFAULT 0,
    `max_update_downloads` INT UNSIGNED NOT NULL DEFAULT 3,
    `override_extra_downloads` INT UNSIGNED NOT NULL DEFAULT 0,
    `override_reason` TEXT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_purchases_user` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_purchases_product` FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_purchases_order` FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `uq_user_product` (`user_id`, `product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `site_configs`
-- --------------------------------------------------------
CREATE TABLE `site_configs` (
    `key` VARCHAR(50) NOT NULL PRIMARY KEY,
    `group` VARCHAR(50) NOT NULL DEFAULT 'general',
    `label` VARCHAR(100) NOT NULL,
    `type` VARCHAR(20) NOT NULL DEFAULT 'text',
    `value` TEXT NULL,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `inquiries`
-- --------------------------------------------------------
CREATE TABLE `inquiries` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(120) NOT NULL,
    `email` VARCHAR(190) NOT NULL,
    `subject` VARCHAR(190) NOT NULL,
    `message` TEXT NOT NULL,
    `type` VARCHAR(50) NOT NULL DEFAULT 'standard',
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Essential Seed Data
-- --------------------------------------------------------

-- Default Admin Account
-- Email: admin@pixelvault.app | Password: admin
INSERT INTO `admins` (`id`, `name`, `email`, `password_hash`, `role`)
VALUES (1, 'Admin User', 'admin@pixelvault.app', '$2y$10$u3RYypVU2.34WVcEQ08yLeb4PXbTghf8TvmcEosjM/JE4m5y6J/bW', 'Super Admin');

-- Default Categories
INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `icon`, `hue`)
VALUES
    (1, 'Page Builders', 'page-builders', 'Drag and drop page builder tools.', 'Layout', 'from-primary/20 to-primary/5'),
    (2, 'SEO & Marketing', 'seo-marketing', 'SEO optimization and marketing plugins.', 'Search', 'from-emerald-400/20 to-emerald-400/5'),
    (3, 'Performance', 'performance', 'Caching and speed optimization tools.', 'Zap', 'from-cyan-400/20 to-cyan-400/5'),
    (4, 'WooCommerce', 'woocommerce', 'E-commerce extensions and tools.', 'ShoppingBag', 'from-purple-400/20 to-purple-400/5'),
    (5, 'Themes', 'themes', 'Premium WordPress themes.', 'Palette', 'from-pink-400/20 to-pink-400/5'),
    (6, 'Security', 'security', 'Security and firewall plugins.', 'Shield', 'from-red-400/20 to-red-400/5');

-- Default Site Configs
INSERT INTO `site_configs` (`key`, `group`, `label`, `type`, `value`) VALUES
    ('site_name', 'branding', 'Site Name', 'text', 'PixelVault'),
    ('site_theme', 'branding', 'Color Theme', 'text', 'vivid_orange'),
    ('primary_color', 'branding', 'Primary Color', 'color', '#f97316'),
    ('site_tagline', 'branding', 'Site Tagline', 'textarea', 'Premium WordPress resources for less.'),
    ('home_hero_title', 'marketing', 'Hero Title', 'textarea', 'Unlock the Best Premium WordPress Resources'),
    ('home_hero_subtitle', 'marketing', 'Hero Subtitle', 'textarea', 'Download GPL themes, plugins, and builder templates for a fraction of the cost. Unlimited site usage.'),
    ('home_hero_cta_1', 'marketing', 'Hero CTA 1', 'text', 'Explore Marketplace'),
    ('home_hero_cta_2', 'marketing', 'Hero CTA 2', 'text', 'Watch a 90-sec demo →'),
    ('home_stat_1_val', 'marketing', 'Stat 1 Value', 'text', '12,400+'),
    ('home_stat_1_lbl', 'marketing', 'Stat 1 Label', 'text', 'Products'),
    ('home_stat_2_val', 'marketing', 'Stat 2 Value', 'text', '98.6%'),
    ('home_stat_2_lbl', 'marketing', 'Stat 1 Label', 'text', 'Uptime'),
    ('home_stat_3_val', 'marketing', 'Stat 3 Value', 'text', '4.9/5'),
    ('home_stat_3_lbl', 'marketing', 'Stat 1 Label', 'text', 'Rating'),
    ('support_email', 'general', 'Support Email', 'text', 'support@pixelvault.app'),
    ('admin_contact_email', 'general', 'Admin Contact Email', 'text', 'admin@pixelvault.app'),
    ('enable_update_notifications', 'marketing', 'Enable Update Notifications', 'text', '1'),
    ('smtp_host', 'general', 'SMTP Host', 'text', 'smtp.gmail.com'),
    ('smtp_port', 'general', 'SMTP Port', 'text', '587'),
    ('smtp_user', 'general', 'SMTP User', 'text', ''),
    ('smtp_pass', 'general', 'SMTP Pass', 'text', ''),
    ('smtp_encryption', 'general', 'SMTP Encryption (tls/ssl)', 'text', 'tls'),
    ('contact_address', 'general', 'Contact Address', 'textarea', '123 Pixel Street, NY 10001'),
    ('footer_copyright', 'branding', 'Footer Copyright', 'text', '© 2026 PixelVault. All rights reserved.'),
    ('footer_credits', 'branding', 'Footer Credits', 'text', 'Built with ♥ for creators.'),
    ('currency_symbol', 'general', 'Currency Symbol', 'text', '$'),
    ('max_downloads', 'general', 'Max Downloads (Update Count)', 'text', '3');

SET FOREIGN_KEY_CHECKS = 1;
