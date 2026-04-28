<?php require BASE_PATH . '/app/Views/partials/header.php'; ?>
<main class="min-h-screen bg-background text-foreground flex flex-col">
  <div class="pt-32 pb-28 container">
    <div class="text-center max-w-3xl mx-auto mb-20 reveal-on-scroll reveal-slide-up">
      <div class="relative inline-flex overflow-hidden rounded-full p-[1px] mb-4 shadow-sm">
        <span class="absolute inset-[-1000%] animate-[spin_3s_linear_infinite] bg-[conic-gradient(from_90deg_at_50%_50%,hsl(var(--primary))_0%,transparent_50%,hsl(var(--primary))_100%)] opacity-70"></span>
        <span class="relative inline-flex items-center justify-center rounded-full bg-background px-4 py-1.5 text-xs font-mono uppercase tracking-[0.2em] text-primary">
          ◆ Transparent Pricing
        </span>
      </div>
      <h1 class="font-display text-5xl md:text-6xl tracking-tight mb-6">Invest in your <span class="text-primary">workflow.</span></h1>
      <p class="text-muted-foreground text-lg">Choose the plan that fits your ambition. Pay once, use everywhere. No hidden fees or recurring subscriptions for individual items.</p>
    </div>
    <div class="grid md:grid-cols-3 gap-8">
      <?php foreach ($plans as $plan): ?>
        <div class="relative rounded-3xl border p-8 flex flex-col <?= !empty($plan['popular']) ? 'border-primary bg-primary/5 shadow-elevated scale-105 z-10' : 'border-border bg-card shadow-soft' ?> reveal-on-scroll reveal-slide-up" style="transition-delay: <?= $i * 100 ?>ms">
          <?php if (!empty($plan['popular'])): ?>
            <div class="absolute -top-4 left-1/2 -translate-x-1/2 bg-primary text-primary-foreground text-[10px] uppercase tracking-widest font-bold px-4 py-1.5 rounded-full">Most Popular</div>
          <?php endif; ?>
          <div class="h-12 w-12 rounded-2xl flex items-center justify-center mb-6 <?= !empty($plan['popular']) ? 'bg-primary text-primary-foreground' : 'bg-secondary text-foreground' ?>">
            <?= icon_svg((string) ($plan['icon'] ?? 'Zap'), 'h-6 w-6') ?>
          </div>
          <h3 class="font-display text-3xl mb-2"><?= e((string) ($plan['name'] ?? '')) ?></h3>
          <div class="flex items-baseline gap-1 mb-4">
            <span class="text-4xl font-display">$</span>
            <span class="text-6xl font-display"><?= e((string) ($plan['price'] ?? '0')) ?></span>
            <span class="text-muted-foreground font-sans">/year</span>
          </div>
          <p class="text-sm text-muted-foreground mb-8"><?= e((string) ($plan['desc'] ?? '')) ?></p>
          <div class="space-y-4 mb-10 flex-1">
            <?php foreach (($plan['features'] ?? []) as $feature): ?>
              <div class="flex items-start gap-3 text-sm">
                <div class="mt-0.5 h-4 w-4 rounded-full flex items-center justify-center flex-shrink-0 <?= !empty($plan['popular']) ? 'bg-primary/20 text-primary' : 'bg-success/20 text-success' ?>">
                  <?= icon_svg('Check', 'h-3 w-3') ?>
                </div>
                <span><?= e((string) $feature) ?></span>
              </div>
            <?php endforeach; ?>
          </div>
          <button class="w-full py-4 rounded-full font-medium transition-all <?= !empty($plan['popular']) ? 'bg-primary text-primary-foreground shadow-glow hover:brightness-110' : 'bg-ink text-ink-foreground hover:bg-black' ?>" type="button"><?= e((string) ($plan['button'] ?? 'Choose Plan')) ?></button>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="mt-20 p-8 rounded-3xl border border-border bg-card/50 backdrop-blur-sm flex flex-col md:flex-row items-center justify-between gap-8 reveal-on-scroll reveal-scale">
      <div>
        <h3 class="font-display text-2xl mb-1">Custom enterprise solutions?</h3>
        <p class="text-muted-foreground text-sm">We offer special licensing for hosting providers and large multi-national agencies.</p>
      </div>
      <button class="px-8 py-3 rounded-full border border-border font-medium hover:bg-secondary transition-colors" type="button">Contact Support</button>
    </div>
  </div>
</main>
<?php require BASE_PATH . '/app/Views/partials/footer.php'; ?>
