<?php
use App\Models\SiteConfigModel;
$configModel = new SiteConfigModel();
$groups = $configModel->getAllGroups();
$activeGroup = query('group', $groups[0] ?? 'branding');
$configs = $configModel->getByGroup($activeGroup);

// For the emails tab, index configs by key for easy access
$configsByKey = [];
foreach ($configs as $cfg) {
    $configsByKey[$cfg['key']] = $cfg;
}
?>

<div class="space-y-8 animate-fade-in">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="font-display text-4xl font-bold tracking-tight">Site <span class="text-primary">Configuration.</span></h1>
            <p class="text-muted-foreground mt-2 text-sm">Manage global settings, branding, and dynamic content.</p>
        </div>
        <div class="flex items-center gap-2 bg-secondary/50 p-1 rounded-xl border border-border/50 flex-wrap">
            <?php foreach ($groups as $group): ?>
                <a href="/admin?tab=site_settings&group=<?= e($group) ?>" 
                   class="px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-widest transition-all
                          <?= $activeGroup === $group ? 'bg-primary text-primary-foreground shadow-glow' : 'hover:bg-secondary text-muted-foreground' ?>">
                    <?= e(ucfirst($group)) ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <?php if ($activeGroup === 'emails'): ?>
    <!-- ================================================================
         EMAILS TAB — custom two-section layout
         ================================================================ -->

    <?php if (query('status') === 'saved'): ?>
        <div class="flex items-center gap-3 px-5 py-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-600 text-sm font-medium">
            <?= icon_svg('CheckCircle2', 'h-5 w-5 shrink-0') ?>
            Email settings saved successfully.
        </div>
    <?php endif; ?>

    <form action="/admin/save-config" method="POST" class="space-y-6">
        <input type="hidden" name="group" value="emails">

        <!-- ── Section 1: Receiving Email ── -->
        <div class="p-8 rounded-[2rem] border border-border bg-card shadow-soft relative overflow-hidden">
            <div class="flex items-center gap-3 mb-8">
                <div class="h-10 w-10 rounded-xl bg-blue-500/10 flex items-center justify-center shrink-0">
                    <?= icon_svg('Inbox', 'h-5 w-5 text-blue-500') ?>
                </div>
                <div>
                    <h3 class="font-bold text-base">Receiving Email</h3>
                    <p class="text-xs text-muted-foreground mt-0.5">The inbox address where contact form submissions are delivered.</p>
                </div>
            </div>

            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <label class="text-[10px] font-bold uppercase tracking-[0.2em] text-muted-foreground">
                        Contact Receiver Email
                    </label>
                    <code class="text-[9px] text-primary/60 font-mono">admin_contact_email</code>
                </div>
                <input type="text"
                       name="config[admin_contact_email]"
                       value="<?= e($configsByKey['admin_contact_email']['value'] ?? '') ?>"
                       placeholder="e.g. inbox@yourdomain.com"
                       class="w-full bg-secondary/30 border border-border rounded-xl px-5 py-4 outline-none focus:border-primary transition-all text-sm">
                <p class="text-[10px] text-muted-foreground">All messages submitted via the website contact form will be sent to this address.</p>
            </div>
        </div>

        <!-- ── Section 2: Sending / SMTP ── -->
        <div class="p-8 rounded-[2rem] border border-border bg-card shadow-soft relative overflow-hidden">
            <div class="flex items-center gap-3 mb-8">
                <div class="h-10 w-10 rounded-xl bg-primary/10 flex items-center justify-center shrink-0">
                    <?= icon_svg('Send', 'h-5 w-5 text-primary') ?>
                </div>
                <div>
                    <h3 class="font-bold text-base">Sending Email / SMTP</h3>
                    <p class="text-xs text-muted-foreground mt-0.5">Used for outgoing emails: update notifications, password resets, and verification codes.</p>
                </div>
            </div>

            <div class="grid gap-8">

                <!-- Notification Sender Identity -->
                <div class="p-5 rounded-2xl bg-secondary/20 border border-border/60 space-y-6">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-muted-foreground">Sender Identity</p>

                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- notification_sender_email -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="text-[10px] font-bold uppercase tracking-[0.2em] text-muted-foreground">Notification Sender Email</label>
                                <code class="text-[9px] text-primary/60 font-mono">notification_sender_email</code>
                            </div>
                            <input type="text"
                                   name="config[notification_sender_email]"
                                   value="<?= e($configsByKey['notification_sender_email']['value'] ?? '') ?>"
                                   placeholder="e.g. updates@yourdomain.com"
                                   class="w-full bg-secondary/30 border border-border rounded-xl px-5 py-4 outline-none focus:border-primary transition-all text-sm">
                            <p class="text-[10px] text-muted-foreground">Shown in the "From" field of update notifications. Must be authorised by your SMTP account.</p>
                        </div>

                        <!-- notification_sender_name -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="text-[10px] font-bold uppercase tracking-[0.2em] text-muted-foreground">Notification Sender Name</label>
                                <code class="text-[9px] text-primary/60 font-mono">notification_sender_name</code>
                            </div>
                            <input type="text"
                                   name="config[notification_sender_name]"
                                   value="<?= e($configsByKey['notification_sender_name']['value'] ?? 'PixelVault Updates') ?>"
                                   placeholder="e.g. PixelVault Updates"
                                   class="w-full bg-secondary/30 border border-border rounded-xl px-5 py-4 outline-none focus:border-primary transition-all text-sm">
                        </div>
                    </div>
                </div>

                <!-- SMTP Connection Settings -->
                <div class="p-5 rounded-2xl bg-secondary/20 border border-border/60 space-y-6">
                    <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-muted-foreground">SMTP Connection</p>

                    <div class="grid md:grid-cols-3 gap-6">
                        <!-- smtp_host -->
                        <div class="md:col-span-2 space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="text-[10px] font-bold uppercase tracking-[0.2em] text-muted-foreground">SMTP Host</label>
                                <code class="text-[9px] text-primary/60 font-mono">smtp_host</code>
                            </div>
                            <input type="text"
                                   name="config[smtp_host]"
                                   value="<?= e($configsByKey['smtp_host']['value'] ?? 'smtp.gmail.com') ?>"
                                   placeholder="smtp.gmail.com"
                                   class="w-full bg-secondary/30 border border-border rounded-xl px-5 py-4 outline-none focus:border-primary transition-all text-sm font-mono">
                        </div>

                        <!-- smtp_port -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="text-[10px] font-bold uppercase tracking-[0.2em] text-muted-foreground">Port</label>
                                <code class="text-[9px] text-primary/60 font-mono">smtp_port</code>
                            </div>
                            <input type="text"
                                   name="config[smtp_port]"
                                   value="<?= e($configsByKey['smtp_port']['value'] ?? '587') ?>"
                                   placeholder="587"
                                   class="w-full bg-secondary/30 border border-border rounded-xl px-5 py-4 outline-none focus:border-primary transition-all text-sm font-mono">
                        </div>
                    </div>

                    <!-- smtp_encryption -->
                    <div class="space-y-3">
                        <label class="text-[10px] font-bold uppercase tracking-[0.2em] text-muted-foreground">Encryption</label>
                        <div class="flex gap-4">
                            <?php foreach (['tls' => 'TLS (port 587 — recommended)', 'ssl' => 'SSL (port 465)', 'none' => 'None'] as $val => $label): ?>
                                <label class="flex items-center gap-2 cursor-pointer group">
                                    <input type="radio"
                                           name="config[smtp_encryption]"
                                           value="<?= $val ?>"
                                           <?= (($configsByKey['smtp_encryption']['value'] ?? 'tls') === $val) ? 'checked' : '' ?>
                                           class="accent-primary">
                                    <span class="text-sm"><?= $label ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                        <input type="hidden" name="config[smtp_encryption]" value="<?= e($configsByKey['smtp_encryption']['value'] ?? 'tls') ?>" id="smtp_enc_hidden">
                        <script>
                            document.querySelectorAll('input[name="config[smtp_encryption]"][type=radio]').forEach(function(r){
                                r.addEventListener('change', function(){
                                    document.getElementById('smtp_enc_hidden').disabled = true;
                                });
                            });
                        </script>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- smtp_user -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="text-[10px] font-bold uppercase tracking-[0.2em] text-muted-foreground">SMTP Username</label>
                                <code class="text-[9px] text-primary/60 font-mono">smtp_user</code>
                            </div>
                            <input type="text"
                                   name="config[smtp_user]"
                                   value="<?= e($configsByKey['smtp_user']['value'] ?? '') ?>"
                                   placeholder="you@gmail.com"
                                   autocomplete="off"
                                   class="w-full bg-secondary/30 border border-border rounded-xl px-5 py-4 outline-none focus:border-primary transition-all text-sm font-mono">
                        </div>

                        <!-- smtp_pass -->
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <label class="text-[10px] font-bold uppercase tracking-[0.2em] text-muted-foreground">SMTP Password / App Password</label>
                                <code class="text-[9px] text-primary/60 font-mono">smtp_pass</code>
                            </div>
                            <input type="password"
                                   name="config[smtp_pass]"
                                   value="<?= e($configsByKey['smtp_pass']['value'] ?? '') ?>"
                                   placeholder="Leave blank to keep current"
                                   autocomplete="new-password"
                                   class="w-full bg-secondary/30 border border-border rounded-xl px-5 py-4 outline-none focus:border-primary transition-all text-sm font-mono">
                            <p class="text-[10px] text-amber-500">⚠ For Gmail, use an App Password (not your account password). The SMTP username is used as the envelope sender — it must match your authenticated mail account.</p>
                        </div>
                    </div>
                </div>

                <!-- Quick-reference tips -->
                <div class="grid md:grid-cols-3 gap-4">
                    <?php
                    $providers = [
                        ['name' => 'Gmail',      'host' => 'smtp.gmail.com',      'port' => '587', 'enc' => 'TLS', 'note' => 'Requires App Password'],
                        ['name' => 'Outlook/365','host' => 'smtp.office365.com',  'port' => '587', 'enc' => 'TLS', 'note' => 'Use full email as username'],
                        ['name' => 'Mailgun',    'host' => 'smtp.mailgun.org',    'port' => '587', 'enc' => 'TLS', 'note' => 'Use SMTP credentials from Mailgun dashboard'],
                    ];
                    foreach ($providers as $p):
                    ?>
                    <div class="p-4 rounded-xl bg-secondary/20 border border-border/50 space-y-1">
                        <p class="text-[10px] font-bold uppercase tracking-widest text-muted-foreground"><?= $p['name'] ?></p>
                        <p class="text-xs font-mono"><?= $p['host'] ?></p>
                        <p class="text-[11px] text-muted-foreground">Port <?= $p['port'] ?> · <?= $p['enc'] ?></p>
                        <p class="text-[10px] text-primary/70"><?= $p['note'] ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>

            </div>
        </div>

        <div class="pt-2 flex items-center justify-between">
            <p class="text-xs text-muted-foreground italic">Changes will reflect instantly after saving.</p>
            <button type="submit" class="px-10 py-4 rounded-xl bg-ink text-ink-foreground font-bold text-xs uppercase tracking-widest shadow-ink hover:scale-[1.02] transition-all">
                Save Email Settings
            </button>
        </div>
    </form>

    <?php else: ?>
    <!-- ================================================================
         ALL OTHER TABS — original generic rendering unchanged
         ================================================================ -->

    <div class="p-8 rounded-[2rem] border border-border bg-card shadow-soft relative overflow-hidden">
        <form action="/admin/save-config" method="POST" enctype="multipart/form-data" class="space-y-8">
            <input type="hidden" name="group" value="<?= e($activeGroup) ?>">
            
            <div class="grid gap-8">
                <?php foreach ($configs as $config): ?>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <label class="text-[10px] font-bold uppercase tracking-[0.2em] text-muted-foreground">
                                <?= e($config['label'] ?? $config['key']) ?>
                            </label>
                            <code class="text-[9px] text-primary/60 font-mono"><?= e($config['key']) ?></code>
                        </div>
                        
                        <?php if ($config['type'] === 'file'): ?>
                            <div class="flex items-center gap-6 p-6 rounded-2xl bg-secondary/20 border border-border">
                                <div class="h-16 w-16 rounded-xl bg-background border border-border flex items-center justify-center overflow-hidden">
                                    <?php if ($config['value']): ?>
                                        <img src="<?= e($config['value']) ?>" alt="Preview" class="max-h-full max-w-full object-contain">
                                    <?php else: ?>
                                        <?= icon_svg('Image', 'h-6 w-6 text-muted-foreground') ?>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-1 space-y-2">
                                    <input type="file" name="files[<?= e($config['key']) ?>]" 
                                           accept=".png,.jpg,.jpeg,.webp,.svg"
                                           class="text-xs text-muted-foreground file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-primary file:text-primary-foreground hover:file:opacity-90"
                                    >
                                    <p class="text-[10px] text-muted-foreground">Recommended: .svg or .png with transparent background.</p>
                                </div>
                            </div>
                        <?php elseif ($config['key'] === 'site_theme'): ?>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <?php
                                $themes = [
                                    'vivid_orange'    => ['name' => 'Sunset Flare',   'primary' => '#ff4d00', 'bg' => '#FBF9F7'],
                                    'emerald_forest'  => ['name' => 'Electric Mint',  'primary' => '#00FAAA', 'bg' => '#F7FBF9'],
                                    'midnight_purple' => ['name' => 'Cosmic Dream',   'primary' => '#8F5FFF', 'bg' => '#F9F7FB'],
                                    'ocean_blue'      => ['name' => 'Royal Galaxy',   'primary' => '#0050D8', 'bg' => '#F7F9FB'],
                                    'cyber_rose'      => ['name' => 'Crimson Night',  'primary' => '#E8003A', 'bg' => '#FBF7F8'],
                                    'golden_amber'    => ['name' => 'Digital Violet', 'primary' => '#3B00FF', 'bg' => '#FBF9F7'],
                                ];
                                foreach ($themes as $id => $t):
                                    $isSelected = $config['value'] === $id;
                                ?>
                                    <label class="cursor-pointer group relative">
                                        <input type="radio" name="config[<?= e($config['key']) ?>]" value="<?= e($id) ?>" 
                                               class="peer sr-only" <?= $isSelected ? 'checked' : '' ?>
                                        >
                                        <div class="p-4 rounded-2xl border-2 transition-all flex flex-col items-center gap-3
                                                    peer-checked:border-primary peer-checked:bg-primary/5 border-border hover:border-primary/40">
                                            <div class="flex gap-1">
                                                <div class="h-6 w-6 rounded-full shadow-sm" style="background: <?= $t['primary'] ?>"></div>
                                                <div class="h-6 w-6 rounded-full shadow-sm border border-border/50" style="background: <?= $t['bg'] ?>"></div>
                                            </div>
                                            <span class="text-[10px] font-bold uppercase tracking-widest text-center"><?= e($t['name']) ?></span>
                                            <?php if ($isSelected): ?>
                                                <div class="absolute top-2 right-2 text-primary"><?= icon_svg('CheckCircle2', 'h-4 w-4') ?></div>
                                            <?php endif; ?>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        <?php elseif ($config['type'] === 'color'): ?>
                            <div class="space-y-4">
                                <div class="flex items-center gap-4">
                                    <input type="color" name="config[<?= e($config['key']) ?>]" 
                                           id="color-picker-<?= e($config['key']) ?>"
                                           value="<?= e($config['value']) ?>"
                                           class="h-12 w-24 rounded-lg bg-secondary/30 border border-border cursor-pointer"
                                           oninput="document.getElementById('hex-input-<?= e($config['key']) ?>').value = this.value.toUpperCase()"
                                    >
                                    <input type="text" id="hex-input-<?= e($config['key']) ?>"
                                           value="<?= e($config['value']) ?>" 
                                           class="flex-1 bg-secondary/30 border border-border rounded-xl px-5 py-3 outline-none focus:border-primary font-mono text-sm uppercase"
                                           oninput="document.getElementById('color-picker-<?= e($config['key']) ?>').value = this.value"
                                    >
                                </div>
                                <div class="flex flex-wrap gap-3 p-4 rounded-2xl bg-secondary/20 border border-border/40">
                                    <div class="w-full text-[9px] font-bold uppercase tracking-widest text-muted-foreground mb-1">Accent Presets</div>
                                    <?php
                                    $accents = [
                                        ['name' => 'Vivid Orange',   'hex' => '#F97316'],
                                        ['name' => 'Emerald Green',  'hex' => '#10B981'],
                                        ['name' => 'Electric Purple','hex' => '#A855F7'],
                                        ['name' => 'Azure Blue',     'hex' => '#0EA5E9'],
                                        ['name' => 'Rose Pink',      'hex' => '#F43F5E'],
                                        ['name' => 'Lime Green',     'hex' => '#84CC16'],
                                        ['name' => 'Amber Glow',     'hex' => '#F59E0B'],
                                        ['name' => 'Royal Indigo',   'hex' => '#6366F1'],
                                    ];
                                    foreach ($accents as $p):
                                    ?>
                                        <button type="button" 
                                                class="group relative h-10 w-10 rounded-full border-2 border-background shadow-soft transition-all hover:scale-110 active:scale-95"
                                                style="background-color: <?= $p['hex'] ?>"
                                                title="<?= e($p['name']) ?>"
                                                onclick="const picker = document.getElementById('color-picker-<?= e($config['key']) ?>'); const input = document.getElementById('hex-input-<?= e($config['key']) ?>'); picker.value = '<?= $p['hex'] ?>'; input.value = '<?= $p['hex'] ?>'; picker.dispatchEvent(new Event('input'));"
                                        >
                                            <span class="absolute inset-0 rounded-full ring-2 ring-transparent group-hover:ring-primary/20 transition-all"></span>
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php elseif ($config['type'] === 'textarea'): ?>
                            <textarea name="config[<?= e($config['key']) ?>]" 
                                      class="w-full bg-secondary/30 border border-border rounded-xl px-5 py-4 outline-none focus:border-primary transition-all min-h-[120px] text-sm"
                            ><?= e($config['value']) ?></textarea>
                        <?php elseif ($config['key'] === 'smtp_pass'): ?>
                            <input type="password" name="config[<?= e($config['key']) ?>]"
                                   value="<?= e($config['value']) ?>"
                                   placeholder="Leave blank to keep current password"
                                   autocomplete="new-password"
                                   class="w-full bg-secondary/30 border border-border rounded-xl px-5 py-4 outline-none focus:border-primary transition-all text-sm font-mono"
                            >
                            <p class="text-[10px] text-amber-500 mt-1.5">⚠ Your SMTP User address is used as the sender. It must match your authenticated mail account to prevent spoofing rejections.</p>
                        <?php else: ?>
                            <input type="text" name="config[<?= e($config['key']) ?>]" 
                                   value="<?= e($config['value']) ?>"
                                   class="w-full bg-secondary/30 border border-border rounded-xl px-5 py-4 outline-none focus:border-primary transition-all text-sm"
                            >
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="pt-6 border-t border-border/50 flex items-center justify-between">
                <p class="text-xs text-muted-foreground italic">Changes will reflect across the site instantly after saving.</p>
                <button type="submit" class="px-10 py-4 rounded-xl bg-ink text-ink-foreground font-bold text-xs uppercase tracking-widest shadow-ink hover:scale-[1.02] transition-all">
                    Save <?= e(ucfirst($activeGroup)) ?> Settings
                </button>
            </div>
        </form>
    </div>

    <!-- Theme Preview -->
    <?php if ($activeGroup === 'branding'): ?>
        <div class="grid md:grid-cols-3 gap-6">
            <div class="p-6 rounded-2xl border border-border bg-card shadow-soft">
                <h4 class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-4">Color Palette</h4>
                <div class="flex gap-2">
                    <div class="h-10 w-10 rounded-lg bg-primary shadow-glow"></div>
                    <div class="h-10 w-10 rounded-lg bg-secondary"></div>
                    <div class="h-10 w-10 rounded-lg bg-ink"></div>
                </div>
            </div>
            <div class="md:col-span-2 p-6 rounded-2xl border border-border bg-card shadow-soft">
                <h4 class="text-xs font-bold uppercase tracking-widest text-muted-foreground mb-4">Typography Preview</h4>
                <div class="space-y-2">
                    <p class="font-display text-2xl tracking-tight">The quick brown fox jumps over the lazy dog.</p>
                    <p class="text-sm text-muted-foreground">System font hierarchy is automatically optimized for readability and aesthetics.</p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php endif; ?>
</div>