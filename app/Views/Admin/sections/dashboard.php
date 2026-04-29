<?php if (($subView ?? 'overview') === 'overview'): ?>
  <div class="grid grid-cols-2 sm:grid-cols-4 border border-border rounded-2xl overflow-hidden">
    <?php foreach (($adminStats ?? []) as $index => $kpi): ?>
      <div class="px-6 py-4 flex items-center gap-4 bg-card <?= $index < 3 ? 'border-r border-border' : '' ?>">
        <div class="h-9 w-9 rounded-xl bg-secondary flex items-center justify-center <?= e((string) ($kpi['color'] ?? '')) ?>"><?= icon_svg((string) ($kpi['icon'] ?? 'BarChart3'), 'h-4 w-4') ?></div>
        <div><div class="text-[10px] text-muted-foreground uppercase tracking-widest font-bold"><?= e((string) ($kpi['label'] ?? '')) ?></div><div class="text-lg font-bold font-display leading-tight"><?= e((string) ($kpi['value'] ?? '')) ?></div></div>
      </div>
    <?php endforeach; ?>
  </div>
  <div class="space-y-6">
    <div class="flex items-center justify-between">
      <div><h2 class="font-display text-2xl font-bold tracking-tight">Good morning, Admin</h2><p class="text-sm text-muted-foreground mt-0.5">Here's what's happening across your platform today.</p></div>
      <div class="hidden sm:flex items-center gap-2 text-xs text-muted-foreground bg-secondary/50 border border-border px-3 py-2 rounded-xl"><?= icon_svg('Clock', 'h-3.5 w-3.5') ?><span>Last sync: 4 min ago</span></div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
      <a href="/admin?tab=dashboard&view=add-product" class="col-span-1 sm:col-span-2 xl:col-span-2 rounded-2xl border border-border bg-gradient-to-br from-emerald-500/10 via-card to-card p-6 hover:border-emerald-500/40">
        <div class="h-12 w-12 rounded-2xl bg-emerald-500/15 flex items-center justify-center mb-5"><?= icon_svg('Plus', 'h-6 w-6 text-emerald-500') ?></div>
        <h3 class="font-bold text-lg mb-1">Add New Product</h3><p class="text-sm text-muted-foreground mb-5 max-w-xs">Draft and publish a new digital item to the marketplace in minutes.</p><div class="text-emerald-500 text-xs font-bold">Open form</div>
      </a>
      <a href="/admin?tab=dashboard&view=upload-update" class="rounded-2xl border border-border bg-gradient-to-br from-blue-500/10 via-card to-card p-6 hover:border-blue-500/40"><div class="h-12 w-12 rounded-2xl bg-blue-500/15 flex items-center justify-center mb-5"><?= icon_svg('UploadCloud', 'h-6 w-6 text-blue-500') ?></div><h3 class="font-bold text-base mb-1">Upload Update</h3><p class="text-xs text-muted-foreground mb-4">Push a new version to an existing product.</p></a>
      <a href="/admin?tab=orders" class="rounded-2xl border border-border bg-gradient-to-br from-purple-500/10 via-card to-card p-6 hover:border-purple-500/40"><div class="h-12 w-12 rounded-2xl bg-purple-500/15 flex items-center justify-center mb-5"><?= icon_svg('Activity', 'h-6 w-6 text-purple-500') ?></div><h3 class="font-bold text-base mb-1">Recent Orders</h3><p class="text-xs text-muted-foreground mb-4">View the latest transactions and revenue.</p></a>
      <div class="col-span-1 sm:col-span-2 xl:col-span-3 rounded-2xl border border-border bg-card p-6">
        <div class="flex items-center justify-between mb-5"><div><h3 class="font-bold text-base">Revenue Overview</h3><p class="text-xs text-muted-foreground mt-0.5">Monthly gross revenue - current quarter</p></div></div>
        <div class="flex items-end gap-1.5 h-24"><?php foreach ([40,65,50,80,70,95,85,100,88,72,90,78] as $i => $h): ?><div class="flex-1 rounded-t-lg" style="height: <?= $h ?>%; background: <?= $i === 7 ? 'linear-gradient(to top, hsl(18 95% 58%), hsl(36 100% 65%))' : 'hsl(var(--secondary))' ?>"></div><?php endforeach; ?></div>
      </div>
      <div class="col-span-1 rounded-2xl border border-border bg-card p-6 space-y-4">
        <div class="flex items-center justify-between mb-1"><h3 class="font-bold text-base">Platform</h3><?= icon_svg('BarChart3', 'h-4 w-4 text-muted-foreground') ?></div>
        <?php foreach (($platformStats ?? []) as $stat): ?><div><div class="flex items-center justify-between mb-1"><span class="text-xs text-muted-foreground"><?= e((string) ($stat['label'] ?? '')) ?></span><span class="text-xs font-bold"><?= e((string) ($stat['value'] ?? '')) ?></span></div><div class="h-1.5 rounded-full bg-secondary overflow-hidden"><div class="h-full rounded-full <?= e((string) ($stat['color'] ?? 'bg-blue-500')) ?>" style="width: <?= e((string) ($stat['pct'] ?? 0)) ?>%"></div></div></div><?php endforeach; ?>
      </div>
    </div>
    <div class="rounded-2xl border border-border bg-card overflow-hidden">
      <div class="flex items-center justify-between px-6 py-4 border-b border-border"><div class="flex items-center gap-2"><?= icon_svg('Sparkles', 'h-4 w-4 text-primary') ?><h3 class="font-bold text-sm">Recent System Activity</h3></div><a href="/admin?tab=orders" class="text-xs text-primary font-bold">View all</a></div>
      <div class="divide-y divide-border/50"><?php foreach (($systemActivity ?? []) as $item): ?><div class="flex items-center gap-4 px-6 py-3.5 hover:bg-secondary/20 transition-colors"><div class="h-8 w-8 rounded-full bg-gradient-to-br from-orange-400 to-amber-300 flex items-center justify-center text-white text-[11px] font-bold flex-shrink-0"><?= e(substr((string) ($item['user'] ?? 'U'), 0, 1)) ?></div><div class="flex-1 min-w-0"><div class="text-sm font-bold truncate"><span class="text-foreground"><?= e((string) ($item['user'] ?? '')) ?></span><span class="text-muted-foreground font-normal"> <?= e((string) ($item['action'] ?? '')) ?> </span><span class="text-foreground"><?= e((string) ($item['product'] ?? '')) ?></span></div><div class="text-[10px] text-muted-foreground mt-0.5"><?= e((string) ($item['time'] ?? '')) ?></div></div><div class="text-sm font-bold font-display"><?= e((string) ($item['amount'] ?? '')) ?></div></div><?php endforeach; ?></div>
    </div>
  </div>
