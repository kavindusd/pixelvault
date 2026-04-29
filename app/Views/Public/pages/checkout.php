<?php require BASE_PATH . '/app/Views/partials/header.php'; ?>
<main class="min-h-screen bg-background text-foreground flex flex-col">
  <?php if ($cartItems === []): ?>
    <div class="pt-40 pb-20 container flex flex-col items-center text-center">
      <div class="h-20 w-20 rounded-full bg-secondary flex items-center justify-center mb-6"><?= icon_svg('ShoppingCart', 'h-10 w-10 text-muted-foreground') ?></div>
      <h2 class="font-display text-4xl mb-4">Your cart is empty</h2>
      <a href="/marketplace" class="text-primary font-bold hover:underline underline-offset-4">Back to Marketplace</a>
    </div>
  <?php else: ?>
    <div class="pt-32 pb-20 container max-w-6xl">
      <div class="flex flex-col lg:flex-row gap-12 text-left">
        <div class="flex-1 space-y-12">
          <h1 class="font-display text-5xl tracking-tight"><span class="text-primary">Secure</span> Checkout</h1>
          <form method="post" action="/checkout/process" class="space-y-8">
            <div class="space-y-6">
              <div class="flex items-center justify-between">
                <h3 class="font-display text-3xl">Billing Information</h3>
              </div>
              <?php 
                $fullName = (string) ($user['name'] ?? '');
                $nameParts = explode(' ', $fullName, 2);
                $firstName = $nameParts[0] ?? '';
                $lastName = $nameParts[1] ?? '';
              ?>
              <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                  <label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground ml-1">First Name</label>
                  <input type="text" name="first_name" placeholder="John" value="<?= e($firstName) ?>" class="w-full bg-card border border-border rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-primary/20">
                </div>
                <div class="space-y-2">
                  <label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground ml-1">Last Name</label>
                  <input type="text" name="last_name" placeholder="Doe" value="<?= e($lastName) ?>" class="w-full bg-card border border-border rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-primary/20">
                </div>
              </div>
              <div class="space-y-2">
                <label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground ml-1">Email Address</label>
                <input type="email" name="email" placeholder="john@example.com" value="<?= e((string) ($user['email'] ?? '')) ?>" class="w-full bg-card border border-border rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-primary/20">
              </div>
              <div class="space-y-2">
                <label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground ml-1">Street Address</label>
                <input type="text" name="address" placeholder="123 Main St, Apartment 4B" value="<?= e((string) (($user['address_apartment'] ?? '') . ' ' . ($user['address_city'] ?? ''))) ?>" class="w-full bg-card border border-border rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-primary/20">
              </div>
              <?php if (!$user): ?>
                <div class="space-y-2">
                  <label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground ml-1">Create account password</label>
                  <input type="password" name="account_password" placeholder="password123" class="w-full bg-card border border-border rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-primary/20">
                  <p class="text-[10px] text-muted-foreground ml-1 font-medium">If you do not have an account, we will create one for you.</p>
                </div>
              <?php endif; ?>
              <div class="grid grid-cols-2 gap-4">
                <div class="space-y-2">
                  <label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground ml-1">Country</label>
                  <div class="w-full bg-secondary/30 border border-border rounded-xl py-3 px-4 text-sm font-medium">Sri Lanka</div>
                </div>
                <div class="space-y-2">
                  <label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground ml-1">Postcode / ZIP</label>
                  <input type="text" name="zip" placeholder="10115" value="<?= e((string) ($user['address_postal_code'] ?? '')) ?>" class="w-full bg-card border border-border rounded-xl py-3 px-4 focus:outline-none focus:ring-2 focus:ring-primary/20">
                </div>
              </div>
            </div>

            <div class="space-y-6">
              <h3 class="font-display text-3xl">Payment Method</h3>
              
              <?php if ($user && !empty($user['paymentMethods'])): ?>
                <div class="p-6 rounded-3xl bg-secondary/20 border border-border space-y-4">
                  <p class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground ml-1">Use Saved Method</p>
                  <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php foreach ($user['paymentMethods'] as $pm): 
                      $provider = $pm['provider'];
                      $icon = match($provider) {
                        'paypal' => 'Wallet',
                        'payhere' => 'Globe',
                        default => 'CreditCard'
                      };
                      $color = match($provider) {
                        'paypal' => 'text-blue-600 bg-blue-500/10',
                        'payhere' => 'text-emerald-600 bg-emerald-500/10',
                        default => 'text-primary bg-primary/10'
                      };
                    ?>
                      <label class="relative flex items-center gap-4 p-5 rounded-2xl border-2 border-border bg-card cursor-pointer hover:border-primary/50 transition-all has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                        <input type="radio" name="payment_method" value="<?= e($provider) ?>" class="sr-only peer" <?= $pm['is_default'] ? 'checked' : '' ?>>
                        <div class="h-10 w-10 rounded-xl <?= $color ?> flex items-center justify-center"><?= icon_svg($icon, 'h-5 w-5') ?></div>
                        <div>
                          <div class="font-bold text-sm"><?= ucfirst($provider) ?></div>
                          <div class="text-[10px] text-muted-foreground uppercase font-bold tracking-widest"><?= e($pm['account_identifier']) ?></div>
                        </div>
                        <div class="ml-auto w-5 h-5 rounded-full border-2 border-border peer-checked:border-primary peer-checked:bg-primary flex items-center justify-center after:content-[''] after:w-1.5 after:h-1.5 after:rounded-full after:bg-white after:opacity-0 peer-checked:after:opacity-100"></div>
                      </label>
                    <?php endforeach; ?>
                  </div>
                  <div class="h-px bg-border my-4"></div>
                  <p class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground ml-1">Or use another one</p>
                </div>
              <?php endif; ?>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <label class="relative flex items-center gap-4 p-5 rounded-2xl border-2 border-border bg-card cursor-pointer hover:border-primary/50 transition-all has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                  <input type="radio" name="payment_method" value="PayPal" class="sr-only peer" <?= (empty($user['paymentMethods']) ? 'checked' : '') ?>>
                  <div class="h-10 w-10 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-600"><?= icon_svg('Wallet', 'h-5 w-5') ?></div>
                  <div><div class="font-bold text-sm">PayPal</div><div class="text-[10px] text-muted-foreground uppercase font-bold tracking-widest">Global Payments</div></div>
                  <div class="ml-auto w-5 h-5 rounded-full border-2 border-border peer-checked:border-primary peer-checked:bg-primary flex items-center justify-center after:content-[''] after:w-1.5 after:h-1.5 after:rounded-full after:bg-white after:opacity-0 peer-checked:after:opacity-100"></div>
                </label>
                <label class="relative flex items-center gap-4 p-5 rounded-2xl border-2 border-border bg-card cursor-pointer hover:border-primary/50 transition-all has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                  <input type="radio" name="payment_method" value="Payhere" class="sr-only peer">
                  <div class="h-10 w-10 rounded-xl bg-emerald-500/10 flex items-center justify-center text-emerald-600"><?= icon_svg('Globe', 'h-5 w-5') ?></div>
                  <div><div class="font-bold text-sm">Payhere</div><div class="text-[10px] text-muted-foreground uppercase font-bold tracking-widest">Local Gateway</div></div>
                  <div class="ml-auto w-5 h-5 rounded-full border-2 border-border peer-checked:border-primary peer-checked:bg-primary flex items-center justify-center after:content-[''] after:w-1.5 after:h-1.5 after:rounded-full after:bg-white after:opacity-0 peer-checked:after:opacity-100"></div>
                </label>
                <label class="relative flex items-center gap-4 p-5 rounded-2xl border-2 border-border bg-card cursor-pointer hover:border-primary/50 transition-all has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                  <input type="radio" name="payment_method" value="Visa" class="sr-only peer">
                  <div class="h-10 w-10 rounded-xl bg-indigo-500/10 flex items-center justify-center text-indigo-600"><?= icon_svg('CreditCard', 'h-5 w-5') ?></div>
                  <div><div class="font-bold text-sm">Visa</div><div class="text-[10px] text-muted-foreground uppercase font-bold tracking-widest">Credit/Debit</div></div>
                  <div class="ml-auto w-5 h-5 rounded-full border-2 border-border peer-checked:border-primary peer-checked:bg-primary flex items-center justify-center after:content-[''] after:w-1.5 after:h-1.5 after:rounded-full after:bg-white after:opacity-0 peer-checked:after:opacity-100"></div>
                </label>
                <label class="relative flex items-center gap-4 p-5 rounded-2xl border-2 border-border bg-card cursor-pointer hover:border-primary/50 transition-all has-[:checked]:border-primary has-[:checked]:bg-primary/5">
                  <input type="radio" name="payment_method" value="Mastercard" class="sr-only peer">
                  <div class="h-10 w-10 rounded-xl bg-orange-500/10 flex items-center justify-center text-orange-600"><?= icon_svg('CreditCard', 'h-5 w-5') ?></div>
                  <div><div class="font-bold text-sm">Mastercard</div><div class="text-[10px] text-muted-foreground uppercase font-bold tracking-widest">Credit/Debit</div></div>
                  <div class="ml-auto w-5 h-5 rounded-full border-2 border-border peer-checked:border-primary peer-checked:bg-primary flex items-center justify-center after:content-[''] after:w-1.5 after:h-1.5 after:rounded-full after:bg-white after:opacity-0 peer-checked:after:opacity-100"></div>
                </label>
              </div>
            </div>
            <div class="p-8 rounded-[2rem] border border-border bg-card space-y-6">
              <div class="flex items-center gap-3 text-primary font-bold text-xs uppercase tracking-widest"><?= icon_svg('ShieldCheck', 'h-4 w-4') ?> Secure Checkout (AES-256)</div>
              <div class="space-y-4">
                <div class="flex justify-between text-muted-foreground"><span>Subtotal</span><span class="font-medium">$<?= e(number_format((float) $total, 2)) ?></span></div>
                <div class="flex justify-between text-muted-foreground"><span>Taxes &amp; Processing</span><span class="font-medium">$0.00</span></div>
                <div class="h-px bg-border pt-2"></div>
                <div class="flex justify-between items-end"><span class="font-display text-2xl">Total Payable</span><span class="font-display text-4xl text-primary">$<?= e(number_format((float) $total, 2)) ?></span></div>
              </div>
              <button type="submit" class="w-full bg-ink text-ink-foreground py-5 rounded-2xl font-bold text-xl shadow-ink flex items-center justify-center gap-3 hover:brightness-110 active:scale-[0.98] transition-all">Confirm &amp; Pay</button>
              <p class="text-[10px] text-center text-muted-foreground uppercase font-bold tracking-widest">Instant digital delivery after payment confirmation</p>
            </div>
          </form>
        </div>

        <!-- Dummy Payment Portal Overlay -->
        <div id="payment-portal" class="fixed inset-0 z-[100] bg-black/80 backdrop-blur-md hidden items-center justify-center p-6">
          <div class="bg-card w-full max-w-md rounded-[2.5rem] p-10 text-center space-y-8 animate-scale-in">
            <div id="portal-logo" class="h-20 flex items-center justify-center">
              <!-- Dynamically set by JS -->
            </div>
            <div class="space-y-2">
              <h3 class="text-2xl font-display font-bold">Secure Authorization</h3>
              <p class="text-sm text-muted-foreground">Redirecting to <span id="gateway-name" class="font-bold text-foreground">PayPal</span> secure environment...</p>
            </div>
            <div class="relative h-1 w-full bg-secondary rounded-full overflow-hidden">
              <div class="absolute inset-y-0 left-0 bg-primary w-1/2 animate-[pv-shimmer_2s_infinite_linear]" style="background-size: 200% 100%; background-image: linear-gradient(90deg, transparent, white, transparent)"></div>
            </div>
            <div class="flex items-center justify-center gap-2 text-[10px] font-bold uppercase tracking-widest text-muted-foreground">
              <?= icon_svg('ShieldCheck', 'h-3.5 w-3.5 text-primary') ?> Verified by SSL / AES-256
            </div>
          </div>
        </div>

        <script>
          document.querySelector('form[action="/checkout/process"]').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = this;
            const method = form.querySelector('input[name="payment_method"]:checked').value;
            const portal = document.getElementById('payment-portal');
            const gatewayName = document.getElementById('gateway-name');
            const portalLogo = document.getElementById('portal-logo');

            gatewayName.textContent = method;
            const iconColor = method === 'PayPal' ? 'text-blue-700' : (method === 'Payhere' ? 'text-emerald-600' : (method === 'Visa' ? 'text-indigo-600' : 'text-orange-600'));
            portalLogo.innerHTML = `<div class="text-4xl font-display font-black italic ${iconColor}">${method}</div>`;

            portal.classList.remove('hidden');
            portal.classList.add('flex');

            setTimeout(() => {
              form.submit();
            }, 2500);
          });
        </script>
        <aside class="lg:w-96 space-y-6">
          <div class="p-8 rounded-[2rem] border border-border bg-secondary/10 sticky top-32">
            <h3 class="font-display text-2xl mb-6">Order Items</h3>
            <div class="space-y-6">
              <?php foreach ($cartItems as $item): ?>
                <div class="flex gap-4 group text-left items-center">
                  <a href="/product/<?= e((string) ($item['id'] ?? '')) ?>" class="h-16 w-16 rounded-xl bg-card border border-border flex items-center justify-center relative overflow-hidden flex-shrink-0">
                    <?php if (!empty($item['image'])): ?>
                      <img src="<?= e((string) $item['image']) ?>" alt="<?= e((string) ($item['name'] ?? '')) ?>" class="absolute inset-0 w-full h-full object-cover">
                    <?php else: ?>
                      <span class="font-display text-2xl text-muted-foreground"><?= e((string) ($item['letter'] ?? 'P')) ?></span>
                    <?php endif; ?>
                  </a>
                  <div class="flex-1 min-w-0">
                    <a href="/product/<?= e((string) ($item['id'] ?? '')) ?>" class="font-bold leading-none mb-1 block truncate hover:text-primary transition-colors"><?= e((string) ($item['name'] ?? '')) ?></a>
                    <div class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground"><?= e((string) ($item['cat'] ?? '')) ?></div>
                    <div class="font-bold text-primary mt-1">$<?= e((string) ($item['price'] ?? '')) ?></div>
                  </div>
                  <form method="post" action="/cart/remove">
                    <input type="hidden" name="product_id" value="<?= e((string) ($item['id'] ?? '')) ?>">
                    <input type="hidden" name="return_to" value="/checkout">
                    <button type="submit" class="h-8 w-8 rounded-lg bg-red-500/10 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all" title="Remove from cart">×</button>
                  </form>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </aside>
      </div>
    </div>
  <?php endif; ?>
</main>
<?php require BASE_PATH . '/app/Views/partials/footer.php'; ?>
