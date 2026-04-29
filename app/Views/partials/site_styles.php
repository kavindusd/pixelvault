<?php
/**
 * Global Stylesheet (PHP-driven for dynamic configurations)
 */
$theme = site_config('site_theme', 'vivid_orange');

// Define palette presets
$palettes = [
    'vivid_orange' => [
        'bg_light' => '18 30% 98%', 'bg_dark' => '18 30% 5%',
        'card_light' => '0 0% 100%', 'card_dark' => '18 25% 8%',
        'border_light' => '18 15% 90%', 'border_dark' => '18 15% 15%',
        'primary' => '#ff4d00',
        'grid' => '18 15% 92%'
    ],
    'emerald_forest' => [
        'bg_light' => '161 30% 98%', 'bg_dark' => '161 30% 5%',
        'card_light' => '0 0% 100%', 'card_dark' => '161 25% 8%',
        'border_light' => '161 15% 90%', 'border_dark' => '161 15% 15%',
        'primary' => '#00FAAA',
        'grid' => '161 15% 92%'
    ],
    'midnight_purple' => [
        'bg_light' => '258 30% 98%', 'bg_dark' => '258 30% 5%',
        'card_light' => '0 0% 100%', 'card_dark' => '258 25% 8%',
        'border_light' => '258 15% 90%', 'border_dark' => '258 15% 15%',
        'primary' => '#8F5FFF',
        'grid' => '258 15% 92%'
    ],
    'ocean_blue' => [
        'bg_light' => '218 30% 98%', 'bg_dark' => '218 30% 6%',
        'card_light' => '0 0% 100%', 'card_dark' => '218 25% 9%',
        'border_light' => '218 15% 90%', 'border_dark' => '218 15% 16%',
        'primary' => '#0050D8',
        'grid' => '218 15% 92%'
    ],
    'cyber_rose' => [
        'bg_light' => '345 30% 98%', 'bg_dark' => '345 30% 5%',
        'card_light' => '0 0% 100%', 'card_dark' => '345 25% 8%',
        'border_light' => '345 15% 90%', 'border_dark' => '345 15% 15%',
        'primary' => '#E8003A',
        'grid' => '345 15% 92%'
    ],
    'golden_amber' => [
        'bg_light' => '254 30% 98%', 'bg_dark' => '254 30% 5%',
        'card_light' => '0 0% 100%', 'card_dark' => '254 25% 8%',
        'border_light' => '254 15% 90%', 'border_dark' => '254 15% 15%',
        'primary' => '#3B00FF',
        'grid' => '254 15% 92%'
    ],
];

$p = $palettes[$theme] ?? $palettes['vivid_orange'];

// Override primary if manual color is set and not the default
$manualPrimary = site_config('primary_color');
if ($manualPrimary && $manualPrimary !== '#f97316' && $theme === 'vivid_orange') {
    $p['primary'] = $manualPrimary;
}

$primaryBase = hex_to_hsl($p['primary']);
$primaryHsl = $primaryBase[0] . ' ' . $primaryBase[1] . '% ' . $primaryBase[2] . '%';
$primaryGlowHsl = $primaryBase[0] . ' ' . min(100, (int)$primaryBase[1] + 10) . '% ' . min(100, (int)$primaryBase[2] + 10) . '%';

