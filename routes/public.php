<?php

declare(strict_types=1);

use App\Controllers\Public\AuthController;
use App\Controllers\Public\CartController;
use App\Controllers\Public\CheckoutController;
use App\Controllers\Public\PageController;

$router->get('/', [PageController::class, 'home']);
$router->get('/marketplace', [PageController::class, 'marketplace']);
$router->get('/categories', [PageController::class, 'categories']);
$router->get('/pricing', [PageController::class, 'pricing']);
$router->get('/login', [PageController::class, 'login']);
$router->get('/register', [PageController::class, 'register']);
$router->get('/updates', [PageController::class, 'updates']);
$router->get('/profile', [PageController::class, 'profile']);
$router->get('/checkout', [PageController::class, 'checkout']);
$router->get('/success', [PageController::class, 'success']);
$router->get('/product/{id}', [PageController::class, 'product']);
$router->get('/download/{id}/{version?}', [PageController::class, 'download']);
$router->get('/verify', [PageController::class, 'verify']);
$router->get('/forgot-password', [PageController::class, 'forgotPassword']);
$router->get('/reset-password', [PageController::class, 'resetPassword']);

$router->addRoute('POST', '/cart/add', [CartController::class, 'add']);
$router->addRoute('POST', '/cart/remove', [CartController::class, 'remove']);
$router->addRoute('POST', '/auth/login', [AuthController::class, 'login']);
$router->addRoute('POST', '/auth/verify', [AuthController::class, 'verify']);
$router->addRoute('POST', '/auth/resend-code', [AuthController::class, 'resendCode']);
$router->addRoute('POST', '/auth/forgot-password', [AuthController::class, 'sendResetLink']);
$router->addRoute('POST', '/auth/reset-password', [AuthController::class, 'resetPassword']);
$router->addRoute('POST', '/auth/register', [AuthController::class, 'register']);
$router->addRoute('POST', '/auth/logout', [AuthController::class, 'logout']);
$router->addRoute('POST', '/checkout/process', [CheckoutController::class, 'process']);
$router->addRoute('POST', '/profile/link-payment', [PageController::class, 'linkPayment']);
$router->addRoute('POST', '/profile/remove-payment', [PageController::class, 'removePayment']);
$router->addRoute('POST', '/contact', [PageController::class, 'submitContact']);
$router->addRoute('POST', '/request-extension', [PageController::class, 'submitExtensionRequest']);
$router->addRoute('POST', '/admin/save-config', [App\Controllers\Admin\ManagementController::class, 'saveConfig']);

$router->get('/{path:.*}', [PageController::class, 'notFound']);
