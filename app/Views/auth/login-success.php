<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Successful</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') ?>/assets/css/app.css">
  <style>
    /* Login success popup styles */
    .login-success-page {
      --success-bg: var(--mist);
      --success-card-bg: rgba(255,255,255,0.95);
      --success-border: rgba(43,42,37,0.12);
      --success-text: var(--earth);
      --success-accent: var(--leaf);
      --success-subtle: var(--text-muted);
      background: var(--success-bg);
      color: var(--success-text);
      font-family: 'DM Sans', sans-serif;
      line-height: 1.6;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0;
      padding: 0;
    }

    .success-modal {
      background: var(--success-card-bg);
      border-radius: 20px;
      padding: 3rem;
      box-shadow: 0 20px 60px rgba(43,42,37,0.15);
      border: 1px solid var(--success-border);
      text-align: center;
      max-width: 400px;
      width: 90%;
      position: relative;
      overflow: hidden;
    }

    .success-icon {
      width: 80px;
      height: 80px;
      margin: 0 auto 2rem;
      background: var(--success-accent);
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

    .success-title {
      font-family: 'DM Serif Display', serif;
      font-size: 2rem;
      font-weight: 400;
      margin-bottom: 1rem;
      color: var(--success-text);
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

    .success-message {
      font-size: 1.1rem;
      color: var(--success-subtle);
      margin-bottom: 1.5rem;
      animation: slideUp 0.7s ease-out;
    }

    .user-info {
      background: rgba(61,107,63,0.08);
      border-radius: 12px;
      padding: 1.5rem;
      margin-bottom: 2rem;
      animation: slideUp 0.8s ease-out;
    }

    .user-name {
      font-weight: 600;
      color: var(--success-text);
      margin-bottom: 0.5rem;
      font-size: 1.1rem;
    }

    .user-email {
      color: var(--success-subtle);
      font-size: 0.95rem;
    }

    .redirect-info {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 1rem;
      color: var(--success-subtle);
      font-size: 0.9rem;
      animation: slideUp 0.9s ease-out;
    }

    .countdown {
      font-weight: 600;
      color: var(--success-accent);
      font-size: 1.2rem;
      min-width: 30px;
      text-align: center;
    }

    .progress-bar {
      position: absolute;
      bottom: 0;
      left: 0;
      height: 4px;
      background: var(--success-accent);
      animation: progressBar 3s linear;
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

    .decorative-element {
      position: absolute;
      top: -50px;
      right: -50px;
      width: 100px;
      height: 100px;
      background: linear-gradient(135deg, rgba(61,107,63,0.1) 0%, rgba(200,168,75,0.08) 100%);
      border-radius: 50%;
      animation: float 3s ease-in-out infinite;
    }

    @keyframes float {
      0%, 100% {
        transform: translateY(0);
      }
      50% {
        transform: translateY(-10px);
      }
    }

    /* Mobile Responsive */
    @media (max-width: 480px) {
      .success-modal {
        padding: 2rem;
        margin: 1rem;
      }

      .success-title {
        font-size: 1.5rem;
      }

      .success-icon {
        width: 60px;
        height: 60px;
        font-size: 2rem;
      }
    }

    /* Critical Breakpoint - Fix 531px/607px width issue */
    @media (max-width: 540px) {
      .success-modal {
        padding: 2.25rem;
        margin: 1.25rem;
      }

      .success-title {
        font-size: 1.625rem;
      }

      .success-icon {
        width: 65px;
        height: 65px;
        font-size: 2.125rem;
      }

      .welcome-message {
        font-size: 1rem;
      }
    }

    /* Mobile phones - Small (Fixes 640px/607px issues) */
    @media (max-width: 640px) {
      .success-modal {
        padding: 2.5rem;
        margin: 1.5rem;
      }

      .success-title {
        font-size: 1.75rem;
      }

      .success-icon {
        width: 70px;
        height: 70px;
        font-size: 2.25rem;
      }

      .welcome-message {
        font-size: 1.05rem;
      }
    }

    /* Ultra small screens */
    @media (max-width: 360px) {
      .success-modal {
        padding: 1.5rem;
        margin: 1rem;
      }

      .success-title {
        font-size: 1.25rem;
      }

      .success-icon {
        width: 50px;
        height: 50px;
        font-size: 1.75rem;
      }

      .welcome-message {
        font-size: 0.95rem;
      }

      .btn-dashboard {
        padding: 10px 20px;
        font-size: 0.9rem;
      }
    }
  </style>
</head>
<body class="login-success-page">
  <div class="decorative-element"></div>
  
  <div class="success-modal">
    <div class="progress-bar"></div>
    
    <div class="success-icon"><?= htmlspecialchars(substr(ucfirst($user['display_name'] ?? $user['email']), 0, 1), ENT_QUOTES, 'UTF-8') ?></div>
    
    <h1 class="success-title">Login Successful!</h1>
    
    <p class="success-message">
      Welcome back to Ulimi. You're now logged in and ready to trade.
    </p>
    
    <div class="user-info">
      <div class="user-name"><?= htmlspecialchars(ucfirst($user['display_name'] ?? $user['email']), ENT_QUOTES, 'UTF-8') ?></div>
      <div class="user-email"><?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?></div>
    </div>
    
    <div class="redirect-info">
      <span>Redirecting to dashboard in</span>
      <span class="countdown" id="countdown">3</span>
      <span>seconds...</span>
    </div>
  </div>

  <script>
    let countdown = 3;
    const countdownElement = document.getElementById('countdown');
    const redirectUrl = <?= json_encode($redirect_url) ?>;

    function updateCountdown() {
      countdownElement.textContent = countdown;
      countdown--;
      
      if (countdown < 0) {
        window.location.href = redirectUrl;
      }
    }

    // Update countdown every second
    updateCountdown();
    const countdownInterval = setInterval(() => {
      updateCountdown();
      if (countdown < 0) {
        clearInterval(countdownInterval);
      }
    }, 1000);

    // Allow manual redirect on click
    document.querySelector('.success-modal').addEventListener('click', function() {
      clearInterval(countdownInterval);
      window.location.href = redirectUrl;
    });

    // Prevent accidental navigation
    window.addEventListener('beforeunload', function(e) {
      if (countdown >= 0) {
        e.preventDefault();
        e.returnValue = '';
      }
    });
  </script>
</body>
</html>
