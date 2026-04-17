<?php
$base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '/');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Listing Created Successfully - Ulimi Agricultural Marketplace</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?= $base ?>/assets/css/app.css">
  <link rel="icon" type="image/png" href="<?= $base ?>/logo.png">
  
  <style>
    .success-page {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      background: linear-gradient(135deg, #f8f9fa 0%, #e8f5e8 100%);
      padding: 2rem;
    }
    
    .success-container {
      max-width: 600px;
      width: 100%;
      text-align: center;
    }
    
    .success-icon {
      width: 120px;
      height: 120px;
      margin: 0 auto 2rem;
      background: #3d6b3f;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      animation: scaleIn 0.5s ease-out;
    }
    
    .success-icon i {
      color: white;
      font-size: 3rem;
    }
    
    .success-title {
      font-family: 'DM Serif Display', serif;
      font-size: 2.5rem;
      color: #3d6b3f;
      margin-bottom: 1rem;
      animation: fadeInUp 0.6s ease-out;
    }
    
    .success-message {
      font-size: 1.2rem;
      color: #666;
      margin-bottom: 2rem;
      line-height: 1.6;
      animation: fadeInUp 0.7s ease-out;
    }
    
    .listing-details {
      background: white;
      border-radius: 12px;
      padding: 2rem;
      margin-bottom: 2rem;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      text-align: left;
      animation: fadeInUp 0.8s ease-out;
    }
    
    .listing-details h3 {
      color: #3d6b3f;
      margin-bottom: 1rem;
      font-size: 1.3rem;
    }
    
    .detail-item {
      display: flex;
      justify-content: space-between;
      padding: 0.75rem 0;
      border-bottom: 1px solid #eee;
    }
    
    .detail-item:last-child {
      border-bottom: none;
    }
    
    .detail-label {
      font-weight: 600;
      color: #333;
    }
    
    .detail-value {
      color: #666;
    }
    
    .action-buttons {
      display: flex;
      gap: 1rem;
      justify-content: center;
      flex-wrap: wrap;
      animation: fadeInUp 0.9s ease-out;
    }
    
    .btn {
      padding: 0.75rem 2rem;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }
    
    .btn-primary {
      background: #3d6b3f;
      color: white;
    }
    
    .btn-primary:hover {
      background: #2d5a2f;
      transform: translateY(-2px);
    }
    
    .btn-secondary {
      background: #6c757d;
      color: white;
    }
    
    .btn-secondary:hover {
      background: #5a6268;
      transform: translateY(-2px);
    }
    
    @keyframes scaleIn {
      from {
        transform: scale(0);
        opacity: 0;
      }
      to {
        transform: scale(1);
        opacity: 1;
      }
    }
    
    @keyframes fadeInUp {
      from {
        transform: translateY(30px);
        opacity: 0;
      }
      to {
        transform: translateY(0);
        opacity: 1;
      }
    }
    
    .confetti {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: 1000;
    }
    
    .confetti-piece {
      position: absolute;
      width: 10px;
      height: 10px;
      background: #3d6b3f;
      animation: confettiFall 3s linear;
    }
    
    @keyframes confettiFall {
      0% {
        transform: translateY(-100vh) rotate(0deg);
        opacity: 1;
      }
      100% {
        transform: translateY(100vh) rotate(720deg);
        opacity: 0;
      }
    }

    /* Bootstrap-like responsive breakpoints */
    @media (max-width: 768px) {
      .success-page {
        padding: 1rem;
      }

      .success-icon {
        width: 100px;
        height: 100px;
      }

      .success-icon i {
        font-size: 2.5rem;
      }

      .success-title {
        font-size: 2rem;
      }

      .success-message {
        font-size: 1.1rem;
      }

      .listing-details {
        padding: 1.5rem;
      }

      .action-buttons {
        flex-direction: column;
        align-items: center;
      }

      .btn {
        width: 100%;
        max-width: 300px;
      }
    }

    /* Critical Breakpoint - Fix 531px/607px width issue */
    @media (max-width: 540px) {
      .success-page {
        padding: 0.625rem;
      }

      .success-container {
        padding: 2.25rem;
      }

      .success-icon {
        width: 70px;
        height: 70px;
      }

      .success-icon i {
        font-size: 2.125rem;
      }

      .success-title {
        font-size: 1.625rem;
      }

      .success-message {
        font-size: 1rem;
      }

      .listing-details {
        padding: 1.25rem;
      }

      .listing-details h3 {
        font-size: 1.15rem;
      }

      .detail-item {
        padding: 0.625rem 0;
      }

      .btn {
        padding: 9px 18px;
        font-size: 0.875rem;
      }
    }

    @media (max-width: 640px) {
      .success-page {
        padding: 0.75rem;
      }

      .success-icon {
        width: 80px;
        height: 80px;
      }

      .success-icon i {
        font-size: 2rem;
      }

      .success-title {
        font-size: 1.75rem;
      }

      .success-message {
        font-size: 1rem;
      }

      .listing-details {
        padding: 1.25rem;
      }

      .listing-details h3 {
        font-size: 1.2rem;
      }

      .detail-item {
        padding: 0.5rem 0;
        flex-direction: column;
        gap: 0.25rem;
      }
    }

    @media (max-width: 480px) {
      .success-title {
        font-size: 1.5rem;
      }

      .success-message {
        font-size: 0.95rem;
      }

      .listing-details {
        padding: 1rem;
      }

      .btn {
        padding: 0.6rem 1.5rem;
        font-size: 0.9rem;
      }
    }

    @media (max-width: 360px) {
      .success-icon {
        width: 60px;
        height: 60px;
      }

      .success-icon i {
        font-size: 1.5rem;
      }

      .success-title {
        font-size: 1.25rem;
      }

      .listing-details h3 {
        font-size: 1.1rem;
      }

      .btn {
        padding: 0.5rem 1.25rem;
        font-size: 0.85rem;
      }
    }
  </style>
</head>
<body>
  <div class="confetti" id="confetti"></div>
  
  <div class="success-page">
    <div class="success-container">
      <div class="success-icon">
        <i class="fa fa-check"></i>
      </div>
      
      <h1 class="success-title">Listing Created Successfully!</h1>
      
      <p class="success-message">
        Congratulations! Your agricultural product has been listed and is now awaiting admin approval before becoming visible to buyers.
      </p>
      
      <?php if (isset($listing)): ?>
      <div class="listing-details">
        <h3>Listing Details</h3>
        <div class="detail-item">
          <span class="detail-label">Product Title:</span>
          <span class="detail-value"><?= htmlspecialchars($listing['title'] ?? '') ?></span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Category:</span>
          <span class="detail-value"><?= htmlspecialchars($listing['category'] ?? '') ?></span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Price:</span>
          <span class="detail-value">MWK <?= number_format($listing['price'] ?? 0, 2) ?> per <?= htmlspecialchars($listing['price_unit'] ?? 'kg') ?></span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Available Quantity:</span>
          <span class="detail-value"><?= htmlspecialchars($listing['quantity'] ?? '') ?> <?= htmlspecialchars($listing['price_unit'] ?? 'kg') ?></span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Location:</span>
          <span class="detail-value"><?= htmlspecialchars($listing['location'] ?? '') ?></span>
        </div>
        <div class="detail-item">
          <span class="detail-label">Status:</span>
          <span class="detail-value" style="color: #ffc107; font-weight: 600;"><?= htmlspecialchars(ucfirst($listing['status'] ?? 'pending')) ?></span>
        </div>
      </div>
      <?php endif; ?>
      
      <div class="action-buttons">
        <a href="<?= $base ?>/dashboard" class="btn btn-primary">
          <i class="fa fa-tachometer"></i>
          Go to Dashboard
        </a>
        <a href="<?= $base ?>/create-listing" class="btn btn-secondary">
          <i class="fa fa-plus"></i>
          Create Another Listing
        </a>
        <a href="<?= $base ?>/browse" class="btn btn-secondary">
          <i class="fa fa-search"></i>
          Browse Products
        </a>
      </div>
    </div>
  </div>
  
  <script>
    // Create confetti effect
    function createConfetti() {
      const confettiContainer = document.getElementById('confetti');
      const colors = ['#3d6b3f', '#c8a84b', '#ffffff'];
      
      for (let i = 0; i < 50; i++) {
        setTimeout(() => {
          const confetti = document.createElement('div');
          confetti.className = 'confetti-piece';
          confetti.style.left = Math.random() * 100 + '%';
          confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
          confetti.style.animationDelay = Math.random() * 0.5 + 's';
          confetti.style.animationDuration = (Math.random() * 2 + 2) + 's';
          confettiContainer.appendChild(confetti);
          
          // Remove confetti after animation
          setTimeout(() => {
            confetti.remove();
          }, 4000);
        }, i * 30);
      }
    }
    
    // Start confetti when page loads
    document.addEventListener('DOMContentLoaded', createConfetti);
  </script>
</body>
</html>
