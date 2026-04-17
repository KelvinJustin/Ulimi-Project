<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title ?? 'Customer Support', ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') ?>/assets/css/app.css">
  <link rel="stylesheet" href="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') ?>/assets/css/output.css">
  <link rel="icon" type="image/png" href="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') ?>/logo.png">
  <style>
    /* Scroll animations */
    .scroll-animate {
      opacity: 1;
      transform: translateY(0);
      transition: all 0.6s ease-out;
    }

    .scroll-animate.animate-in {
      opacity: 1;
      transform: translateY(0);
    }

    .scroll-animate:not(.animate-in) {
      opacity: 0;
      transform: translateY(20px);
    }

    /* Support page scoped styles */
    .support-page {
      --support-bg: var(--mist);
      --support-card-bg: rgba(255,255,255,0.92);
      --support-border: rgba(43,42,37,0.12);
      --support-text: var(--earth);
      --support-accent: var(--leaf);
      --support-subtle: var(--text-muted);
      background: var(--support-bg);
      background-image: linear-gradient(to right, rgba(26,61,34,0.04) 1px, transparent 1px), linear-gradient(to bottom, rgba(26,61,34,0.04) 1px, transparent 1px);
      background-size: 80px 80px;
      color: var(--support-text);
      font-family: 'DM Sans', sans-serif;
      line-height: 1.6;
    }

    .support-section {
      padding: 80px 0;
    }

    .support-container {
      max-width: 1140px;
      margin: 0 auto;
      padding: 0 24px;
    }

    .support-hero {
      text-align: center;
      padding: 120px 0 80px;
      background: linear-gradient(135deg, rgba(61,107,63,0.08) 0%, rgba(200,168,75,0.07) 100%);
    }

    .support-hero h1 {
      font-family: 'DM Serif Display', serif;
      font-size: 3.5rem;
      font-weight: 400;
      margin-bottom: 1rem;
      color: var(--support-text);
    }

    .support-hero p {
      font-size: 1.25rem;
      margin-bottom: 2.5rem;
      color: var(--support-subtle);
    }

    .support-search {
      display: flex;
      max-width: 600px;
      margin: 0 auto 2.5rem;
      background: white;
      border-radius: 50px;
      box-shadow: 0 8px 32px rgba(43,42,37,0.08);
      overflow: hidden;
      border: 1px solid var(--support-border);
    }

    .support-search input {
      flex: 1;
      padding: 18px 28px;
      border: none;
      font-size: 1rem;
      outline: none;
      background: transparent;
    }

    .support-search button {
      padding: 18px 32px;
      background: var(--support-accent);
      color: white;
      border: none;
      font-weight: 500;
      cursor: pointer;
      transition: background 0.3s;
    }

    .support-search button:hover {
      background: #3d6b3f;
    }

    .cta-buttons {
      display: flex;
      gap: 1rem;
      justify-content: center;
      flex-wrap: wrap;
      margin-bottom: 1.5rem;
    }

    .cta-btn {
      display: inline-block;
      padding: 14px 28px;
      background: var(--support-accent);
      color: white;
      text-decoration: none;
      border-radius: 50px;
      font-weight: 500;
      font-size: 15px;
      transition: all 0.3s;
      border: 2px solid var(--support-accent);
      white-space: nowrap;
      min-width: fit-content;
    }

    .cta-btn:hover {
      background: transparent;
      color: var(--support-accent);
    }

    .cta-btn.secondary {
      background: transparent;
      color: var(--support-accent);
    }

    .cta-btn.secondary:hover {
      background: var(--support-accent);
      color: white;
    }

    .section-title {
      font-family: 'DM Serif Display', serif;
      font-size: 2.5rem;
      text-align: center;
      margin-bottom: 3rem;
      color: var(--support-text);
    }

    .faq-categories {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(340px, 1fr));
      gap: 2rem;
      margin-bottom: 3rem;
    }

    .faq-category {
      background: var(--support-card-bg);
      border-radius: 16px;
      padding: 2rem;
      box-shadow: 0 8px 32px rgba(43,42,37,0.06);
      border: 1px solid var(--support-border);
    }

    .faq-category h3 {
      font-family: 'DM Serif Display', serif;
      font-size: 1.5rem;
      margin-bottom: 1.5rem;
      color: var(--support-text);
    }

    .faq-item {
      margin-bottom: 1rem;
    }

    .faq-toggle {
      width: 100%;
      background: none;
      border: none;
      text-align: left;
      padding: 1rem;
      font-size: 1rem;
      font-weight: 500;
      cursor: pointer;
      border-radius: 8px;
      transition: background 0.3s;
      color: var(--support-text);
    }

    .faq-toggle:hover {
      background: rgba(61,107,63,0.08);
    }

    .faq-toggle.active {
      background: rgba(61,107,63,0.12);
    }

    .faq-answer {
      display: none;
      padding: 0 1rem 1rem;
      color: var(--support-subtle);
      line-height: 1.6;
    }

    .faq-answer.show {
      display: block;
    }

    .faq-search {
      max-width: 500px;
      margin: 0 auto;
    }

    .faq-search input {
      width: 100%;
      padding: 16px 24px;
      border: 1px solid var(--support-border);
      border-radius: 50px;
      font-size: 1rem;
      outline: none;
      background: white;
    }

    .contact-info {
      text-align: center;
      background: linear-gradient(135deg, rgba(61,107,63,0.05) 0%, rgba(200,168,75,0.04) 100%);
    }

    .contact-info h2 {
      margin-bottom: 2rem;
    }

    .contact-info p {
      font-size: 1.1rem;
      margin-bottom: 1rem;
    }

    .contact-info strong {
      color: var(--support-accent);
    }

    .contact-info a {
      color: var(--support-accent);
      text-decoration: none;
    }

    .contact-info a:hover {
      text-decoration: underline;
    }

    .kb-categories {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 2rem;
      margin-top: 2rem;
    }

    .kb-category {
      background: var(--support-card-bg);
      border-radius: 12px;
      padding: 1.5rem;
      box-shadow: 0 4px 16px rgba(43,42,37,0.06);
      border: 1px solid var(--support-border);
    }

    .kb-category h3 {
      font-family: 'DM Serif Display', serif;
      font-size: 1.25rem;
      margin-bottom: 1rem;
      color: var(--support-text);
    }

    .kb-category ul {
      list-style: none;
      padding: 0;
    }

    .kb-category li {
      margin-bottom: 0.75rem;
    }

    .kb-category a {
      color: var(--support-accent);
      text-decoration: none;
      font-weight: 500;
    }

    .kb-category a:hover {
      text-decoration: underline;
    }

    .kb-search {
      max-width: 500px;
      margin: 0 auto 2rem;
    }

    .kb-search input {
      width: 100%;
      padding: 16px 24px;
      border: 1px solid var(--support-border);
      border-radius: 50px;
      font-size: 1rem;
      outline: none;
      background: white;
    }

    .forum-topics {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 2rem;
      margin: 2rem 0;
    }

    .forum-topic {
      background: var(--support-card-bg);
      border-radius: 12px;
      padding: 1.5rem;
      text-align: center;
      box-shadow: 0 4px 16px rgba(43,42,37,0.06);
      border: 1px solid var(--support-border);
    }

    .forum-topic h3 {
      font-family: 'DM Serif Display', serif;
      font-size: 1.25rem;
      margin-bottom: 1rem;
      color: var(--support-text);
    }

    .forum-topic a {
      color: var(--support-accent);
      text-decoration: none;
      font-weight: 500;
    }

    .forum-topic a:hover {
      text-decoration: underline;
    }

    .system-status {
      text-align: center;
      background: linear-gradient(135deg, rgba(61,107,63,0.05) 0%, rgba(200,168,75,0.04) 100%);
    }

    .status-indicator {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 0.75rem 1.5rem;
      background: rgba(61,107,63,0.1);
      border-radius: 50px;
      margin-bottom: 2rem;
    }

    .status-indicator::before {
      content: '';
      width: 10px;
      height: 10px;
      border-radius: 50%;
      background: #28a745;
    }

    .update-history {
      max-width: 600px;
      margin: 0 auto;
      text-align: left;
    }

    .update-history h3 {
      font-family: 'DM Serif Display', serif;
      font-size: 1.25rem;
      margin-bottom: 1rem;
      color: var(--support-text);
    }

    .update-history ul {
      list-style: none;
      padding: 0;
    }

    .update-history li {
      padding: 0.5rem 0;
      border-bottom: 1px solid var(--support-border);
    }

    .feedback-form {
      max-width: 600px;
      margin: 0 auto;
      background: var(--support-card-bg);
      border-radius: 16px;
      padding: 2.5rem;
      box-shadow: 0 8px 32px rgba(43,42,37,0.06);
      border: 1px solid var(--support-border);
    }

    /* Responsive */
    @media (max-width: 768px) {
      .support-hero h1 {
        font-size: 2.5rem;
      }

      .support-hero p {
        font-size: 1.1rem;
      }

      .support-search {
        flex-direction: column;
        border-radius: 12px;
      }

      .support-search button {
        border-radius: 0 0 12px 12px;
      }

      .cta-buttons {
        flex-direction: column;
        align-items: center;
      }

      .cta-btn {
        width: 100%;
        max-width: 300px;
      }

      .faq-categories {
        grid-template-columns: 1fr;
      }

      .feedback-form {
        padding: 2rem 1.5rem;
      }

      .chat-window {
        width: calc(100vw - 48px);
        right: -12px;
      }
    }

    /* Critical Breakpoint - Fix 531px/607px width issue */
    @media (max-width: 540px) {
      .support-hero {
        padding: 2.75rem 0.875rem;
      }
      
      .support-hero h1 {
        font-size: 1.875rem;
      }
      
      .support-hero p {
        font-size: 0.95rem;
      }
      
      .support-search input {
        padding: 13px 15px;
        font-size: 0.975rem;
      }
      
      .support-search button {
        padding: 13px 22px;
        font-size: 0.975rem;
      }
      
      .faq-category-card {
        padding: 1.25rem;
      }
      
      .cta-buttons {
        gap: 0.875rem;
      }
      
      .cta-btn {
        padding: 11px 19px;
        font-size: 0.925rem;
      }
    }

    /* Mobile phones - Small (Fixes 640px/607px issues) */
    @media (max-width: 640px) {
      .support-hero {
        padding: 3rem 1rem;
      }
      
      .support-hero h1 {
        font-size: 2rem;
      }
      
      .support-hero p {
        font-size: 1rem;
      }
      
      .support-search input {
        padding: 14px 16px;
        font-size: 1rem;
      }
      
      .support-search button {
        padding: 14px 24px;
        font-size: 1rem;
      }
      
      .faq-category-card {
        padding: 1.5rem;
      }
    }

    /* Mobile phones - Extra small */
    @media (max-width: 480px) {
      .support-hero {
        padding: 2rem 0.75rem;
      }
      
      .support-hero h1 {
        font-size: 1.75rem;
      }
      
      .support-hero p {
        font-size: 0.95rem;
      }
      
      .cta-buttons {
        gap: 0.75rem;
      }
      
      .cta-btn {
        padding: 12px 20px;
        font-size: 0.95rem;
      }
    }

    /* Ultra small screens */
    @media (max-width: 360px) {
      .support-hero h1 {
        font-size: 1.5rem;
      }
      
      .support-search {
        gap: 0;
      }
      
      .support-search input {
        padding: 12px 14px;
        font-size: 0.95rem;
      }
      
      .support-search button {
        padding: 12px 18px;
        font-size: 0.9rem;
      }
    }
  </style>
