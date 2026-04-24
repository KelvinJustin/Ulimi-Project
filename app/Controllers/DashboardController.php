<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\View;
use App\Core\Csrf;
use App\Core\Auth;
use App\Models\User;
use App\Models\Listing;

final class DashboardController
{
    private User $userModel;
    private Listing $listingModel;

    public function __construct(User $userModel = null, Listing $listingModel = null)
    {
        $this->userModel = $userModel ?? new User();
        $this->listingModel = $listingModel ?? new Listing();
    }

    public function index(): void
    {
        // Authentication and role checks now handled by 'auth' middleware

        // Prevent admins from accessing user dashboard
        if (Auth::isAdmin()) {
            http_response_code(403);
            echo 'Access denied - Admin users should use /admin/dashboard';
            exit;
        }

        $user = Auth::user();
        $userRole = $user['role'] ?? 'buyer';

        // Fetch listing count for sellers (pending and active only)
        $listingCount = 0;
        if ($userRole === 'seller') {
            try {
                $listings = $this->listingModel->search(['seller_id' => $user['id'], 'status' => ['pending', 'active']]);
                $listingCount = count($listings);
            } catch (Exception $e) {
                error_log('Failed to fetch listing count: ' . $e->getMessage());
                $listingCount = 0;
            }
        }

        // Fetch favorites count for buyers
        $favoritesCount = 0;
        if ($userRole === 'buyer') {
            try {
                $pdo = \App\Core\Database::pdo();
                $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM favorites WHERE user_id = ?");
                $stmt->execute([$user['id']]);
                $result = $stmt->fetch();
                $favoritesCount = $result['count'] ?? 0;
            } catch (Exception $e) {
                error_log('Failed to fetch favorites count: ' . $e->getMessage());
                $favoritesCount = 0;
            }
        }

        // Generate dynamic greeting
        $greeting = '';
        $userName = ucfirst($user['display_name'] ?? $user['email']);

        // Check if user just registered
        $justRegistered = $_SESSION['just_registered'] ?? false;
        if ($justRegistered) {
            $greeting = "Welcome to Ulimi, {$userName}!";
            // Clear the flag after first display
            unset($_SESSION['just_registered']);
        } else {
            // Time-based greeting
            $hour = (int)date('H');
            if ($hour >= 5 && $hour < 12) {
                $greeting = "Good morning, {$userName}";
            } elseif ($hour >= 12 && $hour < 17) {
                $greeting = "Good afternoon, {$userName}";
            } else {
                $greeting = "Good evening, {$userName}";
            }
        }

        View::render('dashboard/user', [
            'title' => 'Dashboard - Ulimi Agricultural Marketplace',
            'userRole' => $userRole,
            'listingCount' => $listingCount,
            'favoritesCount' => $favoritesCount,
            'greeting' => $greeting
        ]);
    }

    public function adminIndex(): void
    {
        // Authentication and role checks now handled by 'admin' middleware

        // Fetch total listings count
        $totalListings = 0;
        try {
            $listings = $this->listingModel->search([]);
            $totalListings = count($listings);
        } catch (Exception $e) {
            error_log('Failed to fetch total listings count: ' . $e->getMessage());
            $totalListings = 0;
        }

        // Fetch all users from database
        $users = $this->userModel->getAll();

        // Render the admin dashboard view
        View::render('dashboard/index', [
            'title' => 'Admin Dashboard - Ulimi Agricultural Marketplace',
            'userRole' => 'admin',
            'totalListings' => $totalListings,
            'users' => $users,
            'base' => rtrim((string)\App\Core\Config::get('app.base_url', ''), '/')
        ]);
    }

    public function adminListings(): void
    {
        // Authentication and role checks now handled by 'admin' middleware

        View::render('admin/listings', [
            'title' => 'Pending Listings - Admin - Ulimi',
            'csrf' => Csrf::token(),
        ]);
    }

    public function deleteUser(Request $request): void
    {
        // Suppress all errors and warnings
        error_reporting(0);
        ini_set('display_errors', '0');
        
        // Use output buffering to catch any HTML output
        ob_start();
        
        header('Content-Type: application/json');

        error_log('deleteUser called');

        // CSRF validation is now handled by CsrfMiddleware

        // Admin role check now handled by 'admin' middleware

        $userId = (int)$request->input('user_id', 0);
        error_log('Deleting user ID: ' . $userId);
        
        if ($userId <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
            ob_end_clean();
            return;
        }

        // Prevent admin from deleting themselves
        $currentUser = Auth::user();
        if ($userId === $currentUser['id']) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Cannot delete your own account']);
            ob_end_clean();
            return;
        }

        // Use User model to delete user
        $success = $this->userModel->delete($userId);
        error_log('Delete result: ' . ($success ? 'success' : 'failed'));
        
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete user']);
        }
        
        ob_end_clean();
    }

    public function viewUser(Request $request): void
    {
        // Suppress all errors and warnings
        error_reporting(0);
        ini_set('display_errors', '0');
        
        // Use output buffering to catch any HTML output
        ob_start();
        
        header('Content-Type: application/json');

        error_log('viewUser called');

        $userId = (int)$request->input('user_id', 0);
        error_log('Viewing user ID: ' . $userId);
        
        if ($userId <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
            ob_end_clean();
            return;
        }

        // Use User model to fetch user
        $user = $this->userModel->findById($userId);
        
        if (!$user) {
            error_log('User not found: ' . $userId);
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'User not found']);
            ob_end_clean();
            return;
        }

        // Fetch user's listings count from database
        $listingCount = 0;
        try {
            $pdo = \App\Core\Database::pdo();
            $stmt = $pdo->prepare("SELECT COUNT(*) as listing_count FROM commodity_listings WHERE seller_id = ?");
            $stmt->execute([$userId]);
            $listingCount = $stmt->fetch(PDO::FETCH_ASSOC)['listing_count'];
        } catch (Exception $e) {
            $listingCount = 0;
        }

        error_log('User details fetched successfully');
        echo json_encode([
            'success' => true,
            'user' => $user,
            'listing_count' => $listingCount
        ]);
        
        ob_end_clean();
    }

    public function testEndpoint(): void
    {
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Test endpoint works']);
    }

    public function deleteUserWithoutAuth(Request $request): void
    {
        header('Content-Type: application/json');

        $userId = (int)$request->input('user_id', 0);
        
        if ($userId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
            return;
        }

        $success = $this->userModel->delete($userId);
        
        if ($success) {
            echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete user']);
        }
    }

    public function cleanupOrphanedListings(Request $request): void
    {
        header('Content-Type: application/json');

        // Admin role check now handled by 'admin' middleware

        // CSRF validation is now handled by CsrfMiddleware

        try {
            $deletedCount = $this->listingModel->cleanupOrphanedListings();
            echo json_encode([
                'success' => true,
                'message' => "Cleaned up {$deletedCount} orphaned listings",
                'deleted_count' => $deletedCount
            ]);
        } catch (Exception $e) {
            error_log('Failed to cleanup orphaned listings: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Failed to cleanup orphaned listings']);
        }
    }
}
