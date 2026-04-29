-- ============================================================================
-- COMPREHENSIVE DATABASE SETUP FILE
-- Generated from all migrations, schema, and seed files
-- ============================================================================
-- This file contains the complete database structure and initial data
-- Run this once to set up the entire database from scratch
-- ============================================================================


-- 001_create_users.sql
-- ============================================================================
CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    avatar_url VARCHAR(255) NULL,
    role ENUM('customer', 'admin') NOT NULL DEFAULT 'customer',
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 002_create_catalog.sql
-- ============================================================================
CREATE TABLE IF NOT EXISTS categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL UNIQUE,
    slug VARCHAR(140) NOT NULL UNIQUE,
    description TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(190) NOT NULL,
    slug VARCHAR(220) NOT NULL UNIQUE,
    short_description VARCHAR(280) NULL,
    description TEXT NOT NULL,
    key_features JSON NULL,
    image_url VARCHAR(255) NULL,
    license_type VARCHAR(120) NOT NULL DEFAULT 'GPLv3 - Unlimited Sites',
    price DECIMAL(10,2) NOT NULL,
    discount_price DECIMAL(10,2) NULL,
    demo_url VARCHAR(255) NULL,
    file_path VARCHAR(255) NOT NULL,
    current_version VARCHAR(50) NOT NULL,
    last_updated_at DATETIME NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 003_create_orders_and_payments.sql
