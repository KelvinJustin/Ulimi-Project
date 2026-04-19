<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title ?? 'Profile Settings', ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') ?>/assets/css/app.css">
  <link rel="stylesheet" href="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') ?>/assets/css/output.css">
  <link rel="icon" type="image/svg+xml" href="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') ?>/favicon.svg">
  <link rel="alternate icon" href="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') ?>/favicon.ico">
  <style>
    .profile-page {
      --profile-bg: var(--mist);
      --profile-card-bg: rgba(255,255,255,0.92);
      --profile-border: rgba(43,42,37,0.12);
      --profile-text: var(--earth);
      --profile-accent: var(--leaf);
      --profile-subtle: var(--text-muted);
      background: var(--profile-bg);
      color: var(--profile-text);
      font-family: 'DM Sans', sans-serif;
      line-height: 1.6;
      min-height: 100vh;
    }

    .profile-container {
      max-width: 800px;
      margin: 0 auto;
      padding: 2rem;
    }

    .profile-header {
      text-align: center;
      margin-bottom: 3rem;
    }

    .profile-header h1 {
      font-family: 'DM Serif Display', serif;
      font-size: 2.5rem;
      margin-bottom: 1rem;
      color: var(--profile-text);
    }

    .profile-form {
      background: var(--profile-card-bg);
      border-radius: 16px;
      padding: 2.5rem;
      box-shadow: 0 8px 32px rgba(43,42,37,0.06);
      border: 1px solid var(--profile-border);
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 500;
      color: var(--profile-text);
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
      width: 100%;
      padding: 14px 18px;
      border: 1px solid var(--profile-border);
      border-radius: 8px;
      font-size: 1rem;
      background: white;
      outline: none;
      transition: border-color 0.3s;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
      border-color: var(--profile-accent);
    }

    .form-group textarea {
      resize: vertical;
      min-height: 120px;
    }

    .form-actions {
      display: flex;
      gap: 1rem;
      justify-content: flex-end;
      margin-top: 2rem;
    }

    .btn {
      padding: 14px 32px;
      border-radius: 8px;
      font-weight: 500;
      text-decoration: none;
      border: none;
      cursor: pointer;
      transition: all 0.3s;
    }

    .btn-primary {
      background: var(--profile-accent);
      color: white;
    }

    .btn-primary:hover {
      background: #3d6b3f;
    }

    .btn-secondary {
      background: transparent;
      color: var(--profile-text);
      border: 1px solid var(--profile-border);
    }

    .btn-secondary:hover {
      background: rgba(43,42,37,0.05);
    }

    .avatar-section {
      text-align: center;
      margin-bottom: 2rem;
    }

    .avatar {
      width: 120px;
      height: 120px;
      border-radius: 50%;
      background: var(--profile-accent);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 3rem;
      margin: 0 auto 1rem;
    }

    .info-text {
      font-size: 0.9rem;
      color: var(--profile-subtle);
      margin-top: 0.5rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .profile-container {
        padding: 1rem;
      }

      .profile-form {
        padding: 1.5rem;
      }

      .form-actions {
        flex-direction: column;
      }
    }

    /* Critical Breakpoint - Fix 531px/607px width issue */
    @media (max-width: 540px) {
      .profile-header {
        padding: 1.375rem 0;
      }

      .profile-header h1 {
        font-size: 1.875rem;
      }

      .profile-header p {
        font-size: 0.95rem;
      }

      .profile-form {
        padding: 1.125rem;
      }

      .form-group label {
        font-size: 0.875rem;
      }

      .form-group input {
        padding: 9px 13px;
        font-size: 0.925rem;
      }

      .form-actions {
        gap: 0.625rem;
      }

      .btn-primary,
      .btn-secondary {
        padding: 9px 18px;
        font-size: 0.875rem;
      }
    }

    /* Mobile phones - Small (Fixes 640px/607px issues) */
    @media (max-width: 640px) {
      .profile-header {
        padding: 1.5rem 0;
      }

      .profile-header h1 {
        font-size: 1.75rem;
      }

      .profile-header p {
        font-size: 1rem;
      }

      .profile-form {
        padding: 1.25rem;
      }

      .form-group label {
        font-size: 0.9rem;
      }

      .form-group input {
        padding: 10px 14px;
        font-size: 0.95rem;
      }
    }

    /* Mobile phones - Extra small */
    @media (max-width: 480px) {
      .profile-container {
        padding: 0.75rem;
      }

      .profile-header h1 {
        font-size: 1.5rem;
      }

      .profile-header p {
        font-size: 0.95rem;
      }

      .profile-form {
        padding: 1rem;
      }

      .form-group input {
        padding: 8px 12px;
        font-size: 0.9rem;
      }

      .btn-primary,
      .btn-secondary {
        padding: 10px 20px;
        font-size: 0.9rem;
        width: 100%;
      }
    }

    /* Ultra small screens */
    @media (max-width: 360px) {
      .profile-header h1 {
        font-size: 1.25rem;
      }

      .profile-form {
        padding: 0.75rem;
      }

      .btn-primary,
      .btn-secondary {
        padding: 8px 16px;
        font-size: 0.85rem;
      }
    }

    /* Footer integration */
    footer {
      margin-top: 0;
    }
  </style>
