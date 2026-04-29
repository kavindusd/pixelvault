<?php
$activeKey = preg_replace('/[^a-z0-9_]/i', '', (string) (query('tpl') ?? 'update_notification')) ?: 'update_notification';
$tpls = $emailTemplates ?? [];
if (!isset($tpls[$activeKey])) {
    $activeKey = (string) array_key_first($tpls) ?: 'update_notification';
}
$active = $tpls[$activeKey] ?? ['subject' => '', 'body' => '', 'cta' => ''];
$saved = (string) (query('status') ?? '') === 'saved';

// Extend template variables based on active template
$vars = $templateVariables ?? [];
if ($activeKey === 'contact_form') {
    $vars = array_merge($vars, [
        ['tag' => '{{name}}', 'desc' => 'Sender Name'],
        ['tag' => '{{email}}', 'desc' => 'Sender Email'],
        ['tag' => '{{subject}}', 'desc' => 'Original Subject'],
        ['tag' => '{{message}}', 'desc' => 'User Message'],
    ]);
}
?>
<div class="space-y-6 reveal-on-scroll">
  <div class="flex items-end justify-between gap-4">
    <div>
      <h2 class="font-display text-2xl font-bold tracking-tight">Email Builder</h2>
      <p class="text-sm text-muted-foreground mt-0.5">Design automated update notifications.</p>
    </div>
    <?php if ($saved): ?>
      <div class="text-[10px] font-mono uppercase tracking-widest bg-emerald-500/10 text-emerald-600 px-3 py-1.5 rounded-md border border-emerald-500/30">Saved</div>
    <?php endif; ?>
  </div>

  <div class="flex flex-wrap gap-2">
    <?php foreach (array_keys($tpls) as $tplKey): ?>
      <a href="/admin?tab=email&tpl=<?= e($tplKey) ?>"
         class="px-3.5 py-1.5 rounded-md text-[11px] font-mono uppercase tracking-widest border transition-all
                <?= $tplKey === $activeKey ? 'bg-ink text-ink-foreground border-transparent shadow-soft' : 'bg-card border-border text-muted-foreground hover:text-foreground hover:border-foreground/30' ?>">
        <?= e(str_replace('_', ' ', $tplKey)) ?>
      </a>
    <?php endforeach; ?>
  </div>

  <form method="post" action="/admin/email/save" class="grid lg:grid-cols-12 gap-6">
    <input type="hidden" name="template_key" value="<?= e($activeKey) ?>">

    <div class="lg:col-span-7 space-y-4">
      <div class="bg-card border border-border rounded-lg overflow-hidden shadow-soft">
        <div class="px-5 py-3 border-b border-border bg-secondary/30 flex items-center gap-3">
          <?= icon_svg('Layout', 'h-4 w-4 text-primary') ?>
          <span class="text-[11px] font-mono uppercase tracking-widest">Email content</span>
        </div>
        <div class="p-6 space-y-5">
          <div class="space-y-1.5">
            <label class="text-[10px] font-mono uppercase tracking-widest text-muted-foreground">Subject</label>
            <input type="text" name="subject" required value="<?= e((string) ($active['subject'] ?? '')) ?>"
                   class="w-full px-4 py-3 rounded-md bg-background border border-border text-sm focus:outline-none focus:border-primary transition-colors">
          </div>
          <div class="space-y-1.5">
            <label class="text-[10px] font-mono uppercase tracking-widest text-muted-foreground">Body</label>
            <textarea name="body" rows="10"
                      class="w-full p-4 rounded-md bg-background border border-border text-sm font-mono leading-relaxed focus:outline-none focus:border-primary transition-colors"><?= e((string) ($active['body'] ?? '')) ?></textarea>
          </div>
          <div class="space-y-1.5">
            <label class="text-[10px] font-mono uppercase tracking-widest text-muted-foreground">Call to action</label>
            <input type="text" name="cta" value="<?= e((string) ($active['cta'] ?? '')) ?>"
                   class="w-full p-3 rounded-md bg-background border border-border text-sm focus:outline-none focus:border-primary transition-colors">
          </div>
          <div class="flex items-center justify-between pt-2 border-t border-border">
            <p class="text-[10px] text-muted-foreground font-mono">Saved to <span class="text-foreground">storage/data/email-templates.json</span></p>
            <button type="submit" class="inline-flex items-center gap-2 pv-btn-ink px-5 h-10 text-xs font-bold uppercase tracking-widest rounded-md">
              <?= icon_svg('Sparkles', 'h-3.5 w-3.5') ?>
              Save template
            </button>
          </div>
        </div>
      </div>
    </div>

    <div class="lg:col-span-5 space-y-4">
      <div class="bg-card border border-border p-6 rounded-lg shadow-soft">
        <div class="flex items-center gap-2 mb-5">
          <?= icon_svg('Variable', 'h-4 w-4 text-primary') ?>
          <h4 class="font-bold text-sm uppercase tracking-widest font-mono">Variable dictionary</h4>
        </div>
        <div class="grid grid-cols-1 gap-2">
          <?php foreach (($vars ?? []) as $v): ?>
            <div class="p-3 bg-secondary/40 rounded-md border border-border/60 hover:border-primary/40 transition-colors">
              <div class="font-mono text-[11px] font-bold text-primary mb-0.5"><?= e((string) ($v['tag'] ?? '')) ?></div>
              <div class="text-[10px] text-muted-foreground"><?= e((string) ($v['desc'] ?? '')) ?></div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </form>
</div>
