<?php
$categories = $categoriesData['categories'] ?? [];
$platformLogos = $categoriesData['platformLogos'] ?? [];
require BASE_PATH . '/app/Views/partials/header.php';
?>
<main class="min-h-screen bg-background text-foreground flex flex-col overflow-x-hidden">
  <section class="relative pt-36 pb-24 overflow-hidden">
    <div class="absolute inset-0 grid-bg" aria-hidden></div>
    <div class="absolute inset-x-0 top-0 h-[600px] bg-gradient-glow opacity-80" aria-hidden></div>
    <div class="absolute -top-20 left-1/2 -translate-x-1/2 w-[1000px] h-[600px] glow-mesh opacity-40 dark:opacity-60" aria-hidden></div>
    <div class="absolute top-[20%] -left-[10%] w-[40%] h-[40%] bg-primary/5 blur-[120px] rounded-full" aria-hidden></div>
    <div class="absolute top-[60%] -right-[10%] w-[40%] h-[40%] bg-primary/5 blur-[120px] rounded-full" aria-hidden></div>
    <div class="container relative">
      <div class="grid lg:grid-cols-12 gap-12 items-center">
        <div class="lg:col-span-6 reveal-on-scroll reveal-slide-right">
          <style>
            @keyframes metallic-shimmer {
                0% { background-position: 200% center; }
                100% { background-position: -200% center; }
            }
            
            /* Light mode: Darker shimmering metal (gunmetal) */
            .metallic-headline {
                background: linear-gradient(
                    to right,
                    #52525b 0%,
                    #18181b 15%,
                    #71717a 25%,
                    #27272a 40%,
                    #18181b 50%,
                    #71717a 60%,
                    #18181b 85%,
                    #52525b 100%
                );
                background-size: 200% auto;
                color: transparent;
                -webkit-background-clip: text;
                background-clip: text;
                animation: metallic-shimmer 8s linear infinite;
                position: relative;
            }
            
            /* Dark mode: Lighter, polished silver */
            .dark .metallic-headline {
                background: linear-gradient(
                    to right,
                    #d4d4d8 0%,
                    #ffffff 15%,
                    #e4e4e7 25%,
                    #a1a1aa 40%,
                    #ffffff 50%,
                    #e4e4e7 60%,
                    #ffffff 85%,
                    #d4d4d8 100%
                );
                background-size: 200% auto;
                color: transparent;
                -webkit-background-clip: text;
                background-clip: text;
            }

            /* Light mode: subtle dark edge glow */
            .metallic-wrapper {
                position: relative;
                display: inline-block;
                filter: drop-shadow(0 0 2px rgba(0,0,0,0.1));
            }
            /* Dark mode: subtle light edge glow */
            .dark .metallic-wrapper {
                filter: drop-shadow(0 0 4px rgba(255,255,255,0.2));
            }

            @keyframes sparkle-move-1 {
                0% { transform: translate(0, 0) scale(0) rotate(0deg); opacity: 0; }
                15% { transform: translate(30px, -15px) scale(1) rotate(45deg); opacity: 0.8; }
                85% { transform: translate(calc(100% - 30px), 15px) scale(1) rotate(180deg); opacity: 0.8; }
                100% { transform: translate(100%, 0) scale(0) rotate(225deg); opacity: 0; }
            }
            @keyframes sparkle-move-2 {
                0% { transform: translate(100%, 100%) scale(0) rotate(0deg); opacity: 0; }
                15% { transform: translate(calc(100% - 30px), calc(100% + 15px)) scale(1) rotate(-45deg); opacity: 0.8; }
                85% { transform: translate(30px, calc(100% - 15px)) scale(1) rotate(-180deg); opacity: 0.8; }
                100% { transform: translate(0, 100%) scale(0) rotate(-225deg); opacity: 0; }
            }

            /* Sparkles using primary color for harmony */
            .metallic-wrapper::before,
            .metallic-wrapper::after {
                content: '✦';
                position: absolute;
                font-size: 20px;
                line-height: 1;
                color: hsl(var(--primary));
                filter: drop-shadow(0 0 4px hsl(var(--primary)));
                opacity: 0;
                pointer-events: none;
                z-index: 10;
            }
            .metallic-wrapper::before {
                top: 0; left: 0;
                animation: sparkle-move-1 6s infinite linear;
            }
            .metallic-wrapper::after {
                bottom: 0; right: 0;
                animation: sparkle-move-2 7s infinite linear;
            }
          </style>
          <div class="metallic-wrapper">
            <h1 class="mt-6 font-display text-[clamp(3rem,7vw,6rem)] leading-[0.95] tracking-tight text-balance metallic-headline">
              <?= site_config('home_hero_title') ?>
            </h1>
          </div>
          <p class="mt-6 max-w-md text-lg text-muted-foreground leading-relaxed">
            <?= e(site_config('home_hero_subtitle')) ?>
          </p>
          <div class="mt-10 flex flex-wrap items-center gap-4">
            <a href="#products" class="group inline-flex items-center gap-3 rounded-full text-zinc-800 dark:text-zinc-200 bg-gradient-to-br from-zinc-200/80 via-zinc-100/80 to-zinc-300/80 dark:from-zinc-800/80 dark:via-zinc-700/80 dark:to-zinc-900/80 backdrop-blur-md border border-zinc-300/50 dark:border-zinc-600/50 pl-6 pr-2 py-2 text-base font-medium shadow-sm hover:shadow-md hover:brightness-[1.02] hover:text-primary dark:hover:text-primary transition-all duration-300">
              <span class="group-hover:drop-shadow-[0_0_4px_hsl(var(--primary)/0.4)] transition-all"><?= e(site_config('home_hero_cta_1', 'Explore Marketplace')) ?></span>
              <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-primary text-primary-foreground transition-transform group-hover:translate-x-0.5 shadow-glow">
                <?= icon_svg('ArrowRight') ?>
              </span>
            </a>

          </div>
          <div class="mt-12 grid grid-cols-3 max-w-md gap-6">
            <?php foreach ([
                [site_config('home_stat_1_val', '12,400+'), site_config('home_stat_1_lbl', 'Products')],
                [site_config('home_stat_2_val', '98.6%'), site_config('home_stat_2_lbl', 'Uptime')],
                [site_config('home_stat_3_val', '4.9/5'), site_config('home_stat_3_lbl', 'Rating')]
            ] as [$value, $label]): ?>
              <div>
                <div class="font-display text-3xl"><?= e($value) ?></div>
                <div class="text-xs uppercase tracking-wider text-muted-foreground mt-1"><?= e($label) ?></div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
        <div class="lg:col-span-6 relative perspective-1000 reveal-on-scroll reveal-slide-left" style="transition-delay: 200ms">
          <div class="relative">
            <div class="absolute -inset-8 bg-gradient-mesh blur-3xl opacity-60 -z-10"></div>
            <div class="relative rounded-2xl bg-card border border-border shadow-elevated overflow-hidden max-w-[520px] mx-auto lg:ml-auto">
              <div class="flex items-center justify-between px-5 py-3 border-b border-border bg-secondary/40 backdrop-blur-sm">
                <div class="flex items-center gap-1.5">
                  <span class="h-2.5 w-2.5 rounded-full bg-destructive/50"></span>
                  <span class="h-2.5 w-2.5 rounded-full bg-primary/50"></span>
                  <span class="h-2.5 w-2.5 rounded-full bg-success/50"></span>
                </div>
                <div class="font-sans text-[10px] uppercase tracking-widest text-muted-foreground flex items-center gap-2">
                  <?= icon_svg('ShieldCheck', 'h-3 w-3') ?>
                  vault.<?= e(strtolower(str_replace(' ', '', (string) site_config('site_name')))) ?>.app
                </div>
                <div class="w-12"></div>
              </div>
              <div class="flex h-[340px]">
                <div class="w-16 border-r border-border bg-secondary/20 flex flex-col items-center py-6 gap-6">
                  <div class="h-8 w-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center"><?= icon_svg('Zap') ?></div>
                  <div class="h-8 w-8 rounded-lg text-muted-foreground/60 flex items-center justify-center"><?= icon_svg('ArrowRight') ?></div>
                  <div class="h-8 w-8 rounded-lg text-muted-foreground/60 flex items-center justify-center"><?= icon_svg('Star') ?></div>
                  <div class="mt-auto h-8 w-8 rounded-full bg-secondary border border-border flex items-center justify-center"><div class="h-2 w-2 rounded-full bg-success"></div></div>
                </div>
                <div class="flex-1 p-6 overflow-hidden">
                  <div class="flex items-center justify-between mb-6">
                    <h4 class="font-display text-xl tracking-tight">Active Subscriptions</h4>
                    <span class="text-[10px] font-sans font-bold bg-success/10 text-success px-2 py-1 rounded uppercase tracking-wider">All Systems Nominal</span>
                  </div>
                  <div class="grid grid-cols-2 gap-4 mb-6">
                    <div class="p-4 rounded-xl border border-border bg-card/50">
                      <div class="text-[10px] uppercase tracking-wider text-muted-foreground mb-1">Downloads</div>
                      <div class="font-display text-2xl">1,482</div>
                      <div class="text-[10px] text-success mt-1">↑ 12% this week</div>
                    </div>
                    <div class="p-4 rounded-xl border border-border bg-primary/5">
                      <div class="text-[10px] uppercase tracking-wider text-primary/70 mb-1">Vault Status</div>
                      <div class="font-display text-2xl text-primary">Pro Pack</div>
                      <div class="text-[10px] text-primary/60 mt-1">LTD Access</div>
                    </div>
                  </div>
                  <div class="space-y-3">
                    <?php foreach ([['Elementor Pro', 'v3.21.4', 'Installed'], ['WP Rocket', 'v3.16.2', 'Update available'], ['Astra Pro', 'v4.6.5', 'Installed']] as [$name, $version, $status]): ?>
                      <div class="flex items-center justify-between p-3 rounded-lg border border-border/50 bg-secondary/5">
                        <div class="flex items-center gap-3">
                          <div class="h-6 w-6 rounded bg-card border border-border flex items-center justify-center text-[10px] font-bold"><?= e($name[0]) ?></div>
                          <div>
                            <div class="text-xs font-medium"><?= e($name) ?></div>
                            <div class="text-[10px] text-muted-foreground"><?= e($version) ?></div>
                          </div>
                        </div>
                        <div class="text-[10px] font-bold <?= str_contains($status, 'Update') ? 'text-primary' : 'text-muted-foreground' ?>"><?= e($status) ?></div>
                      </div>
                    <?php endforeach; ?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="py-12 border-y border-border bg-card/10">
    <div class="container">
      <div class="flex items-center justify-center gap-4 mb-10">
        <div class="h-px flex-1 max-w-24 bg-gradient-to-r from-transparent to-border"></div>
        <div class="relative inline-flex overflow-hidden rounded-full p-[1px] shadow-soft">
          <span class="absolute inset-[-1000%] animate-[spin_3s_linear_infinite] bg-[conic-gradient(from_90deg_at_50%_50%,hsl(var(--primary))_0%,transparent_50%,hsl(var(--primary))_100%)] opacity-70"></span>
          <span class="relative inline-flex items-center justify-center rounded-full bg-card/90 backdrop-blur-sm px-5 py-2 text-[10px] font-bold font-mono uppercase tracking-[0.2em] text-muted-foreground">
            Trusted by <span class="text-foreground mx-1">12,400+</span> products
          </span>
        </div>
        <div class="h-px flex-1 max-w-24 bg-gradient-to-l from-transparent to-border"></div>
      </div>
    </div>
    <div class="relative overflow-hidden">
      <div class="absolute inset-y-0 left-0 w-32 bg-gradient-to-r from-background to-transparent z-10 pointer-events-none"></div>
      <div class="absolute inset-y-0 right-0 w-32 bg-gradient-to-l from-background to-transparent z-10 pointer-events-none"></div>
      <div class="flex w-max animate-marquee items-center gap-24 py-6">
        <?php foreach (array_merge($platformLogos, $platformLogos, $platformLogos) as $logo): ?>
          <div class="flex items-center justify-center px-4 cursor-default flex-shrink-0">
            <img src="https://cdn.simpleicons.org/<?= e($logo['slug'] ?? '') ?>/717171" alt="<?= e($logo['name'] ?? '') ?>" class="h-9 w-auto opacity-40 grayscale transition-all duration-500 hover:opacity-100 hover:grayscale-0">
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>


  <style>
    @keyframes logo-marquee {
      0% { transform: translate3d(0, 0, 0); }
      100% { transform: translate3d(-33.333%, 0, 0); }
    }

    .animate-marquee {
      animation: logo-marquee 32s linear infinite;
      will-change: transform;
    }

    @media (prefers-reduced-motion: reduce) {
      .animate-marquee {
        animation: none;
      }
    }

    /* ── Services timeline ── */

    /* The dot is 20px wide, centred on the line.
       Line is at left:0 of svc-track (a 20px wide column).
       Dot: left:0, margin-left auto/auto => use absolute left:0, top of card centre */

    .svc-track {
      /* 20px gutter for line + dot, rest for cards */
      padding-left: 44px;
      position: relative;
    }

    /* ambient base line — full height, always visible, very faint */
    .svc-track-line {
      position: absolute;
      left: 9px;           /* centre of 20px gutter */
      top: 10px;
      bottom: 10px;
      width: 1px;
      background: linear-gradient(
        to bottom,
        transparent 0%,
        hsl(var(--border)) 8%,
        hsl(var(--border)) 92%,
        transparent 100%
      );
    }

    /* orange fill that grows downward on scroll */
    .svc-track-fill {
      position: absolute;
      left: 9px;
      top: 10px;
      width: 1px;
      height: 0%;
      background: linear-gradient(
        to bottom,
        hsl(var(--primary)) 0%,
        hsl(var(--primary) / 0.4) 70%,
        transparent 100%
      );
      transition: height 2.6s cubic-bezier(0.16,1,0.3,1);
      z-index: 1;
    }
    .svc-track-fill.svc-go { height: calc(100% - 20px); }

    /* each row */
    .svc-item {
      position: relative;
      padding-bottom: 28px;
      opacity: 0;
      transform: translateX(12px);
      transition: opacity 0.7s ease, transform 0.7s ease;
    }
    .svc-item:last-child { padding-bottom: 0; }
    .svc-item.svc-visible { opacity: 1; transform: translateX(0); }

    /* checkpoint dot — sits exactly on the line (left:9px = centre of gutter)
       dot is 20px wide so left offset = 9px - 10px = -1px from track edge,
       but since track has pl-44px (=44px), dot absolute left = -(44px) + 9px - 10px = -45px ... 
       Simpler: use left: -35px so dot centre aligns with line at left:9px of the track wrapper */
    .svc-dot {
      position: absolute;
      left: -35px;          /* pulls dot back so its centre (10px) sits at line x=9px ✓ */
      top: 50%;
      transform: translateY(-50%);
      width: 20px;
      height: 20px;
      border-radius: 50%;
      border: 2px solid hsl(var(--border));
      background: hsl(var(--background));
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 2;
      transition: border-color 0.5s ease, box-shadow 0.5s ease;
    }
    .svc-item.svc-visible .svc-dot {
      border-color: hsl(var(--primary));
      box-shadow: 0 0 0 5px hsl(var(--primary) / 0.13);
    }
    .svc-dot-core {
      width: 7px; height: 7px;
      border-radius: 50%;
      background: hsl(var(--border));
      transition: background 0.5s ease;
    }
    .svc-item.svc-visible .svc-dot-core { background: hsl(var(--primary)); }
  </style>

  <section id="categories" class="py-28 bg-background relative">
    <div class="container">
      <div class="grid lg:grid-cols-2 gap-16 lg:gap-24 items-start">

        <!-- LEFT: about copy -->
        <div class="lg:sticky lg:top-32">
          <div class="relative inline-flex overflow-hidden rounded-full p-[1px] mb-6 shadow-sm">
            <span class="absolute inset-[-1000%] animate-[spin_3s_linear_infinite] bg-[conic-gradient(from_90deg_at_50%_50%,hsl(var(--primary))_0%,transparent_50%,hsl(var(--primary))_100%)] opacity-70"></span>
            <span class="relative inline-flex items-center justify-center rounded-full bg-background px-4 py-1.5 text-xs font-mono uppercase tracking-[0.2em] text-primary">
              About PixelVault
            </span>
          </div>

          <h2 class="font-display text-5xl md:text-6xl lg:text-7xl tracking-tight leading-[1.0] text-balance">
            The smarter way<br>to build on<br>
            <span class="text-primary">WordPress.</span>
          </h2>

          <p class="mt-8 text-lg text-muted-foreground leading-relaxed max-w-md">
            PixelVault is a GPL marketplace giving developers, agencies, and freelancers access to every major WordPress plugin and theme — at a fraction of the original price.
          </p>
          <p class="mt-4 text-muted-foreground leading-relaxed max-w-md">
            One membership. Unlimited sites. Lifetime updates. No license keys, no upsells, no drama.
          </p>

          <div class="mt-12 grid grid-cols-3 gap-6 max-w-xs">
            <?php foreach ([['28k+','Members'],['12.4k','Products'],['4.9★','Rating']] as [$v,$l]): ?>
            <div>
              <div class="font-display text-2xl text-foreground"><?= e($v) ?></div>
              <div class="text-xs uppercase tracking-wider text-muted-foreground mt-1"><?= e($l) ?></div>
            </div>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- RIGHT: Interactive Focus List -->
        <div class="group/list relative bg-card/40 dark:bg-card/20 backdrop-blur-3xl border border-border/50 rounded-[2.5rem] p-6 lg:p-10 shadow-2xl">
          <!-- Subtle glow in the top corner -->
          <div class="absolute -top-12 -left-12 w-64 h-64 bg-primary/20 blur-[80px] rounded-full pointer-events-none opacity-50"></div>
          <div class="absolute -bottom-12 -right-12 w-64 h-64 bg-primary/10 blur-[80px] rounded-full pointer-events-none opacity-50"></div>
          
          <div class="space-y-1 relative z-10">
            <?php
            $services = [
              ['GPL Plugins & Themes',      'Every major product — Elementor, WooCommerce, Astra and hundreds more.', 'Package'],
              ['Instant Secure Downloads',  'Scanned, verified, delivered the moment you click. No waiting, ever.', 'ShieldCheck'],
              ['Lifetime Version Updates',  'Get up to 3 update downloads per release. Never miss a security patch.', 'RefreshCw'],
              ['No License Keys',           'Pure GPL freedom. Deploy on as many client sites as you like.', 'Unlock'],
              ['Dedicated Support',         'Real answers from real people. Fast response, zero bots.', 'MessageSquare'],
              ['New Drops Every Week',      '240+ fresh products added weekly — curated, tested, ready to ship.', 'Sparkles'],
            ];
            foreach ($services as $i => [$label, $desc, $icon]):
            ?>
            <div class="reveal-on-scroll group/item relative flex items-center gap-5 sm:gap-6 p-5 rounded-[1.75rem] transition-all duration-500 hover:bg-background/90 hover:shadow-[0_20px_50px_-20px_rgba(0,0,0,0.1)] dark:hover:shadow-[0_20px_50px_-20px_rgba(0,0,0,0.5)] border border-transparent hover:border-primary/20 cursor-default hover:!opacity-100 group-hover/list:opacity-40" style="transition-delay:<?= $i * 50 ?>ms">
              
              <!-- Subtle item background glow -->
              <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-transparent opacity-0 group-hover/item:opacity-100 transition-opacity rounded-[1.75rem]"></div>
              
              <div class="relative h-12 w-12 sm:h-14 sm:w-14 rounded-2xl bg-secondary dark:bg-white/5 text-foreground flex items-center justify-center flex-shrink-0 group-hover/item:bg-primary group-hover/item:text-primary-foreground group-hover/item:scale-110 group-hover/item:rotate-3 group-hover/item:shadow-[0_0_20px_hsl(var(--primary)/0.3)] transition-all duration-500">
                <div class="absolute inset-0 rounded-2xl bg-primary/20 blur-md opacity-0 group-hover/item:opacity-100 transition-opacity"></div>
                <span class="relative z-10"><?= icon_svg($icon, 'h-5 w-5 sm:h-6 sm:w-6') ?></span>
              </div>
              
              <div class="relative flex-1">
                <h3 class="font-display text-lg sm:text-xl text-foreground tracking-tight mb-1 group-hover/item:text-primary transition-colors duration-300"><?= e($label) ?></h3>
                <p class="text-xs sm:text-sm text-muted-foreground leading-relaxed group-hover/item:text-foreground/70 transition-colors"><?= e($desc) ?></p>
              </div>
              
            </div>
            <?php endforeach; ?>
          </div>
        </div>

      </div>
    </div>
  </section>

  <!-- Removed old timeline JS -->

  <section id="products" class="py-28 bg-[#FDFBF7] dark:bg-card/30 border-y border-border relative overflow-hidden">
    <div class="absolute inset-0 grid-bg opacity-50" aria-hidden></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[1200px] h-[1200px] bg-primary/[0.03] rounded-full blur-[140px] pointer-events-none" aria-hidden></div>
    <div class="container relative">
      <div class="flex items-end justify-between mb-12 gap-8 flex-wrap">
        <div>
          <div class="relative inline-flex overflow-hidden rounded-full p-[1px] mb-4 shadow-sm">
            <span class="absolute inset-[-1000%] animate-[spin_3s_linear_infinite] bg-[conic-gradient(from_90deg_at_50%_50%,hsl(var(--primary))_0%,transparent_50%,hsl(var(--primary))_100%)] opacity-70"></span>
            <span class="relative inline-flex items-center justify-center rounded-full bg-card px-4 py-1.5 text-xs font-mono uppercase tracking-[0.2em] text-primary">
              03 — Featured drops
            </span>
          </div>
          <h2 class="font-display text-5xl md:text-6xl tracking-tight text-balance">This week in <span class="text-primary">the Vault.</span></h2>
        </div>
        <div class="flex gap-2">
          <?php foreach (['All', 'Plugins', 'Themes', 'Bundles'] as $i => $label): ?>
            <button class="px-4 py-2 rounded-full text-xs font-medium border transition-colors <?= $i === 0 ? 'bg-ink text-ink-foreground border-ink' : 'border-border bg-card hover:bg-secondary' ?>" type="button"><?= e($label) ?></button>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <?php 
        $featuredProducts = array_slice($products, 0, 12);
        foreach ($featuredProducts as $product): 
        ?>
          <article class="group relative rounded-2xl bg-card border border-border overflow-hidden cursor-pointer shadow-elevated dark:shadow-glow transition-all duration-500 hover:-translate-y-1 hover:shadow-[0_24px_50px_-12px_hsl(var(--primary) / 0.35)]">
            <a href="/product/<?= e((string) ($product['id'] ?? '')) ?>" class="relative block aspect-[16/10] bg-gradient-to-br <?= e($product['tone'] ?? '') ?> border-b border-border overflow-hidden flex items-center justify-center">
              <div class="absolute inset-0 grid-bg opacity-40"></div>
              <?php if (!empty($product['image'])): ?>
                <img src="<?= e((string) $product['image']) ?>" alt="<?= e((string) ($product['name'] ?? '')) ?>" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
              <?php else: ?>
                <span class="font-display text-8xl text-foreground/70"><?= e((string) ($product['letter'] ?? 'P')) ?></span>
              <?php endif; ?>
              <div class="absolute top-3 left-3 flex gap-1.5">
                <span class="text-[10px] font-sans uppercase tracking-widest bg-ink text-ink-foreground px-2 py-1 rounded-full">GPL</span>
                <span class="text-[10px] font-sans uppercase tracking-widest bg-card border border-border px-2 py-1 rounded-full">v<?= e((string) ($product['ver'] ?? '')) ?></span>
              </div>
              <div class="absolute top-3 right-3 h-9 w-9 rounded-full bg-primary text-primary-foreground flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                <?= icon_svg('ArrowUpRight') ?>
              </div>
            </a>
            <div class="p-4">
              <div class="flex items-center justify-between text-xs font-sans uppercase tracking-wider text-muted-foreground mb-1">
                <span><?= e((string) ($product['cat'] ?? '')) ?></span>
                <span class="flex items-center gap-1"><?= icon_svg('Star', 'h-3 w-3 fill-primary text-primary') ?>4.9</span>
              </div>
              <h3 class="font-display text-xl leading-tight truncate" title="<?= e((string) ($product['name'] ?? '')) ?>"><?= e((string) ($product['name'] ?? '')) ?></h3>
              <div class="mt-4 flex items-end justify-between">
                <div class="flex items-baseline gap-2">
                  <span class="font-display text-2xl">$<?= e((string) ($product['price'] ?? '')) ?></span>
                  <span class="text-xs text-muted-foreground line-through">$<?= e((string) ($product['og'] ?? '')) ?></span>
                </div>
                <form method="post" action="/cart/add">
                  <input type="hidden" name="product_id" value="<?= e((string) ($product['id'] ?? '')) ?>">
                  <input type="hidden" name="return_to" value="/">
                  <button class="rounded-full bg-secondary text-secondary-foreground px-3 py-1.5 text-xs font-medium group-hover:bg-ink group-hover:text-ink-foreground transition-colors" type="submit">Add to cart</button>
                </form>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
      <div class="mt-12 flex justify-center">
        <a href="/marketplace" class="inline-flex items-center gap-2 text-sm font-medium border border-border rounded-full px-6 py-3 bg-card hover:bg-ink hover:text-ink-foreground hover:border-ink transition-colors">
          View all 12,400 products
          <?= icon_svg('ArrowUpRight') ?>
        </a>
      </div>
    </div>
  </section>

  <style>
    /* =============================================
       FEAT-CARD UNIQUE ANIMATIONS — Why PixelVault
       Each card has a completely different illustration animation.
       Card shell is identical to cat-card (no orbit rings here).
       ============================================= */

    /* ---- CARD 1: PAGE BUILDER
       Mini drag-and-drop canvas: blocks slide in and snap to a grid  ---- */
    @keyframes pb-slidein-row1 {
      0%,10%   { transform: translateX(-32px); opacity:0; }
      25%,75%  { transform: translateX(0);     opacity:1; }
      90%,100% { transform: translateX(-32px); opacity:0; }
    }
    @keyframes pb-slidein-row2 {
      0%,20%   { transform: translateX(32px);  opacity:0; }
      35%,75%  { transform: translateX(0);     opacity:1; }
      90%,100% { transform: translateX(32px);  opacity:0; }
    }
    @keyframes pb-slidein-row3 {
      0%,30%   { transform: translateX(-24px); opacity:0; }
      45%,75%  { transform: translateX(0);     opacity:1; }
      90%,100% { transform: translateX(-24px); opacity:0; }
    }
    @keyframes pb-cursor-move {
      0%   { transform: translate(10px,10px); }
      30%  { transform: translate(68px,18px); }
      55%  { transform: translate(40px,52px); }
      80%  { transform: translate(100px,46px); }
      100% { transform: translate(10px,10px); }
    }
    @keyframes pb-grab {
      0%,25%,75%,100% { transform: scale(1);    box-shadow: none; }
      30%,70%         { transform: scale(1.06); box-shadow: 0 6px 18px rgba(0,0,0,0.12); }
    }
    .pb-row1 { animation: pb-slidein-row1 4s ease-in-out infinite; }
    .pb-row2 { animation: pb-slidein-row2 4s ease-in-out infinite; }
    .pb-row3 { animation: pb-slidein-row3 4s ease-in-out infinite; }
    .pb-cursor { animation: pb-cursor-move 4s ease-in-out infinite; }
    .pb-grab   { animation: pb-grab 4s ease-in-out infinite; }

    /* ---- CARD 2: CART & ANALYTICS
       Area chart wave fills up, a counter ticks up, a cart badge pops  ---- */
    @keyframes chart-fill {
      0%   { clip-path: inset(0 100% 0 0); }
      60%  { clip-path: inset(0 0%   0 0); }
      85%  { clip-path: inset(0 0%   0 0); }
      100% { clip-path: inset(0 100% 0 0); }
    }
    @keyframes chart-line {
      0%   { stroke-dashoffset: 300; }
      60%  { stroke-dashoffset: 0;   }
      85%  { stroke-dashoffset: 0;   }
      100% { stroke-dashoffset: 300; }
    }
    @keyframes badge-pop {
      0%,55%  { transform: scale(0.6); opacity:0; }
      65%     { transform: scale(1.15); opacity:1; }
      72%     { transform: scale(0.95); }
      80%,100%{ transform: scale(1);   opacity:1; }
    }
    .chart-area { animation: chart-fill 4.5s ease-in-out infinite; }
    .chart-line { stroke-dasharray: 300; animation: chart-line 4.5s ease-in-out infinite; }
    .analytics-badge { animation: badge-pop 4.5s ease-in-out infinite; }

    /* ---- CARD 3: EMAIL & FORMS ---- */
    @keyframes email-type {
      0%, 10% { width: 0; opacity: 0; }
      20%, 80% { width: 100%; opacity: 1; }
      90%, 100% { width: 0; opacity: 0; }
    }
    @keyframes email-btn-glow {
      0%, 40% { background: hsl(var(--primary) / 0.1); transform: scale(1); }
      50%, 70% { background: hsl(var(--primary) / 0.8); transform: scale(1.05); }
      80%, 100% { background: hsl(var(--primary) / 0.1); transform: scale(1); }
    }
    @keyframes email-fly-out {
      0%, 65% { transform: translate(0,0) scale(0); opacity: 0; }
      70% { transform: translate(0,0) scale(1); opacity: 1; }
      85% { transform: translate(50px, -50px) scale(0.6); opacity: 0; }
      100% { transform: translate(0,0) scale(0); opacity: 0; }
    }
    .email-l1 { animation: email-type 4s ease-in-out infinite; }
    .email-l2 { animation: email-type 4s ease-in-out 0.3s infinite; }
    .email-btn { animation: email-btn-glow 4s ease-in-out infinite; }
    .email-fly { animation: email-fly-out 4s ease-in-out infinite; }

    /* ---- CARD 4: SAFE & SECURE ---- */
    @keyframes sec-scan {
      0% { top: 0%; opacity: 1; }
      80% { top: 100%; opacity: 1; }
      100% { top: 0%; opacity: 0; }
    }
    @keyframes sec-row-glow {
      0%, 20% { background: transparent; }
      40%, 80% { background: rgba(34,197,94,0.1); }
      100% { background: transparent; }
    }
    @keyframes sec-shield-pop {
      0%, 75% { transform: scale(0.5); opacity: 0; }
      85% { transform: scale(1.1); opacity: 1; }
      100% { transform: scale(1); opacity: 1; }
    }
    .sec-scanner { animation: sec-scan 3s ease-in-out infinite; }
    .sec-row-anim { animation: sec-row-glow 3s ease-in-out infinite; }
    .sec-shield-anim { animation: sec-shield-pop 3s ease-in-out infinite; }

    /* ---- CARD 5: LIFETIME UPDATES ---- */
    @keyframes upd-progress {
      0% { width: 0%; }
      40%, 60% { width: 100%; }
      100% { width: 0%; }
    }
    @keyframes upd-node-pulse {
      0%, 35% { transform: scale(1); background: hsl(var(--border)); }
      40%, 60% { transform: scale(1.2); background: hsl(var(--primary)); box-shadow: 0 0 15px hsl(var(--primary) / 0.5); }
      65%, 100% { transform: scale(1); background: hsl(var(--border)); }
    }
    @keyframes upd-notif-pop {
      0%, 60% { transform: translateY(10px); opacity: 0; }
      75%, 90% { transform: translateY(0); opacity: 1; }
      100% { transform: translateY(10px); opacity: 0; }
    }
    .upd-line { animation: upd-progress 4s ease-in-out infinite; }
    .upd-node { animation: upd-node-pulse 4s ease-in-out infinite; }
    .upd-notif-anim { animation: upd-notif-pop 4s ease-in-out infinite; }

    /* ---- CARD 6: GPL LICENSE ---- */
    @keyframes gpl-unlock {
      0%, 20% { transform: rotate(0); }
      40%, 70% { transform: rotate(-15deg) translateY(-4px); }
      85%, 100% { transform: rotate(0); }
    }
    @keyframes gpl-site-pop {
      0%, 30% { transform: scale(0); opacity: 0; }
      45% { transform: scale(1.1); opacity: 1; }
      55%, 100% { transform: scale(1); opacity: 1; }
    }
    .gpl-lock { animation: gpl-unlock 4s ease-in-out infinite; transform-origin: center bottom; }
    .gpl-site { animation: gpl-site-pop 4s ease-in-out infinite; }
  </style>

  <section id="updates" class="py-28 bg-[#FAFAFA] dark:bg-background relative">
    <div class="container relative z-10">
      <div class="text-center mb-24 max-w-3xl mx-auto reveal-on-scroll reveal-slide-up">
        <div class="relative inline-flex overflow-hidden rounded-full p-[1px] mb-6 shadow-soft">
          <span class="absolute inset-[-1000%] animate-[spin_3s_linear_infinite] bg-[conic-gradient(from_90deg_at_50%_50%,hsl(var(--primary))_0%,transparent_50%,hsl(var(--primary))_100%)] opacity-70"></span>
          <span class="relative inline-flex items-center justify-center rounded-full bg-[#FAFAFA] dark:bg-background px-5 py-2 text-xs font-mono font-bold uppercase tracking-[0.3em] text-muted-foreground">
            Why PixelVault
          </span>
        </div>
        <h2 class="font-display text-5xl md:text-7xl tracking-tight leading-[1.05] text-slate-900 dark:text-white">
          Built like a vault.<br class="hidden md:block"> <span class="text-slate-500">Priced</span> like an open library.
        </h2>
        <p class="mt-6 text-muted-foreground text-lg max-w-xl mx-auto">We treat every download like it matters — because it powers your client's business.</p>
      </div>

      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">

        <!-- ① PAGE BUILDER — drag-and-drop canvas blocks snapping into grid -->
        <div class="group flex flex-col bg-card rounded-[2rem] border border-border shadow-elevated dark:shadow-glow transition-all duration-500 p-2 h-full hover:-translate-y-1 hover:shadow-[0_24px_50px_-12px_hsl(var(--primary) / 0.35)] reveal-on-scroll reveal-slide-up">
          <div class="relative w-full h-[240px] rounded-[1.5rem] bg-[#F3F4F6] dark:bg-background/80 overflow-hidden flex items-center justify-center">
            <!-- Canvas grid bg -->
            <div class="absolute inset-0" style="background-image:linear-gradient(rgba(0,0,0,0.04) 1px,transparent 1px),linear-gradient(90deg,rgba(0,0,0,0.04) 1px,transparent 1px);background-size:18px 18px;"></div>
            <!-- Block rows sliding in -->
            <div class="absolute flex flex-col gap-2 items-start" style="width:170px">
              <div class="pb-row1 pb-grab w-full h-8 rounded-lg bg-white/80 dark:bg-background/70 border border-border shadow-sm flex items-center px-3 gap-2">
                <div class="h-2.5 w-2.5 rounded bg-violet-400/60 flex-shrink-0"></div>
                <div class="h-1.5 flex-1 rounded-full bg-slate-200 dark:bg-slate-700"></div>
                <div class="h-1.5 w-8 rounded-full bg-slate-200 dark:bg-slate-700"></div>
              </div>
              <div class="pb-row2 pb-grab flex gap-2 w-full">
                <div class="h-14 flex-1 rounded-lg bg-white/80 dark:bg-background/70 border border-border shadow-sm flex items-center justify-center">
                  <div class="h-6 w-6 rounded bg-primary/40"></div>
                </div>
                <div class="h-14 flex-1 rounded-lg bg-white/80 dark:bg-background/70 border border-border shadow-sm flex flex-col items-start justify-center px-2 gap-1">
                  <div class="h-1.5 w-full rounded-full bg-slate-200 dark:bg-slate-700"></div>
                  <div class="h-1.5 w-3/4 rounded-full bg-slate-200 dark:bg-slate-700"></div>
                  <div class="h-1.5 w-1/2 rounded-full bg-slate-200 dark:bg-slate-700"></div>
                </div>
              </div>
              <div class="pb-row3 pb-grab w-full h-6 rounded-lg bg-primary/80 flex items-center justify-center">
                <div class="h-1.5 w-16 rounded-full bg-white/60"></div>
              </div>
            </div>
            <!-- Cursor dot -->
            <div class="pb-cursor absolute top-0 left-0 z-20 pointer-events-none">
              <svg width="14" height="18" viewBox="0 0 14 18" fill="none"><path d="M1 1L1 13L4.5 10L6.5 15L8.2 14.3L6.2 9.3L10.5 9L1 1Z" fill="white" stroke="#1e293b" stroke-width="1.2"/></svg>
            </div>
          </div>
          <div class="p-6 pb-8 flex flex-col flex-1">
            <span class="font-mono text-[10px] text-muted-foreground mb-3">01</span>
            <h3 class="font-display text-2xl mb-2 text-slate-900 dark:text-white tracking-tight">Page Builder Toolkit</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed font-medium">Elementor, Beaver Builder, Divi and every major builder — all premium addons, templates and extensions in one place.</p>
          </div>
        </div>

        <!-- ② CART & ANALYTICS — area chart fills up, badge pops -->
        <div class="group flex flex-col bg-card rounded-[2rem] border border-border shadow-elevated dark:shadow-glow transition-all duration-500 p-2 h-full hover:-translate-y-1 hover:shadow-[0_24px_50px_-12px_hsl(var(--primary) / 0.35)] reveal-on-scroll reveal-slide-up" style="transition-delay: 100ms">
          <div class="relative w-full h-[240px] rounded-[1.5rem] bg-[#F3F4F6] dark:bg-background/80 overflow-hidden flex items-end justify-center pb-6">
            <!-- X axis labels -->
            <div class="absolute bottom-4 left-0 right-0 flex justify-between px-8 pointer-events-none">
              <?php foreach (['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $d): ?>
                <span class="font-mono text-[8px] text-slate-400"><?= $d ?></span>
              <?php endforeach; ?>
            </div>
            <!-- SVG area chart -->
            <svg class="absolute inset-0 w-full h-full" viewBox="0 0 280 160" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
              <defs>
                <linearGradient id="area-grad" x1="0" y1="0" x2="0" y2="1">
                  <stop offset="0%" stop-color="hsl(var(--primary) / 0.35)"/>
                  <stop offset="100%" stop-color="hsl(var(--primary) / 0.02)"/>
                </linearGradient>
              </defs>
              <!-- Filled area -->
              <path class="chart-area" d="M0,130 C20,120 40,100 70,75 C95,55 110,90 140,65 C165,44 185,80 210,50 C230,28 255,40 280,30 L280,160 L0,160 Z" fill="url(#area-grad)"/>
              <!-- Line on top -->
              <path class="chart-line" d="M0,130 C20,120 40,100 70,75 C95,55 110,90 140,65 C165,44 185,80 210,50 C230,28 255,40 280,30" fill="none" stroke="rgb(249,115,22)" stroke-width="2.5" stroke-linecap="round"/>
              <!-- Dot at peak -->
              <circle cx="210" cy="50" r="4" fill="rgb(249,115,22)" opacity="0.9"/>
            </svg>
            <!-- Pop badge -->
            <div class="analytics-badge absolute top-8 right-8 bg-white/90 dark:bg-background/90 border border-border rounded-xl px-3 py-1.5 shadow-soft flex items-center gap-2 pointer-events-none">
              <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
              <span class="font-mono text-xs font-bold text-slate-700 dark:text-slate-200">+4,578</span>
            </div>
          </div>
          <div class="p-6 pb-8 flex flex-col flex-1">
            <span class="font-mono text-[10px] text-muted-foreground mb-3">02</span>
            <h3 class="font-display text-2xl mb-2 text-slate-900 dark:text-white tracking-tight">Cart & Analytics</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed font-medium">WooCommerce extensions, checkout boosters, and analytics plugins — everything to turn visitors into customers.</p>
          </div>
        </div>

        <!-- ③ EMAIL & FORMS — mini contact form UI -->
        <div class="group flex flex-col bg-card rounded-[2rem] border border-border shadow-elevated dark:shadow-glow transition-all duration-500 p-2 h-full hover:-translate-y-1 hover:shadow-[0_24px_50px_-12px_hsl(var(--primary) / 0.35)] reveal-on-scroll reveal-slide-up" style="transition-delay: 200ms">
          <div class="relative w-full h-[240px] rounded-[1.5rem] bg-[#F3F4F6] dark:bg-background/80 overflow-hidden flex items-center justify-center">
            <!-- Canvas grid bg -->
            <div class="absolute inset-0" style="background-image:linear-gradient(rgba(0,0,0,0.04) 1px,transparent 1px),linear-gradient(90deg,rgba(0,0,0,0.04) 1px,transparent 1px);background-size:18px 18px;"></div>
            
            <!-- Mini Form Card -->
            <div class="relative w-44 bg-white/90 dark:bg-card border border-border rounded-2xl p-4 shadow-soft">
              <div class="flex items-center gap-2 mb-3">
                <div class="h-2 w-2 rounded-full bg-primary/60"></div>
                <div class="h-1.5 w-12 rounded-full bg-slate-200 dark:bg-slate-700"></div>
              </div>
              <div class="space-y-2 mb-4">
                <div class="email-l1 h-1.5 w-full bg-slate-100 dark:bg-slate-800 rounded-full"></div>
                <div class="email-l2 h-1.5 w-3/4 bg-slate-100 dark:bg-slate-800 rounded-full"></div>
                <div class="h-3 w-full bg-slate-100 dark:bg-slate-800 rounded-lg"></div>
              </div>
              <div class="email-btn h-6 w-full rounded-lg bg-primary/10 flex items-center justify-center relative overflow-hidden">
                <div class="h-1.5 w-10 rounded-full bg-primary/40"></div>
                <!-- Flying envelope -->
                <div class="email-fly absolute flex items-center justify-center text-primary">
                  <?= icon_svg('Send', 'h-3.5 w-3.5') ?>
                </div>
              </div>
            </div>
          </div>
          <div class="p-6 pb-8 flex flex-col flex-1">
            <span class="font-mono text-[10px] text-muted-foreground mb-3">03</span>
            <h3 class="font-display text-2xl mb-2 text-slate-900 dark:text-white tracking-tight">Email & Forms</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed font-medium">Gravity Forms, Fluent Forms, MailPoet and all the SMTP, automation, and lead-capture tools your stack needs.</p>
          </div>
        </div>

        <!-- ④ SAFE & SECURE — file scanner UI -->
        <div class="group flex flex-col bg-card rounded-[2rem] border border-border shadow-elevated dark:shadow-glow transition-all duration-500 p-2 h-full hover:-translate-y-1 hover:shadow-[0_24px_50px_-12px_hsl(var(--primary) / 0.35)] reveal-on-scroll reveal-slide-up">
          <div class="relative w-full h-[240px] rounded-[1.5rem] bg-[#F3F4F6] dark:bg-background/80 overflow-hidden flex items-center justify-center">
            <!-- Canvas grid bg -->
            <div class="absolute inset-0" style="background-image:linear-gradient(rgba(0,0,0,0.04) 1px,transparent 1px),linear-gradient(90deg,rgba(0,0,0,0.04) 1px,transparent 1px);background-size:18px 18px;"></div>
            
            <!-- Scanner Panel -->
            <div class="relative w-48 bg-white/90 dark:bg-card border border-border rounded-2xl shadow-soft overflow-hidden">
              <div class="px-3 py-2 border-b border-border bg-slate-50 dark:bg-slate-800/50 flex items-center justify-between">
                <div class="flex items-center gap-1.5">
                   <div class="h-1.5 w-1.5 rounded-full bg-emerald-500"></div>
                   <span class="font-mono text-[7px] uppercase tracking-widest text-slate-500">Shield.v2</span>
                </div>
                <div class="h-2 w-8 bg-slate-200 dark:bg-slate-700 rounded-full"></div>
              </div>
              <div class="p-3 space-y-2 relative">
                <div class="sec-scanner absolute left-0 right-0 h-[2px] bg-emerald-500/40 z-10"></div>
                <?php foreach(['plugin-core.php', 'engine.js', 'assets.zip'] as $f): ?>
                <div class="sec-row-anim flex items-center justify-between px-2 py-1.5 rounded-md border border-border/20">
                  <span class="font-mono text-[7px] text-slate-400"><?= $f ?></span>
                  <div class="h-1.5 w-6 rounded-full bg-slate-200 dark:bg-slate-700"></div>
                </div>
                <?php endforeach; ?>
              </div>
            </div>
            <!-- Pop Shield -->
            <div class="sec-shield-anim absolute bottom-8 right-8 h-12 w-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center shadow-lg border-4 border-white dark:border-card">
              <?= icon_svg('ShieldCheck', 'h-6 w-6') ?>
            </div>
          </div>
          <div class="p-6 pb-8 flex flex-col flex-1">
            <span class="font-mono text-[10px] text-muted-foreground mb-3">04</span>
            <h3 class="font-display text-2xl mb-2 text-slate-900 dark:text-white tracking-tight">Safe & Secure</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed font-medium">Every file scanned, signed, and delivered through private Cloudflare R2 URLs. No third-party tracking ever.</p>
          </div>
        </div>

        <!-- ⑤ LIFETIME UPDATES — version timeline UI -->
        <div class="group flex flex-col bg-card rounded-[2rem] border border-border shadow-elevated dark:shadow-glow transition-all duration-500 p-2 h-full hover:-translate-y-1 hover:shadow-[0_24px_50px_-12px_hsl(var(--primary) / 0.35)] reveal-on-scroll reveal-slide-up" style="transition-delay: 100ms">
          <div class="relative w-full h-[240px] rounded-[1.5rem] bg-[#F3F4F6] dark:bg-background/80 overflow-hidden flex items-center justify-center">
            <!-- Canvas grid bg -->
            <div class="absolute inset-0" style="background-image:linear-gradient(rgba(0,0,0,0.04) 1px,transparent 1px),linear-gradient(90deg,rgba(0,0,0,0.04) 1px,transparent 1px);background-size:18px 18px;"></div>
            
            <!-- Timeline UI -->
            <div class="relative flex items-center gap-4">
              <div class="px-3 py-2 rounded-xl bg-white/90 dark:bg-card border border-border shadow-sm text-[10px] font-mono font-bold text-slate-400">v3.1.2</div>
              <div class="relative w-12 h-1 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                <div class="upd-line absolute inset-0 bg-primary/40"></div>
              </div>
              <div class="upd-node px-3 py-2 rounded-xl bg-primary text-primary-foreground border border-primary/20 shadow-lg text-[10px] font-mono font-bold">v3.2.0</div>
            </div>
            
            <div class="upd-notif-anim absolute top-10 right-10">
              <div class="bg-emerald-500 text-white px-3 py-1.5 rounded-lg shadow-soft flex items-center gap-2">
                <div class="h-1.5 w-1.5 rounded-full bg-white animate-pulse"></div>
                <span class="font-mono text-[8px] font-bold uppercase tracking-widest">Update Ready</span>
              </div>
            </div>
          </div>
          <div class="p-6 pb-8 flex flex-col flex-1">
            <span class="font-mono text-[10px] text-muted-foreground mb-3">05</span>
            <h3 class="font-display text-2xl mb-2 text-slate-900 dark:text-white tracking-tight">Lifetime Updates</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed font-medium">Up to 3 update downloads per product, per user. Get notified the moment a new version drops — never miss a security patch.</p>
          </div>
        </div>

        <!-- ⑥ GPL — unlimited site nodes UI -->
        <div class="group flex flex-col bg-card rounded-[2rem] border border-border shadow-elevated dark:shadow-glow transition-all duration-500 p-2 h-full hover:-translate-y-1 hover:shadow-[0_24px_50px_-12px_hsl(var(--primary) / 0.35)] reveal-on-scroll reveal-slide-up" style="transition-delay: 200ms">
          <div class="relative w-full h-[240px] rounded-[1.5rem] bg-[#F3F4F6] dark:bg-background/80 overflow-hidden flex items-center justify-center">
            <!-- Canvas grid bg -->
            <div class="absolute inset-0" style="background-image:linear-gradient(rgba(0,0,0,0.04) 1px,transparent 1px),linear-gradient(90deg,rgba(0,0,0,0.04) 1px,transparent 1px);background-size:18px 18px;"></div>
            
            <div class="relative h-20 w-20 flex items-center justify-center">
              <div class="absolute inset-0 bg-violet-500/20 blur-2xl rounded-full"></div>
              <div class="gpl-lock relative h-14 w-14 rounded-2xl bg-white dark:bg-card border border-border shadow-lg flex items-center justify-center z-10">
                 <div class="text-violet-500"><?= icon_svg('Unlock', 'h-6 w-6') ?></div>
              </div>
              
              <!-- Site Bubbles -->
              <div class="gpl-site absolute -top-8 -left-8 h-8 w-8 rounded-lg bg-white dark:bg-card border border-border shadow-sm flex items-center justify-center"><?= icon_svg('Globe', 'h-4 w-4 text-slate-400') ?></div>
              <div class="gpl-site absolute -top-6 -right-10 h-7 w-7 rounded-lg bg-white dark:bg-card border border-border shadow-sm flex items-center justify-center" style="animation-delay: 0.2s"><?= icon_svg('Globe', 'h-3.5 w-3.5 text-slate-400') ?></div>
              <div class="gpl-site absolute -bottom-6 -right-8 h-9 w-9 rounded-lg bg-white dark:bg-card border border-border shadow-sm flex items-center justify-center" style="animation-delay: 0.4s"><?= icon_svg('Globe', 'h-4.5 w-4.5 text-slate-400') ?></div>
              <div class="gpl-site absolute -bottom-10 -left-6 h-6 w-6 rounded-lg bg-white dark:bg-card border border-border shadow-sm flex items-center justify-center" style="animation-delay: 0.6s"><?= icon_svg('Globe', 'h-3 w-3 text-slate-400') ?></div>
            </div>
          </div>
          <div class="p-6 pb-8 flex flex-col flex-1">
            <span class="font-mono text-[10px] text-muted-foreground mb-3">06</span>
            <h3 class="font-display text-2xl mb-2 text-slate-900 dark:text-white tracking-tight">GPL — Unlimited Use</h3>
            <p class="text-sm text-slate-500 dark:text-slate-400 leading-relaxed font-medium">Use on as many sites as you like. No license keys, no activations, no nagging upgrade prompts. Ever.</p>
          </div>
        </div>

      </div>
    </div>
  </section>

  <section class="py-24">
    <div class="container">
      <div class="relative rounded-[3rem] p-12 md:p-24 overflow-hidden border border-border shadow-soft bg-[hsl(var(--primary)/0.02)] dark:bg-card transition-colors reveal-on-scroll reveal-scale">
        <div class="absolute inset-0 block dark:hidden pointer-events-none overflow-hidden">
          <div class="absolute inset-0 bg-gradient-to-r from-transparent via-[hsl(var(--primary)/0.05)] to-transparent"></div>
          <div class="absolute -right-[10%] -bottom-[30%] w-[60%] h-[120%] bg-gradient-to-tl from-primary/10 via-primary/5 to-transparent rounded-[100%] blur-[80px] origin-bottom-right -rotate-12 opacity-50"></div>
          <div class="absolute -right-[5%] top-[10%] w-[40%] h-[80%] bg-gradient-to-l from-primary/5 to-transparent rounded-full blur-[60px] opacity-40"></div>
          <div class="absolute left-[10%] top-0 w-[50%] h-[50%] bg-gradient-to-b from-primary/5 to-transparent rounded-full blur-[60px] opacity-30"></div>
        </div>
        <div class="absolute inset-0 hidden dark:block pointer-events-none overflow-hidden">
          <div class="absolute -right-[10%] -bottom-[30%] w-[60%] h-[120%] bg-gradient-to-tl from-primary/20 via-primary/10 to-transparent rounded-[100%] blur-[80px] origin-bottom-right -rotate-12 opacity-80"></div>
          <div class="absolute -right-[5%] top-[10%] w-[40%] h-[80%] bg-gradient-to-l from-primary/10 to-transparent rounded-full blur-[60px] opacity-70"></div>
          <div class="absolute left-[10%] top-0 w-[50%] h-[50%] bg-gradient-to-b from-primary/10 to-transparent rounded-full blur-[60px] opacity-50"></div>
        </div>
        <div class="relative z-10 max-w-3xl">
          <div class="inline-flex items-center gap-2 rounded-full border border-primary/20 bg-primary/10 px-3 py-1.5 text-[10px] font-mono font-bold uppercase tracking-widest text-primary">
            <?= icon_svg('Sparkles', 'h-3 w-3') ?>
            Limited offer · 50% off bundles
          </div>
          <h2 class="mt-8 font-display text-5xl md:text-7xl leading-[1.05] tracking-tight text-slate-900 dark:text-white transition-colors">
            Stop paying license fees.<br><span class="text-primary">Start shipping.</span>
          </h2>
          <p class="mt-6 text-lg text-slate-600 dark:text-slate-400 max-w-xl transition-colors">Join 28,000+ developers, agencies, and freelancers who build faster with the PixelVault marketplace.</p>
          <div class="mt-12 flex flex-wrap items-center gap-6">
            <a href="/marketplace" class="group inline-flex items-center gap-3 rounded-full bg-primary text-primary-foreground pl-8 pr-3 py-3 text-base font-bold tracking-wide shadow-glow hover:brightness-105 hover:-translate-y-0.5 transition-all">
              Browse the Vault
              <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white text-primary transition-transform group-hover:translate-x-1">
                <?= icon_svg('ArrowRight', 'h-4 w-4 stroke-[3]') ?>
              </span>
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="contact" class="py-24 relative overflow-hidden bg-background">
    <div class="container relative z-10">
      <div class="grid md:grid-cols-2 gap-16 lg:gap-24 items-center reveal-on-scroll reveal-slide-up">
        <div>
          <div class="relative inline-flex overflow-hidden rounded-full p-[1px] mb-6 shadow-sm">
            <span class="absolute inset-[-1000%] animate-[spin_3s_linear_infinite] bg-[conic-gradient(from_90deg_at_50%_50%,hsl(var(--primary))_0%,transparent_50%,hsl(var(--primary))_100%)] opacity-70"></span>
            <span class="relative inline-flex items-center justify-center rounded-full bg-background px-4 py-1.5 text-xs font-mono uppercase tracking-[0.2em] text-primary">
              Get in touch
            </span>
          </div>
          <h2 class="font-display text-5xl md:text-6xl tracking-tight text-balance mb-6">Let's build something <br><span class="text-primary">extraordinary.</span></h2>
          <p class="text-muted-foreground text-lg max-w-md mb-12 leading-relaxed">Have questions about licensing, custom integrations, or just want to explore possibilities? We'd love to hear from you.</p>
          <div class="space-y-8">
            <div class="flex items-start gap-4 group cursor-default">
              <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-secondary/50 border border-border text-primary transition-all group-hover:border-primary group-hover:bg-primary/5"><?= icon_svg('Mail', 'h-5 w-5') ?></div>
              <div>
                <h4 class="font-bold text-foreground">Chat with us</h4>
                <p class="text-sm text-muted-foreground mt-1">Our friendly team is here to help.</p>
                <a href="mailto:hello@pixelvault.studio" class="text-sm font-medium text-primary mt-2 inline-block hover:underline underline-offset-4">hello@pixelvault.studio</a>
              </div>
            </div>
            <div class="flex items-start gap-4 group cursor-default">
              <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-secondary/50 border border-border text-primary transition-all group-hover:border-primary group-hover:bg-primary/5"><?= icon_svg('MapPin', 'h-5 w-5') ?></div>
              <div>
                <h4 class="font-bold text-foreground">Visit our studio</h4>
                <p class="text-sm text-muted-foreground mt-1">Come say hello at our HQ.</p>
                <p class="text-sm font-medium text-foreground mt-2 inline-block"><?= e(site_config('contact_address')) ?></p>
              </div>
            </div>
          </div>
        </div>
        <div class="relative">
          <div class="absolute -inset-8 bg-gradient-to-tr from-primary/20 via-transparent to-transparent rounded-[3rem] blur-3xl opacity-50 pointer-events-none"></div>
          <form method="POST" action="/contact" class="relative bg-card border border-border p-8 md:p-12 rounded-[2.5rem] shadow-elevated dark:shadow-glow flex flex-col gap-6">
            <?php if (query('contact') === 'success'): ?>
              <div class="bg-emerald-500/10 text-emerald-600 p-4 rounded-xl border border-emerald-500/30 text-sm font-medium flex items-center gap-3 animate-fade-in">
                <?= icon_svg('Check', 'h-5 w-5') ?>
                Message sent successfully! We'll get back to you soon.
              </div>
            <?php elseif (query('contact') === 'error'): ?>
              <div class="bg-destructive/10 text-destructive p-4 rounded-xl border border-destructive/30 text-sm font-medium flex items-center gap-3 animate-fade-in">
                <?= icon_svg('X', 'h-5 w-5') ?>
                Failed to send message. Please try again.
              </div>
            <?php endif; ?>

            <div class="grid grid-cols-2 gap-6">
              <div class="space-y-2">
                <label class="text-sm font-medium text-muted-foreground ml-1">First name</label>
                <input type="text" name="first_name" required value="<?= e(explode(' ', (string)($user['name'] ?? ''))[0]) ?>" class="w-full bg-background border border-border rounded-xl px-4 py-3.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="John">
              </div>
              <div class="space-y-2">
                <label class="text-sm font-medium text-muted-foreground ml-1">Last name</label>
                <?php 
                  $nameParts = explode(' ', (string)($user['name'] ?? ''));
                  $lastName = count($nameParts) > 1 ? end($nameParts) : '';
                ?>
                <input type="text" name="last_name" required value="<?= e($lastName) ?>" class="w-full bg-background border border-border rounded-xl px-4 py-3.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="Doe">
              </div>
            </div>
            <div class="space-y-2">
              <label class="text-sm font-medium text-muted-foreground ml-1">Email address</label>
              <input type="email" name="email" required value="<?= e($user['email'] ?? '') ?>" class="w-full bg-background border border-border rounded-xl px-4 py-3.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="johndoe@example.com">
            </div>
            <div class="space-y-2">
              <label class="text-sm font-medium text-muted-foreground ml-1">Subject</label>
              <input type="text" name="subject" required class="w-full bg-background border border-border rounded-xl px-4 py-3.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all" placeholder="How can we help?" value="<?= e(urldecode((string)(query('subject') ?? ''))) ?>">
            </div>
            <div class="space-y-2">
              <label class="text-sm font-medium text-muted-foreground ml-1">Message</label>
              <textarea name="message" required rows="4" class="w-full bg-background border border-border rounded-xl px-4 py-3.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all resize-none" placeholder="Tell us about your project..."><?= e(urldecode((string)(query('message') ?? ''))) ?></textarea>
            </div>
            <button type="submit" class="group inline-flex w-full items-center justify-center gap-2 rounded-xl bg-primary text-primary-foreground px-6 py-4 text-sm font-bold tracking-wide shadow-glow hover:brightness-105 transition-all mt-4">
              Send Message
              <?= icon_svg('Send', 'h-4 w-4') ?>
            </button>
          </form>
        </div>
      </div>
    </div>
  </section>
</main>
<?php require BASE_PATH . '/app/Views/partials/footer.php'; ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const subject = urlParams.get('subject');
    if (subject) {
        // Fill the subject field
        const subjectInput = document.querySelector('input[name="subject"]');
        if (subjectInput && !subjectInput.value) {
            subjectInput.value = decodeURIComponent(subject);
        }
        // Pre-fill a helpful message if it's an extension request
        const isExtension = subject.toLowerCase().includes('extension request');
        const messageBox = document.querySelector('textarea[name="message"]');
        if (messageBox && isExtension) {
            const defaultMsg = 'Hi, I have reached my download limit for this product and would like to request an extension for future updates. Thank you!';
            messageBox.placeholder = defaultMsg;
            messageBox.value = defaultMsg;
        }
        // Scroll to the contact form smoothly
        const contactSection = document.getElementById('contact');
        if (contactSection) {
            contactSection.scrollIntoView({ behavior: 'smooth' });
        }
    }
});
</script>