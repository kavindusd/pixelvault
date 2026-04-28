<?php
$categories = $categoriesData['categories'] ?? [];
$platformStats = $adminStatsData['platformStats'] ?? [];
$adminStats = $adminStatsData['adminStats'] ?? [];
$systemActivity = $activityData['systemActivity'] ?? [];
$updateQueue = $activityData['updateQueue'] ?? [];
$emailTemplates = $emailTemplatesData['emailTemplates'] ?? [];
$templateVariables = $emailTemplatesData['templateVariables'] ?? [];
$securitySettings = $securityData['securitySettings'] ?? [];
$seoSettings = $securityData['seoSettings'] ?? [];
$navItems = [
    'dashboard' => ['label' => 'Dashboard', 'icon' => 'LayoutDashboard'],
    'updates' => ['label' => 'Product Manager', 'icon' => 'Package'],
    'orders' => ['label' => 'Recent Orders', 'icon' => 'Activity'],
    'users' => ['label' => 'Manual Controls', 'icon' => 'User'],
    'email' => ['label' => 'Email Editor', 'icon' => 'Mail'],
    'categories_admin' => ['label' => 'Categories', 'icon' => 'Tag'],
    'site_settings' => ['label' => 'Site Configuration', 'icon' => 'Settings'],
    'inbox' => ['label' => 'Inquiries', 'icon' => 'Mail'],
    'security' => ['label' => 'Security & SEO', 'icon' => 'Shield'],
    'accounts' => ['label' => 'Admin Accounts', 'icon' => 'ShieldAlert'],
];
?>
<main class="min-h-screen flex bg-[hsl(var(--background))] relative overflow-hidden">
  <!-- ambient mesh, sits behind everything -->
  <div class="pointer-events-none absolute inset-0" aria-hidden>
    <div class="absolute -top-32 -left-32 w-[480px] h-[480px] rounded-full bg-primary/10 blur-[120px]"></div>
    <div class="absolute top-1/3 right-0 w-[420px] h-[420px] rounded-full bg-amber-300/10 blur-[120px]"></div>
  </div>

  <aside class="relative z-10 w-[260px] flex-col hidden lg:flex p-3">
    <div class="sticky top-3 flex flex-col h-[calc(100vh-1.5rem)]
                rounded-xl border border-border/70
                bg-card/70 backdrop-blur-xl
                shadow-[0_18px_48px_-24px_hsl(24_14%_9%_/_0.25),inset_0_1px_0_hsl(0_0%_100%_/_0.6)]
                overflow-hidden">
      <div class="p-5 flex items-center gap-3 border-b border-border/60">
        <?php $siteLogo = site_config('site_logo'); ?>
        <?php if ($siteLogo): ?>
          <img src="<?= e($siteLogo) ?>" alt="Admin" class="h-9 w-9 rounded-md object-contain">
        <?php else: ?>
          <div class="h-9 w-9 rounded-md bg-primary flex items-center justify-center shadow-glow">
            <?= icon_svg('Zap', 'h-4 w-4 text-white') ?>
          </div>
        <?php endif; ?>
        <div>
          <div class="text-sm font-bold tracking-tight font-display"><?= e(site_config('site_name', 'PixelVault')) ?></div>
          <div class="text-[10px] text-muted-foreground font-mono uppercase tracking-[0.2em]">Admin</div>
        </div>
      </div>
      <nav class="flex-1 p-3 space-y-0.5 overflow-y-auto">
        <div class="text-[9px] font-mono font-bold uppercase tracking-[0.2em] text-muted-foreground px-3 pt-2 pb-2">Navigation</div>
        <?php foreach ($navItems as $id => $item): $isActive = $activeTab === $id; ?>
          <a href="/admin?tab=<?= e($id) ?>"
             class="relative w-full flex items-center gap-3 px-3 py-2.5 rounded-md text-[13px] font-medium transition-all duration-200
                    <?= $isActive
                       ? 'bg-gradient-to-r from-primary/15 via-primary/8 to-transparent text-primary'
                       : 'text-muted-foreground hover:bg-secondary/60 hover:text-foreground' ?>">
            <?php if ($isActive): ?>
              <span class="absolute left-0 top-1.5 bottom-1.5 w-[3px] rounded-r bg-primary"></span>
            <?php endif; ?>
            <?= icon_svg($item['icon'], 'h-4 w-4 ' . ($isActive ? 'text-primary' : '')) ?>
            <span class="flex-1 text-left"><?= e($item['label']) ?></span>
            <?php if ($isActive): ?><?= icon_svg('ChevronRight', 'h-3 w-3 text-primary') ?><?php endif; ?>
          </a>
        <?php endforeach; ?>
      </nav>
      <div class="p-3 border-t border-border/60 space-y-1 bg-secondary/20">
        <a href="/" target="_blank" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-md text-[13px] font-medium text-muted-foreground hover:bg-secondary hover:text-foreground transition-all">
          <?= icon_svg('ExternalLink', 'h-4 w-4') ?><span class="flex-1 text-left">Live Store</span>
        </a>
        <form method="post" action="/auth/logout">
          <button class="w-full flex items-center gap-3 px-3 py-2.5 rounded-md text-[13px] font-medium text-muted-foreground hover:bg-destructive/10 hover:text-destructive transition-all" type="submit">
            <?= icon_svg('LogOut', 'h-4 w-4') ?><span class="flex-1 text-left">Sign Out</span>
          </button>
        </form>
      </div>
    </div>
  </aside>

  <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
    <header class="sticky top-0 z-30 bg-card/80 backdrop-blur-md border-b border-border">
      <div class="flex items-center gap-4 px-6 h-16">
        <div class="flex items-center gap-2 text-sm"><span class="text-muted-foreground font-medium">Admin</span><?= icon_svg('ChevronRight', 'h-3 w-3 text-muted-foreground') ?><span class="font-bold text-foreground"><?= e($navItems[$activeTab]['label'] ?? 'Dashboard') ?></span></div>
        <div class="flex-1"></div>
        <div class="hidden md:flex items-center gap-2 bg-secondary/60 border border-border rounded-xl px-3 py-2 w-48 text-sm text-muted-foreground"><span><?= icon_svg('Search', 'h-3.5 w-3.5') ?></span><span class="text-xs">Quick search…</span></div>
        <button type="button" data-theme-toggle aria-label="Toggle theme" class="h-9 w-9 shrink-0 flex items-center justify-center rounded-xl bg-secondary/80 border border-border/60 text-muted-foreground hover:bg-secondary hover:text-primary transition-all">
          <span data-theme-icon-on class="flex"><?= icon_svg('Sun', 'h-4 w-4') ?></span>
          <span data-theme-icon-off class="hidden"><?= icon_svg('Moon', 'h-4 w-4') ?></span>
        </button>
      </div>
    </header>

    <div class="flex-1 overflow-y-auto">
      <div class="p-6 lg:p-8 max-w-[1400px] mx-auto space-y-6 reveal-on-scroll reveal-slide-up">
        <?php
          $sectionPath = match ($activeTab) {
              'dashboard' => BASE_PATH . '/app/Views/Admin/sections/dashboard.php',
              'updates' => BASE_PATH . '/app/Views/Admin/sections/updates.php',
              'orders' => BASE_PATH . '/app/Views/Admin/sections/orders.php',
              'users' => BASE_PATH . '/app/Views/Admin/sections/users.php',
              'email' => BASE_PATH . '/app/Views/Admin/sections/email.php',
              'categories_admin' => BASE_PATH . '/app/Views/Admin/sections/categories.php',
              'site_settings' => BASE_PATH . '/app/Views/Admin/sections/site_settings.php',
              'accounts' => BASE_PATH . '/app/Views/Admin/sections/accounts.php',
              'inbox' => BASE_PATH . '/app/Views/Admin/sections/inbox.php',
              default => BASE_PATH . '/app/Views/Admin/sections/security.php',
          };
          require $sectionPath;
        ?>
      </div>
    </div>
  </div>
</main>
