<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title ?? 'My Listings', ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
    *, *::before, *::after { 
      box-sizing: border-box; 
      margin: 0; 
      padding: 0; 
    }

    :root {
      --earth:    #2B2A25;
      --leaf:     #3D6B3F;
      --leaf-lt:  #4F8A52;
      --crop:     #C8A84B;
      --crop-lt:  #E8C96A;
      --cream:    #F5F0E8;
      --cream-dk: #EBE4D6;
      --mist:     #F9F6F0;
      --charcoal: #1A1A16;
      --text-muted: #6B6558;
      --border:   rgba(43,42,37,0.12);
      --font-head: 'DM Serif Display', Georgia, serif;
      --font-body: 'DM Sans', sans-serif;
    }

    body {
      font-family: var(--font-body);
      background: var(--mist);
      color: var(--earth);
      line-height: 1.6;
      min-height: 100vh;
    }

    /* My Listings Page Styles */
    footer {
      background: rgba(255, 255, 255, 0.05);
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      color: #333;
      margin-top: 3rem;
      padding: 2rem 0;
    }
    
    .footer-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 2rem;
      margin-bottom: 2rem;
    }
    
    .footer-brand {
      grid-column: span 2;
    }
    
    .footer-brand .logo {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 1.5rem;
      font-weight: 600;
      color: #3d6b3f !important;
      text-decoration: none;
      margin-bottom: 1rem;
    }
    
    .footer-brand .logo .logo-mark {
      width: 32px;
      height: 32px;
      background: #3d6b3f;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .footer-brand .logo .logo-mark svg {
      width: 20px;
      height: 20px;
      fill: white;
    }
    
    .footer-brand p {
      color: #666;
      line-height: 1.6;
      margin-bottom: 1rem;
    }
    
    .footer-socials {
      display: flex;
      gap: 1rem;
    }
    
    .footer-socials a {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      background: #3d6b3f;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
      transition: all 0.3s;
    }
    
    .footer-socials a:hover {
      background: #2d5a2f;
      transform: translateY(-2px);
    }
    
    .footer-col h6 {
      color: #3d6b3f;
      font-weight: 600;
      margin-bottom: 1rem;
      text-transform: uppercase;
      font-size: 0.9rem;
      letter-spacing: 0.5px;
    }
    
    .footer-col ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    
    .footer-col ul li {
      margin-bottom: 0.5rem;
    }
    
    .footer-col ul li a {
      color: #333;
      text-decoration: none;
      transition: color 0.3s;
    }
    
    .footer-col ul li a:hover {
      color: #3d6b3f;
    }
    
    .footer-bottom {
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      padding-top: 1.5rem;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 1rem;
    }
    
    .footer-bottom span {
      color: #666;
    }
    
    .footer-bottom div {
      display: flex;
      gap: 1rem;
    }
    
    .footer-bottom div a {
      color: #666 !important;
      text-decoration: none !important;
      font-size: 0.85rem;
      transition: color 0.3s;
    }
    
    .footer-bottom div a:hover {
      color: #3d6b3f !important;
    }

    /* Page needs padding for fixed header */
    .page-header {
      margin-top: 68px;
      background: white;
      border-bottom: 1px solid var(--border);
      padding: 2rem 0;
      margin-bottom: 2rem;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 32px;
    }

    .header-content {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 1rem;
    }

    .header-title h1 {
      font-family: var(--font-head);
      font-size: 2.5rem;
      color: var(--earth);
      margin-bottom: 0.5rem;
    }

    .header-title p {
      color: var(--text-muted);
      font-size: 1.1rem;
    }

    .header-actions {
      display: flex;
      gap: 1rem;
    }

    .btn {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 12px 24px;
      border-radius: 8px;
      font-weight: 500;
      text-decoration: none;
      transition: all 0.3s ease;
      cursor: pointer;
      border: none;
      white-space: nowrap;
    }

    .btn-primary {
      background: var(--leaf);
      color: white;
    }

    .btn-primary:hover {
      background: var(--leaf-lt);
      transform: translateY(-2px);
    }

    .btn-secondary {
      background: transparent;
      color: var(--earth);
      border: 1px solid var(--border);
    }

    .btn-secondary:hover {
      background: var(--cream-dk);
    }

    .listings-container {
      background: white;
      border-radius: 16px;
      padding: 2rem;
      box-shadow: 0 4px 20px rgba(43,42,37,0.08);
      border: 1px solid var(--border);
    }

    .listings-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 2rem;
      flex-wrap: wrap;
      gap: 1rem;
    }

    .listings-header h2 {
      font-family: var(--font-head);
      font-size: 1.75rem;
      color: var(--earth);
    }

    .filter-controls {
      display: flex;
      gap: 1rem;
      align-items: center;
      flex-wrap: wrap;
    }

    .filter-group {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .filter-group label {
      font-weight: 500;
      color: var(--text-muted);
    }

    .filter-group select,
    .filter-group input {
      padding: 8px 12px;
      border: 1px solid var(--border);
      border-radius: 6px;
      font-size: 0.9rem;
      background: white;
    }

    .listings-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 2rem;
      margin-bottom: 2rem;
    }

    .listing-card {
      background: var(--cream);
      border-radius: 12px;
      overflow: hidden;
      border: 1px solid var(--border);
      transition: all 0.3s ease;
      cursor: pointer;
    }

    .listing-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 32px rgba(43,42,37,0.15);
    }

    .listing-image {
      width: 100%;
      height: 200px;
      background: linear-gradient(135deg, var(--leaf), var(--crop));
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 3rem;
      position: relative;
      overflow: hidden;
    }

    .listing-image img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .listing-status {
      position: absolute;
      top: 1rem;
      right: 1rem;
      padding: 0.25rem 0.75rem;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 500;
    }

    .status-active {
      background: rgba(40, 167, 69, 0.9);
      color: white;
    }

    .status-pending {
      background: rgba(255, 193, 7, 0.9);
      color: var(--charcoal);
    }

    .status-draft {
      background: rgba(108, 117, 125, 0.9);
      color: white;
    }

    .status-archived {
      background: rgba(220, 53, 69, 0.9);
      color: white;
    }

    .status-sold {
      background: rgba(108, 117, 125, 0.9);
      color: white;
    }

    .listing-content {
      padding: 1.5rem;
    }

    .listing-title {
      font-size: 1.25rem;
      font-weight: 600;
      color: var(--earth);
      margin-bottom: 0.5rem;
      line-height: 1.3;
    }

    .listing-category {
      color: var(--text-muted);
      font-size: 0.9rem;
      margin-bottom: 1rem;
    }

    .listing-price {
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--leaf);
      margin-bottom: 0.5rem;
    }

    .listing-price span {
      font-size: 0.9rem;
      color: var(--text-muted);
      font-weight: 400;
    }

    .listing-location {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      color: var(--text-muted);
      font-size: 0.9rem;
      margin-bottom: 1rem;
    }

    .listing-actions {
      display: flex;
      gap: 0.5rem;
      flex-wrap: wrap;
    }

    .action-btn {
      display: inline-flex;
      align-items: center;
      gap: 0.25rem;
      padding: 6px 12px;
      border: 1px solid var(--border);
      background: white;
      color: var(--earth);
      border-radius: 6px;
      font-size: 0.85rem;
      cursor: pointer;
      transition: all 0.3s;
      text-decoration: none;
    }

    .action-btn:hover {
      background: var(--leaf);
      color: white;
      border-color: var(--leaf);
    }

    .action-btn.danger {
      color: #dc3545;
      border-color: #dc3545;
    }

    .action-btn.danger:hover {
      background: #dc3545;
      color: white;
    }

    .empty-state {
      text-align: center;
      padding: 4rem 2rem;
    }

    .empty-icon {
      width: 80px;
      height: 80px;
      background: var(--cream);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--text-muted);
      font-size: 2.5rem;
      margin: 0 auto 2rem;
    }

    .empty-state h3 {
      font-family: var(--font-head);
      font-size: 1.75rem;
      color: var(--earth);
      margin-bottom: 1rem;
    }

    .empty-state p {
      color: var(--text-muted);
      margin-bottom: 2rem;
      max-width: 500px;
      margin-left: auto;
      margin-right: auto;
    }

    .error-message {
      background: rgba(220, 53, 69, 0.1);
      border: 1px solid rgba(220, 53, 69, 0.2);
      color: #dc3545;
      padding: 1rem;
      border-radius: 8px;
      margin-bottom: 2rem;
      text-align: center;
    }

    @media (max-width: 768px) {
      .container {
        padding: 0 16px;
      }

      .header-content {
        flex-direction: column;
        align-items: flex-start;
      }

      .header-actions {
        width: 100%;
        justify-content: flex-start;
      }

      .listings-grid {
        grid-template-columns: 1fr;
      }

      .filter-controls {
        flex-direction: column;
        align-items: flex-start;
        width: 100%;
      }

      .filter-group {
        width: 100%;
      }

      .filter-group select,
      .filter-group input {
        width: 100%;
      }
    }

    /* Critical Breakpoint - Fix 531px/607px width issue */
    @media (max-width: 540px) {
      .container {
        padding: 0 10px;
      }
      
      .page-header {
        margin-top: 64px;
        padding: 1.25rem 0;
      }
      
      .header-content {
        gap: 0.875rem;
      }
      
      .header-title h1 {
        font-size: 1.875rem;
      }
      
      .header-title p {
        font-size: 0.95rem;
      }
      
      .header-actions {
        gap: 0.625rem;
      }
      
      .btn {
        padding: 9px 18px;
        font-size: 0.875rem;
      }
      
      .listings-container {
        padding: 1.25rem;
      }
      
      .listings-header {
        gap: 0.875rem;
      }
      
      .listings-header h2 {
        font-size: 1.375rem;
      }
      
      .filter-controls {
        gap: 0.625rem;
      }
      
      .listings-grid {
        gap: 0.875rem;
      }
      
      .listing-card {
        border-radius: 10px;
      }
      
      .listing-image {
        height: 170px;
      }
      
      .listing-content {
        padding: 0.875rem;
      }
      
      .listing-title {
        font-size: 1.05rem;
      }
      
      .listing-price {
        font-size: 1.25rem;
      }
      
      .action-btn {
        padding: 5px 10px;
        font-size: 0.8rem;
      }
    }

    @media (max-width: 640px) {
      .container {
        padding: 0 12px;
      }
      
      .page-header {
        margin-top: 68px;
        padding: 1.5rem 0;
      }
      
      .header-content {
        gap: 1rem;
      }
      
      .header-title h1 {
        font-size: 2rem;
      }
      
      .header-title p {
        font-size: 1rem;
      }
      
      .header-actions {
        gap: 0.75rem;
      }
      
      .btn {
        padding: 10px 20px;
        font-size: 0.9rem;
      }
      
      .listings-container {
        padding: 1.5rem;
      }
      
      .listings-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
      }
      
      .listings-header h2 {
        font-size: 1.5rem;
      }
      
      .filter-controls {
        width: 100%;
        gap: 0.75rem;
      }
      
      .filter-group label {
        font-size: 0.9rem;
      }
      
      .filter-group select,
      .filter-group input {
        padding: 8px 12px;
        font-size: 0.9rem;
      }
      
      .listings-grid {
        gap: 1rem;
      }
      
      .listing-card {
        border-radius: 12px;
      }
      
      .listing-image {
        height: 160px;
      }
      
      .listing-content {
        padding: 1rem;
      }
      
      .listing-title {
        font-size: 1.1rem;
      }
      
      .listing-price {
        font-size: 1.3rem;
      }
      
      .listing-actions {
        gap: 0.5rem;
      }
      
      .action-btn {
        padding: 6px 12px;
        font-size: 0.8rem;
      }
    }

    /* Extra Small Screens */
    @media (max-width: 480px) {
      .container {
        padding: 0 8px;
      }
      
      .page-header {
        padding: 1rem 0;
      }
      
      .header-title h1 {
        font-size: 1.75rem;
      }
      
      .header-actions {
        gap: 0.5rem;
      }
      
      .btn {
        padding: 8px 16px;
        font-size: 0.85rem;
      }
      
      .listings-container {
        padding: 1rem;
      }
      
      .listings-header h2 {
        font-size: 1.3rem;
      }
      
      .listing-image {
        height: 140px;
      }
      
      .listing-content {
        padding: 0.75rem;
      }
      
      .listing-title {
        font-size: 1rem;
      }
      
      .listing-price {
        font-size: 1.2rem;
      }
      
      .listing-actions {
        flex-direction: column;
        gap: 0.5rem;
      }
      
      .action-btn {
        padding: 8px 12px;
        font-size: 0.85rem;
        width: 100%;
        text-align: center;
      }
    }

    /* Ultra Small Screens */
    @media (max-width: 360px) {
      .container {
        padding: 0 6px;
      }
      
      .page-header {
        padding: 0.75rem 0;
      }
      
      .header-title h1 {
        font-size: 1.5rem;
      }
      
      .btn {
        padding: 6px 12px;
        font-size: 0.8rem;
      }
      
      .listings-container {
        padding: 0.75rem;
      }
      
      .listings-header h2 {
        font-size: 1.2rem;
      }
      
      .listing-image {
        height: 120px;
      }
      
      .listing-content {
        padding: 0.5rem;
      }
      
      .listing-title {
        font-size: 0.95rem;
      }
      
      .listing-price {
        font-size: 1.1rem;
      }
      
      .empty-state {
        padding: 2rem 1rem;
      }
      
      .empty-state h3 {
        font-size: 1.5rem;
      }
    }
  </style>