// Dark mode tweaks: slightly more vibrant
$primaryHslDark = $primaryBase[0] . ' ' . min(100, (int)$primaryBase[1] + 5) . '% ' . min(100, (int)$primaryBase[2] + 5) . '%';
$primaryGlowHslDark = $primaryBase[0] . ' ' . min(100, (int)$primaryBase[1] + 15) . '% ' . min(100, (int)$primaryBase[2] + 15) . '%';
?>
<style>
:root {
  --background: <?= $p['bg_light'] ?>;
  --foreground: 24 14% 9%;
  --card: <?= $p['card_light'] ?>;
  --card-foreground: 24 14% 9%;
  --popover: <?= $p['card_light'] ?>;
  --popover-foreground: 24 14% 9%;

  --primary: <?= $primaryHsl ?? '18 92% 54%' ?>;
  --primary-foreground: 0 0% 100%;
  --primary-glow: <?= $primaryGlowHsl ?? '24 100% 66%' ?>;

  --ink: 24 14% 8%;
  --ink-foreground: 38 35% 97%;

  --secondary: 36 22% 93%;
  --secondary-foreground: 24 14% 12%;
  --muted: 36 18% 95%;
  --muted-foreground: 24 6% 38%;
  --accent: var(--primary);
  --accent-foreground: 0 0% 100%;
  --destructive: 358 78% 56%;
  --destructive-foreground: 0 0% 100%;
  --success: 152 60% 38%;
  --success-foreground: 0 0% 100%;

  --border: <?= $p['border_light'] ?>;
  --input: <?= $p['border_light'] ?>;
  --ring: var(--primary);

  --radius: 0.5rem;
  --radius-sm: 0.375rem;
  --radius-md: 0.5rem;
  --radius-lg: 0.625rem;
  --radius-xl: 0.875rem;
  --radius-2xl: 1rem;
  --dropdown-bg: hsl(var(--card));
  --dropdown-border: hsl(var(--border));
  --dropdown-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.15);

  --gradient-glow: radial-gradient(70% 50% at 50% 0%, hsl(var(--primary) / .12), transparent 70%);
  --gradient-mesh: linear-gradient(135deg, hsl(var(--primary) / .08), hsl(280 70% 60% / .04) 50%, hsl(36 100% 70% / .08));
  --gradient-card: linear-gradient(180deg, hsl(var(--card)), hsl(var(--background)));
  --gradient-ink: linear-gradient(180deg, hsl(24 14% 10%), hsl(24 14% 7%));

  --shadow-soft: 0 1px 2px hsl(24 14% 9% / .04), 0 6px 16px -8px hsl(24 14% 9% / .08);
  --shadow-elevated: 0 18px 48px -20px hsl(var(--primary) / .25), 0 6px 18px -10px hsl(24 14% 9% / .14);
  --shadow-ink: 0 6px 18px -6px hsl(24 14% 8% / .35);
  --shadow-glow: 0 0 0 1px hsl(var(--primary) / .1), 0 4px 12px -4px hsl(var(--primary) / .25);

  --grid-line: <?= $p['grid'] ?>;

  --font-display: 'Outfit', system-ui, sans-serif;
  --font-serif: 'Outfit', system-ui, sans-serif;
  --font-sans: 'Plus Jakarta Sans', system-ui, sans-serif;
  --font-navbar: 'Plus Jakarta Sans', system-ui, sans-serif;
  --font-mono: 'JetBrains Mono', monospace;
}

.dark {
  --background: <?= $p['bg_dark'] ?>;
  --foreground: 38 28% 94%;
  --card: <?= $p['card_dark'] ?>;
  --card-foreground: 38 28% 94%;
  --popover: <?= $p['card_dark'] ?>;
  --popover-foreground: 38 28% 94%;
  --primary: <?= $primaryHslDark ?? '18 96% 60%' ?>;
  --primary-foreground: 24 14% 6%;
  --primary-glow: <?= $primaryGlowHslDark ?? '24 100% 68%' ?>;
  --ink: 38 28% 94%;
  --ink-foreground: 24 14% 6%;
  --secondary: 24 8% 14%;
  --secondary-foreground: 38 28% 94%;
  --muted: 24 8% 12%;
  --muted-foreground: 30 8% 60%;
  --accent: var(--primary);
  --accent-foreground: 24 14% 6%;
  --destructive: 358 65% 56%;
  --destructive-foreground: 0 0% 100%;
  --success: 152 50% 50%;
  --success-foreground: 0 0% 100%;
  --border: <?= $p['border_dark'] ?>;
  --input: <?= $p['border_dark'] ?>;
  --ring: var(--primary);
  --gradient-glow: radial-gradient(70% 50% at 50% 0%, hsl(var(--primary) / .42), transparent 70%);
  --gradient-mesh: linear-gradient(135deg, hsl(var(--primary) / .22), hsl(280 70% 50% / .14) 50%, hsl(36 100% 55% / .18));
  --gradient-card: linear-gradient(180deg, hsl(var(--card)), hsl(var(--background)));
  --gradient-ink: linear-gradient(180deg, hsl(38 28% 96%), hsl(38 28% 92%));
  --shadow-soft: 0 1px 2px hsl(0 0% 0% / .3), 0 6px 16px -8px hsl(0 0% 0% / .5);
  --shadow-elevated: 0 18px 48px -20px hsl(var(--primary) / .45), 0 6px 18px -10px hsl(0 0% 0% / .55);
  --shadow-ink: 0 6px 18px -6px hsl(0 0% 0% / .6);
  --shadow-glow: 0 0 0 1px hsl(var(--primary) / .25), 0 8px 28px -8px hsl(var(--primary) / .45);
  --grid-line: <?= $p['grid'] ?>;
  --dropdown-bg: hsl(var(--card));
  --dropdown-border: hsl(var(--border));
  --dropdown-shadow: 0 20px 50px -12px rgba(0, 0, 0, 0.4);
}

