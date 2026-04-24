<?php
declare(strict_types=1);

namespace App\Routes;

use App\Core\Router;
use App\Core\Request;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\BrowseController;
use App\Controllers\AffiliateController;
use App\Controllers\ProductController;
use App\Controllers\ApiController;
use App\Controllers\CartController;
use App\Controllers\MessageController;
use App\Controllers\MessagesController;

final class WebRoutes
{
    public function register(Router $router): void
    {
        $container = $router->getContainer();

        $router->get('/', fn() => $container->get(HomeController::class)->index());
        $router->get('/browse', fn() => $container->get(BrowseController::class)->index());
        $router->get('/browse/{id}', fn(Request $req, array $params) => $container->get(BrowseController::class)->viewListing($req, $params));
        $router->get('/seller/{id}', fn(Request $req, array $params) => $container->get(BrowseController::class)->viewSellerProfile($req, $params));
        $router->get('/favorites', fn() => $container->get(BrowseController::class)->favorites())->middleware('auth');

        $router->get('/about', fn() => $container->get(AffiliateController::class)->about());
        $router->get('/marketplace-site', fn() => $container->get(AffiliateController::class)->marketplaceSite());
        $router->get('/services', fn() => $container->get(AffiliateController::class)->services());
        $router->get('/support', fn() => $container->get(AffiliateController::class)->support());
        $router->post('/support/submit', fn(Request $req) => $container->get(AffiliateController::class)->supportSubmit($req))->middleware('throttle-general');
        $router->get('/auth', fn() => $container->get(AffiliateController::class)->auth());

        $router->get('/register', fn() => $container->get(AuthController::class)->showRegister());
        $router->post('/register', fn(Request $req) => $container->get(AuthController::class)->register($req))->middleware('throttle-auth');

        $router->get('/login', fn() => $container->get(AuthController::class)->showLogin());
        $router->post('/login', fn(Request $req) => $container->get(AuthController::class)->login($req))->middleware('throttle-auth');

        $router->post('/logout', fn(Request $req) => $container->get(AuthController::class)->logout($req))->middleware('auth');
        $router->post('/delete-account', fn(Request $req) => $container->get(AuthController::class)->deleteAccount($req))->middleware('auth');
        $router->get('/profile', fn() => $container->get(AuthController::class)->profile())->middleware('auth');
        $router->post('/profile/update', fn(Request $req) => $container->get(AuthController::class)->updateProfile($req))->middleware('auth', 'throttle-general');
        
        // Admin routes - Protected with admin-only access
        $router->get('/admin', fn() => $container->get(AuthController::class)->showAdmin())->middleware('admin');
        $router->post('/admin/delete-all', fn(Request $req) => $container->get(AuthController::class)->massDeleteUsers($req))->middleware('admin');

        // Dashboard routes
        $router->get('/dashboard', fn() => $container->get(DashboardController::class)->index())->middleware('auth');
        $router->get('/messages', fn() => $container->get(MessagesController::class)->index())->middleware('seller');
        $router->post('/admin/view-user', fn(Request $req) => $container->get(DashboardController::class)->viewUser($req))->middleware('admin');
        $router->post('/admin/delete-user-no-auth', fn(Request $req) => $container->get(DashboardController::class)->deleteUserWithoutAuth($req))->middleware('admin');
        $router->post('/admin/cleanup-orphaned-listings', fn(Request $req) => $container->get(DashboardController::class)->cleanupOrphanedListings($req))->middleware('admin');
        $router->get('/admin/test', fn() => $container->get(DashboardController::class)->testEndpoint())->middleware('admin');
        $router->get('/admin/dashboard', fn() => $container->get(DashboardController::class)->adminIndex())->middleware('admin');
        $router->get('/admin/listings', fn() => $container->get(DashboardController::class)->adminListings())->middleware('admin');

        // Product routes
        $router->get('/create-listing', fn() => $container->get(ProductController::class)->showCreateListing())->middleware('seller');
        $router->post('/create-listing', fn(Request $req) => $container->get(ProductController::class)->createListing($req))->middleware('seller', 'throttle-general');
        $router->get('/listings', fn() => $container->get(ProductController::class)->showListings());
        $router->get('/my-listings', fn() => $container->get(ProductController::class)->showSellerListings())->middleware('seller');
        $router->get('/listings/edit/{id}', fn(Request $req, array $params) => $container->get(ProductController::class)->showEditListing($req, $params))->middleware('seller');
        $router->post('/listings/edit/{id}', fn(Request $req, array $params) => $container->get(ProductController::class)->updateListing($req, $params))->middleware('seller', 'throttle-general');
        $router->post('/listings/delete', fn(Request $req) => $container->get(ProductController::class)->deleteListing($req))->middleware('seller', 'throttle-general');
        
        // Listing lifecycle management
        $router->post('/listings/approve', fn(Request $req) => $container->get(ProductController::class)->approveListing($req))->middleware('admin', 'throttle-general');
        $router->post('/listings/reject', fn(Request $req) => $container->get(ProductController::class)->rejectListing($req))->middleware('admin', 'throttle-general');
        $router->post('/listings/archive', fn(Request $req) => $container->get(ProductController::class)->archiveListing($req))->middleware('admin', 'throttle-general');
        $router->get('/admin/pending-listings', fn() => $container->get(ProductController::class)->getPendingListings())->middleware('admin');
        $router->get('/admin/all-listings', fn() => $container->get(ProductController::class)->getAllListings())->middleware('admin');
        
        // API routes
        $router->get('/api/products', fn(Request $req) => $container->get(ApiController::class)->products($req))->middleware('throttle-api-guest');
        $router->get('/api/seller-products', fn(Request $req) => $container->get(ApiController::class)->sellerProducts($req))->middleware('seller', 'throttle-api');
        $router->post('/api/products', fn(Request $req) => $container->get(ProductController::class)->getProducts($req))->middleware('throttle-api-guest');

        // Favorites API routes
        $router->post('/api/favorites/add', fn(Request $req) => $container->get(ApiController::class)->addFavorite($req))->middleware('auth', 'throttle-api');
        $router->post('/api/favorites/remove', fn(Request $req) => $container->get(ApiController::class)->removeFavorite($req))->middleware('auth', 'throttle-api');
        $router->get('/api/favorites', fn(Request $req) => $container->get(ApiController::class)->getFavorites($req))->middleware('auth', 'throttle-api');

        // Cart API routes
        $router->post('/api/cart/add', fn(Request $req) => $container->get(CartController::class)->addItem($req))->middleware('auth', 'throttle-api');
        $router->get('/api/cart', fn() => $container->get(CartController::class)->getCart())->middleware('auth', 'throttle-api');
        $router->post('/api/cart/remove', fn(Request $req) => $container->get(CartController::class)->removeItem($req))->middleware('auth', 'throttle-api');
        $router->post('/api/cart/update-quantity', fn(Request $req) => $container->get(CartController::class)->updateQuantity($req))->middleware('auth', 'throttle-api');

        // Message API routes
        $router->post('/api/messages/conversation/create', fn(Request $req) => $container->get(MessageController::class)->createConversation($req))->middleware('auth', 'throttle-api');
        $router->post('/api/messages/conversation/by-seller', fn(Request $req) => $container->get(MessageController::class)->getConversationBySeller($req))->middleware('auth', 'throttle-api');
        $router->get('/api/messages/{id}', fn(Request $req, array $params) => $container->get(MessageController::class)->getMessages($req, $params))->middleware('auth', 'throttle-api');
        $router->post('/api/messages/send', fn(Request $req) => $container->get(MessageController::class)->sendMessage($req))->middleware('auth', 'throttle-api');
        $router->post('/api/messages/upload', fn(Request $req) => $container->get(MessageController::class)->uploadImage($req))->middleware('auth', 'throttle-upload');
        $router->get('/api/messages/unread-count', fn() => $container->get(MessageController::class)->getUnreadCount())->middleware('auth', 'throttle-api');
        $router->get('/api/messages/conversations', fn() => $container->get(MessageController::class)->getConversations())->middleware('auth', 'throttle-api');

        // Checkout route
        $router->get('/checkout', fn() => $container->get(CartController::class)->checkout())->middleware('auth');

    }
}
