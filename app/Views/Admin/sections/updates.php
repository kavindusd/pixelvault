<?php
$editingId = (int) (query('edit') ?? 0);
$editingProduct = $editingId > 0 ? array_values(array_filter($products ?? [], fn($p) => (int)$p['id'] === $editingId))[0] ?? null : null;
?>

<?php if ($editingProduct): ?>
  <!-- Edit Product View -->
  <div class="space-y-6">
    <div class="flex items-center gap-4">
      <a href="/admin?tab=updates" class="h-10 w-10 rounded-xl bg-secondary flex items-center justify-center text-muted-foreground"><?= icon_svg('ArrowRight', 'h-4 w-4 rotate-180') ?></a>
      <div><h2 class="font-display text-2xl font-bold tracking-tight">Edit Product: <?= e($editingProduct['name']) ?></h2><p class="text-sm text-muted-foreground mt-0.5">Modify core product data and visibility.</p></div>
    </div>

    <form method="post" action="/admin/products/update" class="grid lg:grid-cols-3 gap-6">
      <input type="hidden" name="id" value="<?= $editingProduct['id'] ?>">
      <div class="lg:col-span-2 bg-card border border-border p-6 sm:p-8 rounded-2xl space-y-5">
        <div class="flex items-center gap-2 pb-4 border-b border-border"><?= icon_svg('Edit3', 'h-5 w-5 text-primary') ?><h3 class="font-bold">General Information</h3></div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="space-y-1"><label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Title</label><input type="text" name="title" class="w-full p-3.5 rounded-xl bg-background border border-border" value="<?= e($editingProduct['name']) ?>" required></div>
          <div class="space-y-1"><label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Slug</label><input type="text" name="slug" class="w-full p-3.5 rounded-xl bg-background border border-border" value="<?= e($editingProduct['slug'] ?? '') ?>" required></div>
          <div class="space-y-1">
            <label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Category</label>
            <select name="category_id" class="w-full p-3.5 rounded-xl bg-background border border-border" required>
              <?php foreach (($categories ?? []) as $c): ?>
                <option value="<?= e((string) ($c['id'] ?? '')) ?>" <?= ($editingProduct['cat'] === $c['name']) ? 'selected' : '' ?>><?= e((string) ($c['name'] ?? '')) ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="space-y-1"><label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Price ($)</label><input type="number" step="0.01" name="price" class="w-full p-3.5 rounded-xl bg-background border border-border" value="<?= (float)str_replace(',', '', $editingProduct['price']) ?>" required></div>
          <div class="space-y-1"><label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Technical Requirements</label><input type="text" name="technical_info" class="w-full p-3.5 rounded-xl bg-background border border-border" value="<?= e($editingProduct['technical_info'] ?? 'PHP 8.1+ / WP 6.0+') ?>"></div>
          <div class="space-y-1"><label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Current Version</label><div class="w-full p-3.5 rounded-xl bg-secondary/50 border border-border text-muted-foreground font-mono">v<?= e($editingProduct['ver']) ?></div><input type="hidden" name="current_version" value="<?= e($editingProduct['ver']) ?>"></div>
        </div>
        <div class="space-y-1">
          <label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Description</label>
          <textarea name="description" rows="5" class="w-full p-3.5 rounded-xl bg-background border border-border"><?= e($editingProduct['desc'] ?? '') ?></textarea>
        </div>
        <div class="space-y-1">
          <label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Key Features (One per line)</label>
          <textarea name="key_features_text" rows="4" class="w-full p-3.5 rounded-xl bg-background border border-border font-mono text-xs"><?= e(implode("\n", $editingProduct['features'] ?? [])) ?></textarea>
        </div>
      </div>
      <div class="space-y-6">
        <div class="bg-card border border-border p-6 rounded-2xl space-y-4">
          <div class="flex items-center gap-2 pb-2 border-b border-border"><?= icon_svg('Settings', 'h-4 w-4 text-primary') ?><h3 class="font-bold text-sm">Status & Visibility</h3></div>
          <div class="flex items-center justify-between py-2">
            <span class="text-xs font-medium">Product Active</span>
            <label class="relative inline-flex items-center cursor-pointer">
              <input type="checkbox" name="is_active" value="1" class="sr-only peer" checked>
              <div class="w-11 h-6 bg-secondary peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
            </label>
          </div>

          <!-- Notify buyers section -->
          <div class="border-t border-border pt-4 space-y-3">
            <div class="flex items-center justify-between">
              <span class="text-xs font-medium">Notify Buyers</span>
              <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" name="notify_buyers" value="1" id="notify_buyers_toggle" class="sr-only peer"
                       onchange="document.getElementById('change_note_wrap').classList.toggle('hidden', !this.checked)">
                <div class="w-11 h-6 bg-secondary peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary"></div>
              </label>
            </div>
            <p class="text-[10px] text-muted-foreground">Send a <code class="text-primary">product_updated</code> email to everyone who purchased this product.</p>
            <div id="change_note_wrap" class="hidden space-y-1.5">
              <label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Change note (shown in email)</label>
              <textarea name="change_note" rows="3"
                        placeholder="e.g. Improved performance, fixed checkout bug, updated pricing…"
                        class="w-full p-3 rounded-xl bg-background border border-border text-xs font-mono focus:outline-none focus:border-primary transition-colors"></textarea>
            </div>
          </div>

          <button type="submit" class="w-full bg-ink text-ink-foreground py-4 rounded-xl font-bold shadow-ink hover:brightness-110 transition-all flex items-center justify-center gap-2"><?= icon_svg('Check', 'h-4 w-4') ?> Save Changes</button>
        </div>
        <div class="bg-destructive/5 border border-destructive/20 p-6 rounded-2xl space-y-4">
          <h3 class="font-bold text-sm text-destructive">Danger Zone</h3>
          <p class="text-[10px] text-muted-foreground">Deleting a product will remove it from the store and all user vaults immediately.</p>
          <button type="button" onclick="if(confirm('Are you absolutely sure? This cannot be undone.')) document.getElementById('delete-form').submit();" class="w-full bg-destructive text-white py-3 rounded-xl text-xs font-bold hover:bg-destructive/90 transition-all">Delete Product</button>
        </div>
      </div>
    </form>
    <form id="delete-form" method="post" action="/admin/products/delete">
      <input type="hidden" name="id" value="<?= $editingProduct['id'] ?>">
    </form>
  </div>

