<?php
require BASE_PATH . '/app/Views/partials/header.php';
$firstName = explode(' ', (string) ($user['name'] ?? 'Member'))[0];
$tabs = [
    'overview' => ['label' => 'Overview', 'icon' => 'BarChart3'],
    'vault'    => ['label' => 'My Vault', 'icon' => 'Package'],
    'payments' => ['label' => 'Payments', 'icon' => 'CreditCard'],
    'settings' => ['label' => 'Settings', 'icon' => 'Settings'],
    'security' => ['label' => 'Security', 'icon' => 'ShieldCheck'],
];
?>
<main class="min-h-screen bg-background text-foreground relative flex flex-col pt-20 lg:pt-0">
  <!-- Ambient Background Design -->
  <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
    <!-- Mesh Gradients -->
    <div class="absolute -top-1/4 -right-1/4 w-[1000px] h-[1000px] bg-primary/10 blur-[160px] rounded-full animate-pulse opacity-60" style="animation-duration: 8s"></div>
    <div class="absolute top-1/2 -left-1/4 w-[800px] h-[800px] bg-blue-500/5 blur-[140px] rounded-full animate-pulse opacity-40" style="animation-duration: 12s"></div>
    <div class="absolute -bottom-1/4 right-1/4 w-[600px] h-[600px] bg-purple-500/5 blur-[120px] rounded-full animate-pulse opacity-30" style="animation-duration: 10s"></div>
    
    <!-- Grid & Texture -->
    <div class="absolute inset-0 grid-bg opacity-[0.4] dark:opacity-[0.1]"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-background via-transparent to-background/80"></div>
    
    <!-- Noise Overlay -->
    <div class="absolute inset-0 opacity-[0.03] dark:opacity-[0.05] pointer-events-none mix-blend-overlay" style="background-image: url('https://grainy-gradients.vercel.app/noise.svg')"></div>
  </div>

  <!-- Mobile Top Bar (only visible on small screens) -->
  <div class="lg:hidden flex items-center justify-between p-4 border-b border-border bg-card/50 backdrop-blur-md sticky top-0 z-50">
    <a href="/" class="flex items-center gap-2">
      <div class="h-8 w-8 rounded-lg bg-primary text-primary-foreground flex items-center justify-center shadow-glow">
        <span class="font-display font-black text-xl leading-none">P</span>
      </div>
    </a>
  </div>

  <!-- Main Workspace Area -->
  <div class="flex-1 overflow-y-auto relative z-10 custom-scrollbar h-[calc(100vh-80px)] lg:h-screen lg:pl-32">
    <div class="p-6 md:p-12 lg:p-20 max-w-7xl mx-auto relative pb-32">
        <!-- Status Alerts -->
        <?php if (query('status') === 'success'): ?>
          <div class="mb-8 animate-slide-up">
            <div class="px-6 py-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 flex items-center gap-4">
              <?= icon_svg('CheckCircle', 'h-5 w-5') ?>
              <p class="text-sm font-bold"><?= e(urldecode((string) query('msg', 'Success!'))) ?></p>
            </div>
          </div>
        <?php elseif (query('status') === 'error'): ?>
          <div class="mb-8 animate-slide-up">
            <div class="px-6 py-4 rounded-2xl bg-destructive/10 border border-destructive/20 text-destructive flex items-center gap-4">
              <?= icon_svg('AlertCircle', 'h-5 w-5') ?>
              <p class="text-sm font-bold"><?= e(urldecode((string) query('msg', 'An error occurred.'))) ?></p>
            </div>
          </div>
        <?php endif; ?>

        <?php if ($activeTab === 'overview'): ?>
          <div class="space-y-8">
            <div>
              <h1 class="font-display text-6xl font-normal tracking-tight leading-tight">Welcome back, <br><span class="text-primary"><?= e($firstName) ?>.</span></h1>
              <p class="text-muted-foreground mt-3 text-sm">Here's a snapshot of your account activity.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
              <?php
              $statCards = [
                  ['label' => 'Active Licenses', 'value' => (string) count($ownedProducts), 'color' => 'from-primary/20 to-primary/5', 'border' => 'border-primary/20', 'icon' => 'Package', 'iconColor' => 'text-primary'],
                  ['label' => 'Total Invested', 'value' => '$' . number_format((float) $totalSpent, 2), 'color' => 'from-emerald-500/20 to-emerald-600/5', 'border' => 'border-emerald-500/20', 'icon' => 'TrendingUp', 'iconColor' => 'text-emerald-500'],
                  ['label' => 'Member Since', 'value' => date('Y', strtotime((string) ($user['created_at'] ?? 'now'))), 'color' => 'from-purple-500/20 to-purple-600/5', 'border' => 'border-purple-500/20', 'icon' => 'BadgeCheck', 'iconColor' => 'text-purple-500'],
              ];
              foreach ($statCards as $card):
              ?>
                <div class="p-6 rounded-[1.5rem] border <?= e($card['border']) ?> bg-card bg-gradient-to-br <?= e($card['color']) ?> relative overflow-hidden group hover:shadow-soft transition-all">
                  <div class="absolute -top-6 -right-6 h-20 w-20 rounded-full bg-current opacity-5 blur-xl"></div>
                  <div class="<?= e($card['iconColor']) ?> mb-4"><?= icon_svg($card['icon'], 'h-6 w-6') ?></div>
                  <div class="font-display text-3xl tracking-tight"><?= e($card['value']) ?></div>
                  <div class="text-[10px] font-bold uppercase tracking-[0.2em] text-muted-foreground mt-1.5"><?= e($card['label']) ?></div>
                </div>
              <?php endforeach; ?>
            </div>
            <div class="rounded-[2rem] border border-border bg-card shadow-soft overflow-hidden">
              <div class="flex items-center justify-between p-8 border-b border-border">
                <h3 class="font-display text-2xl">Recent Licenses</h3>
                <a href="/profile?tab=vault" class="text-[10px] font-bold uppercase tracking-widest text-primary hover:underline underline-offset-4">View All →</a>
              </div>
              <?php if ($ownedProducts === []): ?>
                <div class="p-16 text-center">
                  <div class="text-muted-foreground opacity-20 mx-auto mb-4"><?= icon_svg('Package', 'h-10 w-10 mx-auto') ?></div>
                  <p class="text-muted-foreground">No products purchased yet.</p>
                </div>
              <?php else: ?>
                <div class="divide-y divide-border/40">
                  <?php foreach (array_slice($ownedProducts, 0, 4) as $item): 
                    $max = (int) ($item['access']['max_update_downloads'] ?? 3);
                    $used = (int) ($item['access']['update_count'] ?? 0);
                    $remaining = $max - $used;
                  ?>
                    <div class="flex items-center gap-6 px-8 py-6 hover:bg-secondary/20 transition-colors">
                      <div class="h-14 w-14 rounded-2xl bg-gradient-to-br <?= e((string) ($item['tone'] ?? '')) ?> flex items-center justify-center font-display text-3xl flex-shrink-0"><?= e((string) ($item['letter'] ?? 'P')) ?></div>
                      <div class="flex-1 min-w-0">
                        <div class="font-display text-xl"><?= e((string) ($item['name'] ?? '')) ?></div>
                        <div class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground mt-0.5"><?= e((string) ($item['cat'] ?? '')) ?> · v<?= e((string) ($item['ver'] ?? '')) ?></div>
                      </div>
                      <div class="hidden md:flex items-center gap-3">
                        <div class="text-right"><div class="text-xs font-bold <?= $remaining <= 0 ? 'text-destructive' : 'text-emerald-500' ?>"><?= e((string) $remaining) ?>/<?= e((string) $max) ?> updates</div></div>
                        <div class="h-8 w-px bg-border"></div>
                        <div class="text-[10px] font-bold uppercase tracking-widest px-3 py-1.5 rounded-full <?= $remaining <= 0 ? 'bg-destructive/10 text-destructive' : 'bg-emerald-500/10 text-emerald-500' ?>"><?= $remaining <= 0 ? 'Limit Hit' : 'Active' ?></div>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </div>
          </div>
        <?php elseif ($activeTab === 'vault'): ?>
          <div class="space-y-8">
            <div class="flex items-end justify-between">
              <div>
                <h1 class="font-display text-6xl font-normal tracking-tight leading-tight">My Digital <br><span class="text-primary">Vault.</span></h1>
                <p class="text-muted-foreground mt-3 text-sm">Manage and download your licensed assets.</p>
              </div>
              <div class="px-5 py-2.5 rounded-full bg-secondary border border-border text-[10px] font-bold uppercase tracking-widest"><span class="text-primary"><?= e((string) count($ownedProducts)) ?></span> Active</div>
            </div>
            <div class="rounded-[2rem] border border-border bg-card shadow-soft overflow-hidden">
              <div class="grid grid-cols-12 gap-4 px-8 py-4 border-b border-border bg-secondary/40">
                <?php foreach ([['Product','col-span-5'],['Category','col-span-2'],['Quota','col-span-3'],['Actions','col-span-2 text-right']] as [$h,$cls]): ?>
                  <div class="<?= e($cls) ?> text-[9px] font-bold uppercase tracking-[0.2em] text-muted-foreground"><?= e($h) ?></div>
                <?php endforeach; ?>
              </div>
              <?php if ($ownedProducts === []): ?>
                <div class="p-20 text-center">
                  <?= icon_svg('Package', 'h-12 w-12 text-muted-foreground opacity-10 mx-auto mb-5') ?>
                  <h3 class="font-display text-2xl mb-2">Your vault is empty</h3>
                  <p class="text-muted-foreground text-sm">Purchase products from the marketplace to see them here.</p>
                </div>
              <?php else: ?>
                <div class="divide-y divide-border/40">
                  <?php foreach ($ownedProducts as $item): 
                    $max = (int) ($item['access']['max_update_downloads'] ?? 3);
                    $used = (int) ($item['access']['update_count'] ?? 0);
                    $remaining = max(0, $max - $used);
                    $pct = ($max > 0) ? ($remaining / $max) * 100 : 0;
                  ?>
                    <div class="grid grid-cols-12 gap-4 px-8 py-7 items-center hover:bg-secondary/20 transition-colors group">
                      <div class="col-span-5 flex items-center gap-5">
                        <div class="h-14 w-14 rounded-2xl bg-gradient-to-br <?= e((string) ($item['tone'] ?? '')) ?> flex items-center justify-center font-display text-3xl flex-shrink-0"><?= e((string) ($item['letter'] ?? 'P')) ?></div>
                        <div><div class="font-display text-xl"><?= e((string) ($item['name'] ?? '')) ?></div><div class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground mt-0.5">v<?= e((string) ($item['ver'] ?? '')) ?> · GPL</div></div>
                      </div>
                      <div class="col-span-2"><span class="text-xs font-bold bg-secondary px-3 py-1.5 rounded-full text-muted-foreground"><?= e((string) ($item['cat'] ?? '')) ?></span></div>
                      <div class="col-span-3 space-y-2">
                        <div class="flex justify-between text-[10px] font-bold uppercase tracking-widest"><span class="<?= $remaining === 0 ? 'text-destructive' : 'text-emerald-500' ?>"><?= e((string) $remaining) ?> left</span><span class="text-muted-foreground"><?= e((string) $used) ?>/<?= e((string) $max) ?> used</span></div>
                        <div class="h-1.5 w-full bg-secondary/70 rounded-full overflow-hidden"><div class="h-full rounded-full <?= $remaining === 0 ? 'bg-destructive' : 'bg-emerald-500' ?>" style="width: <?= e((string) $pct) ?>%"></div></div>
                      </div>
                      <div class="col-span-2 text-right">
                        <?php if ($remaining > 0): ?>
                          <a href="/product/<?= e((string)($item['id'] ?? '')) ?>" class="inline-flex items-center gap-2.5 h-11 px-6 rounded-2xl bg-ink text-ink-foreground hover:shadow-glow text-xs font-bold transition-all hover:-translate-y-0.5 active:scale-95">
                            <?= icon_svg('Eye', 'h-3.5 w-3.5') ?>
                            View Product
                          </a>
                        <?php else: ?>
                          <form method="POST" action="/request-extension" class="inline-block">
                            <input type="hidden" name="product_id" value="<?= e((string)($item['id'] ?? '')) ?>">
                            <input type="hidden" name="message" value="Hi, I have reached my download limit for this product (<?= e((string)($item['name'] ?? '')) ?>) and would like to request an extension for future updates. Thank you!">
                            <button type="submit" class="inline-flex items-center gap-2.5 h-11 px-6 rounded-2xl bg-blue-600 text-white hover:shadow-[0_8px_20px_-6px_rgba(37,99,235,0.5)] text-xs font-bold transition-all hover:-translate-y-0.5 active:scale-95">
                              <?= icon_svg('ArrowUpRight', 'h-3.5 w-3.5') ?> 
                              <span>Request Extension</span>
                            </button>
                          </form>
                        <?php endif; ?>
                      </div>
                    </div>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </div>
          </div>
        <?php elseif ($activeTab === 'payments'): 
          $paymentMethods = $user['paymentMethods'] ?? [];
          $hasPaypal = false;
          $hasPayhere = false;
          $paypalAccount = '';
          $payhereAccount = '';
          $cards = [];

          foreach ($paymentMethods as $pm) {
              if ($pm['provider'] === 'paypal') {
                  $hasPaypal = true;
                  $paypalAccount = $pm['account_identifier'];
              } elseif ($pm['provider'] === 'payhere') {
                  $hasPayhere = true;
                  $payhereAccount = $pm['account_identifier'];
              } elseif (in_array($pm['provider'], ['visa', 'mastercard'])) {
                  $cards[] = $pm;
              }
          }
        ?>
          <div class="space-y-12">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
              <div>
                <h1 class="font-display text-6xl font-normal tracking-tight leading-tight">Payment <br><span class="text-primary">Methods.</span></h1>
                <p class="text-muted-foreground mt-3 text-sm">Manage your billing connections and purchase protection settings.</p>
              </div>
              <div class="flex gap-4">
                <button onclick="openCardModal()" class="px-6 py-3 rounded-2xl bg-ink text-ink-foreground text-xs font-bold uppercase tracking-widest hover:shadow-glow transition-all flex items-center gap-2">
                  <?= icon_svg('Plus', 'h-4 w-4') ?> Add Card
                </button>
              </div>
            </div>

            <div class="grid md:grid-cols-2 gap-8">
              <!-- PayPal Card -->
              <div class="group relative rounded-[2.5rem] border border-border bg-card overflow-hidden shadow-soft hover:shadow-glow transition-all duration-500">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-600/5 via-transparent to-transparent"></div>
                <div class="p-8 relative z-10">
                  <div class="flex items-center justify-between mb-10">
                    <div class="h-12 w-12 rounded-2xl bg-blue-600/10 flex items-center justify-center">
                      <?= icon_svg('Wallet', 'h-6 w-6 text-blue-600') ?>
                    </div>
                    <?php if ($hasPaypal): ?>
                      <span class="px-3 py-1 rounded-full bg-blue-500/10 text-blue-500 text-[10px] font-bold uppercase tracking-widest">Verified Account</span>
                    <?php else: ?>
                      <button onclick="openPaypalModal()" class="px-4 py-1.5 rounded-full bg-blue-600 text-white text-[10px] font-bold uppercase tracking-widest shadow-glow hover:scale-105 transition-transform">Connect PayPal</button>
                    <?php endif; ?>
                  </div>
                  
                  <div class="space-y-1 mb-8">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-muted-foreground">Connected PayPal</p>
                    <?php if ($hasPaypal): 
                      $atPos = strpos($paypalAccount, '@');
                      $masked = substr($paypalAccount, 0, 1) . str_repeat('*', $atPos - 2) . substr($paypalAccount, $atPos - 1);
                    ?>
                      <p class="text-xl font-display"><?= e($masked) ?></p>
                    <?php else: ?>
                      <p class="text-xl font-display text-muted-foreground/30 italic">No account linked</p>
                    <?php endif; ?>
                  </div>

                  <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                       <span class="text-[9px] font-bold uppercase tracking-widest text-blue-500">PayPal Purchase Protection</span>
                    </div>
                    <?php if ($hasPaypal): ?>
                      <form action="/profile/remove-payment" method="POST" onsubmit="return confirm('Disconnect PayPal?')">
                        <?php 
                          $paypalId = 0;
                          foreach($paymentMethods as $m) if($m['provider'] === 'paypal') $paypalId = $m['id'];
                        ?>
                        <input type="hidden" name="id" value="<?= e((string)$paypalId) ?>">
                        <button type="submit" class="text-xs font-bold text-muted-foreground hover:text-destructive transition-colors">Disconnect</button>
                      </form>
                    <?php endif; ?>
                  </div>
                </div>
              </div>

              <!-- Payhere Card -->
              <div class="group relative rounded-[2.5rem] border border-border bg-card overflow-hidden shadow-soft hover:shadow-glow transition-all duration-500">
                <div class="absolute inset-0 bg-gradient-to-br from-primary/5 via-transparent to-transparent"></div>
                <div class="p-8 relative z-10">
                  <div class="flex items-center justify-between mb-10">
                    <div class="h-12 w-12 rounded-2xl bg-primary/10 flex items-center justify-center">
                      <?= icon_svg('Globe', 'h-6 w-6 text-primary') ?>
                    </div>
                    <?php if ($hasPayhere): ?>
                      <span class="px-3 py-1 rounded-full bg-primary/10 text-primary text-[10px] font-bold uppercase tracking-widest">Linked Wallet</span>
                    <?php else: ?>
                      <button onclick="linkPayhere()" class="px-4 py-1.5 rounded-full bg-primary text-primary-foreground text-[10px] font-bold uppercase tracking-widest shadow-glow hover:scale-105 transition-transform">Link Payhere</button>
                    <?php endif; ?>
                  </div>
                  
                  <div class="space-y-1 mb-8">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-muted-foreground">Payhere Details</p>
                    <?php if ($hasPayhere): 
                      $maskedId = substr($payhereAccount, 0, 4) . str_repeat('*', 6) . substr($payhereAccount, -2);
                    ?>
                      <p class="text-xl font-display"><?= e($maskedId) ?></p>
                    <?php else: ?>
                      <p class="text-xl font-display text-muted-foreground/30 italic">Not connected yet</p>
                    <?php endif; ?>
                  </div>

                  <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                       <span class="text-[9px] font-bold uppercase tracking-widest text-primary">Official Merchant Guarantee</span>
                    </div>
                    <?php if ($hasPayhere): ?>
                      <form action="/profile/remove-payment" method="POST" onsubmit="return confirm('Revoke Payhere access?')">
                        <?php 
                          $payhereId = 0;
                          foreach($paymentMethods as $m) if($m['provider'] === 'payhere') $payhereId = $m['id'];
                        ?>
                        <input type="hidden" name="id" value="<?= e((string)$payhereId) ?>">
                        <button type="submit" class="text-xs font-bold text-muted-foreground hover:text-destructive transition-colors">Revoke</button>
                      </form>
                    <?php endif; ?>
                  </div>
                </div>
              </div>

              <!-- Credit Cards -->
              <?php foreach ($cards as $card): 
                $details = json_decode((string)$card['details'], true) ?: [];
                $brand = $card['provider'];
                $isVisa = $brand === 'visa';
              ?>
                <div class="group relative rounded-[2.5rem] border border-border bg-card overflow-hidden shadow-soft hover:shadow-glow transition-all duration-500">
                  <div class="absolute inset-0 bg-gradient-to-br <?= $isVisa ? 'from-indigo-600/5' : 'from-orange-600/5' ?> via-transparent to-transparent"></div>
                  <div class="p-8 relative z-10">
                    <div class="flex items-center justify-between mb-10">
                      <div class="h-12 w-12 rounded-2xl <?= $isVisa ? 'bg-indigo-600/10' : 'bg-orange-600/10' ?> flex items-center justify-center">
                        <?= icon_svg('CreditCard', 'h-6 w-6 ' . ($isVisa ? 'text-indigo-600' : 'text-orange-600')) ?>
                      </div>
                      <span class="px-3 py-1 rounded-full bg-secondary text-muted-foreground text-[10px] font-bold uppercase tracking-widest"><?= ucfirst($brand) ?></span>
                    </div>
                    
                    <div class="space-y-1 mb-8">
                      <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-muted-foreground">Card Number</p>
                      <p class="text-xl font-display"><?= e($card['account_identifier']) ?></p>
                      <p class="text-[10px] font-bold text-muted-foreground/60 uppercase tracking-widest">Expires <?= e((string)($details['expiry'] ?? 'MM/YY')) ?></p>
                    </div>

                    <div class="flex items-center justify-between">
                      <div class="flex items-center gap-2">
                         <span class="text-[9px] font-bold uppercase tracking-widest text-muted-foreground">Secure Card Storage</span>
                      </div>
                      <form action="/profile/remove-payment" method="POST" onsubmit="return confirm('Remove this card?')">
                        <input type="hidden" name="id" value="<?= e((string)$card['id']) ?>">
                        <button type="submit" class="text-xs font-bold text-muted-foreground hover:text-destructive transition-colors">Remove</button>
                      </form>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>

            <div class="rounded-[2rem] border border-border bg-ink text-ink-foreground p-10 relative overflow-hidden">
               <div class="absolute inset-0 grid-bg opacity-10"></div>
               <div class="relative z-10">
                  <h3 class="font-display text-3xl mb-6">Payment Guarantees</h3>
                  <div class="grid sm:grid-cols-3 gap-8">
                     <div class="space-y-3">
                        <div class="h-10 w-10 rounded-xl bg-primary/20 flex items-center justify-center text-primary">
                           <?= icon_svg('ShieldCheck', 'h-5 w-5') ?>
                        </div>
                        <h4 class="font-bold text-sm">Purchase Protection</h4>
                        <p class="text-xs text-ink-foreground/60 leading-relaxed">Shop with confidence. If an item doesn't arrive or match the description, the provider's protection has you covered.</p>
                     </div>
                     <div class="space-y-3">
                        <div class="h-10 w-10 rounded-xl bg-blue-500/20 flex items-center justify-center text-blue-400">
                           <?= icon_svg('Zap', 'h-5 w-5') ?>
                        </div>
                        <h4 class="font-bold text-sm">Instant Checkout</h4>
                        <p class="text-xs text-ink-foreground/60 leading-relaxed">No more entering card details. Use your linked account for one-click secure access to the entire vault.</p>
                     </div>
                     <div class="space-y-3">
                        <div class="h-10 w-10 rounded-xl bg-emerald-500/20 flex items-center justify-center text-emerald-400">
                           <?= icon_svg('CheckCircle', 'h-5 w-5') ?>
                        </div>
                        <h4 class="font-bold text-sm">Verified Merchants</h4>
                        <p class="text-xs text-ink-foreground/60 leading-relaxed">We only use authorized payment partners to ensure your transactions are handled by the world's most trusted platforms.</p>
                     </div>
                  </div>
               </div>
            </div>
          </div>
        <?php elseif ($activeTab === 'settings'): ?>
          <div class="space-y-8">
            <div><h1 class="font-display text-6xl font-normal tracking-tight leading-tight">Profile <br><span class="text-primary">Settings.</span></h1><p class="text-muted-foreground mt-3 text-sm">Update your personal information and preferences.</p></div>
            <div class="p-8 rounded-[2rem] border border-border bg-card shadow-soft">
              <h3 class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground mb-6">Profile Photo</h3>
              <div class="flex items-center gap-8">
                <img src="<?= e(!empty($user['avatar']) ? $user['avatar'] : 'https://api.dicebear.com/7.x/initials/svg?seed=' . urlencode($user['name'] ?? 'User') . '&backgroundColor=f97316') ?>" class="h-24 w-24 rounded-3xl object-cover border border-border shadow-soft bg-secondary/50" alt="" />
                <div class="space-y-3"><p class="text-sm text-muted-foreground">PNG, JPG or GIF. Max 4MB.</p><div class="flex gap-3"><button class="px-6 py-2.5 rounded-xl bg-secondary border border-border text-xs font-bold uppercase tracking-widest hover:bg-border transition-colors" type="button">Upload New</button><button class="px-6 py-2.5 rounded-xl text-xs font-bold uppercase tracking-widest text-destructive hover:bg-destructive/5 transition-colors" type="button">Remove</button></div></div>
              </div>
            </div>
            <div class="p-8 rounded-[2rem] border border-border bg-card shadow-soft space-y-6">
              <h3 class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Personal Information</h3>
              <div class="grid md:grid-cols-2 gap-6">
                <div class="space-y-2"><label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Full Name</label><div class="relative"><input type="text" value="<?= e((string) ($user['name'] ?? '')) ?>" class="w-full bg-secondary/40 border border-border rounded-2xl py-4 pl-12 pr-4 outline-none text-sm"><span class="absolute left-4 top-1/2 -translate-y-1/2 text-muted-foreground"><?= icon_svg('User') ?></span></div></div>
                <div class="space-y-2"><label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Email Address</label><div class="relative"><input type="email" value="<?= e((string) ($user['email'] ?? '')) ?>" class="w-full bg-secondary/40 border border-border rounded-2xl py-4 pl-12 pr-4 outline-none text-sm"><span class="absolute left-4 top-1/2 -translate-y-1/2 text-muted-foreground"><?= icon_svg('Mail') ?></span></div></div>
              </div>
              <div class="pt-2"><button class="px-8 py-4 rounded-2xl bg-primary text-primary-foreground font-bold text-sm hover:brightness-110 transition-all" type="button">Save Changes</button></div>
            </div>
          </div>
        <?php else: ?>
          <div class="space-y-8">
            <div><h1 class="font-display text-6xl font-normal tracking-tight leading-tight">Account <br><span class="text-primary">Security.</span></h1><p class="text-muted-foreground mt-3 text-sm">Manage your password and account authentication.</p></div>
            <div class="p-8 rounded-[2rem] border border-border bg-card shadow-soft space-y-6">
              <h3 class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Change Password</h3>
              <?php foreach (['Current Password','New Password','Confirm New Password'] as $label): ?>
                <div class="space-y-2"><label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground"><?= e($label) ?></label><div class="relative"><input type="password" placeholder="••••••••" class="w-full bg-secondary/40 border border-border rounded-2xl py-4 pl-12 pr-4 outline-none text-sm"><span class="absolute left-4 top-1/2 -translate-y-1/2 text-muted-foreground"><?= icon_svg('Lock') ?></span></div></div>
              <?php endforeach; ?>
              <button class="px-8 py-4 rounded-2xl bg-ink text-ink-foreground font-bold text-sm transition-all shadow-ink" type="button">Update Password</button>
            </div>
          </div>
        <?php endif; ?>
    </div>
  </div>
