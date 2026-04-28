<?php

declare(strict_types=1);

function app_config(string $key, mixed $default = null): mixed
{
    global $app;

    return $app?->config[$key] ?? $default;
}

function site_config(string $key, mixed $default = null): mixed
{
    static $model = null;
    if ($model === null) {
        $model = new App\Models\SiteConfigModel();
    }
    return $model->get($key, $default);
}

function base_url(string $path = ''): string
{
    $configUrl = site_config('site_url');
    if ($configUrl) {
        return rtrim((string) $configUrl, '/') . ($path !== '' ? '/' . ltrim($path, '/') : '');
    }

    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost:8000';
    
    return $protocol . '://' . $host . ($path !== '' ? '/' . ltrim($path, '/') : '');
}

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function public_path(string $path = ''): string
{
    return BASE_PATH . ($path !== '' ? '/' . ltrim($path, '/') : '');
}

function asset(string $path): string
{
    return '/' . ltrim($path, '/');
}

function build_asset(string $extension): string
{
    $matches = glob(public_path('build/assets/index-*.' . $extension));

    if (!$matches) {
        return '';
    }

    $asset = str_replace('\\', '/', substr($matches[0], strlen(BASE_PATH) + 1));

    return asset($asset);
}

function query(string $key, mixed $default = null): mixed
{
    return $_GET[$key] ?? $default;
}

function old(string $key, mixed $default = null): mixed
{
    return $_POST[$key] ?? $default;
}

function is_active_path(string $path, string $currentPath): bool
{
    return rtrim($path, '/') === rtrim($currentPath, '/')
        || ($path === '/' && $currentPath === '/');
}

