<?php $base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '/'); ?>

<footer>
  <div class="container">
    <div class="footer-content">
      <div class="footer-brand">
        <span class="logo">
          <div class="logo-mark">
            <svg viewBox="0 0 20 20">
              <path d="M10 2C10 2 4 7 4 12a6 6 0 0012 0C16 7 10 2 10 2z"/>
            </svg>
          </div>
          Ulimi Admin Dashboard
        </span>
      </div>
      
      <div class="footer-links">
        <a href="<?= $base ?>/dashboard">Dashboard</a>
        <a href="<?= $base ?>/listings">Manage Listings</a>
        <a href="<?= $base ?>/browse">Browse Products</a>
        <a href="<?= $base ?>/support">Help Center</a>
      </div>
      
      <div class="footer-info">
        © 2026 Ulimi Agricultural Platform - Admin Panel v3.0
      </div>
    </div>
  </div>
</footer>
