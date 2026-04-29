<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class MarketplaceModel
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function updateUserVerificationCode(int $userId, ?string $code, ?string $expiresAt): bool
    {
        $stmt = $this->db->prepare("UPDATE users SET verification_code = :code, token_expires_at = :expires WHERE id = :id");
        return $stmt->execute([
            'id' => $userId,
            'code' => $code,
            'expires' => $expiresAt
        ]);
    }

    public function updateUserResetToken(int $userId, ?string $token, ?string $expiresAt): bool
    {
        $stmt = $this->db->prepare("UPDATE users SET reset_token = :token, token_expires_at = :expires WHERE id = :id");
        return $stmt->execute([
            'id' => $userId,
            'token' => $token,
            'expires' => $expiresAt
        ]);
    }

    public function findUserByResetToken(string $token): ?array
    {
        $now = date('Y-m-d H:i:s');
        $stmt = $this->db->prepare("SELECT * FROM users WHERE reset_token = :token AND token_expires_at > :now LIMIT 1");
        $stmt->execute(['token' => $token, 'now' => $now]);
        return $stmt->fetch() ?: null;
    }

    public function updatePassword(int $userId, string $passwordHash): bool
    {
        $stmt = $this->db->prepare("UPDATE users SET password_hash = :hash, reset_token = NULL, token_expires_at = NULL WHERE id = :id");
        return $stmt->execute([
            'id' => $userId,
            'hash' => $passwordHash
        ]);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function products(): array
    {
        // B7 fix: pull the *current* product_versions row in the same query so
        // listing pages know whether an update is available, instead of always
        // showing latestVer == ver.
        $sql = 'SELECT p.id, p.title, p.slug, p.price, p.discount_price, p.current_version, p.last_updated_at,
                       p.image_url, p.short_description, p.description, p.key_features, p.demo_url,
                       p.technical_info, p.file_path, c.name AS category_name,
                       pv.version AS latest_version
                FROM products p
                INNER JOIN categories c ON c.id = p.category_id
                LEFT JOIN product_versions pv ON pv.product_id = p.id AND pv.is_current = 1
                WHERE p.is_active = 1
                ORDER BY p.id ASC';
        $rows = $this->db->query($sql)->fetchAll();

        return array_map([$this, 'mapProductRow'], $rows);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function productById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT p.id, p.title, p.slug, p.price, p.discount_price, p.current_version, p.last_updated_at,
                    p.image_url, p.short_description, p.description, p.key_features, p.demo_url,
                    p.technical_info, p.file_path, c.name AS category_name
             FROM products p
             INNER JOIN categories c ON c.id = p.category_id
             WHERE p.id = :id AND p.is_active = 1
             LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        if (!is_array($row)) {
            return null;
        }

        $product = $this->mapProductRow($row);
        $latest = $this->latestVersionForProduct($id);
        $product['latestVer'] = $latest ?? $product['ver'];

        return $product;
    }

    /**
     * @return array{categories: array<int, array<string, mixed>>}
     */
    public function categoriesPayload(): array
    {
        $rows = $this->db->query(
            'SELECT c.id, c.name, c.slug, c.icon, c.hue, COUNT(p.id) AS products_count
             FROM categories c
             LEFT JOIN products p ON p.category_id = c.id AND p.is_active = 1
             GROUP BY c.id, c.name, c.slug, c.icon, c.hue
             ORDER BY c.name ASC'
        )->fetchAll();

        return [
            'categories' => array_map(static fn (array $row): array => [
                'id' => (int) $row['id'],
                'name' => (string) $row['name'],
                'slug' => (string) $row['slug'],
                'count' => (int) ($row['products_count'] ?? 0),
                'icon' => (string) ($row['icon'] ?? 'Tag'),
                'hue' => (string) ($row['hue'] ?? 'from-orange-400/20 to-orange-500/10'),
            ], $rows),
            'platformLogos' => [
                ['name' => 'WordPress', 'slug' => 'wordpress'],
                ['name' => 'Elementor', 'slug' => 'elementor'],
                ['name' => 'WooCommerce', 'slug' => 'woocommerce'],
                ['name' => 'Stripe', 'slug' => 'stripe'],
                ['name' => 'PayPal', 'slug' => 'paypal'],
                ['name' => 'Cloudflare', 'slug' => 'cloudflare'],
                ['name' => 'Mailchimp', 'slug' => 'mailchimp'],
                ['name' => 'Hostinger', 'slug' => 'hostinger'],
                ['name' => 'WP Engine', 'slug' => 'wpengine'],
                ['name' => 'GoDaddy', 'slug' => 'godaddy'],
            ],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function updatesList(): array
    {
        $sql = 'SELECT p.id, p.title, c.name AS category_name, p.current_version, p.last_updated_at
                FROM products p
                INNER JOIN categories c ON c.id = p.category_id
                WHERE p.is_active = 1
                ORDER BY p.last_updated_at DESC';
        $rows = $this->db->query($sql)->fetchAll();

        return array_map(static function (array $row): array {
            return [
                'id' => (int) $row['id'],
                'name' => (string) $row['title'],
                'cat' => (string) $row['category_name'],
                'ver' => (string) $row['current_version'],
                'date' => date('M d, Y', strtotime((string) $row['last_updated_at'])),
                'size' => 'N/A',
                'status' => 'Latest',
            ];
        }, $rows);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function adminOrders(): array
    {
        $sql = 'SELECT o.order_number, o.total_amount, o.purchase_date, o.payment_status, u.name, u.email,
                       GROUP_CONCAT(p.title SEPARATOR ", ") AS products
                FROM orders o
                INNER JOIN users u ON u.id = o.user_id
                LEFT JOIN purchases pu ON pu.order_id = o.id
                LEFT JOIN products p ON p.id = pu.product_id
                GROUP BY o.id
                ORDER BY o.purchase_date DESC
                LIMIT 20';
        $rows = $this->db->query($sql)->fetchAll();

        return array_map(static function (array $row): array {
            return [
                'id' => (string) $row['order_number'],
                'name' => (string) $row['name'],
                'email' => (string) $row['email'],
                'prods' => (string) ($row['products'] ?? ''),
                'amount' => '$' . number_format((float) $row['total_amount'], 2),
                'status' => ucfirst((string) $row['payment_status']),
                'date' => date('M d, Y', strtotime((string) $row['purchase_date'])),
            ];
        }, $rows);
    }

    public function findUserByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT id, name, email, password_hash, avatar_url, verification_code, token_expires_at FROM users WHERE email = :email LIMIT 1');
        $stmt->execute(['email' => $email]);
        $row = $stmt->fetch();

        return is_array($row) ? $row : null;
    }

    public function findUserById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT id, name, email, password_hash, avatar_url, verification_code, token_expires_at FROM users WHERE id = :id LIMIT 1');
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return is_array($row) ? $row : null;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findAdminByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM admins WHERE email = ?');
        $stmt->execute([$email]);
        $row = $stmt->fetch();
        return is_array($row) ? $row : null;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function findAdminById(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM admins WHERE id = ?');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return is_array($row) ? $row : null;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getAllAdmins(): array
    {
        return $this->db->query('SELECT id, name, email, role, created_at FROM admins ORDER BY created_at DESC')->fetchAll();
    }

    public function createAdmin(string $name, string $email, string $passwordHash, string $role = 'Super Admin'): int
    {
        $stmt = $this->db->prepare('INSERT INTO admins (name, email, password_hash, role) VALUES (?, ?, ?, ?)');
        $stmt->execute([$name, $email, $passwordHash, $role]);
        return (int) $this->db->lastInsertId();
    }

    public function getUserPaymentMethods(int $userId): array
    {
        $stmt = $this->db->prepare('SELECT * FROM user_payment_methods WHERE user_id = :user_id ORDER BY is_default DESC, created_at DESC');
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }

    public function addUserPaymentMethod(int $userId, string $provider, string $identifier, bool $isDefault = false, ?array $details = null): bool
    {
        if ($isDefault) {
            $this->db->prepare('UPDATE user_payment_methods SET is_default = 0 WHERE user_id = ?')->execute([$userId]);
        }

        $stmt = $this->db->prepare(
            'INSERT INTO user_payment_methods (user_id, provider, account_identifier, is_default, details) 
             VALUES (:user_id, :provider, :identifier, :is_default, :details)'
        );
        return $stmt->execute([
            'user_id' => $userId,
            'provider' => $provider,
            'identifier' => $identifier,
            'is_default' => $isDefault ? 1 : 0,
            'details' => $details ? json_encode($details) : null
        ]);
    }

    public function deleteUserPaymentMethod(int $userId, int $methodId): bool
    {
        $stmt = $this->db->prepare('DELETE FROM user_payment_methods WHERE id = :id AND user_id = :user_id');
        return $stmt->execute(['id' => $methodId, 'user_id' => $userId]);
    }

    public function getAdminPaymentMethods(): array
    {
        return $this->db->query('SELECT * FROM admin_payment_methods ORDER BY created_at DESC')->fetchAll();
    }

    public function addAdminPaymentMethod(string $provider, string $identifier, bool $isActive = true): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO admin_payment_methods (provider, account_identifier, is_active) 
             VALUES (:provider, :identifier, :is_active)'
        );
        return $stmt->execute([
            'provider' => $provider,
            'identifier' => $identifier,
            'is_active' => $isActive ? 1 : 0
        ]);
    }

    public function updateAdminPaymentMethod(int $id, bool $isActive): bool
    {
        $stmt = $this->db->prepare('UPDATE admin_payment_methods SET is_active = :active WHERE id = :id');
        return $stmt->execute(['active' => $isActive ? 1 : 0, 'id' => $id]);
    }

    public function deleteAdminPaymentMethod(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM admin_payment_methods WHERE id = :id');
        return $stmt->execute(['id' => $id]);
    }

    public function createUser(string $name, string $email, string $passwordHash, array $address = [], array $payment = [], ?string $avatarUrl = null): int
    {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare(
                'INSERT INTO users (name, email, password_hash, avatar_url) 
                 VALUES (:name, :email, :password, :avatar)'
            );
            $stmt->execute([
                'name' => $name,
                'email' => $email,
                'password' => $passwordHash,
                'avatar' => $avatarUrl,
            ]);
            
            $userId = (int) $this->db->lastInsertId();

            $this->db->commit();
            return $userId;
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * @return array<int>
     */
    public function purchasedProductIds(int $userId): array
    {
        $stmt = $this->db->prepare('SELECT DISTINCT product_id FROM purchases WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $userId]);
        $rows = $stmt->fetchAll();

        return array_map(static fn (array $row): int => (int) $row['product_id'], $rows);
    }

    /**
     * @return array<int, int>
     */
    public function productUpdateCounts(int $userId): array
    {
        $stmt = $this->db->prepare('SELECT product_id, update_count FROM purchases WHERE user_id = :user_id');
        $stmt->execute(['user_id' => $userId]);
        $rows = $stmt->fetchAll();

        $result = [];
        foreach ($rows as $row) {
            $result[(int) $row['product_id']] = (int) $row['update_count'];
        }

        return $result;
    }

    /**
     * B8 fix: return the actual access row so callers can honor the
     * configurable max_update_downloads + override_extra_downloads instead
     * of hardcoding 3.
     *
     * @return array{max_update_downloads:int, update_count:int, override_extra_downloads:int}|null
     */
    public function productAccessFor(int $userId, int $productId): ?array
    {
        $stmt = $this->db->prepare(
            'SELECT max_update_downloads, update_count, purchased_version, downloaded_versions
             FROM purchases
             WHERE user_id = :user_id AND product_id = :product_id
             LIMIT 1'
        );
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
        $row = $stmt->fetch();
        if (!is_array($row)) {
            return null;
        }

        return [
            'max_update_downloads' => (int) ($row['max_update_downloads'] ?? 3),
            'update_count' => (int) ($row['update_count'] ?? 0),
            'purchased_version' => (string) ($row['purchased_version'] ?? ''),
            'downloaded_versions' => (string) ($row['downloaded_versions'] ?? ''),
            'override_extra_downloads' => 0, 
        ];
    }

    public function createOrder(int $userId, array $cartItems, float $total, string $paymentMethod = 'PayPal'): void
    {
        $this->db->beginTransaction();
        try {
            $orderNumber = 'ORD-' . (string) random_int(100000, 999999);
            $orderStmt = $this->db->prepare(
                'INSERT INTO orders (order_number, user_id, total_amount, payment_status, payment_method, purchase_date)
                 VALUES (:order_number, :user_id, :total_amount, :payment_status, :payment_method, NOW())'
            );
            $orderStmt->execute([
                'order_number' => $orderNumber,
                'user_id' => $userId,
                'total_amount' => $total,
                'payment_status' => 'paid',
                'payment_method' => $paymentMethod,
            ]);
            $orderId = (int) $this->db->lastInsertId();

            $accessStmt = $this->db->prepare(
                'INSERT INTO purchases (user_id, product_id, order_id, max_update_downloads, update_count, purchased_version)
                 VALUES (:user_id, :product_id, :order_id, 3, 0, :purchased_version)
                 ON DUPLICATE KEY UPDATE
                    order_id = VALUES(order_id),
                    max_update_downloads = 3,
                    update_count = 0,
                    purchased_version = VALUES(purchased_version)'
            );

            foreach ($cartItems as $item) {
                // Get the current version of the product
                $product = $this->db->query("SELECT current_version FROM products WHERE id = " . (int)$item['id'])->fetch();
                $currentVer = $product ? $product['current_version'] : '1.0.0';

                $accessStmt->execute([
                    'user_id' => $userId,
                    'product_id' => (int) $item['id'],
                    'order_id' => $orderId,
                    'purchased_version' => $currentVer
                ]);
            }

            $this->db->commit();
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }


    public function recordVersionDownload(int $userId, int $productId, string $version): void
    {
        $access = $this->productAccessFor($userId, $productId);
        if (!$access) return;

        $version = trim($version);
        
        // 1. If it's the base version, don't count it.
        if (version_compare($version, trim((string)$access['purchased_version']), '==')) {
            return;
        }

        // 2. Parse existing downloaded versions
        $versions = array_filter(explode(',', $access['downloaded_versions'] ?? ''));
        
        // 3. If they already unlocked this version, don't count it again.
        foreach ($versions as $v) {
            if (version_compare($version, trim((string)$v), '==')) {
                return;
            }
        }

        // 4. Add new version to list and increment count
        $versions[] = $version;
        $newList = implode(',', $versions);
        $newCount = count($versions);

        $stmt = $this->db->prepare(
            'UPDATE purchases 
             SET update_count = :update_count, 
                 downloaded_versions = :downloaded_versions
             WHERE user_id = :user_id AND product_id = :product_id'
        );
        $stmt->execute([
            'update_count' => $newCount,
            'downloaded_versions' => $newList,
            'user_id' => $userId,
            'product_id' => $productId
        ]);
    }

    /**
     * @param array<string, mixed> $input
     */
    public function createProduct(array $input, int $adminUserId): int
    {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare(
                'INSERT INTO products (
                    category_id, title, slug, short_description, description, key_features, image_url,
                    license_type, price, discount_price, demo_url, file_path, current_version, last_updated_at, is_active, technical_info
                ) VALUES (
                    :category_id, :title, :slug, :short_description, :description, :key_features, :image_url,
                    :license_type, :price, :discount_price, :demo_url, :file_path, :current_version, :last_updated_at, :is_active, :technical_info
                )'
            );
            $stmt->execute([
                'category_id' => (int) $input['category_id'],
                'title' => (string) $input['title'],
                'slug' => (string) $input['slug'],
                'short_description' => (string) ($input['short_description'] ?? ''),
                'description' => (string) $input['description'],
                'key_features' => (string) ($input['key_features'] ?? '[]'),
                'image_url' => (string) ($input['image_url'] ?? ''),
                'license_type' => (string) ($input['license_type'] ?? 'GPLv3 - Unlimited Sites'),
                'price' => (float) $input['price'],
                'discount_price' => $input['discount_price'] === null ? null : (float) $input['discount_price'],
                'demo_url' => (string) ($input['demo_url'] ?? ''),
                'file_path' => (string) $input['file_path'],
                'current_version' => (string) $input['current_version'],
                'last_updated_at' => (string) $input['last_updated_at'],
                'is_active' => (int) ($input['is_active'] ?? 1),
                'technical_info' => (string) ($input['technical_info'] ?? 'PHP 8.1+ / WP 6.0+'),
            ]);
            $productId = (int) $this->db->lastInsertId();

            $versionStmt = $this->db->prepare(
                'INSERT INTO product_versions (
                    product_id, version, changelog, file_path, file_size_bytes, uploaded_by, is_current
                 ) VALUES (
                    :product_id, :version, :changelog, :file_path, :file_size_bytes, :uploaded_by, 1
                 )'
            );
            $versionStmt->execute([
                'product_id' => $productId,
                'version' => (string) $input['current_version'],
                'changelog' => (string) ($input['changelog'] ?? 'Initial release'),
                'file_path' => (string) $input['file_path'],
                'file_size_bytes' => $input['file_size_bytes'] === null ? null : (int) $input['file_size_bytes'],
                'uploaded_by' => $adminUserId,
            ]);

            $this->db->commit();
            return $productId;
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function updateProduct(int $id, array $input): void
    {
        $stmt = $this->db->prepare(
            'UPDATE products SET
                category_id = :category_id,
                title = :title,
                slug = :slug,
                short_description = :short_description,
                description = :description,
                key_features = :key_features,
                license_type = :license_type,
                price = :price,
                discount_price = :discount_price,
                demo_url = :demo_url,
                is_active = :is_active,
                technical_info = :technical_info,
                last_updated_at = :last_updated_at
             WHERE id = :id'
        );
        $stmt->execute([
            'id' => $id,
            'category_id' => (int) $input['category_id'],
            'title' => (string) $input['title'],
            'slug' => (string) $input['slug'],
            'short_description' => (string) ($input['short_description'] ?? ''),
            'description' => (string) $input['description'],
            'key_features' => (string) ($input['key_features'] ?? '[]'),
            'license_type' => (string) ($input['license_type'] ?? 'GPLv3 - Unlimited Sites'),
            'price' => (float) $input['price'],
            'discount_price' => $input['discount_price'] === null ? null : (float) $input['discount_price'],
            'demo_url' => (string) ($input['demo_url'] ?? ''),
            'is_active' => (int) ($input['is_active'] ?? 1),
            'technical_info' => (string) ($input['technical_info'] ?? 'PHP 8.1+ / WP 6.0+'),
            'last_updated_at' => (string) $input['last_updated_at'],
        ]);
    }

    public function deleteProduct(int $id): void
    {
        $this->db->beginTransaction();
        try {
            // Delete versions first
            $stmt = $this->db->prepare('DELETE FROM product_versions WHERE product_id = :id');
            $stmt->execute(['id' => $id]);

            // Delete access records
            $stmt = $this->db->prepare('DELETE FROM purchases WHERE product_id = :id');
            $stmt->execute(['id' => $id]);

            // Delete product
            $stmt = $this->db->prepare('DELETE FROM products WHERE id = :id');
            $stmt->execute(['id' => $id]);

            $this->db->commit();
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function uploadProductVersion(
        int $productId,
        string $version,
        string $filePath,
        ?int $fileSizeBytes,
        string $changelog,
        int $adminUserId,
        bool $setCurrent
    ): void {
        $this->db->beginTransaction();
        try {
            if ($setCurrent) {
                $resetStmt = $this->db->prepare('UPDATE product_versions SET is_current = 0 WHERE product_id = :product_id');
                $resetStmt->execute(['product_id' => $productId]);
            }

            $stmt = $this->db->prepare(
                'INSERT INTO product_versions (
                    product_id, version, changelog, file_path, file_size_bytes, uploaded_by, is_current
                 ) VALUES (
                    :product_id, :version, :changelog, :file_path, :file_size_bytes, :uploaded_by, :is_current
                 )
                 ON DUPLICATE KEY UPDATE
                    changelog = VALUES(changelog),
                    file_path = VALUES(file_path),
                    file_size_bytes = VALUES(file_size_bytes),
                    uploaded_by = VALUES(uploaded_by),
                    is_current = VALUES(is_current)'
            );
            $stmt->execute([
                'product_id' => $productId,
                'version' => $version,
                'changelog' => $changelog,
                'file_path' => $filePath,
                'file_size_bytes' => $fileSizeBytes,
                'uploaded_by' => $adminUserId,
                'is_current' => $setCurrent ? 1 : 0,
            ]);

            if ($setCurrent) {
                $updateProductStmt = $this->db->prepare(
                    'UPDATE products
                     SET current_version = :version, file_path = :file_path, last_updated_at = NOW()
                     WHERE id = :product_id'
                );
                $updateProductStmt->execute([
                    'version' => $version,
                    'file_path' => $filePath,
                    'product_id' => $productId,
                ]);
            }

            $this->db->commit();
        } catch (\Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * @return array<int, array{id:int,name:string,email:string}>
     */
    public function adminUsersList(): array
    {
        $rows = $this->db->query('SELECT id, name, email FROM users ORDER BY name ASC')->fetchAll();
        return array_map(static fn (array $row): array => [
            'id' => (int) $row['id'],
            'name' => (string) $row['name'],
            'email' => (string) $row['email'],
        ], $rows);
    }

    public function resetUpdateCount(int $userId, int $productId): void
    {
        $stmt = $this->db->prepare(
            'UPDATE purchases
             SET update_count = 0
             WHERE user_id = :user_id AND product_id = :product_id'
        );
        $stmt->execute(['user_id' => $userId, 'product_id' => $productId]);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function productVersions(int $productId): array
    {
        $stmt = $this->db->prepare(
            'SELECT id, version, changelog, file_path, file_size_bytes, created_at, is_current
             FROM product_versions
             WHERE product_id = :product_id
             ORDER BY created_at DESC'
        );
        $stmt->execute(['product_id' => $productId]);
        return $stmt->fetchAll();
    }

    public function grantManualDownloadAccess(int $userId, int $productId, int $extraDownloads, int $adminUserId, string $reason): void
    {
        $stmt = $this->db->prepare(
            'UPDATE purchases
             SET max_update_downloads = max_update_downloads + :extra_downloads
             WHERE user_id = :user_id AND product_id = :product_id'
        );
        $stmt->execute([
            'extra_downloads' => $extraDownloads,
            'user_id' => $userId,
            'product_id' => $productId,
        ]);
    }

    public function createCategory(string $name, string $slug, ?string $description, string $icon = 'Tag', string $hue = 'from-orange-400/20 to-orange-500/10'): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO categories (name, slug, description, icon, hue) VALUES (:name, :slug, :description, :icon, :hue)'
        );
        $stmt->execute([
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'icon' => $icon,
            'hue' => $hue,
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function deleteCategory(int $categoryId): void
    {
        $stmt = $this->db->prepare('DELETE FROM categories WHERE id = :id');
        $stmt->execute(['id' => $categoryId]);
    }

    private function latestVersionForProduct(int $productId): ?string
    {
        $stmt = $this->db->prepare(
            'SELECT version FROM product_versions
             WHERE product_id = :product_id
             ORDER BY created_at DESC
             LIMIT 1'
        );
        $stmt->execute(['product_id' => $productId]);
        $row = $stmt->fetch();

        return is_array($row) ? (string) $row['version'] : null;
    }

    /**
     * @param array<string, mixed> $row
     * @return array<string, mixed>
     */
    private function mapProductRow(array $row): array
    {
        $features = json_decode((string) ($row['key_features'] ?? '[]'), true);
        if (!is_array($features)) {
            $features = [];
        }

        $title = (string) $row['title'];
        $firstChar = strtoupper(substr($title, 0, 1));
        $price = (float) $row['price'];
        $discount = $row['discount_price'] !== null ? (float) $row['discount_price'] : null;

        $currentVersion = (string) $row['current_version'];
        $latestVersion = isset($row['latest_version']) && $row['latest_version'] !== null
            ? (string) $row['latest_version']
            : $currentVersion;

        return [
            'id'           => (int) $row['id'],
            'letter'       => $firstChar !== '' ? $firstChar : 'P',
            'name'         => $title,
            'slug'         => (string) ($row['slug'] ?? ''),
            'cat'          => (string) $row['category_name'],
            'price'        => number_format($price, 2),
            'og'           => number_format($discount ?? ($price * 2), 2),
            'ver'          => $currentVersion,
            'latestVer'    => $latestVersion,
            'hasUpdate'    => $latestVersion !== $currentVersion,
            'updateCount'  => 0,
            'tone'         => 'from-primary/30 to-primary/5',
            'image'        => (string) ($row['image_url'] ?? ''),
            'desc'         => (string) ($row['description'] ?? ''),
            'features'     => $features,
            'license'      => 'GPLv3 - Unlimited Sites',
            'lastUpdated'  => date('F d, Y', strtotime((string) $row['last_updated_at'])),
            'demo_url'     => (string) ($row['demo_url'] ?? ''),
            'technical_info' => (string) ($row['technical_info'] ?? 'PHP 8.1+ / WP 6.0+'),
            'file_path'    => (string) ($row['file_path'] ?? ''),
        ];
    }
    /**
     * @return array<int, array<string, mixed>>
     */
    public function getProductPurchasers(int $productId): array
    {
        $stmt = $this->db->prepare("
            SELECT u.id, u.name, u.email 
            FROM users u
            JOIN purchases p ON u.id = p.user_id
            WHERE p.product_id = :product_id
        ");
        $stmt->execute(['product_id' => $productId]);
        return $stmt->fetchAll();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getInquiries(): array
    {
        return $this->db->query("SELECT * FROM inquiries ORDER BY created_at DESC")->fetchAll();
    }

    public function deleteInquiry(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM inquiries WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getUserByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch() ?: null;
    }
}
