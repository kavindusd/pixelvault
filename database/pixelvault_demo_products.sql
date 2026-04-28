-- PixelVault Demo Products & Content
-- Run this script AFTER pixelvault_hostinger.sql

SET FOREIGN_KEY_CHECKS = 0;

-- --------------------------------------------------------
-- Clear existing content to prevent duplicates
-- --------------------------------------------------------
DELETE FROM `product_versions`;
DELETE FROM `products`;

-- --------------------------------------------------------
-- Insert Demo Products
-- --------------------------------------------------------
INSERT INTO `products` (`id`, `category_id`, `title`, `slug`, `short_description`, `description`, `key_features`, `image_url`, `license_type`, `price`, `discount_price`, `demo_url`, `file_path`, `current_version`, `last_updated_at`, `is_active`)
VALUES
    (1, 1, 'Elementor Pro', 'elementor-pro', 'Leading WordPress website builder.', 'The world\'s leading WordPress website builder. Create professional, pixel-perfect websites with the most advanced drag-and-drop editor.', '["Drag & Drop Editor","300+ Basic & Pro Templates","90+ Widgets","Theme Builder","WooCommerce Builder"]', '/assets/products/elementor.png', 'GPLv3 - Unlimited Sites', 4.99, 59.00, 'https://elementor.com', 'storage/files/products/elementor-pro.zip', '3.21.4', '2026-04-20 00:00:00', 1),
    (2, 2, 'Yoast SEO Premium', 'yoast-seo-premium', 'The #1 WordPress SEO plugin.', 'Yoast SEO is the #1 WordPress SEO plugin. Get more visitors from Google and Bing, and increase your social media engagement.', '["Internal Linking Suggestions","Redirect Manager","Social Media Previews","Multiple Focus Keywords"]', '/assets/products/yoast.png', 'GPLv3 - Unlimited Sites', 3.99, 99.00, 'https://yoast.com', 'storage/files/products/yoast-seo-premium.zip', '22.1', '2026-04-18 00:00:00', 1),
    (3, 3, 'WP Rocket', 'wp-rocket', 'Powerful performance caching plugin.', 'The most powerful web performance caching plugin. WP Rocket is much more than just a WordPress caching plugin.', '["Page Caching","GZIP Compression","Browser Caching","Database Optimization","Google Font Optimization"]', '/assets/products/wprocket.png', 'GPLv3 - Unlimited Sites', 5.99, 49.00, 'https://wp-rocket.me', 'storage/files/products/wp-rocket.zip', '3.16', '2026-04-15 00:00:00', 1),
    (4, 5, 'Astra Pro', 'astra-pro', 'Fast and highly customizable theme.', 'The most popular theme of all time. Fast, lightweight, and highly customizable for any type of website.', '["Header Builder","Mega Menu","Sticky Header","Advanced Typography","Custom Layouts"]', '/assets/products/astra.png', 'GPLv3 - Unlimited Sites', 4.99, 59.00, 'https://wpastra.com', 'storage/files/products/astra-pro.zip', '4.6.4', '2026-04-12 00:00:00', 1),
    (5, 5, 'Divi Theme', 'divi-theme', 'Popular visual builder theme.', 'The most popular premium WordPress theme in the world and the most powerful visual page builder.', '["Visual Editor","40+ Website Elements","800+ Pre-made Designs","Global Elements & Styles"]', '/assets/products/divi.png', 'GPLv3 - Unlimited Sites', 6.99, 89.00, 'https://elegantthemes.com', 'storage/files/products/divi-theme.zip', '4.25.1', '2026-04-10 00:00:00', 1);

-- --------------------------------------------------------
-- Insert Product Versions (Required for downloads)
-- --------------------------------------------------------
INSERT INTO `product_versions` (`product_id`, `version`, `changelog`, `file_path`, `is_current`, `uploaded_by`, `created_at`)
VALUES
    (1, '3.21.4', 'Initial stable release for PixelVault', 'storage/files/products/elementor-pro.zip', 1, 1, '2026-04-20 00:00:00'),
    (2, '22.1', 'Full premium features enabled', 'storage/files/products/yoast-seo-premium.zip', 1, 1, '2026-04-18 00:00:00'),
    (3, '3.16', 'Performance optimization update', 'storage/files/products/wp-rocket.zip', 1, 1, '2026-04-15 00:00:00'),
    (4, '4.6.4', 'Latest theme version', 'storage/files/products/astra-pro.zip', 1, 1, '2026-04-12 00:00:00'),
    (5, '4.25.1', 'Visual builder stability fix', 'storage/files/products/divi-theme.zip', 1, 1, '2026-04-10 00:00:00');

SET FOREIGN_KEY_CHECKS = 1;
