# PixelVault — SEO Implementation Guide

## Overview

This guide covers every SEO task you need to implement, in priority order. Each task includes **what file to edit**, **what to add**, and **why it matters**.

---

## Priority 1 — Dynamic Meta Tags (Highest Impact)

### What to do
Make every page have a unique `<title>` and `<meta description>` instead of a hardcoded one.

### File: `app/Views/layouts/app.php`

Replace your static `<title>` and meta tags in `<head>` with dynamic PHP variables:

```html
<title><?= htmlspecialchars($seoTitle ?? 'PixelVault — Premium WordPress Plugins & Themes') ?></title>
<meta name="description" content="<?= htmlspecialchars($seoDescription ?? 'Buy premium WordPress plugins and themes at unbeatable prices. Elementor Pro, Divi, WP Rocket and more.') ?>">
<meta name="keywords" content="<?= htmlspecialchars($seoKeywords ?? 'wordpress plugins, wordpress themes, elementor pro, divi, wp rocket') ?>">
<link rel="canonical" href="<?= htmlspecialchars($seoCanonical ?? 'https://yourdomain.com' . $_SERVER['REQUEST_URI']) ?>">

<!-- Open Graph (for WhatsApp, Facebook previews) -->
<meta property="og:title" content="<?= htmlspecialchars($seoTitle ?? 'PixelVault') ?>">
<meta property="og:description" content="<?= htmlspecialchars($seoDescription ?? '') ?>">
<meta property="og:image" content="<?= htmlspecialchars($seoImage ?? 'https://yourdomain.com/storage/files/site/site_logo.png') ?>">
<meta property="og:url" content="<?= htmlspecialchars($seoCanonical ?? '') ?>">
<meta property="og:type" content="website">

<!-- Twitter Card -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= htmlspecialchars($seoTitle ?? 'PixelVault') ?>">
<meta name="twitter:description" content="<?= htmlspecialchars($seoDescription ?? '') ?>">
<meta name="twitter:image" content="<?= htmlspecialchars($seoImage ?? '') ?>">
```

### File: `app/Controllers/Public/PageController.php`

In each page method, pass SEO data to the view. Examples:

```php
// Home page
public function home(): void {
    $this->render('Public/pages/home', [
        'seoTitle'       => 'PixelVault — Buy Premium WordPress Plugins & Themes',
        'seoDescription' => 'Get Elementor Pro, Divi, WP Rocket, Yoast SEO and more at the best prices. Instant digital delivery.',
        'seoKeywords'    => 'buy elementor pro, buy divi theme, wp rocket cheap, premium wordpress plugins',
        'seoCanonical'   => 'https://yourdomain.com/',
        // ... other data
    ]);
}

// Product detail page
public function product(int $id): void {
    $product = $this->model->getProduct($id);
    $this->render('Public/pages/product-detail', [
        'seoTitle'       => $product['name'] . ' — PixelVault',
        'seoDescription' => 'Buy ' . $product['name'] . ' v' . $product['version'] . '. ' . substr($product['description'], 0, 120) . '...',
        'seoKeywords'    => 'buy ' . strtolower($product['name']) . ', ' . strtolower($product['name']) . ' cheap, ' . strtolower($product['name']) . ' download',
        'seoCanonical'   => 'https://yourdomain.com/product/' . $id,
        'seoImage'       => 'https://yourdomain.com/assets/products/' . $product['image'],
        // ... other data
    ]);
}

// Marketplace page
public function marketplace(): void {
    $this->render('Public/pages/marketplace', [
        'seoTitle'       => 'WordPress Plugin & Theme Marketplace — PixelVault',
        'seoDescription' => 'Browse our full collection of premium WordPress plugins and themes. All products include free updates.',
        'seoCanonical'   => 'https://yourdomain.com/marketplace',
    ]);
}

// Categories page
public function categories(): void {
    $this->render('Public/pages/categories', [
        'seoTitle'       => 'Plugin & Theme Categories — PixelVault',
        'seoDescription' => 'Browse WordPress plugins and themes by category. Page builders, SEO tools, security plugins, and more.',
        'seoCanonical'   => 'https://yourdomain.com/categories',
    ]);
}

// Pricing page
public function pricing(): void {
    $this->render('Public/pages/pricing', [
        'seoTitle'       => 'Pricing — PixelVault',
        'seoDescription' => 'Simple, transparent pricing. Get access to all premium WordPress plugins starting from just $X/month.',
        'seoCanonical'   => 'https://yourdomain.com/pricing',
    ]);
}
```

