<div class="space-y-6">
  <div><h2 class="font-display text-2xl font-bold tracking-tight">Manual Controls</h2><p class="text-sm text-muted-foreground mt-0.5">Override system limits and manage user permissions manually.</p></div>
  <div class="grid md:grid-cols-2 gap-6">
    <form method="post" action="/admin/users/reset-update-count" class="bg-card border border-border p-6 rounded-2xl">
      <div class="h-12 w-12 rounded-xl bg-primary/10 flex items-center justify-center mb-5"><?= icon_svg('RefreshCw', 'h-6 w-6 text-primary') ?></div>
      <h4 class="font-bold text-lg mb-1">Reset Update Count</h4>
      <p class="text-sm text-muted-foreground mb-6">Reset the update limit for a specific user/product.</p>
      <div class="space-y-4">
        <div class="space-y-2">
          <label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground ml-1">User Email</label>
          <input type="email" name="user_email" placeholder="user@example.com" class="w-full p-3 rounded-xl bg-background border border-border text-sm focus:ring-2 focus:ring-primary outline-none transition-all" required>
        </div>
        <div class="space-y-2">
          <label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground ml-1">Select Product</label>
          <select name="product_id" class="w-full p-3 rounded-xl bg-background border border-border text-sm outline-none">
            <?php foreach (($products ?? []) as $p): ?>
              <option value="<?= e((string) ($p['id'] ?? '')) ?>"><?= e((string) ($p['name'] ?? '')) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <button class="w-full py-3.5 rounded-xl bg-primary text-primary-foreground font-bold text-sm shadow-glow hover:brightness-110 active:scale-[0.98] transition-all" type="submit">Reset Count to 0</button>
      </div>
    </form>
    <form method="post" action="/admin/users/grant-access" class="bg-card border border-border p-6 rounded-2xl">
      <div class="h-12 w-12 rounded-xl bg-blue-500/10 flex items-center justify-center mb-5"><?= icon_svg('Unlock', 'h-6 w-6 text-blue-500') ?></div>
      <h4 class="font-bold text-lg mb-1">Manual Download Access</h4>
      <p class="text-sm text-muted-foreground mb-6">Grant extra downloads for a specific user/product.</p>
      <div class="space-y-4">
        <div class="space-y-2">
          <label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground ml-1">User Email</label>
          <input type="email" name="user_email" placeholder="user@example.com" value="<?= e((string)query('email')) ?>" class="w-full p-3 rounded-xl bg-background border border-border text-sm focus:ring-2 focus:ring-blue-500 outline-none transition-all" required>
        </div>
        <div class="space-y-2">
          <label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground ml-1">Select Product</label>
          <select name="product_id" class="w-full p-3 rounded-xl bg-background border border-border text-sm outline-none">
            <?php foreach (($products ?? []) as $p): ?>
              <option value="<?= e((string) ($p['id'] ?? '')) ?>" <?= (int)query('product_id') === (int)($p['id'] ?? 0) ? 'selected' : '' ?>>
                <?= e((string) ($p['name'] ?? '')) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div class="space-y-2">
            <label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground ml-1">Extra Slots</label>
            <input type="number" name="extra_downloads" placeholder="e.g. 5" class="w-full p-3 rounded-xl bg-background border border-border text-sm outline-none" min="1" required>
          </div>
          <div class="space-y-2">
            <label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground ml-1">Reason</label>
            <input type="text" name="override_reason" placeholder="e.g. Support Request" class="w-full p-3 rounded-xl bg-background border border-border text-sm outline-none">
          </div>
        </div>
        <button class="w-full py-3.5 rounded-xl bg-blue-500 text-white font-bold text-sm shadow-lg shadow-blue-500/20 hover:brightness-110 active:scale-[0.98] transition-all" type="submit">Grant Access Override</button>
      </div>
    </form>
  </div>
</div>
