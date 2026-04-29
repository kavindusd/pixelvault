-- Move SMTP and contact email configs into the new 'emails' group
UPDATE site_configs SET `group` = 'emails', `label` = 'Contact Receiver Email'
    WHERE `key` = 'admin_contact_email';

UPDATE site_configs SET `group` = 'emails'
    WHERE `key` IN ('smtp_host', 'smtp_port', 'smtp_user', 'smtp_pass', 'smtp_encryption');

-- Add notification sender configs (INSERT IGNORE skips if they already exist)
INSERT IGNORE INTO site_configs (`key`, `group`, `label`, `type`, `value`) VALUES
    ('notification_sender_email', 'emails', 'Notification Sender Email', 'text', ''),
    ('notification_sender_name',  'emails', 'Notification Sender Name',  'text', 'PixelVault Updates');

-- Add missing columns to the purchases table
ALTER TABLE purchases
    ADD COLUMN purchased_version   VARCHAR(30)  NOT NULL DEFAULT '1.0.0'  AFTER max_update_downloads,
    ADD COLUMN downloaded_versions TEXT         NULL                       AFTER purchased_version,
    ADD COLUMN override_extra_downloads INT UNSIGNED NOT NULL DEFAULT 0    AFTER downloaded_versions;
 

 -- Add site_logo config to the branding group.
-- INSERT IGNORE is safe to run multiple times — it skips if the row already exists.
INSERT IGNORE INTO site_configs (`key`, `group`, `label`, `type`, `value`)
VALUES ('site_logo', 'branding', 'Site Logo', 'file', '');
 