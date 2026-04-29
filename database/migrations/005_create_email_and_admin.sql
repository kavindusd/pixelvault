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
