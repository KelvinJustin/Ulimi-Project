<?php
declare(strict_types=1);

namespace App\Core;

use PDO;
use PDOException;

final class RateLimitStore
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::pdo();
    }

    /**
     * Get current rate limit data for an identifier and endpoint
     */
    public function get(string $identifier, string $endpoint): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM rate_limits
            WHERE identifier = ? AND endpoint = ?
            AND window_start > DATE_SUB(NOW(), INTERVAL 1 HOUR)
            ORDER BY window_start DESC
            LIMIT 1
        ");
        $stmt->execute([$identifier, $endpoint]);
        $result = $stmt->fetch();

        return $result ?: null;
    }

    /**
     * Increment request count for an identifier and endpoint
     */
    public function increment(string $identifier, string $endpoint, int $windowSeconds): int
    {
        $windowStart = date('Y-m-d H:i:s', time() - $windowSeconds);

        // Try to update existing record
        $stmt = $this->pdo->prepare("
            UPDATE rate_limits
            SET request_count = request_count + 1, updated_at = NOW()
            WHERE identifier = ? AND endpoint = ? AND window_start > ?
        ");
        $stmt->execute([$identifier, $endpoint, $windowStart]);

        if ($stmt->rowCount() > 0) {
            // Get updated count
            $stmt = $this->pdo->prepare("
                SELECT request_count FROM rate_limits
                WHERE identifier = ? AND endpoint = ?
                ORDER BY window_start DESC LIMIT 1
            ");
            $stmt->execute([$identifier, $endpoint]);
            return (int)$stmt->fetchColumn();
        }

        // Insert new record
        $stmt = $this->pdo->prepare("
            INSERT INTO rate_limits (identifier, endpoint, request_count, window_start)
            VALUES (?, ?, 1, NOW())
        ");
        $stmt->execute([$identifier, $endpoint]);

        return 1;
    }

    /**
     * Clean up old rate limit records
     */
    public function cleanup(): void
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM rate_limits
            WHERE window_start < DATE_SUB(NOW(), INTERVAL 1 HOUR)
        ");
        $stmt->execute();
    }

    /**
     * Get time until rate limit resets (in seconds)
     */
    public function getResetTime(string $identifier, string $endpoint, int $windowSeconds): int
    {
        $record = $this->get($identifier, $endpoint);
        
        if (!$record) {
            return 0;
        }

        $windowStart = strtotime($record['window_start']);
        $resetTime = $windowStart + $windowSeconds;
        $now = time();
        
        return max(0, $resetTime - $now);
    }
}
