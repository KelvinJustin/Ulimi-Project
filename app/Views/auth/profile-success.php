<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title ?? 'Profile Updated', ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') ?>/assets/css/app.css">
  <link rel="stylesheet" href="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') ?>/assets/css/output.css">
  <link rel="icon" type="image/svg+xml" href="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') ?>/favicon.svg">
  <link rel="alternate icon" href="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') ?>/favicon.ico">
  <style>
    .success-page {
      --success-bg: var(--mist);
      --success-card-bg: rgba(255,255,255,0.92);
      --success-border: rgba(43,42,37,0.12);
      --success-text: var(--earth);
      --success-accent: var(--leaf);
      background: var(--success-bg);
      color: var(--success-text);
      font-family: 'DM Sans', sans-serif;
      line-height: 1.6;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .success-container {
      max-width: 500px;
      padding: 2rem;
    }

    .success-card {
      background: var(--success-card-bg);
      border-radius: 16px;
      padding: 3rem 2rem;
      box-shadow: 0 8px 32px rgba(43,42,37,0.06);
      border: 1px solid var(--success-border);
      text-align: center;
    }

    .success-icon {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background: var(--success-accent);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 3rem;
      margin: 0 auto 1.5rem;
    }

    .success-card h1 {
      font-family: 'DM Serif Display', serif;
      font-size: 2rem;
      margin-bottom: 1rem;
      color: var(--success-text);
    }

    .success-card p {
      color: var(--text-muted);
      margin-bottom: 2rem;
    }

    .btn {
      padding: 14px 32px;
      border-radius: 8px;
      font-weight: 500;
      text-decoration: none;
      border: none;
      cursor: pointer;
      transition: all 0.3s;
      display: inline-block;
    }

    .btn-primary {
      background: var(--success-accent);
      color: white;
    }

    .btn-primary:hover {
      background: #3d6b3f;
    }
  </style>
</head>
<body class="success-page">
  <div class="success-container">
    <div class="success-card">
      <div class="success-icon">
        <i class="fa fa-check"></i>
      </div>
      <h1>Profile Updated Successfully</h1>
      <p>Your profile information has been updated.</p>
      <a href="<?= htmlspecialchars($redirect_url ?? '/dashboard', ENT_QUOTES, 'UTF-8') ?>" class="btn btn-primary">
        Return to Dashboard
      </a>
    </div>
  </div>

  <script>
    // Auto-redirect after 3 seconds
    setTimeout(function() {
      window.location.href = '<?= htmlspecialchars($redirect_url ?? '/dashboard', ENT_QUOTES, 'UTF-8') ?>';
    }, 3000);
  </script>
</body>
</html>
