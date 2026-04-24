<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\View;
use App\Core\Csrf;
use App\Core\Auth;
use App\Models\User;
use App\Core\Validator;

final class AuthController
{
    private User $userModel;

    public function __construct(User $userModel = null)
    {
        $this->userModel = $userModel ?? new User();
    }

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
        if ($this->userModel->findByEmail($email)) {
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
        $userId = $userModel->create([
            'email' => $email,
            'password' => $password,
            'display_name' => $displayName,
            'role' => $role
        ]);

        if ($userId) {
            // Log user in
            Auth::login($userId);

            // Set session flag for new user
            $_SESSION['just_registered'] = true;

            $base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '/');
            
            // Redirect admins to admin dashboard, others to regular dashboard
            $user = $userModel->findById($userId);
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
        $success = $this->userModel->delete($user['id']);

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
}
