<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Delete Account Error</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') ?>/assets/css/app.css">
  <style>
    /* Delete error page styles */
    .delete-error-page {
      --error-bg: var(--mist);
      --error-card-bg: rgba(255,255,255,0.95);
      --error-border: rgba(43,42,37,0.12);
      --error-text: var(--earth);
      --error-accent: var(--leaf);
      --error-subtle: var(--text-muted);
      background: var(--error-bg);
      color: var(--error-text);
      font-family: 'DM Sans', sans-serif;
      line-height: 1.6;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0;
      padding: 0;
    }

    .error-modal {
      background: var(--error-card-bg);
      border-radius: 20px;
      padding: 3rem;
      box-shadow: 0 20px 60px rgba(43,42,37,0.15);
      border: 1px solid var(--error-border);
      text-align: center;
      max-width: 450px;
      width: 90%;
      position: relative;
      overflow: hidden;
    }

    .error-icon {
      width: 80px;
      height: 80px;
      margin: 0 auto 2rem;
      background: #ffc107;
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

    .error-title {
      font-family: 'DM Serif Display', serif;
      font-size: 2rem;
      font-weight: 400;
      margin-bottom: 1rem;
      color: #ffc107;
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

    .error-message {
      font-size: 1.1rem;
      color: var(--error-subtle);
      margin-bottom: 2rem;
      animation: slideUp 0.7s ease-out;
    }

    .retry-btn {
      display: inline-block;
      margin-top: 2rem;
      padding: 12px 24px;
      background: #ffc107;
      color: #212529;
      text-decoration: none;
      border-radius: 8px;
      font-weight: 500;
      transition: background 0.3s;
      animation: slideUp 0.8s ease-out;
      margin-right: 1rem;
    }

    .retry-btn:hover {
      background: #e0a800;
    }

    .back-btn {
      display: inline-block;
      margin-top: 2rem;
      padding: 12px 24px;
      background: var(--error-accent);
      color: white;
      text-decoration: none;
      border-radius: 8px;
      font-weight: 500;
      transition: background 0.3s;
      animation: slideUp 0.9s ease-out;
    }

    .back-btn:hover {
      background: #3d6b3f;
    }

    /* Mobile Responsive */
    @media (max-width: 480px) {
      .error-modal {
        padding: 2rem;
        margin: 1rem;
      }

      .error-title {
        font-size: 1.5rem;
      }

      .error-icon {
        width: 60px;
        height: 60px;
        font-size: 2rem;
      }

      .retry-btn, .back-btn {
        display: block;
        margin: 0.5rem auto;
      }
    }

    /* Critical Breakpoint - Fix 531px/607px width issue */
    @media (max-width: 540px) {
      .error-modal {
        padding: 2.25rem;
        margin: 1.25rem;
      }

      .error-title {
        font-size: 1.625rem;
      }

      .error-icon {
        width: 65px;
        height: 65px;
        font-size: 2.125rem;
      }

      .error-message {
        font-size: 1rem;
      }

      .retry-btn, .back-btn {
        padding: 9px 18px;
        font-size: 0.875rem;
      }
    }

    /* Mobile phones - Small (Fixes 640px/607px issues) */
    @media (max-width: 640px) {
      .error-modal {
        padding: 2.5rem;
        margin: 1.5rem;
      }

      .error-title {
        font-size: 1.75rem;
      }

      .error-icon {
        width: 70px;
        height: 70px;
        font-size: 2.25rem;
      }

      .error-message {
        font-size: 1.05rem;
      }
    }

    /* Ultra small screens */
    @media (max-width: 360px) {
      .error-modal {
        padding: 1.5rem;
        margin: 1rem;
      }

      .error-title {
        font-size: 1.25rem;
      }

      .error-icon {
        width: 50px;
        height: 50px;
        font-size: 1.75rem;
      }

      .error-message {
        font-size: 0.95rem;
      }

      .retry-btn, .back-btn {
        padding: 10px 20px;
        font-size: 0.9rem;
      }
    }
  </style>
</head>
<body class="delete-error-page">
  <div class="error-modal">
    <div class="error-icon">!</div>
    
    <h1 class="error-title">Deletion Failed</h1>
    
    <p class="error-message">
      <?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?>
    </p>
    
    <p style="color: var(--error-subtle); margin-bottom: 2rem;">
      Please try again or contact support if the problem persists.
    </p>
    
    <a href="/dashboard/settings" class="retry-btn">Try Again</a>
    <a href="/dashboard" class="back-btn">Back to Dashboard</a>
  </div>
</body>
</html>
