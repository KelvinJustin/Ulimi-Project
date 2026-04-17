<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Pay with Stripe</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') ?>/assets/css/app.css" />
  <style>
    /* Payment page styles with Bootstrap-like responsive design */
    .payment-page {
      min-height: 100vh;
      background: linear-gradient(135deg, #f8f9fa 0%, #e8f5e8 100%);
      font-family: 'DM Sans', sans-serif;
      padding: 2rem 1rem;
    }
    
    .payment-container {
      max-width: 600px;
      margin: 0 auto;
      background: white;
      border-radius: 16px;
      box-shadow: 0 4px 24px rgba(43,42,37,0.1);
      overflow: hidden;
    }
    
    .payment-header {
      background: linear-gradient(135deg, #3D6B3F 0%, #4F8A52 100%);
      color: white;
      padding: 2rem;
      text-align: center;
    }
    
    .payment-header h1 {
      font-family: 'DM Serif Display', serif;
      font-size: 2rem;
      margin-bottom: 0.5rem;
    }
    
    .payment-header p {
      opacity: 0.9;
      font-size: 1rem;
    }
    
    .payment-body {
      padding: 2rem;
    }
    
    .order-info {
      background: #f8f9fa;
      border-radius: 12px;
      padding: 1.5rem;
      margin-bottom: 1.5rem;
    }
    
    .order-info .row {
      display: flex;
      justify-content: space-between;
      margin-bottom: 0.75rem;
    }
    
    .order-info .row:last-child {
      margin-bottom: 0;
    }
    
    .order-info .label {
      color: #6B6558;
    }
    
    .order-info .value {
      font-weight: 600;
      color: #2B2A25;
    }
    
    .order-info .total {
      font-size: 1.25rem;
      color: #3D6B3F;
    }
    
    .payment-form {
      margin-top: 1.5rem;
    }
    
    .btn-payment {
      width: 100%;
      padding: 1rem 2rem;
      background: #3D6B3F;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 1.1rem;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    
    .btn-payment:hover {
      background: #4F8A52;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(61,107,63,0.3);
    }
    
    .error-message {
      background: rgba(220,53,69,0.1);
      border: 1px solid rgba(220,53,69,0.2);
      color: #dc3545;
      padding: 1.5rem;
      border-radius: 12px;
      text-align: center;
    }
    
    .error-message code {
      background: rgba(0,0,0,0.1);
      padding: 0.25rem 0.5rem;
      border-radius: 4px;
      font-size: 0.9rem;
    }
    
    .payment-footer {
      text-align: center;
      padding: 1rem;
      color: #6B6558;
      font-size: 0.9rem;
    }
    
    .payment-footer a {
      color: #3D6B3F;
      text-decoration: none;
    }
    
    /* Bootstrap-like responsive breakpoints */
    /* Large tablets */
    @media (max-width: 1024px) {
      .payment-container {
        max-width: 500px;
      }
    }
    
    /* Tablets */
    @media (max-width: 768px) {
      .payment-page {
        padding: 1rem;
      }
      
      .payment-header {
        padding: 1.5rem;
      }
      
      .payment-header h1 {
        font-size: 1.75rem;
      }
      
      .payment-body {
        padding: 1.5rem;
      }
    }
    
    /* Critical Breakpoint - Fix 531px/607px width issue */
    @media (max-width: 540px) {
      .payment-page {
        padding: 0.875rem 0.625rem;
      }
      
      .payment-container {
        border-radius: 10px;
      }
      
      .payment-header {
        padding: 1.375rem;
      }
      
      .payment-header h1 {
        font-size: 1.625rem;
      }
      
      .payment-header p {
        font-size: 0.95rem;
      }
      
      .payment-body {
        padding: 1.125rem;
      }
      
      .order-info {
        padding: 1.125rem;
      }
      
      .order-info .row {
        gap: 0.1875rem;
      }
      
      .btn-payment {
        padding: 0.875rem 1.625rem;
        font-size: 0.925rem;
      }
    }
    
    /* Mobile phones - Small (Fixes 640px/607px issues) */
    @media (max-width: 640px) {
      .payment-container {
        border-radius: 12px;
      }
      
      .payment-header h1 {
        font-size: 1.5rem;
      }
      
      .payment-body {
        padding: 1rem;
      }
      
      .order-info {
        padding: 1rem;
      }
      
      .order-info .row {
        flex-direction: column;
        gap: 0.25rem;
      }
    }
    
    /* Mobile phones - Extra small */
    @media (max-width: 480px) {
      .payment-page {
        padding: 0.5rem;
      }
      
      .payment-header {
        padding: 1rem;
      }
      
      .payment-header h1 {
        font-size: 1.25rem;
      }
      
      .payment-header p {
        font-size: 0.9rem;
      }
      
      .payment-body {
        padding: 0.75rem;
      }
      
      .btn-payment {
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
      }
    }
    
    /* Ultra small screens */
    @media (max-width: 360px) {
      .payment-header h1 {
        font-size: 1.1rem;
      }
      
      .order-info .value {
        font-size: 0.95rem;
      }
      
      .order-info .total {
        font-size: 1.1rem;
      }
    }
  </style>
</head>
<body>
  <div class="payment-page">
    <div class="payment-container">
      <div class="payment-header">
        <h1><i class="fa fa-credit-card"></i> Checkout</h1>
        <p>Complete your payment securely</p>
      </div>
      
      <div class="payment-body">
        <div class="order-info">
          <div class="row">
            <span class="label">Order Number:</span>
            <span class="value"><?= htmlspecialchars($order['order_number'] ?? 'N/A', ENT_QUOTES, 'UTF-8') ?></span>
          </div>
          <div class="row">
            <span class="label">Amount:</span>
            <span class="value total"><?= htmlspecialchars($order['currency'] ?? 'MWK', ENT_QUOTES, 'UTF-8') ?> <?= number_format((float)($order['total_amount'] ?? 0), 2) ?></span>
          </div>
        </div>

        <?php if (empty($stripeConfigured)): ?>
          <div class="error-message">
            <p><i class="fa fa-exclamation-triangle"></i> Stripe is not configured.</p>
            <p>Set <code>STRIPE_SECRET_KEY</code> and <code>STRIPE_WEBHOOK_SECRET</code> environment variables.</p>
          </div>
        <?php else: ?>
          <form method="post" action="/payments/stripe/create" class="payment-form">
            <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf, ENT_QUOTES, 'UTF-8') ?>" />
            <input type="hidden" name="order_id" value="<?= (int)$order['id'] ?>" />
            <button class="btn-payment" type="submit">
              <i class="fa fa-lock"></i> Pay with Stripe
            </button>
          </form>
        <?php endif; ?>
      </div>
      
      <div class="payment-footer">
        <a href="/dashboard"><i class="fa fa-arrow-left"></i> Back to Dashboard</a>
      </div>
    </div>
  </div>
</body>
</html>
