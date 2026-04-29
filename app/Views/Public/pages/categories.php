<?php require BASE_PATH . '/app/Views/partials/header.php'; ?>
<main class="min-h-screen bg-background text-foreground flex flex-col">
  <div class="pt-32 pb-28 container">
    <div class="text-center max-w-3xl mx-auto mb-20 reveal-on-scroll reveal-slide-up">
      <div class="relative inline-flex overflow-hidden rounded-full p-[1px] mb-4 shadow-sm">
        <span class="absolute inset-[-1000%] animate-[spin_3s_linear_infinite] bg-[conic-gradient(from_90deg_at_50%_50%,hsl(var(--primary))_0%,transparent_50%,hsl(var(--primary))_100%)] opacity-70"></span>
        <span class="relative inline-flex items-center justify-center rounded-full bg-background px-4 py-1.5 text-xs font-mono uppercase tracking-[0.2em] text-primary">
          ◆ Explore the Ecosystem
        </span>
      </div>
      <h1 class="font-display text-5xl md:text-6xl tracking-tight mb-6">Browse by <span class="text-primary">Category.</span></h1>
      <p class="text-muted-foreground text-lg">Every plugin and theme in our vault is manually verified and categorized by our team of WordPress experts.</p>
    </div>
    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
      <?php foreach ($categories as $i => $category): ?>
        <a href="/marketplace?cat=<?= urlencode((string) ($category['name'] ?? '')) ?>" class="group relative rounded-3xl border border-border bg-card p-8 overflow-hidden hover:shadow-elevated transition-all h-full block reveal-on-scroll reveal-slide-up" style="transition-delay: <?= $i * 50 ?>ms">
          <div class="absolute -top-12 -right-12 h-40 w-40 rounded-full bg-gradient-to-br <?= e((string) ($category['hue'] ?? '')) ?> blur-3xl opacity-40 group-hover:opacity-100 transition-opacity"></div>
          <div class="relative">
            <div class="relative h-14 w-14 rounded-full flex items-center justify-center mb-6 transition-all duration-300 group-hover:scale-110">
              <div class="absolute inset-0 rounded-full bg-gradient-to-br <?= e((string) ($category['hue'] ?? '')) ?> blur-md opacity-60 group-hover:opacity-100 transition-opacity"></div>
              <div class="absolute inset-0 rounded-full border border-white/20"></div>
              <span class="relative z-10 h-7 w-7 text-foreground group-hover:text-primary transition-colors"><?= icon_svg((string) ($category['icon'] ?? 'Layout'), 'h-7 w-7') ?></span>
            </div>
            <h3 class="font-display text-2xl mb-2"><?= e((string) ($category['name'] ?? '')) ?></h3>
            <p class="text-sm text-muted-foreground mb-4 line-clamp-2"><?= e((string) ($category['desc'] ?? '')) ?></p>
            <div class="flex items-center justify-between mt-auto">
              <span class="text-xs font-sans font-bold bg-secondary px-3 py-1 rounded-full text-muted-foreground group-hover:text-primary transition-colors"><?= e((string) ($category['count'] ?? '0')) ?> Items</span>
              <span class="h-8 w-8 rounded-full border border-border flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all hover:bg-primary hover:text-primary-foreground hover:border-primary"><?= icon_svg('ArrowRight') ?></span>
            </div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
    <div class="mt-24 bg-ink text-ink-foreground rounded-[2rem] p-12 relative overflow-hidden reveal-on-scroll reveal-scale">
      <div class="absolute inset-0 grid-bg opacity-10"></div>
      <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-12">
        <div class="max-w-xl text-center md:text-left">
          <h2 class="font-display text-4xl md:text-5xl mb-4">Can't find what you're looking for?</h2>
          <p class="opacity-70">We add over 50 new items every week. If we're missing a specific plugin or theme, let us know and we'll check its compatibility.</p>
        </div>
        <button class="bg-primary text-primary-foreground px-8 py-4 rounded-full font-bold hover:scale-105 transition-transform" type="button">Request an Item</button>
      </div>
    </div>
  </div>
</main>
<?php require BASE_PATH . '/app/Views/partials/footer.php'; ?>
