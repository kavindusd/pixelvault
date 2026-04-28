<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class SiteConfigModel
{
    private PDO $db;
    private static ?array $cache = null;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function all(): array
    {
        if (self::$cache !== null) {
            return self::$cache;
        }

        $stmt = $this->db->query("SELECT * FROM site_configs");
        $rows = $stmt->fetchAll();
        
        $configs = [];
        foreach ($rows as $row) {
            $configs[$row['key']] = $row['value'];
        }

        self::$cache = $configs;
        return $configs;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $configs = $this->all();
        return $configs[$key] ?? $default;
    }

    public function update(string $key, string $value): bool
    {
        $stmt = $this->db->prepare("UPDATE site_configs SET value = :value WHERE `key` = :key");
        $success = $stmt->execute(['value' => $value, 'key' => $key]);
        if ($success) {
            self::$cache = null; // Invalidate cache
        }
        return $success;
    }

    public function getByGroup(string $group): array
    {
        $stmt = $this->db->prepare("SELECT * FROM site_configs WHERE `group` = :group");
        $stmt->execute(['group' => $group]);
        return $stmt->fetchAll();
    }

    public function getAllGroups(): array
    {
        $stmt = $this->db->query("SELECT DISTINCT `group` FROM site_configs");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
