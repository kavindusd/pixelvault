<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\MarketplaceModel;
use App\Services\MailService;
use App\Services\AdminAuth;

final class ManagementController extends Controller
{
    private AdminAuth $auth;
    private MarketplaceModel $marketplace;

    public function __construct()
    {
        $this->auth = new AdminAuth();
        $this->marketplace = new MarketplaceModel();
    }

    /**
     * @param array<string, string> $params
     */
    public function createProduct(array $params = []): void
    {
        $adminId = $this->requireAdminId();
        if ($adminId === null) {
            return;
        }

        $featuresRaw = trim((string) ($_POST['key_features_text'] ?? ''));
        $features = $featuresRaw === '' ? [] : array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $featuresRaw) ?: [])));

        $title = trim((string) ($_POST['title'] ?? ''));
        $slug = trim((string) ($_POST['slug'] ?? ''));
        if ($title === '' || $slug === '') {
            header('Location: /admin?tab=dashboard&view=add-product&status=error');
            exit;
        }

        $uploadedImagePath = $this->storeUploadedFile('image_file', BASE_PATH . '/assets/products', '/assets/products', $slug);
        $uploadedZipPath = $this->storeUploadedFile('product_file', BASE_PATH . '/storage/files/products', 'storage/files/products', $slug);

        $imagePathInput = trim((string) ($_POST['image_url'] ?? ''));
        $filePathInput = trim((string) ($_POST['file_path'] ?? ''));
        $finalImagePath = $uploadedImagePath ?? ($imagePathInput !== '' ? $imagePathInput : '');
        $finalFilePath = $uploadedZipPath ?? ($filePathInput !== '' ? $filePathInput : '');

        if ($finalFilePath === '') {
            header('Location: /admin?tab=dashboard&view=add-product&status=error');
            exit;
        }

        try {
            $this->marketplace->createProduct([
                'category_id' => (int) ($_POST['category_id'] ?? 0),
                'title' => $title,
                'slug' => $slug,
                'short_description' => trim((string) ($_POST['short_description'] ?? '')),
                'description' => trim((string) ($_POST['description'] ?? '')),
                'key_features' => json_encode($features, JSON_UNESCAPED_SLASHES),
                'image_url' => $finalImagePath,
                'license_type' => trim((string) ($_POST['license_type'] ?? 'GPLv3 - Unlimited Sites')),
                'price' => (float) ($_POST['price'] ?? 0),
                'discount_price' => ($_POST['discount_price'] ?? '') === '' ? null : (float) $_POST['discount_price'],
                'demo_url' => trim((string) ($_POST['demo_url'] ?? '')),
                'file_path' => $finalFilePath,
                'current_version' => trim((string) ($_POST['current_version'] ?? '1.0.0')),
                'last_updated_at' => trim((string) ($_POST['last_updated_at'] ?? date('Y-m-d'))),
                'technical_info' => trim((string) ($_POST['technical_info'] ?? 'PHP 8.1+ / WP 6.0+')),
                'is_active' => isset($_POST['is_active']) ? 1 : 0,
                'changelog' => trim((string) ($_POST['changelog'] ?? 'Initial release')),
                'file_size_bytes' => ($_POST['file_size_bytes'] ?? '') === '' ? (isset($_FILES['product_file']['size']) ? (int) $_FILES['product_file']['size'] : null) : (int) $_POST['file_size_bytes'],
            ], $adminId);
        } catch (\PDOException $e) {
            if ($e->getCode() === '23000') {
                header('Location: /admin?tab=dashboard&view=add-product&status=error&message=' . urlencode('A product with this slug already exists.'));
                exit;
            }
            throw $e;
        }

        header('Location: /admin?tab=dashboard&view=add-product&status=created');
        exit;
    }

    /**
     * @param array<string, string> $params
     */
    public function updateProduct(array $params = []): void
    {
        $adminId = $this->requireAdminId();
        if ($adminId === null) {
            return;
        }

        $id    = (int) ($_POST['id'] ?? 0);
        $title = trim((string) ($_POST['title'] ?? ''));
        if ($id <= 0 || $title === '') {
            header('Location: /admin?tab=updates&status=error');
            exit;
        }

        $featuresRaw = trim((string) ($_POST['key_features_text'] ?? ''));
        $features = $featuresRaw === '' ? [] : array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $featuresRaw) ?: [])));

        $this->marketplace->updateProduct($id, [
            'category_id'       => (int) ($_POST['category_id'] ?? 0),
            'title'             => $title,
            'slug'              => trim((string) ($_POST['slug'] ?? '')),
            'short_description' => trim((string) ($_POST['short_description'] ?? '')),
            'description'       => trim((string) ($_POST['description'] ?? '')),
            'key_features'      => json_encode($features, JSON_UNESCAPED_SLASHES),
            'license_type'      => trim((string) ($_POST['license_type'] ?? 'GPLv3 - Unlimited Sites')),
            'price'             => (float) ($_POST['price'] ?? 0),
            'discount_price'    => ($_POST['discount_price'] ?? '') === '' ? null : (float) $_POST['discount_price'],
            'demo_url'          => trim((string) ($_POST['demo_url'] ?? '')),
            'is_active'         => isset($_POST['is_active']) ? 1 : 0,
            'technical_info'    => trim((string) ($_POST['technical_info'] ?? 'PHP 8.1+ / WP 6.0+')),
            'last_updated_at'   => trim((string) ($_POST['last_updated_at'] ?? date('Y-m-d'))),
        ]);

        // Notify all buyers if the admin checked "Notify buyers" and notifications are enabled.
        $notifyBuyers = isset($_POST['notify_buyers']) && site_config('enable_update_notifications', '1') === '1';
        if ($notifyBuyers) {
            $changeNote  = trim((string) ($_POST['change_note'] ?? 'The product has been updated with improvements.'));
            $purchasers  = $this->marketplace->getProductPurchasers($id);
            MailService::notifyProductBuyers($purchasers, $title, $changeNote);
        }

        header('Location: /admin?tab=updates&status=updated');
        exit;
    }

    /**
     * @param array<string, string> $params
     */
    public function deleteProduct(array $params = []): void
    {
        if ($this->requireAdminId() === null) {
            return;
        }

        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $this->marketplace->deleteProduct($id);
        }

        header('Location: /admin?tab=updates&status=deleted');
        exit;
    }

    /**
     * @param array<string, string> $params
     */
    public function uploadUpdate(array $params = []): void
    {
        $adminId = $this->requireAdminId();
        if ($adminId === null) {
            return;
        }

        $productId = (int) ($_POST['product_id'] ?? 0);
        $version = trim((string) ($_POST['version'] ?? ''));
        $product = $this->marketplace->productById($productId);
        $slug = (string) ($product['slug'] ?? 'general');
        $uploadedZipPath = $this->storeUploadedFile('update_file', BASE_PATH . '/storage/files/products', 'storage/files/products', $slug);
        $filePathInput = trim((string) ($_POST['file_path'] ?? ''));
        $filePath = $uploadedZipPath ?? $filePathInput;
        if ($productId <= 0 || $version === '' || $filePath === '') {
            header('Location: /admin?tab=dashboard&view=upload-update&status=error');
            exit;
        }

        $this->marketplace->uploadProductVersion(
            $productId,
            $version,
            $filePath,
            ($_POST['file_size_bytes'] ?? '') === '' ? (isset($_FILES['update_file']['size']) ? (int) $_FILES['update_file']['size'] : null) : (int) $_POST['file_size_bytes'],
            trim((string) ($_POST['changelog'] ?? '')),
            $adminId,
            isset($_POST['set_current'])
        );

        // Send Email Notifications
        if (site_config('enable_update_notifications', '1') === '1') {
            $purchasers = $this->marketplace->getProductPurchasers($productId);
            foreach ($purchasers as $user) {
                MailService::send($user['email'], 'update_notification', [
                    'user_name' => $user['name'] ?? 'User',
                    'product_name' => $product['title'],
                    'version' => $version,
                    'release_notes' => trim((string) ($_POST['changelog'] ?? 'Regular maintenance update.')),
                    'action_url' => base_url('/profile')
                ], (string) site_config('notification_sender_email'), (string) site_config('notification_sender_name', 'PixelVault Updates'));
            }
        }

        header('Location: /admin?tab=dashboard&view=upload-update&status=updated');
        exit;
    }

    /**
     * @param array<string, string> $params
     */
    public function resetUpdateCount(array $params = []): void
    {
        $adminId = $this->requireAdminId();
        if ($adminId === null) {
            return;
        }
        $userId = (int) ($_POST['user_id'] ?? 0);
        $userEmail = trim((string) ($_POST['user_email'] ?? ''));
        $productId = (int) ($_POST['product_id'] ?? 0);

        if ($userId <= 0 && $userEmail !== '') {
            $user = $this->marketplace->getUserByEmail($userEmail);
            $userId = $user ? (int)$user['id'] : 0;
        }

        if ($userId > 0 && $productId > 0) {
            $this->marketplace->resetUpdateCount($userId, $productId);
            header('Location: /admin?tab=users&status=reset');
        } else {
            header('Location: /admin?tab=users&status=error&message=' . urlencode('User not found.'));
        }
        exit;
    }

    /**
     * @param array<string, string> $params
     */
    public function grantAccess(array $params = []): void
    {
        $adminId = $this->requireAdminId();
        if ($adminId === null) {
            return;
        }
        $userId = (int) ($_POST['user_id'] ?? 0);
        $userEmail = trim((string) ($_POST['user_email'] ?? ''));
        $productId = (int) ($_POST['product_id'] ?? 0);

        if ($userId <= 0 && $userEmail !== '') {
            $user = $this->marketplace->getUserByEmail($userEmail);
            $userId = $user ? (int)$user['id'] : 0;
        }

        $extraDownloads = max(0, (int) ($_POST['extra_downloads'] ?? 0));
        $reason = trim((string) ($_POST['override_reason'] ?? 'Manual override'));
        
        if ($userId > 0 && $productId > 0 && $extraDownloads > 0) {
            $this->marketplace->grantManualDownloadAccess($userId, $productId, $extraDownloads, $adminId, $reason);
            header('Location: /admin?tab=users&status=granted');
        } else {
            header('Location: /admin?tab=users&status=error&message=' . urlencode('User or Product not found.'));
        }
        exit;
    }

    /**
     * @param array<string, string> $params
     */
    public function deleteInquiry(array $params = []): void
    {
        if ($this->requireAdminId() === null) {
            return;
        }
        $id = (int) ($_POST['id'] ?? 0);
        if ($id > 0) {
            $this->marketplace->deleteInquiry($id);
        }
        header('Location: /admin?tab=inbox&status=deleted');
        exit;
    }

    /**
     * @param array<string, string> $params
     */
    public function createCategory(array $params = []): void
    {
        $adminId = $this->requireAdminId();
        if ($adminId === null) {
            return;
        }
        $name = trim((string) ($_POST['name'] ?? ''));
        $slug = trim((string) ($_POST['slug'] ?? ''));
        $description = trim((string) ($_POST['description'] ?? ''));
        $icon = trim((string) ($_POST['icon'] ?? 'Tag'));
        $hue = trim((string) ($_POST['hue'] ?? 'from-orange-400/20 to-orange-500/10'));
        
        if ($name === '' || $slug === '') {
            header('Location: /admin?tab=categories_admin&status=error');
            exit;
        }

        $this->marketplace->createCategory($name, $slug, $description !== '' ? $description : null, $icon, $hue);
        header('Location: /admin?tab=categories_admin&status=created');
        exit;
    }

    /**
     * @param array<string, string> $params
     */
    public function deleteCategory(array $params = []): void
    {
        $adminId = $this->requireAdminId();
        if ($adminId === null) {
            return;
        }
        $categoryId = (int) ($_POST['category_id'] ?? 0);
        if ($categoryId > 0) {
            $this->marketplace->deleteCategory($categoryId);
        }
        header('Location: /admin?tab=categories_admin&status=deleted');
        exit;
    }

    /**
     * Persist an updated email template (subject / body / cta) into storage/data/email-templates.json
     */
    public function saveEmailTemplate(array $params = []): void
    {
        $adminId = $this->requireAdminId();
        if ($adminId === null) {
            return;
        }

        $key = preg_replace('/[^a-z0-9_]/i', '', (string) ($_POST['template_key'] ?? 'update_notification')) ?: 'update_notification';
        $subject = trim((string) ($_POST['subject'] ?? ''));
        $body = (string) ($_POST['body'] ?? '');
        $cta = trim((string) ($_POST['cta'] ?? ''));

        $path = BASE_PATH . '/storage/data/email-templates.json';
        $data = is_file($path) ? (json_decode((string) file_get_contents($path), true) ?: []) : [];
        $data['emailTemplates'][$key] = [
            'subject' => $subject,
            'body' => $body,
            'cta' => $cta,
        ];
        @file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        header('Location: /admin?tab=email&status=saved');
        exit;
    }

    /**
     * Toggle a single security flag and persist to storage/data/security-settings.json
     */
    public function toggleSecurity(array $params = []): void
    {
        $adminId = $this->requireAdminId();
        if ($adminId === null) {
            return;
        }

        $id = (string) ($_POST['setting_id'] ?? '');
        $next = !empty($_POST['enabled']);

        $path = BASE_PATH . '/storage/data/security-settings.json';
        $data = is_file($path) ? (json_decode((string) file_get_contents($path), true) ?: []) : [];
        foreach (($data['securitySettings'] ?? []) as $i => $row) {
            if (($row['id'] ?? '') === $id) {
                $data['securitySettings'][$i]['status'] = $next;
                break;
            }
        }
        @file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

        header('Location: /admin?tab=security&status=saved');
        exit;
    }

    /**
     * Rebuild a basic sitemap.xml from the products dataset.
     */
    public function rebuildSitemap(array $params = []): void
    {
        $adminId = $this->requireAdminId();
        if ($adminId === null) {
            return;
        }

        $products = $this->marketplace->products();
        $base = rtrim((string) (env('APP_URL') ?? 'https://pixelvault.app'), '/');

        $urls = ['', '/marketplace', '/pricing', '/updates'];
        foreach ($products as $product) {
            $urls[] = '/product/' . (string) ($product['id'] ?? '');
        }

        $today = date('Y-m-d');
        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
        foreach ($urls as $u) {
            $xml .= "  <url><loc>" . htmlspecialchars($base . $u, ENT_XML1) . "</loc><lastmod>{$today}</lastmod></url>\n";
        }
        $xml .= "</urlset>\n";
        @file_put_contents(BASE_PATH . '/sitemap.xml', $xml);

        header('Location: /admin?tab=security&status=sitemap');
        exit;
    }

    public function saveConfig(array $params = []): void
    {
        $adminId = $this->requireAdminId();
        if ($adminId === null) {
            return;
        }

        $configs = $_POST['config'] ?? [];
        $group = (string) ($_POST['group'] ?? 'general');
        
        $configModel = new \App\Models\SiteConfigModel();
        
        // Handle Text Configs
        foreach ($configs as $key => $value) {
            $configModel->update((string)$key, (string)$value);
        }

        // Handle File Configs (e.g., site_logo)
        if (isset($_FILES['files']) && is_array($_FILES['files']['name'])) {
            foreach ($_FILES['files']['name'] as $key => $name) {
                if ($_FILES['files']['error'][$key] === UPLOAD_ERR_OK) {
                    $tmpName = $_FILES['files']['tmp_name'][$key];
                    $path = $this->storeUploadedFileForConfig($key, $tmpName, $name);
                    if ($path) {
                        $configModel->update((string)$key, $path);
                    }
                }
            }
        }

        header('Location: /admin?tab=site_settings&group=' . urlencode($group) . '&status=saved');
        exit;
    }

    private function storeUploadedFileForConfig(string $key, string $tmpPath, string $originalName): ?string
    {
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $finalName = $key . '_' . bin2hex(random_bytes(4)) . '.' . $extension;
        $targetDir = BASE_PATH . '/storage/files/site';
        
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0755, true);
        }

        $targetPath = $targetDir . '/' . $finalName;
        if (move_uploaded_file($tmpPath, $targetPath)) {
            return '/storage/files/site/' . $finalName;
        }

        return null;
    }

    public function addAdminPayment(array $params = []): void
    {
        $this->requireAdminId();
        $provider = trim((string) ($_POST['provider'] ?? ''));
        $identifier = trim((string) ($_POST['account_identifier'] ?? ''));
        
        if ($provider !== '' && $identifier !== '') {
            $this->marketplace->addAdminPaymentMethod($provider, $identifier);
            header('Location: /admin?tab=site_settings&group=payments&status=success');
        } else {
            header('Location: /admin?tab=site_settings&group=payments&status=error');
        }
        exit;
    }

    public function updateAdminPayment(array $params = []): void
    {
        $this->requireAdminId();
        $id = (int) ($_POST['id'] ?? 0);
        $isActive = isset($_POST['is_active']);
        
        if ($id > 0) {
            $this->marketplace->updateAdminPaymentMethod($id, $isActive);
            header('Location: /admin?tab=site_settings&group=payments&status=updated');
        } else {
            header('Location: /admin?tab=site_settings&group=payments&status=error');
        }
        exit;
    }

    public function deleteAdminPayment(array $params = []): void
    {
        $this->requireAdminId();
        $id = (int) ($_POST['id'] ?? 0);
        
        if ($id > 0) {
            $this->marketplace->deleteAdminPaymentMethod($id);
            header('Location: /admin?tab=site_settings&group=payments&status=deleted');
        } else {
            header('Location: /admin?tab=site_settings&group=payments&status=error');
        }
        exit;
    }

    private function requireAdminId(): ?int
    {
        $admin = $this->auth->admin();
        if (!$admin) {
            header('Location: /admin/login');
            exit;
        }

        return (int) ($admin['id'] ?? 0);
    }

    public function createAdminAccount(array $params = []): void
    {
        $currentId = $this->requireAdminId();
        
        $name = trim((string) ($_POST['name'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $role = trim((string) ($_POST['role'] ?? 'Super Admin'));

        if ($name === '' || $email === '' || strlen($password) < 6) {
            header('Location: /admin?tab=accounts&status=error&message=' . urlencode('Invalid data provided.'));
            exit;
        }

        $success = $this->auth->createAdmin($name, $email, $password, $role);
        
        if (!$success) {
            header('Location: /admin?tab=accounts&status=error&message=' . urlencode('Email already in use.'));
            exit;
        }

        header('Location: /admin?tab=accounts&status=success');
        exit;
    }

    private function storeUploadedFile(string $fileKey, string $targetDirAbsolute, string $publicPathPrefix, string $subDir = ''): ?string
    {
        if (!isset($_FILES[$fileKey]) || !is_array($_FILES[$fileKey])) {
            return null;
        }

        $file = $_FILES[$fileKey];
        $error = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);
        if ($error === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        if ($error !== UPLOAD_ERR_OK) {
            error_log("Upload error for {$fileKey}: " . $error);
            return null;
        }

        $tmpPath = (string) ($file['tmp_name'] ?? '');
        $originalName = (string) ($file['name'] ?? '');
        $fileSize = (int) ($file['size'] ?? 0);

        if ($tmpPath === '' || $originalName === '' || !is_uploaded_file($tmpPath)) {
            return null;
        }

        // 1. Extension Whitelist
        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        $allowedExtensions = [
            'image_file' => ['jpg', 'jpeg', 'png', 'webp', 'svg'],
            'product_file' => ['zip'],
            'update_file' => ['zip']
        ];

        $whitelist = $allowedExtensions[$fileKey] ?? [];
        if (!in_array($extension, $whitelist, true)) {
            error_log("Security Alert: Blocked upload with extension .{$extension} for {$fileKey}");
            return null;
        }

        // 2. MIME Type Validation
        $validMime = true; 
        if (class_exists('\finfo')) {
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($tmpPath);
            
            $allowedMimes = [
                'zip' => ['application/zip', 'application/x-zip-compressed', 'multipart/x-zip', 'application/x-compressed'],
                'jpg' => ['image/jpeg', 'image/pjpeg'],
                'jpeg' => ['image/jpeg', 'image/pjpeg'],
                'png' => ['image/png', 'image/x-png'],
                'webp' => ['image/webp'],
                'svg' => ['image/svg+xml', 'text/plain', 'image/svg'] 
            ];

            $validMime = false;
            foreach ($allowedMimes[$extension] ?? [] as $m) {
                if ($mimeType === $m) {
                    $validMime = true;
                    break;
                }
            }
        }

        if (!$validMime && $extension !== 'svg') { 
             error_log("Security Alert: MIME type mismatch for .{$extension}");
             return null;
        }

        // 3. Filename Sanitization
        $safeBase = preg_replace('/[^a-zA-Z0-9_\-]/', '-', pathinfo($originalName, PATHINFO_FILENAME));
        $safeBase = trim((string) $safeBase, '-');
        if ($safeBase === '') {
            $safeBase = 'vault-asset';
        }

        $finalName = sprintf('%s_%s_%s.%s', 
            $safeBase, 
            bin2hex(random_bytes(4)), 
            date('YmdHis'), 
            $extension
        );

        $finalTargetDir = rtrim($targetDirAbsolute, DIRECTORY_SEPARATOR);
        if ($subDir !== '') {
            $finalTargetDir .= DIRECTORY_SEPARATOR . trim($subDir, DIRECTORY_SEPARATOR);
        }

        if (!is_dir($finalTargetDir)) {
            if (!mkdir($finalTargetDir, 0755, true) && !is_dir($finalTargetDir)) {
                error_log("Failed to create target directory: " . $finalTargetDir);
                return null;
            }
        }

        $targetPath = $finalTargetDir . DIRECTORY_SEPARATOR . $finalName;

        // 4. Move and Verify
        if (!move_uploaded_file($tmpPath, $targetPath)) {
            error_log("Failed to move uploaded file to: " . $targetPath);
            return null;
        }

        chmod($targetPath, 0644);

        // 5. Cloudflare R2 Upload (Optional)
        $r2 = new \App\Services\R2StorageService();
        if ($r2->isEnabled() && in_array($fileKey, ['product_file', 'update_file'], true)) {
            $r2Key = ($subDir !== '' ? trim($subDir, '/') . '/' : '') . $finalName;
            $r2Path = $r2->upload($targetPath, $r2Key);
            if ($r2Path) {
                // If uploaded to R2, we can optionally delete the local file
                // unlink($targetPath); 
                return $r2Path;
            }
        }

        $publicPath = rtrim($publicPathPrefix, '/') . '/';
        if ($subDir !== '') {
            $publicPath .= trim($subDir, '/') . '/';
        }
        
        return $publicPath . $finalName;
    }
}
