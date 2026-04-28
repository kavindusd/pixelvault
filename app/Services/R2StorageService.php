<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Lightweight Cloudflare R2 / S3 Compatible Storage Service
 * Handles file uploads and Signed URL generation using SigV4 without external libraries.
 */
final class R2StorageService
{
    private string $accessKey;
    private string $secretKey;
    private string $region;
    private string $bucket;
    private string $endpoint;
    private bool $enabled;

    public function __construct()
    {
        $this->enabled   = (($_ENV['R2_ENABLED'] ?? getenv('R2_ENABLED')) === 'true');
        $this->accessKey = (string) ($_ENV['R2_ACCESS_KEY_ID'] ?? getenv('R2_ACCESS_KEY_ID') ?? '');
        $this->secretKey = (string) ($_ENV['R2_SECRET_ACCESS_KEY'] ?? getenv('R2_SECRET_ACCESS_KEY') ?? '');
        $this->bucket    = (string) ($_ENV['R2_BUCKET_NAME'] ?? getenv('R2_BUCKET_NAME') ?? '');
        $this->endpoint  = rtrim((string) ($_ENV['R2_ENDPOINT'] ?? getenv('R2_ENDPOINT') ?? ''), '/');
        $this->region    = 'auto';
    }

    private function log(string $msg): void
    {
        $logFile = BASE_PATH . '/storage/logs/r2_debug.log';
        file_put_contents($logFile, "[" . date('Y-m-d H:i:s') . "] $msg\n", FILE_APPEND);
    }

    public function isEnabled(): bool
    {
        return $this->enabled && $this->accessKey !== '' && $this->secretKey !== '';
    }

    /**
     * Upload a local file to R2
     */
    public function upload(string $localPath, string $r2Key): ?string
    {
        if (!$this->isEnabled()) {
            $this->log("Upload aborted: R2 is NOT enabled or missing keys.");
            return null;
        }
        
        if (!is_file($localPath)) {
            $this->log("Upload aborted: Local file not found: $localPath");
            return null;
        }

        $this->log("Starting upload: $localPath -> $r2Key");

        $content = file_get_contents($localPath);
        if ($content === false) {
            $this->log("Upload aborted: Could not read local file.");
            return null;
        }

        $host = parse_url($this->endpoint, PHP_URL_HOST);
        $url  = $this->endpoint . '/' . $this->bucket . '/' . ltrim($r2Key, '/');
        $date = gmdate('Ymd\THis\Z');
        $day  = gmdate('Ymd');

        $headers = [
            'Host' => $host,
            'x-amz-date' => $date,
            'Content-Type' => 'application/octet-stream',
        ];

        // SigV4 logic for PUT
        $canonicalHeaders = "host:$host\nx-amz-date:$date\n";
        $signedHeaders = "host;x-amz-date";
        $payloadHash = hash('sha256', $content);
        
        $canonicalRequest = "PUT\n/" . $this->bucket . '/' . ltrim($r2Key, '/') . "\n\n$canonicalHeaders\n$signedHeaders\n$payloadHash";
        $stringToSign = "AWS4-HMAC-SHA256\n$date\n$day/$this->region/s3/aws4_request\n" . hash('sha256', $canonicalRequest);
        
        $signature = $this->calculateSignature($stringToSign, $day);

        $authHeader = "AWS4-HMAC-SHA256 Credential=$this->accessKey/$day/$this->region/s3/aws4_request, SignedHeaders=$signedHeaders, Signature=$signature";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: $authHeader",
            "x-amz-date: $date",
            "Content-Type: application/octet-stream",
            "x-amz-content-sha256: $payloadHash"
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 300) {
            $this->log("Upload SUCCESS: r2://" . ltrim($r2Key, '/'));
            return "r2://" . ltrim($r2Key, '/');
        }

        $this->log("Upload FAILED (HTTP $httpCode): " . (string)$response);
        error_log("R2 Upload Failed ($httpCode): " . (string)$response);
        return null;
    }

    /**
     * Generate a pre-signed URL for a file on R2
     */
    public function getSignedUrl(string $r2Key, int $expiresSeconds = 3600): string
    {
        $r2Key = str_replace('r2://', '', $r2Key);
        $host = parse_url($this->endpoint, PHP_URL_HOST);
        $day = gmdate('Ymd');
        $date = gmdate('Ymd\THis\Z');
        
        $credential = "$this->accessKey/$day/$this->region/s3/aws4_request";
        
        $params = [
            'X-Amz-Algorithm' => 'AWS4-HMAC-SHA256',
            'X-Amz-Credential' => $credential,
            'X-Amz-Date' => $date,
            'X-Amz-Expires' => (string)$expiresSeconds,
            'X-Amz-SignedHeaders' => 'host',
        ];
        
        ksort($params);
        $query = http_build_query($params);
        
        $canonicalRequest = "GET\n/" . $this->bucket . '/' . ltrim($r2Key, '/') . "\n$query\nhost:$host\n\nhost\nUNSIGNED-PAYLOAD";
        $stringToSign = "AWS4-HMAC-SHA256\n$date\n$day/$this->region/s3/aws4_request\n" . hash('sha256', $canonicalRequest);
        
        $signature = $this->calculateSignature($stringToSign, $day);
        
        return $this->endpoint . '/' . $this->bucket . '/' . ltrim($r2Key, '/') . '?' . $query . '&X-Amz-Signature=' . $signature;
    }

    private function calculateSignature(string $stringToSign, string $day): string
    {
        $kDate    = hash_hmac('sha256', $day, "AWS4" . $this->secretKey, true);
        $kRegion  = hash_hmac('sha256', $this->region, $kDate, true);
        $kService = hash_hmac('sha256', 's3', $kRegion, true);
        $kSigning = hash_hmac('sha256', 'aws4_request', $kService, true);
        
        return hash_hmac('sha256', $stringToSign, $kSigning);
    }
}
