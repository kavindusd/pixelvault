# PixelVault — Premium Digital Marketplace

A high-end, editorial-style marketplace platform built with vanilla PHP, designed for selling digital assets like WordPress plugins, themes, creative resources, and software products. Features secure authentication, advanced user management, robust product handling, and a professional administration backend.

![Marketplace Preview](screenshots/ss1.png)

---

## 🌟 Key Features

### **Security & Authentication**
- **Email Verification**: Mandatory security code verification during registration
- **Two-Factor Authentication (2FA)**: Secure login with email verification codes
- **Secure Password Recovery**: Token-based reset system with expiry protection and timezone support
- **Role-Based Access Control**: Distinct interfaces and permissions for Users and Administrators
- **Session Management**: Secure cookie-based sessions with auto-logout

### **User Experience**
- **Premium Design**: Modern, responsive UI with glassmorphic elements and smooth animations
- **Dark Mode Support**: Full dark mode integration with curated HSL color palettes
- **Custom User Avatars**: Choose from premium 3D avatars or auto-generated initial fallbacks
- **Fluid Shopping Cart**: Session-based cart system with real-time updates
- **Product Discovery**: Advanced filtering by category, search functionality, and detailed product pages
- **Order History**: Full access to past purchases and download management

### **Product Management**
- **Version Control**: Track and manage product versions with changelog support
- **Update Downloads**: Auto-increment download counts for product updates
- **File Management**: Upload and organize product files (ZIP archives recommended)
- **Category Organization**: Create and manage product categories with slug-based routing
- **Product Details**: Rich descriptions, key features, pricing, licensing info, and demo links

### **Administration Dashboard**
- **Product Management**: Create, edit, delete products; manage versions and changelogs
- **Order Tracking**: Real-time transaction monitoring and customer activity logs
- **User Management**: View user accounts, roles, verification status, and activity
- **Inquiry Management**: Centralized inbox for customer support and quota requests
- **Security Controls**: Toggle platform-wide security settings and manage admin accounts
- **Email Templates**: Customize verification, password reset, and notification emails
- **Site Settings**: Manage navigation, pricing tiers, and email configuration
- **Activity Logs**: Complete audit trail of all platform activities

### **Database & Storage**
- **MySQL/MariaDB Backend**: Structured database for users, products, orders, and more
- **File Storage**: Organized file system for product downloads and uploads
- **JSON Data Files**: Fallback data storage for configuration and static content
- **Activity Logging**: Timestamped logs of all user and system actions

---

## 🛠️ Tech Stack

| Component | Technology |
|-----------|-----------|
| **Language** | PHP 8.1+ |
| **Architecture** | Vanilla MVC (no frameworks) |
| **Database** | MySQL 8.0 / MariaDB |
| **Frontend** | HTML5, Tailwind CSS, Vanilla JavaScript |
| **Styling** | Custom CSS variable system with HSL color tokens |
| **Email** | SMTP integration for all notifications |
| **Authentication** | Session-based + JWT support |

---

## 📁 Project Structure

