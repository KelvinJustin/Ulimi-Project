<?php
$base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '/');
$isLoggedIn = \App\Core\Auth::check();
$user = \App\Core\Auth::user();

// Get current path for navigation highlighting
$currentPath = $_SERVER['REQUEST_URI'] ?? '/';
$currentPath = parse_url($currentPath, PHP_URL_PATH) ?? '/';
$currentPath = rtrim($currentPath, '/');
if ($currentPath === '') $currentPath = '/';

function isNavLinkActive($href, $currentPath) {
  $href = rtrim($href, '/');
  if ($href === '') $href = '/';
  return $href === $currentPath;
}
?>

<header class="bg-white border-b border-ash-grey/30 sticky top-0 z-50">
  <nav aria-label="Global" class="mx-auto flex max-w-7xl items-center justify-between p-6 lg:px-8">
    <!-- Logo -->
    <div class="flex lg:flex-1 items-center">
          <a href="<?= $base ?>/" class="-m-1.5 p-1.5 flex items-center">
          <span class="sr-only">Ulimi</span>
          <div class="flex items-center gap-2">
            <img src="/logo.png" alt="Ulimi Logo" class="w-8 h-8 rounded-lg object-cover">
            <span class="text-xl font-semibold text-dark-fern">Ulimi</span>
          </div>
      </a>
    </div>

    <!-- Mobile menu button -->
    <div class="flex lg:hidden">
      <button type="button" id="mobile-menu-button" class="-m-2.5 inline-flex items-center justify-center rounded-md p-2.5 text-ash-grey">
        <span class="sr-only">Open main menu</span>
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
        </svg>
      </button>
    </div>

    <!-- Desktop navigation -->
    <div class="hidden lg:flex lg:gap-x-8">
      <a href="<?= $base ?>/" class="<?= isNavLinkActive('/', $currentPath) ? 'text-fern' : 'text-dark-fern' ?> text-sm font-semibold hover:text-olive transition-colors">Home</a>
      <?php if ($isLoggedIn): ?>
      <a href="<?= $base ?>/browse" class="<?= isNavLinkActive('/browse', $currentPath) ? 'text-fern' : 'text-dark-fern' ?> text-sm font-semibold hover:text-olive transition-colors">Browse</a>
      <?php endif; ?>
      <a href="<?= $base ?>/marketplace-site" class="<?= isNavLinkActive('/marketplace-site', $currentPath) ? 'text-fern' : 'text-dark-fern' ?> text-sm font-semibold hover:text-olive transition-colors">Marketplace</a>
      <a href="<?= $base ?>/services" class="<?= isNavLinkActive('/services', $currentPath) ? 'text-fern' : 'text-dark-fern' ?> text-sm font-semibold hover:text-olive transition-colors">Services</a>
      <a href="<?= $base ?>/about" class="<?= isNavLinkActive('/about', $currentPath) ? 'text-fern' : 'text-dark-fern' ?> text-sm font-semibold hover:text-olive transition-colors">About Ulimi</a>
      <a href="<?= $base ?>/support" class="<?= isNavLinkActive('/support', $currentPath) ? 'text-fern' : 'text-dark-fern' ?> text-sm font-semibold hover:text-olive transition-colors">Support</a>
    </div>

    <!-- Desktop auth buttons -->
    <div class="hidden lg:flex lg:flex-1 lg:justify-end items-center gap-4">
      <?php if ($isLoggedIn): ?>
        <!-- Message notification -->
        <div class="relative">
          <?php if ($user['role'] === 'seller'): ?>
            <a href="<?= $base ?>/messages" class="flex items-center justify-center w-10 h-10 rounded-full bg-soft-linen text-dark-fern hover:bg-olive hover:text-white transition-colors">
              <i class="fa fa-envelope"></i>
            </a>
          <?php else: ?>
            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-200 text-gray-400 cursor-not-allowed" title="Messages available for sellers only">
              <i class="fa fa-envelope"></i>
            </div>
          <?php endif; ?>
          <span id="messageBadge" class="hidden absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">0</span>
        </div>
        <!-- User dropdown -->
        <div class="relative" id="user-menu-container">
          <button type="button" id="user-menu-button" class="flex items-center gap-2 rounded-full bg-fern px-3 py-2 text-white hover:bg-olive transition-colors">
            <span class="w-8 h-8 rounded-full bg-fern-dark flex items-center justify-center text-sm font-medium">
              <?php 
              $displayName = $user['display_name'] ?? '';
              $email = $user['email'] ?? '';
              $initial = !empty($displayName) ? strtoupper(substr($displayName, 0, 1)) : (!empty($email) ? strtoupper(substr($email, 0, 1)) : 'U');
              echo htmlspecialchars($initial, ENT_QUOTES, 'UTF-8');
              ?>
            </span>
            <span class="text-sm font-medium"><?= htmlspecialchars(ucfirst($user['display_name'] ?? $user['email']), ENT_QUOTES, 'UTF-8') ?></span>
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
            </svg>
          </button>
          
          <!-- Dropdown menu -->
          <div id="user-dropdown" class="fixed w-56 rounded-lg bg-white shadow-lg ring-1 ring-ash-grey/30 hidden z-[100]">
            <div class="px-4 py-3 border-b border-ash-grey/30">
              <p class="text-sm font-semibold text-dark-fern"><?= htmlspecialchars(ucfirst($user['display_name'] ?? $user['email']), ENT_QUOTES, 'UTF-8') ?></p>
              <p class="text-xs text-ash-grey"><?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?></p>
            </div>
            <div class="py-1">
              <a href="<?= $base ?>/dashboard" class="block px-4 py-2 text-sm text-dark-fern hover:bg-soft-linen">Dashboard</a>
              <?php if ($user['role'] === 'seller'): ?>
              <a href="<?= $base ?>/messages" class="block px-4 py-2 text-sm text-dark-fern hover:bg-soft-linen">Messages</a>
              <?php endif; ?>
              <a href="<?= $base ?>/profile" class="block px-4 py-2 text-sm text-dark-fern hover:bg-soft-linen">Profile Settings</a>
              <?php if ($user['role'] === 'admin'): ?>
              <a href="<?= $base ?>/admin" class="block px-4 py-2 text-sm text-dark-fern hover:bg-soft-linen">Admin Terminal</a>
              <?php endif; ?>
            </div>
            <div class="border-t border-ash-grey/30 py-1">
              <form method="post" action="<?= $base ?>/logout">
                <input type="hidden" name="_csrf" value="<?= htmlspecialchars(\App\Core\Csrf::token(), ENT_QUOTES, 'UTF-8') ?>">
                <button type="submit" class="block w-full px-4 py-2 text-sm text-left text-red-600 hover:bg-soft-linen">Log Out</button>
              </form>
            </div>
          </div>
        </div>
      <?php else: ?>
        <a href="<?= $base ?>/login" class="text-sm font-semibold text-dark-fern hover:text-fern transition-colors">Log in →</a>
        <a href="<?= $base ?>/register" class="ml-4 rounded-lg bg-fern px-4 py-2 text-sm font-semibold text-white hover:bg-olive transition-colors">Sign Up</a>
      <?php endif; ?>
    </div>
  </nav>

  <!-- Mobile menu -->
  <div id="mobile-menu" class="hidden lg:hidden">
    <div class="fixed inset-0 z-50">
      <div class="fixed inset-y-0 right-0 z-50 w-full overflow-y-auto bg-white px-6 py-6 sm:max-w-sm sm:ring-1 sm:ring-ash-grey/30">
        <div class="flex items-center justify-between">
          <a href="<?= $base ?>/" class="-m-1.5 p-1.5 flex items-center">
            <span class="sr-only">Ulimi</span>
            <div class="flex items-center gap-2">
              <img src="/logo.png" alt="Ulimi Logo" class="w-8 h-8 rounded-lg object-cover">
              <span class="text-xl font-semibold text-dark-fern">Ulimi</span>
            </div>
          </a>
          <button type="button" id="mobile-menu-close" class="-m-2.5 rounded-md p-2.5 text-ash-grey">
            <span class="sr-only">Close menu</span>
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        <div class="mt-6 flow-root">
          <div class="-my-6 divide-y divide-ash-grey/20">
            <div class="space-y-2 py-6">
              <a href="<?= $base ?>/" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold text-dark-fern hover:bg-soft-linen">Home</a>
              <?php if ($isLoggedIn): ?>
              <a href="<?= $base ?>/browse" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold text-dark-fern hover:bg-soft-linen">Browse</a>
              <?php endif; ?>
              <a href="<?= $base ?>/marketplace-site" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold text-dark-fern hover:bg-soft-linen">Marketplace</a>
              <a href="<?= $base ?>/services" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold text-dark-fern hover:bg-soft-linen">Services</a>
              <a href="<?= $base ?>/about" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold text-dark-fern hover:bg-soft-linen">About Ulimi</a>
              <a href="<?= $base ?>/support" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold text-dark-fern hover:bg-soft-linen">Support</a>
            </div>
            <div class="py-6">
              <?php if ($isLoggedIn): ?>
                <div class="px-3 py-2 mb-4">
                  <p class="text-sm font-semibold text-dark-fern"><?= htmlspecialchars(ucfirst($user['display_name'] ?? $user['email']), ENT_QUOTES, 'UTF-8') ?></p>
                  <p class="text-xs text-ash-grey"><?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?></p>
                </div>
                <a href="<?= $base ?>/dashboard" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold text-dark-fern hover:bg-soft-linen">Dashboard</a>
                <?php if ($user['role'] === 'seller'): ?>
                <div class="relative">
                  <a href="<?= $base ?>/messages" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold text-dark-fern hover:bg-soft-linen">Messages</a>
                  <span id="mobileMessageBadge" class="hidden absolute -top-1 -right-2 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">0</span>
                </div>
                <?php endif; ?>
                <a href="<?= $base ?>/profile" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold text-dark-fern hover:bg-soft-linen">Profile Settings</a>
                <?php if ($user['role'] === 'admin'): ?>
                <a href="<?= $base ?>/admin" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold text-dark-fern hover:bg-soft-linen">Admin Terminal</a>
                <?php endif; ?>
                <form method="post" action="<?= $base ?>/logout">
                  <input type="hidden" name="_csrf" value="<?= htmlspecialchars(\App\Core\Csrf::token(), ENT_QUOTES, 'UTF-8') ?>">
                  <button type="submit" class="-mx-3 block w-full rounded-lg px-3 py-2 text-base font-semibold text-red-600 hover:bg-soft-linen">Log Out</button>
                </form>
              <?php else: ?>
                <a href="<?= $base ?>/login" class="-mx-3 block rounded-lg px-3 py-2 text-base font-semibold text-dark-fern hover:bg-soft-linen">Log in</a>
                <a href="<?= $base ?>/register" class="-mx-3 block rounded-lg bg-fern px-3 py-2 text-base font-semibold text-white hover:bg-olive">Sign Up</a>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</header>

