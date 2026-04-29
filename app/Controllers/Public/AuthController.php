<?php

declare(strict_types=1);

namespace App\Controllers\Public;

use App\Core\Controller;
use App\Services\SessionAuth;
use App\Services\AdminAuth;

final class AuthController extends Controller
{
    private SessionAuth $auth;
    private AdminAuth $adminAuth;

    public function __construct()
    {
        $this->auth = new SessionAuth();
        $this->adminAuth = new AdminAuth();
    }

    /**
     * @param array<string, string> $params
     */
    public function login(array $params = []): void
    {
        $email = (string) ($_POST['email'] ?? '');
        $password = (string) ($_POST['password'] ?? '');
        $returnTo = (string) ($_POST['return_to'] ?? '');

        if ($returnTo === '/admin') {
            $success = $this->adminAuth->login($email, $password);
            if (!$success) {
                header('Location: /admin/login?login=failed');
                exit;
            }
            header('Location: /admin');
            exit;
        }

        $user = $this->auth->login($email, $password);

        if (!$user) {
            header('Location: /login?login=failed');
            exit;
        }

        // Generate Code and Send Email
        $code = $this->auth->generateVerificationCode((int)$user['id']);
        \App\Services\MailService::send($user['email'], 'auth_verification', [
            'user_name' => $user['name'],
            'code' => $code
        ]);

        $_SESSION['verify_user_id'] = $user['id'];
        $_SESSION['verify_return_to'] = $returnTo;
        
        header('Location: /verify');
        exit;
    }

    public function verify(array $params = []): void
    {
        $userId = (int)($_SESSION['verify_user_id'] ?? 0);
        $code = trim((string)($_POST['code'] ?? ''));

        if ($userId <= 0 || $code === '') {
            header('Location: /verify?error=invalid');
            exit;
        }

        if ($this->auth->verifyCode($userId, $code)) {
            $user = (new \App\Models\MarketplaceModel())->findUserById($userId);
            $this->auth->createSession($user);
            
            $returnTo = (string)($_SESSION['verify_return_to'] ?? '');
            unset($_SESSION['verify_user_id'], $_SESSION['verify_return_to']);
            
            header('Location: ' . ($returnTo !== '' ? $returnTo : '/profile'));
            exit;
        }

        header('Location: /verify?error=failed');
        exit;
    }

    public function resendCode(array $params = []): void
    {
        $userId = (int)($_SESSION['verify_user_id'] ?? 0);
        if ($userId <= 0) {
            header('Location: /login');
            exit;
        }

        $user = (new \App\Models\MarketplaceModel())->findUserById($userId);
        if ($user) {
            $code = $this->auth->generateVerificationCode($userId);
            \App\Services\MailService::send($user['email'], 'auth_verification', [
                'user_name' => $user['name'],
                'code' => $code
            ]);
        }

        header('Location: /verify?status=resent');
        exit;
    }

    public function sendResetLink(array $params = []): void
    {
        $email = trim((string)($_POST['email'] ?? ''));
        if ($email === '') {
            header('Location: /forgot-password?error=empty');
            exit;
        }

        $token = $this->auth->generateResetToken($email);
        if ($token) {
            $user = (new \App\Models\MarketplaceModel())->findUserByEmail($email);
            \App\Services\MailService::send($email, 'password_reset', [
                'user_name' => $user['name'],
                'action_url' => base_url('/reset-password?token=' . $token)
            ]);
        }

        // Always redirect to success to prevent email enumeration
        header('Location: /forgot-password?status=sent');
        exit;
    }

    public function resetPassword(array $params = []): void
    {
        $token = trim((string)($_POST['token'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        if ($token === '' || strlen($password) < 6) {
            header('Location: /reset-password?token=' . $token . '&error=invalid');
            exit;
        }

        if ($this->auth->resetPassword($token, $password)) {
            header('Location: /login?reset=success');
            exit;
        }

        header('Location: /reset-password?token=' . $token . '&error=failed');
        exit;
    }

    /**
     * @param array<string, string> $params
     */
    public function register(array $params = []): void
    {
        $name = trim((string) ($_POST['name'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        
        $address = [
            'country' => trim((string) ($_POST['country'] ?? '')),
            'city' => trim((string) ($_POST['city'] ?? '')),
            'apartment' => trim((string) ($_POST['apartment'] ?? '')),
            'postal_code' => trim((string) ($_POST['postal_code'] ?? '')),
        ];

        $payment = [
            'card_number' => trim((string) ($_POST['card_number'] ?? '')),
            'card_expiry' => trim((string) ($_POST['card_expiry'] ?? '')),
            'card_cvc' => trim((string) ($_POST['card_cvc'] ?? '')),
        ];

        $avatarUrl = (string)($_POST['avatar_url'] ?? '');

        if ($name === '' || $email === '' || strlen($password) < 6) {
            header('Location: /register?error=' . urlencode('Please fill all required fields correctly. Password must be at least 6 characters.'));
            exit;
        }

        $user = $this->auth->register($name, $email, $password, $address, $payment, $avatarUrl);

        if (!$user) {
            header('Location: /register?error=' . urlencode('Email address is already in use.'));
            exit;
        }

        $_SESSION['verify_user_id'] = $user['id'];
        
        header('Location: /verify');
        exit;
    }

    /**
     * @param array<string, string> $params
     */
    public function logout(array $params = []): void
    {
        $this->auth->logout();
        $this->adminAuth->logout();
        header('Location: /');
        exit;
    }
}
