<?php

declare(strict_types=1);

use App\Controllers\Admin\PageController;
use App\Controllers\Admin\ManagementController;

$router->get('/admin', [PageController::class, 'dashboard']);
$router->addRoute('POST', '/admin/products/create', [ManagementController::class, 'createProduct']);
$router->addRoute('POST', '/admin/products/update', [ManagementController::class, 'updateProduct']);
$router->addRoute('POST', '/admin/products/delete', [ManagementController::class, 'deleteProduct']);
$router->addRoute('POST', '/admin/products/upload-update', [ManagementController::class, 'uploadUpdate']);
$router->addRoute('POST', '/admin/users/reset-update-count', [ManagementController::class, 'resetUpdateCount']);
$router->addRoute('POST', '/admin/users/grant-access', [ManagementController::class, 'grantAccess']);
$router->addRoute('POST', '/admin/categories/create', [ManagementController::class, 'createCategory']);
$router->addRoute('POST', '/admin/categories/delete', [ManagementController::class, 'deleteCategory']);
$router->addRoute('POST', '/admin/email/save', [ManagementController::class, 'saveEmailTemplate']);
$router->addRoute('POST', '/admin/security/toggle', [ManagementController::class, 'toggleSecurity']);
$router->addRoute('POST', '/admin/security/sitemap', [ManagementController::class, 'rebuildSitemap']);
$router->addRoute('POST', '/admin/accounts/create', [ManagementController::class, 'createAdminAccount']);
$router->addRoute('POST', '/admin/inquiries/delete', [ManagementController::class, 'deleteInquiry']);
$router->addRoute('POST', '/admin/settings/save', [ManagementController::class, 'saveConfig']);