</head>
<body>

<?php require APP_PATH . '/Views/partials/header-tailwind.php'; ?>
  <div class="support-page">
  <?php $base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '/'); ?>
  <section class="support-hero">
    <div class="support-container">
      <h1 class="scroll-animate">How can we assist you?</h1>
      <p class="scroll-animate">Find solutions quickly or contact our support team for help.</p>
      
      <div class="support-search scroll-animate">
        <input type="text" placeholder="Search for your issue..." aria-label="Support Search" id="supportSearch">
        <button class="search-btn" onclick="performSearch()">Search</button>
      </div>
      
      <div class="cta-buttons scroll-animate">
        <a href="#faq" class="cta-btn">Browse FAQs</a>
        <a href="#contact-form" class="cta-btn secondary">Contact Support</a>
      </div>
      
      <p class="scroll-animate"><a href="#account-help" style="color: var(--support-accent);">Need help with registration or login?</a></p>
    </div>
  </section>

  <!-- FAQs Section -->
  <section class="support-section faqs" id="faq">
    <div class="support-container">
      <h2 class="section-title scroll-animate">Frequently Asked Questions</h2>
      
      <div class="faq-categories">
        <div class="faq-category scroll-animate">
          <h3>Account Issues</h3>
          <div class="faq-item">
            <button class="faq-toggle" onclick="toggleFAQ(this)">How do I reset my password?</button>
            <div class="faq-answer">To reset your password, click the 'Forgot Password' link on the login page. Enter your email address and follow the instructions sent to your email to create a new password.</div>
          </div>
          <div class="faq-item">
            <button class="faq-toggle" onclick="toggleFAQ(this)">How do I update my profile information?</button>
            <div class="faq-answer">Log in to your account and navigate to the Dashboard. Click on 'Profile Settings' where you can update your personal information, contact details, and preferences.</div>
          </div>
          <div class="faq-item">
            <button class="faq-toggle" onclick="toggleFAQ(this)">Why can't I log in to my account?</button>
            <div class="faq-answer">Ensure you're using the correct email and password. If you've forgotten your password, use the 'Forgot Password' link. If issues persist, contact our support team.</div>
          </div>
        </div>
        
        <div class="faq-category scroll-animate">
          <h3>Transactions</h3>
          <div class="faq-item">
            <button class="faq-toggle" onclick="toggleFAQ(this)">How can I place an order?</button>
            <div class="faq-answer">Browse the marketplace, select the products you want, and add them to your cart. Proceed to checkout, confirm your order details, and complete the payment process.</div>
          </div>
          <div class="faq-item">
            <button class="faq-toggle" onclick="toggleFAQ(this)">What payment methods are accepted?</button>
            <div class="faq-answer">We accept various payment methods including credit/debit cards, mobile money, and bank transfers. Available options may vary based on your location.</div>
          </div>
          <div class="faq-item">
            <button class="faq-toggle" onclick="toggleFAQ(this)">How do I track my order?</button>
            <div class="faq-answer">Once your order is confirmed, you'll receive a tracking number via email. Use this number in the 'Track Order' section of your dashboard to monitor delivery status.</div>
          </div>
        </div>
        
        <div class="faq-category scroll-animate">
          <h3>Technical Support</h3>
          <div class="faq-item">
            <button class="faq-toggle" onclick="toggleFAQ(this)">The website is loading slowly</button>
            <div class="faq-answer">Try clearing your browser cache and cookies, or try using a different browser. If the problem continues, check your internet connection or contact our technical team.</div>
          </div>
          <div class="faq-item">
            <button class="faq-toggle" onclick="toggleFAQ(this)">I'm having trouble uploading images</button>
            <div class="faq-answer">Ensure your images are in supported formats (JPG, PNG) and under 5MB. Check your internet connection and try again. If issues persist, contact support.</div>
          </div>
        </div>
      </div>
      
      <div class="faq-search scroll-animate">
        <input type="text" placeholder="Search FAQs..." aria-label="FAQ Search" id="faqSearch" onkeyup="searchFAQs()">
      </div>
    </div>
  </section>

  <!-- BotPenguin AI Assistant -->
  <script id="messenger-widget-b" src="https://cdn.botpenguin.com/website-bot.js" defer>69e2c2af977c2b308de622f9,69e2c282d265c6bc6827522b</script>

  <!-- Contact Information -->
  <section class="support-section contact-info">
    <div class="support-container">
      <h2 class="section-title scroll-animate">Contact Us</h2>
      <p>Call Us: <strong>+265 999 137 598</strong></p>
      <p>Email: <strong><a href="mailto:support@ulimi.mw">support@ulimi.mw</a></strong></p>
      <p>Our support team is available Monday to Friday, 9 AM - 6 PM (CAT).</p>
      <div class="live-chat-status">
        <span style="color: var(--support-accent); font-weight: 500;">Chat Now: Available</span>
      </div>
    </div>
  </section>

  <!-- Knowledge Base -->
  <section class="support-section" id="knowledge-base">
    <div class="support-container">
      <h2 class="section-title scroll-animate">Knowledge Base</h2>
      
      <div class="kb-search scroll-animate">
        <input type="text" placeholder="Search Knowledge Base..." class="kb-search" id="kbSearch" onkeyup="searchKB()">
      </div>
      
      <div class="kb-categories">
        <div class="kb-category scroll-animate">
          <h3>Platform Tutorials</h3>
          <ul>
            <li><a href="#">How to Register</a></li>
            <li><a href="#">How to Upload Listings</a></li>
            <li><a href="#">Navigating the Dashboard</a></li>
            <li><a href="#">Managing Your Profile</a></li>
          </ul>
        </div>
        <div class="kb-category scroll-animate">
          <h3>Account Management</h3>
          <ul>
            <li><a href="#">How to Update Profile</a></li>
            <li><a href="#">Password Recovery</a></li>
            <li><a href="#">Setting Up Notifications</a></li>
            <li><a href="#">Privacy Settings</a></li>
          </ul>
        </div>
        <div class="kb-category scroll-animate">
          <h3>Buying & Selling</h3>
          <ul>
            <li><a href="#">How to Place an Order</a></li>
            <li><a href="#">Creating Product Listings</a></li>
            <li><a href="#">Understanding Payment Methods</a></li>
            <li><a href="#">Shipping and Delivery</a></li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <!-- Community Forum -->
  <section class="support-section community-forum" id="community-forum">
    <div class="support-container">
      <h2 class="section-title scroll-animate">Join the Community</h2>
      <p class="scroll-animate">Need help from fellow traders or farmers? Join the Ulimi community forum to discuss issues, share tips, and collaborate.</p>
      
      <div class="forum-topics">
        <div class="forum-topic scroll-animate">
          <h3>Trading Advice</h3>
          <a href="#">Join the Discussion</a>
        </div>
        <div class="forum-topic scroll-animate">
          <h3>Agri-Tech Tools</h3>
          <a href="#">Join the Discussion</a>
        </div>
        <div class="forum-topic scroll-animate">
          <h3>Market Insights</h3>
          <a href="#">Join the Discussion</a>
        </div>
      </div>
      
      <div style="text-align: center; margin-top: 2rem;" class="scroll-animate">
        <a href="#" class="cta-btn">Join the Forum</a>
      </div>
    </div>
  </section>

  <!-- System Status -->
  <section class="support-section system-status">
    <div class="support-container">
      <h2 class="section-title scroll-animate">System Status</h2>
      <div class="status-indicator scroll-animate">
        <span>All Systems Operational</span>
      </div>
      <div class="update-history scroll-animate">
        <h3>Recent Updates</h3>
        <ul>
          <li>System upgrade completed on April 5th, 2026</li>
          <li>New features added to the dashboard on April 3rd, 2026</li>
          <li>Performance improvements deployed on April 1st, 2026</li>
        </ul>
      </div>
    </div>
  </section>

  <!-- Feedback Section -->
  <section class="support-section">
    <div class="support-container">
      <h2 class="section-title scroll-animate">Your Feedback Matters</h2>
      
      <form class="feedback-form scroll-animate" id="feedbackForm">
        <div class="form-group">
          <label for="feedback-name">Your Name</label>
          <input type="text" id="feedback-name" name="feedback_name" required>
        </div>
        
        <div class="form-group">
          <label for="feedback-email">Email Address</label>
          <input type="email" id="feedback-email" name="feedback_email" required>
        </div>
        
        <div class="form-group">
          <label for="feedback-category">Feedback Category</label>
          <select id="feedback-category" name="feedback_category" required>
            <option value="">Select a category</option>
            <option value="feature-request">Feature Request</option>
            <option value="bug-report">Bug Report</option>
            <option value="general-feedback">General Feedback</option>
          </select>
        </div>
        
        <div class="form-group">
          <label for="feedback-message">Your Message</label>
          <textarea id="feedback-message" name="feedback_message" required></textarea>
        </div>
        
        <button type="submit" class="submit-btn">Submit Feedback</button>
      </form>
    </div>
  </section>

  <script>
    // FAQ Toggle
    function toggleFAQ(button) {
      const answer = button.nextElementSibling;
      const isActive = button.classList.contains('active');
      
      // Close all FAQs in the same category
      const category = button.closest('.faq-category');
      category.querySelectorAll('.faq-toggle').forEach(btn => {
        btn.classList.remove('active');
        btn.nextElementSibling.classList.remove('show');
      });
      
      // Open clicked FAQ if it wasn't active
      if (!isActive) {
        button.classList.add('active');
        answer.classList.add('show');
      }
    }

    // FAQ Search
    function searchFAQs() {
      const searchTerm = document.getElementById('faqSearch').value.toLowerCase();
      const faqItems = document.querySelectorAll('.faq-item');
      
      faqItems.forEach(item => {
        const question = item.querySelector('.faq-toggle').textContent.toLowerCase();
        const answer = item.querySelector('.faq-answer').textContent.toLowerCase();
        
        if (question.includes(searchTerm) || answer.includes(searchTerm)) {
          item.style.display = 'block';
        } else {
          item.style.display = 'none';
        }
      });
    }

    // Knowledge Base Search
    function searchKB() {
      const searchTerm = document.getElementById('kbSearch').value.toLowerCase();
      const categories = document.querySelectorAll('.kb-category');
      
      categories.forEach(category => {
        const links = category.querySelectorAll('a');
        let hasMatch = false;
        
        links.forEach(link => {
          if (link.textContent.toLowerCase().includes(searchTerm)) {
            hasMatch = true;
          }
        });
        
        category.style.display = hasMatch || searchTerm === '' ? 'block' : 'none';
      });
    }

    // Support Search
    function performSearch() {
      const searchTerm = document.getElementById('supportSearch').value.toLowerCase();
      // Simple implementation - scroll to FAQ section and filter
      document.getElementById('faq').scrollIntoView({ behavior: 'smooth' });
      document.getElementById('faqSearch').value = searchTerm;
      searchFAQs();
    }

    // Feedback Form
    document.getElementById('feedbackForm').addEventListener('submit', function(e) {
      e.preventDefault();
      alert('Thank you for your feedback! We appreciate your input and will use it to improve our services.');
      this.reset();
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          target.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
          });
        }
      });
    });

    // Scroll Animation Observer
    const observerOptions = {
      root: null,
      rootMargin: '0px',
      threshold: 0.1
    };

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.classList.add('animate-in');
        }
      });
    }, observerOptions);

    // Observe all scroll-animate elements
    document.querySelectorAll('.scroll-animate').forEach(el => {
      observer.observe(el);
    });

    // Trigger initial animations for elements already in view
    window.addEventListener('load', () => {
      document.querySelectorAll('.scroll-animate').forEach(el => {
        const rect = el.getBoundingClientRect();
        if (rect.top < window.innerHeight) {
          el.classList.add('animate-in');
        }
      });
    });
  </script>
</body>
</html>
