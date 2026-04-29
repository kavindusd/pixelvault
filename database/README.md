# Database Setup

This project now includes a full MySQL schema + versioned migrations for the Digital Product Marketplace MVP.

## Included Files

- `schema.sql` - full one-shot database schema
- `migrations/*.sql` - incremental SQL migrations
- `migrate.php` - simple PHP migration runner (PDO)
- `seeds/001_initial_seed.sql` - starter admin/user/category/template records

## 1) Configure Environment

Copy `.env.example` to `.env` and set:

- `DB_HOST`
- `DB_PORT`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`

## 2) Run Migrations

```bash
php database/migrate.php
```

## 3) Run Seed Data

```bash
mysql -u root -p digital_marketplace < database/seeds/001_initial_seed.sql
```

## Alternate: Import Full Schema Directly

```bash
mysql -u root -p < database/schema.sql
```

## Core Tables Added

- `users`
- `categories`
- `products`
- `orders`
- `order_items`
- `payments`
- `product_versions`
- `user_product_access` (tracks update count and override controls)
- `download_logs`
- `email_templates`
- `update_notifications`
- `admin_action_logs`

This structure supports the PDF requirements: secure digital downloads, version tracking, max 3 updates per user, admin overrides, and update email notifications.
