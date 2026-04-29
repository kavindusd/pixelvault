<div class="space-y-6 reveal-on-scroll">
  <div class="flex items-end justify-between gap-4">
    <div>
      <h2 class="font-display text-2xl font-bold tracking-tight">Manage Categories</h2>
      <p class="text-sm text-muted-foreground mt-0.5">Add, edit or remove product categories from your marketplace.</p>
    </div>
  </div>

  <form method="post" action="/admin/categories/create" class="bg-card border border-border rounded-2xl p-6 shadow-soft">
    <div class="grid md:grid-cols-4 gap-6 items-start">
      <div class="space-y-1.5">
        <label class="text-[10px] font-mono uppercase tracking-widest text-muted-foreground">Category Name</label>
        <input type="text" name="name" id="cat_name" required
               class="w-full px-4 py-3 rounded-md bg-background border border-border text-sm focus:outline-none focus:border-primary transition-colors"
               placeholder="e.g. Page Builders">
      </div>
      
      <div class="space-y-1.5">
        <label class="text-[10px] font-mono uppercase tracking-widest text-muted-foreground">Slug</label>
        <input type="text" name="slug" id="cat_slug" required
               class="w-full px-4 py-3 rounded-md bg-background border border-border text-sm focus:outline-none focus:border-primary transition-colors"
               placeholder="page-builders">
      </div>

      <div class="space-y-1.5">
        <label class="text-[10px] font-mono uppercase tracking-widest text-muted-foreground">Icon Selection</label>
        <div class="relative">
          <select name="icon" id="cat_icon" class="w-full px-4 py-3 rounded-md bg-background border border-border text-sm appearance-none focus:outline-none focus:border-primary transition-colors">
            <?php 
              $icons = ['Layout', 'ShoppingBag', 'Search', 'Palette', 'Shield', 'Zap', 'Star', 'Mail', 'Package', 'Clock', 'Globe', 'Layers', 'Tag', 'DollarSign', 'Smartphone', 'Monitor', 'KeyRound'];
              foreach ($icons as $ico): ?>
              <option value="<?= $ico ?>"><?= $ico ?></option>
            <?php endforeach; ?>
          </select>
          <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-muted-foreground">
             <?= icon_svg('ChevronRight', 'h-4 w-4 rotate-90') ?>
          </div>
        </div>
      </div>

      <div class="space-y-1.5">
        <label class="text-[10px] font-mono uppercase tracking-widest text-muted-foreground">Gradient Palette</label>
        <div class="relative">
          <select name="hue" class="w-full px-4 py-3 rounded-md bg-background border border-border text-sm appearance-none focus:outline-none focus:border-primary transition-colors">
            <option value="from-primary/20 to-primary/5">Vibrant Orange</option>
            <option value="from-blue-400/20 to-blue-500/5">Royal Blue</option>
            <option value="from-emerald-400/20 to-emerald-400/5">Forest Emerald</option>
            <option value="from-purple-400/20 to-purple-400/5">Deep Purple</option>
            <option value="from-pink-400/20 to-pink-400/5">Soft Pink</option>
            <option value="from-cyan-400/20 to-cyan-400/5">Arctic Cyan</option>
            <option value="from-red-400/20 to-red-400/5">Alert Red</option>
            <option value="from-amber-400/20 to-amber-500/10">Golden Amber</option>
          </select>
          <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-muted-foreground">
             <?= icon_svg('ChevronRight', 'h-4 w-4 rotate-90') ?>
          </div>
        </div>
      </div>

      <div class="md:col-span-4 space-y-1.5">
        <label class="text-[10px] font-mono uppercase tracking-widest text-muted-foreground">Description (optional)</label>
        <textarea name="description" rows="2"
                  class="w-full p-4 rounded-md bg-background border border-border text-sm focus:outline-none focus:border-primary transition-colors"
                  placeholder="Tell users what this category contains..."></textarea>
      </div>

      <div class="md:col-span-4 flex justify-end pt-2 border-t border-border">
        <button type="submit" class="inline-flex items-center gap-2 pv-btn-ink px-8 h-12 text-sm font-bold uppercase tracking-widest rounded-xl transition-all hover:scale-[1.02] active:scale-[0.98]">
          <?= icon_svg('Plus', 'h-4 w-4') ?>
          Add New Category
        </button>
      </div>
    </div>
  </form>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const nameInput = document.getElementById('cat_name');
      const slugInput = document.getElementById('cat_slug');
      const iconSelect = document.getElementById('cat_icon');

      const iconMap = {
        'shop': 'ShoppingBag', 'store': 'ShoppingBag', 'commerce': 'ShoppingBag',
        'build': 'Layout', 'page': 'Layout', 'design': 'Layout',
        'seo': 'Search', 'market': 'Search', 'analysis': 'Search',
        'theme': 'Palette', 'art': 'Palette', 'style': 'Palette',
        'secure': 'Shield', 'safety': 'Shield', 'protect': 'Shield',
        'speed': 'Zap', 'performance': 'Zap', 'fast': 'Zap',
        'mail': 'Mail', 'contact': 'Mail',
        'time': 'Clock', 'history': 'Clock',
        'web': 'Globe', 'world': 'Globe',
        'layer': 'Layers', 'stack': 'Layers',
        'code': 'Monitor', 'app': 'Monitor',
        'key': 'KeyRound', 'access': 'KeyRound'
      };

      nameInput?.addEventListener('input', (e) => {
        const val = e.target.value;
        if (!slugInput.value || slugInput.dataset.touched !== 'true') {
          slugInput.value = val.toLowerCase().replace(/[^a-z0-9]/g, '-').replace(/-+/g, '-').replace(/^-|-$/g, '');
        }

        // Suggest Icon
        const lowerVal = val.toLowerCase();
        for (const [key, icon] of Object.entries(iconMap)) {
          if (lowerVal.includes(key)) {
            iconSelect.value = icon;
            break;
          }
        }
      });

      slugInput?.addEventListener('input', () => slugInput.dataset.touched = 'true');
    });
  </script>
  <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
    <?php foreach (($categories ?? []) as $cat): ?>
      <div class="group bg-card border border-border p-5 rounded-2xl flex items-center gap-4 hover:border-primary/50 transition-all">
        <div class="h-10 w-10 rounded-xl bg-gradient-to-br <?= e((string) ($cat['hue'] ?? '')) ?> flex items-center justify-center"><?= icon_svg((string) ($cat['icon'] ?? 'Layout'), 'h-5 w-5 text-foreground') ?></div>
        <div class="flex-1 min-w-0">
          <div class="text-sm font-bold truncate"><?= e((string) ($cat['name'] ?? '')) ?></div>
          <div class="text-[10px] text-muted-foreground"><?= e((string) ($cat['count'] ?? '0')) ?> Items</div>
        </div>
        <form method="post" action="/admin/categories/delete">
          <input type="hidden" name="category_id" value="<?= e((string) ($cat['id'] ?? '')) ?>">
          <button class="h-8 w-8 rounded-lg bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition-all" type="submit" title="Delete category">×</button>
        </form>
      </div>
    <?php endforeach; ?>
  </div>
</div>
