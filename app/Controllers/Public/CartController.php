<?php

declare(strict_types=1);

namespace App\Controllers\Public;

use App\Core\Controller;
use App\Services\ProductCatalog;
use App\Services\SessionCart;

final class CartController extends Controller
{
    private SessionCart $cart;
    private ProductCatalog $catalog;

    public function __construct()
    {
        $this->cart = new SessionCart();
        $this->catalog = new ProductCatalog();
    }

    /**
     * @param array<string, string> $params
     */
    public function add(array $params = []): void
    {
        $productId = (int) ($_POST['product_id'] ?? 0);
        $returnTo = (string) ($_POST['return_to'] ?? '/marketplace');
        $product = $this->catalog->find($productId);

        if ($product) {
            $this->cart->add($product);
        }

        header('Location: ' . $returnTo);
        exit;
    }

    /**
     * @param array<string, string> $params
     */
    public function remove(array $params = []): void
    {
        $productId = (int) ($_POST['product_id'] ?? 0);
        $returnTo = (string) ($_POST['return_to'] ?? '/checkout');
        $this->cart->remove($productId);
        header('Location: ' . $returnTo);
        exit;
    }
}
