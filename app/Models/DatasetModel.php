<?php

declare(strict_types=1);

namespace App\Models;

final class DatasetModel
{
    /**
     * @return array<int|string, mixed>|null
     */
    public function all(string $dataset): ?array
    {
        $safeDataset = preg_replace('/[^a-z0-9\-]/i', '', $dataset);

        if ($safeDataset === '') {
            return null;
        }

        $path = BASE_PATH . '/storage/data/' . $safeDataset . '.json';

        if (!is_file($path)) {
            return null;
        }

        $contents = file_get_contents($path);

        if ($contents === false) {
            return null;
        }

        $decoded = json_decode($contents, true);

        return is_array($decoded) ? $decoded : null;
    }

    /**
     * @return array<int|string, mixed>
     */
    public function allOrEmpty(string $dataset): array
    {
        return $this->all($dataset) ?? [];
    }
}