<?php elseif (($subView ?? 'overview') === 'add-product'): ?>
  <div class="space-y-6">
    <div class="flex items-center gap-4">
      <a href="/admin?tab=dashboard&view=overview" class="h-10 w-10 rounded-xl bg-secondary flex items-center justify-center text-muted-foreground"><?= icon_svg('LayoutDashboard', 'h-4 w-4') ?></a>
      <div><h2 class="font-display text-2xl font-bold tracking-tight">Add New Product</h2><p class="text-sm text-muted-foreground mt-0.5">Create a new digital item with complete database fields.</p></div>
    </div>
    <form method="post" action="/admin/products/create" enctype="multipart/form-data" class="grid lg:grid-cols-3 gap-6">
      <div class="lg:col-span-2 bg-card border border-border p-6 sm:p-8 rounded-2xl space-y-5">
        <div class="flex items-center gap-2 pb-4 border-b border-border"><?= icon_svg('FileText', 'h-5 w-5 text-primary') ?><h3 class="font-bold">Product Data</h3></div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
          <div class="space-y-1"><label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Title</label><input type="text" name="title" class="w-full p-3.5 rounded-xl bg-background border border-border" placeholder="Product title" required></div>
          <div class="space-y-1"><label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Slug</label><input type="text" name="slug" class="w-full p-3.5 rounded-xl bg-background border border-border" placeholder="product-slug" required></div>
          <div class="space-y-1"><label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Category</label><select name="category_id" class="w-full p-3.5 rounded-xl bg-background border border-border" required><?php foreach (($categories ?? []) as $c): ?><option value="<?= e((string) ($c['id'] ?? '')) ?>"><?= e((string) ($c['name'] ?? '')) ?></option><?php endforeach; ?></select></div>
          <div class="space-y-1"><label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">License Type</label><input type="text" name="license_type" class="w-full p-3.5 rounded-xl bg-background border border-border" value="GPLv3 - Unlimited Sites"></div>
          <div class="space-y-1"><label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Price</label><input type="number" step="0.01" name="price" class="w-full p-3.5 rounded-xl bg-background border border-border" placeholder="Price" required></div>
          <div class="space-y-1"><label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Discount Price</label><input type="number" step="0.01" name="discount_price" class="w-full p-3.5 rounded-xl bg-background border border-border" placeholder="Optional"></div>
          <div class="space-y-1"><label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Current Version</label><input type="text" name="current_version" class="w-full p-3.5 rounded-xl bg-background border border-border" placeholder="1.0.0" required></div>
          <div class="space-y-1"><label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Last Updated</label><input type="date" name="last_updated_at" class="w-full p-3.5 rounded-xl bg-background border border-border focus:border-primary outline-none transition-colors" value="<?= e(date('Y-m-d')) ?>" required></div>
          <div class="space-y-1"><label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Technical Requirements</label><input type="text" name="technical_info" class="w-full p-3.5 rounded-xl bg-background border border-border focus:border-primary outline-none transition-colors" placeholder="e.g. PHP 8.1+ / WP 6.0+" value="PHP 8.1+ / WP 6.0+"></div>
          <div class="space-y-1">
            <label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Product Image</label>
            <div class="relative group">
              <input type="file" name="image_file" accept=".png,.jpg,.jpeg,.webp,.svg" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="this.nextElementSibling.querySelector('.file-name').textContent = this.files[0]?.name || 'No file chosen'">
              <div class="flex items-center gap-3 w-full p-3.5 rounded-xl bg-secondary/30 border border-dashed border-border group-hover:border-primary/50 transition-all">
                <div class="h-8 w-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-all"><?= icon_svg('Image', 'h-4 w-4') ?></div>
                <span class="file-name text-xs text-muted-foreground truncate">Choose an image...</span>
              </div>
            </div>
            <p class="text-[10px] text-muted-foreground">Allowed: .png, .jpg, .webp, .svg</p>
          </div>
          <div class="space-y-1">
            <label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Demo URL</label>
            <input type="text" name="demo_url" class="w-full p-3.5 rounded-xl bg-background border border-border" placeholder="https://demo.example.com">
          </div>
          <div class="space-y-1 sm:col-span-2">
            <label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Product ZIP</label>
            <div class="relative group">
              <input type="file" name="product_file" accept=".zip" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required onchange="this.nextElementSibling.querySelector('.file-name').textContent = this.files[0]?.name || 'No file chosen'">
              <div class="flex items-center gap-4 w-full p-6 rounded-2xl bg-secondary/20 border-2 border-dashed border-border group-hover:border-primary/50 transition-all">
                <div class="h-12 w-12 rounded-2xl bg-primary text-white flex items-center justify-center shadow-glow group-hover:scale-105 transition-all"><?= icon_svg('Upload', 'h-6 w-6') ?></div>
                <div>
                  <div class="file-name text-sm font-bold truncate">Click or drag to upload ZIP</div>
                  <div class="text-[10px] text-muted-foreground uppercase tracking-widest mt-1">Maximum 512MB · GPL Ready</div>
                </div>
              </div>
            </div>
            <div class="flex items-center gap-2 mt-2">
              <span class="flex h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
              <p class="text-[10px] text-muted-foreground font-medium">Files are automatically sanitized and secured upon upload.</p>
            </div>
          </div>
          <div class="space-y-1">
            <label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">File Size (bytes)</label>
            <input type="number" name="file_size_bytes" class="w-full p-3.5 rounded-xl bg-background border border-border" placeholder="Optional">
          </div>
          <label class="flex items-center gap-2 text-sm text-muted-foreground"><input type="checkbox" name="is_active" checked> Active product</label>
        </div>
        <div class="space-y-1"><label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Short Description</label><input type="text" name="short_description" class="w-full p-3.5 rounded-xl bg-background border border-border" placeholder="Short description (280 chars)"></div>
        <div class="space-y-1"><label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Full Description</label><textarea rows="4" name="description" class="w-full p-3.5 rounded-xl bg-background border border-border" placeholder="Full description" required></textarea></div>
        <div class="space-y-1"><label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Key Features (one per line)</label><textarea rows="4" name="key_features_text" class="w-full p-3.5 rounded-xl bg-background border border-border" placeholder="Feature 1&#10;Feature 2"></textarea></div>
        <div class="space-y-1"><label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Initial Changelog</label><textarea rows="3" name="changelog" class="w-full p-3.5 rounded-xl bg-background border border-border" placeholder="Initial changelog"></textarea></div>
      </div>
      <div class="space-y-6">
        <div class="bg-card border border-border p-6 rounded-2xl">
          <h4 class="font-bold mb-2">Schema Coverage</h4>
          <p class="text-xs text-muted-foreground">This form maps the `products` columns and creates initial `product_versions` record.</p>
        </div>
        <button class="w-full py-4 rounded-xl bg-primary text-primary-foreground font-bold shadow-glow" type="submit">Create Product</button>
      </div>
    </form>
  </div>
