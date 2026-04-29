<?php require BASE_PATH . '/app/Views/partials/header.php'; ?>
<main class="min-h-screen bg-background text-foreground flex flex-col">
  <div class="pt-32 pb-28 container">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-16 reveal-on-scroll reveal-slide-up">
      <div>
        <h1 class="font-display text-5xl md:text-6xl tracking-tight mb-4">Version <span class="text-primary">History</span></h1>
        <p class="text-muted-foreground max-w-md">Real-time update stream for the entire Vault. Your products are always fresh, always secure.</p>
      </div>
      <div class="flex gap-3">
        <div class="bg-success/10 text-success border border-success/20 px-4 py-2 rounded-full text-xs font-medium flex items-center gap-2">
          <?= icon_svg('RefreshCw', 'h-3 w-3') ?>
          Checking Cloudflare R2 mirror...
        </div>
      </div>
    </div>
    <div class="bg-card border border-border rounded-3xl overflow-hidden shadow-soft reveal-on-scroll reveal-slide-up" style="transition-delay: 200ms">
      <div class="p-6 border-b border-border bg-secondary/30 flex items-center justify-between">
        <h2 class="font-display text-2xl flex items-center gap-3"><?= icon_svg('RefreshCw', 'h-6 w-6 text-primary') ?>Latest available updates</h2>
        <div class="relative hidden sm:block">
          <span class="absolute left-3 top-1/2 -translate-y-1/2 h-3.5 w-3.5 text-muted-foreground"><?= icon_svg('Search', 'h-3.5 w-3.5') ?></span>
          <input type="text" placeholder="Find a product..." class="bg-background border border-border rounded-full py-1.5 pl-9 pr-4 text-xs focus:ring-1 focus:ring-primary focus:outline-none transition-all">
        </div>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="bg-secondary/10 uppercase text-[10px] font-bold tracking-widest text-muted-foreground border-b border-border">
              <th class="px-6 py-4">Status</th>
              <th class="px-6 py-4">Product Name</th>
              <th class="px-6 py-4">Category</th>
              <th class="px-6 py-4">Version</th>
              <th class="px-6 py-4">Released</th>
              <th class="px-6 py-4 text-right">Action</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-border/50">
            <?php foreach ($updates as $update): ?>
              <tr class="hover:bg-primary/[0.02] transition-colors group cursor-pointer" onclick="window.location.href='/product/<?= e((string) ($update['id'] ?? '')) ?>'">
                <td class="px-6 py-4">
                  <span class="px-2 py-0.5 rounded text-[10px] font-bold <?= ($update['status'] ?? '') === 'New' ? 'bg-primary text-primary-foreground' : (($update['status'] ?? '') === 'Security' ? 'bg-destructive text-destructive-foreground' : 'bg-secondary text-muted-foreground') ?>">
                    <?= e((string) ($update['status'] ?? '')) ?>
                  </span>
                </td>
                <td class="px-6 py-4"><div class="font-display text-lg group-hover:text-primary transition-colors hover:underline underline-offset-4"><?= e((string) ($update['name'] ?? '')) ?></div></td>
                <td class="px-6 py-4"><span class="text-xs text-muted-foreground"><?= e((string) ($update['cat'] ?? '')) ?></span></td>
                <td class="px-6 py-4"><span class="font-mono text-xs">v<?= e((string) ($update['ver'] ?? '')) ?></span></td>
                <td class="px-6 py-4 text-xs text-muted-foreground"><?= e((string) ($update['date'] ?? '')) ?> · <?= e((string) ($update['size'] ?? '')) ?></td>
                <td class="px-6 py-4 text-right">
                  <a href="/product/<?= e((string) ($update['id'] ?? '')) ?>" class="h-9 w-9 rounded-full bg-secondary group-hover:bg-ink group-hover:text-ink-foreground inline-flex items-center justify-center transition-all"><?= icon_svg('ArrowRight') ?></a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</main>
<?php require BASE_PATH . '/app/Views/partials/footer.php'; ?>
