-- ============================================================
-- PixelVault Full Database Setup
-- ============================================================
-- For LOCAL development: uncomment the CREATE DATABASE lines below.
-- For HOSTINGER (shared hosting): leave them commented out.
-- The database must already exist on shared hosting.
-- ============================================================

CREATE DATABASE IF NOT EXISTS pixelvault_db
CHARACTER SET utf8mb4
COLLATE utf8mb4_unicode_ci;
USE pixelvault_db;

SET FOREIGN_KEY_CHECKS = 0;

-- ============================================================
-- DROP ALL TABLES (clean slate)
-- ============================================================
DROP TABLE IF EXISTS admin_action_logs;
DROP TABLE IF EXISTS update_notifications;
DROP TABLE IF EXISTS email_templates;
DROP TABLE IF EXISTS user_payment_methods;
DROP TABLE IF EXISTS user_payment_methods_old;
DROP TABLE IF EXISTS admin_payment_methods;
DROP TABLE IF EXISTS inquiries;
DROP TABLE IF EXISTS site_configs;
DROP TABLE IF EXISTS purchases;
DROP TABLE IF EXISTS orders;
DROP TABLE IF EXISTS product_versions;
DROP TABLE IF EXISTS products;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS admins;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS schema_migrations;

-- ============================================================
-- CREATE TABLES (verified against PHP model queries)
-- ============================================================

-- USERS
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    avatar_url VARCHAR(255) NULL,
    verification_code VARCHAR(10) NULL,
    reset_token VARCHAR(100) NULL,
    token_expires_at DATETIME NULL,
    address_country VARCHAR(100) NULL,
    address_city VARCHAR(100) NULL,
    address_apartment VARCHAR(100) NULL,
    address_postal_code VARCHAR(50) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ADMINS
