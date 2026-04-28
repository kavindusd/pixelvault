<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\DatasetModel;
use App\Models\MarketplaceModel;
use App\Services\ProductCatalog;
use App\Services\AdminAuth;
use App\Services\SessionCart;

final class PageController extends Controller
{
    private DatasetModel $datasets;
    private SessionCart $cart;
    private AdminAuth $auth;
    private ProductCatalog $catalog;
    private MarketplaceModel $marketplace;

    public function __construct()
    {
        $this->datasets = new DatasetModel();
        $this->cart = new SessionCart();
        $this->auth = new AdminAuth();
        $this->catalog = new ProductCatalog();
        $this->marketplace = new MarketplaceModel();
    }

    /**
     * @param array<string, string> $params
     */
    public function dashboard(array $params = []): void
    {
        $admin = $this->auth->admin();

        if (!$admin) {
            $this->render('Admin/pages/admin-login', [
                'pageTitle' => 'Admin Login - ' . app_config('name'),
                'currentPath' => '/admin',
                'navigation' => $this->datasets->allOrEmpty('navigation'),
                'cartCount' => $this->cart->count(),
                'user' => null,
            ]);
            return;
        }

        $activeTab = (string) query('tab', 'dashboard');
        $subView = (string) query('view', 'overview');

        $products = $this->catalog->all();
        $orders = $this->marketplace->adminOrders();
        $members = $this->marketplace->adminUsersList();

        $this->render('Admin/pages/admin', [
            'pageTitle' => 'Admin - ' . app_config('name'),
            'currentPath' => '/admin',
            'navigation' => $this->datasets->allOrEmpty('navigation'),
            'cartCount' => $this->cart->count(),
            'user' => null, // We separate admin from user, but header might expect 'user'
            'admin' => $admin,
            'activeTab' => $activeTab,
            'subView' => $subView,
            'categoriesData' => $this->marketplace->categoriesPayload(),
            'products' => $products,
            'activityData' => $this->datasets->allOrEmpty('activity'),
            'adminStatsData' => $this->datasets->allOrEmpty('admin-stats'),
            'orders' => $orders,
            'members' => $members,
            'admins' => $this->marketplace->getAllAdmins(),
            'emailTemplatesData' => $this->datasets->allOrEmpty('email-templates'),
            'securityData' => $this->datasets->allOrEmpty('security-settings'),
            'updates' => $this->marketplace->updatesList(),
            'inquiries' => $this->marketplace->getInquiries(),
        ]);
    }
}
