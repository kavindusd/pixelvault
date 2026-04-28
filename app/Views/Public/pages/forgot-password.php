<?php require BASE_PATH . '/app/Views/partials/header.php'; ?>
<main class="min-h-screen bg-background text-foreground flex flex-col relative overflow-hidden">
  <!-- Decorative Background Elements -->
  <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-primary/10 blur-[150px] rounded-full pointer-events-none -translate-y-1/2 translate-x-1/3"></div>
  <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-blue-500/10 blur-[120px] rounded-full pointer-events-none translate-y-1/3 -translate-x-1/3"></div>

  <div class="flex-1 flex items-center justify-center p-4 py-24 relative z-10">
    <div class="w-full max-w-md bg-card/60 backdrop-blur-2xl border border-border/50 rounded-[2.5rem] shadow-elevated p-8 md:p-12 reveal-on-scroll reveal-scale">
      <div class="mb-10 text-center">
        <div class="h-16 w-16 rounded-2xl bg-primary/10 text-primary flex items-center justify-center mx-auto mb-6 shadow-glow">
          <?= icon_svg('KeyRound', 'h-8 w-8') ?>
        </div>
        <h1 class="font-display text-3xl mb-3">Forgot Password?</h1>
        <p class="text-muted-foreground text-sm">Enter your email address and we'll send you a secure link to reset your password.</p>
      </div>

      <?php if ($status === 'sent'): ?>
        <div class="text-center space-y-6 py-4 animate-in fade-in zoom-in duration-500">
          <div class="h-12 w-12 rounded-full bg-emerald-500/20 text-emerald-500 flex items-center justify-center mx-auto">
            <?= icon_svg('Check', 'h-6 w-6') ?>
          </div>
          <div class="space-y-2">
            <h3 class="font-bold text-lg">Check your inbox</h3>
            <p class="text-sm text-muted-foreground leading-relaxed">If an account exists with that email, you will receive a reset link shortly.</p>
          </div>
          <a href="/login" class="inline-block w-full py-4 rounded-2xl bg-secondary text-foreground font-bold hover:bg-secondary/80 transition-all">Back to Login</a>
        </div>
      <?php else: ?>
        <form method="post" action="/auth/forgot-password" class="space-y-6">
          <?php if ($error === 'empty'): ?>
            <div class="flex items-center gap-3 text-xs text-destructive bg-destructive/5 border border-destructive/20 p-4 rounded-xl">
              <?= icon_svg('AlertCircle', 'h-4 w-4') ?>
              <span>Please enter your email address.</span>
            </div>
          <?php elseif ($error === 'expired'): ?>
            <div class="flex items-center gap-3 text-xs text-destructive bg-destructive/5 border border-destructive/20 p-4 rounded-xl">
              <?= icon_svg('AlertCircle', 'h-4 w-4') ?>
              <span>The reset link was invalid or has expired. Please request a new one.</span>
            </div>
          <?php endif; ?>

          <div class="space-y-2 group">
            <label class="text-[11px] font-bold uppercase tracking-[0.2em] text-muted-foreground ml-1 group-focus-within:text-primary transition-colors">Email Address</label>
            <div class="relative">
              <span class="absolute left-4 top-1/2 -translate-y-1/2 text-muted-foreground group-focus-within:text-primary transition-colors"><?= icon_svg('Mail', 'h-5 w-5') ?></span>
              <input type="email" name="email" class="w-full bg-background/50 border border-border rounded-2xl py-4 pl-12 pr-4 focus:bg-background focus:ring-2 focus:ring-primary/30 focus:border-primary/30 focus:outline-none transition-all placeholder:text-muted-foreground/50 text-foreground" placeholder="name@email.com" required />
            </div>
          </div>

          <button class="w-full relative group overflow-hidden bg-primary text-primary-foreground font-bold py-4 rounded-2xl shadow-glow hover:shadow-lg transition-all" type="submit">
            <span class="relative z-10 flex items-center justify-center gap-2">Send Reset Link <?= icon_svg('Send', 'h-4 w-4') ?></span>
            <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
          </button>
        </form>

        <div class="mt-8 text-center">
          <a href="/login" class="text-xs font-medium text-muted-foreground hover:text-primary transition-colors flex items-center justify-center gap-2">
            <?= icon_svg('ArrowRight', 'h-3 w-3 rotate-180') ?> Back to Sign In
          </a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</main>
<?php require BASE_PATH . '/app/Views/partials/footer.php'; ?>
