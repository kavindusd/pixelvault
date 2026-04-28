<?php require BASE_PATH . '/app/Views/partials/header.php'; ?>
<main class="min-h-screen bg-background text-foreground flex flex-col">
  <div class="pt-40 pb-20 container flex flex-col items-center text-center">
    <div class="h-24 w-24 rounded-full bg-success/10 text-success flex items-center justify-center mb-8 shadow-glow"><?= icon_svg('Check', 'h-12 w-12') ?></div>
    <h1 class="font-display text-6xl tracking-tight mb-4">Payment Successful!</h1>
    <p class="text-xl text-muted-foreground max-w-xl mx-auto leading-relaxed mb-10">Thank you for your purchase. Your digital products have been added to your vault and are ready for download.</p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
      <a href="/profile" class="px-8 py-4 bg-ink text-ink-foreground rounded-2xl font-bold">Go to My Vault</a>
      <a href="/marketplace" class="px-8 py-4 border border-border rounded-2xl font-bold hover:bg-secondary transition-all">Continue Shopping</a>
    </div>
  </div>
</main>
<?php require BASE_PATH . '/app/Views/partials/footer.php'; ?>
