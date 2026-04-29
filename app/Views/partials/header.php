<?php
$navigation = $navigation ?? [];
$currentPath = $currentPath ?? '/';
$cartCount = $cartCount ?? 0;
$user = $user ?? null;
?>
<header class="fixed top-0 inset-x-0 z-50 transition-all duration-300 font-navbar pt-3 px-4">
  <div class="container">
    <div data-site-header-shell class="flex items-center justify-between gap-3 px-3 py-2">
      <a href="/" class="flex items-center gap-2 shrink-0">
        <?php $siteLogo = site_config('site_logo'); ?>
        <?php if ($siteLogo): ?>
          <img src="<?= e($siteLogo) ?>" alt="<?= e(site_config('site_name', 'PixelVault')) ?>" class="h-8 w-auto object-contain">
        <?php else: ?>
          <div class="h-8 w-8 rounded-md bg-primary flex items-center justify-center text-primary-foreground shadow-glow">
            <span class="font-display font-black text-xl leading-none">P</span>
          </div>
        <?php endif; ?>
        <span class="font-serif text-xl tracking-tight"><?= e(site_config('site_name', 'PixelVault')) ?></span>
      </a>

      <nav class="hidden lg:flex items-center pv-nav-links">
        <?php foreach ($navigation as $item): ?>
          <?php $active = is_active_path((string) ($item['href'] ?? ''), (string) $currentPath); ?>
          <a
            href="<?= e($item['href'] ?? '#') ?>"
            class="pv-nav-link relative px-4 py-2 text-sm font-medium transition-colors duration-200 <?= $active ? 'is-active text-foreground' : 'text-muted-foreground hover:text-foreground' ?>"
          >
            <?= e($item['label'] ?? '') ?>
          </a>
        <?php endforeach; ?>
      </nav>

      <div class="flex items-center gap-2 shrink-0">
        <div class="hidden sm:flex items-center gap-2">
          <!-- Premium Glass Theme Toggle -->
          <button type="button" data-theme-toggle aria-label="Toggle color theme" 
                  class="relative h-9 w-[60px] shrink-0 items-center rounded-full px-1
                         bg-gradient-to-br from-zinc-200/80 via-zinc-100/80 to-zinc-300/80 dark:from-zinc-800/80 dark:via-zinc-700/80 dark:to-zinc-900/80 
                         backdrop-blur-md border border-zinc-300/50 dark:border-zinc-600/50 shadow-sm hover:brightness-105 transition-all group">
            <span class="theme-switch-thumb flex h-7 w-7 items-center justify-center rounded-full bg-background text-foreground shadow-[0_2px_8px_rgba(0,0,0,0.15)] transition-all duration-300 border border-border/50 group-hover:shadow-[0_4px_12px_rgba(0,0,0,0.2)]">
              <span data-theme-icon-on class="flex items-center justify-center text-primary drop-shadow-[0_0_4px_hsl(var(--primary)/0.4)]"><?= icon_svg('Sun', 'h-4 w-4') ?></span>
              <span data-theme-icon-off class="hidden items-center justify-center text-primary drop-shadow-[0_0_4px_hsl(var(--primary)/0.4)]"><?= icon_svg('Moon', 'h-4 w-4') ?></span>
            </span>
          </button>

          <!-- Premium Glass Cart Button -->
          <a href="/checkout" aria-label="Cart" 
             class="relative h-9 w-9 shrink-0 flex items-center justify-center rounded-lg
                    bg-gradient-to-br from-zinc-200/80 via-zinc-100/80 to-zinc-300/80 dark:from-zinc-800/80 dark:via-zinc-700/80 dark:to-zinc-900/80 
                    backdrop-blur-md border border-zinc-300/50 dark:border-zinc-600/50 shadow-sm hover:brightness-105 transition-all group">
            <span class="text-zinc-600 dark:text-zinc-400 group-hover:text-primary group-hover:drop-shadow-[0_0_8px_hsl(var(--primary)/0.4)] transition-all"><?= icon_svg('ShoppingCart', 'h-5 w-5') ?></span>
            <?php if ($cartCount > 0): ?>
              <span class="absolute -top-1.5 -right-1.5 h-4 w-4 bg-primary text-[9px] font-bold text-primary-foreground rounded-full flex items-center justify-center ring-2 ring-background shadow-[0_0_10px_hsl(var(--primary)/0.5)] transition-all animate-scale-in"><?= e((string) $cartCount) ?></span>
            <?php endif; ?>
          </a>
        </div>

        <?php if ($user): ?>
          <a href="<?= ($user['role'] ?? '') === 'Administrator' ? '/admin' : '/profile' ?>" aria-label="Profile" class="hidden sm:inline-flex h-9 w-9 shrink-0 overflow-hidden items-center justify-center rounded-lg border-2 border-primary/25 p-0.5 shadow-sm hover:border-primary/60 transition-all">
            <img src="<?= e(!empty($user['avatar']) ? $user['avatar'] : 'https://api.dicebear.com/7.x/initials/svg?seed=' . urlencode($user['name'] ?? 'User') . '&backgroundColor=f97316') ?>" alt="<?= e((string) ($user['name'] ?? 'User')) ?>" class="h-full w-full rounded-md object-cover">
          </a>
        <?php else: ?>
          <a href="/login" class="group hidden sm:inline-flex h-9 items-center justify-center px-5 text-sm font-semibold text-zinc-800 dark:text-zinc-200 bg-gradient-to-br from-zinc-200/80 via-zinc-100/80 to-zinc-300/80 dark:from-zinc-800/80 dark:via-zinc-700/80 dark:to-zinc-900/80 backdrop-blur-md border border-zinc-300/50 dark:border-zinc-600/50 rounded-lg shadow-sm hover:shadow-md hover:brightness-105 hover:text-primary dark:hover:text-primary transition-all duration-300">
            <span class="group-hover:drop-shadow-[0_0_8px_hsl(var(--primary)/0.4)] transition-all">Sign In</span>
          </a>
        <?php endif; ?>
        <button type="button" data-menu-toggle aria-label="Open menu" class="lg:hidden h-9 w-9 shrink-0 rounded-md border border-border flex items-center justify-center bg-background/50">
          <?= icon_svg('Menu') ?>
        </button>
      </div>
    </div>
  </div>
