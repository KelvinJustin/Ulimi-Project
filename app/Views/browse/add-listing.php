<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Listing - Ulimi Agricultural Marketplace</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') ?>/assets/css/app.css">
  <link rel="stylesheet" href="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') ?>/assets/css/browse.css">
  <link rel="stylesheet" href="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '/') ?>/assets/css/add-listing.css">
  
  <!-- Meta tags -->
  <meta name="description" content="Add your agricultural products to Ulimi marketplace. Sell maize, ground nuts, soya, pigeon peas and more to buyers across Malawi.">
  <meta name="keywords" content="add listing, sell products, malawi agriculture, marketplace">
</head>
<body>
  <?php 
  $base = rtrim((string)\App\Core\Config::get('app.base_url', ''), '/');
  ?>

  <main class="add-listing-main">
    <div class="container">
      <!-- Page Header -->
      <section class="page-header-section">
        <div class="page-header">
          <div class="breadcrumb">
            <a href="<?= $base ?>/browse" class="breadcrumb-link">
              <i class="fas fa-arrow-left"></i>
              Back to Browse
            </a>
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-current">Add Listing</span>
          </div>
          
          <div class="page-title-section">
            <h1 class="page-title">Add New Product Listing</h1>
            <p class="page-subtitle">List your agricultural products for thousands of buyers across Malawi</p>
          </div>
        </div>
      </section>

      <!-- Add Listing Form -->
      <section class="form-section">
        <form id="addListingForm" class="add-listing-form" enctype="multipart/form-data">
          <!-- Basic Information -->
          <div class="form-section">
            <h2 class="form-section-title">
              <i class="fas fa-info-circle"></i>
              Basic Information
            </h2>
            
            <div class="form-grid">
              <div class="form-group">
                <label for="productName" class="form-label">
                  Product Name <span class="required">*</span>
                </label>
                <input 
                  type="text" 
                  id="productName" 
                  name="productName" 
                  class="form-input" 
                  placeholder="e.g., Premium White Maize"
                  required
                >
                <small class="form-help">Be specific about variety and quality</small>
              </div>

              <div class="form-group">
                <label for="category" class="form-label">
                  Category <span class="required">*</span>
                </label>
                <select id="category" name="category" class="form-select" required>
                  <option value="">Select a category</option>
                  <option value="grains">Grains & Cereals</option>
                  <option value="legumes">Legumes & Pulses</option>
                  <option value="vegetables">Vegetables</option>
                  <option value="fruits">Fruits</option>
                  <option value="cash-crops">Cash Crops</option>
                  <option value="livestock">Livestock</option>
                  <option value="inputs">Farm Inputs</option>
                  <option value="other">Other</option>
                </select>
              </div>

              <div class="form-group">
                <label for="quality" class="form-label">
                  Quality Grade <span class="required">*</span>
                </label>
                <select id="quality" name="quality" class="form-select" required>
                  <option value="">Select quality grade</option>
                  <option value="premium">Premium</option>
                  <option value="grade-a">Grade A</option>
                  <option value="grade-b">Grade B</option>
                  <option value="commercial">Commercial</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Description -->
          <div class="form-section">
            <h2 class="form-section-title">
              <i class="fas fa-align-left"></i>
              Description
            </h2>
            
            <div class="form-group">
              <label for="description" class="form-label">
                Product Description <span class="required">*</span>
              </label>
              <textarea 
                id="description" 
                name="description" 
                class="form-textarea" 
                rows="4" 
                placeholder="Describe your product in detail - include variety, growing conditions, harvesting methods, storage, etc."
                required
              ></textarea>
              <div class="char-count">
                <span id="charCount">0</span> / 500 characters
              </div>
              <small class="form-help">
                Good descriptions help buyers make informed decisions and can lead to faster sales
              </small>
            </div>
          </div>

          <!-- Pricing and Quantity -->
          <div class="form-section">
            <h2 class="form-section-title">
              <i class="fas fa-tag"></i>
              Pricing & Quantity
            </h2>
            
            <div class="form-grid">
              <div class="form-group">
                <label for="price" class="form-label">
                  Price per kg (MWK) <span class="required">*</span>
                </label>
                <div class="input-group">
                  <span class="input-prefix">MWK</span>
                  <input 
                    type="number" 
                    id="price" 
                    name="price" 
                    class="form-input" 
                    placeholder="0.00"
                    min="0"
                    step="0.01"
                    required
                  >
                </div>
                <small class="form-help">Set competitive prices based on current market rates</small>
              </div>

              <div class="form-group">
                <label for="quantity" class="form-label">
                  Available Quantity (kg) <span class="required">*</span>
                </label>
                <input 
                  type="number" 
                  id="quantity" 
                  name="quantity" 
                  class="form-input" 
                  placeholder="e.g., 500"
                  min="1"
                  required
                >
                <small class="form-help">Be realistic about available stock</small>
              </div>

              <div class="form-group">
                <label for="minOrder" class="form-label">
                  Minimum Order (kg)
                </label>
                <input 
                  type="number" 
                  id="minOrder" 
                  name="minOrder" 
                  class="form-input" 
                  placeholder="e.g., 50"
                  min="1"
                >
                <small class="form-help">Minimum quantity buyers can purchase</small>
              </div>
            </div>
          </div>

          <!-- Location -->
          <div class="form-section">
            <h2 class="form-section-title">
              <i class="fas fa-map-marker-alt"></i>
              Location
            </h2>
            
            <div class="form-grid">
              <div class="form-group">
                <label for="location" class="form-label">
                  Location <span class="required">*</span>
                </label>
                <select id="location" name="location" class="form-select" required>
                  <option value="">Select your location</option>
                  <option value="lilongwe">Lilongwe (Central)</option>
                  <option value="blantyre">Blantyre (Southern)</option>
                  <option value="mzuzu">Mzuzu (Northern)</option>
                  <option value="zomba">Zomba</option>
                  <option value="kasungu">Kasungu</option>
                  <option value="mangochi">Mangochi</option>
                  <option value="karonga">Karonga</option>
                  <option value="dedza">Dedza</option>
                  <option value="salima">Salima</option>
                  <option value="other">Other</option>
                </select>
                <small class="form-help">Helps buyers find products near them</small>
              </div>

              <div class="form-group">
                <label for="delivery" class="form-label">
                  Delivery Options
                </label>
                <div class="checkbox-group">
                  <label class="checkbox-label">
                    <input type="checkbox" id="deliveryAvailable" name="deliveryAvailable" value="true">
                    <span class="checkbox-custom"></span>
                    Delivery Available
                  </label>
                  <small class="form-help">Can you deliver to buyers?</small>
                </div>
              </div>
            </div>
          </div>

          <!-- Images -->
          <div class="form-section">
            <h2 class="form-section-title">
              <i class="fas fa-camera"></i>
              Product Images
            </h2>
            
            <div class="image-upload-section">
              <div class="image-upload-area" id="imageUploadArea">
                <div class="upload-placeholder">
                  <i class="fas fa-cloud-upload-alt"></i>
                  <h4>Drop images here or click to browse</h4>
                  <p>Upload up to 5 high-quality images (JPG, PNG)</p>
                  <p class="upload-specs">Maximum file size: 5MB per image</p>
                </div>
                
                <input 
                  type="file" 
                  id="productImages" 
                  name="productImages[]" 
                  class="file-input" 
                  accept="image/jpeg,image/jpg,image/png" 
                  multiple
                  style="display: none;"
                >
              </div>

              <div class="image-preview-container" id="imagePreviewContainer">
                <!-- Image previews will be added here -->
              </div>

              <div class="image-tips">
                <h5>Tips for great photos:</h5>
                <ul>
                  <li>Use good lighting and clear background</li>
                  <li>Show product from multiple angles</li>
                  <li>Include scale or size reference</li>
                  <li>Avoid blurry or dark images</li>
                  <li>Show actual product, not stock photos</li>
                </ul>
              </div>
            </div>
          </div>

          <!-- Additional Details -->
          <div class="form-section">
            <h2 class="form-section-title">
              <i class="fas fa-plus-circle"></i>
              Additional Details
            </h2>
            
            <div class="form-grid">
              <div class="form-group">
                <label for="certifications" class="form-label">
                  Certifications
                </label>
                <div class="checkbox-group">
                  <label class="checkbox-label">
                    <input type="checkbox" id="organic" name="certifications[]" value="organic">
                    <span class="checkbox-custom"></span>
                    Organic
                  </label>
                  <label class="checkbox-label">
                    <input type="checkbox" id="gap" name="certifications[]" value="gap">
                    <span class="checkbox-custom"></span>
                    Good Agricultural Practices (GAP)
                  </label>
                  <label class="checkbox-label">
                    <input type="checkbox" id="fairtrade" name="certifications[]" value="fairtrade">
                    <span class="checkbox-custom"></span>
                    Fair Trade
                  </label>
                </div>
              </div>

              <div class="form-group">
                <label for="harvestDate" class="form-label">
                  Harvest Date
                </label>
                <input 
                  type="date" 
                  id="harvestDate" 
                  name="harvestDate" 
                  class="form-input"
                >
                <small class="form-help">Helps buyers assess freshness</small>
              </div>

              <div class="form-group">
                <label for="shelfLife" class="form-label">
                  Shelf Life (days)
                </label>
                <input 
                  type="number" 
                  id="shelfLife" 
                  name="shelfLife" 
                  class="form-input" 
                  placeholder="e.g., 30"
                  min="1"
                >
                <small class="form-help">How long the product remains in good condition</small>
              </div>
            </div>
          </div>

          <!-- Form Actions -->
          <div class="form-actions">
            <div class="action-buttons">
              <button type="button" id="saveDraft" class="btn btn-outline">
                <i class="fas fa-save"></i>
                Save as Draft
              </button>
              <button type="button" id="previewListing" class="btn btn-outline">
                <i class="fas fa-eye"></i>
                Preview Listing
              </button>
              <button type="submit" class="btn btn-primary btn-large">
                <i class="fas fa-check"></i>
                Publish Listing
              </button>
            </div>
            
            <div class="form-progress" id="formProgress" style="display: none;">
              <div class="progress-bar">
                <div class="progress-fill" id="progressFill"></div>
              </div>
              <p class="progress-text">Uploading... <span id="progressPercent">0</span>%</p>
            </div>
          </div>
        </form>
      </section>

      <!-- Success Message (Hidden by default) -->
      <section class="success-section" id="successSection" style="display: none;">
        <div class="success-content">
          <div class="success-icon">
            <i class="fas fa-check-circle"></i>
          </div>
          <h2>Listing Published Successfully!</h2>
          <p>Your product listing is now live and visible to thousands of buyers across Malawi.</p>
          
          <div class="success-actions">
            <a href="<?= $base ?>/browse" class="btn btn-outline">
              <i class="fas fa-th"></i>
              Browse More Products
            </a>
            <a href="<?= $base ?>/dashboard?tab=listings" class="btn btn-primary">
              <i class="fas fa-list"></i>
              View My Listings
            </a>
          </div>
        </div>
      </section>
    </div>
  </main>

  <!-- Preview Modal -->
  <div id="previewModal" class="modal">
    <div class="modal-content modal-large">
      <div class="modal-header">
        <h3>Preview Listing</h3>
        <button type="button" id="closePreview" class="modal-close">
          <i class="fas fa-times"></i>
        </button>
      </div>
      
      <div class="modal-body">
        <div id="previewContent">
          <!-- Preview content will be generated here -->
        </div>
      </div>
    </div>
  </div>

  <?php require APP_PATH . '/Views/partials/footer-tailwind.php'; ?>

  <!-- JavaScript -->
  <script src="<?= rtrim((string)\App\Core\Config::get('app.base_url', ''), '') ?>/assets/js/add-listing.js"></script>
</body>
</html>