```
digitak-market-app/
├── app/
│   ├── Controllers/
│   │   ├── Public/              # User-facing endpoints
│   │   │   ├── AuthController.php
│   │   │   ├── CheckoutController.php
│   │   │   ├── CartController.php
│   │   │   └── PageController.php
│   │   └── Admin/               # Admin-only endpoints
│   │       └── ManagementController.php
│   │
│   ├── Models/
│   │   ├── UserModel.php        # User queries and auth
│   │   ├── MarketplaceModel.php # Products, orders, purchases
│   │   ├── InquiryModel.php     # Support inquiries
│   │   └── AdminModel.php       # Admin-specific queries
│   │
│   ├── Services/
│   │   ├── SessionAuth.php      # Authentication logic
│   │   ├── SessionCart.php      # Shopping cart logic
│   │   ├── EmailService.php     # SMTP email sending
│   │   ├── FileService.php      # File upload/download
│   │   └── SecurityService.php  # Security utilities
│   │
│   ├── Core/
│   │   ├── Application.php      # Core app router and bootstrap
│   │   ├── Controller.php       # Base controller class
│   │   ├── Router.php           # Route matching and dispatch
│   │   └── Database.php         # Database connection handler
│   │
│   ├── Views/
│   │   ├── layouts/
│   │   │   └── app.php          # Main HTML layout
│   │   ├── partials/
│   │   │   ├── header.php       # Navigation bar
│   │   │   ├── footer.php       # Footer
│   │   │   └── site_styles.php  # CSS variables and theme
│   │   ├── Public/pages/
│   │   │   ├── home.php
│   │   │   ├── marketplace.php
│   │   │   ├── product-detail.php
│   │   │   ├── checkout.php
│   │   │   ├── cart.php
│   │   │   ├── profile.php
│   │   │   ├── login.php
│   │   │   ├── register.php
│   │   │   ├── verify.php
│   │   │   └── [more pages...]
│   │   └── Admin/
│   │       ├── pages/
│   │       │   ├── admin.php
│   │       │   └── admin-login.php
│   │       └── sections/
│   │           ├── dashboard.php
│   │           ├── products.php
│   │           ├── orders.php
│   │           ├── users.php
│   │           ├── email.php
│   │           ├── categories.php
│   │           ├── [more sections...]
│   │
│   └── helpers.php              # Global utility functions
│
├── config/
│   ├── app.php                  # App settings
│   ├── database.php             # DB credentials
│   └── jwt.php                  # JWT config
│
├── routes/
│   ├── web.php                  # Public web routes
│   ├── public.php               # Public API routes
│   ├── admin.php                # Admin routes
│   └── api.php                  # JSON API routes
│
├── bootstrap/
│   └── app.php                  # Bootstrap and env loader
│
├── database/
│   ├── migrations/              # SQL migration files (001-011)
│   ├── seeds/                   # Initial data seeds
│   ├── schema.sql               # Full database schema
│   ├── fullsetup.sql            # Complete setup file
│   ├── migrate.php              # Migration runner
│   └── rebuild.php              # Database rebuild script
│
├── storage/
│   ├── data/                    # JSON data files
│   │   ├── users.json
│   │   ├── products.json
│   │   ├── orders.json
│   │   ├── email-templates.json
│   │   └── [more...]
│   ├── files/                   # Uploaded product files
│   └── logs/                    # Activity and error logs
│
├── public/                      # Web-accessible static files
│   └── assets/
│
├── assets/                      # Product downloads and media
│   └── products/
│
├── .env.example                 # Environment template
├── .htaccess                    # Apache URL rewriting
├── index.php                    # Application entry point
├── README.md                    # This file
└── CHANGES.md                   # Changelog and bug fixes
```

---

## 🚀 Installation & Setup

### **Prerequisites**
- PHP 8.1 or higher
- MySQL 8.0 / MariaDB 10.5+
- Apache with `mod_rewrite` enabled (or Nginx with proper config)
- Composer (optional, for any dependencies)

### **Step 1: Clone/Extract the Repository**

```bash
git clone https://github.com/your-username/pixelvault.git
cd pixelvault
```

Or extract the provided ZIP file to your web root:

```bash
unzip digitak_market_app_php.zip
cd "digitak mrket app -php"
```

### **Step 2: Configure Environment**

Copy the example env file and update credentials:

```bash
cp .env.example .env
```

Edit `.env` with your configuration:

```env
# Database
DB_HOST=localhost
DB_NAME=pixelvault
DB_USER=root
DB_PASS=your_password
DB_PORT=3306

# SMTP Email
SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_USER=your_email@gmail.com
SMTP_PASS=your_app_password
SMTP_FROM=noreply@pixelvault.com

# App
APP_NAME=PixelVault
APP_URL=http://localhost:8000
APP_DEBUG=true

# JWT
JWT_SECRET=your_super_secret_key_here_min_32_chars
JWT_ALGORITHM=HS256
JWT_EXPIRY=7200

# Admin
ADMIN_EMAIL=admin@pixelvault.com
ADMIN_PASSWORD=secure_password_hash
```

### **Step 3: Setup Database**

**Option A: Using the migration script (Recommended)**

```bash
php database/migrate.php
```

**Option B: Direct SQL import**

```bash
mysql -u root -p pixelvault < database/fullsetup.sql
```

Or use phpMyAdmin:
1. Create a new database: `pixelvault`
2. Import `database/fullsetup.sql`

**Option C: Manual steps**

```bash
# Create database
mysql -u root -p -e "CREATE DATABASE pixelvault CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# Import schema
mysql -u root -p pixelvault < database/schema.sql

# Run migrations
php database/migrate.php

# Load seed data
mysql -u root -p pixelvault < database/seeds/001_initial_seed.sql
```

### **Step 4: Web Server Configuration**

#### **Apache Setup**
The `.htaccess` file handles routing. Ensure these modules are enabled:

```bash
sudo a2enmod rewrite
sudo a2enmod headers
sudo systemctl restart apache2
```

#### **PHP Development Server (Quick Testing)**

```bash
php -S localhost:8000
```

