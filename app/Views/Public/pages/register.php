<?php require BASE_PATH . '/app/Views/partials/header.php'; ?>
<main class="min-h-screen bg-background text-foreground flex flex-col relative overflow-hidden">
  <!-- Decorative Background Elements -->
  <div class="absolute top-0 left-0 w-[800px] h-[800px] bg-primary/10 blur-[150px] rounded-full pointer-events-none -translate-y-1/2 -translate-x-1/3"></div>
  <div class="absolute bottom-0 right-0 w-[600px] h-[600px] bg-blue-500/10 blur-[120px] rounded-full pointer-events-none translate-y-1/3 translate-x-1/3"></div>
  <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSI4IiBoZWlnaHQ9IjgiPjxyZWN0IHdpZHRoPSI4IiBoZWlnaHQ9IjgiIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4wMiIvPjwvc3ZnPg==')] opacity-20 pointer-events-none"></div>

  <div class="flex-1 flex items-center justify-center p-4 py-24 relative z-10">
    <div class="w-full max-w-4xl bg-card/60 backdrop-blur-2xl border border-border/50 rounded-[2.5rem] shadow-elevated dark:shadow-glow overflow-hidden reveal-on-scroll reveal-scale">
      
      <div class="p-8 md:p-14 relative">
        <div class="mb-10 text-center max-w-xl mx-auto">
          <a href="/" class="inline-flex items-center justify-center gap-2 font-display text-xl tracking-tight mb-8 hover:opacity-80 transition-opacity">
            <div class="h-8 w-8 rounded-lg bg-primary text-primary-foreground flex items-center justify-center shadow-glow">
              <span class="font-display font-black text-xl leading-none">P</span>
            </div>
            PixelVault
          </a>
          <h1 class="font-display text-4xl mb-3">Create your account.</h1>
          <p class="text-muted-foreground">Join thousands of developers and agencies building faster with PixelVault.</p>
        </div>

        <form method="post" action="/auth/register" class="space-y-10 relative z-10 max-w-2xl mx-auto">
          
          <?php if (!empty($registerError)): ?>
            <div class="flex items-start gap-3 text-sm text-destructive bg-destructive/5 border border-destructive/20 p-4 rounded-2xl">
              <?= icon_svg('AlertCircle', 'h-5 w-5 flex-shrink-0 mt-0.5') ?>
              <div>
                <p class="font-bold mb-1">Registration failed</p>
                <p class="opacity-90"><?= e($registerError) ?></p>
              </div>
            </div>
          <?php endif; ?>

          <!-- Avatar Selection Section -->
          <div class="space-y-6">
            <div class="flex items-center gap-4 mb-6">
              <div class="h-8 w-8 rounded-full bg-primary/20 text-primary flex items-center justify-center font-bold font-mono text-sm">1</div>
              <h2 class="font-display text-2xl">Pick your avatar</h2>
              <div class="flex-1 h-px bg-border"></div>
            </div>

            <input type="hidden" name="avatar_url" id="avatar_url" value="https://api.dicebear.com/7.x/adventurer/svg?seed=Felix" />
            <div class="grid grid-cols-4 md:grid-cols-8 gap-4">
              <?php
              $seeds = ['Felix', 'Aneka', 'Mason', 'Lilly', 'Jack', 'Mia', 'Aiden', 'Sofia'];
              foreach ($seeds as $seed):
                $url = "https://api.dicebear.com/7.x/adventurer/svg?seed=$seed";
              ?>
                <button type="button" 
                        onclick="selectAvatar('<?= $url ?>', this)"
                        class="avatar-option relative aspect-square rounded-2xl border-2 border-transparent bg-secondary/30 hover:bg-secondary/50 transition-all overflow-hidden group <?= $seed === 'Felix' ? 'border-primary shadow-glow' : '' ?>">
                  <img src="<?= $url ?>" alt="Avatar <?= $seed ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform" />
                  <div class="absolute inset-0 bg-primary/20 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                </button>
              <?php endforeach; ?>
            </div>
            <script>
              function selectAvatar(url, el) {
                document.getElementById('avatar_url').value = url;
                document.querySelectorAll('.avatar-option').forEach(opt => {
                  opt.classList.remove('border-primary', 'shadow-glow');
                });
                el.classList.add('border-primary', 'shadow-glow');
              }
            </script>
          </div>

          <!-- Personal Information Section -->
          <div class="space-y-5">
            <div class="flex items-center gap-4 mb-6">
              <div class="h-8 w-8 rounded-full bg-primary/20 text-primary flex items-center justify-center font-bold font-mono text-sm">2</div>
              <h2 class="font-display text-2xl">Personal Info</h2>
              <div class="flex-1 h-px bg-border"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              <div class="space-y-2 group">
                <label class="text-[11px] font-bold uppercase tracking-[0.2em] text-muted-foreground ml-1 group-focus-within:text-primary transition-colors">Full Name</label>
                <div class="relative">
                  <span class="absolute left-4 top-1/2 -translate-y-1/2 text-muted-foreground group-focus-within:text-primary transition-colors"><?= icon_svg('User', 'h-5 w-5') ?></span>
                  <input type="text" name="name" class="w-full bg-background/50 border border-border rounded-2xl py-4 pl-12 pr-4 focus:bg-background focus:ring-2 focus:ring-primary/30 focus:border-primary/30 focus:outline-none transition-all placeholder:text-muted-foreground/50 text-foreground" placeholder="John Doe" required />
                </div>
              </div>

              <div class="space-y-2 group">
                <label class="text-[11px] font-bold uppercase tracking-[0.2em] text-muted-foreground ml-1 group-focus-within:text-primary transition-colors">Email Address</label>
                <div class="relative">
                  <span class="absolute left-4 top-1/2 -translate-y-1/2 text-muted-foreground group-focus-within:text-primary transition-colors"><?= icon_svg('Mail', 'h-5 w-5') ?></span>
                  <input type="email" name="email" class="w-full bg-background/50 border border-border rounded-2xl py-4 pl-12 pr-4 focus:bg-background focus:ring-2 focus:ring-primary/30 focus:border-primary/30 focus:outline-none transition-all placeholder:text-muted-foreground/50 text-foreground" placeholder="name@email.com" required />
                </div>
              </div>
            </div>

            <div class="space-y-2 group">
              <label class="text-[11px] font-bold uppercase tracking-[0.2em] text-muted-foreground ml-1 group-focus-within:text-primary transition-colors">Password</label>
              <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-muted-foreground group-focus-within:text-primary transition-colors"><?= icon_svg('Lock', 'h-5 w-5') ?></span>
                <input type="password" name="password" class="w-full bg-background/50 border border-border rounded-2xl py-4 pl-12 pr-4 focus:bg-background focus:ring-2 focus:ring-primary/30 focus:border-primary/30 focus:outline-none transition-all placeholder:text-muted-foreground/50 text-foreground" placeholder="••••••••" required minlength="6" />
              </div>
            </div>
          </div>

          <!-- Billing Address Section -->
          <div class="space-y-5">
            <div class="flex items-center gap-4 mb-6">
              <div class="h-8 w-8 rounded-full bg-primary/20 text-primary flex items-center justify-center font-bold font-mono text-sm">3</div>
              <h2 class="font-display text-2xl">Billing Address</h2>
              <div class="flex-1 h-px bg-border"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
              <div class="space-y-2 group">
                <label class="text-[11px] font-bold uppercase tracking-[0.2em] text-muted-foreground ml-1 group-focus-within:text-primary transition-colors">Country</label>
                <div class="relative">
                  <span class="absolute left-4 top-1/2 -translate-y-1/2 text-muted-foreground group-focus-within:text-primary transition-colors"><?= icon_svg('MapPin', 'h-5 w-5') ?></span>
                  <!-- Simple text input with datalist for country picking/typing -->
                  <input type="text" list="countries" name="country" class="w-full bg-background/50 border border-border rounded-2xl py-4 pl-12 pr-4 focus:bg-background focus:ring-2 focus:ring-primary/30 focus:border-primary/30 focus:outline-none transition-all placeholder:text-muted-foreground/50 text-foreground" placeholder="Select or type country..." required />
                  <datalist id="countries">
                    <option value="United States">
                    <option value="United Kingdom">
                    <option value="Canada">
                    <option value="Australia">
                    <option value="Germany">
                    <option value="France">
                    <option value="India">
                  </datalist>
                </div>
              </div>

              <div class="space-y-2 group">
                <label class="text-[11px] font-bold uppercase tracking-[0.2em] text-muted-foreground ml-1 group-focus-within:text-primary transition-colors">City</label>
                <div class="relative">
                  <input type="text" name="city" class="w-full bg-background/50 border border-border rounded-2xl py-4 px-4 focus:bg-background focus:ring-2 focus:ring-primary/30 focus:border-primary/30 focus:outline-none transition-all placeholder:text-muted-foreground/50 text-foreground" placeholder="New York" required />
                </div>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
              <div class="md:col-span-2 space-y-2 group">
                <label class="text-[11px] font-bold uppercase tracking-[0.2em] text-muted-foreground ml-1 group-focus-within:text-primary transition-colors">Apartment, suite, etc.</label>
                <div class="relative">
                  <input type="text" name="apartment" class="w-full bg-background/50 border border-border rounded-2xl py-4 px-4 focus:bg-background focus:ring-2 focus:ring-primary/30 focus:border-primary/30 focus:outline-none transition-all placeholder:text-muted-foreground/50 text-foreground" placeholder="Apt 4B" required />
                </div>
              </div>

              <div class="space-y-2 group">
                <label class="text-[11px] font-bold uppercase tracking-[0.2em] text-muted-foreground ml-1 group-focus-within:text-primary transition-colors">Postal Code</label>
                <div class="relative">
                  <input type="text" name="postal_code" class="w-full bg-background/50 border border-border rounded-2xl py-4 px-4 focus:bg-background focus:ring-2 focus:ring-primary/30 focus:border-primary/30 focus:outline-none transition-all placeholder:text-muted-foreground/50 text-foreground" placeholder="10001" required />
                </div>
              </div>
            </div>
          </div>

          <!-- Payment Info Section (Optional) -->
          <div class="space-y-5">
            <div class="flex items-center gap-4 mb-6">
              <div class="h-8 w-8 rounded-full bg-secondary text-foreground flex items-center justify-center font-bold font-mono text-sm border border-border">3</div>
              <h2 class="font-display text-2xl flex items-center gap-3">Payment Info <span class="text-xs font-mono uppercase tracking-widest text-muted-foreground bg-secondary px-2 py-1 rounded-md border border-border">(Optional)</span></h2>
              <div class="flex-1 h-px bg-border"></div>
            </div>

            <div class="p-6 rounded-2xl border border-border/50 bg-secondary/10 space-y-5">
              <div class="space-y-2 group">
                <label class="text-[11px] font-bold uppercase tracking-[0.2em] text-muted-foreground ml-1 group-focus-within:text-primary transition-colors">Card Number</label>
                <div class="relative">
                  <span class="absolute left-4 top-1/2 -translate-y-1/2 text-muted-foreground group-focus-within:text-primary transition-colors"><?= icon_svg('CreditCard', 'h-5 w-5') ?></span>
                  <input type="text" name="card_number" class="w-full bg-background/50 border border-border rounded-2xl py-4 pl-12 pr-4 focus:bg-background focus:ring-2 focus:ring-primary/30 focus:border-primary/30 focus:outline-none transition-all placeholder:text-muted-foreground/50 text-foreground font-mono" placeholder="0000 0000 0000 0000" maxlength="19" />
                </div>
              </div>

              <div class="grid grid-cols-2 gap-5">
                <div class="space-y-2 group">
                  <label class="text-[11px] font-bold uppercase tracking-[0.2em] text-muted-foreground ml-1 group-focus-within:text-primary transition-colors">Expiry Date</label>
                  <div class="relative">
                    <input type="text" name="card_expiry" class="w-full bg-background/50 border border-border rounded-2xl py-4 px-4 focus:bg-background focus:ring-2 focus:ring-primary/30 focus:border-primary/30 focus:outline-none transition-all placeholder:text-muted-foreground/50 text-foreground font-mono" placeholder="MM/YY" maxlength="5" />
                  </div>
                </div>

                <div class="space-y-2 group">
                  <label class="text-[11px] font-bold uppercase tracking-[0.2em] text-muted-foreground ml-1 group-focus-within:text-primary transition-colors">CVC</label>
                  <div class="relative">
                    <input type="text" name="card_cvc" class="w-full bg-background/50 border border-border rounded-2xl py-4 px-4 focus:bg-background focus:ring-2 focus:ring-primary/30 focus:border-primary/30 focus:outline-none transition-all placeholder:text-muted-foreground/50 text-foreground font-mono" placeholder="123" maxlength="4" />
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="pt-6">
            <button class="w-full relative group overflow-hidden bg-primary text-primary-foreground font-bold py-5 rounded-2xl shadow-[0_0_20px_rgba(249,115,22,0.3)] hover:shadow-[0_0_30px_rgba(249,115,22,0.5)] transition-all" type="submit">
              <span class="relative z-10 flex items-center justify-center gap-2 text-lg">Create Account <?= icon_svg('ArrowRight', 'h-5 w-5 group-hover:translate-x-1 transition-transform') ?></span>
              <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
            </button>
            <p class="mt-6 text-center text-sm text-muted-foreground">
              Already have an account? <a href="/login" class="text-primary font-medium hover:underline underline-offset-4">Sign in</a>
            </p>
          </div>
          
        </form>
      </div>

    </div>
  </div>
</main>
<?php require BASE_PATH . '/app/Views/partials/footer.php'; ?>