</main>

<!-- Floating Vertical Navigation Dock -->
<nav class="fixed left-8 top-1/2 -translate-y-1/2 z-[100] animate-slide-left hidden lg:block">
  <div class="flex flex-col items-center gap-2 p-2 rounded-2xl bg-card/40 backdrop-blur-3xl border border-border/50 shadow-[0_20px_50px_-12px_rgba(0,0,0,0.3)]">
    <!-- Home Link -->
    <a href="/" class="h-12 w-12 flex items-center justify-center rounded-2xl text-muted-foreground hover:bg-secondary hover:text-foreground transition-all group relative">
      <?= icon_svg('Layout', 'h-5 w-5') ?>
      <span class="absolute left-16 top-1/2 -translate-y-1/2 px-3 py-1.5 rounded-lg bg-ink text-ink-foreground text-[10px] font-bold uppercase tracking-widest opacity-0 group-hover:opacity-100 -translate-x-2 group-hover:translate-x-0 transition-all pointer-events-none shadow-xl whitespace-nowrap">Marketplace</span>
    </a>

    <div class="w-8 h-px bg-border/50 my-1"></div>

    <!-- Nav Tabs -->
    <?php foreach ($tabs as $id => $tab): $isActive = $activeTab === $id; ?>
      <a href="/profile?tab=<?= e($id) ?>" 
         class="h-12 w-12 flex items-center justify-center rounded-2xl transition-all group relative
                <?= $isActive ? 'text-primary bg-gradient-to-br from-primary/20 to-primary/5 border border-primary/20 shadow-glow scale-110' : 'text-muted-foreground hover:bg-secondary hover:text-foreground' ?>">
        <div class="absolute inset-0 rounded-2xl bg-primary/10 blur-md opacity-0 <?= $isActive ? 'opacity-100' : '' ?> group-hover:opacity-100 transition-opacity"></div>
        <span class="relative z-10"><?= icon_svg($tab['icon'], 'h-5 w-5') ?></span>
        <span class="absolute left-16 top-1/2 -translate-y-1/2 px-3 py-1.5 rounded-lg bg-ink text-ink-foreground text-[10px] font-bold uppercase tracking-widest opacity-0 group-hover:opacity-100 -translate-x-2 group-hover:translate-x-0 transition-all pointer-events-none shadow-xl whitespace-nowrap"><?= e($tab['label']) ?></span>
      </a>
    <?php endforeach; ?>

    <div class="w-8 h-px bg-border/50 my-1"></div>

    <!-- User Profile -->
    <div class="h-12 w-12 flex items-center justify-center rounded-2xl overflow-hidden border border-border/50 group relative cursor-pointer">
      <img src="<?= e(!empty($user['avatar']) ? $user['avatar'] : 'https://api.dicebear.com/7.x/initials/svg?seed=' . urlencode($user['name'] ?? 'User')) ?>" alt="" class="h-full w-full object-cover">
      <span class="absolute left-16 top-1/2 -translate-y-1/2 px-3 py-1.5 rounded-lg bg-ink text-ink-foreground text-[10px] font-bold uppercase tracking-widest opacity-0 group-hover:opacity-100 -translate-x-2 group-hover:translate-x-0 transition-all pointer-events-none shadow-xl whitespace-nowrap"><?= e((string) ($user['name'] ?? 'Account')) ?></span>
    </div>

    <!-- Logout -->
    <form method="post" action="/auth/logout">
      <button type="submit" class="h-12 w-12 flex items-center justify-center rounded-2xl text-muted-foreground hover:bg-destructive/10 hover:text-destructive transition-all group relative">
        <?= icon_svg('LogOut', 'h-5 w-5') ?>
        <span class="absolute left-16 top-1/2 -translate-y-1/2 px-3 py-1.5 rounded-lg bg-destructive text-white text-[10px] font-bold uppercase tracking-widest opacity-0 group-hover:opacity-100 -translate-x-2 group-hover:translate-x-0 transition-all pointer-events-none shadow-xl whitespace-nowrap">Sign Out</span>
      </button>
    </form>
  </div>
