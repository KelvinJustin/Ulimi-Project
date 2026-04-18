<?php $base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '/'); ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<footer style="background: #1A1A16; color: rgba(255,255,255,0.6); padding: 64px 0 32px;">
  <div class="mx-auto max-w-7xl px-6 lg:px-8">
    <div class="grid grid-cols-1 gap-12 md:grid-cols-2 lg:grid-cols-4 lg:gap-12">
      <!-- Brand Section -->
      <div class="space-y-4">
        <a href="<?= $base ?>/" class="flex items-center gap-2" style="color: rgba(255,255,255,0.9); text-decoration: none;">
          <img src="/logo.png" alt="Ulimi Logo" class="w-8 h-8 rounded-lg object-cover">
          <span class="text-xl font-semibold">Ulimi</span>
        </a>
        <p style="font-size: 13px; line-height: 1.7; max-width: 260px;">Ulimi is building a platform that connects farms to markets and helps solve the challenges ahead in Malawi's agricultural supply chain.</p>
        <div class="flex gap-4">
          <a href="#" target="_blank" title="Facebook" style="width: 32px; height: 32px; border-radius: 50%; background: rgba(151,134,61,0.2); display: flex; align-items: center; justify-content: center; color: rgba(242,237,228,0.8); text-decoration: none; font-size: 13px; transition: 0.28s;">
            <i class="fa-brands fa-facebook"></i>
          </a>
          <a href="#" target="_blank" title="Twitter" style="width: 32px; height: 32px; border-radius: 50%; background: rgba(151,134,61,0.2); display: flex; align-items: center; justify-content: center; color: rgba(242,237,228,0.8); text-decoration: none; font-size: 13px; transition: 0.28s;">
            <i class="fa-brands fa-twitter"></i>
          </a>
          <a href="#" target="_blank" title="Instagram" style="width: 32px; height: 32px; border-radius: 50%; background: rgba(151,134,61,0.2); display: flex; align-items: center; justify-content: center; color: rgba(242,237,228,0.8); text-decoration: none; font-size: 13px; transition: 0.28s;">
            <i class="fa-brands fa-instagram"></i>
          </a>
          <a href="#" target="_blank" title="YouTube" style="width: 32px; height: 32px; border-radius: 50%; background: rgba(151,134,61,0.2); display: flex; align-items: center; justify-content: center; color: rgba(242,237,228,0.8); text-decoration: none; font-size: 13px; transition: 0.28s;">
            <i class="fa-brands fa-youtube"></i>
          </a>
          <a href="#" target="_blank" title="LinkedIn" style="width: 32px; height: 32px; border-radius: 50%; background: rgba(151,134,61,0.2); display: flex; align-items: center; justify-content: center; color: rgba(242,237,228,0.8); text-decoration: none; font-size: 13px; transition: 0.28s;">
            <i class="fa-brands fa-linkedin"></i>
          </a>
        </div>
      </div>

      <!-- Company Links -->
      <div>
        <h6 style="font-size: 13px; font-weight: 500; color: rgba(242,237,228,0.9); margin-bottom: 16px; text-transform: uppercase; letter-spacing: 0.8px;">Company</h6>
        <ul style="list-style: none; display: flex; flex-direction: column; gap: 10px;">
          <li><a href="<?= $base ?>/about" style="font-size: 13px; color: rgba(255,255,255,0.5); text-decoration: none; transition: 0.28s;">About Us</a></li>
          <li><a href="#" style="font-size: 13px; color: rgba(255,255,255,0.5); text-decoration: none; transition: 0.28s;">Investors</a></li>
          <li><a href="#" style="font-size: 13px; color: rgba(255,255,255,0.5); text-decoration: none; transition: 0.28s;">Careers</a></li>
          <li><a href="#" style="font-size: 13px; color: rgba(255,255,255,0.5); text-decoration: none; transition: 0.28s;">Blog</a></li>
        </ul>
      </div>

      <!-- Legal Links -->
      <div>
        <h6 style="font-size: 13px; font-weight: 500; color: rgba(242,237,228,0.9); margin-bottom: 16px; text-transform: uppercase; letter-spacing: 0.8px;">Legal</h6>
        <ul style="list-style: none; display: flex; flex-direction: column; gap: 10px;">
          <li><a href="#" style="font-size: 13px; color: rgba(242,237,228,0.6); text-decoration: none; transition: 0.28s;">Terms</a></li>
          <li><a href="#" style="font-size: 13px; color: rgba(242,237,228,0.6); text-decoration: none; transition: 0.28s;">Privacy Policy</a></li>
          <li><a href="#" style="font-size: 13px; color: rgba(242,237,228,0.6); text-decoration: none; transition: 0.28s;">Cookies Policy</a></li>
        </ul>
      </div>

      <!-- Support Links -->
      <div>
        <h6 style="font-size: 13px; font-weight: 500; color: rgba(242,237,228,0.9); margin-bottom: 16px; text-transform: uppercase; letter-spacing: 0.8px;">Support</h6>
        <ul style="list-style: none; display: flex; flex-direction: column; gap: 10px;">
          <li><a href="<?= $base ?>/support" style="font-size: 13px; color: rgba(242,237,228,0.6); text-decoration: none; transition: 0.28s;">Help & Support</a></li>
          <li><a href="<?= $base ?>/support#faq" style="font-size: 13px; color: rgba(242,237,228,0.6); text-decoration: none; transition: 0.28s;">FAQ</a></li>
          <li><a href="#" style="font-size: 13px; color: rgba(242,237,228,0.6); text-decoration: none; transition: 0.28s;">Report a Bug</a></li>
          <li><a href="#" style="font-size: 13px; color: rgba(242,237,228,0.6); text-decoration: none; transition: 0.28s;">Ideas & Suggestions</a></li>
        </ul>
      </div>
    </div>

    <!-- Footer Bottom -->
    <div style="margin-top: 48px; padding-top: 24px; border-top: 1px solid rgba(171,175,159,0.2); display: flex; align-items: center; justify-content: space-between; font-size: 12px; flex-wrap: wrap; gap: 16px;">
      <span style="color: rgba(242,237,228,0.6);">Ulimi © 2026 — Agricultural Platform</span>
      <div style="display: flex; gap: 20px;">
        <a href="#" style="color: rgba(242,237,228,0.4); text-decoration: none;">English</a>
        <a href="#" style="color: rgba(242,237,228,0.4); text-decoration: none;">Français</a>
        <a href="#" style="color: rgba(242,237,228,0.4); text-decoration: none;">Swahili</a>
      </div>
    </div>
  </div>
</footer>

<script src="<?= $base ?>/assets/js/app.js" defer></script>
