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
        $home = new HomeController();
        $auth = new AuthController();
        $dashboard = new DashboardController();
        $browse = new BrowseController();
        $product = new ProductController();
        $cart = new CartController();
        $message = new MessageController();
        $messages = new MessagesController();
        $affiliate = new AffiliateController();

        $router->get('/', fn() => $home->index());
        $router->get('/browse', fn() => $browse->index());
        $router->get('/browse/{id}', fn(Request $req, array $params) => $browse->viewListing($req, $params));
        $router->get('/seller/{id}', fn(Request $req, array $params) => $browse->viewSellerProfile($req, $params));
        $router->get('/favorites', fn() => $browse->favorites());

        $router->get('/about', fn() => $affiliate->about());
        $router->get('/marketplace-site', fn() => $affiliate->marketplaceSite());
        $router->get('/services', fn() => $affiliate->services());
        $router->get('/support', fn() => $affiliate->support());
        $router->post('/support/submit', fn(Request $req) => $affiliate->supportSubmit());
        $router->get('/auth', fn() => $affiliate->auth());

        $router->get('/register', fn() => $auth->showRegister());
        $router->post('/register', fn(Request $req) => $auth->register($req));

        $router->get('/login', fn() => $auth->showLogin());
        $router->post('/login', fn(Request $req) => $auth->login($req));

        $router->post('/logout', fn(Request $req) => $auth->logout($req));
        $router->post('/delete-account', fn(Request $req) => $auth->deleteAccount($req));
        $router->get('/profile', fn() => $auth->profile());
        $router->post('/profile/update', fn(Request $req) => $auth->updateProfile($req));
        
        // Admin routes - Protected with admin-only access
        $router->get('/admin', fn() => $auth->showAdmin());
        $router->post('/admin/delete-all', fn(Request $req) => $auth->massDeleteUsers($req));

        // Dashboard routes
        $router->get('/dashboard', fn() => $dashboard->index());
        $router->get('/messages', fn() => $messages->index());
        $router->get('/profile', fn() => $auth->profile());
        $router->post('/profile', fn(Request $req) => $auth->updateProfile());
        $router->post('/admin/view-user', fn(Request $req) => $dashboard->viewUser($req));
        $router->post('/admin/delete-user-no-auth', fn(Request $req) => $dashboard->deleteUserWithoutAuth($req));
        $router->post('/admin/cleanup-orphaned-listings', fn(Request $req) => $dashboard->cleanupOrphanedListings($req));
        $router->get('/admin/test', fn() => $dashboard->testEndpoint());
        $router->get('/admin/dashboard', fn() => $dashboard->adminIndex());
        $router->get('/admin/listings', fn() => $dashboard->adminListings());

        // Product routes
        $router->get('/create-listing', fn() => $product->showCreateListing());
        $router->post('/create-listing', fn(Request $req) => $product->createListing($req));
        $router->get('/listings', fn() => $product->showListings());
        $router->get('/my-listings', fn() => $product->showSellerListings());
        $router->get('/listings/edit/{id}', fn(Request $req, array $params) => $product->showEditListing($req, $params));
        $router->post('/listings/edit/{id}', fn(Request $req, array $params) => $product->updateListing($req, $params));
        $router->post('/listings/delete', fn(Request $req) => $product->deleteListing($req));
        
        // Listing lifecycle management
        $router->post('/listings/approve', fn(Request $req) => $product->approveListing($req));
        $router->post('/listings/reject', fn(Request $req) => $product->rejectListing($req));
        $router->post('/listings/archive', fn(Request $req) => $product->archiveListing($req));
        $router->get('/admin/pending-listings', fn() => $product->getPendingListings());
        $router->get('/admin/all-listings', fn() => $product->getAllListings());
        
        // API routes
        $api = new ApiController();
        $router->get('/api/products', fn(Request $req) => $api->products($req));
        $router->get('/api/seller-products', fn(Request $req) => $api->sellerProducts($req));
        $router->post('/api/products', fn(Request $req) => $product->getProducts($req));

        // Favorites API routes
        $router->post('/api/favorites/add', fn(Request $req) => $api->addFavorite($req));
        $router->post('/api/favorites/remove', fn(Request $req) => $api->removeFavorite($req));
        $router->get('/api/favorites', fn(Request $req) => $api->getFavorites($req));

        // Cart API routes
        $router->post('/api/cart/add', fn(Request $req) => $cart->addItem($req));
        $router->get('/api/cart', fn() => $cart->getCart());
        $router->post('/api/cart/remove', fn(Request $req) => $cart->removeItem($req));
        $router->post('/api/cart/update-quantity', fn(Request $req) => $cart->updateQuantity($req));

        // Message API routes
        $router->post('/api/messages/conversation/create', fn(Request $req) => $message->createConversation($req));
        $router->post('/api/messages/conversation/by-seller', fn(Request $req) => $message->getConversationBySeller($req));
        $router->get('/api/messages/{id}', fn(Request $req, array $params) => $message->getMessages($req, $params));
        $router->post('/api/messages/send', fn(Request $req) => $message->sendMessage($req));
        $router->post('/api/messages/upload', fn(Request $req) => $message->uploadImage($req));
        $router->get('/api/messages/unread-count', fn() => $message->getUnreadCount());
        $router->get('/api/messages/conversations', fn() => $message->getConversations());

        // Checkout route
        $router->get('/checkout', fn() => $cart->checkout());

    }
}
