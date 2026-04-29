<footer class="relative border-t border-border bg-[hsl(var(--primary)/0.02)] dark:bg-card overflow-hidden transition-colors">
  <!-- Light mode gradient -->
  <div class="absolute inset-0 block dark:hidden pointer-events-none overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-[hsl(var(--primary)/0.05)] to-transparent"></div>
    <div class="absolute -right-[10%] -bottom-[30%] w-[60%] h-[120%] bg-gradient-to-tl from-primary/10 via-primary/5 to-transparent rounded-[100%] blur-[80px] origin-bottom-right -rotate-12 opacity-50"></div>
    <div class="absolute -right-[5%] top-[10%] w-[40%] h-[80%] bg-gradient-to-l from-primary/5 to-transparent rounded-full blur-[60px] opacity-40"></div>
    <div class="absolute left-[10%] top-0 w-[50%] h-[50%] bg-gradient-to-b from-primary/5 to-transparent rounded-full blur-[60px] opacity-30"></div>
  </div>
  <!-- Dark mode gradient -->
  <div class="absolute inset-0 hidden dark:block pointer-events-none overflow-hidden">
    <div class="absolute -right-[10%] -bottom-[30%] w-[60%] h-[120%] bg-gradient-to-tl from-primary/20 via-primary/10 to-transparent rounded-[100%] blur-[80px] origin-bottom-right -rotate-12 opacity-80"></div>
    <div class="absolute -right-[5%] top-[10%] w-[40%] h-[80%] bg-gradient-to-l from-primary/10 to-transparent rounded-full blur-[60px] opacity-70"></div>
    <div class="absolute left-[10%] top-0 w-[50%] h-[50%] bg-gradient-to-b from-amber-500/10 to-transparent rounded-full blur-[60px] opacity-50"></div>
  </div>
  
  <!-- Big Background Text -->
  <div class="absolute inset-x-0 bottom-0 pointer-events-none z-0 overflow-hidden flex justify-center -mb-8 lg:-mb-16">
    <span class="font-display text-[15vw] leading-none font-black text-primary/15 dark:text-primary/10 tracking-tighter whitespace-nowrap select-none">PIXELVAULT</span>
  </div>

  <div class="container relative z-10 py-20">
    <div class="grid lg:grid-cols-12 gap-12">
      <div class="lg:col-span-4">
        <a href="/" class="flex items-center gap-2">
          <?= site_logo_html('h-8 w-auto object-contain', 'h-8 w-8 rounded-lg bg-primary flex items-center justify-center text-primary-foreground shadow-glow shrink-0') ?>
          <span class="font-display text-2xl tracking-tight"><?= e(site_config('site_name', 'PixelVault')) ?></span>
        </a>
        <p class="mt-4 text-sm text-muted-foreground max-w-xs leading-relaxed">
          <?= e(site_config('site_tagline')) ?>
        </p>
        <form class="mt-8 flex max-w-sm">
          <input type="email" placeholder="you@studio.com" class="flex-1 rounded-l-full border border-border bg-card px-5 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
          <button class="rounded-r-full bg-ink text-ink-foreground px-5 text-sm font-medium" type="button">Subscribe</button>
        </form>
      </div>
      <div class="lg:col-span-8 grid grid-cols-2 md:grid-cols-4 gap-8">
        <?php
        $footerColumns = [
            ['title' => 'Marketplace', 'links' => [['Plugins', '/marketplace'], ['Themes', '/marketplace'], ['Bundles', '/marketplace'], ['Free downloads', '#'], ['New releases', '/updates']]],
            ['title' => 'Account', 'links' => [['My orders', '/profile'], ['Downloads', '/profile'], ['Update center', '/updates'], ['Profile', '/profile'], ['Support', '#']]],
            ['title' => 'Company', 'links' => [['About', '#'], ['Blog', '#'], ['Affiliates', '#'], ['Press kit', '#'], ['Contact', '#contact']]],
            ['title' => 'Legal', 'links' => [['Terms of use', '#'], ['Privacy policy', '#'], ['GPL license', '#'], ['Refunds', '#'], ['Admin Access', '/admin']]],
        ];
        foreach ($footerColumns as $column):
        ?>
          <div>
            <h4 class="font-mono text-xs uppercase tracking-widest text-muted-foreground mb-4"><?= e($column['title']) ?></h4>
            <ul class="space-y-3">
              <?php foreach ($column['links'] as [$label, $href]): ?>
                <li>
                  <a href="<?= e($href) ?>" class="text-sm text-muted-foreground hover:text-foreground transition-colors">
                    <?= e($label) ?>
                  </a>
                </li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="mt-16 flex flex-wrap items-center justify-between gap-4">
      <p class="text-xs text-muted-foreground font-mono"><?= e(site_config('footer_copyright')) ?></p>
      <p class="text-xs text-muted-foreground font-mono"><?= e(site_config('footer_credits')) ?></p>
    </div>
  </div>
</footer>