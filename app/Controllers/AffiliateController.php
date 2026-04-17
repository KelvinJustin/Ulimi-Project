<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Request;
use App\Core\View;
use App\Core\Csrf;

final class AffiliateController
{
    public function about(): void
    {
        View::render('affiliate.about', [
            'title' => 'About Ulimi'
        ]);
    }

    public function marketplaceSite(): void
    {
        View::render('affiliate.marketplace', [
            'title' => 'Ulimi Marketplace'
        ]);
    }

    public function services(): void
    {
        View::render('affiliate.services', [
            'title' => 'Services'
        ]);
    }

    public function support(): void
    {
        View::render('affiliate.support', [
            'title' => 'Customer Support',
            'csrf' => Csrf::token()
        ]);
    }

    public function supportSubmit(Request $request): void
    {
        // Validate CSRF token
        if (!Csrf::verify($request->input('_csrf'))) {
            http_response_code(419);
            echo 'Invalid CSRF token';
            return;
        }

        // TODO: Implement actual support ticket processing
        // For now, redirect back with success message
        $base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '/');
        header('Location: ' . $base . '/support?success=1');
        exit;
    }

    public function auth(): void
    {
        View::render('affiliate.auth', [
            'title' => 'Authentication'
        ]);
    }
}