</head>
<body>
  <?php 
  $base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '/');
  ?>

  <div class="page-header">
    <div class="container">
      <div class="header-content">
        <div class="header-title">
          <h1>My Listings</h1>
          <p>Manage your agricultural product listings</p>
        </div>
        <div class="header-actions">
          <a href="<?= $base ?>/create-listing" class="btn btn-primary">
            <i class="fa fa-plus"></i>
            Create New Listing
          </a>
          <a href="<?= $base ?>/dashboard" class="btn btn-secondary">
            <i class="fa fa-arrow-left"></i>
            Back to Dashboard
          </a>
        </div>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="listings-container">
      <?php if (isset($error)): ?>
        <div class="error-message">
          <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
        </div>
      <?php endif; ?>

      <div class="listings-header">
        <h2>Your Listings</h2>
        <div class="filter-controls">
          <div class="filter-group">
            <label for="status-filter">Status:</label>
            <select id="status-filter">
              <option value="">All Status</option>
              <option value="active">Active</option>
              <option value="pending">Pending</option>
              <option value="draft">Draft</option>
              <option value="archived">Archived</option>
            </select>
          </div>
          <div class="filter-group">
            <label for="search-filter">Search:</label>
            <input type="text" id="search-filter" placeholder="Search listings...">
          </div>
        </div>
      </div>

      <?php if (empty($listings)): ?>
        <div class="empty-state">
          <div class="empty-icon">
            <i class="fa fa-list-alt"></i>
          </div>
          <h3>No Listings Yet</h3>
          <p>You haven't created any listings yet. Start by adding your first agricultural product to reach buyers across Malawi.</p>
          <a href="<?= $base ?>/create-listing" class="btn btn-primary">
            <i class="fa fa-plus"></i>
            Create Your First Listing
          </a>
        </div>
      <?php else: ?>
        <div class="listings-grid">
          <?php foreach ($listings as $listing): ?>
            <div class="listing-card">
              <div class="listing-image">
                <?php if (!empty($listing['image_path'])): ?>
                  <img src="<?= htmlspecialchars($listing['image_path'], ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($listing['title'], ENT_QUOTES, 'UTF-8') ?>">
                <?php else: ?>
                  <i class="fa fa-leaf"></i>
                <?php endif; ?>
                <span class="listing-status status-<?= htmlspecialchars($listing['status'] ?? 'pending', ENT_QUOTES, 'UTF-8') ?>">
                  <?= htmlspecialchars(ucfirst($listing['status'] ?? 'Pending'), ENT_QUOTES, 'UTF-8') ?>
                </span>
              </div>
              <div class="listing-content">
                <h3 class="listing-title"><?= htmlspecialchars($listing['title'], ENT_QUOTES, 'UTF-8') ?></h3>
                <p class="listing-category"><?= htmlspecialchars($listing['category'] ?? 'Agriculture', ENT_QUOTES, 'UTF-8') ?></p>
                <div class="listing-price">
                  MWK <?= number_format($listing['price_per_unit'] ?? 0, 2) ?>
                  <span>/ <?= htmlspecialchars($listing['price_unit'] ?? 'unit', ENT_QUOTES, 'UTF-8') ?></span>
                </div>
                <div class="listing-location">
                  <i class="fa fa-map-marker"></i>
                  <?= htmlspecialchars($listing['location_text'] ?? 'Location not specified', ENT_QUOTES, 'UTF-8') ?>
                </div>
                <div class="listing-actions">
                  <a href="#" class="action-btn">
                    <i class="fa fa-eye"></i>
                    View
                  </a>
                  <a href="/listings/edit/<?= $listing['id'] ?>" class="action-btn">
                    <i class="fa fa-edit"></i>
                    Edit
                  </a>
                  <button class="action-btn" data-listing-id="<?= $listing['id'] ?>" onclick="archiveListing(<?= $listing['id'] ?>, event)">
                    <i class="fa fa-archive"></i>
                    Archive
                  </button>
                  <button class="action-btn danger" data-listing-id="<?= $listing['id'] ?>" onclick="deleteListing(<?= $listing['id'] ?>, event)">
                    <i class="fa fa-trash"></i>
                    Delete
                  </button>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>

  <?php require APP_PATH . '/Views/partials/footer-tailwind.php'; ?>

  <script>
    // User menu functionality
    function toggleUserMenu() {
      const dropdown = document.getElementById('userDropdown');
      dropdown.classList.toggle('show');
    }

    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
      const userMenu = document.querySelector('.user-menu');
      const dropdown = document.getElementById('userDropdown');
      
      if (userMenu && !userMenu.contains(event.target)) {
        dropdown.classList.remove('show');
      }
    });

    // Filter functionality
    document.getElementById('status-filter').addEventListener('change', filterListings);
    document.getElementById('search-filter').addEventListener('input', filterListings);

    function filterListings() {
      const statusFilter = document.getElementById('status-filter').value.toLowerCase();
      const searchFilter = document.getElementById('search-filter').value.toLowerCase();
      const listings = document.querySelectorAll('.listing-card');

      listings.forEach(listing => {
        const status = listing.querySelector('.listing-status').textContent.toLowerCase();
        const title = listing.querySelector('.listing-title').textContent.toLowerCase();
        const category = listing.querySelector('.listing-category').textContent.toLowerCase();

        const matchesStatus = !statusFilter || status.includes(statusFilter);
        const matchesSearch = !searchFilter || title.includes(searchFilter) || category.includes(searchFilter);

        listing.style.display = matchesStatus && matchesSearch ? 'block' : 'none';
      });
    }

    // Delete listing functionality
    function deleteListing(listingId, event) {
      event.preventDefault();
      
      // Show confirmation popup
      showConfirmPopup(
        'Delete Listing',
        'Are you sure you want to delete this listing? This action cannot be undone.',
        () => {
          // User confirmed, proceed with deletion
          const formData = new FormData();
          formData.append('listing_id', listingId);
          formData.append('_csrf', '<?= htmlspecialchars($csrf ?? '', ENT_QUOTES, 'UTF-8') ?>');

          fetch('/listings/delete', {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              // Show success popup
              showSuccessPopup('Listing deleted successfully!');
              
              // Remove the listing card from DOM
              const listingCard = document.querySelector(`[data-listing-id="${listingId}"]`);
              if (listingCard) {
                listingCard.closest('.listing-card').remove();
              }
              
              // Check if no listings remain
              const remainingListings = document.querySelectorAll('.listing-card');
              if (remainingListings.length === 0) {
                location.reload();
              }
            } else {
              showSuccessPopup('Failed to delete listing: ' + (data.message || 'Unknown error'));
            }
          })
          .catch(error => {
            console.error('Error:', error);
            showSuccessPopup('Failed to delete listing. Please try again.');
          });
        }
      );
    }

    // Archive listing functionality
    function archiveListing(listingId, event) {
      event.preventDefault();
      
      // Show confirmation popup
      showConfirmPopup(
        'Archive Listing',
        'Are you sure you want to archive this listing? It will be hidden from public view but can be restored later.',
        () => {
          // User confirmed, proceed with archiving
          const formData = new FormData();
          formData.append('listing_id', listingId);
          formData.append('_csrf', '<?= htmlspecialchars($csrf ?? '', ENT_QUOTES, 'UTF-8') ?>');

          fetch('/listings/archive', {
            method: 'POST',
            body: formData
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              // Show success popup
              showSuccessPopup('Listing archived successfully!');
              
              // Update the listing status in DOM
              const listingCard = document.querySelector(`[data-listing-id="${listingId}"]`);
              if (listingCard) {
                const statusBadge = listingCard.closest('.listing-card').querySelector('.listing-status');
                if (statusBadge) {
                  statusBadge.className = 'listing-status status-archived';
                  statusBadge.textContent = 'Archived';
                }
              }
              location.reload();
            } else {
              showSuccessPopup('Failed to archive listing: ' + (data.message || 'Unknown error'));
            }
          })
          .catch(error => {
            console.error('Error:', error);
            showSuccessPopup('Failed to archive listing. Please try again.');
          });
        }
      );
    }

    function showConfirmPopup(title, message, onConfirm) {
      // Create popup element
      const popup = document.createElement('div');
      popup.style.cssText = `
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.2);
        z-index: 10000;
        text-align: center;
        min-width: 300px;
      `;
      
      popup.innerHTML = `
        <div style="color: #dc3545; font-size: 3rem; margin-bottom: 1rem;">
          <i class="fa fa-exclamation-triangle"></i>
        </div>
        <h3 style="margin: 0 0 0.5rem 0; color: #333;">${title}</h3>
        <p style="margin: 0 0 1.5rem 0; color: #666;">${message}</p>
        <div style="display: flex; gap: 1rem; justify-content: center;">
          <button id="confirmCancel" style="
            background: transparent;
            color: #333;
            border: 1px solid #ccc;
            padding: 10px 24px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
          ">Cancel</button>
          <button id="confirmYes" style="
            background: #dc3545;
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
          ">Delete</button>
        </div>
      `;
      
      // Add overlay
      const overlay = document.createElement('div');
      overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 9999;
      `;
      
      document.body.appendChild(overlay);
      document.body.appendChild(popup);
      
      // Handle button clicks
      document.getElementById('confirmCancel').onclick = function() {
        popup.remove();
        overlay.remove();
      };
      
      document.getElementById('confirmYes').onclick = function() {
        popup.remove();
        overlay.remove();
        onConfirm();
      };
      
      overlay.onclick = function() {
        popup.remove();
        overlay.remove();
      };
    }

    function showSuccessPopup(message) {
      // Create popup element
      const popup = document.createElement('div');
      popup.style.cssText = `
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 8px 32px rgba(0,0,0,0.2);
        z-index: 10000;
        text-align: center;
        min-width: 300px;
      `;
      
      popup.innerHTML = `
        <div style="color: #28a745; font-size: 3rem; margin-bottom: 1rem;">
          <i class="fa fa-check-circle"></i>
        </div>
        <h3 style="margin: 0 0 0.5rem 0; color: #333;">Success!</h3>
        <p style="margin: 0 0 1.5rem 0; color: #666;">${message}</p>
        <button onclick="this.parentElement.remove()" style="
          background: #3d6b3f;
          color: white;
          border: none;
          padding: 10px 24px;
          border-radius: 6px;
          cursor: pointer;
          font-size: 1rem;
        ">OK</button>
      `;
      
      // Add overlay
      const overlay = document.createElement('div');
      overlay.style.cssText = `
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 9999;
      `;
      overlay.onclick = function() {
        popup.remove();
        overlay.remove();
      };
      
      document.body.appendChild(overlay);
      document.body.appendChild(popup);
      
      // Auto-close after 2 seconds
      setTimeout(() => {
        if (document.body.contains(popup)) {
          popup.remove();
          overlay.remove();
        }
      }, 2000);
    }
  </script>
</body>
</html>
