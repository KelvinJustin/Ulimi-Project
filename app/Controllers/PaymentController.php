<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\View;
use App\Core\Csrf;
use App\Core\Auth;
use App\Core\Database;

final class PaymentController
{
    public function paymentCallback(Request $request): void
    {
        // Get transaction reference from query parameters
        $txRef = $request->input('tx_ref') ?? '';

        // TODO: Verify transaction with PayChangu API before providing value
        // TODO: Update order status in database
        // TODO: Clear cart cookie

        View::render('payment.success', [
            'title' => 'Payment Successful - Ulimi Marketplace',
            'tx_ref' => $txRef
        ]);
    }

    public function paymentReturn(Request $request): void
    {
        // Get transaction reference and status from query parameters
        $txRef = $request->input('tx_ref') ?? '';
        $status = $request->input('status') ?? 'failed';

        View::render('payment.failed', [
            'title' => 'Payment Failed - Ulimi Marketplace',
            'tx_ref' => $txRef,
            'status' => $status
        ]);
    }

    public function showStripeCheckout(Request $request, array $params): void
    {
        // Require authentication
        if (!Auth::check()) {
            Auth::redirectToLogin();
            return;
        }

        $orderId = (int)($params['orderId'] ?? 0);
        
        // TODO: Fetch actual order from database
        $order = [
            'id' => $orderId,
            'order_number' => 'ORD-' . str_pad((string)$orderId, 6, '0', STR_PAD_LEFT),
            'total_amount' => 100.00,
            'currency' => 'MWK'
        ];

        $stripeConfigured = !empty(getenv('STRIPE_SECRET_KEY')) && !empty(getenv('STRIPE_WEBHOOK_SECRET'));

        View::render('payments.stripe', [
            'title' => 'Stripe Checkout - Ulimi',
            'order' => $order,
            'stripeConfigured' => $stripeConfigured,
            'csrf' => Csrf::token()
        ]);
    }

    public function createStripeCheckoutSession(Request $request): void
    {
        // Validate CSRF token
        if (!Csrf::verify($request->input('_csrf'))) {
            http_response_code(419);
            echo 'Invalid CSRF token';
            return;
        }

        // Require authentication
        if (!Auth::check()) {
            Auth::redirectToLogin();
            return;
        }

        $orderId = (int)$request->input('order_id', 0);
        
        // TODO: Create actual Stripe checkout session
        // For now, redirect to success page
        $base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '/');
        header('Location: ' . $base . '/payments/stripe/success?session_id=demo_session_' . $orderId);
        exit;
    }

    public function stripeSuccess(Request $request): void
    {
        // Require authentication
        if (!Auth::check()) {
            Auth::redirectToLogin();
            return;
        }

        $sessionId = $request->input('session_id', '');
        
        View::render('payments.success', [
            'title' => 'Payment Successful - Ulimi',
            'sessionId' => $sessionId,
            'user' => Auth::user()
        ]);
    }

    public function stripeCancel(Request $request): void
    {
        // Require authentication
        if (!Auth::check()) {
            Auth::redirectToLogin();
            return;
        }

        View::render('payments.cancel', [
            'title' => 'Payment Cancelled - Ulimi',
            'user' => Auth::user()
        ]);
    }

    public function stripeWebhook(Request $request): void
    {
        // TODO: Implement actual Stripe webhook handling
        // For now, just return success response
        http_response_code(200);
        echo 'Webhook received';
    }
}