> **Note:** Pages like `/login`, `/register`, `/checkout`, `/profile`, `/download` should have a `<meta name="robots" content="noindex, nofollow">` tag — you don't want Google indexing those.

```html
<!-- Add this inside <head> for private/auth pages only -->
<?php if ($noIndex ?? false): ?>
<meta name="robots" content="noindex, nofollow">
<?php endif; ?>
```

And in their controllers: `'noIndex' => true`

---

## Priority 2 — `robots.txt`

### What to do
Create a `robots.txt` file in your web root to control what Google crawls.

### File: `public/robots.txt` (or your web root, same folder as `index.php`)

```
User-agent: *

# Allow public pages
Allow: /
Allow: /marketplace
Allow: /categories
Allow: /product/
Allow: /pricing
Allow: /updates

# Block private/functional pages
Disallow: /admin
Disallow: /admin/
Disallow: /checkout
Disallow: /login
Disallow: /register
Disallow: /profile
Disallow: /download/
Disallow: /cart/
Disallow: /auth/
Disallow: /verify
Disallow: /forgot-password
Disallow: /reset-password
Disallow: /success

# Block asset folders
Disallow: /storage/
Disallow: /build/
Disallow: /config/
Disallow: /app/
Disallow: /bootstrap/

# Sitemap location
Sitemap: https://yourdomain.com/sitemap.xml
```

---

## Priority 3 — Sitemap

### What to do
Your `rebuildSitemap()` method in `ManagementController.php` already exists. Verify it generates valid XML and outputs something like this:

### Expected `sitemap.xml` output

```xml
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">

  <!-- Static pages -->
  <url>
    <loc>https://yourdomain.com/</loc>
    <changefreq>daily</changefreq>
    <priority>1.0</priority>
  </url>
  <url>
    <loc>https://yourdomain.com/marketplace</loc>
    <changefreq>daily</changefreq>
    <priority>0.9</priority>
  </url>
  <url>
    <loc>https://yourdomain.com/categories</loc>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
  </url>
  <url>
    <loc>https://yourdomain.com/pricing</loc>
    <changefreq>monthly</changefreq>
    <priority>0.7</priority>
  </url>
  <url>
    <loc>https://yourdomain.com/updates</loc>
    <changefreq>weekly</changefreq>
    <priority>0.6</priority>
  </url>

  <!-- Dynamic product pages (loop from DB) -->
  <url>
    <loc>https://yourdomain.com/product/1</loc>
    <lastmod>2026-04-29</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.8</priority>
  </url>
  <!-- ... more products -->

</urlset>
```

### What to check in your `rebuildSitemap()` method
- It fetches all products from the DB and loops through them
- It writes to `public/sitemap.xml` (web-accessible path)
- It includes `<lastmod>` using the product's last updated timestamp
- After rebuilding, submit `https://yourdomain.com/sitemap.xml` to **Google Search Console**

---

## Priority 4 — Structured Data (Schema.org) on Product Pages

### What to do
Add a JSON-LD script block to your product detail page. This can make Google show rich results (price, name, image) directly in search.

### File: `app/Views/Public/pages/product-detail.php`

Add this inside `<head>` or just before `</body>`:

```html
<script type="application/ld+json">
{
  "@context": "https://schema.org/",
  "@type": "Product",
  "name": "<?= htmlspecialchars($product['name']) ?>",
  "image": "https://yourdomain.com/assets/products/<?= htmlspecialchars($product['image']) ?>",
  "description": "<?= htmlspecialchars($product['description']) ?>",
  "brand": {
    "@type": "Brand",
    "name": "PixelVault"
  },
  "offers": {
    "@type": "Offer",
    "url": "https://yourdomain.com/product/<?= $product['id'] ?>",
    "priceCurrency": "USD",
    "price": "<?= $product['price'] ?>",
    "availability": "https://schema.org/InStock",
    "seller": {
      "@type": "Organization",
      "name": "PixelVault"
    }
  }
}
</script>
```

Also add a breadcrumb schema on the same page:

```html
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    { "@type": "ListItem", "position": 1, "name": "Home", "item": "https://yourdomain.com/" },
    { "@type": "ListItem", "position": 2, "name": "Marketplace", "item": "https://yourdomain.com/marketplace" },
    { "@type": "ListItem", "position": 3, "name": "<?= htmlspecialchars($product['name']) ?>" }
  ]
}
</script>
```

