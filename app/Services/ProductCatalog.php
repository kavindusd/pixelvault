<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\MarketplaceModel;

final class ProductCatalog
{
    public function __construct(
        private readonly MarketplaceModel $marketplace = new MarketplaceModel(),
    ) {
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function all(): array
    {
        return $this->marketplace->products();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function find(int $id): ?array
    {
        return $this->marketplace->productById($id);
    }
}
