<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Payment Cancelled - Ulimi</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') ?>/assets/css/app.css" />
  <style>
    /* Payment cancelled page styles with Bootstrap-like responsive design */
    .payment-result-page {
      min-height: 100vh;
      background: linear-gradient(135deg, #fff5f5 0%, #fff 100%);
      font-family: 'DM Sans', sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem 1rem;
    }
    
    .result-container {
      max-width: 500px;
      background: white;
      border-radius: 16px;
      box-shadow: 0 4px 24px rgba(43,42,37,0.1);
      text-align: center;
      padding: 3rem 2rem;
    }
    
    .result-icon {
      width: 80px;
      height: 80px;
      background: linear-gradient(135deg, #dc3545, #e4606d);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 1.5rem;
      font-size: 2.5rem;
      color: white;
    }
    
    .result-title {
      font-family: 'DM Serif Display', serif;
      font-size: 2rem;
      color: #2B2A25;
      margin-bottom: 0.5rem;
    }
    
    .result-message {
      color: #6B6558;
      font-size: 1.1rem;
      margin-bottom: 1.5rem;
    }
    
    .btn-result {
      display: inline-block;
      padding: 12px 32px;
      background: #3D6B3F;
      color: white;
      text-decoration: none;
      border-radius: 8px;
      font-weight: 500;
      transition: all 0.3s ease;
    }
    
    .btn-result:hover {
      background: #4F8A52;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(61,107,63,0.3);
    }
    
    .btn-result-secondary {
      background: transparent;
      color: #6B6558;
      border: 1px solid #ddd;
      margin-left: 1rem;
    }
    
    .btn-result-secondary:hover {
      background: #f8f9fa;
      color: #2B2A25;
    }
    
    /* Bootstrap-like responsive breakpoints */
    /* Critical Breakpoint - Fix 531px/607px width issue */
    @media (max-width: 540px) {
      .result-container {
        padding: 1.875rem;
      }
      
      .result-icon {
        width: 70px;
        height: 70px;
        font-size: 2.125rem;
      }
      
      .result-title {
        font-size: 1.625rem;
      }
      
      .result-message {
        font-size: 1rem;
      }
      
      .btn-result {
        padding: 0.875rem 1.625rem;
        font-size: 0.925rem;
      }
    }

    @media (max-width: 768px) {
      .result-container {
        padding: 2rem 1.5rem;
      }
      
      .result-icon {
        width: 60px;
        height: 60px;
        font-size: 2rem;
      }
      
      .result-title {
        font-size: 1.5rem;
      }
      
      .result-message {
        font-size: 1rem;
      }
    }
    
    @media (max-width: 640px) {
      .result-container {
        padding: 1.5rem 1rem;
        border-radius: 12px;
      }
      
      .result-icon {
        width: 50px;
        height: 50px;
        font-size: 1.5rem;
      }
      
      .result-title {
        font-size: 1.25rem;
      }
      
      .btn-result {
        display: block;
        width: 100%;
        margin: 0.5rem 0;
      }
      
      .btn-result-secondary {
        margin-left: 0;
      }
    }
    
    @media (max-width: 480px) {
      .payment-result-page {
        padding: 1rem 0.5rem;
      }
      
      .result-container {
        padding: 1rem;
      }
    }
  </style>
</head>
<body>
  <div class="payment-result-page">
    <div class="result-container">
      <div class="result-icon">
        <i class="fa fa-times"></i>
      </div>
      <h1 class="result-title">Payment Cancelled</h1>
      <p class="result-message">You cancelled the payment. No charges were made.</p>
      
      <a href="/dashboard" class="btn-result">
        <i class="fa fa-home"></i> Back to Dashboard
      </a>
      <a href="/browse" class="btn-result btn-result-secondary">
        <i class="fa fa-shopping-basket"></i> Continue Shopping
      </a>
    </div>
  </div>
</body>
</html>
