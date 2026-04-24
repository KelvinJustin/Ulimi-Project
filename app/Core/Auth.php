<?php
declare(strict_types=1);

namespace App\Core;

use App\Models\User;

final class Auth
{
    public static function user(): ?array
    {
        // Ensure session is started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $id = $_SESSION['user_id'] ?? null;
        if (!$id) {
            return null;
        }

        $userModel = new \App\Models\User();
        $user = $userModel->findById((int)$id);
        
        // Add role string for backward compatibility
        if ($user) {
            $user['role'] = self::roleIdToRole($user['role_id']);
        }
        
        return $user;
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function id(): ?int
    {
        return isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;
    }

    public static function role(): ?string
    {
        $user = self::user();
        return $user['role'] ?? null;
    }

    public static function login(int $userId): void
    {
        // Ensure session is started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        session_regenerate_id(true);
        $_SESSION['user_id'] = $userId;
    }

    public static function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], (bool)$params['secure'], (bool)$params['httponly']);
        }
        session_destroy();
    }

    public static function requireRole(array $roles): void
    {
        if (!self::check()) {
            http_response_code(302);
            header('Location: /login');
            exit;
        }

        $user = self::user();
        if (!$user || !in_array($user['role'], $roles)) {
            http_response_code(403);
            if (!headers_sent()) {
                View::render('auth.access-denied', [
                    'title' => 'Access Denied - Ulimi Agricultural Marketplace'
                ]);
            }
            exit;
        }
    }

    public static function attempt(string $email, string $password): bool
    {
        $userModel = new \App\Models\User();
        $user = $userModel->verifyCredentials($email, $password);
        if ($user) {
            self::login($user['id']);
            return true;
        }
        return false;
    }

    public static function isSeller(): bool
    {
        $user = self::user();
        return $user && $user['role'] === 'seller';
    }

    public static function isBuyer(): bool
    {
        $user = self::user();
        return $user && $user['role'] === 'buyer';
    }

    public static function isAdmin(): bool
    {
        $user = self::user();
        return $user && $user['role'] === 'admin';
    }

    public static function requireSeller(): void
    {
        if (!self::check()) {
            self::redirectToLogin();
            return;
        }

        if (!self::isSeller()) {
            http_response_code(403);
            if (!headers_sent()) {
                View::render('auth.access-denied', [
                    'title' => 'Access Denied - Ulimi Agricultural Marketplace'
                ]);
            }
            exit;
        }
    }

    public static function requireAdmin(): void
    {
        if (!self::check()) {
            self::redirectToLogin();
            return;
        }

        if (!self::isAdmin()) {
            http_response_code(403);
            if (!headers_sent()) {
                // Show proper access denied page
                View::render('auth.access-denied', [
                    'title' => 'Access Denied - Ulimi Agricultural Marketplace'
                ]);
            }
            exit;
        }
    }

    public static function redirectToLogin(): void
    {
        http_response_code(302);
        if (!headers_sent()) {
            $base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '/');
            // Ensure base_url is a relative path or same-origin to prevent open redirects
            if (parse_url($base, PHP_URL_HOST) && parse_url($base, PHP_URL_HOST) !== parse_url($_SERVER['HTTP_HOST'] ?? '', PHP_URL_HOST)) {
                $base = '';
            }
            header('Location: ' . $base . '/login');
        }
        exit;
    }

    public static function requireBuyer(): void
    {
        if (!self::check()) {
            self::redirectToLogin();
            return;
        }

        if (!self::isBuyer()) {
            http_response_code(403);
            if (!headers_sent()) {
                View::render('auth.access-denied', [
                    'title' => 'Access Denied - Ulimi Agricultural Marketplace'
                ]);
            }
            exit;
        }
    }

    private static function roleIdToRole(int $roleId): string
    {
        $roleMap = [
            1 => 'seller',
            2 => 'buyer',
            3 => 'admin'
        ];
        return $roleMap[$roleId] ?? 'buyer';
    }
}
