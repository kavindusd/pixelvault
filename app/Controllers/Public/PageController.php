<?php

declare(strict_types=1);

namespace App\Controllers\Public;

use App\Core\Controller;
use App\Models\DatasetModel;
use App\Models\MarketplaceModel;
use App\Services\MailService;
use App\Services\ProductCatalog;
use App\Services\SessionAuth;
use App\Services\SessionCart;

final class PageController extends Controller
{
    private DatasetModel $datasets;
    private SessionCart $cart;
    private SessionAuth $auth;
    private ProductCatalog $catalog;
    private MarketplaceModel $marketplace;

    public function __construct()
    {
        $this->datasets = new DatasetModel();
        $this->cart = new SessionCart();
        $this->auth = new SessionAuth();
        $this->catalog = new ProductCatalog();
        $this->marketplace = new MarketplaceModel();
    }

    /**
     * @param array<string, string> $params
     */
    public function submitContact(array $params = []): void
    {
        $firstName = trim((string) ($_POST['first_name'] ?? ''));
        $lastName  = trim((string) ($_POST['last_name'] ?? ''));
        $name      = trim("$firstName $lastName") ?: 'Website Visitor';
        $email     = trim((string) ($_POST['email'] ?? ''));
        $subject   = trim((string) ($_POST['subject'] ?? 'Website Inquiry'));
        $message   = trim((string) ($_POST['message'] ?? ''));
        
        // SMTP logic below...


        $adminEmail = site_config('admin_contact_email', 'admin@pixelvault.app');
        $siteName   = site_config('site_name', 'PixelVault');
        $primary    = site_config('primary_color', '#f97316');

        // Build a styled HTML email body directly
        $escapedName    = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        $escapedEmail   = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
        $escapedSubject = htmlspecialchars($subject, ENT_QUOTES, 'UTF-8');
        $escapedMessage = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));
        $replyLink      = htmlspecialchars('mailto:' . $email, ENT_QUOTES, 'UTF-8');

        $badgeText = $isExtensionRequest ? 'Quota Alert' : 'New Message';
        $badgeColor = $isExtensionRequest ? '#3b82f6' : $primary;
        $borderColor = $isExtensionRequest ? '#3b82f6' : '#e5e5e5';

        $html = <<<HTML
<!DOCTYPE html><html><body style="font-family:sans-serif;background:#f5f5f5;padding:20px;margin:0;">
<div style="max-width:620px;margin:0 auto;background:#ffffff;border-radius:12px;overflow:hidden;border:2px solid {$borderColor};">
  <div style="background:#18181b;padding:28px 32px;display:flex;align-items:center;gap:12px;">
    <h2 style="margin:0;color:#ffffff;font-size:20px;">{$siteName}</h2>
    <span style="margin-left:auto;background:{$badgeColor};color:#fff;padding:4px 12px;border-radius:20px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;">{$badgeText}</span>
  </div>
  <div style="padding:36px 32px;">
    <h1 style="font-size:22px;margin:0 0 24px;color:#111;">{$escapedSubject}</h1>
    <table style="width:100%;border-collapse:collapse;margin-bottom:24px;">
      <tr><td style="padding:10px 14px;background:#f9f9f9;border:1px solid #eee;width:30%;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#777;">From</td><td style="padding:10px 14px;border:1px solid #eee;font-size:14px;color:#222;">{$escapedName}</td></tr>
      <tr><td style="padding:10px 14px;background:#f9f9f9;border:1px solid #eee;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#777;">Email</td><td style="padding:10px 14px;border:1px solid #eee;font-size:14px;color:#222;">{$escapedEmail}</td></tr>
    </table>
    <div style="margin-bottom:8px;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#777;">Message Details</div>
    <div style="padding:20px;background:#f9f9f9;border:1px solid #eee;border-radius:8px;font-size:14px;line-height:1.7;color:#333;">{$escapedMessage}</div>
    <div style="margin-top:28px;text-align:center;">
      <a href="{$replyLink}" style="background:{$badgeColor};color:#fff;padding:13px 32px;text-decoration:none;border-radius:8px;font-weight:700;font-size:13px;display:inline-block;letter-spacing:0.5px;">Reply to Customer</a>
    </div>
  </div>
  <div style="background:#f4f4f4;padding:18px 32px;text-align:center;font-size:11px;color:#999;">&copy; {$this->year()} {$siteName} &mdash; This message was sent via the website contact form.</div>
