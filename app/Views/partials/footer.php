<?php $base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '/'); ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<footer>
  <div class="container">
    <div class="footer-grid">
      <div class="footer-brand">
        <a href="<?= $base ?>/" class="logo" style="color:rgba(255,255,255,0.9);text-decoration:none;">
          <div class="logo-mark"><svg viewBox="0 0 20 20"><path d="M10 2C10 2 4 7 4 12a6 6 0 0012 0C16 7 10 2 10 2z"/></svg></div>
          Ulimi
        </a>
        <p>Ulimi is building a platform that connects farms to markets and helps solve the challenges ahead in Malawi's agricultural supply chain.</p>
        <div class="footer-socials" style="margin-top:20px;">
          <a href="#" target="_blank" title="Facebook"><i class="fa-brands fa-facebook"></i></a>
          <a href="#" target="_blank" title="Twitter"><i class="fa-brands fa-twitter"></i></a>
          <a href="#" target="_blank" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
          <a href="#" target="_blank" title="YouTube"><i class="fa-brands fa-youtube"></i></a>
          <a href="#" target="_blank" title="LinkedIn"><i class="fa-brands fa-linkedin"></i></a>
        </div>
      </div>
      <div class="footer-col">
        <h6>Company</h6>
        <ul>
          <li><a href="<?= $base ?>/about">About Us</a></li>
          <li><a href="#">Investors</a></li>
          <li><a href="#">Careers</a></li>
          <li><a href="#">Blog</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h6>Legal</h6>
        <ul>
          <li><a href="#">Terms</a></li>
          <li><a href="#">Privacy Policy</a></li>
          <li><a href="#">Cookies Policy</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h6>Support</h6>
        <ul>
          <li><a href="<?= $base ?>/support">Help & Support</a></li>
          <li><a href="<?= $base ?>/support#faq">FAQ</a></li>
          <li><a href="#">Report a Bug</a></li>
          <li><a href="#">Ideas & Suggestions</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom">
      <span>Ulimi © 2026 - Agricultural Platform</span>
      <div style="display:flex;gap:20px;">
        <a href="#" style="color:rgba(255,255,255,0.4);text-decoration:none;font-size:12px;">English</a>
        <a href="#" style="color:rgba(255,255,255,0.4);text-decoration:none;font-size:12px;">Français</a>
        <a href="#" style="color:rgba(255,255,255,0.4);text-decoration:none;font-size:12px;">Swahili</a>
      </div>
    </div>
  </div>
</footer>

<script src="<?= $base ?>/assets/js/app.js" defer></script>