</head>
<body class="profile-page">
  <?php 
  $base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '/');
  ?>

  <div class="profile-container">
    <div class="profile-header">
      <h1>Profile Settings</h1>
      <p>Manage your account information and preferences</p>
    </div>

    <form class="profile-form" method="post" action="<?= $base ?>/profile/update">
      <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf ?? '', ENT_QUOTES, 'UTF-8') ?>">
      
      <div class="avatar-section">
        <div class="avatar">
          <?= htmlspecialchars(substr(ucfirst($user['display_name'] ?? $user['email']), 0, 1), ENT_QUOTES, 'UTF-8') ?>
        </div>
        <div class="info-text">Your profile avatar</div>
      </div>

      <div class="form-group">
        <label for="display_name">Display Name</label>
        <input type="text" id="display_name" name="display_name"
               value="<?= htmlspecialchars($old['display_name'] ?? ucfirst($user['display_name'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
               required>
        <div class="info-text">This is how your name will appear to other users</div>
        <?php if (!empty($errors['display_name'])): ?><div class="error" style="color: #dc2626; font-size: 0.9rem; margin-top: 0.5rem;"><?= htmlspecialchars($errors['display_name'], ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
      </div>

      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email"
               value="<?= htmlspecialchars($old['email'] ?? $user['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
               required>
        <div class="info-text">Your email address for notifications and login</div>
        <?php if (!empty($errors['email'])): ?><div class="error" style="color: #dc2626; font-size: 0.9rem; margin-top: 0.5rem;"><?= htmlspecialchars($errors['email'], ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
      </div>

      <div class="form-group">
        <label for="role">Account Type</label>
        <select id="role" name="role" disabled>
          <option value="buyer" <?= ($user['role'] ?? '') === 'buyer' ? 'selected' : '' ?>>Buyer</option>
          <option value="seller" <?= ($user['role'] ?? '') === 'seller' ? 'selected' : '' ?>>Seller</option>
          <option value="admin" <?= ($user['role'] ?? '') === 'admin' ? 'selected' : '' ?>>Admin</option>
        </select>
        <div class="info-text">Account type cannot be changed. Contact support if needed.</div>
      </div>

      <div class="form-group">
        <label for="bio">Bio</label>
        <textarea id="bio" name="bio" placeholder="Tell us about yourself..."><?= htmlspecialchars($old['bio'] ?? $user['bio'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
        <div class="info-text">Optional: Share information about yourself or your business</div>
      </div>

      <?php if (!empty($errors['general'])): ?>
        <div class="error" style="color: #dc2626; padding: 1rem; background: #fee2e2; border-radius: 8px; margin-bottom: 1rem;">
          <?= htmlspecialchars($errors['general'], ENT_QUOTES, 'UTF-8') ?>
        </div>
      <?php endif; ?>

      <div class="form-actions">
        <button type="button" class="btn btn-secondary" onclick="window.location.href='<?= $base ?>/dashboard'">
          Cancel
        </button>
        <button type="submit" class="btn btn-primary">
          Save Changes
        </button>
      </div>
    </form>
  </div>

  <?php require APP_PATH . '/Views/partials/footer-tailwind.php'; ?>

  <script>
    // Form validation
    document.querySelector('.profile-form').addEventListener('submit', function(e) {
      const displayName = document.getElementById('display_name').value.trim();
      const email = document.getElementById('email').value.trim();

      if (displayName.length < 3) {
        e.preventDefault();
        alert('Display name must be at least 3 characters.');
        return;
      }

      if (!email.includes('@') || !email.includes('.')) {
        e.preventDefault();
        alert('Please enter a valid email address.');
        return;
      }
    });
  </script>
</body>
</html>