</nav>
<?php require BASE_PATH . '/app/Views/partials/footer.php'; ?>

<!-- Dummy PayPal Modal -->
<div id="paypalModal" class="fixed inset-0 z-[200] hidden flex items-center justify-center p-6">
  <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closePaypalModal()"></div>
  <div class="relative w-full max-w-md bg-white text-zinc-900 rounded-[2.5rem] overflow-hidden shadow-2xl animate-scale-in">
    <div class="p-8 text-center border-b border-zinc-100">
      <div class="flex justify-center mb-6">
         <svg class="h-8" viewBox="0 0 24 24" fill="#003087"><path d="M20.067 6.178c-.96-1.503-2.847-2.178-5.334-2.178H7.333a1.333 1.333 0 0 0-1.319 1.134L3.067 21.089a.667.667 0 0 0 .659.778h4.667a.667.667 0 0 0 .659-.556l1.334-8.333.007-.06a1.333 1.333 0 0 1 1.319-1.134h1.334c2.667 0 4.667-.667 5.334-4.334.333-1.667-.267-2.667-.333-2.667zM14.734 8.178c-.333 1.667-1.333 2.667-3.334 2.667h-.667l.334-2.112.2-.066c.133-.033.4-.066.6-.066h.667c1.333 0 1.667.333 2.2 1.577z"></path></svg>
      </div>
      <h3 class="text-2xl font-bold">Log in with PayPal</h3>
      <p class="text-sm text-zinc-500 mt-2">To connect your account to PixelVault</p>
    </div>
    <form id="paypalLoginForm" class="p-8 space-y-4" onsubmit="handlePaypalLogin(event)">
      <input type="email" id="paypalEmail" required class="w-full px-5 py-4 rounded-xl border border-zinc-200 bg-zinc-50 focus:ring-2 focus:ring-blue-600 outline-none transition-all" placeholder="Email or mobile number">
      <input type="password" id="paypalPass" required class="w-full px-5 py-4 rounded-xl border border-zinc-200 bg-zinc-50 focus:ring-2 focus:ring-blue-600 outline-none transition-all" placeholder="Password">
      <button type="submit" class="w-full py-4 rounded-xl bg-[#0070ba] text-white font-bold hover:bg-[#005ea6] transition-colors">Log In</button>
      <div id="paypalLoading" class="hidden text-center py-2">
         <div class="inline-block h-5 w-5 border-2 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
         <p class="text-[10px] font-bold mt-2 text-blue-600 uppercase tracking-widest">Verifying Account...</p>
      </div>
    </form>
  </div>
