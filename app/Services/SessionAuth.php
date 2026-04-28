<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\MarketplaceModel;

final class SessionAuth
{
    public function __construct(
        private readonly MarketplaceModel $marketplace = new MarketplaceModel(),
        private readonly JwtService $jwt = new JwtService(),
    ) {
    }

    /**
     * @return array<string, mixed>|null
     */
    public function user(): ?array
    {
        if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
            return $_SESSION['user'];
        }

        $cookieName = $this->jwt->cookieName();
        $token = (string) ($_COOKIE[$cookieName] ?? '');
        if ($token === '') {
            return null;
        }

        $payload = $this->jwt->decode($token);
        if (!$payload) {
            $this->logout();
            return null;
        }

        $userId = (int) ($payload['sub'] ?? 0);
        if ($userId <= 0) {
            $this->logout();
            return null;
        }

        $user = $this->marketplace->findUserById($userId);
        if (!$user) {
            $this->logout();
            return null;
        }

        $sessionUser = $this->toSessionUser($user);
        $_SESSION['user'] = $sessionUser;

        return $sessionUser;
    }

    public function login(string $email, string $password): ?array
    {
        $user = $this->marketplace->findUserByEmail($email);
        if (!$user) {
            return null;
        }

        $matches = password_verify($password, (string) $user['password_hash']);
        if (!$matches) {
            return null;
        }

        return $user;
    }

    public function createSession(array $user): bool
    {
        $userId = (int) $user['id'];
        $sessionUser = $this->toSessionUser($user);
        $_SESSION['user'] = $sessionUser;
        $token = $this->jwt->encode([
            'sub' => $userId,
            'email' => (string) $user['email'],
            'role' => (string) $user['role'],
        ]);

        $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || ((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
        setcookie($this->jwt->cookieName(), $token, [
            'expires' => time() + 86400,
            'path' => '/',
            'secure' => $isHttps,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        return true;
    }

    public function generateVerificationCode(int $userId): string
    {
        $code = (string) random_int(100000, 999999);
        $expires = date('Y-m-d H:i:s', time() + 600); // 10 minutes
        $this->marketplace->updateUserVerificationCode($userId, $code, $expires);
        return $code;
    }

    public function verifyCode(int $userId, string $code): bool
    {
        $user = $this->marketplace->findUserById($userId);
        if (!$user) return false;

        $storedCode = (string)($user['verification_code'] ?? '');
        $expires = (string)($user['token_expires_at'] ?? '');

        if ($storedCode === $code && strtotime($expires) > time()) {
            $this->marketplace->updateUserVerificationCode($userId, null, null);
            return true;
        }

        return false;
    }

    public function generateResetToken(string $email): ?string
    {
        $user = $this->marketplace->findUserByEmail($email);
        if (!$user) return null;

        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', time() + 3600); // 1 hour
        $this->marketplace->updateUserResetToken((int)$user['id'], $token, $expires);
        return $token;
    }

    public function resetPassword(string $token, string $newPassword): bool
    {
        $user = $this->marketplace->findUserByResetToken($token);
        if (!$user) return false;

        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->marketplace->updatePassword((int)$user['id'], $hash);
    }

    public function register(string $name, string $email, string $password, array $address = [], array $payment = [], ?string $avatarUrl = null): ?array
    {
        $existing = $this->marketplace->findUserByEmail($email);
        if ($existing) {
            return null; // Email already in use
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $userId = $this->marketplace->createUser($name, $email, $passwordHash, $address, $payment, $avatarUrl);
        
        $user = $this->marketplace->findUserById($userId);
        if ($user) {
            // Send verification code for registration as well
            $code = $this->generateVerificationCode($userId);
            \App\Services\MailService::send($email, 'auth_verification', [
                'user_name' => $name,
                'code' => $code
            ]);
            return $user;
        }

        return null;
    }

    public function logout(): void
    {
        unset($_SESSION['user']);
        $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || ((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
        setcookie($this->jwt->cookieName(), '', [
            'expires' => time() - 3600,
            'path' => '/',
            'secure' => $isHttps,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }

    /**
     * Fix B1: call ->user() exactly once.
     *
     * @param array<int> $productIds
     */
    public function purchaseProducts(array $productIds): void
    {
        $user = $this->user();
        if (!$user) {
            return;
        }

        $existing = $user['purchasedProductIds'] ?? [];
        $user['purchasedProductIds'] = array_values(array_unique(array_map('intval', array_merge($existing, $productIds))));
        $_SESSION['user'] = $user;
    }

    /**
     * Fix B1: call ->user() exactly once.
     */
    public function incrementProductUpdateCount(int $productId): void
    {
        $user = $this->user();
        if (!$user) {
            return;
        }

        $current = (int) ($user['productUpdates'][$productId] ?? 0);
        $user['productUpdates'][$productId] = $current + 1;
        $_SESSION['user'] = $user;
        $userId = (int) ($user['id'] ?? 0);
        if ($userId > 0) {
            $this->marketplace->incrementUpdateCount($userId, $productId);
        }
    }

    public function linkPaymentMethod(string $type, string $value): bool
    {
        $user = $this->user();
        if (!$user) {
            return false;
        }

        $userId = (int) $user['id'];
        $paypal = $user['paypal_email'] ?? null;
        $payhere = $user['payhere_id'] ?? null;

        if ($type === 'paypal') {
            $paypal = $value;
        } elseif ($type === 'payhere') {
            $payhere = $value;
        }

        $success = $this->marketplace->updateUserPaymentMethods($userId, $paypal, $payhere);
        if ($success) {
            $user['paypal_email'] = $paypal;
            $user['payhere_id'] = $payhere;
            $_SESSION['user'] = $user;
            return true;
        }

        return false;
    }

    /**
     * @param array<string, mixed> $user
     * @return array<string, mixed>
     */
    private function toSessionUser(array $user): array
    {
        $userId = (int) $user['id'];

        return [
            'id' => $userId,
            'name' => (string) $user['name'],
            'email' => (string) $user['email'],
            'avatar' => (string) ($user['avatar_url'] ?? ''),
            'role' => ((string) $user['role']) === 'admin' ? 'Administrator' : 'Regular Member',
            'paypal_email' => (string) ($user['paypal_email'] ?? ''),
            'payhere_id' => (string) ($user['payhere_id'] ?? ''),
            'productUpdates' => $this->marketplace->productUpdateCounts($userId),
            'purchasedProductIds' => $this->marketplace->purchasedProductIds($userId),
        ];
    }
}
