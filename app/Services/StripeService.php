<?php
declare(strict_types=1);

namespace App\Services;

use App\Core\Config;
use App\Core\HttpClient;

final class StripeService
{
    private string $secretKey;
    private string $webhookSecret;

    public function __construct()
    {
        $this->secretKey = (string)Config::get('stripe.secret_key', '');
        $this->webhookSecret = (string)Config::get('stripe.webhook_secret', '');
    }

    public function isConfigured(): bool
    {
        return $this->secretKey !== '';
    }

    public function createCheckoutSession(array $payload): array
    {
        $res = HttpClient::postForm('https://api.stripe.com/v1/checkout/sessions', [
            'Authorization: Bearer ' . $this->secretKey,
            'Content-Type: application/x-www-form-urlencoded',
        ], $payload);

        $json = json_decode($res['body'], true);
        if (!is_array($json)) {
            $json = ['raw' => $res['body']];
        }

        return ['status' => $res['status'], 'data' => $json, 'error' => $res['error']];
    }

    public function verifyWebhookSignature(string $payload, string $signatureHeader): bool
    {
        if ($this->webhookSecret === '') {
            return false;
        }

        // Minimal Stripe signature verification:
        // header: t=timestamp,v1=signature
        $parts = explode(',', $signatureHeader);
        $timestamp = null;
        $sig = null;
        foreach ($parts as $p) {
            $p = trim($p);
            if (str_starts_with($p, 't=')) {
                $timestamp = substr($p, 2);
            } elseif (str_starts_with($p, 'v1=')) {
                $sig = substr($p, 3);
            }
        }

        if (!$timestamp || !$sig) {
            return false;
        }

        $signedPayload = $timestamp . '.' . $payload;
        $expected = hash_hmac('sha256', $signedPayload, $this->webhookSecret);
        return hash_equals($expected, $sig);
    }
}