Then visit: `http://localhost:8000`

#### **Nginx Setup**

```nginx
server {
    listen 80;
    server_name pixelvault.local;

    root /var/www/pixelvault;
    index index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
}
```

### **Step 5: Create Storage Directories**

Ensure these directories are writable:

```bash
chmod -R 755 storage/
chmod -R 755 storage/data/
chmod -R 755 storage/files/
chmod -R 755 storage/logs/
```

### **Step 6: Default Admin Credentials**

After setup, log in as admin:

- **URL**: `http://localhost:8000/admin/login`
- **Email**: `admin@pixelvault.com` (from `.env`)
- **Password**: Set in `.env` `ADMIN_PASSWORD` field

---

## 📚 Core Features Guide

### **User Registration & Authentication**

1. **Registration Flow**:
   - User submits email, password, name
   - System sends verification code
   - User enters code to activate account
   - Account is created with 2FA enabled by default

2. **Login Flow**:
   - User enters email and password
   - System validates credentials
   - Sends 2FA verification code via email
   - User enters code to complete login
   - Session cookie is set

3. **Password Recovery**:
   - User requests password reset
   - System generates time-limited token
   - Email sent with reset link
   - User can reset password within expiry window

### **Shopping & Checkout**

1. **Add to Cart**:
   - User adds product to cart (session-based)
   - Real-time cart count updates
   - Can view cart at `/cart` route

2. **Checkout Process**:
   - User proceeds to checkout
   - Confirms purchase details
   - If not logged in, can register or use guest checkout
   - Order is created and marked as "pending"
   - User receives confirmation email

3. **Order History**:
   - Users can view past purchases in profile
   - Download product files from order history
   - Track update counts and available downloads

### **Product Management (Admin)**

1. **Create Product**:
   - Upload ZIP file containing product
   - Fill in title, description, pricing, licensing
   - Assign to category
   - Set demo URL and version
   - Product goes live immediately

2. **Manage Updates**:
   - Upload new version as ZIP
   - Add changelog entry
   - Mark as current version
   - Users see update notification

3. **Track Analytics**:
   - View sales per product
   - Monitor download counts
   - Track update usage
   - See customer inquiries

### **Email System**

The app sends automated emails for:
- Email verification (registration)
- 2FA codes (login)
- Password reset links
- Order confirmations
- Update notifications

Customize templates in Admin > Email Settings.

---

## 🔒 Security Features

### **Built-In Protections**
- **Password Hashing**: Uses PHP's `password_hash()` with bcrypt
- **Session Security**: Secure cookie handling with HttpOnly flag
- **CSRF Protection**: Request validation tokens on forms
- **SQL Injection Prevention**: Prepared statements throughout
- **XSS Protection**: Output escaping on all user data
- **Input Validation**: Server-side validation on all endpoints

### **Admin Security**
- **Role-Based Access**: Only admins can access `/admin/*` routes
- **Activity Logging**: All admin actions are logged
- **Security Settings**: Toggle security features globally
- **Account Management**: Admin-only password changes

---

## 📊 Database Schema Overview

### **Core Tables**

| Table | Purpose |
|-------|---------|
| `users` | User accounts, authentication |
| `products` | Product catalog |
| `categories` | Product categories |
| `orders` | Customer orders/purchases |
| `order_items` | Individual items in orders |
| `payments` | Payment records |
| `product_versions` | Product update history |
| `user_product_access` | Download/update quotas per user |
| `inquiries` | Customer support tickets |
| `email_templates` | Customizable email templates |
| `admin_accounts` | Admin user accounts |
| `activity_log` | Audit trail of actions |

See `database/schema.sql` for full schema details.

---

## 🛣️ API Routes

### **Public Routes**

```
GET    /                      # Homepage
GET    /marketplace           # Product listing
GET    /product/{slug}        # Product details
GET    /categories            # Category listing
POST   /register              # User registration
POST   /verify                # Email verification
GET    /login                 # Login page
POST   /login                 # Process login
GET    /logout                # Logout
GET    /forgot-password       # Password reset form
POST   /forgot-password       # Send reset email
GET    /reset-password        # Reset password form
POST   /reset-password        # Process reset
GET    /profile               # User profile
GET    /cart                  # Shopping cart
POST   /cart/add              # Add to cart
POST   /cart/remove           # Remove from cart
GET    /checkout              # Checkout page
POST   /checkout              # Process order
GET    /success               # Order success page
GET    /updates               # Product updates listing
GET    /pricing               # Pricing page
```

### **Admin Routes**

