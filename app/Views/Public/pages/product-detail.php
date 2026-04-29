<?php require BASE_PATH . '/app/Views/partials/header.php'; ?>
<main class="min-h-screen bg-background text-foreground flex flex-col">
  <div class="pt-32 pb-20 container">
    <a href="/marketplace" class="inline-flex items-center gap-2 text-sm text-muted-foreground hover:text-primary transition-colors mb-8">
      <?= icon_svg('ArrowRight', 'h-4 w-4 rotate-180') ?> Back to Marketplace
    </a>

    <?php if (query('status') === 'error'): ?>
      <div class="mb-8 p-4 rounded-2xl bg-red-500/10 border border-red-500/20 text-red-500 flex items-center gap-3 animate-shake">
        <?= icon_svg('AlertCircle', 'h-5 w-5') ?>
        <p class="text-sm font-medium"><?= e(query('msg') ?? 'An error occurred.') ?></p>
      </div>
    <?php endif; ?>

    <?php if (query('status') === 'success'): ?>
      <div class="mb-8 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 flex items-center gap-3 animate-scale-in">
        <?= icon_svg('CheckCircle', 'h-5 w-5') ?>
        <p class="text-sm font-medium"><?= e(query('msg') ?? 'Success!') ?></p>
      </div>
    <?php endif; ?>
    <div class="grid lg:grid-cols-2 gap-12 lg:gap-20">
      <div class="space-y-8">
        <div class="aspect-video rounded-[2.5rem] bg-gradient-to-br <?= e((string) ($product['tone'] ?? '')) ?> border border-border flex items-center justify-center relative overflow-hidden shadow-elevated">
          <div class="absolute inset-0 opacity-10 bg-[radial-gradient(circle_at_center,var(--primary),transparent)]"></div>
          <?php if (!empty($product['image'])): ?>
            <img src="<?= e((string) $product['image']) ?>" alt="<?= e((string) ($product['name'] ?? '')) ?>" class="absolute inset-0 w-full h-full object-cover">
          <?php else: ?>
            <span class="font-display text-[120px] text-foreground/20"><?= e((string) ($product['letter'] ?? 'P')) ?></span>
          <?php endif; ?>
        </div>
        <div class="flex flex-col sm:flex-row gap-4">
          <?php if (!$access): ?>
            <form method="post" action="/cart/add" class="flex-1">
              <input type="hidden" name="product_id" value="<?= e((string) ($product['id'] ?? '')) ?>">
              <input type="hidden" name="return_to" value="/product/<?= e((string) ($product['id'] ?? '')) ?>">
              <button type="submit" class="w-full bg-ink text-ink-foreground py-5 rounded-2xl font-bold text-lg shadow-ink flex items-center justify-center gap-3 hover:brightness-110 transition-all">
                Purchase License - $<?= e((string) ($product['price'] ?? '')) ?>
              </button>
            </form>
          <?php elseif ($canDownload): ?>
            <a href="/download/<?= e((string) ($product['id'] ?? '')) ?>" class="flex-1 bg-primary text-primary-foreground py-5 rounded-2xl font-bold text-lg shadow-glow flex items-center justify-center gap-3 hover:brightness-110 active:scale-[0.98] transition-all">
              <?= icon_svg('ArrowDownToLine', 'h-5 w-5') ?> 
              <?= $hasNewUpdate ? 'Download Latest Update' : 'Download Product Files' ?>
            </a>
          <?php elseif ($limitReached): ?>
            <div class="flex-1 space-y-4">
              <form method="post" action="/cart/add">
                <input type="hidden" name="product_id" value="<?= e((string) ($product['id'] ?? '')) ?>">
                <input type="hidden" name="return_to" value="/product/<?= e((string) ($product['id'] ?? '')) ?>">
                <button type="submit" class="w-full bg-ink text-ink-foreground py-5 rounded-2xl font-bold text-lg shadow-ink flex items-center justify-center gap-3 hover:brightness-110 transition-all">
                  <?= icon_svg('RefreshCw', 'h-5 w-5') ?> Buy Again to Reset Limit - $<?= e((string) ($product['price'] ?? '')) ?>
                </button>
              </form>
              <div class="flex items-center gap-2 px-4 text-[10px] font-bold uppercase tracking-widest text-muted-foreground/60">
                <?= icon_svg('Info', 'h-3.5 w-3.5') ?>
                <span>You can also request a manual extension via your <a href="/profile?tab=vault" class="text-primary hover:underline">Vault</a></span>
              </div>
            </div>
          <?php endif; ?>
          <a href="<?= e((string) ($product['demo_url'] ?? '#')) ?>" target="_blank" class="px-8 py-5 rounded-2xl border border-border bg-card font-bold hover:bg-secondary transition-all flex items-center justify-center gap-2"><?= icon_svg('ArrowUpRight', 'h-4 w-4') ?> View Live Demo</a>
        </div>

        <div class="p-6 rounded-3xl border border-border bg-secondary/20 space-y-4">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
              <div class="h-2 w-2 rounded-full bg-success animate-pulse"></div>
              <span class="text-sm font-bold uppercase tracking-widest text-muted-foreground">Version <?= e((string) ($product['ver'] ?? '')) ?></span>
            </div>
            <?php if (($product['latestVer'] ?? '') !== ($product['ver'] ?? '')): ?>
              <span class="text-[10px] font-bold bg-primary/10 text-primary px-2 py-1 rounded">New Update Available: <?= e((string) ($product['latestVer'] ?? '')) ?></span>
            <?php endif; ?>
          </div>
          
          <?php if ($access): ?>
            <p class="text-[10px] text-center text-muted-foreground font-medium uppercase tracking-widest mt-4">
              <?php 
                $rem = max(0, $maxAllowed - $productUpdateCount);
              ?>
              Quota: <?= e((string) $rem) ?> / <?= e((string) $maxAllowed) ?> updates remaining
            </p>
          <?php endif; ?>
        </div>
      </div>
      <div class="space-y-10">
        <div>
          <div class="relative inline-flex overflow-hidden rounded-full p-[1px] mb-4 shadow-sm">
            <span class="absolute inset-[-1000%] animate-[spin_3s_linear_infinite] bg-[conic-gradient(from_90deg_at_50%_50%,hsl(var(--primary))_0%,transparent_50%,hsl(var(--primary))_100%)] opacity-70"></span>
            <span class="relative inline-flex items-center justify-center rounded-full bg-background px-4 py-1.5 text-xs font-mono uppercase tracking-[0.2em] text-primary">
              <?= icon_svg('ShieldCheck', 'h-3.5 w-3.5 mr-2') ?>
              GPL Verified Product
            </span>
          </div>
          <h1 class="font-display text-5xl md:text-6xl tracking-tight mb-6"><?= e((string) ($product['name'] ?? '')) ?></h1>
          <p class="text-lg text-muted-foreground leading-relaxed"><?= e((string) ($product['desc'] ?? '')) ?></p>
        </div>
        <div class="grid grid-cols-2 gap-6 pb-10 border-b border-border">
          <div>
            <h4 class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 flex items-center gap-2"><?= icon_svg('Cpu', 'h-3 w-3') ?> Technical Info</h4>
            <div class="font-display text-2xl"><?= e((string) ($product['technical_info'] ?? 'PHP 8.1+ / WP 6.0+')) ?></div>
          </div>
          <div>
            <h4 class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-2 flex items-center gap-2">Last Updated</h4>
            <div class="font-display text-2xl"><?= e((string) ($product['lastUpdated'] ?? '')) ?></div>
          </div>
        </div>
        <div class="space-y-6">
          <h3 class="font-display text-3xl">Key Features</h3>
          <ul class="grid sm:grid-cols-2 gap-4">
            <?php foreach (($product['features'] ?? []) as $feature): ?>
              <li class="flex items-start gap-3">
                <div class="h-5 w-5 rounded-full bg-primary/10 flex items-center justify-center mt-0.5">+</div>
                <span class="text-sm font-medium"><?= e((string) $feature) ?></span>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
        <div class="p-8 rounded-[2rem] border border-border bg-card">
          <h3 class="font-display text-2xl mb-4">License Information</h3>
          <p class="text-sm text-muted-foreground leading-relaxed mb-6">This product is distributed under the <span class="font-bold text-foreground">GPL (General Public License)</span>. You are free to use it on unlimited websites for your personal or client projects.</p>
          <div class="flex items-center gap-2 text-xs font-bold text-success uppercase tracking-widest"><?= icon_svg('ShieldCheck', 'h-4 w-4') ?> Original Product Files · Virus Scanned</div>
        </div>

        <?php if (!empty($versions)): ?>
        <div class="space-y-6 pt-10">
          <div class="flex items-center justify-between">
            <h3 class="font-display text-3xl">Version History</h3>
            <span class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground"><?= count($versions) ?> Release<?= count($versions) > 1 ? 's' : '' ?></span>
          </div>
          <div class="divide-y divide-border/50 border-y border-border/50">
            <?php foreach ($versions as $v): 
              $isLatest = (string)($v['version'] ?? '') === (string)($product['latestVer'] ?? '');
              $canDownloadVersion = ($access !== null) && !$limitReached;
            ?>
              <div class="py-6 flex flex-col sm:flex-row gap-6">
                <div class="flex-shrink-0 w-24">
                  <div class="font-mono text-sm font-bold flex items-center gap-2">
                    v<?= e((string)($v['version'] ?? '')) ?>
                    <?php if ($isLatest): ?>
                      <span class="h-1.5 w-1.5 rounded-full bg-primary shadow-[0_0_8px_hsl(var(--primary))]"></span>
                    <?php endif; ?>
                  </div>
                  <div class="text-[10px] text-muted-foreground uppercase tracking-widest mt-1"><?= date('M d, Y', strtotime((string)($v['created_at'] ?? 'now'))) ?></div>
                </div>
                <div class="flex-1 min-w-0">
                  <div class="text-sm text-muted-foreground leading-relaxed prose prose-sm dark:prose-invert max-w-none">
                    <?= nl2br(e((string)($v['changelog'] ?? 'Initial release'))) ?>
                  </div>
                </div>
                <div class="flex-shrink-0">
                  <?php 
                    $vStr = (string)($v['version'] ?? '');
                    $unlocked = array_filter(explode(',', $access['downloaded_versions'] ?? ''));
                    $isOwned = ($access !== null) && (
                        $vStr === $access['purchased_version'] || 
                        in_array($vStr, $unlocked, true)
                    );
                    // They can download if they own it OR if they haven't reached the limit yet
                    $canGetThis = $isOwned || ($access !== null && !$limitReached);
                  ?>
                  <?php if ($canGetThis): ?>
                    <a href="/download/<?= e((string)($product['id'] ?? '')) ?>/<?= e($vStr) ?>" class="h-10 px-4 rounded-xl bg-secondary hover:bg-ink hover:text-ink-foreground text-xs font-bold flex items-center gap-2 transition-all">
                      <?= icon_svg('ArrowDownToLine', 'h-3.5 w-3.5') ?> Download
                    </a>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</main>
<?php require BASE_PATH . '/app/Views/partials/footer.php'; ?>