-- ============================================================================
CREATE TABLE IF NOT EXISTS orders (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(40) NOT NULL UNIQUE,
    user_id BIGINT UNSIGNED NOT NULL,
    subtotal DECIMAL(10,2) NOT NULL,
    discount_total DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    grand_total DECIMAL(10,2) NOT NULL,
    payment_status ENUM('pending', 'paid', 'failed', 'refunded') NOT NULL DEFAULT 'pending',
    purchase_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    notes TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS order_items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    product_title_snapshot VARCHAR(190) NOT NULL,
    product_version_snapshot VARCHAR(50) NOT NULL,
    unit_price DECIMAL(10,2) NOT NULL,
    quantity INT UNSIGNED NOT NULL DEFAULT 1,
    line_total DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_order_items_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    CONSTRAINT fk_order_items_product FOREIGN KEY (product_id) REFERENCES products(id),
    UNIQUE KEY uq_order_product (order_id, product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS payments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_id BIGINT UNSIGNED NOT NULL,
    gateway ENUM('paypal', 'payhere') NOT NULL,
    gateway_transaction_id VARCHAR(120) NULL,
    amount DECIMAL(10,2) NOT NULL,
    currency CHAR(3) NOT NULL DEFAULT 'USD',
    status ENUM('initiated', 'success', 'failed', 'refunded') NOT NULL DEFAULT 'initiated',
    paid_at DATETIME NULL,
    raw_response JSON NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_payments_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 004_create_updates_and_downloads.sql
-- ============================================================================
CREATE TABLE IF NOT EXISTS product_versions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED NOT NULL,
    version VARCHAR(50) NOT NULL,
    changelog TEXT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_size_bytes BIGINT UNSIGNED NULL,
    uploaded_by BIGINT UNSIGNED NULL,
    is_current TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_product_versions_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    CONSTRAINT fk_product_versions_user FOREIGN KEY (uploaded_by) REFERENCES users(id),
    UNIQUE KEY uq_product_version (product_id, version)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS user_product_access (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    order_item_id BIGINT UNSIGNED NOT NULL,
    max_update_downloads INT UNSIGNED NOT NULL DEFAULT 3,
    update_count INT UNSIGNED NOT NULL DEFAULT 0,
    last_downloaded_version VARCHAR(50) NULL,
    override_extra_downloads INT UNSIGNED NOT NULL DEFAULT 0,
    override_reason VARCHAR(255) NULL,
    override_set_by BIGINT UNSIGNED NULL,
    override_expires_at DATETIME NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_access_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_access_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    CONSTRAINT fk_access_order_item FOREIGN KEY (order_item_id) REFERENCES order_items(id) ON DELETE CASCADE,
    CONSTRAINT fk_access_override_user FOREIGN KEY (override_set_by) REFERENCES users(id),
    UNIQUE KEY uq_user_product_access (user_id, product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS download_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_product_access_id BIGINT UNSIGNED NOT NULL,
    product_version_id BIGINT UNSIGNED NOT NULL,
    download_type ENUM('purchase', 'update') NOT NULL DEFAULT 'update',
    ip_address VARCHAR(45) NULL,
    user_agent VARCHAR(500) NULL,
    signed_url_expires_at DATETIME NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_download_logs_access FOREIGN KEY (user_product_access_id) REFERENCES user_product_access(id) ON DELETE CASCADE,
    CONSTRAINT fk_download_logs_version FOREIGN KEY (product_version_id) REFERENCES product_versions(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 005_create_email_and_admin.sql
-- ============================================================================
CREATE TABLE IF NOT EXISTS email_templates (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    template_key VARCHAR(80) NOT NULL UNIQUE,
    subject VARCHAR(190) NOT NULL,
    body_html MEDIUMTEXT NOT NULL,
    body_text MEDIUMTEXT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    updated_by BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_email_templates_user FOREIGN KEY (updated_by) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS update_notifications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_version_id BIGINT UNSIGNED NOT NULL,
    user_id BIGINT UNSIGNED NOT NULL,
    email_template_id BIGINT UNSIGNED NULL,
    delivery_status ENUM('queued', 'sent', 'failed') NOT NULL DEFAULT 'queued',
    sent_at DATETIME NULL,
    error_message VARCHAR(255) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_update_notifications_version FOREIGN KEY (product_version_id) REFERENCES product_versions(id) ON DELETE CASCADE,
    CONSTRAINT fk_update_notifications_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_update_notifications_template FOREIGN KEY (email_template_id) REFERENCES email_templates(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS admin_action_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    admin_user_id BIGINT UNSIGNED NOT NULL,
    action_type ENUM('upload_version', 'reset_update_count', 'override_download_access', 'manual_notification') NOT NULL,
    target_user_id BIGINT UNSIGNED NULL,
    target_product_id BIGINT UNSIGNED NULL,
    action_payload JSON NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_admin_logs_admin FOREIGN KEY (admin_user_id) REFERENCES users(id),
    CONSTRAINT fk_admin_logs_target_user FOREIGN KEY (target_user_id) REFERENCES users(id),
    CONSTRAINT fk_admin_logs_target_product FOREIGN KEY (target_product_id) REFERENCES products(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 006_add_indexes.sql
-- ============================================================================
CREATE INDEX idx_products_category ON products(category_id);
CREATE INDEX idx_products_current_version ON products(current_version);

CREATE INDEX idx_orders_user ON orders(user_id);
CREATE INDEX idx_orders_status ON orders(payment_status);
CREATE INDEX idx_orders_purchase_date ON orders(purchase_date);

CREATE INDEX idx_order_items_product ON order_items(product_id);

CREATE INDEX idx_payments_status ON payments(status);
CREATE INDEX idx_payments_gateway ON payments(gateway);

CREATE INDEX idx_product_versions_current ON product_versions(product_id, is_current);

CREATE INDEX idx_access_user_product ON user_product_access(user_id, product_id);
CREATE INDEX idx_access_update_count ON user_product_access(update_count);

CREATE INDEX idx_download_logs_created_at ON download_logs(created_at);

CREATE INDEX idx_notifications_delivery_status ON update_notifications(delivery_status);

-- 007_add_user_details.sql
-- ============================================================================
ALTER TABLE users 
ADD COLUMN address_country VARCHAR(100) NULL,
ADD COLUMN address_city VARCHAR(100) NULL,
ADD COLUMN address_apartment VARCHAR(100) NULL,
ADD COLUMN address_postal_code VARCHAR(50) NULL;

CREATE TABLE IF NOT EXISTS user_payment_methods (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    card_number_masked VARCHAR(20) NULL,
    card_expiry VARCHAR(10) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_user_payment_methods_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 008_create_inquiries.sql
-- ============================================================================
CREATE TABLE IF NOT EXISTS inquiries (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL,
    subject VARCHAR(190) NOT NULL,
    message TEXT NOT NULL,
    type VARCHAR(50) NOT NULL DEFAULT 'standard',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 009_add_type_to_inquiries.sql
-- ============================================================================
-- Ensure the type column exists in inquiries table
SET @dbname = DATABASE();
SET @tablename = 'inquiries';
SET @columnname = 'type';
SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
   WHERE TABLE_SCHEMA = @dbname
     AND TABLE_NAME = @tablename
     AND COLUMN_NAME = @columnname
  ) > 0,
  'SELECT 1',
  'ALTER TABLE inquiries ADD COLUMN type VARCHAR(50) NOT NULL DEFAULT "standard" AFTER message'
));
PREPARE stmt FROM @preparedStatement;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Also ensure created_at exists for sorting
SET @columnname = 'created_at';
SET @preparedStatement = (SELECT IF(
  (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
   WHERE TABLE_SCHEMA = @dbname
     AND TABLE_NAME = @tablename
     AND COLUMN_NAME = @columnname
  ) > 0,
  'SELECT 1',
  'ALTER TABLE inquiries ADD COLUMN created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP'
));
PREPARE stmt FROM @preparedStatement;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- schema.sql
-- ============================================================================
CREATE DATABASE IF NOT EXISTS digital_marketplace
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE digital_marketplace;

CREATE TABLE IF NOT EXISTS schema_migrations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    migration VARCHAR(255) NOT NULL UNIQUE,
    executed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    avatar_url VARCHAR(255) NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    paypal_email VARCHAR(255) NULL,
    payhere_id VARCHAR(255) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS admins (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'Super Admin',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS categories (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL UNIQUE,
    slug VARCHAR(140) NOT NULL UNIQUE,
    description TEXT NULL,
    icon VARCHAR(50) DEFAULT 'Tag',
    hue VARCHAR(100) DEFAULT 'from-orange-400/20 to-orange-500/10',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS products (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id BIGINT UNSIGNED NOT NULL,
    title VARCHAR(190) NOT NULL,
    slug VARCHAR(220) NOT NULL UNIQUE,
    short_description VARCHAR(280) NULL,
    description TEXT NOT NULL,
    key_features JSON NULL,
    image_url VARCHAR(255) NULL,
    license_type VARCHAR(120) NOT NULL DEFAULT 'GPLv3 - Unlimited Sites',
    price DECIMAL(10,2) NOT NULL,
    discount_price DECIMAL(10,2) NULL,
    demo_url VARCHAR(255) NULL,
    file_path VARCHAR(255) NOT NULL,
    current_version VARCHAR(50) NOT NULL,
    last_updated_at DATETIME NOT NULL,
    technical_info VARCHAR(255) NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS product_versions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_id BIGINT UNSIGNED NOT NULL,
    version VARCHAR(50) NOT NULL,
    changelog TEXT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_size_bytes BIGINT UNSIGNED NULL,
    uploaded_by BIGINT UNSIGNED NULL,
    is_current TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_product_versions_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    CONSTRAINT fk_product_versions_user FOREIGN KEY (uploaded_by) REFERENCES admins(id),
    UNIQUE KEY uq_product_version (product_id, version)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS orders (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_number VARCHAR(40) NOT NULL UNIQUE,
    user_id BIGINT UNSIGNED NOT NULL,
    total_amount DECIMAL(10,2) NOT NULL,
    payment_status ENUM('pending', 'paid', 'failed', 'refunded') NOT NULL DEFAULT 'pending',
    payment_method VARCHAR(50) NULL,
    purchase_date DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS purchases (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    product_id BIGINT UNSIGNED NOT NULL,
    order_id BIGINT UNSIGNED NOT NULL,
    update_count INT UNSIGNED NOT NULL DEFAULT 0,
    max_update_downloads INT UNSIGNED NOT NULL DEFAULT 3,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_purchases_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_purchases_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    CONSTRAINT fk_purchases_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    UNIQUE KEY uq_user_product (user_id, product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS site_configs (
    `key` VARCHAR(50) NOT NULL PRIMARY KEY,
    `group` VARCHAR(50) NOT NULL DEFAULT 'general',
    `label` VARCHAR(100) NOT NULL,
    `type` VARCHAR(20) NOT NULL DEFAULT 'text',
    `value` TEXT NULL,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_products_category ON products(category_id);
CREATE INDEX idx_products_current_version ON products(current_version);
CREATE INDEX idx_orders_user ON orders(user_id);
CREATE INDEX idx_orders_status ON orders(payment_status);
CREATE INDEX idx_orders_purchase_date ON orders(purchase_date);
CREATE INDEX idx_product_versions_current ON product_versions(product_id, is_current);
CREATE INDEX idx_purchases_user_product ON purchases(user_id, product_id);
CREATE INDEX idx_purchases_update_count ON purchases(update_count);

CREATE TABLE IF NOT EXISTS inquiries (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL,
    subject VARCHAR(190) NOT NULL,
    message TEXT NOT NULL,
    type VARCHAR(50) NOT NULL DEFAULT 'standard',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_inquiries_type ON inquiries(type);
CREATE INDEX idx_inquiries_created_at ON inquiries(created_at);



-- INITIAL DATA SEED
-- ============================================================================
SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE purchases;
TRUNCATE TABLE orders;
TRUNCATE TABLE product_versions;
TRUNCATE TABLE products;
TRUNCATE TABLE categories;
TRUNCATE TABLE users;
TRUNCATE TABLE site_configs;
SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO users (id, name, email, password_hash, avatar_url)
VALUES
    (1, 'Demo Member', 'demo@pixelvault.app', '$2y$10$ByZ3.Fti6dXUxXP5JHf9Aein8u7L6OVGm/rL6xIuZ15xJ2LmEH04W', 'https://ui-avatars.com/api/?name=Vault+Member+Demo&background=FBBC05&color=fff');

INSERT INTO admins (id, name, email, password_hash, role)
VALUES
    (1, 'Admin User', 'admin@pixelvault.app', '$2y$10$u3RYypVU2.34WVcEQ08yLeb4PXbTghf8TvmcEosjM/JE4m5y6J/bW', 'Super Admin');

INSERT INTO categories (id, name, slug, description, icon, hue)
VALUES
    (1, 'Page Builders', 'page-builders', 'Drag and drop page builder tools.', 'Layout', 'from-primary/20 to-primary/5'),
    (2, 'SEO & Marketing', 'seo-marketing', 'SEO optimization and marketing plugins.', 'Search', 'from-emerald-400/20 to-emerald-400/5'),
    (3, 'Performance', 'performance', 'Caching and speed optimization tools.', 'Zap', 'from-cyan-400/20 to-cyan-400/5'),
    (4, 'WooCommerce', 'woocommerce', 'E-commerce extensions and tools.', 'ShoppingBag', 'from-purple-400/20 to-purple-400/5'),
    (5, 'Themes', 'themes', 'Premium WordPress themes.', 'Palette', 'from-pink-400/20 to-pink-400/5'),
    (6, 'Security', 'security', 'Security and firewall plugins.', 'Shield', 'from-red-400/20 to-red-400/5');


INSERT INTO products (id, category_id, title, slug, short_description, description, key_features, image_url, license_type, price, discount_price, demo_url, file_path, current_version, last_updated_at, is_active)
VALUES
    (1, 1, 'Elementor Pro', 'elementor-pro', 'Leading WordPress website builder.', 'The world''s leading WordPress website builder. Create professional, pixel-perfect websites with the most advanced drag-and-drop editor.', '["Drag & Drop Editor","300+ Basic & Pro Templates","90+ Widgets","Theme Builder","WooCommerce Builder"]', '/assets/products/elementor.png', 'GPLv3 - Unlimited Sites', 4.99, 59.00, 'https://example.com/demo/elementor-pro', 'r2://products/elementor-pro.zip', '3.21.4', '2026-04-20 00:00:00', 1),
    (2, 2, 'Yoast SEO Premium', 'yoast-seo-premium', 'The #1 WordPress SEO plugin.', 'Yoast SEO is the #1 WordPress SEO plugin. Get more visitors from Google and Bing, and increase your social media engagement.', '["Internal Linking Suggestions","Redirect Manager","Social Media Previews","Multiple Focus Keywords"]', '/assets/products/yoast.png', 'GPLv3 - Unlimited Sites', 3.99, 99.00, 'https://example.com/demo/yoast-seo-premium', 'r2://products/yoast-seo-premium.zip', '22.1', '2026-04-18 00:00:00', 1),
    (3, 3, 'WP Rocket', 'wp-rocket', 'Powerful performance caching plugin.', 'The most powerful web performance caching plugin. WP Rocket is much more than just a WordPress caching plugin.', '["Page Caching","GZIP Compression","Browser Caching","Database Optimization","Google Font Optimization"]', '/assets/products/wprocket.png', 'GPLv3 - Unlimited Sites', 5.99, 49.00, 'https://example.com/demo/wp-rocket', 'r2://products/wp-rocket.zip', '3.16', '2026-04-15 00:00:00', 1),
    (4, 4, 'Astra Pro', 'astra-pro', 'Fast and highly customizable theme.', 'The most popular theme of all time. Fast, lightweight, and highly customizable for any type of website.', '["Header Builder","Mega Menu","Sticky Header","Advanced Typography","Custom Layouts"]', '/assets/products/astra.png', 'GPLv3 - Unlimited Sites', 4.99, 59.00, 'https://example.com/demo/astra-pro', 'r2://products/astra-pro.zip', '4.6.4', '2026-04-12 00:00:00', 1),
    (5, 4, 'Divi Theme', 'divi-theme', 'Popular visual builder theme.', 'The most popular premium WordPress theme in the world and the most powerful visual page builder.', '["Visual Editor","40+ Website Elements","800+ Pre-made Designs","Global Elements & Styles"]', '/assets/products/divi.png', 'GPLv3 - Unlimited Sites', 6.99, 89.00, 'https://example.com/demo/divi-theme', 'r2://products/divi-theme.zip', '4.25.1', '2026-04-10 00:00:00', 1),
    (6, 4, 'OceanWP Pro', 'oceanwp-pro', 'Lightweight and extendable theme.', 'The perfect theme for your project. Lightweight and highly extendable, it will enable you to create almost any type of website.', '["WooCommerce Integration","Native Cart Popup","Stick Anything","Full Screen Scrolling"]', '/assets/products/oceanwp.webp', 'GPLv3 - Unlimited Sites', 3.99, 49.00, 'https://example.com/demo/oceanwp-pro', 'r2://products/oceanwp-pro.zip', '3.5.4', '2026-04-08 00:00:00', 1),
    (7, 4, 'GeneratePress Premium', 'generatepress-premium', 'Theme focused on speed and usability.', 'A lightweight WordPress theme that focuses on speed and usability. Better performance, higher search visibility.', '["Block-Based Design","Site Library","Typography Controls","Spacing Controls","Secondary Nav"]', '/assets/products/generatepress.webp', 'GPLv3 - Unlimited Sites', 4.99, 59.00, 'https://example.com/demo/generatepress-premium', 'r2://products/generatepress-premium.zip', '2.4.1', '2026-04-05 00:00:00', 1),
    (10, 5, 'WooCommerce Subscriptions', 'woocommerce-subscriptions', 'Recurring billing for WooCommerce.', 'Allow your customers to subscribe to your products or services and pay on a weekly, monthly or annual basis.', '["Flexible Billing Periods","Automatic Renewals","Subscriber Management","Subscription Coupons"]', '/assets/products/woo-subs.webp', 'GPLv3 - Unlimited Sites', 19.99, 199.00, 'https://example.com/demo/woocommerce-subscriptions', 'r2://products/woocommerce-subscriptions.zip', '5.7.1', '2026-04-22 00:00:00', 1),
    (11, 6, 'Solid Security Pro', 'solid-security-pro', 'Comprehensive security plugin.', 'Protect your WordPress site from hackers and malware. The most comprehensive security plugin available.', '["Two-Factor Authentication","Malware Scanning","Brute Force Protection","User Action Logging"]', '/assets/products/security.webp', 'GPLv3 - Unlimited Sites', 8.99, 99.00, 'https://example.com/demo/solid-security-pro', 'r2://products/solid-security-pro.zip', '7.3.2', '2026-04-19 00:00:00', 1);

INSERT INTO product_versions (product_id, version, changelog, file_path, is_current, uploaded_by, created_at)
VALUES
    (1, '3.21.4', 'Current stable release', 'r2://products/elementor-pro-3.21.4.zip', 1, 1, '2026-04-20 00:00:00'),
    (1, '3.22.0', 'New update available', 'r2://products/elementor-pro-3.22.0.zip', 0, 1, '2026-04-21 00:00:00'),
    (2, '22.1', 'Current stable release', 'r2://products/yoast-seo-premium-22.1.zip', 1, 1, '2026-04-18 00:00:00'),
    (3, '3.16', 'Current stable release', 'r2://products/wp-rocket-3.16.zip', 1, 1, '2026-04-15 00:00:00'),
    (3, '3.17.1', 'Performance update available', 'r2://products/wp-rocket-3.17.1.zip', 0, 1, '2026-04-17 00:00:00'),
    (4, '4.6.4', 'Current stable release', 'r2://products/astra-pro-4.6.4.zip', 1, 1, '2026-04-12 00:00:00'),
    (4, '4.7.0', 'Feature update available', 'r2://products/astra-pro-4.7.0.zip', 0, 1, '2026-04-13 00:00:00'),
    (5, '4.25.1', 'Current stable release', 'r2://products/divi-theme-4.25.1.zip', 1, 1, '2026-04-10 00:00:00'),
    (6, '3.5.4', 'Current stable release', 'r2://products/oceanwp-pro-3.5.4.zip', 1, 1, '2026-04-08 00:00:00'),
    (6, '3.6.0', 'New release available', 'r2://products/oceanwp-pro-3.6.0.zip', 0, 1, '2026-04-09 00:00:00'),
    (7, '2.4.1', 'Current stable release', 'r2://products/generatepress-premium-2.4.1.zip', 1, 1, '2026-04-05 00:00:00'),
    (7, '2.5.0', 'New release available', 'r2://products/generatepress-premium-2.5.0.zip', 0, 1, '2026-04-06 00:00:00'),
    (10, '5.7.1', 'Current stable release', 'r2://products/woocommerce-subscriptions-5.7.1.zip', 1, 1, '2026-04-22 00:00:00'),
    (10, '6.0.0', 'Major update available', 'r2://products/woocommerce-subscriptions-6.0.0.zip', 0, 1, '2026-04-23 00:00:00'),
    (11, '7.3.2', 'Current stable release', 'r2://products/solid-security-pro-7.3.2.zip', 1, 1, '2026-04-19 00:00:00'),
    (11, '7.4.0', 'Security update available', 'r2://products/solid-security-pro-7.4.0.zip', 0, 1, '2026-04-20 00:00:00');

INSERT INTO orders (id, order_number, user_id, total_amount, payment_status, payment_method, purchase_date)
VALUES
    (1, 'ORD-9284', 1, 98.00, 'paid', 'paypal', '2026-04-26 08:00:00'),
    (2, 'ORD-9283', 1, 59.00, 'paid', 'payhere', '2026-04-26 07:48:00'),
    (3, 'ORD-9282', 1, 299.00, 'paid', 'paypal', '2026-04-26 07:00:00'),
    (4, 'ORD-9281', 1, 49.00, 'pending', 'paypal', '2026-04-26 05:00:00'),
    (5, 'ORD-9280', 1, 29.00, 'paid', 'paypal', '2026-04-25 10:00:00'),
    (6, 'ORD-9279', 1, 49.00, 'paid', 'payhere', '2026-04-24 10:00:00');

INSERT INTO purchases (user_id, product_id, order_id, update_count)
VALUES
    (1, 1, 1, 1),
    (1, 2, 4, 3),
    (1, 3, 2, 0),
    (1, 4, 3, 0),
    (1, 5, 3, 2);

INSERT INTO site_configs (`key`, `group`, `label`, `type`, `value`) VALUES
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
    ('home_stat_2_lbl', 'marketing', 'Stat 2 Label', 'text', 'Uptime'),
    ('home_stat_3_val', 'marketing', 'Stat 3 Value', 'text', '4.9/5'),
    ('home_stat_3_lbl', 'marketing', 'Stat 3 Label', 'text', 'Rating'),
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
-- ============================================================================
-- DATABASE SETUP COMPLETE
-- ============================================================================