</div>

<!-- Card Modal -->
<div id="cardModal" class="fixed inset-0 z-[200] hidden flex items-center justify-center p-6">
  <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeCardModal()"></div>
  <div class="relative w-full max-w-md bg-card border border-border rounded-[2.5rem] overflow-hidden shadow-2xl animate-scale-in">
    <div class="p-8 border-b border-border">
      <h3 class="text-2xl font-display">Add Credit Card</h3>
      <p class="text-sm text-muted-foreground mt-1">Visa or Mastercard supported.</p>
    </div>
    <form class="p-8 space-y-6" onsubmit="handleCardSubmit(event)">
      <div class="space-y-2">
        <label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Card Number</label>
        <div class="relative">
          <input type="text" id="cardNumber" required maxlength="19" class="w-full bg-secondary/40 border border-border rounded-2xl py-4 pl-12 pr-4 outline-none text-sm font-mono" placeholder="0000 0000 0000 0000">
          <span id="cardIcon" class="absolute left-4 top-1/2 -translate-y-1/2 text-muted-foreground"><?= icon_svg('CreditCard') ?></span>
        </div>
      </div>
      <div class="grid grid-cols-2 gap-4">
        <div class="space-y-2">
          <label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Expiry Date</label>
          <input type="text" id="cardExpiry" required maxlength="5" class="w-full bg-secondary/40 border border-border rounded-2xl py-4 px-4 outline-none text-sm font-mono" placeholder="MM/YY">
        </div>
        <div class="space-y-2">
          <label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">CVC</label>
          <input type="text" required maxlength="4" class="w-full bg-secondary/40 border border-border rounded-2xl py-4 px-4 outline-none text-sm font-mono" placeholder="•••">
        </div>
      </div>
      <button type="submit" class="w-full py-4 rounded-2xl bg-primary text-primary-foreground font-bold hover:brightness-110 transition-all">Add Card</button>
      <div id="cardLoading" class="hidden text-center py-2">
         <div class="inline-block h-5 w-5 border-2 border-primary border-t-transparent rounded-full animate-spin"></div>
         <p class="text-[10px] font-bold mt-2 text-primary uppercase tracking-widest">Securing Card...</p>
      </div>
    </form>
  </div>
