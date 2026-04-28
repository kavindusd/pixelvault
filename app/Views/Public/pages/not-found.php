<?php require BASE_PATH . '/app/Views/partials/header.php'; ?>
<main class="min-h-screen bg-background text-foreground flex flex-col">
  <div class="pt-40 pb-20 container flex flex-col items-center text-center">
    <div class="h-20 w-20 rounded-3xl bg-secondary border border-border flex items-center justify-center mb-8 rotate-12 shadow-soft">
      <span class="text-3xl text-muted-foreground">?</span>
    </div>
    <h1 class="font-display text-8xl md:text-[10rem] tracking-tighter mb-4 opacity-10">404</h1>
    <div class="-mt-20 md:-mt-32">
      <h2 class="font-display text-4xl mb-4">Lost in the Vault?</h2>
      <p class="text-muted-foreground max-w-md mx-auto leading-relaxed mb-10">The script or plugin you're looking for doesn't exist, or has been moved to a new digital directory.</p>
      <a href="/" class="inline-flex items-center gap-2 px-8 py-4 bg-ink text-ink-foreground rounded-2xl font-bold hover:brightness-110 shadow-ink transition-all">Go back Home</a>
    </div>
  </div>
</main>
<?php require BASE_PATH . '/app/Views/partials/footer.php'; ?>