<?php else: ?>
  <div class="space-y-6">
    <div class="flex items-center gap-4"><a href="/admin?tab=dashboard&view=overview" class="h-10 w-10 rounded-xl bg-secondary flex items-center justify-center text-muted-foreground"><?= icon_svg('LayoutDashboard', 'h-4 w-4') ?></a><div><h2 class="font-display text-2xl font-bold tracking-tight">Upload Update</h2><p class="text-sm text-muted-foreground mt-0.5">Add product version and optionally set as current.</p></div></div>
    <form method="post" action="/admin/products/upload-update" enctype="multipart/form-data" class="bg-card border border-border rounded-2xl p-6 space-y-4">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="space-y-1"><label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Product</label><select name="product_id" class="w-full p-3.5 rounded-xl bg-background border border-border" required><?php foreach (($products ?? []) as $p): ?><option value="<?= e((string) ($p['id'] ?? '')) ?>" <?= (int)query('product_id') === (int)$p['id'] ? 'selected' : '' ?>><?= e((string) ($p['name'] ?? '')) ?> (v<?= e((string) ($p['ver'] ?? '')) ?>)</option><?php endforeach; ?></select></div>
        <div class="space-y-1"><label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">New Version</label><input type="text" name="version" class="w-full p-3.5 rounded-xl bg-background border border-border" placeholder="e.g. 3.0.1" required></div>
        <div class="space-y-1 md:col-span-2">
          <label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Update ZIP File</label>
          <div class="relative group">
            <input type="file" name="update_file" accept=".zip" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required onchange="this.nextElementSibling.querySelector('.file-name').textContent = this.files[0]?.name || 'No file chosen'">
            <div class="flex items-center gap-4 w-full p-6 rounded-2xl bg-blue-500/10 border-2 border-dashed border-blue-500/30 group-hover:border-blue-500 transition-all">
              <div class="h-12 w-12 rounded-2xl bg-blue-500 text-white flex items-center justify-center shadow-[0_0_20px_rgba(59,130,246,0.3)] group-hover:scale-105 transition-all"><?= icon_svg('Package', 'h-6 w-6') ?></div>
              <div>
                <div class="file-name text-sm font-bold truncate text-blue-600">Select version update file...</div>
                <div class="text-[10px] text-blue-500 uppercase tracking-widest mt-1">New version ZIP · Sanitized</div>
              </div>
            </div>
          </div>
          <div class="flex items-center gap-2 mt-2">
            <span class="flex h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
            <p class="text-[10px] text-muted-foreground font-medium">Files are scanned and secured on arrival.</p>
          </div>
        </div>
        <div class="space-y-1"><label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">File Size (bytes)</label><input type="number" name="file_size_bytes" class="w-full p-3.5 rounded-xl bg-background border border-border" placeholder="Optional"></div>
        <label class="flex items-center gap-2 text-sm text-muted-foreground"><input type="checkbox" name="set_current" checked> Set as current product version</label>
      </div>
      <div class="space-y-1"><label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground">Changelog</label><textarea rows="4" name="changelog" class="w-full p-3.5 rounded-xl bg-background border border-border" placeholder="Changelog details"></textarea></div>
      <button class="w-full md:w-auto px-6 py-3 rounded-xl bg-blue-500 text-white font-bold" type="submit">Upload Version</button>
    </form>
  </div>
<?php endif; ?>
