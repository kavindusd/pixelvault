<div class="space-y-6">
  <div class="flex items-end justify-between">
    <div>
      <h2 class="text-3xl font-display font-bold tracking-tight">Payment Receiving Accounts</h2>
      <p class="text-sm text-muted-foreground mt-1">Configure where the money goes for each payment provider.</p>
    </div>
    <button onclick="document.getElementById('addPaymentModal').classList.remove('hidden')" class="px-5 py-2.5 rounded-xl bg-primary text-primary-foreground text-xs font-bold uppercase tracking-widest hover:brightness-110 transition-all flex items-center gap-2">
      <?= icon_svg('Plus', 'h-4 w-4') ?> Add Account
    </button>
  </div>

  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach ($adminPaymentMethods as $method): 
      $provider = $method['provider'];
      $isActive = (bool)$method['is_active'];
      $tone = match($provider) {
        'paypal' => 'from-blue-600/10 to-blue-600/5 border-blue-600/20',
        'payhere' => 'from-orange-600/10 to-orange-600/5 border-orange-600/20',
        'visa' => 'from-indigo-600/10 to-indigo-600/5 border-indigo-600/20',
        'mastercard' => 'from-red-600/10 to-red-600/5 border-red-600/20',
        default => 'from-secondary to-secondary/50 border-border'
      };
      $icon = match($provider) {
        'paypal' => 'Wallet',
        'payhere' => 'Globe',
        default => 'CreditCard'
      };
      $iconColor = match($provider) {
        'paypal' => 'text-blue-600',
        'payhere' => 'text-orange-600',
        'visa' => 'text-indigo-600',
        'mastercard' => 'text-red-600',
        default => 'text-primary'
      };
    ?>
      <div class="group relative rounded-3xl border p-6 bg-card bg-gradient-to-br <?= $tone ?> transition-all hover:shadow-glow">
        <div class="flex items-center justify-between mb-6">
          <div class="h-10 w-10 rounded-xl bg-background/50 flex items-center justify-center <?= $iconColor ?>">
            <?= icon_svg($icon, 'h-5 w-5') ?>
          </div>
          <div class="flex items-center gap-2">
            <span class="px-2 py-0.5 rounded-full <?= $isActive ? 'bg-emerald-500/10 text-emerald-500' : 'bg-muted text-muted-foreground' ?> text-[9px] font-bold uppercase tracking-widest">
              <?= $isActive ? 'Active' : 'Disabled' ?>
            </span>
          </div>
        </div>
        
        <div class="space-y-1 mb-6">
          <p class="text-[9px] font-bold uppercase tracking-[0.2em] text-muted-foreground"><?= ucfirst($provider) ?> Account</p>
          <p class="text-lg font-display truncate" title="<?= e($method['account_identifier']) ?>"><?= e($method['account_identifier']) ?></p>
        </div>

        <div class="flex items-center justify-between pt-4 border-t border-border/50">
          <form action="/admin/update-payment-method" method="POST">
            <input type="hidden" name="id" value="<?= e((string)$method['id']) ?>">
            <input type="hidden" name="is_active" value="<?= $isActive ? '0' : '1' ?>">
            <button type="submit" class="text-[10px] font-bold uppercase tracking-widest <?= $isActive ? 'text-amber-500' : 'text-emerald-500' ?> hover:underline">
              <?= $isActive ? 'Disable' : 'Enable' ?>
            </button>
          </form>
          <form action="/admin/delete-payment-method" method="POST" onsubmit="return confirm('Delete this account?')">
            <input type="hidden" name="id" value="<?= e((string)$method['id']) ?>">
            <button type="submit" class="text-[10px] font-bold uppercase tracking-widest text-destructive hover:underline">Delete</button>
          </form>
        </div>
      </div>
    <?php endforeach; ?>

    <?php if (empty($adminPaymentMethods)): ?>
      <div class="col-span-full py-20 text-center border-2 border-dashed border-border rounded-3xl">
        <div class="h-16 w-16 bg-secondary rounded-2xl flex items-center justify-center mx-auto mb-4 text-muted-foreground/30">
          <?= icon_svg('Wallet', 'h-8 w-8') ?>
        </div>
        <h3 class="text-xl font-display font-bold">No receiving accounts</h3>
        <p class="text-sm text-muted-foreground mt-1">Add your first account to start accepting payments.</p>
      </div>
    <?php endif; ?>
  </div>
</div>

<!-- Add Payment Modal -->
<div id="addPaymentModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-6">
  <div class="absolute inset-0 bg-background/80 backdrop-blur-sm" onclick="this.parentElement.classList.add('hidden')"></div>
  <div class="relative w-full max-w-md bg-card border border-border rounded-[2.5rem] shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-300">
    <div class="p-8 border-b border-border">
      <h3 class="text-2xl font-display font-bold">Add Receiving Account</h3>
      <p class="text-sm text-muted-foreground mt-1">Configure where funds will be deposited.</p>
    </div>
    <form action="/admin/add-payment-method" method="POST" class="p-8 space-y-6">
      <div class="space-y-2">
        <label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Provider</label>
        <select name="provider" required class="w-full bg-secondary/50 border border-border rounded-2xl py-4 px-4 outline-none text-sm appearance-none">
          <option value="paypal">PayPal</option>
          <option value="payhere">PayHere</option>
          <option value="visa">Visa (Direct)</option>
          <option value="mastercard">Mastercard (Direct)</option>
        </select>
      </div>
      <div class="space-y-2">
        <label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Account Identifier / Merchant ID</label>
        <input type="text" name="account_identifier" required class="w-full bg-secondary/50 border border-border rounded-2xl py-4 px-4 outline-none text-sm" placeholder="e.g. merchant@email.com or ID-12345">
      </div>
      <div class="pt-4 flex gap-4">
        <button type="button" onclick="document.getElementById('addPaymentModal').classList.add('hidden')" class="flex-1 py-4 rounded-2xl bg-secondary text-secondary-foreground font-bold text-sm">Cancel</button>
        <button type="submit" class="flex-1 py-4 rounded-2xl bg-primary text-primary-foreground font-bold text-sm hover:brightness-110 transition-all">Add Account</button>
      </div>
    </form>
  </div>
</div>