</div>

<form id="linkPaymentForm" action="/profile/link-payment" method="POST" class="hidden">
   <input type="hidden" name="type" id="linkType">
   <input type="hidden" name="value" id="linkValue">
</form>

<script>
  function openPaypalModal() {
      document.getElementById('paypalModal').classList.remove('hidden');
      document.body.style.overflow = 'hidden';
  }
  function closePaypalModal() {
      document.getElementById('paypalModal').classList.add('hidden');
      document.body.style.overflow = 'auto';
  }
  function handlePaypalLogin(e) {
      e.preventDefault();
      const email = document.getElementById('paypalEmail').value;
      const btn = e.target.querySelector('button');
      const loading = document.getElementById('paypalLoading');

      btn.classList.add('hidden');
      loading.classList.remove('hidden');
      setTimeout(() => {
          document.getElementById('linkType').value = 'paypal';
          document.getElementById('linkValue').value = email;
          document.getElementById('linkPaymentForm').submit();
      }, 2000);
  }

  function linkPayhere() {
      const id = 'PAYH' + Math.floor(10000000 + Math.random() * 90000000);
      if (confirm('Simulate Payhere connection for ID: ' + id + '?')) {
          document.getElementById('linkType').value = 'payhere';
          document.getElementById('linkValue').value = id;
          document.getElementById('linkPaymentForm').submit();
      }
  }

  function openCardModal() {
      document.getElementById('cardModal').classList.remove('hidden');
      document.body.style.overflow = 'hidden';
  }
  function closeCardModal() {
      document.getElementById('cardModal').classList.add('hidden');
      document.body.style.overflow = 'auto';
  }
  function handleCardSubmit(e) {
      e.preventDefault();
      const num = document.getElementById('cardNumber').value.replace(/\s/g, '');
      const btn = e.target.querySelector('button');
      const loading = document.getElementById('cardLoading');

      btn.classList.add('hidden');
      loading.classList.remove('hidden');

      setTimeout(() => {
          const provider = num.startsWith('4') ? 'visa' : 'mastercard';
          const masked = '•••• ' + num.slice(-4);
          document.getElementById('linkType').value = provider;
          document.getElementById('linkValue').value = masked;
          document.getElementById('linkPaymentForm').submit();
      }, 2000);
  }

  document.getElementById('cardNumber').addEventListener('input', (e) => {
      let val = e.target.value.replace(/\D/g, '');
      let formatted = val.match(/.{1,4}/g)?.join(' ') || '';
      e.target.value = formatted;
      
      const icon = document.getElementById('cardIcon');
      if (val.startsWith('4')) icon.style.color = '#4f46e5'; 
      else if (val.startsWith('5')) icon.style.color = '#ea580c';
      else icon.style.color = 'inherit';
  });

  document.getElementById('cardExpiry').addEventListener('input', (e) => {
      let val = e.target.value.replace(/\D/g, '');
      if (val.length >= 2) {
          e.target.value = val.slice(0, 2) + '/' + val.slice(2, 4);
      } else {
          e.target.value = val;
      }
  });
</script>

<style>
  @keyframes slide-up {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
  }
  @keyframes scale-in {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
  }
  @keyframes slide-left {
    from { opacity: 0; transform: translate(-20px, -50%); }
    to { opacity: 1; transform: translate(0, -50%); }
  }
  .animate-slide-left { animation: slide-left 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
  .animate-slide-up { animation: slide-up 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
  .animate-scale-in { animation: scale-in 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
</style>
