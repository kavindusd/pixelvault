<?php

declare(strict_types=1);

namespace App\Services;

final class JwtService
{
    /**
     * @param array<string, mixed> $claims
     */
    public function encode(array $claims): string
    {
        $config = $this->config();
        $header = ['alg' => 'HS256', 'typ' => 'JWT'];
        $now = time();
        $payload = array_merge([
            'iss' => $config['issuer'],
            'aud' => $config['audience'],
            'iat' => $now,
            'exp' => $now + (int) $config['ttl_seconds'],
        ], $claims);

        $headerSegment = $this->base64UrlEncode((string) json_encode($header, JSON_UNESCAPED_SLASHES));
        $payloadSegment = $this->base64UrlEncode((string) json_encode($payload, JSON_UNESCAPED_SLASHES));
        $signatureSegment = $this->base64UrlEncode(
            hash_hmac('sha256', $headerSegment . '.' . $payloadSegment, (string) $config['secret'], true)
        );

        return $headerSegment . '.' . $payloadSegment . '.' . $signatureSegment;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function decode(string $token): ?array
    {
        if ($token === '') {
            return null;
        }

        $segments = explode('.', $token);
        if (count($segments) !== 3) {
            return null;
        }

        [$headerSegment, $payloadSegment, $signatureSegment] = $segments;
        $config = $this->config();
        $expectedSignature = $this->base64UrlEncode(
            hash_hmac('sha256', $headerSegment . '.' . $payloadSegment, (string) $config['secret'], true)
        );

        if (!hash_equals($expectedSignature, $signatureSegment)) {
            return null;
        }

        $payloadJson = $this->base64UrlDecode($payloadSegment);
        if ($payloadJson === null) {
            return null;
        }

        $payload = json_decode($payloadJson, true);
        if (!is_array($payload)) {
            return null;
        }

        $now = time();
        if ((int) ($payload['exp'] ?? 0) < $now) {
            return null;
        }
        if (($payload['iss'] ?? null) !== $config['issuer']) {
            return null;
        }
        if (($payload['aud'] ?? null) !== $config['audience']) {
            return null;
        }

        return $payload;
    }

    public function cookieName(): string
    {
        return (string) $this->config()['cookie_name'];
    }

    /**
     * @return array<string, mixed>
     */
    private function config(): array
    {
        /** @var array<string, mixed> */
        return require BASE_PATH . '/config/jwt.php';
    }

    private function base64UrlEncode(string $value): string
    {
        return rtrim(strtr(base64_encode($value), '+/', '-_'), '=');
    }

    private function base64UrlDecode(string $value): ?string
    {
        $padding = strlen($value) % 4;
        if ($padding > 0) {
            $value .= str_repeat('=', 4 - $padding);
        }

        $decoded = base64_decode(strtr($value, '-_', '+/'), true);
        return $decoded === false ? null : $decoded;
    }
}