---

## Priority 5 — Image Alt Text

### What to do
Every `<img>` tag in your views should have a meaningful `alt` attribute. This helps Google Image Search and accessibility.

### Files: All view files under `app/Views/`

**Bad:**
```html
<img src="/assets/products/elementor.png">
<img src="/assets/products/divi.png" alt="">
```

**Good:**
```html
<img src="/assets/products/elementor.png" alt="Elementor Pro WordPress Page Builder">
<img src="/assets/products/divi.png" alt="Divi Theme by Elegant Themes">
```

**For dynamic product images:**
```html
<img src="/assets/products/<?= htmlspecialchars($product['image']) ?>" 
     alt="<?= htmlspecialchars($product['name']) ?> — PixelVault">
```

---

## Priority 6 — `.htaccess` SEO Rules

### What to do
Add these rules to your existing `.htaccess` to improve technical SEO.

### File: `.htaccess`

```apache
# Force HTTPS (update yourdomain.com)
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://yourdomain.com/$1 [R=301,L]

# Remove trailing slash (canonicalization)
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)/$ /$1 [R=301,L]

# Browser caching for static assets
<IfModule mod_expires.c>
  ExpiresActive On
  ExpiresByType image/png "access plus 1 month"
  ExpiresByType image/jpg "access plus 1 month"
  ExpiresByType image/jpeg "access plus 1 month"
  ExpiresByType text/css "access plus 1 week"
  ExpiresByType application/javascript "access plus 1 week"
</IfModule>

# Gzip compression (faster page loads = better ranking)
<IfModule mod_deflate.c>
  AddOutputFilterByType DEFLATE text/html text/css application/javascript application/json
</IfModule>
```

---

## Priority 7 — Heading Structure in Views

### What to do
Each page should have exactly **one `<h1>`** that describes the page clearly. Subheadings should use `<h2>` and `<h3>`.

### What to check per page

| Page | `<h1>` should say |
|---|---|
| Home | `Buy Premium WordPress Plugins & Themes` |
| Marketplace | `WordPress Plugin & Theme Marketplace` |
| Product Detail | `{Product Name}` (the actual product name) |
| Categories | `Browse by Category` |
| Pricing | `Simple, Transparent Pricing` |
| Updates | `Latest Plugin & Theme Updates` |

Make sure you don't have multiple `<h1>` tags on a single page (common mistake with headers and hero sections both using `<h1>`).

---

## Priority 8 — URL Fix for Category Filter (Bug B9)

### What to do
Your marketplace filter `?cat=` breaks for category names with `&`. Fix this so Google can crawl category-filtered pages correctly.

### File: `app/Views/Public/pages/marketplace.php`

Change category links from:
```html
<a href="/marketplace?cat=<?= $category['name'] ?>">
```

To:
```html
<a href="/marketplace?cat=<?= urlencode($category['name']) ?>">
```

And add a canonical tag in the controller for filtered views to prevent duplicate indexing:

```php
// In marketplace() method of PageController.php
$cat = $_GET['cat'] ?? null;
$canonical = $cat 
    ? 'https://yourdomain.com/marketplace?cat=' . urlencode($cat)
    : 'https://yourdomain.com/marketplace';
```

---

## Summary Checklist

| # | Task | File(s) | Effort |
|---|---|---|---|
| 1 | Dynamic `<title>` + meta tags | `app.php` layout + `PageController.php` | Medium |
| 2 | `robots.txt` | `public/robots.txt` (new file) | Easy |
| 3 | Verify & complete sitemap | `ManagementController.php` | Easy |
| 4 | Structured data (JSON-LD) | `product-detail.php` | Medium |
| 5 | Image alt text | All view files | Easy |
| 6 | `.htaccess` caching + HTTPS | `.htaccess` | Easy |
| 7 | `<h1>` heading structure | All page views | Easy |
| 8 | `urlencode()` on category filter | `marketplace.php` + `PageController.php` | Easy |

---

## After Implementing — Google Search Console

1. Go to [search.google.com/search-console](https://search.google.com/search-console)
2. Add your domain and verify ownership (add a `<meta name="google-site-verification">` tag to your layout)
3. Submit your sitemap URL: `https://yourdomain.com/sitemap.xml`
4. Monitor which pages Google has indexed and fix any crawl errors

---

*Replace all instances of `yourdomain.com` with your actual domain before deploying.*
