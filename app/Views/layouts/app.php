<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? site_config('site_name', 'PixelVault')) ?></title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS Configuration -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    container: {
                        center: true,
                        padding: {
                            DEFAULT: '1.25rem',
                            sm: '2rem',
                            lg: '4rem',
                        },
                    },
                    colors: {
                        border: "hsl(var(--border))",
                        input: "hsl(var(--input))",
                        ring: "hsl(var(--ring))",
                        background: "hsl(var(--background))",
                        foreground: "hsl(var(--foreground))",
                        primary: {
                            DEFAULT: "hsl(var(--primary))",
                            foreground: "hsl(var(--primary-foreground))",
                        },
                        secondary: {
                            DEFAULT: "hsl(var(--secondary))",
                            foreground: "hsl(var(--secondary-foreground))",
                        },
                        destructive: {
                            DEFAULT: "hsl(var(--destructive))",
                            foreground: "hsl(var(--destructive-foreground))",
                        },
                        muted: {
                            DEFAULT: "hsl(var(--muted))",
                            foreground: "hsl(var(--muted-foreground))",
                        },
                        accent: {
                            DEFAULT: "hsl(var(--accent))",
                            foreground: "hsl(var(--accent-foreground))",
                        },
                        popover: {
                            DEFAULT: "hsl(var(--popover))",
                            foreground: "hsl(var(--popover-foreground))",
                        },
                        card: {
                            DEFAULT: "hsl(var(--card))",
                            foreground: "hsl(var(--card-foreground))",
                        },
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'Inter', 'sans-serif'],
                        display: ['"Outfit"', 'sans-serif'],
                        serif: ['"Outfit"', 'sans-serif'],
                        navbar: ['"Plus Jakarta Sans"', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                    boxShadow: {
                        'soft': 'var(--shadow-soft)',
                        'elevated': 'var(--shadow-elevated)',
                        'ink': 'var(--shadow-ink)',
                        'glow': 'var(--shadow-glow)',
                    }
                }
            }
        }
    </script>
    
    <style type="text/tailwindcss">
        @layer base {
            body {
                @apply font-sans;
            }
            h1, h2, h3, h4, h5, h6 {
                @apply font-display;
            }
        }
        @layer components {
            .container {
                @apply mx-auto max-w-[1400px];
            }
        }
    </style>

    <?php require BASE_PATH . '/app/Views/partials/site_styles.php'; ?>
</head>
<body class="bg-background text-foreground antialiased selection:bg-primary/20 selection:text-primary min-h-screen preload">
    
    <?php require $viewPath; ?>

    <script>
        (function() {
            // Theme setup & persistence
            const updateTheme = (isDark) => {
                document.documentElement.classList.toggle('dark', isDark);
                localStorage.setItem('theme', isDark ? 'dark' : 'light');
                
                // Sync all theme toggle buttons
                document.querySelectorAll('[data-theme-toggle]').forEach(btn => {
                    btn.setAttribute('aria-pressed', isDark);
                    
                    // Toggle icons if they exist in the button
                    const sunIcon = btn.querySelector('[data-theme-icon-on]');
                    const moonIcon = btn.querySelector('[data-theme-icon-off]');
                    if (sunIcon && moonIcon) {
                        sunIcon.classList.toggle('hidden', isDark);
                        sunIcon.classList.toggle('flex', !isDark);
                        moonIcon.classList.toggle('hidden', !isDark);
                        moonIcon.classList.toggle('flex', isDark);
                    }
                });
            };

            const initialTheme = localStorage.getItem('theme') === 'dark' || 
                                (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches);
            updateTheme(initialTheme);

            // Global event delegation
            document.addEventListener('click', e => {
                // Theme toggle
                const themeBtn = e.target.closest('[data-theme-toggle]');
                if (themeBtn) {
                    const isDark = !document.documentElement.classList.contains('dark');
                    updateTheme(isDark);
                }
                
                // Mobile menu
                const menuBtn = e.target.closest('[data-menu-toggle]');
                if (menuBtn) {
                    const menu = document.querySelector('[data-mobile-menu]');
                    if (menu) {
                        const isHidden = menu.classList.toggle('hidden');
                        document.body.classList.toggle('overflow-hidden', !isHidden);
                    }
                }
            });

            // Scroll reveal animations
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                    }
                });
            }, { threshold: 0.1 });

            document.querySelectorAll('.reveal-on-scroll').forEach(el => observer.observe(el));
            
            // Header dynamics
            const header = document.querySelector('[data-site-header-shell]');
            if (header) {
                const handleScroll = () => {
                    header.classList.toggle('is-scrolled', window.scrollY > 20);
                };
                window.addEventListener('scroll', handleScroll, { passive: true });
                handleScroll();
            }

            // Remove preload class
            window.addEventListener('load', () => {
                setTimeout(() => {
                    document.body.classList.remove('preload');
                }, 100);
            });
        })();
    </script>
</body>
</html>
