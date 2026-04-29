<?php

declare(strict_types=1);

namespace App\Services;

final class MailService
{
    /**
     * Send an email using a template and SMTP if configured.
     */
    public static function send(string $to, string $templateKey, array $data = [], ?string $fromEmail = null, ?string $fromName = null): bool
    {
        $template = self::getTemplate($templateKey);
        if (!$template) {
            return false;
        }

        $subject = self::replaceVariables($template['subject'] ?? '', $data);
        $body = self::replaceVariables($template['body'] ?? '', $data);
        $cta = $template['cta'] ?? '';

        $message = self::wrapInHtml($subject, $body, $cta, $data);

        return self::dispatch($to, $subject, $message, $fromEmail, $fromName);
    }

    /**
     * Send a raw HTML email directly (no template file required).
     * Used by the contact form and other direct-send cases.
     */
    public static function sendDirect(string $to, string $subject, string $htmlBody): bool
    {
        return self::dispatch($to, $subject, $htmlBody);
    }

    public static function notifyProductBuyers(array $purchasers, string $productName, string $changeNote): void
    {
        $senderEmail = (string) site_config('notification_sender_email', '');
        $senderName  = (string) site_config('notification_sender_name', 'PixelVault Updates');

        foreach ($purchasers as $user) {
            self::send((string) $user['email'], 'product_updated', [
                'user_name'    => (string) ($user['name'] ?? 'User'),
                'product_name' => $productName,
                'change_note'  => $changeNote,
                'action_url'   => base_url('/profile'),
            ], $senderEmail, $senderName);
        }
    }

    /**
     * Core dispatch: log, then SMTP or mail() fallback.
     */
    private static function dispatch(string $to, string $subject, string $message, ?string $fromEmail = null, ?string $fromName = null): bool
    {
        $logDir = BASE_PATH . '/storage/logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        $logFile = $logDir . '/mail.log';

        $smtpHost = (string) site_config('smtp_host', '');
        $smtpUser = (string) site_config('smtp_user', '');
        $smtpPass = (string) site_config('smtp_pass', '');
        $smtpPort = (int) site_config('smtp_port', '587');
        $smtpEnc  = (string) site_config('smtp_encryption', 'tls');

        $logEntry = "[" . date('Y-m-d H:i:s') . "] ATTEMPT To: $to | Subject: $subject\n";

        // Use SMTP if host + user are configured
        if ($smtpHost !== '' && $smtpUser !== '') {
            $logEntry .= "Method: SMTP ($smtpHost)\n";
            file_put_contents($logFile, $logEntry, FILE_APPEND);

            $success = self::sendSmtp($to, $subject, $message, $smtpHost, $smtpPort, $smtpUser, $smtpPass, $smtpEnc, $logFile, $fromEmail, $fromName);

            $status = $success ? "SUCCESS" : "FAILED";
            file_put_contents($logFile, "Result: $status\n" . str_repeat('-', 50) . "\n", FILE_APPEND);
            return $success;
        }

        // Fallback to PHP mail()
        $logEntry .= "Method: PHP mail() (SMTP credentials missing)\n";
        file_put_contents($logFile, $logEntry, FILE_APPEND);

        // Use support_email as the From address for the mail() fallback
        $fromAddress = $fromEmail ?? (string) site_config('support_email', 'no-reply@pixelvault.app');
        $siteName    = $fromName ?? (string) site_config('site_name', 'PixelVault');

        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=utf-8',
            'From: ' . $siteName . ' <' . $fromAddress . '>',
            'X-Mailer: PHP/' . phpversion(),
        ];

        $success = mail($to, $subject, $message, implode("\r\n", $headers));
        $status = $success ? "SUCCESS" : "FAILED (Check your server mail configuration)";
        file_put_contents($logFile, "Result: $status\n" . str_repeat('-', 50) . "\n", FILE_APPEND);