function icon_svg(string $name, string $classes = 'h-4 w-4'): string
{
    $svg = match ($name) {
        'Sparkles' => '<path d="M12 3l1.8 4.2L18 9l-4.2 1.8L12 15l-1.8-4.2L6 9l4.2-1.8L12 3z"/><path d="M5 3l.8 1.7L7.5 5.5l-1.7.8L5 8l-.8-1.7L2.5 5.5l1.7-.8L5 3z"/><path d="M19 16l1 2 2 1-2 1-1 2-1-2-2-1 2-1 1-2z"/>',
        'ShoppingCart' => '<circle cx="9" cy="20" r="1"/><circle cx="17" cy="20" r="1"/><path d="M3 4h2l2.4 10.5a1 1 0 0 0 1 .8h8.8a1 1 0 0 0 1-.8L20 7H6"/>',
        'Menu' => '<path d="M4 7h16M4 12h16M4 17h16"/>',
        'X' => '<path d="M6 6l12 12M18 6L6 18"/>',
        'ArrowRight' => '<path d="M5 12h14"/><path d="M13 5l7 7-7 7"/>',
        'ArrowUpRight' => '<path d="M7 17L17 7"/><path d="M8 7h9v9"/>',
        'Star' => '<path d="M12 3.5l2.8 5.6 6.2.9-4.5 4.4 1.1 6.2L12 17.7 6.4 20.6l1.1-6.2L3 10l6.2-.9L12 3.5z"/>',
        'ChevronRight' => '<path d="M9 6l6 6-6 6"/>',
        'Layout' => '<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M9 4v16"/>',
        'LayoutDashboard' => '<path d="M3 13h8V3H3v10z"/><path d="M13 21h8V11h-8v10z"/><path d="M13 3h8v6h-8V3z"/><path d="M3 17h8v4H3v-4z"/>',
        'ShoppingBag' => '<path d="M6 8h12l-1 12H7L6 8z"/><path d="M9 8V6a3 3 0 0 1 6 0v2"/>',
        'Search' => '<circle cx="11" cy="11" r="7"/><path d="M20 20l-3.5-3.5"/>',
        'Mail' => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M4 7l8 6 8-6"/>',
        'BarChart3' => '<path d="M4 19V9"/><path d="M10 19V5"/><path d="M16 19v-7"/><path d="M22 19v-3"/>',
        'Palette' => '<path d="M12 3a9 9 0 1 0 0 18h1a3 3 0 0 0 0-6h-1a2 2 0 1 1 0-4h2a5 5 0 0 0 0-10h-2z"/><circle cx="7.5" cy="10.5" r=".8"/><circle cx="9.5" cy="7.5" r=".8"/><circle cx="14.5" cy="7.5" r=".8"/><circle cx="16.5" cy="11.5" r=".8"/>',
        'Shield' => '<path d="M12 3l7 3v5c0 5-3.4 8.3-7 10-3.6-1.7-7-5-7-10V6l7-3z"/>',
        'Zap' => '<path d="M13 2L4 14h6l-1 8 9-12h-6l1-8z"/>',
        'Settings' => '<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.8l.1.1a2 2 0 1 1-2.8 2.8l-.1-.1a1.7 1.7 0 0 0-1.8-.3 1.7 1.7 0 0 0-1 1.5V21a2 2 0 1 1-4 0v-.2a1.7 1.7 0 0 0-1-1.5 1.7 1.7 0 0 0-1.8.3l-.1.1a2 2 0 1 1-2.8-2.8l.1-.1a1.7 1.7 0 0 0 .3-1.8 1.7 1.7 0 0 0-1.5-1H3a2 2 0 1 1 0-4h.2a1.7 1.7 0 0 0 1.5-1 1.7 1.7 0 0 0-.3-1.8l-.1-.1a2 2 0 1 1 2.8-2.8l.1.1a1.7 1.7 0 0 0 1.8.3h.1a1.7 1.7 0 0 0 1-1.5V3a2 2 0 1 1 4 0v.2a1.7 1.7 0 0 0 1 1.5h.1a1.7 1.7 0 0 0 1.8-.3l.1-.1a2 2 0 1 1 2.8 2.8l-.1.1a1.7 1.7 0 0 0-.3 1.8v.1a1.7 1.7 0 0 0 1.5 1H21a2 2 0 1 1 0 4h-.2a1.7 1.7 0 0 0-1.5 1z"/>',
        'Package' => '<path d="M3 7l9-4 9 4-9 4-9-4z"/><path d="M3 7v10l9 4 9-4V7"/><path d="M12 11v10"/>',
        'TrendingUp' => '<path d="M3 17l6-6 4 4 8-8"/><path d="M14 7h7v7"/>',
        'BadgeCheck' => '<path d="M16 18l-4 3-4-3-5-1 1-5-1-5 5-1 4-3 4 3 5 1-1 5 1 5-5 1z"/><path d="M9 12l2 2 4-4"/>',
        'LogOut' => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="M16 17l5-5-5-5"/><path d="M21 12H9"/>',
        'User' => '<path d="M20 21a8 8 0 0 0-16 0"/><circle cx="12" cy="7" r="4"/>',
        'CreditCard' => '<rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20"/>',
        'Bell' => '<path d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.2V11a6 6 0 1 0-12 0v3.2a2 2 0 0 1-.6 1.4L4 17h5"/><path d="M10 17a2 2 0 0 0 4 0"/>',
        'Globe' => '<circle cx="12" cy="12" r="10"/><path d="M2 12h20"/><path d="M12 2a15 15 0 0 1 0 20"/><path d="M12 2a15 15 0 0 0 0 20"/>',
        'KeyRound' => '<circle cx="7.5" cy="15.5" r="5.5"/><path d="M13 15h8"/><path d="M17 13v4"/><path d="M20 13v4"/>',
        'Unlock' => '<rect x="3" y="11" width="18" height="10" rx="2"/><path d="M7 11V8a5 5 0 0 1 9.5-2"/>',
        'UploadCloud' => '<path d="M16 16l-4-4-4 4"/><path d="M12 12v9"/><path d="M20.4 18.4A5 5 0 0 0 18 9h-1.3A8 8 0 1 0 4 16.3"/>',
        'ExternalLink' => '<path d="M14 3h7v7"/><path d="M10 14L21 3"/><path d="M21 14v5a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5"/>',
        'Sun' => '<circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="M4.9 4.9l1.4 1.4"/><path d="M17.7 17.7l1.4 1.4"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="M4.9 19.1l1.4-1.4"/><path d="M17.7 6.3l1.4-1.4"/>',
        'Moon' => '<path d="M21 12.8A9 9 0 1 1 11.2 3 7 7 0 0 0 21 12.8z"/>',
        'Lightbulb' => '<path d="M9 18h6"/><path d="M10 22h4"/><path d="M12 2a7 7 0 0 0-4 12.8c.6.5 1 1.2 1.2 2H15c.2-.8.6-1.5 1.2-2A7 7 0 0 0 12 2z"/>',
        'LightbulbOff' => '<path d="M9 18h6"/><path d="M10 22h4"/><path d="M2 2l20 20"/><path d="M8.6 8.6A7 7 0 0 0 8 14.8c.6.5 1 1.2 1.2 2H15c.1-.5.3-.9.6-1.3"/><path d="M14.8 9.2A7 7 0 0 0 12 2a7 7 0 0 0-3.5.9"/>',
        'Plus' => '<path d="M12 5v14"/><path d="M5 12h14"/>',
        'Tag' => '<path d="M20 12l-8 8-8-8V4h8l8 8z"/><circle cx="7.5" cy="7.5" r="1.5"/>',
        'FileText' => '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M8 13h8"/><path d="M8 17h8"/><path d="M8 9h2"/>',
        'DollarSign' => '<path d="M12 2v20"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7H14.5a3.5 3.5 0 0 1 0 7H7"/>',
        'Layers' => '<path d="M12 2l9 5-9 5-9-5 9-5z"/><path d="M3 12l9 5 9-5"/><path d="M3 17l9 5 9-5"/>',
        'Clock' => '<circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/>',
        'Filter' => '<path d="M4 6h16"/><path d="M7 12h10"/><path d="M10 18h4"/>',
        'MoreHorizontal' => '<circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/>',
        'ArrowDownToLine' => '<path d="M12 3v12"/><path d="M7 10l5 5 5-5"/><path d="M5 21h14"/>',
        'Variable' => '<path d="M8 4c-2 2-3 4.5-3 8s1 6 3 8"/><path d="M16 4c2 2 3 4.5 3 8s-1 6-3 8"/><path d="M10 12h4"/>',
        'Eye' => '<path d="M2 12s4-7 10-7 10 7 10 7-4 7-10 7-10-7-10-7z"/><circle cx="12" cy="12" r="3"/>',
        'Edit3' => '<path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 1 1 3 3L7 19l-4 1 1-4 12.5-12.5z"/>',
        'Smartphone' => '<rect x="7" y="2" width="10" height="20" rx="2"/><path d="M11 18h2"/>',
        'Monitor' => '<rect x="3" y="4" width="18" height="12" rx="2"/><path d="M8 20h8"/><path d="M12 16v4"/>',
        'Info' => '<circle cx="12" cy="12" r="10"/><path d="M12 16v-4"/><path d="M12 8h.01"/>',
        'ShieldCheck' => '<path d="M12 3l7 3v5c0 5-3.4 8.3-7 10-3.6-1.7-7-5-7-10V6l7-3z"/><path d="M9 12l2 2 4-4"/>',
        'RefreshCw' => '<path d="M21 12a9 9 0 0 0-15.5-6.4L3 8"/><path d="M3 3v5h5"/><path d="M3 12a9 9 0 0 0 15.5 6.4L21 16"/><path d="M21 21v-5h-5"/>',
        'Lock' => '<rect x="5" y="11" width="14" height="10" rx="2"/><path d="M8 11V8a4 4 0 0 1 8 0v3"/>',
        'MapPin' => '<path d="M12 21s-6-5.3-6-11a6 6 0 1 1 12 0c0 5.7-6 11-6 11z"/><circle cx="12" cy="10" r="2"/>',
        'Send' => '<path d="M22 2L11 13"/><path d="M22 2L15 22l-4-9-9-4 20-7z"/>',
        'Check' => '<path d="M5 12l5 5L20 7"/>',
        // B10 fix: previously-missing icons used by admin views.
        'Activity' => '<path d="M22 12h-4l-3 9L9 3l-3 9H2"/>',
        'Server' => '<rect x="3" y="3" width="18" height="7" rx="2"/><rect x="3" y="14" width="18" height="7" rx="2"/><path d="M7 6.5h.01"/><path d="M7 17.5h.01"/>',
        'Fingerprint' => '<path d="M12 11c0 6-3 9-3 9"/><path d="M16 11c0 4-1 7-1 7"/><path d="M8 11c0 5 1.5 8 1.5 8"/><path d="M12 11v.01"/><path d="M5 15c0-5 3-9 7-9s7 4 7 9"/>',
        // New icons added for admin panel improvements
        'ShieldAlert' => '<path d="M12 3l7 3v5c0 5-3.4 8.3-7 10-3.6-1.7-7-5-7-10V6l7-3z"/><path d="M12 8v4"/><path d="M12 16h.01"/>',
        'UserPlus' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="8" x2="19" y2="14"/><line x1="16" y1="11" x2="22" y2="11"/>',
        'Inbox' => '<polyline points="22 12 16 12 14 15 10 15 8 12 2 12"/><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/>',
        'CheckCircle' => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>',
        'AlertCircle' => '<circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>',
        'Trash' => '<polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>',
        'Cpu' => '<rect x="4" y="4" width="16" height="16" rx="2"/><path d="M9 9h6v6H9z"/><path d="M15 2v2"/><path d="M9 2v2"/><path d="M20 15h2"/><path d="M20 9h2"/><path d="M9 20v2"/><path d="M15 20v2"/><path d="M2 15h2"/><path d="M2 9h2"/>',
        'PenTool' => '<path d="M12 19l7-7 3 3-7 7-3-3z"/><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"/><path d="M2 2l5 5"/>',
        default => '<circle cx="12" cy="12" r="9"/>',
    };

    return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round" class="' . e($classes) . '">' . $svg . '</svg>';
}

function hex_to_hsl(string $hex): array
{
    $hex = str_replace('#', '', $hex);
    if (strlen($hex) === 3) {
        $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
    }
    $r = hexdec(substr($hex, 0, 2)) / 255;
    $g = hexdec(substr($hex, 2, 2)) / 255;
    $b = hexdec(substr($hex, 4, 2)) / 255;

    $max = max($r, $g, $b);
    $min = min($r, $g, $b);
    $h = 0;
    $s = 0;
    $l = ($max + $min) / 2;

    if ($max !== $min) {
        $d = $max - $min;
        $s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);
        switch ($max) {
            case $r: $h = ($g - $b) / $d + ($g < $b ? 6 : 0); break;
            case $g: $h = ($b - $r) / $d + 2; break;
            case $b: $h = ($r - $g) / $d + 4; break;
        }
        $h /= 6;
    }

    return [round($h * 360), round($s * 100), round($l * 100)];
}

function session_get(string $key, mixed $default = null): mixed
{
    return $_SESSION[$key] ?? $default;
}

function redirect(string $path): never
{
    header('Location: ' . $path);
    exit;
}