body::before { display: none !important; }

/* Global Theme Transition */
body:not(.preload) *, body:not(.preload) *::before, body:not(.preload) *::after {
  transition: background-color 0.4s cubic-bezier(0.4, 0, 0.2, 1), 
              border-color 0.4s cubic-bezier(0.4, 0, 0.2, 1),
              color 0.4s cubic-bezier(0.4, 0, 0.2, 1),
              box-shadow 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.preload * {
  transition: none !important;
}

/* Ensure images and specific animated elements don't lag */
img, .reveal-on-scroll, [data-theme-toggle] .theme-switch-thumb {
  transition: transform 0.4s cubic-bezier(0.22, 1, 0.36, 1), opacity 0.4s ease !important;
}

/* ----- Typography utility shims ----- */
.font-display { font-family: var(--font-display); letter-spacing: -0.02em; }
.font-serif   { font-family: var(--font-serif); }
.font-sans    { font-family: var(--font-sans); }
.font-navbar  { font-family: var(--font-navbar); letter-spacing: -0.01em; }
.font-mono    { font-family: var(--font-mono); }

/* ----- Custom backgrounds + radii ----- */
.bg-ink              { background-color: hsl(var(--ink)); }
.text-ink            { color: hsl(var(--ink)); }
.text-ink-foreground { color: hsl(var(--ink-foreground)); }
.bg-gradient-glow    { background: var(--gradient-glow); }
.bg-gradient-mesh    { background: var(--gradient-mesh); }
.bg-gradient-card    { background: var(--gradient-card); }
.bg-gradient-ink     { background: var(--gradient-ink); }

.shadow-soft     { box-shadow: var(--shadow-soft); }
.shadow-elevated { box-shadow: var(--shadow-elevated); }
.shadow-ink      { box-shadow: var(--shadow-ink); }
.shadow-glow     { box-shadow: var(--shadow-glow); }

.glow-mesh {
  background:
    radial-gradient(40% 60% at 30% 30%, hsl(var(--primary) / .25), transparent 60%),
    radial-gradient(40% 60% at 70% 70%, hsl(280 70% 65% / .18), transparent 60%);
  filter: blur(40px);
}

.grid-bg {
  background-image:
    linear-gradient(to right, hsl(var(--grid-line) / 0.7) 1px, transparent 1px),
    linear-gradient(to bottom, hsl(var(--grid-line) / 0.7) 1px, transparent 1px);
  background-size: 56px 56px;
  mask-image: radial-gradient(circle at center, black 30%, transparent 80%);
  -webkit-mask-image: radial-gradient(circle at center, black 30%, transparent 80%);
  opacity: 0.8;
}

.dark .grid-bg {
  opacity: 0.15;
}

/* Sharper corners */
.rounded-sm   { border-radius: var(--radius-sm) !important; }
.rounded      { border-radius: var(--radius) !important; }
.rounded-md   { border-radius: var(--radius-md) !important; }
.rounded-lg   { border-radius: var(--radius-lg) !important; }
.rounded-xl   { border-radius: var(--radius-xl) !important; }
.rounded-2xl  { border-radius: var(--radius-2xl) !important; }
.rounded-3xl  { border-radius: 1.25rem !important; }
.rounded-\[2rem\] { border-radius: 1.125rem !important; }

/* Global Dropdown Design */
.pv-dropdown {
  position: relative;
  display: inline-block;
}

.pv-dropdown-trigger {
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.pv-dropdown-content {
  position: absolute;
  top: 100%;
  right: 0;
  margin-top: 0.75rem;
  min-width: 200px;
  background: var(--dropdown-bg);
  border: 1px solid var(--dropdown-border);
  border-radius: var(--radius-xl);
  box-shadow: var(--dropdown-shadow);
  opacity: 0;
  visibility: hidden;
  transform: translateY(10px) scale(0.95);
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  z-index: 60;
  overflow: hidden;
}

.pv-dropdown:hover .pv-dropdown-content,
.pv-dropdown:focus-within .pv-dropdown-content {
  opacity: 1;
  visibility: visible;
  transform: translateY(0) scale(1);
}

.pv-dropdown-item {
  display: block;
  padding: 0.75rem 1rem;
  font-size: 0.75rem;
  font-weight: 600;
  color: hsl(var(--muted-foreground));
  transition: all 0.2s ease;
  white-space: nowrap;
  text-decoration: none;
}

.pv-dropdown-item:hover {
  background: hsl(var(--primary) / 0.1);
  color: hsl(var(--primary));
  padding-left: 1.25rem;
}

.pv-dropdown-item.active {
  background: hsl(var(--primary));
  color: hsl(var(--primary-foreground));
}

/* Polished Standard Selects */
select {
  appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='currentColor' stroke-width='2.5'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M19 9l-7 7-7-7'%3E%3C/path%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 0.75rem center;
  background-size: 0.8em;
  padding-right: 2.5rem !important;
}

/* ============================================================ */
/*  NAVBAR                                                      */
/* ============================================================ */
[data-site-header-shell] {
  background: hsl(var(--background) / 0.65);
  backdrop-filter: blur(14px);
  -webkit-backdrop-filter: blur(14px);
  border: 1px solid hsl(var(--border) / 0.7);
  border-radius: var(--radius-xl);
  box-shadow: 0 6px 20px -14px hsl(24 14% 9% / 0.18);
  transition: all 0.7s cubic-bezier(0.4, 0, 0.2, 1) !important;
  max-width: 100%;
  margin: 0 auto;
}
[data-site-header-shell].is-scrolled {
  background: hsl(var(--background) / 0.92);
  border-color: hsl(var(--border));
  border-radius: var(--radius-2xl);
  max-width: 880px;
  padding-left: 0.875rem !important;
  padding-right: 0.5rem !important;
  box-shadow:
    0 14px 36px -18px hsl(24 14% 9% / 0.25),
    0 6px 14px -10px hsl(24 14% 9% / 0.18);
}
.dark [data-site-header-shell] {
  background: hsl(var(--card) / 0.55);
  border-color: hsl(var(--border) / 0.7);
}
.dark [data-site-header-shell].is-scrolled {
  background: hsl(var(--card) / 0.92);
  box-shadow:
    0 14px 36px -18px hsl(0 0% 0% / 0.55),
    0 6px 14px -10px hsl(0 0% 0% / 0.45);
}

[data-site-header-shell] .pv-nav-links { gap: 0.25rem; transition: gap 320ms ease; }
[data-site-header-shell].is-scrolled .pv-nav-links { gap: 0.05rem; }
[data-site-header-shell] .pv-nav-links a { transition: padding 320ms ease, color 200ms ease; }
[data-site-header-shell].is-scrolled .pv-nav-links a {
  padding-left: 0.625rem;
  padding-right: 0.625rem;
  font-size: 0.8125rem;
}

.pv-nav-link { position: relative; z-index: 1; }
.pv-nav-link::before {
  content: '';
  position: absolute;
  inset: -2px -4px;
  background: radial-gradient(circle at center, hsl(var(--primary) / 0.15) 0%, transparent 70%);
  opacity: 0;
  transform: scale(0.85);
  transition: all 400ms cubic-bezier(0.22, 1, 0.36, 1);
  z-index: -1;
  filter: blur(10px);
  pointer-events: none;
}
.pv-nav-link:hover::before,
.pv-nav-link.is-active::before {
  opacity: 1;
  transform: scale(1.2);
}
.pv-nav-link:hover, .pv-nav-link.is-active {
  color: hsl(var(--primary)) !important;
  text-shadow: 0 0 10px hsl(var(--primary) / 0.15);
}

/* Theme toggle */
[data-theme-toggle] { position: relative; overflow: hidden; cursor: pointer; }
[data-theme-toggle] .theme-switch-thumb { transform: translateX(0); transition: transform 320ms cubic-bezier(0.22,1,0.36,1); }
[data-theme-toggle][aria-pressed="true"] .theme-switch-thumb { transform: translateX(1.75rem); }
.dark [data-theme-toggle] { color: hsl(var(--primary)); }

/* ============================================================ */
/*  Motion                                                      */
/* ============================================================ */
@keyframes pv-fade-in       { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }
@keyframes pv-scale-in      { from { opacity: 0; transform: scale(0.96); } to { opacity: 1; transform: scale(1); } }
@keyframes pv-shimmer       { 0% { background-position: -200% 0; } 100% { background-position: 200% 0; } }
@keyframes pv-float         { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }

.animate-fade-in    { animation: pv-fade-in 540ms cubic-bezier(0.22, 1, 0.36, 1) both; }
.animate-fade-in-up { animation: pv-fade-in 700ms cubic-bezier(0.22, 1, 0.36, 1) both; }
.animate-scale-in   { animation: pv-scale-in 420ms cubic-bezier(0.22, 1, 0.36, 1) both; }
.animate-float      { animation: pv-float 4.5s ease-in-out infinite; }

.search-filter-container {
  z-index: 20;
}

.reveal-on-scroll {
  opacity: 0;
  transition: all 0.8s cubic-bezier(0.22, 1, 0.36, 1);
  will-change: transform, opacity;
}
.reveal-on-scroll.is-visible { opacity: 1; transform: none !important; }

.reveal-slide-up { transform: translateY(30px); }
.reveal-slide-down { transform: translateY(-30px); }
.reveal-slide-left { transform: translateX(30px); }
.reveal-slide-right { transform: translateX(-30px); }
.reveal-scale { transform: scale(0.95); }

.hover-lift { transition: transform 240ms cubic-bezier(0.22,1,0.36,1), box-shadow 240ms ease; }
.hover-lift:hover { transform: translateY(-3px); box-shadow: var(--shadow-elevated); }

.pv-card {
  background-color: hsl(var(--card));
  border: 1px solid hsl(var(--border));
  border-radius: var(--radius-xl);
  transition: border-color 220ms ease, box-shadow 240ms ease, transform 240ms cubic-bezier(0.22,1,0.36,1);
}
.pv-card:hover {
  border-color: hsl(var(--primary) / 0.35);
  box-shadow: 0 14px 36px -18px hsl(18 92% 40% / 0.18), 0 4px 12px -6px hsl(24 14% 9% / 0.08);
}

.pv-btn-ink {
  background: var(--gradient-ink);
  color: hsl(var(--ink-foreground));
  box-shadow: var(--shadow-ink);
  border-radius: var(--radius-lg);
  transition: transform 200ms ease, box-shadow 220ms ease, filter 200ms ease;
}
.pv-btn-ink:hover { filter: brightness(1.08); transform: translateY(-1px); }
.pv-btn-ink:active { transform: translateY(0); }

::selection { background: hsl(var(--primary) / 0.25); color: hsl(var(--foreground)); }

[data-mobile-menu]:not(.hidden) { animation: pv-fade-in 320ms ease both; }

/* Admin Sidebar Utilities */
.admin-sidebar-active {
    background: linear-gradient(to right, hsl(var(--primary) / 0.15) 0%, hsl(var(--primary) / 0.08) 50%, transparent 100%);
    color: hsl(var(--primary));
}

/* ============================================================ */
/*  MOBILE RESPONSIVENESS                                       */
/* ============================================================ */

@media (max-width: 768px) {
  :root {
    --radius-xl: 0.75rem;
    --radius-2xl: 0.875rem;
  }

  .container {
    padding-left: 1.25rem !important;
    padding-right: 1.25rem !important;
  }

  /* Typography */
  .font-display.text-5xl, .font-display.text-6xl, .font-display.text-7xl {
    font-size: 2.75rem !important;
    line-height: 1.1 !important;
  }
  
  .font-display.text-4xl {
    font-size: 2.25rem !important;
  }

  /* Hero adjustments */
  section.pt-36 {
    padding-top: 7rem !important;
  }

  .metallic-headline {
    font-size: 3.5rem !important;
    line-height: 1.0 !important;
  }

  /* Grid Reset */
  .grid-cols-2, .grid-cols-3, .grid-cols-4 {
    grid-template-columns: repeat(1, minmax(0, 1fr)) !important;
  }
  
  .sm\:grid-cols-2 {
    grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
  }

  /* Navigation & Header */
  [data-site-header-shell] {
    margin: 0 0.5rem;
    padding: 0.5rem 0.75rem !important;
  }

  [data-site-header-shell] .font-serif.text-xl {
    font-size: 1.125rem !important;
  }

  /* Specific Component Tweaks */
  .perspective-1000 {
    perspective: none !important;
  }

  .perspective-1000 > div {
    transform: none !important;
  }

  /* Subscriptions Preview Card */
  .h-\[340px\] {
    height: auto !important;
    min-height: 300px;
  }

  .flex.h-\[340px\] {
    flex-direction: column;
    height: auto !important;
  }

  .w-16.border-r {
    width: 100% !important;
    height: auto !important;
    border-right: none !important;
    border-bottom: 1px solid hsl(var(--border));
    flex-direction: row !important;
    padding: 1rem !important;
    justify-content: center !important;
  }

  /* Logo Marquee */
  .animate-marquee {
    gap: 3rem !important;
  }

  /* Services List */
  .group\/item {
    padding: 1rem !important;
    border-radius: 1.25rem !important;
  }

  /* Product Cards */
  .aspect-\[16\/10\], .aspect-\[4\/3\] {
    aspect-ratio: 1 / 1 !important;
  }

  /* Search & Filter Bar */
  .search-filter-container form, 
  main .container > .flex-col.md\:flex-row {
    flex-direction: column !important;
    align-items: stretch !important;
    gap: 1rem !important;
  }
  
  .search-filter-container form > div,
  main .container > .flex-col.md\:flex-row > div {
    width: 100% !important;
  }

  .pv-dropdown-trigger {
    width: 100% !important;
    justify-content: space-between !important;
  }

  .pv-dropdown {
    width: 100% !important;
  }

  /* Hero Stats */
  .mt-12.grid.grid-cols-3 {
    grid-template-columns: repeat(3, 1fr) !important;
    gap: 1rem !important;
  }
  
  .mt-12.grid.grid-cols-3 .text-3xl {
    font-size: 1.5rem !important;
  }

  /* Checkout Billing Grid */
  form .grid-cols-2 {
    grid-template-columns: 1fr !important;
    gap: 1.5rem !important;
  }
  
  /* Header Specifics */
  [data-site-header-shell].is-scrolled {
    max-width: calc(100% - 1rem) !important;
  }

  /* Profile Page Grids */
  .lg\:pl-32 {
    padding-left: 0 !important;
  }
  
  .grid-cols-12 {
    display: flex !important;
    flex-direction: column !important;
    gap: 1.5rem !important;
    padding: 1.5rem !important;
  }
  
  .grid-cols-12 > div {
    grid-column: span 12 / span 12 !important;
    width: 100% !important;
    text-align: left !important;
  }
  
  .grid-cols-12 > div.col-span-2.text-right {
    text-align: left !important;
  }
  
  .grid-cols-12.border-b.bg-secondary\/40 {
    display: none !important; /* Hide table headers on mobile */
  }

  .lg\:h-screen {
    height: auto !important;
  }

  .h-\[calc\(100vh-80px\)\] {
    height: auto !important;
  }

  /* Buttons */
  .py-5.rounded-2xl {
    padding-top: 1rem !important;
    padding-bottom: 1rem !important;
  }
}

/* Extra small devices */
@media (max-width: 480px) {
  .metallic-headline {
    font-size: 2.75rem !important;
  }
  
  .sm\:grid-cols-2 {
    grid-template-columns: 1fr !important;
  }
  
  .font-display.text-5xl {
    font-size: 2.25rem !important;
  }
}
</style>
