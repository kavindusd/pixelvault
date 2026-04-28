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
