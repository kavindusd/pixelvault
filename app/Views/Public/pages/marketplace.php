<?php require BASE_PATH . '/app/Views/partials/header.php'; ?>
<main class="min-h-screen bg-background text-foreground flex flex-col">
  <div class="pt-32 pb-20 container">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-8 mb-12">
      <div class="reveal-on-scroll reveal-slide-up">
        <h1 class="font-display text-5xl md:text-6xl tracking-tight mb-4"><?= site_config('marketplace_title') ?></h1>
        <p class="text-muted-foreground max-w-md"><?= e(site_config('marketplace_subtitle')) ?></p>
      </div>
      <form class="flex items-center gap-3 reveal-on-scroll reveal-slide-up relative z-30" style="transition-delay: 150ms" method="get" action="/marketplace">
        <?php if ($activeCategory !== 'All'): ?><input type="hidden" name="cat" value="<?= e((string) $activeCategory) ?>"><?php endif; ?>
        
        <div class="relative group w-full md:w-80">
          <span class="absolute left-4 top-1/2 -translate-y-1/2 h-4 w-4 text-muted-foreground group-focus-within:text-primary transition-colors"><?= icon_svg('Search') ?></span>
          <input type="text" name="search" placeholder="Search the Vault..." class="w-full bg-card border border-border rounded-2xl py-3 pl-11 pr-4 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all shadow-soft text-sm" value="<?= e((string) $search) ?>">
        </div>

        <div class="flex items-center gap-2">
          <button type="submit" class="h-11 w-11 rounded-2xl bg-primary text-primary-foreground flex items-center justify-center shadow-glow hover:brightness-110 transition-all" title="Search">
            <?= icon_svg('Search', 'h-5 w-5') ?>
          </button>

          <div class="pv-dropdown">
            <div class="pv-dropdown-trigger h-11 px-4 rounded-2xl bg-secondary border border-border text-foreground hover:bg-secondary/80 transition-all">
              <?= icon_svg('Filter', 'h-4 w-4') ?>
              <span class="text-[10px] font-mono font-bold uppercase tracking-widest hidden sm:block"><?= e($activeCategory) ?></span>
              <?= icon_svg('ChevronDown', 'h-3 w-3 opacity-50') ?>
            </div>
            <div class="pv-dropdown-content">
              <?php foreach ($categories as $category): 
                $name = (string) ($category['name'] ?? ''); 
                $isActive = strcasecmp((string) $activeCategory, $name) === 0;
              ?>
                <a href="/marketplace<?= $name !== 'All' ? '?cat=' . urlencode($name) . ($search ? '&search='.urlencode($search) : '') : ($search ? '?search='.urlencode($search) : '') ?>" 
                   class="pv-dropdown-item <?= $isActive ? 'active' : '' ?>">
                  <?= e($name) ?>
                </a>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </form>
    </div>
    <?php if ($products !== []): ?>
      <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <?php foreach ($products as $i => $product): ?>
          <article class="reveal-on-scroll reveal-slide-up group relative rounded-lg bg-card border border-border overflow-hidden cursor-pointer hover:border-primary/40 hover:-translate-y-1 hover:shadow-elevated transition-all duration-300" style="transition-delay: <?= ($i % 4) * 80 ?>ms;">
            <a href="/product/<?= e((string) ($product['id'] ?? '')) ?>" class="relative block aspect-[4/3] bg-gradient-to-br <?= e((string) ($product['tone'] ?? '')) ?> border-b border-border overflow-hidden flex items-center justify-center">
              <div class="absolute inset-0 grid-bg opacity-40"></div>
              <?php if (!empty($product['image'])): ?>
                <img src="<?= e((string) $product['image']) ?>" alt="<?= e((string) ($product['name'] ?? '')) ?>" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
              <?php else: ?>
                <span class="font-display text-8xl text-foreground/70"><?= e((string) ($product['letter'] ?? 'P')) ?></span>
              <?php endif; ?>
              <div class="absolute top-3 left-3 flex gap-1.5">
                <span class="text-[10px] font-mono uppercase tracking-widest bg-ink text-ink-foreground px-2 py-1 rounded-md">GPL</span>
                <span class="text-[10px] font-mono uppercase tracking-widest bg-card/90 backdrop-blur border border-border px-2 py-1 rounded-md">v<?= e((string) ($product['ver'] ?? '')) ?></span>
              </div>
            </a>
            <div class="p-5">
              <div class="flex items-center justify-between text-[10px] font-mono uppercase tracking-wider text-muted-foreground">
                <span><?= e((string) ($product['cat'] ?? '')) ?></span>
                <span class="flex items-center gap-1"><?= icon_svg('Star', 'h-3 w-3 fill-primary text-primary') ?> 4.9</span>
              </div>
              <a href="/product/<?= e((string) ($product['id'] ?? '')) ?>"><h3 class="mt-2 font-display text-xl leading-tight hover:text-primary transition-colors"><?= e((string) ($product['name'] ?? '')) ?></h3></a>
              <div class="mt-4 flex items-end justify-between">
                <div class="flex items-baseline gap-2"><span class="font-display text-2xl">$<?= e((string) ($product['price'] ?? '')) ?></span><span class="text-xs text-muted-foreground line-through">$<?= e((string) ($product['og'] ?? '')) ?></span></div>
                <form method="post" action="/cart/add">
                  <input type="hidden" name="product_id" value="<?= e((string) ($product['id'] ?? '')) ?>">
                  <input type="hidden" name="return_to" value="/marketplace">
                  <button type="submit" class="rounded-md bg-secondary text-secondary-foreground hover:bg-ink hover:text-ink-foreground h-9 px-4 text-[11px] font-mono font-bold uppercase tracking-widest transition-colors">Add</button>
                </form>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php else: ?><div class="py-20 text-center"><h3 class="text-xl font-display">No products found</h3></div><?php endif; ?>
  </div>
</main>
<?php require BASE_PATH . '/app/Views/partials/footer.php'; ?>
