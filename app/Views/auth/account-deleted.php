<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Account Deleted</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') ?>/assets/css/app.css">
  <style>
    /* Account deleted page styles */
    .account-deleted-page {
      --deleted-bg: var(--mist);
      --deleted-card-bg: rgba(255,255,255,0.95);
      --deleted-border: rgba(43,42,37,0.12);
      --deleted-text: var(--earth);
      --deleted-accent: var(--leaf);
      --deleted-subtle: var(--text-muted);
      background: var(--deleted-bg);
      color: var(--deleted-text);
      font-family: 'DM Sans', sans-serif;
      line-height: 1.6;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0;
      padding: 0;
    }

    .deleted-modal {
      background: var(--deleted-card-bg);
      border-radius: 20px;
      padding: 3rem;
      box-shadow: 0 20px 60px rgba(43,42,37,0.15);
      border: 1px solid var(--deleted-border);
      text-align: center;
      max-width: 450px;
      width: 90%;
      position: relative;
      overflow: hidden;
    }

    .deleted-icon {
      width: 80px;
      height: 80px;
      margin: 0 auto 2rem;
      background: #dc3545;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 2.5rem;
      color: white;
      animation: scaleIn 0.5s ease-out;
    }

    @keyframes scaleIn {
      0% {
        transform: scale(0);
        opacity: 0;
      }
      50% {
        transform: scale(1.1);
      }
      100% {
        transform: scale(1);
        opacity: 1;
      }
    }

    .deleted-title {
      font-family: 'DM Serif Display', serif;
      font-size: 2rem;
      font-weight: 400;
      margin-bottom: 1rem;
      color: #dc3545;
      animation: slideUp 0.6s ease-out;
    }

    @keyframes slideUp {
      0% {
        transform: translateY(20px);
        opacity: 0;
      }
      100% {
        transform: translateY(0);
        opacity: 1;
      }
    }

    .deleted-message {
      font-size: 1.1rem;
      color: var(--deleted-subtle);
      margin-bottom: 2rem;
      animation: slideUp 0.7s ease-out;
    }

    .redirect-info {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 1rem;
      color: var(--deleted-subtle);
      font-size: 0.9rem;
      animation: slideUp 0.8s ease-out;
    }

    .countdown {
      font-weight: 600;
      color: var(--deleted-accent);
      font-size: 1.2rem;
      min-width: 30px;
      text-align: center;
    }

    .progress-bar {
      position: absolute;
      bottom: 0;
      left: 0;
      height: 4px;
      background: var(--deleted-accent);
      animation: progressBar 5s linear;
      transform-origin: left;
    }

    @keyframes progressBar {
      0% {
        transform: scaleX(1);
      }
      100% {
        transform: scaleX(0);
      }
    }

    .back-link {
      display: inline-block;
      margin-top: 2rem;
      padding: 12px 24px;
      background: var(--deleted-accent);
      color: white;
      text-decoration: none;
      border-radius: 8px;
      font-weight: 500;
      transition: background 0.3s;
      animation: slideUp 0.9s ease-out;
    }

    .back-link:hover {
      background: #3d6b3f;
    }

    /* Mobile Responsive */
    @media (max-width: 480px) {
      .deleted-modal {
        padding: 2rem;
        margin: 1rem;
      }

      .deleted-title {
        font-size: 1.5rem;
      }

      .deleted-icon {
        width: 60px;
        height: 60px;
        font-size: 2rem;
      }
    }

    /* Critical Breakpoint - Fix 531px/607px width issue */
    @media (max-width: 540px) {
      .deleted-modal {
        padding: 2.25rem;
        margin: 1.25rem;
      }

      .deleted-title {
        font-size: 1.625rem;
      }

      .deleted-icon {
        width: 65px;
        height: 65px;
        font-size: 2.125rem;
      }

      .deleted-message {
        font-size: 1rem;
      }

      .back-link {
        padding: 9px 18px;
        font-size: 0.875rem;
      }
    }

    /* Mobile phones - Small (Fixes 640px/607px issues) */
    @media (max-width: 640px) {
      .deleted-modal {
        padding: 2.5rem;
        margin: 1.5rem;
      }

      .deleted-title {
        font-size: 1.75rem;
      }

      .deleted-icon {
        width: 70px;
        height: 70px;
        font-size: 2.25rem;
      }

      .deleted-message {
        font-size: 1.05rem;
      }
    }

    /* Ultra small screens */
    @media (max-width: 360px) {
      .deleted-modal {
        padding: 1.5rem;
        margin: 1rem;
      }

      .deleted-title {
        font-size: 1.25rem;
      }

      .deleted-icon {
        width: 50px;
        height: 50px;
        font-size: 1.75rem;
      }

      .deleted-message {
        font-size: 0.95rem;
      }

      .back-link {
        padding: 10px 20px;
        font-size: 0.9rem;
      }
    }
  </style>
</head>
<body class="account-deleted-page">
  <div class="deleted-modal">
    <div class="progress-bar"></div>
    
    <div class="deleted-icon">?</div>
    
    <h1 class="deleted-title">Account Deleted</h1>
    
    <p class="deleted-message">
      <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
    </p>
    
    <p style="color: var(--deleted-subtle); margin-bottom: 2rem;">
      We're sorry to see you go. All your data has been permanently removed from our system.
    </p>
    
    <div class="redirect-info">
      <span>Redirecting to homepage in</span>
      <span class="countdown" id="countdown">5</span>
      <span>seconds...</span>
    </div>
    
    <a href="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') ?>/" class="back-link">
      Go to Homepage Now
    </a>
  </div>

  <script>
    let countdown = 5;
    const countdownElement = document.getElementById('countdown');

    function updateCountdown() {
      countdownElement.textContent = countdown;
      countdown--;
      
      if (countdown < 0) {
        window.location.href = '<?= htmlspecialchars(rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') . '/', ENT_QUOTES, 'UTF-8') ?>';
      }
    }

    // Start countdown
    updateCountdown();
    const countdownInterval = setInterval(() => {
      updateCountdown();
      if (countdown < 0) {
        clearInterval(countdownInterval);
      }
    }, 1000);

    // Allow manual redirect on click
    document.querySelector('.deleted-modal').addEventListener('click', function(e) {
      if (e.target.classList.contains('back-link')) {
        return; // Don't interfere with the back link
      }
      clearInterval(countdownInterval);
      window.location.href = '<?= htmlspecialchars(rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') . '/', ENT_QUOTES, 'UTF-8') ?>';
    });
  </script>
</body>
</html>
