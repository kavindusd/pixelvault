<?php

declare(strict_types=1);

namespace App\Services;

final class SessionCart
{
    /**
     * @return array<int, array<string, mixed>>
     */
    public function items(): array
    {
        return $_SESSION['cart'] ?? [];
    }

    public function add(array $product): bool
    {
        $items = $this->items();
        $newId = (int) ($product['id'] ?? 0);

        // B20 fix: compare ids as ints so a string id from session storage
        // doesn't bypass the duplicate check.
        foreach ($items as $item) {
            if ((int) ($item['id'] ?? 0) === $newId) {
                return false;
            }
        }

        $items[] = [
            'id' => $newId,
            'name' => $product['name'],
            'price' => $product['price'],
            'image' => $product['image'] ?? null,
            'letter' => $product['letter'],
            'cat' => $product['cat'],
            'ver' => $product['ver'] ?? '1.0.0',
        ];

        $_SESSION['cart'] = $items;
        return true;
    }

    public function remove(int $productId): void
    {
        $_SESSION['cart'] = array_values(array_filter(
            $this->items(),
            static fn (array $item): bool => (int) ($item['id'] ?? 0) !== $productId
        ));
    }

    public function clear(): void
    {
        $_SESSION['cart'] = [];
    }

    public function total(): float
    {
        return array_reduce($this->items(), static function (float $total, array $item): float {
            return $total + (float) ($item['price'] ?? 0);
        }, 0.0);
    }

    public function count(): int
    {
        return count($this->items());
    }
}
