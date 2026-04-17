<?php
declare(strict_types=1);

namespace App\Core;

final class Csrf
{
    public static function token(): string
    {
        if (!isset($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }
        return (string)$_SESSION['_csrf'];
    }

    public static function verify(?string $token): bool
    {
        $sessionToken = $_SESSION['_csrf'] ?? '';
        if (!is_string($sessionToken) || $sessionToken === '') {
            return false;
        }
        if (!is_string($token) || $token === '') {
            return false;
        }
        return hash_equals($sessionToken, $token);
    }
}
