<?php
$base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pending Listings - Admin - Ulimi</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    
    :root {
      --earth: #2B2A25;
      --leaf: #3D6B3F;
      --leaf-lt: #4F8A52;
      --crop: #C8A84B;
      --cream: #F5F0E8;
      --mist: #F9F6F0;
      --text-muted: #6B6558;
      --border: rgba(43,42,37,0.12);
    }
    
    body {
      font-family: 'DM Sans', sans-serif;
      background: var(--mist);
      color: var(--earth);
      line-height: 1.6;
      min-height: 100vh;
      padding-top: 68px;
    }
    
    .admin-container {
      max-width: 1400px;
      margin: 0 auto;
      padding: 2rem 32px;
    }
    
    .page-header {
      background: white;
      border-bottom: 1px solid var(--border);
      padding: 2rem 0;
      margin-bottom: 2rem;
    }
    
    .page-header h1 {
      font-family: 'DM Serif Display', serif;
      font-size: 2rem;
      color: var(--earth);
      margin-bottom: 0.5rem;
    }
    
    .page-header p {
      color: var(--text-muted);
      margin-bottom: 0;
    }
    
    .listings-table {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 16px rgba(43,42,37,0.08);
      border: 1px solid var(--border);
      overflow: hidden;
    }
    
    .listings-table table {
      width: 100%;
      border-collapse: collapse;
    }
    
    .listings-table th,
    .listings-table td {
      padding: 1rem;
      text-align: left;
      border-bottom: 1px solid var(--border);
    }
    
    .listings-table th {
      background: rgba(61,107,63,0.05);
      font-weight: 600;
      color: var(--earth);
    }
    
    .listings-table tr:last-child td {
      border-bottom: none;
    }
    
    .listings-table tr:hover {
      background: rgba(61,107,63,0.02);
    }
    
    .status-badge {
      display: inline-block;
      padding: 0.25rem 0.75rem;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 500;
    }
    
    .status-pending {
      background: rgba(255, 193, 7, 0.1);
      color: #ffc107;
    }
    
    .action-buttons {
      display: flex;
      gap: 0.5rem;
    }
    
    .btn-sm {
      padding: 0.5rem 1rem;
      border-radius: 6px;
      border: none;
      cursor: pointer;
      transition: all 0.3s;
      font-size: 0.85rem;
      font-weight: 500;
    }
    
    .btn-approve {
      background: var(--leaf);
      color: white;
    }
    
    .btn-approve:hover {
      background: var(--leaf-lt);
    }
    
    .btn-reject {
      background: #dc3545;
      color: white;
    }
    
    .btn-reject:hover {
      background: #c82333;
    }
    
    .loading-state,
    .empty-state {
      text-align: center;
      padding: 3rem;
      color: var(--text-muted);
    }
    
    .product-image {
      width: 60px;
      height: 60px;
      object-fit: cover;
      border-radius: 8px;
    }
    
    @media (max-width: 768px) {
      .container {
        padding: 1rem 16px;
      }
      
      .listings-table {
        overflow-x: auto;
      }
      
      .action-buttons {
        flex-direction: column;
      }
    }
  </style>
</head>
<body>
  <input type="hidden" name="_csrf" value="<?= htmlspecialchars(\App\Core\Csrf::token(), ENT_QUOTES, 'UTF-8') ?>">
  
  <div class="page-header">
    <div class="admin-container">
      <h1>Pending Listings</h1>
      <p>Review and approve product listings submitted by sellers</p>
    </div>
  </div>
  
  <div class="admin-container">
    <div class="listings-table">
      <div id="loadingState" class="loading-state">Loading listings...</div>
      <div id="listingsContent" style="display: none;">
        <table>
          <thead>
            <tr>
              <th>Image</th>
              <th>Title</th>
              <th>Seller</th>
              <th>Price</th>
              <th>Quantity</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="listingsTableBody"></tbody>
        </table>
      </div>
      <div id="emptyState" class="empty-state" style="display: none;">
        <p>No pending listings to review</p>
      </div>
    </div>
  </div>

  <script>
    const base = '<?= $base ?>';
    
    fetch(base + '/admin/pending-listings')
      .then(r => r.json())
      .then(data => {
        document.getElementById('loadingState').style.display = 'none';
        
        if (data.success && data.listings.length > 0) {
          document.getElementById('listingsContent').style.display = 'block';
          renderListings(data.listings);
        } else {
          document.getElementById('emptyState').style.display = 'block';
        }
      })
      .catch(error => {
        console.error('Error loading listings:', error);
        document.getElementById('loadingState').innerHTML = 'Error loading listings';
      });

    function renderListings(listings) {
      const tbody = document.getElementById('listingsTableBody');
      tbody.innerHTML = listings.map(l => `
        <tr>
          <td>
            ${l.image_path 
              ? `<img src="/${l.image_path}" class="product-image" alt="${l.title}">` 
              : '<div class="product-image" style="background: #e2e3e5; display: flex; align-items: center; justify-content: center;">No image</div>'
            }
          </td>
          <td>
            <strong>${l.title}</strong><br>
            <small>${l.commodity_name || 'N/A'}</small>
          </td>
          <td>${l.seller_name || 'Unknown'}</td>
          <td>MWK ${l.price_per_unit} / ${l.price_unit}</td>
          <td>${l.quantity_available} ${l.price_unit}</td>
          <td><span class="status-badge status-pending">${l.status}</span></td>
          <td>
            <div class="action-buttons">
              <button class="btn-sm btn-approve" onclick="approve(${l.id})">
                <i class="fa fa-check"></i> Approve
              </button>
              <button class="btn-sm btn-reject" onclick="reject(${l.id})">
                <i class="fa fa-times"></i> Reject
              </button>
            </div>
          </td>
        </tr>
      `).join('');
    }
    
    function approve(id) {
      if (!confirm('Are you sure you want to approve this listing?')) return;
      
      const formData = new FormData();
      formData.append('listing_id', id);
      formData.append('_csrf', document.querySelector('[name=_csrf]')?.value);
      
      fetch(base + '/listings/approve', { 
        method: 'POST', 
        body: formData
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          location.reload();
        } else {
          alert('Failed to approve listing: ' + (data.message || 'Unknown error'));
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Failed to approve listing. Please try again.');
      });
    }
    
    function reject(id) {
      if (!confirm('Are you sure you want to reject this listing?')) return;
      
      const formData = new FormData();
      formData.append('listing_id', id);
      formData.append('_csrf', document.querySelector('[name=_csrf]')?.value);
      
      fetch(base + '/listings/reject', { 
        method: 'POST', 
        body: formData
      })
      .then(r => r.json())
      .then(data => {
        if (data.success) {
          location.reload();
        } else {
          alert('Failed to reject listing: ' + (data.message || 'Unknown error'));
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Failed to reject listing. Please try again.');
      });
    }
  </script>
</body>
</html>