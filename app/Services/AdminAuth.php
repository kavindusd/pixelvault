<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\MarketplaceModel;

final class AdminAuth
{
    public function __construct(
        private readonly MarketplaceModel $marketplace = new MarketplaceModel()
    ) {
    }

    /**
     * @return array<string, mixed>|null
     */
    public function admin(): ?array
    {
        if (isset($_SESSION['admin']) && is_array($_SESSION['admin'])) {
            return $_SESSION['admin'];
        }

        return null;
    }

    public function login(string $email, string $password): bool
    {
        $admin = $this->marketplace->findAdminByEmail($email);
        if (!$admin) {
            return false;
        }

        $matches = password_verify($password, (string) $admin['password_hash']);
        if (!$matches) {
            return false;
        }

        $_SESSION['admin'] = [
            'id' => (int) $admin['id'],
            'name' => (string) $admin['name'],
            'email' => (string) $admin['email'],
            'role' => (string) $admin['role'],
        ];

        return true;
    }

    public function logout(): void
    {
        unset($_SESSION['admin']);
    }

    public function createAdmin(string $name, string $email, string $password, string $role = 'Super Admin'): bool
    {
        $existing = $this->marketplace->findAdminByEmail($email);
        if ($existing) {
            return false;
        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $this->marketplace->createAdmin($name, $email, $passwordHash, $role);
        return true;
    }
}
