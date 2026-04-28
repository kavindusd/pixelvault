# PixelVault — patch + design refresh

This archive contains your original PHP project with **bug fixes** and a **design refinement** layered on top of the existing aesthetic. No business logic, routes, controllers, models, schema, or JSON data structures were renamed.

---

## ✅ Bug fixes applied

| ID  | File(s) | What changed |
|-----|---------|--------------|
| B1  | `app/Services/SessionAuth.php` | `purchaseProducts()` and `incrementProductUpdateCount()` now call `->user()` once instead of twice (was 2× DB round-trips per call). |
| B2  | `app/Core/Application.php` | `run()` wraps dispatch in try/catch. In debug mode it re-throws; in production it renders a friendly branded 500 page instead of leaking stack traces. |
| B4  | `app/Models/MarketplaceModel.php` (`createOrder`) | When a returning buyer re-purchases a product they already own, `user_product_access` now resets `update_count`, `override_extra_downloads`, `override_reason`, and `max_update_downloads = 3`. Previously they kept the old (possibly exhausted) quota. |
| B5,B6 | `app/Controllers/Public/CheckoutController.php` | Guest checkout no longer silently 500s on a wrong password. The order is only created after we verify a valid authenticated user. Failures redirect to `/checkout?error=auth` or `/checkout?error=order`. `purchaseProducts()` now runs after the auth gate. |
| B7  | `app/Models/MarketplaceModel.php` (`products`, `mapProductRow`) | `products()` now `LEFT JOIN`s `product_versions WHERE is_current = 1` so list pages know the real latest version. Returned rows include a new `hasUpdate` boolean. |
| B8  | `app/Controllers/Public/PageController.php`, `app/Models/MarketplaceModel.php` | Product detail page now reads the actual `user_product_access` row (`max_update_downloads + override_extra_downloads`) instead of hardcoding `3`. New model method: `productAccessFor($userId, $productId)`. |
| B10 | `app/helpers.php` | Added missing `Activity`, `Server`, `Fingerprint` icon paths. The admin nav, dashboard quick-action card, and security-settings list previously rendered empty `<svg>` for these. |
| B18 | `bootstrap/app.php` | `.env` parser now strips surrounding `"` or `'` from values. `JWT_SECRET="abc"` no longer signs HMAC with literal quote characters. |
| B19 | `config/database.php` | Added explicit parens around `??`/`?:` for clarity (PHP precedence is `??` < `?:` so the original chain happened to work, but only by accident). |
| B20 | `app/Services/SessionCart.php` | Cart duplicate check now casts both ids to `int`. Previously a session-restored string id could bypass the check. |

### Issues documented but **not** auto-fixed (ask if you want them next round)

- **B11** Email Builder textareas have no `name=""` and no submit form — editing does nothing.
- **B12** Security toggles are static visuals, not real toggles.
- **B13** Category delete needs a `confirm()` and ideally CSRF token.
- **B9** Marketplace `?cat=` filter breaks for category names with `&` — links should `urlencode()` the value.

---

## 🎨 Design refresh

Same brand — warmer, sharper, more editorial. All changes layered through `app/Views/layouts/app.php` and `app/Views/partials/header.php`. The compiled Tailwind bundle in `build/assets/index-*.css` is left untouched; new styles override it through a `<style>` block loaded **after** the bundle.

### Token changes
- **Border-radius scale tightened** across the board (`--radius` now `0.5rem`, `--radius-2xl` now `1rem`). The previous `1rem` base felt squishy. Tailwind's `rounded-lg`/`rounded-xl`/`rounded-2xl` classes are remapped to the new scale via `!important` overrides in CSS — no view markup edits needed.
- Palette: refined orange (`--primary: 18 92% 54%`), denser ink (`--ink: 24 14% 8%`), warmer cream background (`--background: 38 35% 97%`), better dark mode contrast.
- New gradients: `--gradient-ink` (subtle vertical), refined `--gradient-glow` and `--gradient-mesh`.
- New shadow: `--shadow-glow` for primary-tinted focus rings.
- Typography: added **Geist** as the display font (was using Helvetica fallback). `font-display`, `font-navbar`, `font-serif`, `font-sans`, `font-mono` are now real CSS rules — not just CSS variables — so the Tailwind utility names that the views already use actually work.