        return $success;
    }

    private static function getTemplate(string $key): ?array
    {
        $path = BASE_PATH . '/storage/data/email-templates.json';
        if (!is_file($path)) {
            return null;
        }
        $data = json_decode((string) file_get_contents($path), true);
        return $data['emailTemplates'][$key] ?? null;
    }

    private static function replaceVariables(string $content, array $data): string
    {
        foreach ($data as $key => $value) {
            $content = str_replace('{{' . $key . '}}', (string) $value, $content);
        }
        return $content;
    }

    private static function sendSmtp(string $to, string $subject, string $message, string $host, int $port, string $user, string $pass, string $encryption, string $logFile, ?string $fromEmail = null, ?string $fromName = null): bool
    {
        $log = function($msg) use ($logFile) {
            file_put_contents($logFile, "SMTP DEBUG: $msg\n", FILE_APPEND);
        };

        try {
            $siteName = $fromName ?? site_config('site_name', 'PixelVault');
            // Envelope address
            $from = $user;

            $ehloHost = $_SERVER['HTTP_HOST'] ?? gethostname() ?: 'localhost';
            $remote = ($encryption === 'ssl' ? 'ssl://' : '') . $host;

            $context = stream_context_create([
                'ssl' => [
                    'verify_peer'       => false,
                    'verify_peer_name'  => false,
                    'allow_self_signed' => true,
                ],
            ]);

            $socket = stream_socket_client($remote . ':' . $port, $errno, $errstr, 15, STREAM_CLIENT_CONNECT, $context);
            if (!$socket) {
                $log("Connection Failed: $errstr ($errno)");
                return false;
            }

            $getResponse = static function ($socket): string {
                $res = '';
                while ($str = fgets($socket, 515)) {
                    $res .= $str;
                    if (substr($str, 3, 1) === ' ') {
                        break;
                    }
                }
                return $res;
            };

            $r = $getResponse($socket);
            if (strpos($r, '220') !== 0) { $log("Connect Error: $r"); fclose($socket); return false; }

            fwrite($socket, "EHLO $ehloHost\r\n");
            $getResponse($socket);

            if ($encryption === 'tls') {
                fwrite($socket, "STARTTLS\r\n");
                $r = $getResponse($socket);
                if (strpos($r, '220') !== 0) { $log("STARTTLS Error: $r"); fclose($socket); return false; }

                // STREAM_CRYPTO_METHOD_TLS_CLIENT attempts TLS 1.0 first, which
                // Gmail and most modern providers have disabled. Explicitly require
                // TLS 1.2 (and 1.3 when available) to avoid "Crypto Enable Failed".
                $cryptoMethod = STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT;
                if (defined('STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT')) {
                    $cryptoMethod |= STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT;
                }
                if (!stream_socket_enable_crypto($socket, true, $cryptoMethod)) {
                    $log("Crypto Enable Failed (TLS 1.2/1.3 handshake rejected — check SMTP host/port and that openssl extension is loaded)");
                    fclose($socket);
                    return false;
                }
                fwrite($socket, "EHLO $ehloHost\r\n");
                $getResponse($socket);
            }

            if ($pass !== '') {
                fwrite($socket, "AUTH LOGIN\r\n");
                $getResponse($socket);
                fwrite($socket, base64_encode($user) . "\r\n");
                $getResponse($socket);
                fwrite($socket, base64_encode($pass) . "\r\n");
                $r = $getResponse($socket);
                if (strpos($r, '235') !== 0) { $log("Auth Failed: $r"); fclose($socket); return false; }
            }

            fwrite($socket, "MAIL FROM: <$user>\r\n");
            $getResponse($socket);
            fwrite($socket, "RCPT TO: <$to>\r\n");
            $getResponse($socket);
            fwrite($socket, "DATA\r\n");
            $r = $getResponse($socket);
            if (strpos($r, '354') !== 0) { $log("DATA Command Failed: $r"); fclose($socket); return false; }

            $headers  = "MIME-Version: 1.0\r\n";
            $headers .= "Content-type: text/html; charset=utf-8\r\n";
            $headers .= "To: <$to>\r\n";
            $headers .= "From: $siteName <$from>\r\n";
            $headers .= "Subject: $subject\r\n";
            $headers .= "Date: " . date('r') . "\r\n";
            $headers .= "X-Mailer: PHP/PixelVaultSMTP\r\n";

            fwrite($socket, $headers . "\r\n" . $message . "\r\n.\r\n");
            $r = $getResponse($socket);
            if (strpos($r, '250') !== 0) { $log("Message Send Failed: $r"); fclose($socket); return false; }

            fwrite($socket, "QUIT\r\n");
            fclose($socket);
            return true;

        } catch (\Exception $e) {
            $log("Exception: " . $e->getMessage());
            return false;
        }
    }

    private static function wrapInHtml(string $subject, string $body, string $cta, array $data): string
    {
        $primaryColor = site_config('primary_color', '#F97316');
        $siteName     = site_config('site_name', 'PixelVault');
        $ctaHtml = '';
        if ($cta && isset($data['action_url'])) {
            $ctaHtml = '<div style="padding: 30px 0; text-align: center;"><a href="' . $data['action_url'] . '" style="background:' . $primaryColor . '; color:#fff; padding:12px 30px; text-decoration:none; border-radius:8px; font-weight:bold; display:inline-block;">' . $cta . '</a></div>';
        }
        return '<!DOCTYPE html><html><body style="font-family:sans-serif; background:#f9f9f9; padding:20px;"><div style="max-width:600px; margin:0 auto; background:#fff; border-radius:12px; border:1px solid #eee; overflow:hidden;"><div style="background:#18181b; padding:30px; text-align:center; color:#fff;"><h2 style="margin:0;">' . $siteName . '</h2></div><div style="padding:40px;"><h1>' . $subject . '</h1><div style="line-height:1.6; color:#333;">' . nl2br($body) . '</div>' . $ctaHtml . '</div><div style="background:#f4f4f4; padding:20px; text-align:center; font-size:12px; color:#777;">&copy; ' . date('Y') . ' ' . $siteName . '</div></div></body></html>';
    }
}
