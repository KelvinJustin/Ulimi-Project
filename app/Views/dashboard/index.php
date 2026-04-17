<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title ?? 'Dashboard', ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="/assets/css/output.css">
  <style>
    /* CSS Reset and Variables */
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
      --radius:   10px;
      --font-head: 'DM Serif Display', Georgia, serif;
      --font-body: 'DM Sans', sans-serif;
      --transition: 0.28s cubic-bezier(0.4, 0, 0.2, 1);
    }

    html { 
      scroll-behavior: smooth; 
    }

    /* Dashboard scoped styles */
    .dashboard-page {
      --dashboard-bg: var(--mist);
      --dashboard-card-bg: rgba(255,255,255,0.92);
      --dashboard-border: rgba(43,42,37,0.12);
      --dashboard-text: var(--earth);
      --dashboard-accent: var(--leaf);
      --dashboard-subtle: var(--text-muted);
      background:
        repeating-conic-gradient(
          rgba(200,200,200,0.1) 0deg 90deg,
          rgba(180,180,180,0.05) 90deg 180deg
        ) 0 0 / 20px 20px,
        var(--dashboard-bg);
      color: var(--dashboard-text);
      font-family: 'DM Sans', sans-serif;
      line-height: 1.6;
      min-height: 100vh;
    }

    .dashboard-container {
      display: flex;
      min-height: 100vh;
    }

    /* Sidebar Navigation */
    .dashboard-nav {
      width: 260px;
      background: var(--dashboard-card-bg);
      border-right: 1px solid var(--dashboard-border);
      padding: 0;
      position: sticky;
      top: 0;
      height: 100vh;
      overflow-y: auto;
    }

    .dashboard-nav-header {
      padding: 1.5rem;
      border-bottom: 1px solid var(--dashboard-border);
      margin-bottom: 1.5rem;
      display: block;
      width: 100%;
      box-sizing: border-box;
    }

    .dashboard-nav-logo {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 1.5rem;
      font-weight: 700;
      color: var(--dashboard-accent);
      margin-bottom: 0.25rem;
    }

    .dashboard-nav-logo i {
      font-size: 1.25rem;
    }

    .dashboard-nav-subtitle {
      font-size: 0.85rem;
      color: var(--dashboard-subtle);
      font-weight: 500;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .dashboard-nav ul {
      list-style: none;
      padding: 0 1.5rem;
      margin: 0;
    }

    .dashboard-nav li {
      margin-bottom: 0.5rem;
    }

    .dashboard-nav a {
      display: block;
      padding: 12px 24px;
      color: var(--dashboard-text);
      text-decoration: none;
      font-weight: 500;
      transition: all 0.3s;
      border-left: 3px solid transparent;
    }

    .dashboard-nav a:hover,
    .dashboard-nav a.active {
      background: rgba(61,107,63,0.08);
      border-left-color: var(--dashboard-accent);
      color: var(--dashboard-accent);
    }

    /* Main Dashboard Area */
    .dashboard-main {
      flex: 1;
      padding: 2rem;
      overflow-y: auto;
    }

    .dashboard-header {
      margin-bottom: 2rem;
    }

    .dashboard-header-content {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 2rem;
      flex-wrap: wrap;
    }

    .dashboard-header h1 {
      font-family: 'DM Serif Display', serif;
      font-size: 2.5rem;
      font-weight: 400;
      margin-bottom: 0.5rem;
      color: var(--dashboard-text);
    }

    .dashboard-header p {
      color: var(--dashboard-subtle);
      font-size: 1.1rem;
      margin-bottom: 1.5rem;
    }

    .dashboard-header-actions {
      display: flex;
      gap: 1rem;
      align-items: center;
    }

    .listings-nav-btn {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 12px 20px;
      background: var(--dashboard-accent, #3d6b3f);
      color: white;
      text-decoration: none;
      border-radius: 8px;
      font-weight: 500;
      font-size: 0.95rem;
      transition: all 0.3s ease;
      border: none;
      cursor: pointer;
      white-space: nowrap;
    }

    .listings-nav-btn:hover {
      background: #2d5a2f;
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(61, 107, 63, 0.25);
    }

    .listings-nav-btn i {
      font-size: 1rem;
    }

    .add-listing-btn {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      padding: 12px 20px;
      background: var(--crop, #C8A84B);
      color: var(--charcoal, #1A1A16);
      text-decoration: none;
      border-radius: 8px;
      font-weight: 600;
      font-size: 0.95rem;
      transition: all 0.3s ease;
      border: none;
      cursor: pointer;
      white-space: nowrap;
      box-shadow: 0 2px 8px rgba(200, 168, 75, 0.2);
    }

    .add-listing-btn:hover {
      background: var(--crop-lt, #E8C96A);
      transform: translateY(-2px);
      box-shadow: 0 4px 16px rgba(200, 168, 75, 0.3);
    }

    .add-listing-btn i {
      font-size: 1rem;
    }

    .create-listing-btn {
      display: inline-block;
      padding: 12px 24px;
      background: var(--dashboard-accent);
      color: white;
      text-decoration: none;
      border-radius: 8px;
      font-weight: 500;
      transition: background 0.3s;
    }

    .create-listing-btn:hover {
      background: #3d6b3f;
    }

    /* Key Metrics */
    .key-metrics {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
      gap: 1.5rem;
      margin-bottom: 2rem;
    }
    
    .metric {
      background: var(--dashboard-card-bg);
      border: 1px solid var(--dashboard-border);
      border-radius: 16px;
      padding: 2rem;
      position: relative;
      overflow: hidden;
      transition: all 0.3s ease;
      cursor: pointer;
    }
    
    .metric::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, var(--dashboard-accent), var(--crop));
      transform: scaleX(0);
      transition: transform 0.3s ease;
    }
    
    .metric:hover {
      transform: translateY(-4px);
      box-shadow: 0 12px 32px rgba(0, 0, 0, 0.1);
      border-color: var(--dashboard-accent);
    }
    
    .metric:hover::before {
      transform: scaleX(1);
    }
    
    .metric-icon {
      width: 48px;
      height: 48px;
      background: linear-gradient(135deg, var(--dashboard-accent), var(--crop));
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 1.5rem;
      margin-bottom: 1rem;
    }
    
    .metric-value {
      font-size: 2.5rem;
      font-weight: 700;
      color: var(--dashboard-text);
      margin-bottom: 0.5rem;
      line-height: 1;
    }
    
    .metric-label {
      font-size: 0.9rem;
      font-weight: 500;
      color: var(--dashboard-subtle);
      margin-bottom: 1rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    
    .metric-change {
      font-size: 0.85rem;
      font-weight: 500;
      display: inline-flex;
      align-items: center;
      gap: 0.25rem;
      padding: 0.25rem 0.75rem;
      border-radius: 20px;
      background: rgba(61, 107, 63, 0.1);
      color: var(--dashboard-accent);
    }
    
    .metric-change.negative {
      background: rgba(220, 53, 69, 0.1);
      color: #dc3545;
    }

    /* Section Styles */
    .dashboard-section {
      background: var(--dashboard-card-bg);
      border-radius: 16px;
      padding: 2rem;
      box-shadow: 0 8px 32px rgba(43,42,37,0.06);
      border: 1px solid var(--dashboard-border);
      margin-bottom: 2rem;
    }

    .dashboard-section h2 {
      font-family: 'DM Serif Display', serif;
      font-size: 1.75rem;
      margin-bottom: 1.5rem;
      color: var(--dashboard-text);
    }

    /* Form Styles */
    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 500;
      color: var(--dashboard-text);
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
      width: 100%;
      padding: 12px 16px;
      border: 1px solid var(--dashboard-border);
      border-radius: 8px;
      font-size: 1rem;
      background: white;
      outline: none;
      transition: border-color 0.3s;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
      border-color: var(--dashboard-accent);
    }

    .form-group textarea {
      resize: vertical;
      min-height: 100px;
    }

    .submit-btn {
      background: var(--dashboard-accent);
      color: white;
      border: none;
      padding: 12px 24px;
      border-radius: 8px;
      font-size: 1rem;
      font-weight: 500;
      cursor: pointer;
      transition: background 0.3s;
    }

    .submit-btn:hover {
      background: #3d6b3f;
    }

    /* Table Styles */
    .data-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 1rem;
    }

    .data-table th,
    .data-table td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid var(--dashboard-border);
    }

    .data-table th {
      font-weight: 600;
      color: var(--dashboard-text);
      background: rgba(61,107,63,0.05);
    }

    .data-table tr:hover {
      background: rgba(61,107,63,0.02);
    }

    /* Status Badges */
    .status-badge {
      display: inline-block;
      padding: 4px 12px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 500;
    }

    .status-badge.active {
      background: rgba(40, 167, 69, 0.1);
      color: #28a745;
    }

    .status-badge.pending {
      background: rgba(255, 193, 7, 0.1);
      color: #ffc107;
    }

    .status-badge.completed {
      background: rgba(40, 167, 69, 0.1);
      color: #28a745;
    }

    .status-badge.sold {
      background: rgba(108, 117, 125, 0.1);
      color: #6c757d;
    }

    /* Action Buttons */
    .action-buttons {
      display: flex;
      gap: 0.5rem;
    }

    .action-btn {
      padding: 6px 12px;
      border: 1px solid var(--dashboard-border);
      background: white;
      color: var(--dashboard-text);
      border-radius: 6px;
      font-size: 0.85rem;
      cursor: pointer;
      transition: all 0.3s;
    }

    .action-btn:hover {
      background: var(--dashboard-accent);
      color: white;
      border-color: var(--dashboard-accent);
    }

    .action-btn.danger {
      color: #dc3545;
      border-color: #dc3545;
    }

    .action-btn.danger:hover {
      background: #dc3545;
      color: white;
    }

    /* Filters */
    .filters {
      display: flex;
      gap: 1rem;
      margin-bottom: 1.5rem;
      flex-wrap: wrap;
    }

    .filter-group {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .filter-group label {
      font-weight: 500;
      color: var(--dashboard-text);
    }

    .filter-group input,
    .filter-group select {
      padding: 8px 12px;
      border: 1px solid var(--dashboard-border);
      border-radius: 6px;
      font-size: 0.9rem;
    }

    /* Balance Cards */
    .balance-cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1.5rem;
      margin-bottom: 2rem;
    }

    .balance-card {
      background: linear-gradient(135deg, rgba(61,107,63,0.1) 0%, rgba(200,168,75,0.08) 100%);
      border-radius: 12px;
      padding: 1.5rem;
      text-align: center;
    }

    .balance-card h3 {
      font-size: 1rem;
      color: var(--dashboard-subtle);
      margin-bottom: 0.5rem;
    }

    .balance-card p {
      font-size: 2rem;
      font-weight: 600;
      color: var(--dashboard-accent);
      margin: 0;
    }

    /* Price Changes */
    .price-change {
      font-weight: 500;
    }

    .price-change.positive {
      color: #28a745;
    }

    .price-change.negative {
      color: #dc3545;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
      .dashboard-container {
        flex-direction: column;
      }

      .dashboard-nav {
        width: 100%;
        height: auto;
        position: relative;
        border-right: none;
        border-bottom: 1px solid var(--dashboard-border);
        padding: 1rem 0;
      }

      .dashboard-nav ul {
        display: flex;
        overflow-x: auto;
        padding: 0 1rem;
      }

      .dashboard-nav li {
        margin: 0;
        white-space: nowrap;
      }

      .dashboard-nav a {
        padding: 8px 16px;
        border-left: none;
        border-bottom: 3px solid transparent;
      }

      .dashboard-nav a:hover,
      .dashboard-nav a.active {
        border-left: none;
        border-bottom-color: var(--dashboard-accent);
      }

      .dashboard-main {
        padding: 1rem;
      }

      .dashboard-header h1 {
        font-size: 2rem;
      }

      .key-metrics {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
      }

      .filters {
        flex-direction: column;
      }

      .data-table {
        font-size: 0.85rem;
      }

      .data-table th,
      .data-table td {
        padding: 8px;
      }
    }

    /* Status Badge Styles */
    .status-badge {
      padding: 4px 8px;
      border-radius: 12px;
      font-size: 11px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .status-badge.active {
      background: #e8f5e8;
      color: #2e7d32;
    }

    .status-badge.pending {
      background: #fff3e0;
      color: #e65100;
    }

    .status-badge.completed {
      background: #e3f2fd;
      color: #1565c0;
    }

    .status-badge.processing {
      background: #f3e5f5;
      color: #7b1fa2;
    }

    .status-badge.sold {
      background: #fce4ec;
      color: #c2185b;
    }

    /* Action Buttons */
    .action-buttons {
      display: flex;
      gap: 0.5rem;
    }

    .action-btn {
      padding: 6px 12px;
      border: 1px solid var(--dashboard-border);
      border-radius: 6px;
      background: white;
      color: var(--dashboard-text);
      font-size: 12px;
      cursor: pointer;
      transition: all 0.2s;
    }

    .action-btn:hover {
      background: var(--dashboard-accent);
      color: white;
      border-color: var(--dashboard-accent);
    }

    .action-btn.danger {
      color: #dc3545;
      border-color: #dc3545;
    }

    .action-btn.danger:hover {
      background: #dc3545;
      color: white;
    }

    /* Balance Cards */
    .balance-cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
      margin-bottom: 2rem;
    }

    .balance-card {
      background: white;
      border-radius: 12px;
      padding: 1.5rem;
      box-shadow: 0 4px 16px rgba(43,42,37,0.06);
      border: 1px solid var(--dashboard-border);
      text-align: center;
    }

    .balance-card h3 {
      font-size: 0.9rem;
      font-weight: 500;
      color: var(--dashboard-subtle);
      margin-bottom: 0.5rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .balance-amount {
      font-size: 1.8rem;
      font-weight: 700;
      color: var(--dashboard-accent);
      margin: 0;
    }

    .balance-amount.pending {
      color: #e65100;
    }

    /* Product Grid for Buyers */
    .product-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 1.5rem;
      margin-top: 1.5rem;
    }

    .product-card {
      background: white;
      border-radius: 12px;
      padding: 1.5rem;
      box-shadow: 0 4px 16px rgba(43,42,37,0.06);
      border: 1px solid var(--dashboard-border);
      transition: transform 0.2s, box-shadow 0.2s;
    }

    .product-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(43,42,37,0.12);
    }

    .product-card h4 {
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--dashboard-text);
      margin-bottom: 0.5rem;
    }

    .product-card p {
      color: var(--dashboard-subtle);
      margin-bottom: 1rem;
      line-height: 1.5;
    }

    .product-card .price {
      font-size: 1.25rem;
      font-weight: 700;
      color: var(--dashboard-accent);
      margin-bottom: 1rem;
    }

    /* Browse Controls */
    .browse-controls {
      display: flex;
      gap: 1rem;
      margin-bottom: 1.5rem;
      flex-wrap: wrap;
    }

    .browse-controls .filter-group {
      flex: 1;
      min-width: 200px;
    }

    /* Data Table Styles */
    .data-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 1rem;
      background: white;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 2px 8px rgba(43,42,37,0.06);
    }

    .data-table th,
    .data-table td {
      padding: 12px 16px;
      text-align: left;
      border-bottom: 1px solid var(--dashboard-border);
    }

    .data-table th {
      background: var(--mist);
      font-weight: 600;
      color: var(--dashboard-text);
      font-size: 0.9rem;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .data-table tbody tr:hover {
      background: rgba(61,107,63,0.04);
    }

    .data-table tbody tr:last-child td {
      border-bottom: none;
    }

    /* Filters */
    .filters {
      display: flex;
      gap: 1rem;
      margin-bottom: 1.5rem;
      flex-wrap: wrap;
    }

    .filters .filter-group {
      flex: 1;
      min-width: 200px;
    }

    .filters .filter-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 500;
      color: var(--dashboard-text);
      font-size: 0.9rem;
    }

    .filters .filter-group select,
    .filters .filter-group input {
      width: 100%;
      padding: 8px 12px;
      border: 1px solid var(--dashboard-border);
      border-radius: 6px;
      font-size: 0.9rem;
      background: white;
    }

    /* Transaction Type Badges */
    .transaction-type {
      padding: 4px 8px;
      border-radius: 12px;
      font-size: 11px;
      font-weight: 600;
      text-transform: uppercase;
    }

    .transaction-type.sale {
      background: #e8f5e8;
      color: #2e7d32;
    }

    .transaction-type.purchase {
      background: #e3f2fd;
      color: #1565c0;
    }

    /* Price Changes */
    .price-change {
      font-weight: 600;
      font-size: 0.9rem;
    }

    .price-change.positive {
      color: #2e7d32;
    }

    .price-change.negative {
      color: #c62828;
    }

    /* Create Listing CTA Styles */
    .create-listing-cta {
      display: grid;
      grid-template-columns: 1fr auto;
      gap: 3rem;
      align-items: center;
      padding: 2rem;
      background: linear-gradient(135deg, rgba(74, 124, 78, 0.05) 0%, rgba(74, 124, 78, 0.1) 100%);
      border-radius: 12px;
      border: 1px solid rgba(74, 124, 78, 0.2);
    }

    .cta-content h3 {
      font-size: 1.5rem;
      font-weight: 600;
      color: var(--dashboard-text);
      margin-bottom: 1rem;
    }

    .cta-content p {
      color: var(--dashboard-subtle);
      margin-bottom: 1.5rem;
      line-height: 1.6;
    }

    .feature-list {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .feature-list li {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      margin-bottom: 0.75rem;
      color: var(--dashboard-text);
      font-size: 0.95rem;
    }

    .feature-list i {
      color: var(--dashboard-accent);
      font-size: 0.9rem;
      width: 16px;
    }

    .cta-action {
      text-align: center;
    }

    .btn-large {
      padding: 1rem 2rem;
      font-size: 1.1rem;
      font-weight: 600;
      border-radius: 8px;
      display: inline-flex;
      align-items: center;
      gap: 0.75rem;
      text-decoration: none;
      transition: all 0.3s;
      margin-bottom: 1rem;
    }

    .btn-large:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 24px rgba(74, 124, 78, 0.3);
    }

    .cta-note {
      font-size: 0.85rem;
      color: var(--dashboard-subtle);
      margin: 0;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
      .dashboard-container {
        flex-direction: column;
      }

      .dashboard-nav {
        width: 100%;
        height: auto;
        position: relative;
        padding: 1rem 0;
      }

      .dashboard-nav ul {
        display: flex;
        overflow-x: auto;
        padding: 0 1rem;
      }

      .dashboard-nav li {
        margin: 0;
        white-space: nowrap;
      }

      .dashboard-nav a {
        padding: 8px 16px;
        border-left: none;
        border-bottom: 3px solid transparent;
      }

      .dashboard-nav a:hover,
      .dashboard-nav a.active {
        border-left: none;
        border-bottom-color: var(--dashboard-accent);
      }

      .key-metrics {
        grid-template-columns: 1fr;
      }

      .balance-cards {
        grid-template-columns: 1fr;
      }

      .browse-controls {
        flex-direction: column;
      }

      .create-listing-cta {
        grid-template-columns: 1fr;
        gap: 2rem;
        text-align: center;
      }

      .cta-action {
        text-align: center;
      }

      .filters {
        flex-direction: column;
      }

      .data-table {
        font-size: 0.85rem;
      }

      .data-table th,
      .data-table td {
        padding: 8px;
      }
    }
  /* Settings Section Styles */
    .settings-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1.5rem;
      margin-bottom: 2rem;
    }
    
    .setting-category {
      background: var(--dashboard-card-bg, rgba(255, 255, 255, 0.05));
      border: 1px solid var(--dashboard-border, rgba(255, 255, 255, 0.1));
      border-radius: 12px;
      padding: 1.5rem;
    }
    
    .setting-category h3 {
      color: var(--dashboard-accent, #3d6b3f);
      margin-bottom: 1rem;
      font-size: 1.1rem;
      font-weight: 600;
    }
    
    .setting-item {
      margin-bottom: 1rem;
    }
    
    .setting-item label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 500;
      color: var(--dashboard-text, #333);
    }
    
    .setting-item input,
    .setting-item select,
    .setting-item textarea {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid var(--dashboard-border, #ddd);
      border-radius: 6px;
      background: white;
      font-size: 0.9rem;
    }
    
    .setting-item textarea {
      min-height: 80px;
      resize: vertical;
    }
    
    .checkbox-group {
      display: flex;
      flex-direction: column;
      gap: 0.75rem;
    }
    
    .checkbox-group label {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      font-size: 0.9rem;
      cursor: pointer;
      padding: 0.5rem;
      border-radius: 6px;
      transition: background-color 0.2s ease;
      position: relative;
    }
    
    .checkbox-group label:hover {
      background: rgba(61, 107, 63, 0.05);
    }
    
    .checkbox-group input[type="checkbox"] {
      margin: 0;
      appearance: none;
      width: 20px;
      height: 20px;
      border: 2px solid var(--dashboard-border, #ddd);
      border-radius: 4px;
      background: white;
      cursor: pointer;
      position: relative;
      transition: all 0.2s ease;
      flex-shrink: 0;
    }
    
    .checkbox-group input[type="checkbox"]:checked {
      background: var(--dashboard-accent, #3d6b3f);
      border-color: var(--dashboard-accent, #3d6b3f);
    }
    
    .checkbox-group input[type="checkbox"]:checked::after {
      content: '';
      position: absolute;
      top: 2px;
      left: 6px;
      width: 6px;
      height: 10px;
      border: solid white;
      border-width: 0 2px 2px 0;
      transform: rotate(45deg);
    }
    
    .checkbox-group input[type="checkbox"]:focus {
      outline: 2px solid var(--dashboard-accent, #3d6b3f);
      outline-offset: 2px;
    }
    
    .checkbox-group input[type="checkbox"]:hover {
      border-color: var(--dashboard-accent, #3d6b3f);
    }
    
    .settings-actions {
      display: flex;
      gap: 1rem;
      justify-content: flex-end;
      margin-top: 2rem;
    }
    
    .settings-actions .submit-btn {
      background: var(--dashboard-accent, #3d6b3f);
      color: white;
      border: none;
      padding: 0.75rem 1.5rem;
      border-radius: 6px;
      font-weight: 500;
      cursor: pointer;
      transition: background 0.3s;
    }
    
    .settings-actions .submit-btn:hover {
      background: #2d5a2f;
    }
    
    .settings-actions .action-btn {
      background: transparent;
      color: var(--dashboard-text, #333);
      border: 1px solid var(--dashboard-border, #ddd);
      padding: 0.75rem 1.5rem;
      border-radius: 6px;
      font-weight: 500;
      cursor: pointer;
      transition: all 0.3s;
    }
    
    .settings-actions .action-btn:hover {
      background: var(--dashboard-border, #ddd);
    }
    
    /* Modal Styles */
    .modal-overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.6);
      backdrop-filter: blur(4px);
    }
    
    .modal-content {
      position: relative;
      background: white;
      border-radius: 12px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      max-width: 500px;
      width: 90%;
      max-height: 80vh;
      overflow-y: auto;
      animation: modalSlideIn 0.3s ease-out;
    }
    
    @keyframes modalSlideIn {
      from {
        opacity: 0;
        transform: translateY(-50px) scale(0.95);
      }
      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }
    
    .modal-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 1.5rem;
      border-bottom: 1px solid #e0e0e0;
      background: linear-gradient(135deg, rgba(220, 53, 69, 0.1), rgba(220, 53, 69, 0.05));
      border-radius: 12px 12px 0 0;
    }
    
    .modal-header h3 {
      margin: 0;
      color: #dc3545;
      font-size: 1.3rem;
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    
    .modal-close {
      background: none;
      border: none;
      font-size: 1.5rem;
      color: #666;
      cursor: pointer;
      padding: 0.5rem;
      border-radius: 50%;
      width: 40px;
      height: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: all 0.2s ease;
    }
    
    .modal-close:hover {
      background: rgba(0, 0, 0, 0.1);
      color: #dc3545;
    }
    
    .modal-close:focus {
      outline: 2px solid #dc3545;
      outline-offset: 2px;
    }
    
    .modal-body {
      padding: 1.5rem;
    }
    
    .modal-warning {
      color: #333;
      margin-bottom: 1rem;
      line-height: 1.5;
    }
    
    .modal-warning strong {
      color: #dc3545;
    }
    
    .deletion-consequences {
      margin: 1rem 0;
      padding-left: 1.5rem;
      color: #555;
    }
    
    .deletion-consequences li {
      margin-bottom: 0.5rem;
      line-height: 1.4;
    }
    
    .modal-question {
      margin-top: 1.5rem;
      font-weight: 600;
      color: #333;
      font-size: 1.1rem;
    }
    
    .modal-actions {
      display: flex;
      gap: 1rem;
      padding: 1.5rem;
      border-top: 1px solid #e0e0e0;
      background: #f8f9fa;
      border-radius: 0 0 12px 12px;
    }
    
    .modal-btn {
      flex: 1;
      padding: 0.75rem 1.5rem;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s ease;
      text-transform: none;
      letter-spacing: normal;
    }
    
    .modal-btn-cancel {
      background: #6c757d;
      color: white;
    }
    
    .modal-btn-cancel:hover {
      background: #5a6268;
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(108, 117, 125, 0.3);
    }
    
    .modal-btn-danger {
      background: #dc3545;
      color: white;
    }
    
    .modal-btn-danger:hover {
      background: #c82333;
      transform: translateY(-1px);
      box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
    }
    
    .modal-btn:focus {
      outline: 2px solid #4a7c4e;
      outline-offset: 2px;
    }
    
    /* Compact Admin Footer Styles */
    footer {
      background: var(--dashboard-card-bg, rgba(255, 255, 255, 0.05));
      border-top: 1px solid var(--dashboard-border, rgba(255, 255, 255, 0.1));
      color: var(--dashboard-subtle, #666);
      margin-top: 2rem;
      padding: 1.5rem 0;
      font-size: 0.85rem;
    }
    
    .footer-content {
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
      gap: 1rem;
    }
    
    .footer-brand {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }
    
    .footer-brand .logo {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--dashboard-accent, #3d6b3f) !important;
      text-decoration: none;
    }
    
    .footer-brand .logo .logo-mark {
      width: 24px;
      height: 24px;
      background: var(--dashboard-accent, #3d6b3f);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    
    .footer-brand .logo .logo-mark svg {
      width: 14px;
      height: 14px;
      fill: white;
    }
    
    .footer-links {
      display: flex;
      gap: 1.5rem;
      align-items: center;
    }
    
    .footer-links a {
      color: var(--dashboard-subtle, #666);
      text-decoration: none;
      transition: color 0.2s;
      font-size: 0.85rem;
    }
    
    .footer-links a:hover {
      color: var(--dashboard-accent, #3d6b3f);
    }
    
    .footer-info {
      color: var(--dashboard-subtle, #666);
      font-size: 0.8rem;
    }

    /* Mobile Responsive */
    @media (max-width: 768px) {
      .dashboard-container {
        flex-direction: column;
      }
      
      .dashboard-nav {
        width: 100%;
        height: auto;
        position: relative;
        padding: 1rem 0;
      }
      
      .dashboard-main {
        padding: 1rem;
      }
      
      .dashboard-header-content {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
      }
      
      .dashboard-header-actions {
        flex-direction: column;
        width: 100%;
        gap: 0.75rem;
        justify-content: flex-start;
      }
      
      .listings-nav-btn {
        padding: 10px 16px;
        font-size: 0.9rem;
      }
      
      .add-listing-btn {
        padding: 10px 16px;
        font-size: 0.9rem;
      }
      
      .dashboard-header-actions a {
        width: 100%;
        justify-content: center;
      }
      
      .key-metrics {
        grid-template-columns: 1fr;
      }
      
      .footer-content {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
      }
      
      .footer-links {
        flex-direction: column;
        gap: 0.75rem;
      }
    }
    
    @media (max-width: 600px) {
      .modal-content {
        width: 95%;
        margin: 1rem;
      }
      
      .modal-header {
        padding: 1rem;
      }
      
      .modal-header h3 {
        font-size: 1.1rem;
      }
      
      .modal-body {
        padding: 1rem;
      }
      
      .modal-actions {
        flex-direction: column;
        padding: 1rem;
      }
      
      .modal-btn {
        width: 100%;
      }
    }
  </style>
</head>
<body class="dashboard-page">
  <?php require APP_PATH . '/Views/partials/header-tailwind.php'; ?>
  <?php
  $base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '/');
  $user = \App\Core\Auth::user();
  $userRole = $user['role'] ?? 'buyer';
  ?>

  <div class="dashboard-container">
    <!-- Sidebar Navigation -->
    <nav class="dashboard-nav">
      <div class="dashboard-nav-header">
        <div class="dashboard-nav-logo">
          <i class="fa fa-leaf"></i>
          <span>Ulimi</span>
        </div>
        <div class="dashboard-nav-subtitle">Admin Dashboard</div>
      </div>
      <ul>
        <li><a href="#dashboard" class="nav-link active">Dashboard</a></li>
        <li><a href="#users" class="nav-link">Users</a></li>
        <li><a href="#listings" class="nav-link">Listings</a></li>
        <li><a href="#orders" class="nav-link">Orders</a></li>
        <li><a href="#payments" class="nav-link">Payments</a></li>
        <li><a href="#settings" class="nav-link">Settings</a></li>
      </ul>
    </nav>

    <!-- Dashboard Main Area -->
    <div class="dashboard-main">
      <!-- Dashboard Section -->
      <section class="dashboard-section" id="dashboard">
        <div class="dashboard-header">
          <div class="dashboard-header-content">
            <div>
              <h1>Dashboard</h1>
              <p>Manage your agricultural marketplace and track performance.</p>
            </div>
            <div class="dashboard-header-actions">
              <a href="/admin/listings" class="listings-nav-btn">
                <i class="fa fa-check-circle"></i>
                Pending Approvals
              </a>
              <a href="/listings" class="listings-nav-btn">
                <i class="fa fa-list"></i>
                View Listings
              </a>
            </div>
          </div>
        </div>

        <!-- Key Metrics -->
        <div class="key-metrics">
          <div class="metric">
            <div class="metric-icon">
              <i class="fa fa-users"></i>
            </div>
            <div class="metric-value"><?= count($users ?? []) ?></div>
            <div class="metric-label">Total Users</div>
            <div class="metric-change">
              <i class="fa fa-arrow-up"></i>
              +12% this month
            </div>
          </div>
          <div class="metric">
            <div class="metric-icon">
              <i class="fa fa-list-alt"></i>
            </div>
            <div class="metric-value"><?= $totalListings ?? 0 ?></div>
            <div class="metric-label">Active Listings</div>
            <div class="metric-change">
              <i class="fa fa-arrow-up"></i>
              +8% this week
            </div>
          </div>
          <div class="metric">
            <div class="metric-icon">
              <i class="fa fa-shopping-cart"></i>
            </div>
            <div class="metric-value">12</div>
            <div class="metric-label">Total Orders</div>
            <div class="metric-change">
              <i class="fa fa-arrow-up"></i>
              +25% this month
            </div>
          </div>
          <div class="metric">
            <div class="metric-icon">
              <i class="fa fa-money"></i>
            </div>
            <div class="metric-value">15.7K</div>
            <div class="metric-label">Total Revenue (MWK)</div>
            <div class="metric-change">
              <i class="fa fa-arrow-up"></i>
              +18% this month
            </div>
          </div>
        </div>
      </section>

      <!-- Users Section -->
      <section class="dashboard-section" id="users">
        <div class="dashboard-header">
          <h2>Users</h2>
          <p>Manage all registered users in the marketplace.</p>
        </div>

        <div class="filters">
          <div class="filter-group">
            <label for="user-role">Role</label>
            <select id="user-role">
              <option value="">All Roles</option>
              <option value="admin">Admin</option>
              <option value="seller">Seller</option>
              <option value="buyer">Buyer</option>
            </select>
          </div>
          <div class="filter-group">
            <label for="user-search">Search</label>
            <input type="text" id="user-search" placeholder="Search users...">
          </div>
        </div>

        <table class="data-table">
          <thead>
            <tr>
              <th>Name</th>
              <th>Email</th>
              <th>Role</th>
              <th>Joined</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php if (isset($users) && count($users) > 0): ?>
              <?php foreach ($users as $user): ?>
                <tr>
                  <td><?= htmlspecialchars(ucfirst($user['display_name'] ?? 'Unknown'), ENT_QUOTES, 'UTF-8') ?></td>
                  <td><?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?></td>
                  <td><span class="status-badge active"><?= htmlspecialchars(ucfirst($user['role'] ?? 'Unknown'), ENT_QUOTES, 'UTF-8') ?></span></td>
                  <td><?= date('Y-m-d', strtotime($user['created_at'])) ?></td>
                  <td><span class="status-badge active">Active</span></td>
                  <td>
                    <div class="action-buttons">
                      <button class="action-btn" onclick="viewUser(<?= htmlspecialchars($user['id'], ENT_QUOTES, 'UTF-8') ?>, '<?= htmlspecialchars(ucfirst($user['display_name'] ?? 'Unknown'), ENT_QUOTES, 'UTF-8') ?>', '<?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?>', '<?= htmlspecialchars($user['role'] ?? 'Unknown', ENT_QUOTES, 'UTF-8') ?>', event)">View</button>
                      <button class="action-btn danger" onclick="deleteUser(<?= htmlspecialchars($user['id'], ENT_QUOTES, 'UTF-8') ?>, event)">Delete</button>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="6" style="text-align: center; padding: 2rem;">No users found</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </section>

      <!-- Listings Section -->
      <section class="dashboard-section" id="listings">
        <div class="dashboard-header">
          <h2>Listings</h2>
          <p>Manage all product listings in the marketplace.</p>
          <a href="<?= $base ?>/listings" class="create-listing-btn">View All Listings</a>
        </div>

        <div class="filters">
          <div class="filter-group">
            <label for="listing-status">Status</label>
            <select id="listing-status">
              <option value="">All Status</option>
              <option value="active">Active</option>
              <option value="pending">Pending</option>
              <option value="sold">Sold</option>
            </select>
          </div>
          <div class="filter-group">
            <label for="listing-category">Category</label>
            <select id="listing-category">
              <option value="">All Categories</option>
              <option value="cereals">Cereals</option>
              <option value="vegetables">Vegetables</option>
              <option value="legumes">Legumes</option>
            </select>
          </div>
        </div>

        <table class="data-table">
          <thead>
            <tr>
              <th>Product</th>
              <th>Seller</th>
              <th>Price</th>
              <th>Quantity</th>
              <th>Status</th>
              <th>Created</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Test Maize Listing</td>
              <td>Test Seller</td>
              <td>MWK 50.00/kg</td>
              <td>100 kg</td>
              <td><span class="status-badge active">Active</span></td>
              <td>2026-04-09</td>
              <td>
                <div class="action-buttons">
                  <button class="action-btn">View</button>
                  <button class="action-btn">Edit</button>
                  <button class="action-btn danger">Delete</button>
                </div>
              </td>
            </tr>
            <tr>
              <td>miludliufh</td>
              <td>Test Seller</td>
              <td>MWK 10.00/kg</td>
              <td>1000 kg</td>
              <td><span class="status-badge active">Active</span></td>
              <td>2026-04-09</td>
              <td>
                <div class="action-buttons">
                  <button class="action-btn">View</button>
                  <button class="action-btn">Edit</button>
                  <button class="action-btn danger">Delete</button>
                </div>
              </td>
            </tr>
            <tr>
              <td>werwerw</td>
              <td>Test Seller</td>
              <td>MWK 23,235.00/kg</td>
              <td>325,235 kg</td>
              <td><span class="status-badge active">Active</span></td>
              <td>2026-04-09</td>
              <td>
                <div class="action-buttons">
                  <button class="action-btn">View</button>
                  <button class="action-btn">Edit</button>
                  <button class="action-btn danger">Delete</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </section>

      <!-- Orders Section -->
      <section class="dashboard-section" id="orders">
        <div class="dashboard-header">
          <h2>Orders</h2>
          <p>Manage all customer orders and transactions.</p>
        </div>

        <div class="filters">
          <div class="filter-group">
            <label for="order-status">Status</label>
            <select id="order-status">
              <option value="">All Status</option>
              <option value="pending">Pending</option>
              <option value="processing">Processing</option>
              <option value="completed">Completed</option>
              <option value="cancelled">Cancelled</option>
            </select>
          </div>
          <div class="filter-group">
            <label for="order-date">Date Range</label>
            <select id="order-date">
              <option value="">All Time</option>
              <option value="today">Today</option>
              <option value="week">This Week</option>
              <option value="month">This Month</option>
            </select>
          </div>
        </div>

        <table class="data-table">
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Customer</th>
              <th>Product</th>
              <th>Amount</th>
              <th>Status</th>
              <th>Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>#ORD-001</td>
              <td>Test Buyer</td>
              <td>Test Maize Listing</td>
              <td>MWK 5,000.00</td>
              <td><span class="status-badge processing">Processing</span></td>
              <td>2026-04-09</td>
              <td>
                <div class="action-buttons">
                  <button class="action-btn">View</button>
                  <button class="action-btn">Update</button>
                </div>
              </td>
            </tr>
            <tr>
              <td>#ORD-002</td>
              <td>Test Buyer</td>
              <td>miludliufh</td>
              <td>MWK 10,000.00</td>
              <td><span class="status-badge completed">Completed</span></td>
              <td>2026-04-08</td>
              <td>
                <div class="action-buttons">
                  <button class="action-btn">View</button>
                  <button class="action-btn">Refund</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </section>

      <!-- Payments Section -->
      <section class="dashboard-section" id="payments">
        <div class="dashboard-header">
          <h2>Payments</h2>
          <p>Monitor all payment transactions and revenue.</p>
        </div>

        <div class="balance-cards">
          <div class="balance-card">
            <h3>Total Revenue</h3>
            <p class="balance-amount">MWK 15,680.00</p>
          </div>
          <div class="balance-card">
            <h3>Pending Payments</h3>
            <p class="balance-amount pending">MWK 2,340.00</p>
          </div>
          <div class="balance-card">
            <h3>This Month</h3>
            <p class="balance-amount">MWK 8,450.00</p>
          </div>
        </div>

        <div class="filters">
          <div class="filter-group">
            <label for="payment-status">Status</label>
            <select id="payment-status">
              <option value="">All Status</option>
              <option value="completed">Completed</option>
              <option value="pending">Pending</option>
              <option value="failed">Failed</option>
            </select>
          </div>
          <div class="filter-group">
            <label for="payment-method">Method</label>
            <select id="payment-method">
              <option value="">All Methods</option>
              <option value="mobile">Mobile Money</option>
              <option value="bank">Bank Transfer</option>
              <option value="cash">Cash</option>
            </select>
          </div>
        </div>

        <table class="data-table">
          <thead>
            <tr>
              <th>Transaction ID</th>
              <th>Customer</th>
              <th>Amount</th>
              <th>Method</th>
              <th>Status</th>
              <th>Date</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>#TXN-001</td>
              <td>Test Buyer</td>
              <td>MWK 5,000.00</td>
              <td>Mobile Money</td>
              <td><span class="status-badge completed">Completed</span></td>
              <td>2026-04-09</td>
              <td>
                <div class="action-buttons">
                  <button class="action-btn">View</button>
                  <button class="action-btn">Receipt</button>
                </div>
              </td>
            </tr>
            <tr>
              <td>#TXN-002</td>
              <td>Test Buyer</td>
              <td>MWK 10,000.00</td>
              <td>Bank Transfer</td>
              <td><span class="status-badge pending">Pending</span></td>
              <td>2026-04-08</td>
              <td>
                <div class="action-buttons">
                  <button class="action-btn">View</button>
                  <button class="action-btn">Verify</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </section>

      <!-- Settings Section -->
      <section class="dashboard-section" id="settings">
        <div class="dashboard-header">
          <h2>Settings</h2>
          <p>Configure your marketplace preferences and system settings.</p>
        </div>

        <div class="settings-grid">
          <div class="setting-category">
            <h3>General Settings</h3>
            <div class="setting-item">
              <label for="site-name">Site Name</label>
              <input type="text" id="site-name" value="Ulimi Agricultural Marketplace">
            </div>
            <div class="setting-item">
              <label for="site-description">Site Description</label>
              <textarea id="site-description">Connect farmers and buyers across Malawi</textarea>
            </div>
            <div class="setting-item">
              <label for="maintenance-mode">Maintenance Mode</label>
              <select id="maintenance-mode">
                <option value="off">Off</option>
                <option value="on">On</option>
              </select>
            </div>
          </div>

          <div class="setting-category">
            <h3>Payment Settings</h3>
            <div class="setting-item">
              <label for="currency">Default Currency</label>
              <select id="currency">
                <option value="MWK" selected>MWK (Malawian Kwacha)</option>
                <option value="USD">USD (US Dollar)</option>
              </select>
            </div>
            <div class="setting-item">
              <label for="payment-methods">Accepted Payment Methods</label>
              <div class="checkbox-group">
                <label><input type="checkbox" checked> Mobile Money</label>
                <label><input type="checkbox" checked> Bank Transfer</label>
                <label><input type="checkbox"> Cash on Delivery</label>
              </div>
            </div>
          </div>

          <div class="setting-category">
            <h3>Email Settings</h3>
            <div class="setting-item">
              <label for="admin-email">Admin Email</label>
              <input type="email" id="admin-email" value="admin@ulimi.com">
            </div>
            <div class="setting-item">
              <label for="email-notifications">Email Notifications</label>
              <div class="checkbox-group">
                <label><input type="checkbox" checked> New Orders</label>
                <label><input type="checkbox" checked> New Users</label>
                <label><input type="checkbox"> System Updates</label>
              </div>
            </div>
          </div>
        </div>

        <div class="settings-actions">
          <button class="submit-btn">Save Settings</button>
          <button class="action-btn">Reset to Defaults</button>
        </div>
      </section>
    </div>
  </div>

  <input type="hidden" name="_csrf" value="<?= htmlspecialchars(\App\Core\Csrf::token(), ENT_QUOTES, 'UTF-8') ?>">

  <!-- User Details Modal -->
  <div id="userModal" class="modal" style="display: none;">
    <div class="modal-content">
      <div class="modal-header">
        <h2>User Details</h2>
        <button class="close-modal" onclick="closeUserModal()">&times;</button>
      </div>
      <div class="modal-body" id="userModalBody">
        Loading user details...
      </div>
    </div>
  </div>

  <style>
    .modal {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      display: flex;
      align-items: center;
      justify-content: center;
      z-index: 1000;
    }

    .modal-content {
      background: white;
      border-radius: 12px;
      max-width: 500px;
      width: 90%;
      max-height: 80vh;
      overflow-y: auto;
    }

    .modal-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 1.5rem;
      border-bottom: 1px solid #eee;
    }

    .modal-header h2 {
      margin: 0;
      font-size: 1.5rem;
      color: #333;
    }

    .close-modal {
      background: none;
      border: none;
      font-size: 2rem;
      cursor: pointer;
      color: #999;
      padding: 0;
      line-height: 1;
    }

    .close-modal:hover {
      color: #333;
    }

    .modal-body {
      padding: 1.5rem;
    }

    .user-detail-row {
      display: flex;
      padding: 0.75rem 0;
      border-bottom: 1px solid #eee;
    }

    .user-detail-label {
      font-weight: 600;
      color: #555;
      width: 150px;
      flex-shrink: 0;
    }

    .user-detail-value {
      color: #333;
    }
  </style>

  <?php require APP_PATH . '/Views/partials/admin_footer.php'; ?>

  <script>
    const base = '<?= $base ?>';

    // View user functionality
    function viewUser(userId, displayName, email, role, event) {
      event.preventDefault();

      const modal = document.getElementById('userModal');
      const modalBody = document.getElementById('userModalBody');

      modal.style.display = 'flex';
      modalBody.innerHTML = `
        <div class="user-detail-row">
          <span class="user-detail-label">ID:</span>
          <span class="user-detail-value">${userId}</span>
        </div>
        <div class="user-detail-row">
          <span class="user-detail-label">Name:</span>
          <span class="user-detail-value">${displayName}</span>
        </div>
        <div class="user-detail-row">
          <span class="user-detail-label">Email:</span>
          <span class="user-detail-value">${email}</span>
        </div>
        <div class="user-detail-row">
          <span class="user-detail-label">Role:</span>
          <span class="user-detail-value">${role.charAt(0).toUpperCase() + role.slice(1)}</span>
        </div>
      `;
    }

    function closeUserModal() {
      document.getElementById('userModal').style.display = 'none';
    }

    // Delete user functionality
    function deleteUser(userId, event) {
      event.preventDefault();

      if (!confirm('Are you sure you want to delete this user? This action cannot be undone.')) return;

      const formData = new FormData();
      formData.append('user_ids[]', userId);
      formData.append('_csrf', document.querySelector('[name=_csrf]')?.value);

      fetch(base + '/admin/delete-all', {
        method: 'POST',
        body: formData
      })
      .then(r => r.text())
      .then(data => {
        alert('User deleted successfully');
        location.reload();
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Failed to delete user. Please try again.');
      });
    }

    // Close modal when clicking outside
    document.getElementById('userModal').addEventListener('click', function(event) {
      if (event.target === this) {
        closeUserModal();
      }
    });
  </script>

  <?php if ($userRole === 'seller'): ?>
  <script>
    // Load seller's products when page loads
    document.addEventListener('DOMContentLoaded', function() {
      loadSellerProducts();
    });

    async function loadSellerProducts() {
      try {
        const response = await fetch('/api/seller-products.php');
        const data = await response.json();
        
        if (data.success) {
          renderSellerProducts(data.products);
        } else {
          console.error('Failed to load products:', data.message);
          renderEmptySellerProducts();
        }
      } catch (error) {
        console.error('Error loading products:', error);
        renderEmptySellerProducts();
      }
    }

    function renderSellerProducts(products) {
      const productsList = document.getElementById('sellerProductsList');
      if (!productsList) return;

      if (products.length === 0) {
        renderEmptySellerProducts();
        return;
      }

      const productsHTML = products.map(product => `
        <tr>
          <td>
            <div class="product-info">
              <strong>${escapeHtml(product.title)}</strong>
              <br>
              <small class="text-muted">${escapeHtml(product.category)}</small>
            </div>
          </td>
          <td>${product.quantity} ${product.price_unit}</td>
          <td>MWK ${product.price.toFixed(2)}/${product.price_unit}</td>
          <td>
            <span class="status-badge ${product.status}">${capitalizeFirst(product.status)}</span>
          </td>
          <td>
            <div class="action-buttons">
              <button class="action-btn" onclick="editProduct(${product.id})">Edit</button>
              <button class="action-btn danger" onclick="deleteProduct(${product.id})">Delete</button>
            </div>
          </td>
        </tr>
      `).join('');

      productsList.innerHTML = productsHTML;
    }

    function renderEmptySellerProducts() {
      const productsList = document.getElementById('sellerProductsList');
      if (!productsList) return;

      productsList.innerHTML = `
        <tr>
          <td colspan="5" style="text-align: center; padding: 2rem;">
            <p>You haven't created any listings yet.</p>
            <a href="/create-listing" class="btn btn-primary">Create Your First Listing</a>
          </td>
        </tr>
      `;
    }

    function escapeHtml(text) {
      const div = document.createElement('div');
      div.textContent = text;
      return div.innerHTML;
    }

    function capitalizeFirst(str) {
      return str.charAt(0).toUpperCase() + str.slice(1);
    }

    function editProduct(productId) {
      // Placeholder for edit functionality
      console.log('Edit product:', productId);
      // TODO: Implement edit functionality
    }

    function deleteProduct(productId) {
      if (confirm('Are you sure you want to delete this listing?')) {
        // Placeholder for delete functionality
        console.log('Delete product:', productId);
        // TODO: Implement delete functionality
      }
    }
  </script>
  <?php endif; ?>
</body>
</html>
