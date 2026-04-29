<?php require BASE_PATH . '/app/Views/partials/header.php'; ?>
<main class="min-h-screen bg-background text-foreground flex flex-col relative overflow-hidden">
  <!-- Decorative Background Elements -->
  <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-primary/10 blur-[150px] rounded-full pointer-events-none -translate-y-1/2 translate-x-1/3"></div>
  <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-blue-500/10 blur-[120px] rounded-full pointer-events-none translate-y-1/3 -translate-x-1/3"></div>
  <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI4IiBoZWlnaHQ9IjgiPjxyZWN0IHdpZHRoPSI4IiBoZWlnaHQ9IjgiIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wMiIvPjwvc3ZnPg==')] opacity-20 pointer-events-none"></div>

  <div class="flex-1 flex items-center justify-center p-4 py-24 relative z-10">
    <div class="w-full max-w-5xl bg-card/60 backdrop-blur-2xl border border-border/50 rounded-[2.5rem] shadow-elevated dark:shadow-glow overflow-hidden flex flex-col md:flex-row reveal-on-scroll reveal-scale">
      
      <!-- Left side: Login Form -->
      <div class="flex-1 p-8 md:p-14 relative">
        <div class="mb-10">
          <a href="/" class="inline-flex items-center gap-2 font-display text-xl tracking-tight mb-8 hover:opacity-80 transition-opacity">
            <?= site_logo_html('h-8 w-auto object-contain') ?>
            <?= e(site_config('site_name', 'PixelVault')) ?>
          </a>
          <h1 class="font-display text-4xl mb-3">Welcome back.</h1>
          <p class="text-muted-foreground">Sign in to access your vault, secure downloads, and account dashboard.</p>
        </div>

        <form method="post" action="/auth/login" class="space-y-5 relative z-10">
          <?php if (!empty($loginFailed)): ?>
            <div class="flex items-start gap-3 text-sm text-destructive bg-destructive/5 border border-destructive/20 p-4 rounded-2xl">
              <?= icon_svg('AlertCircle', 'h-5 w-5 flex-shrink-0 mt-0.5') ?>
              <div>
                <p class="font-bold mb-1">Authentication failed</p>
                <p class="opacity-90">Please check your email and password. Try <code class="bg-destructive/10 px-1 py-0.5 rounded">demo@pixelvault.app</code> / <code class="bg-destructive/10 px-1 py-0.5 rounded">password123</code>.</p>
              </div>
            </div>
          <?php endif; ?>

          <?php if (!empty($resetSuccess)): ?>
            <div class="flex items-start gap-3 text-sm text-emerald-500 bg-emerald-500/5 border border-emerald-500/20 p-4 rounded-2xl">
              <?= icon_svg('CheckCircle', 'h-5 w-5 flex-shrink-0 mt-0.5') ?>
              <div>
                <p class="font-bold mb-1">Password reset success</p>
                <p class="opacity-90">Your password has been updated. You can now sign in with your new password.</p>
              </div>
            </div>
          <?php endif; ?>

          <div class="space-y-2 group">
            <label class="text-[11px] font-bold uppercase tracking-[0.2em] text-muted-foreground ml-1 group-focus-within:text-primary transition-colors">Email Address</label>
            <div class="relative">
              <span class="absolute left-4 top-1/2 -translate-y-1/2 text-muted-foreground group-focus-within:text-primary transition-colors"><?= icon_svg('Mail', 'h-5 w-5') ?></span>
              <input type="email" name="email" class="w-full bg-background/50 border border-border rounded-2xl py-4 pl-12 pr-4 focus:bg-background focus:ring-2 focus:ring-primary/30 focus:border-primary/30 focus:outline-none transition-all placeholder:text-muted-foreground/50 text-foreground" placeholder="name@email.com" value="<?= e((string) old('email', '')) ?>" required />
            </div>
          </div>

          <div class="space-y-2 group">
            <div class="flex items-center justify-between ml-1">
              <label class="text-[11px] font-bold uppercase tracking-[0.2em] text-muted-foreground group-focus-within:text-primary transition-colors">Password</label>
              <a href="/forgot-password" class="text-xs text-primary hover:underline underline-offset-4">Forgot?</a>
            </div>
            <div class="relative">
              <span class="absolute left-4 top-1/2 -translate-y-1/2 text-muted-foreground group-focus-within:text-primary transition-colors"><?= icon_svg('Lock', 'h-5 w-5') ?></span>
              <input type="password" name="password" class="w-full bg-background/50 border border-border rounded-2xl py-4 pl-12 pr-4 focus:bg-background focus:ring-2 focus:ring-primary/30 focus:border-primary/30 focus:outline-none transition-all placeholder:text-muted-foreground/50 text-foreground" placeholder="••••••••" required />
            </div>
          </div>

          <button class="w-full relative group overflow-hidden bg-primary text-primary-foreground font-bold py-4 rounded-2xl shadow-[0_0_20px_rgba(249,115,22,0.3)] hover:shadow-[0_0_30px_rgba(249,115,22,0.5)] transition-all mt-4" type="submit">
            <span class="relative z-10 flex items-center justify-center gap-2">Sign In <?= icon_svg('ArrowRight', 'h-4 w-4 group-hover:translate-x-1 transition-transform') ?></span>
            <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
          </button>
        </form>

        <p class="mt-8 text-center text-sm text-muted-foreground">
          Don't have an account? <a href="/register" class="text-primary font-medium hover:underline underline-offset-4">Create one</a>
        </p>
      </div>

      <!-- Right side: Visual/Info -->
      <div class="hidden md:flex flex-1 bg-secondary/30 relative items-center justify-center p-16 border-l border-border/50 overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_center,_var(--tw-gradient-stops))] from-primary/10 via-transparent to-transparent"></div>
        
        <!-- Animated Background Orbs -->
        <div class="absolute top-1/4 right-1/4 w-64 h-64 bg-primary/20 blur-[100px] rounded-full animate-pulse"></div>
        <div class="absolute bottom-1/4 left-1/4 w-48 h-48 bg-blue-500/10 blur-[80px] rounded-full animate-pulse" style="animation-delay: 1s;"></div>

        <div class="relative z-10 w-full">
          <div class="space-y-12">
            <!-- Feature 1 -->
            <div class="flex gap-6 group">
              <div class="h-14 w-14 shrink-0 rounded-2xl bg-card border border-border flex items-center justify-center shadow-soft group-hover:scale-110 group-hover:border-primary/50 transition-all duration-500">
                <?= icon_svg('ShieldCheck', 'h-6 w-6 text-primary') ?>
              </div>
              <div>
                <h3 class="font-display text-xl mb-2 text-foreground group-hover:text-primary transition-colors">Advanced Vault Security</h3>
                <p class="text-sm text-muted-foreground leading-relaxed">Your digital assets are protected by industry-standard encryption and secure multi-factor authentication protocols.</p>
              </div>
            </div>

            <!-- Feature 2 -->
            <div class="flex gap-6 group">
              <div class="h-14 w-14 shrink-0 rounded-2xl bg-card border border-border flex items-center justify-center shadow-soft group-hover:scale-110 group-hover:border-primary/50 transition-all duration-500">
                <?= icon_svg('Zap', 'h-6 w-6 text-amber-500') ?>
              </div>
              <div>
                <h3 class="font-display text-xl mb-2 text-foreground group-hover:text-amber-500 transition-colors">Instant Update Access</h3>
                <p class="text-sm text-muted-foreground leading-relaxed">Never miss a critical patch. Get immediate notifications and one-click downloads for all your purchased premium products.</p>
              </div>
            </div>

            <!-- Feature 3 -->
            <div class="flex gap-6 group">
              <div class="h-14 w-14 shrink-0 rounded-2xl bg-card border border-border flex items-center justify-center shadow-soft group-hover:scale-110 group-hover:border-primary/50 transition-all duration-500">
                <?= icon_svg('Terminal', 'h-6 w-6 text-blue-500') ?>
              </div>
              <div>
                <h3 class="font-display text-xl mb-2 text-foreground group-hover:text-blue-500 transition-colors">Developer Ready</h3>
                <p class="text-sm text-muted-foreground leading-relaxed">Access documentation, API keys, and technical support resources designed specifically for modern web development workflows.</p>
              </div>
            </div>
          </div>

        </div>
      </div>

    </div>
  </div>
</main>
<?php require BASE_PATH . '/app/Views/partials/footer.php'; ?>