</div>
</body></html>
HTML;

        $isExtensionRequest = (stripos($subject, 'extension request') !== false) || (stripos($subject, 'request extension') !== false);
        $emailSubject = $isExtensionRequest ? "🔴 [URGENT] {$escapedSubject}" : "[{$siteName}] {$escapedSubject} — from {$escapedName}";
        $success = MailService::sendDirect($adminEmail, $emailSubject, $html);

        if ($success) {
            header('Location: /?contact=success#contact');
        } else {
            header('Location: /?contact=error#contact');
        }
        exit;
    }

    /**
     * @param array<string, string> $params
     */
    public function submitExtensionRequest(array $params = []): void
    {
        $user = $this->auth->user();
        if (!$user) {
            redirect('/login');
        }

        $productId = (int) ($_POST['product_id'] ?? 0);
        $product = $this->catalog->find($productId);
        $message = trim((string) ($_POST['message'] ?? ''));

        if (!$product || $message === '') {
            $msg = urlencode('Product ID or message missing.');
            redirect('/profile?tab=vault&status=error&msg=' . $msg);
        }

        $name = (string) ($user['name'] ?? 'User');
        $email = (string) ($user['email'] ?? '');
        $subject = "Extension Request for " . ($product['name'] ?? 'Product');

        // SAVE TO DATABASE
        try {
            $db = \App\Core\Database::connection();
            $stmt = $db->prepare("INSERT INTO inquiries (name, email, subject, message, type) VALUES (:name, :email, :subject, :message, :type)");
            $stmt->execute([
                'name'    => $name,
                'email'   => $email,
                'subject' => $subject,
                'message' => $message,
                'type'    => 'extension_request'
            ]);
        } catch (\Throwable $e) {
            error_log("Failed to save extension inquiry: " . $e->getMessage());
        }

        // SEND SMTP EMAIL TO ADMIN
        $adminEmail = site_config('admin_contact_email', 'admin@pixelvault.app');
        $siteName   = site_config('site_name', 'PixelVault');
        $emailSubject = "🔴 [URGENT] Extension Request from " . $name;
        
        $escapedName = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        $escapedProd = htmlspecialchars($product['name'], ENT_QUOTES, 'UTF-8');
        $escapedMsg  = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));
        
        $html = "User <b>{$escapedName}</b> ({$email}) has requested an extension for <b>{$escapedProd}</b>.<br><br><b>Message:</b><br>{$escapedMsg}";
        MailService::sendDirect($adminEmail, $emailSubject, $html);

        redirect('/profile?tab=vault&status=success&msg=' . urlencode('Extension request submitted successfully.'));
    }

    private function year(): string
    {
        return date('Y');
    }

    /**
     * @param array<string, string> $params
     */
    public function home(array $params = []): void
    {
        $this->render('Public/pages/home', [
            'pageTitle' => app_config('name') . ' - ' . app_config('description'),
            'currentPath' => '/',
            'navigation' => $this->datasets->allOrEmpty('navigation'),
            'categoriesData' => $this->marketplace->categoriesPayload(),
            'products' => $this->catalog->all(),
            'cartCount' => $this->cart->count(),
            'user' => $this->auth->user(),
        ]);
    }

    /**
     * @param array<string, string> $params
     */
    public function marketplace(array $params = []): void
    {
        $products = $this->catalog->all();
        $categoriesPayload = $this->marketplace->categoriesPayload();
        $categories = $categoriesPayload['categories'] ?? [];
        $activeCategory = (string) query('cat', 'All');
        $search = trim((string) query('search', ''));

        $filteredProducts = array_values(array_filter($products, static function (array $product) use ($activeCategory, $search): bool {
            $matchesCategory = $activeCategory === 'All'
                || strcasecmp((string) ($product['cat'] ?? ''), $activeCategory) === 0;

            $haystack = strtolower((string) ($product['name'] ?? '') . ' ' . (string) ($product['cat'] ?? ''));
            $matchesSearch = $search === '' || str_contains($haystack, strtolower($search));

            return $matchesCategory && $matchesSearch;
        }));

        $this->render('Public/pages/marketplace', [
            'pageTitle' => 'Marketplace - ' . app_config('name'),
            'currentPath' => '/marketplace',
            'navigation' => $this->datasets->allOrEmpty('navigation'),
            'categories' => array_merge([['name' => 'All']], $categories),
            'activeCategory' => $activeCategory,
            'search' => $search,
            'products' => $filteredProducts,
            'cartCount' => $this->cart->count(),
            'user' => $this->auth->user(),
        ]);
    }

    /**
     * @param array<string, string> $params
     */
    public function categories(array $params = []): void
    {
        $categoriesPayload = $this->marketplace->categoriesPayload();

        $this->render('Public/pages/categories', [
            'pageTitle' => 'Categories - ' . app_config('name'),
            'currentPath' => '/categories',
            'navigation' => $this->datasets->allOrEmpty('navigation'),
            'categories' => $categoriesPayload['categories'] ?? [],
            'cartCount' => $this->cart->count(),
            'user' => $this->auth->user(),
        ]);
    }

    /**
     * @param array<string, string> $params
     */
    public function pricing(array $params = []): void
    {
        $this->render('Public/pages/pricing', [
            'pageTitle' => 'Pricing - ' . app_config('name'),
            'currentPath' => '/pricing',
            'navigation' => $this->datasets->allOrEmpty('navigation'),
            'plans' => $this->datasets->allOrEmpty('pricing'),
            'cartCount' => $this->cart->count(),
            'user' => $this->auth->user(),
        ]);
    }

    /**
     * @param array<string, string> $params
     */
    public function login(array $params = []): void
    {
        if ($this->auth->user()) {
            $user = $this->auth->user();
            redirect((($user['role'] ?? '') === 'Administrator') ? '/admin' : '/profile');
        }

        $this->render('Public/pages/login', [
            'pageTitle' => 'Sign In - ' . app_config('name'),
            'currentPath' => '/login',
            'navigation' => $this->datasets->allOrEmpty('navigation'),
            'cartCount' => $this->cart->count(),
            'user' => null,
            'loginFailed' => (query('login') === 'failed'),
            'resetSuccess' => (query('reset') === 'success'),
        ]);
    }

    public function verify(array $params = []): void
    {
        if (!isset($_SESSION['verify_user_id'])) {
            redirect('/login');
        }

        $this->render('Public/pages/verify', [
            'pageTitle' => 'Verify Identity - ' . app_config('name'),
            'currentPath' => '/verify',
            'navigation' => $this->datasets->allOrEmpty('navigation'),
            'cartCount' => $this->cart->count(),
            'user' => null,
            'error' => query('error'),
        ]);
    }

    public function forgotPassword(array $params = []): void
    {
        $this->render('Public/pages/forgot-password', [
            'pageTitle' => 'Forgot Password - ' . app_config('name'),
            'currentPath' => '/forgot-password',
            'navigation' => $this->datasets->allOrEmpty('navigation'),
            'cartCount' => $this->cart->count(),
            'user' => null,
            'error' => query('error'),
            'status' => query('status'),
        ]);
    }

    public function resetPassword(array $params = []): void
    {
        $token = (string)query('token');
        if ($token === '') redirect('/login');

        // Validate token exists and hasn't expired yet
        $user = (new \App\Models\MarketplaceModel())->findUserByResetToken($token);
        if (!$user) {
            redirect('/forgot-password?error=expired');
        }

        $this->render('Public/pages/reset-password', [
            'pageTitle' => 'Set New Password - ' . app_config('name'),
            'currentPath' => '/reset-password',
            'navigation' => $this->datasets->allOrEmpty('navigation'),
            'cartCount' => $this->cart->count(),
            'user' => null,
            'token' => $token,
            'error' => query('error'),
        ]);
    }

    /**
     * @param array<string, string> $params
     */
    public function register(array $params = []): void
    {
        if ($this->auth->user()) {
            redirect('/profile');
        }

        $this->render('Public/pages/register', [
            'pageTitle' => 'Create Account - ' . app_config('name'),
            'currentPath' => '/register',
            'navigation' => $this->datasets->allOrEmpty('navigation'),
            'cartCount' => $this->cart->count(),
            'user' => null,
            'registerError' => query('error') !== null ? urldecode((string) query('error')) : '',
        ]);
    }

    /**
     * @param array<string, string> $params
     */
    public function updates(array $params = []): void
    {
        $updates = $this->marketplace->updatesList();
        usort($updates, static fn (array $a, array $b): int => strcmp((string) ($a['cat'] ?? ''), (string) ($b['cat'] ?? '')));

        $this->render('Public/pages/updates', [
            'pageTitle' => 'Updates - ' . app_config('name'),
            'currentPath' => '/updates',
            'navigation' => $this->datasets->allOrEmpty('navigation'),
            'updates' => $updates,
            'cartCount' => $this->cart->count(),
            'user' => $this->auth->user(),
        ]);
    }

    /**
     * @param array<string, string> $params
     */
    public function download(array $params = []): void
    {
        $user = $this->auth->user();
        if (!$user) {
            redirect('/login');
        }

        $productId = (int) ($params['id'] ?? 0);
        $product = $this->catalog->find($productId);

        if (!$product) {
            redirect('/marketplace');
        }

        $userId = (int) ($user['id'] ?? 0);
        $access = $this->marketplace->productAccessFor($userId, $productId);

        if (!$access) {
            redirect('/product/' . $productId . '?status=error&msg=' . urlencode('You do not have access to this product.'));
        }

        // Determine which version is being requested
        $requestedVersion = trim((string) ($params['version'] ?? ''));
        if ($requestedVersion === '') {
            $requestedVersion = trim((string) ($product['latestVer'] ?? '1.0.0'));
        }

        $maxAllowed = $access['max_update_downloads'] + $access['override_extra_downloads'];
        $unlockedVersions = array_filter(explode(',', $access['downloaded_versions'] ?? ''));
        
        $isBaseVersion = (version_compare($requestedVersion, trim((string)$access['purchased_version']), '=='));
        $isAlreadyUnlocked = false;
        foreach ($unlockedVersions as $uv) {
            if (version_compare($requestedVersion, trim((string)$uv), '==')) {
                $isAlreadyUnlocked = true;
                break;
            }
        }

        // If it's a NEW update (not base, not already unlocked)
        if (!$isBaseVersion && !$isAlreadyUnlocked) {
            if ($access['update_count'] >= $maxAllowed) {
                redirect('/product/' . $productId . '?status=error&msg=' . urlencode('Update download limit reached. Please request an extension.'));
            }
            // Use a slot
            $this->marketplace->recordVersionDownload($userId, $productId, $requestedVersion);
        }

        // Serve File
        $version = $requestedVersion;
        $relativeFile = (string) ($product['file_path'] ?? '');

        if ($version !== '') {
            $allVersions = $this->marketplace->productVersions($productId);
            foreach ($allVersions as $v) {
                if ((string) ($v['version'] ?? '') === $version) {
                    $relativeFile = (string) ($v['file_path'] ?? '');
                    break;
                }
            }
        }

        // --- CLOUDFLARE R2 HANDLING ---
        if (str_starts_with($relativeFile, 'r2://')) {
            $r2 = new \App\Services\R2StorageService();
            if ($r2->isEnabled()) {
                $signedUrl = $r2->getSignedUrl($relativeFile, 3600); // 1 hour expiry
                redirect($signedUrl);
            }
        }
        // ------------------------------

        $filePath = BASE_PATH . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $relativeFile);

        if (!is_file($filePath)) {
            error_log("Download error: File not found. Tried path: " . $filePath);
            error_log("Relative path was: " . $relativeFile);
            redirect('/product/' . $productId . '?status=error&msg=' . urlencode('The product file is currently missing or inaccessible.'));
        }

        $filename = basename($filePath);
        
        if (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        
        readfile($filePath);
        exit;
    }

    /**
     * @param array<string, string> $params
     */
    public function product(array $params = []): void
    {
        $idOrSlug = (string) ($params['id'] ?? '');
        
        if (is_numeric($idOrSlug)) {
            $product = $this->catalog->find((int)$idOrSlug);
        } else {
            $product = (new \App\Models\MarketplaceModel())->productBySlug($idOrSlug);
        }

        if (!$product) {
            $this->render('Public/pages/not-found', [
                'pageTitle' => 'Not Found - ' . app_config('name'),
                'currentPath' => '/product/' . $productId,
                'navigation' => $this->datasets->allOrEmpty('navigation'),
                'cartCount' => $this->cart->count(),
                'user' => $this->auth->user(),
            ]);
            return;
        }

        $user = $this->auth->user();
        $userId = (int) ($user['id'] ?? 0);
        $access = $userId > 0 ? $this->marketplace->productAccessFor($userId, $productId) : null;
        
        $hasAccess = $access !== null;
        $maxAllowed = $access ? ($access['max_update_downloads'] + $access['override_extra_downloads']) : 3;
        $productUpdateCount = $access ? $access['update_count'] : 0;

        // Smart limit check: they are limited ONLY if they want a NEW update and have 0 slots.
        // On the main page, we check against the LATEST version.
        $latestVersion = trim((string)($product['latestVer'] ?? '1.0.0'));
        $purchasedVer = trim((string)($access['purchased_version'] ?? ''));
        
        $isBase = $access && version_compare($latestVersion, $purchasedVer, '==');
        
        $isUnlocked = false;
        if ($access) {
            $unlockedList = array_filter(explode(',', $access['downloaded_versions'] ?? ''));
            foreach ($unlockedList as $uv) {
                if (version_compare($latestVersion, trim((string)$uv), '==')) {
                    $isUnlocked = true;
                    break;
                }
            }
        }
        
        $limitReached = $hasAccess && !$isBase && !$isUnlocked && ($productUpdateCount >= $maxAllowed);
        
        // They can only download the LATEST version if they own it or have slots left
        $canDownload = $hasAccess && ($isBase || $isUnlocked || !$limitReached);
        
        // hasNewUpdate is for the badge
        $hasNewUpdate = $access && ($latestVersion !== $access['purchased_version']);

        $this->render('Public/pages/product-detail', [
            'pageTitle' => ($product['name'] ?? 'Product') . ' - ' . app_config('name'),
            'currentPath' => '/product/' . $productId,
            'navigation' => $this->datasets->allOrEmpty('navigation'),
            'product' => $product,
            'productUpdateCount' => $productUpdateCount,
            'maxAllowed' => $maxAllowed,
            'canDownload' => $canDownload,
            'limitReached' => $limitReached,
            'hasNewUpdate' => $hasNewUpdate,
            'access' => $access,
            'versions' => $this->marketplace->productVersions($productId),
            'cartCount' => $this->cart->count(),
            'user' => $user,
        ]);
    }

    /**
     * @param array<string, string> $params
     */
    public function checkout(array $params = []): void
    {
        $this->render('Public/pages/checkout', [
            'pageTitle' => 'Checkout - ' . app_config('name'),
            'currentPath' => '/checkout',
            'navigation' => $this->datasets->allOrEmpty('navigation'),
            'cartItems' => $this->cart->items(),
            'total' => $this->cart->total(),
            'cartCount' => $this->cart->count(),
            'user' => $this->auth->user(),
        ]);
    }

    /**
     * @param array<string, string> $params
     */
    public function profile(array $params = []): void
    {
        $user = $this->auth->user();

        if (!$user) {
            redirect('/');
        }

        $activeTab = (string) query('tab', 'overview');
        $products = $this->catalog->all();
        
        $purchasedIds = $user['purchasedProductIds'] ?? [];
        $ownedProducts = [];
        foreach ($products as $product) {
            if (in_array((int)$product['id'], $purchasedIds, true)) {
                // Fetch detailed access info for the progress bar
                $access = $this->marketplace->productAccessFor((int)$user['id'], (int)$product['id']);
                $product['access'] = $access;
                $ownedProducts[] = $product;
            }
        }
        
        $totalSpent = array_reduce($ownedProducts, static fn (float $sum, array $product): float => $sum + (float) ($product['price'] ?? 0), 0.0);

        $this->render('Public/pages/profile', [
            'pageTitle' => 'Profile - ' . app_config('name'),
            'currentPath' => '/profile',
            'navigation' => $this->datasets->allOrEmpty('navigation'),
            'cartCount' => $this->cart->count(),
            'user' => $user,
            'activeTab' => $activeTab,
            'ownedProducts' => $ownedProducts,
            'totalSpent' => $totalSpent,
        ]);
    }

    /**
     * @param array<string, string> $params
     */
    public function linkPayment(array $params = []): void
    {
        $user = $this->auth->user();
        if (!$user) {
            redirect('/login');
        }

        $type = (string) ($_POST['type'] ?? '');
        $value = (string) ($_POST['value'] ?? '');

        if ($type === '' || $value === '') {
            redirect('/profile?tab=payments&status=error&msg=' . urlencode('Invalid data provided.'));
        }

        $success = $this->auth->linkPaymentMethod($type, $value);

        if ($success) {
            redirect('/profile?tab=payments&status=success&msg=' . urlencode(ucfirst($type) . ' linked successfully.'));
        } else {
            redirect('/profile?tab=payments&status=error&msg=' . urlencode('Failed to link ' . $type . '.'));
        }
    }

    /**
     * @param array<string, string> $params
     */
    public function success(array $params = []): void
    {
        $this->render('Public/pages/success', [
            'pageTitle' => 'Success - ' . app_config('name'),
            'currentPath' => '/success',
            'navigation' => $this->datasets->allOrEmpty('navigation'),
            'cartCount' => $this->cart->count(),
            'user' => $this->auth->user(),
        ]);
    }

    /**
     * @param array<string, string> $params
     */
    public function notFound(array $params = []): void
    {
        http_response_code(404);
        $this->render('Public/pages/not-found', [
            'pageTitle' => 'Not Found - ' . app_config('name'),
            'currentPath' => parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/',
            'navigation' => $this->datasets->allOrEmpty('navigation'),
            'cartCount' => $this->cart->count(),
            'user' => $this->auth->user(),
        ]);
    }
}
