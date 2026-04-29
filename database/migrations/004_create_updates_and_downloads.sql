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
