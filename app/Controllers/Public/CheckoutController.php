<?php

declare(strict_types=1);

namespace App\Controllers\Public;

use App\Core\Controller;
use App\Models\MarketplaceModel;
use App\Services\SessionAuth;
use App\Services\SessionCart;

final class CheckoutController extends Controller
{
    private SessionCart $cart;
    private SessionAuth $auth;
    private MarketplaceModel $marketplace;

    public function __construct()
    {
        $this->cart = new SessionCart();
        $this->auth = new SessionAuth();
        $this->marketplace = new MarketplaceModel();
    }

    /**
     * @param array<string, string> $params
     */
    public function process(array $params = []): void
    {
        $items = $this->cart->items();

        if ($items === []) {
            header('Location: /checkout');
            exit;
        }

        // Fix B5/B6: gate purchase on a valid authenticated user. Attempt
        // optional guest login first, then bail out cleanly if still anonymous
        // (instead of letting the FK insert blow up with a 500).
        if (!$this->auth->user()) {
            $email = (string) ($_POST['email'] ?? '');
            $password = (string) ($_POST['account_password'] ?? '');

            if ($email !== '' && $password !== '') {
                $this->auth->login($email, $password);
            }
        }

        $user = $this->auth->user();
        if (!$user) {
            header('Location: /checkout?error=auth');
            exit;
        }

        $userId = (int) ($user['id'] ?? 0);
        if ($userId <= 0) {
            header('Location: /checkout?error=auth');
            exit;
        }

        $productIds = array_map(static fn (array $item): int => (int) ($item['id'] ?? 0), $items);

        $paymentMethod = (string) ($_POST['payment_method'] ?? 'PayPal');
        try {
            $this->marketplace->createOrder($userId, $items, $this->cart->total(), $paymentMethod);
        } catch (\Throwable $e) {
            // Order failed — surface a user-friendly state instead of a 500.
            header('Location: /checkout?error=order');
            exit;
        }

        $this->auth->purchaseProducts($productIds);
        $this->cart->clear();

        header('Location: /success');
        exit;
    }
}
