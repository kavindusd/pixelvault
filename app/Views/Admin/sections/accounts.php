<div class="space-y-8">
  <div class="flex items-center justify-between">
    <div>
      <h2 class="text-3xl font-display tracking-tight">Admin Accounts</h2>
      <p class="text-muted-foreground">Manage administrator access and system roles.</p>
    </div>
  </div>

  <?php if (query('status') === 'success'): ?>
    <div class="bg-success/10 border border-success/20 text-success p-4 rounded-xl flex items-center gap-3 animate-scale-in">
      <?= icon_svg('CheckCircle', 'h-5 w-5') ?>
      <p class="text-sm font-medium">Administrator account created successfully.</p>
    </div>
  <?php elseif (query('status') === 'error'): ?>
    <div class="bg-destructive/10 border border-destructive/20 text-destructive p-4 rounded-xl flex items-center gap-3 animate-scale-in">
      <?= icon_svg('AlertCircle', 'h-5 w-5') ?>
      <p class="text-sm font-medium"><?= e(query('message', 'An error occurred.')) ?></p>
    </div>
  <?php endif; ?>

  <div class="grid lg:grid-cols-3 gap-8">
    <!-- Account Creation Form -->
    <div class="lg:col-span-1">
      <div class="bg-card border border-border rounded-2xl overflow-hidden shadow-soft sticky top-24">
        <div class="p-6 border-b border-border bg-secondary/30">
          <h3 class="font-display text-xl flex items-center gap-2">
            <?= icon_svg('UserPlus', 'h-5 w-5 text-primary') ?>
            Create Admin
          </h3>
        </div>
        <form action="/admin/accounts/create" method="POST" class="p-6 space-y-5">
          <div class="space-y-2">
            <label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground ml-1">Full Name</label>
            <input type="text" name="name" required placeholder="John Admin"
                   class="w-full bg-background border border-border rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
          </div>
          <div class="space-y-2">
            <label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground ml-1">Email Address</label>
            <input type="email" name="email" required placeholder="admin@pixelvault.app"
                   class="w-full bg-background border border-border rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
          </div>
          <div class="space-y-2">
            <label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground ml-1">Password</label>
            <input type="password" name="password" required minlength="6" placeholder="••••••••"
                   class="w-full bg-background border border-border rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
          </div>
          <div class="space-y-2">
            <label class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground ml-1">System Role</label>
            <select name="role" class="w-full bg-background border border-border rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all">
              <option value="Super Admin">Super Admin</option>
              <option value="Moderator">Moderator</option>
              <option value="Support">Support Agent</option>
            </select>
          </div>
          <button type="submit" class="w-full bg-primary text-primary-foreground font-bold py-3.5 rounded-xl shadow-glow hover:brightness-110 transition-all flex items-center justify-center gap-2">
            <?= icon_svg('ShieldCheck', 'h-4 w-4') ?>
            Provision Account
          </button>
        </form>
      </div>
    </div>

    <!-- Admin List -->
    <div class="lg:col-span-2">
      <div class="bg-card border border-border rounded-2xl overflow-hidden shadow-soft">
        <div class="p-6 border-b border-border flex items-center justify-between">
          <h3 class="font-display text-xl flex items-center gap-2">
            <?= icon_svg('Shield', 'h-5 w-5 text-primary') ?>
            Active Administrators
          </h3>
          <span class="text-xs font-mono bg-secondary px-2 py-1 rounded border border-border text-muted-foreground">
            Total: <?= count($admins ?? []) ?>
          </span>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-left">
            <thead>
              <tr class="bg-secondary/10 text-[10px] font-bold uppercase tracking-widest text-muted-foreground border-b border-border">
                <th class="px-6 py-4">Administrator</th>
                <th class="px-6 py-4">Role</th>
                <th class="px-6 py-4">Joined</th>
                <th class="px-6 py-4 text-right">Actions</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-border/50">
              <?php foreach ($admins ?? [] as $adm): 
                $isSelf = (int)$adm['id'] === (int)($admin['id'] ?? 0);
              ?>
                <tr class="hover:bg-primary/[0.01] transition-colors">
                  <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                      <div class="h-9 w-9 rounded-full bg-gradient-to-br from-zinc-100 to-zinc-200 dark:from-zinc-800 dark:to-zinc-900 border border-border flex items-center justify-center font-bold text-xs text-primary shadow-sm">
                        <?= strtoupper(substr($adm['name'], 0, 1)) ?>
                      </div>
                      <div>
                        <div class="font-bold text-sm flex items-center gap-2">
                          <?= e($adm['name']) ?>
                          <?php if ($isSelf): ?>
                            <span class="text-[9px] bg-primary/10 text-primary px-1.5 py-0.5 rounded-full border border-primary/20 font-mono">YOU</span>
                          <?php endif; ?>
                        </div>
                        <div class="text-xs text-muted-foreground"><?= e($adm['email']) ?></div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4">
                    <span class="text-[10px] font-bold px-2 py-1 rounded bg-secondary border border-border text-muted-foreground">
                      <?= e($adm['role']) ?>
                    </span>
                  </td>
                  <td class="px-6 py-4 text-xs text-muted-foreground">
                    <?= date('M d, Y', strtotime($adm['created_at'])) ?>
                  </td>
                  <td class="px-6 py-4 text-right">
                    <button class="h-8 w-8 rounded-lg hover:bg-secondary flex items-center justify-center text-muted-foreground transition-colors">
                      <?= icon_svg('MoreHorizontal', 'h-4 w-4') ?>
                    </button>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