</header>

<div data-mobile-menu class="fixed inset-0 z-[40] bg-background lg:hidden pt-28 px-6 pb-12 flex flex-col hidden">
  <div class="flex-1 space-y-8 mt-4">
    <p class="text-[10px] font-bold uppercase tracking-[0.3em] text-muted-foreground border-b border-border pb-4">Navigation</p>
    <div class="grid gap-5">
      <?php foreach ($navigation as $item): ?>
        <a href="<?= e($item['href'] ?? '#') ?>" class="text-3xl font-display tracking-tight hover:text-primary transition-colors">
          <?= e($item['label'] ?? '') ?>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
  <div class="space-y-6 pt-10 border-t border-border">
    <div class="flex items-center justify-between">
      <span class="text-xs font-bold uppercase tracking-widest text-muted-foreground">Appearance</span>
      <button type="button" data-theme-toggle aria-label="Toggle color theme" class="inline-flex relative h-8 w-[60px] shrink-0 items-center rounded-full bg-secondary/60 border border-border/40 hover:bg-secondary/90 transition-colors shadow-inner focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-primary/50 group">
        <span class="theme-switch-thumb absolute left-1 flex h-6 w-6 items-center justify-center rounded-full bg-background text-foreground shadow-[0_2px_8px_rgba(0,0,0,0.15)] transition-transform duration-300 border border-border/50 group-hover:shadow-[0_4px_12px_rgba(0,0,0,0.2)]">
          <span data-theme-icon-on class="flex items-center justify-center text-primary drop-shadow-[0_0_4px_hsl(var(--primary)/0.4)]"><?= icon_svg('Sun', 'h-3.5 w-3.5') ?></span>
          <span data-theme-icon-off class="hidden items-center justify-center text-primary drop-shadow-[0_0_4px_hsl(var(--primary)/0.4)]"><?= icon_svg('Moon', 'h-3.5 w-3.5') ?></span>
        </span>
      </button>
    </div>
    <?php if (!$user): ?>
      <a href="/login" class="group block w-full py-4 text-center font-semibold text-base text-zinc-800 dark:text-zinc-200 bg-gradient-to-br from-zinc-200/80 via-zinc-100/80 to-zinc-300/80 dark:from-zinc-800/80 dark:via-zinc-700/80 dark:to-zinc-900/80 backdrop-blur-md border border-zinc-300/50 dark:border-zinc-600/50 rounded-xl shadow-sm hover:shadow-md hover:brightness-105 hover:text-primary dark:hover:text-primary transition-all duration-300">
        <span class="group-hover:drop-shadow-[0_0_8px_hsl(var(--primary)/0.4)] transition-all">Sign In to Vault</span>
      </a>
    <?php endif; ?>
  </div>
</div>
