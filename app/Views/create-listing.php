<?php
$base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '/');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title ?? 'Create Listing - Ulimi Agricultural Marketplace', ENT_QUOTES, 'UTF-8') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="<?= $base ?>/assets/css/app.css">
  <link rel="icon" type="image/png" href="<?= $base ?>/logo.png">
  
  <style>
    /* Create listing page styles */
    .create-listing-page {
      --listing-bg: var(--mist);
      --listing-card-bg: rgba(255,255,255,0.92);
      --listing-border: rgba(43,42,37,0.12);
      --listing-text: var(--earth);
      --listing-accent: var(--leaf);
      --listing-subtle: var(--text-muted);
      --listing-error: #dc3545;
      --listing-success: #28a745;
      background: var(--listing-bg);
      color: var(--listing-text);
      font-family: 'DM Sans', sans-serif;
      line-height: 1.6;
      min-height: 100vh;
    }

    .create-listing-container {
      max-width: 900px;
      margin: 0 auto;
      padding: 2rem;
    }

    .page-header {
      text-align: center;
      margin-bottom: 3rem;
    }

    .page-header h1 {
      font-family: 'DM Serif Display', serif;
      font-size: 2.5rem;
      margin-bottom: 1rem;
      color: var(--listing-text);
    }

    .page-header p {
      font-size: 1.1rem;
      color: var(--listing-subtle);
    }

    .create-listing-form {
      background: var(--listing-card-bg);
      border-radius: 16px;
      padding: 2.5rem;
      box-shadow: 0 8px 32px rgba(43,42,37,0.06);
      border: 1px solid var(--listing-border);
    }

    .form-section {
      margin-bottom: 2.5rem;
    }

    .form-section-title {
      font-family: 'DM Serif Display', serif;
      font-size: 1.5rem;
      margin-bottom: 1.5rem;
      color: var(--listing-text);
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .form-section-title i {
      color: var(--listing-accent);
      font-size: 1.2rem;
    }

    .form-row {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1.5rem;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      font-weight: 500;
      color: var(--listing-text);
    }

    .form-group label .required {
      color: var(--listing-error);
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
      width: 100%;
      padding: 14px 18px;
      border: 1px solid var(--listing-border);
      border-radius: 8px;
      font-size: 1rem;
      background: white;
      outline: none;
      transition: border-color 0.3s;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
      border-color: var(--listing-accent);
    }

    .form-group textarea {
      resize: vertical;
      min-height: 120px;
    }

    .form-group .help-text {
      font-size: 0.9rem;
      color: var(--listing-subtle);
      margin-top: 0.25rem;
    }

    .form-group .error {
      color: var(--listing-error);
      font-size: 0.9rem;
      margin-top: 0.25rem;
    }

    .image-upload-area {
      border: 2px dashed var(--listing-border);
      border-radius: 8px;
      padding: 2rem;
      text-align: center;
      cursor: pointer;
      transition: all 0.3s;
    }

    .image-upload-area:hover {
      border-color: var(--listing-accent);
      background: rgba(61,107,63,0.02);
    }

    .image-upload-area.has-image {
      border-style: solid;
      border-color: var(--listing-success);
    }

    .image-preview {
      max-width: 200px;
      max-height: 200px;
      margin: 1rem auto;
      border-radius: 8px;
      display: none;
    }

    .form-actions {
      display: flex;
      gap: 1rem;
      justify-content: flex-end;
      margin-top: 2rem;
      padding-top: 2rem;
      border-top: 1px solid var(--listing-border);
    }

    .btn {
      padding: 14px 32px;
      border-radius: 8px;
      font-weight: 500;
      text-decoration: none;
      border: none;
      cursor: pointer;
      transition: all 0.3s;
      font-size: 1rem;
    }

    .btn-primary {
      background: var(--listing-accent);
      color: white;
    }

    .btn-primary:hover {
      background: #3d6b3f;
    }

    .btn-secondary {
      background: transparent;
      color: var(--listing-text);
      border: 1px solid var(--listing-border);
    }

    .btn-secondary:hover {
      background: rgba(43,42,37,0.05);
    }

    .progress-indicator {
      display: flex;
      justify-content: space-between;
      margin-bottom: 2rem;
      position: relative;
    }

    .progress-step {
      flex: 1;
      text-align: center;
      position: relative;
    }

    .progress-step::before {
      content: '';
      position: absolute;
      top: 20px;
      left: 50%;
      width: 100%;
      height: 2px;
      background: var(--listing-border);
      z-index: 1;
    }

    .progress-step:last-child::before {
      display: none;
    }

    .progress-step.active::before {
      background: var(--listing-accent);
    }

    .progress-number {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: var(--listing-border);
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 0.5rem;
      position: relative;
      z-index: 2;
      font-weight: 600;
    }

    .progress-step.active .progress-number {
      background: var(--listing-accent);
    }

    .progress-step.completed .progress-number {
      background: var(--listing-success);
    }

    .progress-label {
      font-size: 0.9rem;
      color: var(--listing-subtle);
    }

    .progress-step.active .progress-label {
      color: var(--listing-text);
      font-weight: 500;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .create-listing-container {
        padding: 1rem;
      }

      .create-listing-form {
        padding: 1.5rem;
      }

      .form-row {
        grid-template-columns: 1fr;
      }

      .form-actions {
        flex-direction: column;
      }

      .page-header h1 {
        font-size: 2rem;
      }
    }

    /* Critical Breakpoint - Fix 531px/607px width issue */
    @media (max-width: 540px) {
      .create-listing-container {
        padding: 0.625rem 0.375rem;
      }

      .create-listing-form {
        padding: 1.125rem;
      }

      .page-header {
        padding: 1.375rem 0;
      }

      .page-header h1 {
        font-size: 1.875rem;
      }

      .page-header p {
        font-size: 0.95rem;
      }

      .form-group label {
        font-size: 0.875rem;
      }

      .form-group input,
      .form-group select,
      .form-group textarea {
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
      .create-listing-container {
        padding: 0.75rem 0.5rem;
      }

      .create-listing-form {
        padding: 1.25rem;
      }

      .page-header {
        padding: 1.5rem 0;
      }

      .page-header h1 {
        font-size: 1.75rem;
      }

      .page-header p {
        font-size: 1rem;
      }

      .form-group label {
        font-size: 0.9rem;
      }

      .form-group input,
      .form-group select,
      .form-group textarea {
        padding: 10px 14px;
        font-size: 0.95rem;
      }
    }

    /* Mobile phones - Extra small */
    @media (max-width: 480px) {
      .create-listing-form {
        padding: 1rem;
      }

      .page-header h1 {
        font-size: 1.5rem;
      }

      .page-header p {
        font-size: 0.95rem;
      }

      .form-actions {
        gap: 0.75rem;
      }

      .btn-primary,
      .btn-secondary {
        padding: 10px 20px;
        font-size: 0.9rem;
      }

      .form-group input,
      .form-group select,
      .form-group textarea {
        padding: 8px 12px;
        font-size: 0.9rem;
      }
    }

    /* Ultra small screens */
    @media (max-width: 360px) {
      .create-listing-container {
        padding: 0.5rem 0.25rem;
      }

      .create-listing-form {
        padding: 0.75rem;
      }

      .page-header h1 {
        font-size: 1.25rem;
      }

      .page-header p {
        font-size: 0.9rem;
      }

      .btn-primary,
      .btn-secondary {
        padding: 8px 16px;
        font-size: 0.85rem;
        width: 100%;
      }
    }
  </style>
</head>
<body class="create-listing-page">

  <div class="create-listing-container">
    <div class="page-header">
      <h1><?= !empty($isEdit) ? 'Edit Listing' : 'Create New Listing' ?></h1>
      <p><?= !empty($isEdit) ? 'Update your agricultural product listing' : 'List your agricultural products to reach thousands of buyers across Malawi' ?></p>
    </div>

    <form class="create-listing-form" method="post" action="<?= $base ?>/<?= !empty($isEdit) ? 'listings/edit/' . $listingId : 'create-listing' ?>" enctype="multipart/form-data" id="createListingForm">
      <input type="hidden" name="_csrf" value="<?= htmlspecialchars($csrf ?? '', ENT_QUOTES, 'UTF-8') ?>">
      <?php if (!empty($listingId)): ?>
        <input type="hidden" name="listing_id" value="<?= $listingId ?>">
      <?php endif; ?>
      
      <!-- Progress Indicator -->
      <div class="progress-indicator">
        <div class="progress-step active" data-step="1">
          <div class="progress-number">1</div>
          <div class="progress-label">Basic Info</div>
        </div>
        <div class="progress-step" data-step="2">
          <div class="progress-number">2</div>
          <div class="progress-label">Details</div>
        </div>
        <div class="progress-step" data-step="3">
          <div class="progress-number">3</div>
          <div class="progress-label">Pricing</div>
        </div>
        <div class="progress-step" data-step="4">
          <div class="progress-number">4</div>
          <div class="progress-label">Images</div>
        </div>
      </div>

      <!-- Basic Information Section -->
      <div class="form-section" data-section="1">
        <h2 class="form-section-title">
          <i class="fa fa-info-circle"></i>
          Basic Information
        </h2>
        
        <div class="form-row">
          <div class="form-group">
            <label for="title">Product Title <span class="required">*</span></label>
            <input type="text" id="title" name="title" 
                   value="<?= htmlspecialchars($old['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
                   placeholder="e.g., Premium Grade Maize - Harvest 2024" 
                   required>
            <div class="help-text">Give your product a clear, descriptive title</div>
            <?php if (!empty($errors['title'])): ?><div class="error"><?= htmlspecialchars($errors['title'], ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
          </div>
          
          <div class="form-group">
            <label for="category">Category <span class="required">*</span></label>
            <select id="category" name="category" required>
              <option value="">Select a category</option>
              <option value="grains" <?= ($old['category'] ?? '') === 'grains' ? 'selected' : '' ?>>Grains & Cereals</option>
              <option value="legumes" <?= ($old['category'] ?? '') === 'legumes' ? 'selected' : '' ?>>Legumes</option>
              <option value="vegetables" <?= ($old['category'] ?? '') === 'vegetables' ? 'selected' : '' ?>>Vegetables</option>
              <option value="fruits" <?= ($old['category'] ?? '') === 'fruits' ? 'selected' : '' ?>>Fruits</option>
              <option value="cash-crops" <?= ($old['category'] ?? '') === 'cash-crops' ? 'selected' : '' ?>>Cash Crops</option>
              <option value="livestock" <?= ($old['category'] ?? '') === 'livestock' ? 'selected' : '' ?>>Livestock</option>
              <option value="inputs" <?= ($old['category'] ?? '') === 'inputs' ? 'selected' : '' ?>>Farm Inputs</option>
            </select>
            <div class="help-text">Choose the best category for your product</div>
            <?php if (!empty($errors['category'])): ?><div class="error"><?= htmlspecialchars($errors['category'], ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
          </div>
        </div>

        <div class="form-group">
          <label for="description">Description <span class="required">*</span></label>
          <textarea id="description" name="description" 
                    placeholder="Describe your product in detail - quality, characteristics, growing conditions, etc." 
                    required><?= htmlspecialchars($old['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
          <div class="help-text">Minimum 10 characters. Be specific about quality, variety, and characteristics.</div>
          <?php if (!empty($errors['description'])): ?><div class="error"><?= htmlspecialchars($errors['description'], ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
        </div>
      </div>

      <!-- Details Section -->
      <div class="form-section" data-section="2">
        <h2 class="form-section-title">
          <i class="fa fa-map-marker"></i>
          Location & Quality
        </h2>
        
        <div class="form-row">
          <div class="form-group">
            <label for="location">Location (District) <span class="required">*</span></label>
            <select id="location" name="location" required>
              <option value="">Select your district</option>
              <optgroup label="Northern Region">
                <option value="chitipa" <?= ($old['location'] ?? '') === 'chitipa' ? 'selected' : '' ?>>Chitipa</option>
                <option value="karonga" <?= ($old['location'] ?? '') === 'karonga' ? 'selected' : '' ?>>Karonga</option>
                <option value="nkhata-bay" <?= ($old['location'] ?? '') === 'nkhata-bay' ? 'selected' : '' ?>>Nkhata Bay</option>
                <option value="rumphi" <?= ($old['location'] ?? '') === 'rumphi' ? 'selected' : '' ?>>Rumphi</option>
                <option value="mzimba" <?= ($old['location'] ?? '') === 'mzimba' ? 'selected' : '' ?>>Mzimba</option>
                <option value="likoma" <?= ($old['location'] ?? '') === 'likoma' ? 'selected' : '' ?>>Likoma</option>
              </optgroup>
              <optgroup label="Central Region">
                <option value="kasungu" <?= ($old['location'] ?? '') === 'kasungu' ? 'selected' : '' ?>>Kasungu</option>
                <option value="nkhotakota" <?= ($old['location'] ?? '') === 'nkhotakota' ? 'selected' : '' ?>>Nkhotakota</option>
                <option value="ntcheu" <?= ($old['location'] ?? '') === 'ntcheu' ? 'selected' : '' ?>>Ntcheu</option>
                <option value="ntchisi" <?= ($old['location'] ?? '') === 'ntchisi' ? 'selected' : '' ?>>Ntchisi</option>
                <option value="dedza" <?= ($old['location'] ?? '') === 'dedza' ? 'selected' : '' ?>>Dedza</option>
                <option value="dowa" <?= ($old['location'] ?? '') === 'dowa' ? 'selected' : '' ?>>Dowa</option>
                <option value="lilongwe" <?= ($old['location'] ?? '') === 'lilongwe' ? 'selected' : '' ?>>Lilongwe</option>
                <option value="mchinji" <?= ($old['location'] ?? '') === 'mchinji' ? 'selected' : '' ?>>Mchinji</option>
                <option value="salima" <?= ($old['location'] ?? '') === 'salima' ? 'selected' : '' ?>>Salima</option>
              </optgroup>
              <optgroup label="Southern Region">
                <option value="balaka" <?= ($old['location'] ?? '') === 'balaka' ? 'selected' : '' ?>>Balaka</option>
                <option value="blantyre" <?= ($old['location'] ?? '') === 'blantyre' ? 'selected' : '' ?>>Blantyre</option>
                <option value="chikwawa" <?= ($old['location'] ?? '') === 'chikwawa' ? 'selected' : '' ?>>Chikwawa</option>
                <option value="chiradzulu" <?= ($old['location'] ?? '') === 'chiradzulu' ? 'selected' : '' ?>>Chiradzulu</option>
                <option value="machinga" <?= ($old['location'] ?? '') === 'machinga' ? 'selected' : '' ?>>Machinga</option>
                <option value="mangochi" <?= ($old['location'] ?? '') === 'mangochi' ? 'selected' : '' ?>>Mangochi</option>
                <option value="mulanje" <?= ($old['location'] ?? '') === 'mulanje' ? 'selected' : '' ?>>Mulanje</option>
                <option value="mwanza" <?= ($old['location'] ?? '') === 'mwanza' ? 'selected' : '' ?>>Mwanza</option>
                <option value="neno" <?= ($old['location'] ?? '') === 'neno' ? 'selected' : '' ?>>Neno</option>
                <option value="nsanje" <?= ($old['location'] ?? '') === 'nsanje' ? 'selected' : '' ?>>Nsanje</option>
                <option value="phalombe" <?= ($old['location'] ?? '') === 'phalombe' ? 'selected' : '' ?>>Phalombe</option>
                <option value="thyolo" <?= ($old['location'] ?? '') === 'thyolo' ? 'selected' : '' ?>>Thyolo</option>
                <option value="zomba" <?= ($old['location'] ?? '') === 'zomba' ? 'selected' : '' ?>>Zomba</option>
              </optgroup>
            </select>
            <div class="help-text">Select the district where your product is located</div>
            <?php if (!empty($errors['location'])): ?><div class="error"><?= htmlspecialchars($errors['location'], ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
          </div>
          
          <div class="form-group">
            <label for="quality_grade">Quality Grade</label>
            <select id="quality_grade" name="quality_grade">
              <option value="">Select quality grade</option>
              <option value="premium" <?= ($old['quality_grade'] ?? '') === 'premium' ? 'selected' : '' ?>>Premium</option>
              <option value="standard" <?= ($old['quality_grade'] ?? '') === 'standard' ? 'selected' : '' ?>>Standard</option>
              <option value="basic" <?= ($old['quality_grade'] ?? '') === 'basic' ? 'selected' : '' ?>>Basic</option>
            </select>
            <div class="help-text">Optional: Grade your product quality</div>
          </div>
        </div>
      </div>

      <!-- Pricing Section -->
      <div class="form-section" data-section="3">
        <h2 class="form-section-title">
          <i class="fa fa-money"></i>
          Pricing & Quantity
        </h2>
        
        <div class="form-row">
          <div class="form-group">
            <label for="price">Price per Unit (MWK) <span class="required">*</span></label>
            <input type="number" id="price" name="price" 
                   value="<?= htmlspecialchars($old['price'] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
                   step="0.01" min="0.01" 
                   placeholder="0.00" 
                   required>
            <div class="help-text">Price per unit in Malawi Kwacha</div>
            <?php if (!empty($errors['price'])): ?><div class="error"><?= htmlspecialchars($errors['price'], ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
          </div>
          
          <div class="form-group">
            <label for="price_unit">Price Unit <span class="required">*</span></label>
            <select id="price_unit" name="price_unit" required>
              <option value="">Select unit</option>
              <option value="kg" <?= ($old['price_unit'] ?? '') === 'kg' ? 'selected' : '' ?>>Kilogram (kg)</option>
              <option value="bag" <?= ($old['price_unit'] ?? '') === 'bag' ? 'selected' : '' ?>>Bag (50kg)</option>
              <option value="ton" <?= ($old['price_unit'] ?? '') === 'ton' ? 'selected' : '' ?>>Ton (1000kg)</option>
              <option value="piece" <?= ($old['price_unit'] ?? '') === 'piece' ? 'selected' : '' ?>>Piece</option>
              <option value="liter" <?= ($old['price_unit'] ?? '') === 'liter' ? 'selected' : '' ?>>Liter</option>
            </select>
            <div class="help-text">Unit for pricing</div>
            <?php if (!empty($errors['price_unit'])): ?><div class="error"><?= htmlspecialchars($errors['price_unit'], ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
          </div>
        </div>
        
        <div class="form-row">
          <div class="form-group">
            <label for="quantity">Available Quantity <span class="required">*</span></label>
            <input type="number" id="quantity" name="quantity" 
                   value="<?= htmlspecialchars($old['quantity'] ?? '', ENT_QUOTES, 'UTF-8') ?>" 
                   step="0.01" min="0.01" 
                   placeholder="0.00" 
                   required>
            <div class="help-text">How much do you have available?</div>
            <?php if (!empty($errors['quantity'])): ?><div class="error"><?= htmlspecialchars($errors['quantity'], ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
          </div>
          
          <div class="form-group">
            <label for="min_order_quantity">Minimum Order Quantity</label>
            <input type="number" id="min_order_quantity" name="min_order_quantity" 
                   value="<?= htmlspecialchars($old['min_order_quantity'] ?? '1', ENT_QUOTES, 'UTF-8') ?>" 
                   step="0.01" min="0.01" 
                   placeholder="1.00">
            <div class="help-text">Minimum quantity buyers can order</div>
          </div>
        </div>
      </div>

      <!-- Images Section -->
      <div class="form-section" data-section="4">
        <h2 class="form-section-title">
          <i class="fa fa-camera"></i>
          Product Images
        </h2>
        
        <div class="form-group">
          <label for="product_image">Product Photo</label>
          <div class="image-upload-area" id="imageUploadArea">
            <input type="file" id="product_image" name="product_image" 
                   accept="image/jpeg,image/jpg,image/png" 
                   style="display: none;">
            <div class="upload-placeholder">
              <i class="fa fa-cloud-upload" style="font-size: 3rem; color: var(--listing-subtle); margin-bottom: 1rem;"></i>
              <p>Click to upload product image</p>
              <p class="help-text">JPG or PNG, maximum 5MB</p>
            </div>
            <img id="imagePreview" class="image-preview" alt="Product preview">
          </div>
          <?php if (!empty($existingImage)): ?>
            <div class="existing-image" style="margin-top: 1rem;">
              <p style="font-size: 0.9rem; color: var(--listing-subtle);">Current image:</p>
              <img src="/<?= htmlspecialchars($existingImage, ENT_QUOTES, 'UTF-8') ?>" 
                   alt="Current product image" 
                   style="max-width: 200px; max-height: 200px; border-radius: 8px; margin-top: 0.5rem;">
            </div>
          <?php endif; ?>
          <?php if (!empty($errors['product_image'])): ?><div class="error"><?= htmlspecialchars($errors['product_image'], ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
        </div>
      </div>

      <!-- Form Actions -->
      <div class="form-actions">
        <button type="button" class="btn btn-secondary" onclick="window.location.href='<?= $base ?>/dashboard'">
          Cancel
        </button>
        <button type="submit" class="btn btn-secondary" name="save_as_draft" value="1" id="draftBtn">
          <i class="fa fa-save"></i>
          Save as Draft
        </button>
        <button type="submit" class="btn btn-primary" id="submitBtn">
          <i class="fa fa-<?= !empty($isEdit) ? 'check-circle' : 'plus-circle' ?>"></i>
          <?= !empty($isEdit) ? 'Update Listing' : 'Submit for Approval' ?>
        </button>
      </div>
    </form>
  </div>

  <script>
    // Form validation and interaction
    document.addEventListener('DOMContentLoaded', function() {
      const form = document.getElementById('createListingForm');
      const imageUploadArea = document.getElementById('imageUploadArea');
      const fileInput = document.getElementById('product_image');
      const imagePreview = document.getElementById('imagePreview');
      const progressSteps = document.querySelectorAll('.progress-step');
      
      // Image upload handling
      imageUploadArea.addEventListener('click', function() {
        fileInput.click();
      });
      
      fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
          // Validate file
          const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
          const maxSize = 5 * 1024 * 1024; // 5MB
          
          if (!allowedTypes.includes(file.type)) {
            alert('Please upload a JPG or PNG image.');
            fileInput.value = '';
            return;
          }
          
          if (file.size > maxSize) {
            alert('Image size must be less than 5MB.');
            fileInput.value = '';
            return;
          }
          
          // Show preview
          const reader = new FileReader();
          reader.onload = function(e) {
            imagePreview.src = e.target.result;
            imagePreview.style.display = 'block';
            imageUploadArea.classList.add('has-image');
            document.querySelector('.upload-placeholder').style.display = 'none';
          };
          reader.readAsDataURL(file);
        }
      });
      
      // Progress indicator
      function updateProgress() {
        const sections = document.querySelectorAll('.form-section');
        const currentSection = Math.ceil(Array.from(sections).findIndex(section => {
          const inputs = section.querySelectorAll('input, select, textarea');
          return Array.from(inputs).some(input => input.value === '');
        }) / 2) + 1;
        
        progressSteps.forEach((step, index) => {
          step.classList.remove('active', 'completed');
          if (index < currentSection - 1) {
            step.classList.add('completed');
          } else if (index === currentSection - 1) {
            step.classList.add('active');
          }
        });
      }
      
      // Update progress on input change
      form.addEventListener('input', updateProgress);
      updateProgress();
      
      // Form submission
      form.addEventListener('submit', function(e) {
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Creating...';
        
        // Form will submit normally
      });
      
      // Real-time validation
      const requiredFields = form.querySelectorAll('[required]');
      requiredFields.forEach(field => {
        field.addEventListener('blur', function() {
          if (!field.value.trim()) {
            field.style.borderColor = 'var(--listing-error)';
          } else {
            field.style.borderColor = '';
          }
        });
      });
    });
  </script>
  
  <?php require APP_PATH . '/Views/partials/footer-tailwind.php'; ?>
</body>
</html>
