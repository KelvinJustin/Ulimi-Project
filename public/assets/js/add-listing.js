/**
 * Ulimi Agricultural Marketplace - Add Listing Page JavaScript
 * Handles product listing creation, image upload, form validation, and preview
 * Optimized for low-bandwidth environments and mobile-first design
 */

// ===================================
   GLOBAL VARIABLES AND STATE
   ===================================
const state = {
    formData: {
        productName: '',
        category: '',
        quality: '',
        description: '',
        price: '',
        quantity: '',
        minOrder: '',
        location: '',
        deliveryAvailable: false,
        certifications: [],
        harvestDate: '',
        shelfLife: ''
    },
    uploadedImages: [],
    isPreviewMode: false,
    isSubmitting: false
};

// DOM Elements
const elements = {
    form: null,
    productName: null,
    category: null,
    quality: null,
    description: null,
    price: null,
    quantity: null,
    minOrder: null,
    location: null,
    deliveryAvailable: null,
    organic: null,
    gap: null,
    fairtrade: null,
    harvestDate: null,
    shelfLife: null,
    productImages: null,
    imageUploadArea: null,
    imagePreviewContainer: null,
    previewModal: null,
    previewContent: null,
    successSection: null,
    formProgress: null,
    progressFill: null,
    progressPercent: null,
    charCount: null
};

// ===================================
   UTILITY FUNCTIONS
   ===================================