CREATE TABLE admins (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'Super Admin',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- CATEGORIES
CREATE TABLE categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL UNIQUE,
    slug VARCHAR(140) NOT NULL UNIQUE,
    description TEXT NULL,
    icon VARCHAR(50) NOT NULL DEFAULT 'Tag',
    hue VARCHAR(100) NOT NULL DEFAULT 'from-orange-400/20 to-orange-500/10',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- PRODUCTS
CREATE TABLE products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(190) NOT NULL,
    slug VARCHAR(220) NOT NULL UNIQUE,
    short_description VARCHAR(280) NULL,
    description TEXT NOT NULL,
    key_features TEXT NULL,
    image_url VARCHAR(255) NULL,
    license_type VARCHAR(120) NOT NULL DEFAULT 'GPLv3 - Unlimited Sites',
    price DECIMAL(10,2) NOT NULL,
    discount_price DECIMAL(10,2) NULL,
    demo_url VARCHAR(255) NULL,
    file_path VARCHAR(255) NOT NULL DEFAULT '',
    current_version VARCHAR(50) NOT NULL,
    last_updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    technical_info VARCHAR(255) NOT NULL DEFAULT 'PHP 8.1+ / WP 6.0+',
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- PRODUCT VERSIONS
CREATE TABLE product_versions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED NOT NULL,
    version VARCHAR(50) NOT NULL,
    changelog TEXT NULL,
    file_path VARCHAR(255) NOT NULL DEFAULT '',
    file_size_bytes BIGINT UNSIGNED NULL,
    uploaded_by BIGINT UNSIGNED NULL,
    is_current TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_product_version (product_id, version),
    CONSTRAINT fk_pv_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    CONSTRAINT fk_pv_admin FOREIGN KEY (uploaded_by) REFERENCES admins(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ORDERS
CREATE TABLE orders (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(40) NOT NULL UNIQUE,
    user_id BIGINT UNSIGNED NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    payment_status ENUM('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending',
    payment_method VARCHAR(50) NULL,
    purchase_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- PURCHASES
CREATE TABLE purchases (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    order_id BIGINT UNSIGNED NOT NULL,
    update_count INT UNSIGNED NOT NULL DEFAULT 0,
    max_update_downloads INT UNSIGNED NOT NULL DEFAULT 3,
    purchased_version VARCHAR(30) NOT NULL DEFAULT '1.0.0',
    downloaded_versions TEXT NULL,
    override_extra_downloads INT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_user_product (user_id, product_id),
    CONSTRAINT fk_purchases_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_purchases_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    CONSTRAINT fk_purchases_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- SITE CONFIGS
CREATE TABLE site_configs (
    `key` VARCHAR(50) NOT NULL PRIMARY KEY,
    `group` VARCHAR(50) NOT NULL DEFAULT 'general',
    `label` VARCHAR(100) NOT NULL,
    `type` VARCHAR(20) NOT NULL DEFAULT 'text',
    `value` TEXT NULL,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- INQUIRIES
CREATE TABLE inquiries (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL,
    subject VARCHAR(190) NOT NULL,
    message TEXT NOT NULL,
    type VARCHAR(50) NOT NULL DEFAULT 'standard',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ADMIN PAYMENT METHODS
CREATE TABLE admin_payment_methods (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    provider ENUM('paypal','payhere','visa','mastercard') NOT NULL,
    account_identifier VARCHAR(190) NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- USER PAYMENT METHODS
CREATE TABLE user_payment_methods (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    provider ENUM('paypal','payhere','visa','mastercard') NOT NULL,
    account_identifier VARCHAR(190) NOT NULL,
    is_default TINYINT(1) NOT NULL DEFAULT 0,
    details TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_upm_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- SEED DATA
-- ============================================================

-- Admin (password = admin123)
INSERT INTO admins (id, name, email, password_hash, role) VALUES
(1, 'Super Admin', 'admin@pixelvault.app', '$2y$10$u3RYypVU2.34WVcEQ08yLeb4PXbTghf8TvmcEosjM/JE4m5y6J/bW', 'Super Admin');

-- Demo User (password = password123)
INSERT INTO users (id, name, email, password_hash, avatar_url) VALUES
(1, 'Demo Member', 'demo@pixelvault.app', '$2y$10$ByZ3.Fti6dXUxXP5JHf9Aein8u7L6OVGm/rL6xIuZ15xJ2LmEH04W', 'https://ui-avatars.com/api/?name=Demo+Member&background=FBBC05&color=fff');

-- Categories
INSERT INTO categories (id, name, slug, description, icon, hue) VALUES
(1, 'Page Builders', 'page-builders', 'Drag and drop page builder tools.', 'Layout', 'from-primary/20 to-primary/5'),
(2, 'SEO & Marketing', 'seo-marketing', 'SEO optimization and marketing plugins.', 'Search', 'from-emerald-400/20 to-emerald-400/5'),
(3, 'Performance', 'performance', 'Caching and speed optimization tools.', 'Zap', 'from-cyan-400/20 to-cyan-400/5'),
(4, 'WooCommerce', 'woocommerce', 'E-commerce extensions and tools.', 'ShoppingBag', 'from-purple-400/20 to-purple-400/5'),
(5, 'Themes', 'themes', 'Premium WordPress themes.', 'Palette', 'from-pink-400/20 to-pink-400/5'),
(6, 'Security', 'security', 'Security and firewall plugins.', 'Shield', 'from-red-400/20 to-red-400/5');

-- Products
INSERT INTO products (id, category_id, title, slug, short_description, description, key_features, image_url, license_type, price, discount_price, demo_url, file_path, current_version, last_updated_at, technical_info, is_active) VALUES
(1, 1, 'Elementor Pro', 'elementor-pro', 'Leading WordPress website builder.', 'The world leading WordPress website builder with advanced drag-and-drop editor.', '["Drag & Drop Editor","300+ Templates","90+ Widgets","Theme Builder","WooCommerce Builder"]', '/assets/products/elementor.png', 'GPLv3 - Unlimited Sites', 4.99, 59.00, 'https://elementor.com', 'products/elementor-pro.zip', '3.21.4', NOW(), 'PHP 8.1+ / WP 6.0+', 1),
(2, 2, 'Yoast SEO Premium', 'yoast-seo-premium', 'The #1 WordPress SEO plugin.', 'Yoast SEO helps you rank higher in search engines and improve your site visibility.', '["Internal Linking","Redirect Manager","Social Previews","Multiple Focus Keywords"]', '/assets/products/yoast.png', 'GPLv3 - Unlimited Sites', 3.99, 99.00, 'https://yoast.com', 'products/yoast-seo.zip', '22.1', NOW(), 'PHP 8.1+ / WP 6.0+', 1),
(3, 3, 'WP Rocket', 'wp-rocket', 'Powerful performance caching plugin.', 'The most powerful caching plugin. WP Rocket improves your loading time instantly.', '["Page Caching","GZIP Compression","Browser Caching","Database Optimization"]', '/assets/products/wprocket.png', 'GPLv3 - Unlimited Sites', 5.99, 49.00, 'https://wp-rocket.me', 'products/wp-rocket.zip', '3.16', NOW(), 'PHP 8.1+ / WP 6.0+', 1),
(4, 5, 'Astra Pro', 'astra-pro', 'Fast and highly customizable theme.', 'The most popular theme. Fast, lightweight, and highly customizable for any website.', '["Header Builder","Mega Menu","Sticky Header","Advanced Typography","Custom Layouts"]', '/assets/products/astra.png', 'GPLv3 - Unlimited Sites', 4.99, 59.00, 'https://wpastra.com', 'products/astra-pro.zip', '4.6.4', NOW(), 'PHP 8.1+ / WP 6.0+', 1),
(5, 4, 'WooCommerce Subscriptions', 'woocommerce-subscriptions', 'Recurring billing for WooCommerce.', 'Allow your customers to subscribe and pay on a weekly, monthly or annual basis.', '["Flexible Billing Periods","Automatic Renewals","Subscriber Management","Subscription Coupons"]', '/assets/products/woo-subs.webp', 'GPLv3 - Unlimited Sites', 19.99, 199.00, 'https://woocommerce.com', 'products/woocommerce-subscriptions.zip', '5.7.1', NOW(), 'PHP 8.1+ / WP 6.0+', 1),
(6, 6, 'Solid Security Pro', 'solid-security-pro', 'Comprehensive security plugin.', 'Protect your WordPress site from hackers and malware with the most comprehensive security plugin.', '["Two-Factor Authentication","Malware Scanning","Brute Force Protection","User Action Logging"]', '/assets/products/security.webp', 'GPLv3 - Unlimited Sites', 8.99, 99.00, 'https://solidwp.com', 'products/solid-security-pro.zip', '7.3.2', NOW(), 'PHP 8.1+ / WP 6.0+', 1);

-- Product Versions
INSERT INTO product_versions (product_id, version, changelog, file_path, file_size_bytes, uploaded_by, is_current) VALUES
(1, '3.21.4', 'Current stable release', 'products/elementor-pro.zip', NULL, 1, 1),
(2, '22.1', 'Current stable release', 'products/yoast-seo.zip', NULL, 1, 1),
(3, '3.16', 'Current stable release', 'products/wp-rocket.zip', NULL, 1, 1),
(4, '4.6.4', 'Current stable release', 'products/astra-pro.zip', NULL, 1, 1),
(5, '5.7.1', 'Current stable release', 'products/woocommerce-subscriptions.zip', NULL, 1, 1),
(6, '7.3.2', 'Current stable release', 'products/solid-security-pro.zip', NULL, 1, 1);

-- Admin Payment Methods (required for checkout)
INSERT INTO admin_payment_methods (provider, account_identifier, is_active) VALUES
('paypal', 'payments@pixelvault.app', 1),
('payhere', 'MERCHANT_ID_REPLACE_ME', 1);

-- Site Configuration
INSERT INTO site_configs (`key`, `group`, `label`, `type`, `value`) VALUES
('site_name', 'branding', 'Site Name', 'text', 'PixelVault'),
('site_theme', 'branding', 'Color Theme', 'text', 'vivid_orange'),
('primary_color', 'branding', 'Primary Color', 'color', '#f97316'),
('site_tagline', 'branding', 'Site Tagline', 'textarea', 'Premium WordPress resources for less.'),
('site_logo', 'branding', 'Site Logo', 'file', ''),
('footer_copyright', 'branding', 'Footer Copyright', 'text', '© 2026 PixelVault. All rights reserved.'),
('footer_credits', 'branding', 'Footer Credits', 'text', 'Built with love for creators.'),
('home_hero_title', 'marketing', 'Hero Title', 'textarea', 'Unlock the Best Premium WordPress Resources'),
('home_hero_subtitle', 'marketing', 'Hero Subtitle', 'textarea', 'Download GPL themes, plugins, and builder templates for a fraction of the cost. Unlimited site usage.'),
('home_hero_cta_1', 'marketing', 'Hero CTA 1', 'text', 'Explore Marketplace'),
('home_hero_cta_2', 'marketing', 'Hero CTA 2', 'text', 'Watch a 90-sec demo'),
('home_stat_1_val', 'marketing', 'Stat 1 Value', 'text', '12,400+'),
('home_stat_1_lbl', 'marketing', 'Stat 1 Label', 'text', 'Products'),
('home_stat_2_val', 'marketing', 'Stat 2 Value', 'text', '98.6%'),
('home_stat_2_lbl', 'marketing', 'Stat 2 Label', 'text', 'Uptime'),
('home_stat_3_val', 'marketing', 'Stat 3 Value', 'text', '4.9/5'),
('home_stat_3_lbl', 'marketing', 'Stat 3 Label', 'text', 'Rating'),
('enable_update_notifications', 'marketing', 'Enable Update Notifications', 'text', '1'),
('support_email', 'emails', 'Support Email', 'text', 'support@pixelvault.app'),
('admin_contact_email', 'emails', 'Contact Receiver Email', 'text', 'admin@pixelvault.app'),
('smtp_host', 'emails', 'SMTP Host', 'text', 'smtp.gmail.com'),
('smtp_port', 'emails', 'SMTP Port', 'text', '587'),
('smtp_user', 'emails', 'SMTP User', 'text', ''),
('smtp_pass', 'emails', 'SMTP Pass', 'text', ''),
('smtp_encryption', 'emails', 'SMTP Encryption', 'text', 'tls'),
('notification_sender_email', 'emails', 'Notification Sender Email', 'text', ''),
('notification_sender_name', 'emails', 'Notification Sender Name', 'text', 'PixelVault Updates'),
('contact_address', 'general', 'Contact Address', 'textarea', '123 Pixel Street, NY 10001'),
('currency_symbol', 'general', 'Currency Symbol', 'text', '$'),
('max_downloads', 'general', 'Max Downloads', 'text', '3');

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================
-- SETUP COMPLETE
-- Admin login: admin@pixelvault.app / admin123
-- Demo user:   demo@pixelvault.app  / password123
-- ============================================================
