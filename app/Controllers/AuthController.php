<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\View;
use App\Core\Csrf;
use App\Core\Auth;
use App\Core\FileUserStorage;
use App\Core\Validator;

final class AuthController
{
    public function showRegister(): void
    {
        View::render('auth.register', [
            'title' => 'Register - Ulimi Agricultural Marketplace',
            'csrf' => Csrf::token(),
            'errors' => [],
            'old' => []
        ]);
    }

    public function register(Request $request): void
    {
        // Validate CSRF token
        if (!Csrf::verify($request->input('_csrf'))) {
            http_response_code(419);
            echo 'Invalid CSRF token';
            return;
        }

        // Collect form data
        $role = $request->input('role', 'buyer');
        $displayName = trim($request->input('display_name', ''));
        $email = trim($request->input('email', ''));
        $password = $request->input('password', '');
        $phone = trim($request->input('phone', ''));

        $errors = [];

        // Validate inputs
        if (!Validator::str($displayName, 3, 120)) {
            $errors['display_name'] = 'Display name must be between 3 and 120 characters.';
        }

        if (!Validator::email($email)) {
            $errors['email'] = 'Please enter a valid email address.';
        }

        if (!Validator::str($password, 8, 255)) {
            $errors['password'] = 'Password must be at least 8 characters long.';
        }

        if (!in_array($role, ['buyer', 'seller'])) {
            $errors['role'] = 'Please select a valid role.';
        }

        // Check if email already exists
        if (FileUserStorage::findByEmail($email)) {
            $errors['email'] = 'Email already exists.';
        }

        if ($errors) {
            View::render('auth.register', [
                'title' => 'Register - Ulimi Agricultural Marketplace',
                'csrf' => Csrf::token(),
                'errors' => $errors,
                'old' => [
                    'role' => $role,
                    'display_name' => $displayName,
                    'email' => $email,
                    'phone' => $phone
                ]
            ]);
            return;
        }

        // Create user
        $success = FileUserStorage::createUser([
            'email' => $email,
            'password' => $password,
            'display_name' => $displayName,
            'role' => $role
        ]);

        if ($success) {
            // Log user in
            $user = FileUserStorage::findByEmail($email);
            if ($user) {
                Auth::login($user['id']);

                // Also create database user and profile for listing display
                $this->createDatabaseUser($user);

                // Set session flag for new user
                $_SESSION['just_registered'] = true;
            }

            $base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '/');
            
            // Redirect admins to admin dashboard, others to regular dashboard
            $redirectUrl = ($user && $user['role'] === 'admin') ? $base . '/admin/dashboard' : $base . '/dashboard';
            
            View::render('auth.register-success', [
                'title' => 'Registration Successful',
                'user' => $user,
                'redirect_url' => $redirectUrl
            ]);
        } else {
            View::render('auth.register', [
                'title' => 'Register - Ulimi Agricultural Marketplace',
                'csrf' => Csrf::token(),
                'errors' => ['general' => 'Registration failed. Please try again.'],
                'old' => [
                    'role' => $role,
                    'display_name' => $displayName,
                    'email' => $email,
                    'phone' => $phone
                ]
            ]);
        }
    }

    public function showLogin(): void
    {
        View::render('auth.login', [
            'title' => 'Login - Ulimi Agricultural Marketplace',
            'csrf' => Csrf::token(),
            'errors' => [],
            'old' => []
        ]);
    }

    public function login(Request $request): void
    {
        // Validate CSRF token
        if (!Csrf::verify($request->input('_csrf'))) {
            http_response_code(419);
            echo 'Invalid CSRF token';
            return;
        }

        $email = trim($request->input('email', ''));
        $password = $request->input('password', '');

        $errors = [];

        if (!Validator::email($email)) {
            $errors['email'] = 'Please enter a valid email address.';
        }

        if (empty($password)) {
            $errors['password'] = 'Please enter your password.';
        }

        if ($errors) {
            View::render('auth.login', [
                'title' => 'Login - Ulimi Agricultural Marketplace',
                'csrf' => Csrf::token(),
                'errors' => $errors,
                'old' => ['email' => $email]
            ]);
            return;
        }

        // Attempt login
        if (Auth::attempt($email, $password)) {
            $base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '/');
            $user = Auth::user();
            
            // Redirect admins to admin dashboard, others to regular dashboard
            $redirectUrl = ($user['role'] === 'admin') ? $base . '/admin/dashboard' : $base . '/dashboard';
            
            View::render('auth.login-success', [
                'title' => 'Login Successful',
                'user' => $user,
                'redirect_url' => $redirectUrl
            ]);
        } else {
            View::render('auth.login', [
                'title' => 'Login - Ulimi Agricultural Marketplace',
                'csrf' => Csrf::token(),
                'errors' => ['general' => 'Invalid email or password.'],
                'old' => ['email' => $email]
            ]);
        }
    }

    public function logout(Request $request): void
    {
        // Validate CSRF token if present
        $csrfToken = $request->input('_csrf');
        if ($csrfToken && !Csrf::verify($csrfToken)) {
            http_response_code(419);
            echo 'Invalid CSRF token';
            return;
        }

        Auth::logout();
        $base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '/');
        header('Location: ' . $base . '/login');
        exit;
    }

    public function deleteAccount(Request $request): void
    {
        // Validate CSRF token
        if (!Csrf::verify($request->input('_csrf'))) {
            http_response_code(419);
            echo 'Invalid CSRF token';
            return;
        }

        $user = Auth::user();
        if (!$user) {
            View::render('auth.delete-error', [
                'title' => 'Error - Account Not Found'
            ]);
            return;
        }

        // Prevent deletion of admin accounts
        if ($user['role'] === 'admin') {
            View::render('auth.delete-error', [
                'title' => 'Error - Cannot Delete Admin Account'
            ]);
            return;
        }

        // Delete account
        $success = FileUserStorage::deleteUser($user['id']);

        if ($success) {
            Auth::logout();
            View::render('auth.account-deleted', [
                'title' => 'Account Deleted Successfully'
            ]);
        } else {
            View::render('auth.delete-error', [
                'title' => 'Error - Account Deletion Failed'
            ]);
        }
    }

    public function showAdmin(): void
    {
        // Require admin role
        Auth::requireRole(['admin']);
        
        View::render('admin.terminal', [
            'title' => 'Admin Terminal - Ulimi',
            'users' => FileUserStorage::loadUsers(),
            'csrf' => Csrf::token()
        ]);
    }

    public function profile(): void
    {
        // Require authentication
        if (!Auth::check()) {
            $base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '/');
            header('Location: ' . $base . '/login');
            exit;
        }

        View::render('auth.profile', [
            'title' => 'Profile Settings - Ulimi',
            'user' => Auth::user(),
            'csrf' => Csrf::token()
        ]);
    }

    public function massDeleteUsers(Request $request): void
    {
        // Validate CSRF token
        if (!Csrf::verify($request->input('_csrf'))) {
            http_response_code(419);
            echo 'Invalid CSRF token';
            return;
        }

        // Require admin role
        Auth::requireRole(['admin']);

        $action = $request->input('action', '');
        
        if ($action === 'delete_all') {
            // Delete all non-admin users
            $allUsers = FileUserStorage::loadUsers();
            $deletedCount = 0;
            
            foreach ($allUsers as $user) {
                if ($user['role'] !== 'admin') {
                    if (FileUserStorage::deleteUser($user['id'])) {
                        $deletedCount++;
                    }
                }
            }
            
            $remainingUsers = FileUserStorage::loadUsers();
            $remainingCount = count(array_filter($remainingUsers, fn($u) => $u['role'] === 'admin'));
            
            View::render('admin.result', [
                'title' => 'Mass Delete Operation Complete',
                'deleted_count' => $deletedCount,
                'remaining_count' => $remainingCount,
                'error' => false
            ]);
        } else {
            // Individual user deletion
            $userIds = $request->input('user_ids', []);
            $deletedCount = 0;

            foreach ($userIds as $userId) {
                $user = FileUserStorage::findById((int)$userId);
                if ($user && $user['role'] !== 'admin') {
                    if (FileUserStorage::deleteUser((int)$userId)) {
                        $deletedCount++;
                    }
                }
            }
            
            View::render('admin.result', [
                'title' => 'User Deletion Complete',
                'deleted_count' => $deletedCount,
                'error' => false
            ]);
        }
    }

    private function createDatabaseUser(array $user): void
    {
        try {
            $pdo = \App\Core\Database::pdo();
            
            // Map role to role_id
            $roleMap = ['seller' => 1, 'buyer' => 2, 'admin' => 3];
            $roleId = $roleMap[$user['role']] ?? 2;
            
            // Check if user already exists in database
            $stmt = $pdo->prepare("SELECT id FROM users WHERE id = ?");
            $stmt->execute([$user['id']]);
            $existing = $stmt->fetch();
            
            if (!$existing) {
                // Create user in database
                $stmt = $pdo->prepare("
                    INSERT INTO users (id, role_id, email, password_hash, status, created_at, updated_at)
                    VALUES (?, ?, ?, ?, 'active', NOW(), NOW())
                ");
                $stmt->execute([
                    $user['id'],
                    $roleId,
                    $user['email'],
                    $user['password_hash'] ?? password_hash('default', PASSWORD_DEFAULT)
                ]);
            }
            
            // Check if profile exists
            $stmt = $pdo->prepare("SELECT user_id FROM user_profiles WHERE user_id = ?");
            $stmt->execute([$user['id']]);
            $profileExists = $stmt->fetch();
            
            if (!$profileExists) {
                // Create user profile
                $stmt = $pdo->prepare("
                    INSERT INTO user_profiles (user_id, display_name, rating_avg, rating_count, created_at, updated_at)
                    VALUES (?, ?, 0.00, 0, NOW(), NOW())
                ");
                $stmt->execute([
                    $user['id'],
                    $user['display_name'] ?? explode('@', $user['email'])[0]
                ]);
            }
        } catch (Exception $e) {
            error_log('Failed to create database user: ' . $e->getMessage());
        }
    }
}