/**
 * Show notification
 * @param {string} message - Notification message
 * @param {string} type - Notification type ('success', 'error', 'info', 'warning')
 */
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'}"></i>
        <span>${message}</span>
    `;
    
    // Add notification styles if not already added
    if (!document.getElementById('notification-styles')) {
        const styles = document.createElement('style');
        styles.id = 'notification-styles';
        styles.textContent = `
            .notification {
                position: fixed;
                top: 20px;
                right: 20px;
                background: var(--white);
                padding: 1rem 1.5rem;
                border-radius: var(--radius-md);
                box-shadow: var(--shadow-lg);
                z-index: 10000;
                display: flex;
                align-items: center;
                gap: 0.75rem;
                transform: translateX(400px);
                transition: transform 0.3s ease;
                max-width: 400px;
            }
            
            .notification.show {
                transform: translateX(0);
            }
            
            .notification-success {
                border-left: 4px solid var(--success-green);
            }
            
            .notification-error {
                border-left: 4px solid var(--error-red);
            }
            
            .notification-warning {
                border-left: 4px solid var(--warning-yellow);
            }
            
            .notification-info {
                border-left: 4px solid #17a2b8;
            }
            
            .notification i {
                font-size: 1.25rem;
                flex-shrink: 0;
            }
            
            .notification-success i {
                color: var(--success-green);
            }
            
            .notification-error i {
                color: var(--error-red);
            }
            
            .notification-warning i {
                color: var(--warning-yellow);
            }
            
            .notification-info i {
                color: #17a2b8;
            }
        `;
        document.head.appendChild(styles);
    }
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => notification.classList.add('show'), 100);
    
    // Auto remove after 4 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 4000);
}

/**
 * Validate form field
 * @param {string} fieldName - Field name to validate
 * @returns {boolean} - Whether field is valid
 */
function validateField(fieldName) {
    const field = elements[fieldName];
    const value = state.formData[fieldName];
    
    switch (fieldName) {
        case 'productName':
            return value.trim().length >= 3 && value.trim().length <= 100;
        case 'category':
        case 'quality':
        case 'location':
            return value !== '';
        case 'description':
            return value.trim().length >= 20 && value.trim().length <= 500;
        case 'price':
            const price = parseFloat(value);
            return !isNaN(price) || price > 0;
        case 'quantity':
        case 'minOrder':
            const qty = parseInt(value);
            return !isNaN(qty) && qty > 0;
        default:
            return true;
    }
}

/**
 * Validate entire form
 * @returns {boolean} - Whether form is valid
 */
function validateForm() {
    const requiredFields = ['productName', 'category', 'quality', 'description', 'price', 'quantity', 'location'];
    const errors = [];
    
    requiredFields.forEach(field => {
        if (!validateField(field)) {
            errors.push(`${field} is required`);
        }
    });
    
    if (errors.length > 0) {
        showNotification('Please fix the following errors: ' + errors.join(', '), 'error');
        return false;
    }
    
    return true;
}

/**
 * Format price for display
 * @param {number} price - Price to format
 * @returns {string} - Formatted price string
 */
function formatPrice(price) {
    return new Intl.NumberFormat('en-MW', {
        style: 'currency',
        currency: 'MWK',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(price);
}

/**
 * Generate preview HTML from form data
 * @returns {string} - Preview HTML
 */
function generatePreviewHTML() {
    const data = state.formData;
    const certifications = [];
    if (data.organic) certifications.push('Organic');
    if (data.gap) certifications.push('GAP');
    if (data.fairtrade) certifications.push('Fair Trade');
    
    return `
        <div class="product-preview">
            <div class="preview-image-section">
                ${state.uploadedImages.length > 0 ? 
                    state.uploadedImages.map((img, index) => 
                        `<img src="${img.url}" alt="Preview ${index + 1}" class="preview-image">`
                    ).join('') :
                    '<div class="preview-placeholder"><i class="fas fa-image"></i><p>No images uploaded</p></div>'
                }
            </div>
            <div class="preview-details">
                <h3>${data.productName || 'Untitled Product'}</h3>
                <div class="preview-meta">
                    <span class="preview-category">${data.category || 'No category'}</span>
                    <span class="preview-quality">${data.quality || 'No quality'}</span>
                </div>
                <div class="preview-description">
                    ${data.description || 'No description provided'}
                </div>
                <div class="preview-pricing">
                    <div class="preview-price">${formatPrice(data.price || 0)}</div>
                    <div class="preview-quantity">${data.quantity || 0} kg available</div>
                </div>
                <div class="preview-location">
                    <i class="fas fa-map-marker-alt"></i>
                    ${data.location || 'No location'}
                </div>
                ${certifications.length > 0 ? 
                    `<div class="preview-certifications">
                        ${certifications.map(cert => `<span class="preview-cert">${cert}</span>`).join('')}
                    </div>` : ''
                }
            </div>
        </div>
    `;
}

// ===================================
   IMAGE UPLOAD FUNCTIONALITY
   ===================================

/**
 * Handle image file selection
 * @param {Event} event - File input change event
 */
function handleImageSelect(event) {
    const files = Array.from(event.target.files);
    
    if (files.length + state.uploadedImages.length > 5) {
        showNotification('Maximum 5 images allowed', 'warning');
        return;
    }
    
    files.forEach(file => {
        if (!file.type.startsWith('image/')) {
            showNotification('Only image files are allowed', 'error');
            return;
        }
        
        if (file.size > 5 * 1024 * 1024) { // 5MB
            showNotification('File size must be less than 5MB', 'error');
            return;
        }
        
        const reader = new FileReader();
        reader.onload = (e) => {
            const imageData = {
                name: file.name,
                url: e.target.result,
                size: file.size,
                type: file.type
            };
            
            state.uploadedImages.push(imageData);
            addImagePreview(imageData);
        };
        
        reader.readAsDataURL(file);
    });
}

/**
 * Add image preview
 * @param {Object} imageData - Image data object
 */
function addImagePreview(imageData) {
    const previewContainer = elements.imagePreviewContainer;
    
    const preview = document.createElement('div');
    preview.className = 'image-preview';
    preview.innerHTML = `
        <img src="${imageData.url}" alt="${imageData.name}">
        <div class="image-preview-actions">
            <button type="button" class="image-preview-btn" onclick="removeImage(${state.uploadedImages.length - 1})" title="Remove">
                <i class="fas fa-times"></i>
            </button>
            <button type="button" class="image-preview-btn" onclick="moveImage(${state.uploadedImages.length - 1}, -1)" title="Move left">
                <i class="fas fa-arrow-left"></i>
            </button>
            <button type="button" class="image-preview-btn" onclick="moveImage(${state.uploadedImages.length - 1}, 1)" title="Move right">
                <i class="fas fa-arrow-right"></i>
            </button>
        </div>
    `;
    
    previewContainer.appendChild(preview);
}

/**
 * Remove uploaded image
 * @param {number} index - Image index
 */
function removeImage(index) {
    state.uploadedImages.splice(index, 1);
    updateImagePreviews();
}

/**
 * Move image position
 * @param {number} index - Image index
 * @param {number} direction - Direction (-1 for left, 1 for right)
 */
function moveImage(index, direction) {
    const newIndex = index + direction;
    if (newIndex >= 0 && newIndex < state.uploadedImages.length) {
        const temp = state.uploadedImages[index];
        state.uploadedImages[index] = state.uploadedImages[newIndex];
        state.uploadedImages[newIndex] = temp;
        updateImagePreviews();
    }
}

/**
 * Update all image previews
 */
function updateImagePreviews() {
    const container = elements.imagePreviewContainer;
    container.innerHTML = '';
    state.uploadedImages.forEach((imageData, index) => {
        addImagePreview(imageData);
    });
}

// ===================================
   FORM HANDLING
   ===================================

/**
 * Update form data state
 * @param {Event} event - Input event
 */
function updateFormData(event) {
    const { name, value, type, checked } = event.target;
    
    if (type === 'checkbox') {
        state.formData[name] = checked;
    } else if (name === 'certifications[]') {
        // Handle certification checkboxes
        const certValue = value;
        if (checked) {
            if (!state.formData.certifications.includes(certValue)) {
                state.formData.certifications.push(certValue);
            }
        } else {
            const index = state.formData.certifications.indexOf(certValue);
            if (index > -1) {
                state.formData.certifications.splice(index, 1);
            }
        }
    } else {
        state.formData[name] = value;
    }
    
    // Update character count for description
    if (name === 'description') {
        elements.charCount.textContent = value.length;
    }
}

/**
 * Setup drag and drop for image upload
 */
function setupDragAndDrop() {
    const uploadArea = elements.imageUploadArea;
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        uploadArea.addEventListener(eventName, (e) => {
            e.preventDefault();
            e.stopPropagation();
        });
    });
    
    uploadArea.addEventListener('drop', (e) => {
        const files = Array.from(e.dataTransfer.files);
        handleImageSelect({ target: { files } });
    });
    
    uploadArea.addEventListener('dragover', () => {
        uploadArea.classList.add('drag-over');
    });
    
    uploadArea.addEventListener('dragleave', () => {
        uploadArea.classList.remove('drag-over');
    });
}

/**
 * Show/hide form progress
 * @param {boolean} show - Whether to show progress
 * @param {number} percent - Progress percentage
 */
function setFormProgress(show, percent = 0) {
    const progressSection = elements.formProgress;
    const progressFill = elements.progressFill;
    const progressPercent = elements.progressPercent;
    
    if (show) {
        progressSection.style.display = 'block';
        progressFill.style.width = `${percent}%`;
        progressPercent.textContent = percent;
    } else {
        progressSection.style.display = 'none';
        progressFill.style.width = '0%';
        progressPercent.textContent = '0';
    }
}

/**
 * Handle form submission
 * @param {Event} event - Submit event
 */
async function handleFormSubmit(event) {
    event.preventDefault();
    
    if (state.isSubmitting) {
        showNotification('Please wait for the current submission to complete', 'warning');
        return;
    }
    
    if (!validateForm()) {
        return;
    }
    
    state.isSubmitting = true;
    setFormProgress(true, 10);
    
    try {
        // Simulate API call - replace with actual fetch
        await new Promise(resolve => setTimeout(resolve, 2000));
        
        // In production, this would be:
        // const formData = new FormData(elements.form);
        // state.uploadedImages.forEach((img, index) => {
        //     formData.append(`images[${index}]`, img.file);
        // });
        // 
        // const response = await fetch('/api/listings', {
        //     method: 'POST',
        //     body: formData
        // });
        // 
        // if (response.ok) {
        //     showSuccessState();
        // } else {
        //     throw new Error('Failed to create listing');
        // }
        
        showSuccessState();
        
    } catch (error) {
        console.error('Error submitting form:', error);
        showNotification('Failed to create listing. Please try again.', 'error');
    } finally {
        state.isSubmitting = false;
        setFormProgress(false);
    }
}

/**
 * Show success state
 */
function showSuccessState() {
    elements.form.style.display = 'none';
    elements.successSection.style.display = 'block';
    
    // Scroll to success message
    elements.successSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

/**
 * Save form as draft
 */
function saveDraft() {
    if (!validateField('productName')) {
        showNotification('Please add at least a product name to save as draft', 'warning');
        return;
    }
    
    const draftData = {
        ...state.formData,
        images: state.uploadedImages,
        savedAt: new Date().toISOString()
    };
    
    // Save to localStorage
    const drafts = JSON.parse(localStorage.getItem('ulimi_drafts') || '[]');
    drafts.push(draftData);
    localStorage.setItem('ulimi_drafts', JSON.stringify(drafts));
    
    showNotification('Draft saved successfully!', 'success');
}

/**
 * Show preview modal
 */
function showPreview() {
    if (!validateField('productName')) {
        showNotification('Please add a product name to preview', 'warning');
        return;
    }
    
    const previewContent = elements.previewContent;
    previewContent.innerHTML = generatePreviewHTML();
    
    elements.previewModal.classList.add('open');
    state.isPreviewMode = true;
}

/**
 * Close preview modal
 */
function closePreview() {
    elements.previewModal.classList.remove('open');
    state.isPreviewMode = false;
}

// ===================================
   EVENT LISTENERS
   ===================================

/**
 * Initialize DOM element references
 */
function initializeElements() {
    elements.form = document.getElementById('addListingForm');
    elements.productName = document.getElementById('productName');
    elements.category = document.getElementById('category');
    elements.quality = document.getElementById('quality');
    elements.description = document.getElementById('description');
    elements.price = document.getElementById('price');
    elements.quantity = document.getElementById('quantity');
    elements.minOrder = document.getElementById('minOrder');
    elements.location = document.getElementById('location');
    elements.deliveryAvailable = document.getElementById('deliveryAvailable');
    elements.organic = document.getElementById('organic');
    elements.gap = document.getElementById('gap');
    elements.fairtrade = document.getElementById('fairtrade');
    elements.harvestDate = document.getElementById('harvestDate');
    elements.shelfLife = document.getElementById('shelfLife');
    elements.productImages = document.getElementById('productImages');
    elements.imageUploadArea = document.getElementById('imageUploadArea');
    elements.imagePreviewContainer = document.getElementById('imagePreviewContainer');
    elements.previewModal = document.getElementById('previewModal');
    elements.previewContent = document.getElementById('previewContent');
    elements.successSection = document.getElementById('successSection');
    elements.formProgress = document.getElementById('formProgress');
    elements.progressFill = document.getElementById('progressFill');
    elements.progressPercent = document.getElementById('progressPercent');
    elements.charCount = document.getElementById('charCount');
}

/**
 * Setup event listeners
 */
function setupEventListeners() {
    // Form input listeners
    const formInputs = [
        'productName', 'category', 'quality', 'description', 
        'price', 'quantity', 'minOrder', 'location',
        'deliveryAvailable', 'organic', 'gap', 'fairtrade',
        'harvestDate', 'shelfLife'
    ];
    
    formInputs.forEach(inputName => {
        const element = elements[inputName];
        if (element) {
            element.addEventListener('input', updateFormData);
            element.addEventListener('change', updateFormData);
        }
    });
    
    // Image upload
    elements.productImages.addEventListener('change', handleImageSelect);
    elements.imageUploadArea.addEventListener('click', () => {
        elements.productImages.click();
    });
    
    // Form actions
    document.getElementById('saveDraft').addEventListener('click', saveDraft);
    document.getElementById('previewListing').addEventListener('click', showPreview);
    elements.form.addEventListener('submit', handleFormSubmit);
    
    // Preview modal
    document.getElementById('closePreview').addEventListener('click', closePreview);
    
    // Setup drag and drop
    setupDragAndDrop();
    
    // Load saved draft if exists
    loadDraft();
}

/**
 * Load saved draft
 */
function loadDraft() {
    const urlParams = new URLSearchParams(window.location.search);
    const draftId = urlParams.get('draft');
    
    if (draftId) {
        try {
            const drafts = JSON.parse(localStorage.getItem('ulimi_drafts') || '[]');
            const draft = drafts.find(d => d.savedAt === draftId);
            
            if (draft) {
                // Populate form with draft data
                Object.keys(draft).forEach(key => {
                    if (key !== 'images' && elements[key]) {
                        if (key === 'certifications') {
                            draft.certifications.forEach(cert => {
                                const checkbox = document.getElementById(cert);
                                if (checkbox) checkbox.checked = true;
                            });
                        } else {
                            elements[key].value = draft[key];
                        }
                    }
                });
                
                // Load images
                state.uploadedImages = draft.images || [];
                updateImagePreviews();
                
                showNotification('Draft loaded successfully!', 'success');
            }
        } catch (error) {
            console.error('Error loading draft:', error);
        }
    }
}

// ===================================
   INITIALIZATION
   ===================================

/**
 * Initialize the add listing page
 */
function initializeAddListing() {
    initializeElements();
    setupEventListeners();
    
    // Add custom styles for drag and drop
    const style = document.createElement('style');
    style.textContent = `
        .image-upload-area.drag-over {
            border-color: var(--primary-green);
            background: rgba(46, 125, 50, 0.05);
        }
        
        .product-preview {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            max-width: 600px;
            margin: 0 auto;
        }
        
        .preview-image-section {
            grid-column: span 2;
        }
        
        .preview-image-section img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: var(--radius-sm);
        }
        
        .preview-details {
            grid-column: span 1;
        }
        
        .preview-meta {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .preview-category,
        .preview-quality {
            background: var(--bg-light);
            padding: 0.25rem 0.75rem;
            border-radius: var(--radius-sm);
            font-size: 0.85rem;
        }
        
        .preview-description {
            margin-bottom: 1.5rem;
            line-height: 1.6;
            color: var(--text-muted);
        }
        
        .preview-pricing {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .preview-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-green);
        }
        
        .preview-quantity {
            font-size: 0.9rem;
            color: var(--text-muted);
        }
        
        .preview-location {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--text-muted);
        }
        
        .preview-certifications {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        
        .preview-cert {
            background: var(--success-green);
            color: var(--white);
            padding: 0.25rem 0.5rem;
            border-radius: var(--radius-sm);
            font-size: 0.75rem;
        }
    `;
    document.head.appendChild(style);
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeAddListing);
} else {
    initializeAddListing();
}
