<?php
$status = (string) (query('status') ?? '');
?>
<div class="space-y-6 reveal-on-scroll">
  <div class="flex items-end justify-between gap-4">
    <div>
      <h2 class="font-display text-2xl font-bold tracking-tight">Security &amp; SEO Settings</h2>
      <p class="text-sm text-muted-foreground mt-0.5">Manage platform access, encryption, and search visibility.</p>
    </div>
    <?php if ($status === 'saved'): ?>
      <div class="text-[10px] font-mono uppercase tracking-widest bg-emerald-500/10 text-emerald-600 px-3 py-1.5 rounded-md border border-emerald-500/30">Setting updated</div>
    <?php elseif ($status === 'sitemap'): ?>
      <div class="text-[10px] font-mono uppercase tracking-widest bg-primary/10 text-primary px-3 py-1.5 rounded-md border border-primary/30">Sitemap rebuilt</div>
    <?php endif; ?>
  </div>

  <div class="grid lg:grid-cols-2 gap-5">
    <!-- Platform security -->
    <div class="bg-card border border-border rounded-lg overflow-hidden shadow-soft">
      <div class="px-6 py-4 border-b border-border bg-gradient-to-br from-emerald-500/10 to-transparent flex items-center gap-3">
        <div class="h-9 w-9 rounded-md bg-emerald-500/15 flex items-center justify-center"><?= icon_svg('Lock', 'h-4 w-4 text-emerald-600') ?></div>
        <div>
          <h4 class="font-bold text-sm uppercase tracking-widest font-mono">Platform security</h4>
          <p class="text-[11px] text-muted-foreground">Access policies &amp; protection</p>
        </div>
      </div>
      <div class="p-2 space-y-1">
        <?php foreach (($securitySettings ?? []) as $sec): $on = !empty($sec['status']); ?>
          <form method="post" action="/admin/security/toggle" class="group relative flex items-center justify-between p-4 hover:bg-secondary/30 rounded-md transition-all">
            <input type="hidden" name="setting_id" value="<?= e((string) ($sec['id'] ?? '')) ?>">
            <input type="hidden" name="enabled" value="<?= $on ? '0' : '1' ?>">
            <div class="flex items-center gap-3">
              <?= icon_svg((string) ($sec['icon'] ?? 'Shield'), 'h-4 w-4 text-muted-foreground') ?>
              <div>
                <span class="text-sm font-bold block"><?= e((string) ($sec['label'] ?? '')) ?></span>
                <span class="text-[10px] text-muted-foreground block"><?= e((string) ($sec['d'] ?? '')) ?></span>
              </div>
            </div>
            <button type="submit" aria-label="Toggle <?= e((string) ($sec['label'] ?? '')) ?>"
                    class="relative h-6 w-11 rounded-full p-1 transition-colors <?= $on ? 'bg-emerald-500' : 'bg-secondary border border-border' ?>">
              <span class="block h-4 w-4 rounded-full bg-white shadow transition-transform <?= $on ? 'translate-x-5' : 'translate-x-0' ?>"></span>
            </button>
          </form>
        <?php endforeach; ?>
      </div>
    </div>

    <!-- SEO -->
    <div class="bg-card border border-border rounded-lg overflow-hidden shadow-soft">
      <div class="px-6 py-4 border-b border-border bg-gradient-to-br from-blue-500/10 to-transparent flex items-center gap-3">
        <div class="h-9 w-9 rounded-md bg-blue-500/15 flex items-center justify-center"><?= icon_svg('Globe', 'h-4 w-4 text-blue-500') ?></div>
        <div>
          <h4 class="font-bold text-sm uppercase tracking-widest font-mono">Search &amp; discovery</h4>
          <p class="text-[11px] text-muted-foreground">SEO optimization rules</p>
        </div>
      </div>
      <div class="p-2 space-y-1">
        <?php foreach (($seoSettings ?? []) as $rule): ?>
          <div class="group flex flex-col p-4 hover:bg-secondary/30 rounded-md transition-all">
            <div class="flex justify-between items-center mb-1">
              <span class="text-sm font-bold"><?= e((string) ($rule['label'] ?? '')) ?></span>
              <?php if (!empty($rule['action'])): ?>
                <form method="post" action="/admin/security/sitemap">
                  <button class="px-3 py-1.5 rounded-md bg-primary/10 text-primary text-[10px] font-bold uppercase tracking-widest hover:bg-primary hover:text-primary-foreground transition-colors" type="submit">
                    <?= e((string) $rule['action']) ?>
                  </button>
                </form>
              <?php else: ?>
                <span class="px-2 py-1 bg-emerald-500/10 text-emerald-600 text-[10px] font-bold uppercase rounded-md font-mono"><?= e((string) ($rule['status'] ?? '')) ?></span>
              <?php endif; ?>
            </div>
            <p class="text-[10px] text-muted-foreground"><?= e((string) ($rule['d'] ?? '')) ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>
