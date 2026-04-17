<?php
declare(strict_types=1);

namespace App\Models;

use PDO;

final class Payment extends Model
{
    public function createInitiated(int $orderId, string $provider, string $method, float $amount, string $currency, ?string $providerReference = null, ?array $rawPayload = null): int
    {
        $stmt = $this->db->prepare('INSERT INTO payments (order_id, provider, method, provider_reference, amount, currency, status, raw_payload) VALUES (:order_id, :provider, :method, :provider_reference, :amount, :currency, :status, :raw_payload)');
        $stmt->execute([
            'order_id' => $orderId,
            'provider' => $provider,
            'method' => $method,
            'provider_reference' => $providerReference,
            'amount' => $amount,
            'currency' => $currency,
            'status' => 'initiated',
            'raw_payload' => $rawPayload ? json_encode($rawPayload) : null,
        ]);

        return (int)$this->db->lastInsertId();
    }

    public function markVerifiedByProviderReference(string $provider, string $providerReference, ?array $rawPayload = null): void
    {
        $stmt = $this->db->prepare('UPDATE payments SET status = :status, raw_payload = COALESCE(:raw_payload, raw_payload) WHERE provider = :provider AND provider_reference = :ref');
        $stmt->execute([
            'status' => 'verified',
            'raw_payload' => $rawPayload ? json_encode($rawPayload) : null,
            'provider' => $provider,
            'ref' => $providerReference,
        ]);
    }

    public function findByProviderReference(string $provider, string $providerReference): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM payments WHERE provider = :provider AND provider_reference = :ref LIMIT 1');
        $stmt->execute(['provider' => $provider, 'ref' => $providerReference]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
}