<?php else: ?>
  <!-- Product Manager List View -->
  <div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div><h2 class="font-display text-2xl font-bold tracking-tight">Product Manager</h2><p class="text-sm text-muted-foreground mt-0.5">Edit, update, or remove your marketplace assets.</p></div>
      <a href="/admin?tab=dashboard&view=add-product" class="h-11 px-6 rounded-xl bg-primary text-white font-bold flex items-center gap-2 shadow-glow hover:brightness-110 transition-all"><?= icon_svg('Plus', 'h-4 w-4') ?> Create New</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="bg-card border border-border p-5 rounded-2xl flex items-center gap-4">
        <div class="h-12 w-12 rounded-xl bg-primary/10 flex items-center justify-center text-primary"><?= icon_svg('Package', 'h-6 w-6') ?></div>
        <div><div class="text-2xl font-display font-bold"><?= count($products ?? []) ?></div><div class="text-[10px] text-muted-foreground uppercase tracking-widest font-bold">Total Products</div></div>
      </div>
      <div class="bg-card border border-border p-5 rounded-2xl flex items-center gap-4">
        <div class="h-12 w-12 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-500"><?= icon_svg('UploadCloud', 'h-6 w-6') ?></div>
        <div><div class="text-2xl font-display font-bold"><?= count($products ?? []) ?></div><div class="text-[10px] text-muted-foreground uppercase tracking-widest font-bold">Manageable Items</div></div>
      </div>
      <div class="bg-card border border-border p-5 rounded-2xl flex items-center gap-4">
        <div class="h-12 w-12 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-500"><?= icon_svg('TrendingUp', 'h-6 w-6') ?></div>
        <div><div class="text-2xl font-display font-bold">$<?= number_format(array_reduce($products ?? [], fn($s, $p) => $s + (float)str_replace(',', '', $p['price']), 0), 2) ?></div><div class="text-[10px] text-muted-foreground uppercase tracking-widest font-bold">Inventory Value</div></div>
      </div>
    </div>

    <div class="bg-card border border-border rounded-2xl overflow-hidden shadow-soft">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse min-w-[800px]">
          <thead>
            <tr class="bg-secondary/20 border-b border-border">
              <th class="py-4 px-6 text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Product Details</th>
              <th class="py-4 px-6 text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Price</th>
              <th class="py-4 px-6 text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Version</th>
              <th class="py-4 px-6 text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Last Update</th>
              <th class="py-4 px-6 text-[10px] font-bold uppercase tracking-widest text-muted-foreground text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-border/50">
            <?php foreach (($products ?? []) as $prod): ?>
              <tr class="hover:bg-secondary/10 transition-colors">
                <td class="py-4 px-6">
                  <div class="flex items-center gap-4">
                    <div class="h-11 w-11 rounded-xl bg-gradient-to-br <?= e((string) ($prod['tone'] ?? '')) ?> flex items-center justify-center font-display text-xl shadow-sm"><?= e((string) ($prod['letter'] ?? 'P')) ?></div>
                    <div><div class="font-bold text-sm"><?= e((string) ($prod['name'] ?? '')) ?></div><div class="text-[10px] text-muted-foreground"><?= e((string) ($prod['cat'] ?? 'Uncategorized')) ?></div></div>
                  </div>
                </td>
                <td class="py-4 px-6"><div class="font-bold text-sm">$<?= e($prod['price']) ?></div></td>
                <td class="py-4 px-6"><span class="px-2 py-0.5 bg-primary/10 text-primary rounded-md text-[10px] font-bold font-mono">v<?= e($prod['ver']) ?></span></td>
                <td class="py-4 px-6 text-xs text-muted-foreground"><?= e($prod['lastUpdated']) ?></td>
                <td class="py-4 px-6 text-right">
                  <div class="flex items-center justify-end gap-2">
                    <a href="/admin?tab=updates&edit=<?= $prod['id'] ?>" class="h-9 px-4 rounded-lg bg-secondary hover:bg-primary hover:text-white text-[11px] font-bold transition-all flex items-center gap-2"><?= icon_svg('Edit3', 'h-3.5 w-3.5') ?> Edit</a>
                    <a href="/admin?tab=dashboard&view=upload-update&product_id=<?= $prod['id'] ?>" class="h-9 px-4 rounded-lg bg-secondary hover:bg-blue-500 hover:text-white text-[11px] font-bold transition-all flex items-center gap-2"><?= icon_svg('UploadCloud', 'h-3.5 w-3.5') ?> Update</a>
                  </div>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
<?php endif; ?>