```
GET    /admin/login                 # Admin login
POST   /admin/login                 # Process login
GET    /admin                       # Dashboard
POST   /admin/logout                # Logout

# Products
GET    /admin/products              # Product list
POST   /admin/products/create       # Create product
POST   /admin/products/{id}/update  # Update product
POST   /admin/products/{id}/delete  # Delete product

# Orders
GET    /admin/orders                # Order list
GET    /admin/orders/{id}           # Order details

# Users
GET    /admin/users                 # User list
GET    /admin/users/{id}            # User details

# Email
GET    /admin/email                 # Email template editor
POST   /admin/email/save            # Save template

# Settings
GET    /admin/settings              # Settings page
POST   /admin/settings/save         # Save settings
POST   /admin/security/toggle       # Toggle security feature
POST   /admin/security/sitemap      # Generate sitemap

# Inquiries
GET    /admin/inquiries             # Inquiry list
GET    /admin/inquiries/{id}        # Inquiry details
POST   /admin/inquiries/{id}/reply  # Reply to inquiry

# Categories
GET    /admin/categories            # Category list
POST   /admin/categories/create     # Create category
POST   /admin/categories/{id}/delete # Delete category
```

---

## 🎨 Customization

### **Theme & Design**

The app uses CSS variables for theming. Customize colors in:
- `app/Views/partials/site_styles.php` — Color tokens and design system

Key variables:
```css
--primary: 18 92% 54%;           /* Brand orange */
--background: 38 35% 97%;        /* Warm cream */
--ink: 24 14% 8%;                /* Dark text */
--surface: 0 0% 100%;            /* Pure white */
--border: 0 0% 90%;              /* Light gray */
```

### **Email Templates**

Customize in Admin Panel > Email Settings:
- **Registration Verification** — Subject, body, CTA button
- **Password Reset** — Link and instructions
- **Order Confirmation** — Order details layout
- **Update Notification** — Update announcement

### **Navigation**

Edit main navigation in:
- `storage/data/navigation.json` — Nav links and structure

---

## 🐛 Troubleshooting

### **Database Connection Error**

Check `.env` credentials:
```bash
mysql -u your_user -p -h localhost -e "USE pixelvault; SHOW TABLES;"
```

### **Email Not Sending**

1. Verify SMTP credentials in `.env`
2. Check `storage/logs/` for email errors
3. Enable less secure apps (if using Gmail)
4. Test SMTP connection manually

### **File Upload Issues**

1. Ensure `storage/files/` is writable: `chmod 755 storage/files/`
2. Check PHP `upload_max_filesize` in `php.ini`
3. Verify file permissions with: `ls -la storage/files/`

### **Routing Not Working**

1. Ensure Apache has `mod_rewrite` enabled
2. Check `.htaccess` exists and is readable
3. Verify `AllowOverride All` in Apache config
4. Restart Apache: `sudo systemctl restart apache2`

### **Session Issues**

1. Ensure `php.ini` has `session.save_path` writable
2. Clear browser cookies and try again
3. Check `session.cookie_httponly` is `On` in `php.ini`

---

## 📋 Recent Changes & Improvements

See `CHANGES.md` for detailed changelog including:
- **Bug fixes** (B1-B20): Performance, security, and logic corrections
- **Design refresh**: Enhanced UI with tighter spacing and premium aesthetics
- **Admin improvements**: Wired email templates, security toggles, sitemap generation

---

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/your-feature`
3. Make changes (without altering core business logic)
4. Commit: `git commit -m "Add your feature"`
5. Push: `git push origin feature/your-feature`
6. Submit a pull request

### **Guidelines**
- Do not change database schema without updating migrations
- Keep controllers focused on request/response
- Add models for new data operations
- Update views consistently with design system
- Test thoroughly before submitting

---

## 📄 License

This project is provided as-is. See license agreement for terms.

---

## 📞 Support

For issues and questions:
- Check `CHANGES.md` for known issues
- Review error logs in `storage/logs/`
- Contact kavindusd2000@gmail.com

---

## 🎯 Roadmap (Potential Future Features)

- [ ] Automated payment gateway integration (Stripe/PayPal)
- [ ] Advanced analytics dashboard
- [ ] Bulk user import/export
- [ ] Multi-currency support
- [ ] Product affiliate system
- [ ] Advanced email marketing features
- [ ] API rate limiting and quota system
- [ ] Two-factor SMS authentication
- [ ] Product subscription/licensing models
- [ ] Marketplace analytics and reporting

---

**Designed with ❤️ for digital creators worldwide.**

Last Updated: April 29, 2026 