### Navbar (matches your brief)
- Bordered "box" container with the same rounded radius as cards.
- **Shrinks on scroll**: `max-width: 880px`, sharper border, deeper shadow.
- Link gap and per-link padding tighten via CSS transitions when scrolled — feels like it physically condenses.
- Animated underline on hover and on the active link (replaces the static orange text).
- Smaller, sharper icon buttons (h-9 instead of h-10) to match the tightened radius scale.
- New `pv-btn-ink` class with subtle gradient + lift on hover.

### Motion
- Real keyframes added: `pv-fade-in`, `pv-fade-in-up`, `pv-scale-in`, `pv-shimmer`, `pv-float`. Exposed as `.animate-fade-in`, `.animate-fade-in-up`, `.animate-scale-in`, `.animate-float`.
- `.reveal-on-scroll` utility + IntersectionObserver — add the class to any element to fade-up when it enters the viewport.
- `.hover-lift` and `.pv-card` utilities for consistent card interactions.
- Mobile menu now fades in instead of snapping.

---

## ⏳ What's still pending (would need another turn)

The design tokens and the navbar are the foundation — they affect every page automatically. The following pages still use the old per-page markup and would each benefit from a tighter, more editorial pass (hero rebalance, refined product cards, denser admin tables, glassmorphic admin sidebar, etc.):

**Public**: `home.php`, `marketplace.php`, `product-detail.php`, `checkout.php`, `profile.php`, `pricing.php`, `categories.php`, `updates.php`, `success.php`, `not-found.php`, `footer.php`

**Admin**: `admin.php` (sidebar), all 7 sections in `app/Views/Admin/sections/`

The current files **work** with the new tokens — radii are already sharper, the palette is denser, the navbar is the new design — but they were written for the old token scale, so individual layouts could be refined further. Reply "continue refining" and I'll do the next batch.

---

## 🧪 Verified

- All PHP files (controllers, models, services, helpers, views) pass `php -l` syntax check.
- No file outside `app/`, `bootstrap/`, `config/` was touched.
- DB schema, migrations, seeds, routes, JSON data files: untouched.
- `build/assets/index-*.css`: untouched.

Drop the archive into the same web root, point Apache/Nginx at `index.php` with the existing `.htaccess`, and you're live.

## v2 — admin polish + wired settings

### Backend (PHP)
- `ManagementController::saveEmailTemplate()` — persists subject / body / cta to `storage/data/email-templates.json`.
- `ManagementController::toggleSecurity()` — flips a single security flag and persists.
- `ManagementController::rebuildSitemap()` — generates `sitemap.xml` from the products dataset.
- `routes/admin.php` — added `POST /admin/email/save`, `/admin/security/toggle`, `/admin/security/sitemap`.

### Admin UI
- **Sidebar** — glassmorphic panel: `bg-card/70 backdrop-blur-xl`, sticky inside a 1.5rem inset, gradient ambient mesh behind, gradient-to-r active state with primary rail, mono uppercase labels.
- **Login** — same glassy treatment, grid-bg + glow-mesh ambient, mono "Restricted · Admin only" tag, gradient top hairline, ink CTA with arrow.
- **Email Builder** — now a real `<form method="post" action="/admin/email/save">` with template-key tabs, mono labels, "Saved" toast via `?status=saved`, ink save button.
- **Security & SEO** — each toggle is its own form posting to `/admin/security/toggle` (truly toggles). Sitemap "Rebuild" button posts to `/admin/security/sitemap`.

### Public UI
- **Marketplace** — categories rendered as mono-uppercase chips with rounded-md; product cards use sharper `rounded-lg`, hover lift + border glow, staggered `reveal-on-scroll`, mono "GPL" / version pills.
