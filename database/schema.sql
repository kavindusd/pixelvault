-- ============================================================================
-- BASE DATABASE SCHEMA
-- Generated from consolidated migrations
-- ============================================================================

CREATE DATABASE IF NOT EXISTS digital_marketplace_v2
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE digital_marketplace_v2;

-- Track migrations
CREATE TABLE IF NOT EXISTS schema_migrations (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    migration VARCHAR(255) NOT NULL UNIQUE,
    executed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Users (Customers)
CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    avatar_url VARCHAR(255) NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
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

-- Administrators
CREATE TABLE IF NOT EXISTS admins (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    role VARCHAR(50) NOT NULL DEFAULT 'Super Admin',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Categories
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

-- Products
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
    technical_info VARCHAR(255) DEFAULT 'PHP 8.1+ / WP 6.0+',
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_products_category FOREIGN KEY (category_id) REFERENCES categories(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Product Versions
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

-- Orders
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

-- Purchases (User Product Access)
CREATE TABLE IF NOT EXISTS purchases (
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
    CONSTRAINT fk_purchases_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    CONSTRAINT fk_purchases_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE,
    CONSTRAINT fk_purchases_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    UNIQUE KEY uq_user_product (user_id, product_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Site Configs
CREATE TABLE IF NOT EXISTS site_configs (
    `key` VARCHAR(50) NOT NULL PRIMARY KEY,
    `group` VARCHAR(50) NOT NULL DEFAULT 'general',
    `label` VARCHAR(100) NOT NULL,
    `type` VARCHAR(20) NOT NULL DEFAULT 'text',
    `value` TEXT NULL,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Inquiries (Contact Form & Extension Requests)
CREATE TABLE IF NOT EXISTS inquiries (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    email VARCHAR(190) NOT NULL,
    subject VARCHAR(190) NOT NULL,
    message TEXT NOT NULL,
    type VARCHAR(50) NOT NULL DEFAULT 'standard',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Admin Receiving Accounts (Where the money goes)
CREATE TABLE IF NOT EXISTS admin_payment_methods (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    provider ENUM('paypal', 'payhere', 'visa', 'mastercard') NOT NULL,
    account_identifier VARCHAR(190) NOT NULL, -- e.g., email or Merchant ID
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- User Payment Methods (How the user pays)
CREATE TABLE IF NOT EXISTS user_payment_methods (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    provider ENUM('paypal', 'payhere', 'visa', 'mastercard') NOT NULL,
    account_identifier VARCHAR(190) NOT NULL, -- e.g., email or masked card
    is_default TINYINT(1) NOT NULL DEFAULT 0,
    details JSON NULL, -- e.g., {"expiry": "12/25", "brand": "visa"}
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_user_payment_methods_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Email Templates (Optional/Planned)
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
    CONSTRAINT fk_email_templates_admin FOREIGN KEY (updated_by) REFERENCES admins(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Update Notifications (Optional/Planned)
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

-- Admin Action Logs
CREATE TABLE IF NOT EXISTS admin_action_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    admin_user_id BIGINT UNSIGNED NOT NULL,
    action_type ENUM('upload_version', 'reset_update_count', 'override_download_access', 'manual_notification') NOT NULL,
    target_user_id BIGINT UNSIGNED NULL,
    target_product_id BIGINT UNSIGNED NULL,
    action_payload JSON NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_admin_logs_admin FOREIGN KEY (admin_user_id) REFERENCES admins(id),
    CONSTRAINT fk_admin_logs_target_user FOREIGN KEY (target_user_id) REFERENCES users(id),
    CONSTRAINT fk_admin_logs_target_product FOREIGN KEY (target_product_id) REFERENCES products(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- User Payment Methods Legacy (Removed, replaced by user_payment_methods above)
-- User Payment Methods (Optional/Planned)
CREATE TABLE IF NOT EXISTS user_payment_methods_old (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    card_number_masked VARCHAR(20) NULL,
    card_expiry VARCHAR(10) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_user_payment_methods_user_old FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. INDEXES
-- ============================================================================
CREATE INDEX idx_products_category ON products(category_id);
CREATE INDEX idx_products_current_version ON products(current_version);
CREATE INDEX idx_orders_user ON orders(user_id);
CREATE INDEX idx_orders_status ON orders(payment_status);
CREATE INDEX idx_orders_purchase_date ON orders(purchase_date);
CREATE INDEX idx_product_versions_current ON product_versions(product_id, is_current);
CREATE INDEX idx_purchases_user_product ON purchases(user_id, product_id);
CREATE INDEX idx_purchases_update_count ON purchases(update_count);
CREATE INDEX idx_inquiries_type ON inquiries(type);
CREATE INDEX idx_inquiries_created_at ON inquiries(created_at);
CREATE INDEX idx_admin_payment_active ON admin_payment_methods(is_active);
CREATE INDEX idx_user_payment_user ON user_payment_methods(user_id);
