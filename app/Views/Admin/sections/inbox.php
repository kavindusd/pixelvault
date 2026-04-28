<?php
// Fallback: fetch directly from DB in case $inquiries wasn't injected
if (!isset($inquiries)) {
    try {
        $inquiries = \App\Core\Database::connection()->query("SELECT * FROM inquiries ORDER BY created_at DESC")->fetchAll();
    } catch (\Throwable $e) {
        $inquiries = [];
    }
}
?>
<div class="space-y-6 animate-in fade-in slide-in-from-bottom-4 duration-700">
  <div class="flex items-center justify-between">
    <div>
      <h2 class="text-3xl font-bold tracking-tight font-display">Customer Inquiries</h2>
      <p class="text-muted-foreground mt-1 text-sm font-medium">Manage support messages and extension requests.</p>
    </div>
    <div class="flex items-center gap-2 px-4 py-2 bg-secondary/50 border border-border rounded-xl text-xs font-bold uppercase tracking-widest text-muted-foreground">
      <?= icon_svg('Inbox', 'h-3.5 w-3.5') ?>
      <?= count($inquiries) ?> Messages
    </div>
  </div>

  <div class="grid gap-6">
    <div class="rounded-[2rem] border border-border/60 bg-card/50 backdrop-blur-md overflow-hidden shadow-elevated transition-all duration-500 hover:shadow-2xl hover:shadow-primary/5">
      <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
          <thead>
            <tr class="border-b border-border/40 bg-secondary/30">
              <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-[0.2em] text-muted-foreground">Sender</th>
              <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-[0.2em] text-muted-foreground">Subject & Message</th>
              <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-[0.2em] text-muted-foreground text-center">Type</th>
              <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-[0.2em] text-muted-foreground">Date</th>
              <th class="px-6 py-5 text-[10px] font-bold uppercase tracking-[0.2em] text-muted-foreground text-right">Action</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-border/30">
            <?php if (empty($inquiries)): ?>
              <tr>
                <td colspan="5" class="px-6 py-20 text-center">
                  <div class="flex flex-col items-center gap-3">
                    <div class="h-12 w-12 rounded-full bg-secondary flex items-center justify-center text-muted-foreground">
                      <?= icon_svg('Inbox', 'h-6 w-6') ?>
                    </div>
                    <p class="text-sm font-medium text-muted-foreground">No inquiries found yet.</p>
                  </div>
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($inquiries as $msg): ?>
                <?php $isReq = ($msg['type'] === 'extension_request'); ?>
                <tr class="group hover:bg-secondary/20 transition-all duration-300">
                  <td class="px-6 py-6">
                    <div class="flex items-center gap-3">
                      <div class="h-10 w-10 rounded-full bg-gradient-to-br <?= $isReq ? 'from-blue-500/20 to-blue-600/10' : 'from-primary/20 to-primary/10' ?> flex items-center justify-center font-bold text-xs text-foreground">
                        <?= strtoupper(substr($msg['name'], 0, 1)) ?>
                      </div>
                      <div class="flex flex-col">
                        <span class="font-bold text-sm tracking-tight"><?= e($msg['name']) ?></span>
                        <span class="text-[11px] text-muted-foreground font-mono"><?= e($msg['email']) ?></span>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-6">
                    <div class="max-w-md space-y-1">
                      <div class="flex items-center gap-2">
                        <?php if ($isReq): ?>
                          <span class="text-[9px] font-black uppercase tracking-tighter bg-blue-500 text-white px-1.5 py-0.5 rounded shadow-sm">Request</span>
                        <?php endif; ?>
                        <span class="font-bold text-sm truncate"><?= e($msg['subject']) ?></span>
                      </div>
                      <p class="text-xs text-muted-foreground line-clamp-2 leading-relaxed"><?= e($msg['message']) ?></p>
                    </div>
                  </td>
                  <td class="px-6 py-6 text-center">
                    <?php if ($isReq): ?>
                      <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-500/10 text-blue-600 border border-blue-500/20 text-[10px] font-bold uppercase tracking-wider">
                        <?= icon_svg('ArrowUpRight', 'h-3 w-3') ?>
                        Extension
                      </div>
                    <?php else: ?>
                      <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-600 border border-emerald-500/20 text-[10px] font-bold uppercase tracking-wider">
                        <?= icon_svg('MessageSquare', 'h-3 w-3') ?>
                        Support
                      </div>
                    <?php endif; ?>
                  </td>
                  <td class="px-6 py-6">
                    <span class="text-[11px] font-medium text-muted-foreground uppercase tracking-widest"><?= date('M d, H:i', strtotime($msg['created_at'])) ?></span>
                  </td>
                  <td class="px-6 py-6 text-right">
                    <div class="flex items-center justify-end gap-2">
                      <?php if ($isReq): ?>
                        <?php 
                          $productName = '';
                          if (preg_match('/Extension Request for (.*)/i', $msg['subject'], $matches)) {
                              $productName = trim($matches[1]);
                          }
                          $matchingProd = null;
                          if ($productName !== '') {
                              foreach (($products ?? []) as $p) {
                                  if (strcasecmp((string)($p['name'] ?? ''), $productName) === 0) {
                                      $matchingProd = $p;
                                      break;
                                  }
                              }
                          }
                          $approveUrl = "/admin?tab=users&email=" . urlencode($msg['email']) . ($matchingProd ? "&product_id=" . $matchingProd['id'] : "");
                        ?>
                        <a href="<?= $approveUrl ?>" class="h-9 px-3 rounded-xl bg-blue-500 text-white hover:shadow-glow hover:shadow-blue-500/20 text-[10px] font-bold flex items-center gap-1.5 transition-all">
                          <?= icon_svg('Check', 'h-3.5 w-3.5') ?> Approve
                        </a>
                        <form method="POST" action="/admin/inquiries/delete" onsubmit="return confirm('Reject this request?');">
                          <input type="hidden" name="id" value="<?= $msg['id'] ?>">
                          <button type="submit" class="h-9 px-3 rounded-xl bg-red-500/10 text-red-600 hover:bg-red-500 hover:text-white text-[10px] font-bold flex items-center gap-1.5 transition-all">
                            <?= icon_svg('X', 'h-3.5 w-3.5') ?> Reject
                          </button>
                        </form>
                      <?php else: ?>
                        <a href="mailto:<?= e($msg['email']) ?>" class="h-9 px-3 rounded-xl bg-ink text-ink-foreground hover:shadow-glow text-[10px] font-bold flex items-center gap-1.5 transition-all">
                          <?= icon_svg('Mail', 'h-3.5 w-3.5') ?> Reply
                        </a>
                        <form method="POST" action="/admin/inquiries/delete" onsubmit="return confirm('Remove this message?');">
                          <input type="hidden" name="id" value="<?= $msg['id'] ?>">
                          <button type="submit" class="h-9 px-3 rounded-xl bg-secondary text-muted-foreground hover:bg-red-500 hover:text-white text-[10px] font-bold flex items-center gap-1.5 transition-all">
                            <?= icon_svg('Trash', 'h-3.5 w-3.5') ?> Remove
                          </button>
                        </form>
                      <?php endif; ?>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