<script>
window.currentUserId = <?= $isLoggedIn ? $user['id'] : 0 ?>;

// Mobile menu toggle
const mobileMenuButton = document.getElementById('mobile-menu-button');
const mobileMenuClose = document.getElementById('mobile-menu-close');
const mobileMenu = document.getElementById('mobile-menu');

if (mobileMenuButton && mobileMenu && mobileMenuClose) {
  mobileMenuButton.addEventListener('click', () => {
    mobileMenu.classList.remove('hidden');
  });

  mobileMenuClose.addEventListener('click', () => {
    mobileMenu.classList.add('hidden');
  });
}

// User dropdown toggle
const userMenuButton = document.getElementById('user-menu-button');
const userDropdown = document.getElementById('user-dropdown');

if (userMenuButton && userDropdown) {
  userMenuButton.addEventListener('click', (e) => {
    e.stopPropagation();
    
    // Position the dropdown based on button position
    const buttonRect = userMenuButton.getBoundingClientRect();
    userDropdown.style.top = (buttonRect.bottom + 8) + 'px';
    userDropdown.style.right = (window.innerWidth - buttonRect.right) + 'px';
    
    userDropdown.classList.toggle('hidden');
  });

  // Close dropdown when clicking outside
  document.addEventListener('click', (e) => {
    if (!userMenuButton.contains(e.target) && !userDropdown.contains(e.target)) {
      userDropdown.classList.add('hidden');
    }
  });

  // Reposition dropdown on window resize
  window.addEventListener('resize', () => {
    if (!userDropdown.classList.contains('hidden')) {
      const buttonRect = userMenuButton.getBoundingClientRect();
      userDropdown.style.top = (buttonRect.bottom + 8) + 'px';
      userDropdown.style.right = (window.innerWidth - buttonRect.right) + 'px';
    }
  });
}

// Start notification polling if logged in
<?php if ($isLoggedIn): ?>
if (typeof startNotificationPolling === 'function') {
  startNotificationPolling();
}
<?php endif; ?>
</script>

<script src="/assets/js/chat.js" defer></script>
