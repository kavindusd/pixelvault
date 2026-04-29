<?php require BASE_PATH . '/app/Views/partials/header.php'; ?>
<main class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden bg-background">
  <div class="absolute inset-x-0 top-0 h-[600px] bg-gradient-glow" aria-hidden></div>
  <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-[700px] h-[400px] glow-mesh opacity-60" aria-hidden></div>

  <div class="flex-1 flex items-center justify-center p-4 py-24 relative z-10">
    <div class="w-full max-w-5xl bg-card/60 backdrop-blur-2xl border border-border/50 rounded-[2.5rem] shadow-elevated dark:shadow-glow overflow-hidden flex flex-col md:flex-row reveal-on-scroll reveal-scale">
      
      <!-- Left side: Admin Login Form -->
      <div class="flex-1 p-8 md:p-14 relative flex flex-col justify-center">
        <div class="absolute top-0 inset-x-0 h-[4px] bg-gradient-to-r from-primary via-amber-500 to-primary shadow-glow"></div>
        
        <div class="mb-10">
          <h2 class="font-display text-[10px] uppercase tracking-[0.4em] text-primary font-bold mb-4">Central Intelligence</h2>
          <div class="relative inline-block mb-10">
            <span class="font-display text-5xl md:text-6xl font-black tracking-tighter text-foreground leading-none uppercase">PIXEL<span class="text-primary">VAULT</span></span>
            <div class="absolute -bottom-2 left-0 w-12 h-1.5 bg-primary rounded-full"></div>
          </div>
          <h1 class="font-display text-4xl mb-4 font-bold tracking-tight">System Access.</h1>
          <p class="text-muted-foreground leading-relaxed text-sm">Enter your administrative credentials to access the central management console and system configurations.</p>
        </div>

        <form method="post" action="/auth/login" class="space-y-6 relative z-10">
          <input type="hidden" name="return_to" value="/admin">
          
          <?php if (query('login') === 'failed'): ?>
            <div class="flex items-start gap-3 text-sm text-destructive bg-destructive/5 border border-destructive/20 p-4 rounded-2xl animate-scale-in">
              <?= icon_svg('ShieldAlert', 'h-5 w-5 flex-shrink-0 mt-0.5') ?>
              <div>
                <p class="font-bold mb-1">Access Denied</p>
                <p class="opacity-90">Invalid security credentials. Please verify your administrator email and key.</p>
              </div>
            </div>
          <?php endif; ?>

          <div class="space-y-2 group">
            <label class="text-[11px] font-bold uppercase tracking-[0.2em] text-muted-foreground ml-1 group-focus-within:text-primary transition-colors">Administrator Email</label>
            <div class="relative">
              <span class="absolute left-4 top-1/2 -translate-y-1/2 text-muted-foreground group-focus-within:text-primary transition-colors"><?= icon_svg('Mail', 'h-5 w-5') ?></span>
              <input type="email" name="email" class="w-full bg-background/50 border border-border rounded-2xl py-4 pl-12 pr-4 focus:bg-background focus:ring-2 focus:ring-primary/30 focus:border-primary/30 focus:outline-none transition-all placeholder:text-muted-foreground/50 text-foreground" placeholder="admin@pixelvault.app" required />
            </div>
          </div>

          <div class="space-y-2 group">
            <label class="text-[11px] font-bold uppercase tracking-[0.2em] text-muted-foreground ml-1 group-focus-within:text-primary transition-colors">Security Key</label>
            <div class="relative">
              <span class="absolute left-4 top-1/2 -translate-y-1/2 text-muted-foreground group-focus-within:text-primary transition-colors"><?= icon_svg('ShieldCheck', 'h-5 w-5') ?></span>
              <input type="password" name="password" class="w-full bg-background/50 border border-border rounded-2xl py-4 pl-12 pr-4 focus:bg-background focus:ring-2 focus:ring-primary/30 focus:border-primary/30 focus:outline-none transition-all placeholder:text-muted-foreground/50 text-foreground" placeholder="••••••••" required />
            </div>
          </div>

          <button class="w-full relative group overflow-hidden bg-primary text-primary-foreground font-bold py-4 rounded-2xl shadow-glow hover:brightness-110 transition-all mt-4" type="submit">
            <span class="relative z-10 flex items-center justify-center gap-2 font-display uppercase tracking-widest text-xs">Authorize Access <?= icon_svg('ArrowRight', 'h-4 w-4 group-hover:translate-x-1 transition-transform') ?></span>
            <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
          </button>
        </form>
      </div>

      <!-- Right side: Administrative Info -->
      <div class="hidden md:flex flex-1 bg-secondary/30 relative items-center justify-center p-16 border-l border-border/50 overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-primary/10 via-transparent to-transparent"></div>
        
        <div class="relative z-10 w-full space-y-10">
        <div class="relative z-10 w-full space-y-10">

          <div class="space-y-8">
            <div class="flex gap-5 group">
              <div class="h-12 w-12 shrink-0 rounded-xl bg-card border border-border flex items-center justify-center shadow-soft group-hover:border-primary/50 transition-colors">
                <?= icon_svg('Activity', 'h-5 w-5 text-primary') ?>
              </div>
              <div>
                <h3 class="font-display text-lg mb-1 text-foreground">Real-time Monitoring</h3>
                <p class="text-xs text-muted-foreground leading-relaxed">Oversee marketplace activity, order processing, and system health in real-time from a single dashboard.</p>
              </div>
            </div>

            <div class="flex gap-5 group">
              <div class="h-12 w-12 shrink-0 rounded-xl bg-card border border-border flex items-center justify-center shadow-soft group-hover:border-amber-500/50 transition-colors">
                <?= icon_svg('Package', 'h-5 w-5 text-amber-500') ?>
              </div>
              <div>
                <h3 class="font-display text-lg mb-1 text-foreground">Product Governance</h3>
                <p class="text-xs text-muted-foreground leading-relaxed">Manage the entire product lifecycle, from initial version uploads to license distribution and updates.</p>
              </div>
            </div>

            <div class="flex gap-5 group">
              <div class="h-12 w-12 shrink-0 rounded-xl bg-card border border-border flex items-center justify-center shadow-soft group-hover:border-blue-500/50 transition-colors">
                <?= icon_svg('Shield', 'h-5 w-5 text-blue-500') ?>
              </div>
              <div>
                <h3 class="font-display text-lg mb-1 text-foreground">Access Control</h3>
                <p class="text-xs text-muted-foreground leading-relaxed">Grant manual downloads, manage user roles, and maintain the integrity of the PixelVault ecosystem.</p>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</main>
