/**
 * Ulimi Agricultural Marketplace - Browse Page JavaScript
 * Handles product browsing, filtering, cart, favorites, and UI interactions
 * Optimized for low-bandwidth environments and mobile-first design
 */

// ===================================
   GLOBAL VARIABLES AND STATE
   ===================================
const state = {
    products: [],
    filteredProducts: [],
    cart: [],
    favorites: [],
    currentView: 'grid', // 'grid' or 'list'
    currentPage: 1,
    itemsPerPage: 12,
    totalPages: 1,
    isLoading: false,
    filters: {
        search: '',
        category: 'all',
        location: 'all',
        priceRange: 'all',
        sortBy: 'newest',
        quality: 'all',
        minQuantity: '',
        certification: 'all',
        sellerType: 'all'
    },
    userLocation: null, // Will be populated from geolocation
    paginationType: 'pagination' // 'pagination' or 'infinite'
};

// DOM Elements
const elements = {
    searchInput: null,
    categoryFilter: null,
    locationFilter: null,
    priceFilter: null,
    sortBy: null,
    productsGrid: null,
    cartSidebar: null,
    cartOverlay: null,
    favoritesModal: null,
    productModal: null,
    modalOverlay: null,
    loadingState: null,
    emptyState: null,
    errorState: null,
    resultsCount: null,
    paginationSection: null,
    loadMoreSection: null
};

// ===================================
   SAMPLE DATA - Replace with API calls
   ===================================
const sampleProducts = [
    {
        id: 1,
        name: 'Premium White Maize',
        price: 1173,
        category: 'grains',
        location: 'Lilongwe',
        region: 'Central',
        quantity: 500,
        unit: 'kg',
        quality: 'Grade A',
        seller: {
            name: 'John Banda Farm',
            type: 'farmer',
            rating: 4.8,
            avatar: 'https://via.placeholder.com/50x50'
        },
        images: ['https://via.placeholder.com/300x200'],
        description: 'High quality white maize, properly dried and stored. Perfect for both human consumption and animal feed.',
        certifications: ['organic'],
        dateAdded: '2024-01-15',
        views: 245,
        isFavorite: false,
        distance: 12
    },
    {
        id: 2,
        name: 'Shelled Ground Nuts',
        price: 2500,
        category: 'legumes',
        location: 'Blantyre',
        region: 'Southern',
        quantity: 200,
        unit: 'kg',
        quality: 'Premium',
        seller: {
            name: 'Southern Agro Cooperative',
            type: 'cooperative',
            rating: 4.6,
            avatar: 'https://via.placeholder.com/50x50'
        },
        images: ['https://via.placeholder.com/300x200'],
        description: 'Fresh shelled ground nuts with high oil content. Grown using sustainable farming practices.',
        certifications: ['organic', 'gap'],
        dateAdded: '2024-01-14',
        views: 189,
        isFavorite: true,
        distance: 45
    },
    {
        id: 3,
        name: 'Soya Beans',
        price: 1004,
        category: 'legumes',
        location: 'Mzuzu',
        region: 'Northern',
        quantity: 150,
        unit: 'kg',
        quality: 'Grade A',
        seller: {
            name: 'Northern Farmers Union',
            type: 'cooperative',
            rating: 4.9,
            avatar: 'https://via.placeholder.com/50x50'
        },
        images: ['https://via.placeholder.com/300x200'],
        description: 'High protein soya beans, non-GMO variety. Excellent for livestock feed and oil extraction.',
        certifications: ['gap'],
        dateAdded: '2024-01-13',
        views: 312,
        isFavorite: false,
        distance: 280
    },
    {
        id: 4,
        name: 'Pigeon Peas',
        price: 1200,
        category: 'legumes',
        location: 'Zomba',
        region: 'Southern',
        quantity: 100,
        unit: 'kg',
        quality: 'Premium',
        seller: {
            name: 'Zomba Smallholders',
            type: 'farmer',
            rating: 4.7,
            avatar: 'https://via.placeholder.com/50x50'
        },
        images: ['https://via.placeholder.com/300x200'],
        description: 'Organic pigeon peas, rich in protein and fiber. Perfect for local markets and export.',
        certifications: ['organic', 'fairtrade'],
        dateAdded: '2024-01-12',
        views: 156,
        isFavorite: false,
        distance: 65
    },
    {
        id: 5,
        name: 'Fresh Tomatoes',
        price: 2500,
        category: 'vegetables',
        location: 'Dedza',
        region: 'Central',
        quantity: 50,
        unit: 'kg',
        quality: 'Grade A',
        seller: {
            name: 'Green Valley Farms',
            type: 'farmer',
            rating: 4.5,
            avatar: 'https://via.placeholder.com/50x50'
        },
        images: ['https://via.placeholder.com/300x200'],
        description: 'Fresh, ripe tomatoes grown organically. Perfect for local markets and restaurants.',
        certifications: ['organic'],
        dateAdded: '2024-01-11',
        views: 423,
        isFavorite: true,
        distance: 35
    },
    {
        id: 6,
        name: 'Burley Tobacco',
        price: 900,
        category: 'cash-crops',
        location: 'Mangochi',
        region: 'Southern',
        quantity: 80,
        unit: 'kg',
        quality: 'Premium',
        seller: {
            name: 'Lake Malawi Tobacco Co.',
            type: 'aggregator',
            rating: 4.4,
            avatar: 'https://via.placeholder.com/50x50'
        },
        images: ['https://via.placeholder.com/300x200'],
        description: 'Premium quality burley tobacco, properly cured and ready for international markets.',
        certifications: ['gap'],
        dateAdded: '2024-01-10',
        views: 567,
        isFavorite: false,
        distance: 120
    },
    {
        id: 7,
        name: 'Unshelled Ground Nuts',
        price: 1300,
        category: 'legumes',
        location: 'Lilongwe',
        region: 'Central',
        quantity: 300,
        unit: 'kg',
        quality: 'Grade A',
        seller: {
            name: 'Central Region Farmers',
            type: 'farmer',
            rating: 4.5,
            avatar: 'https://via.placeholder.com/50x50'
        },
        images: ['https://via.placeholder.com/300x200'],
        description: 'Fresh unshelled ground nuts, perfect for oil extraction and direct consumption.',
        certifications: ['organic'],
        dateAdded: '2024-01-16',
        views: 198,
        isFavorite: false,
        distance: 8
    },
    {
        id: 8,
        name: 'Sorghum',
        price: 900,
        category: 'grains',
        location: 'Salima',
        region: 'Central',
        quantity: 400,
        unit: 'kg',
        quality: 'Grade B',
        seller: {
            name: 'Salima Grain Farmers',
            type: 'cooperative',
            rating: 4.3,
            avatar: 'https://via.placeholder.com/50x50'
        },
        images: ['https://via.placeholder.com/300x200'],
        description: 'Traditional sorghum variety, well-suited for Malawi growing conditions.',
        certifications: ['gap'],
        dateAdded: '2024-01-09',
        views: 267,
        isFavorite: false,
        distance: 95
    },
    {
        id: 9,
        name: 'Millet',
        price: 1000,
        category: 'grains',
        location: 'Karonga',
        region: 'Northern',
        quantity: 250,
        unit: 'kg',
        quality: 'Grade B',
        seller: {
            name: 'Northern Smallholders Coop',
            type: 'cooperative',
            rating: 4.2,
            avatar: 'https://via.placeholder.com/50x50'
        },
        images: ['https://via.placeholder.com/300x200'],
        description: 'Pearl millet, drought-resistant variety perfect for northern regions.',
        certifications: ['organic'],
        dateAdded: '2024-01-08',
        views: 145,
        isFavorite: false,
        distance: 380
    },
    {
        id: 10,
        name: 'Sunflower',
        price: 1500,
        category: 'cash-crops',
        location: 'Kasungu',
        region: 'Central',
        quantity: 180,
        unit: 'kg',
        quality: 'Grade A',
        seller: {
            name: 'Kasungu Sunflower Growers',
            type: 'farmer',
            rating: 4.6,
            avatar: 'https://via.placeholder.com/50x50'
        },
        images: ['https://via.placeholder.com/300x200'],
        description: 'High-quality sunflower seeds for oil production and bird feed.',
        certifications: ['organic'],
        dateAdded: '2024-01-07',
        views: 189,
        isFavorite: false,
        distance: 120
    }
];

// ===================================
   UTILITY FUNCTIONS
   ===================================

/**
 * Get user-specific cart cookie name
 * @returns {string} User-specific cookie name
 */
function getCartCookieName() {
    const userId = window.ulimiUserId || 'guest';
    return 'ulimi_cart_user_' + userId;
}

/**
 * Get cookie value
 * @param {string} name - Cookie name
 * @returns {string|null} Cookie value
 */
function getCookie(name) {
    const nameEQ = name + '=';
    const ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

/**
 * Set cookie value
 * @param {string} name - Cookie name
 * @param {string} value - Cookie value
 * @param {number} days - Days until expiration
 */
function setCookie(name, value, days) {
    const expires = new Date();
    expires.setTime(expires.getTime() + days * 24 * 60 * 60 * 1000);
    document.cookie = name + '=' + JSON.stringify(value) + ';expires=' + expires.toUTCString() + ';path=/';
}

/**
 * Format price in MWK currency
 * @param {number} price - Price to format
 * @returns {string} Formatted price string
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
 * Format date to relative time
 * @param {string} dateString - ISO date string
 * @returns {string} Relative time string
 */
function formatRelativeTime(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffTime = Math.abs(now - date);
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    if (diffDays === 1) return '1 day ago';
    if (diffDays < 7) return `${diffDays} days ago`;
    if (diffDays < 30) return `${Math.floor(diffDays / 7)} weeks ago`;
    if (diffDays < 365) return `${Math.floor(diffDays / 30)} months ago`;
    return `${Math.floor(diffDays / 365)} years ago`;
}

/**
 * Debounce function for search input
 * @param {Function} func - Function to debounce
 * @param {number} wait - Wait time in ms
 * @returns {Function} Debounced function
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Generate star rating HTML
 * @param {number} rating - Rating value
 * @returns {string} Star rating HTML
 */
function generateStars(rating) {
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating % 1 >= 0.5;
    const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
    
    let stars = '';
    for (let i = 0; i < fullStars; i++) {
        stars += '<i class="fas fa-star"></i>';
    }
    if (hasHalfStar) {
        stars += '<i class="fas fa-star-half-alt"></i>';
    }
    for (let i = 0; i < emptyStars; i++) {
        stars += '<i class="far fa-star"></i>';
    }
    
    return stars;
}

/**
 * Show/hide loading state
 * @param {boolean} show - Whether to show loading
 */
function setLoading(show) {
    state.isLoading = show;
    if (show) {
        elements.loadingState.style.display = 'block';
        elements.productsGrid.style.display = 'none';
        elements.emptyState.style.display = 'none';
        elements.errorState.style.display = 'none';
    } else {
        elements.loadingState.style.display = 'none';
        elements.productsGrid.style.display = 'grid';
    }
}

/**
 * Show empty state
 */
function showEmptyState() {
    elements.loadingState.style.display = 'none';
    elements.productsGrid.style.display = 'none';
    elements.emptyState.style.display = 'block';
    elements.errorState.style.display = 'none';
    elements.paginationSection.style.display = 'none';
    elements.loadMoreSection.style.display = 'none';
}

/**
 * Show error state
 */
function showErrorState() {
    elements.loadingState.style.display = 'none';
    elements.productsGrid.style.display = 'none';
    elements.emptyState.style.display = 'none';
    elements.errorState.style.display = 'block';
    elements.paginationSection.style.display = 'none';
    elements.loadMoreSection.style.display = 'none';
}

/**
 * Update results count
 */
function updateResultsCount() {
    elements.resultsCount.textContent = state.filteredProducts.length;
    
    const locationText = state.filters.location !== 'all' 
        ? ` in ${getLocationName(state.filters.location)}` 
        : '';
    elements.locationContext.textContent = locationText;
}

/**
 * Get location display name
 * @param {string} locationKey - Location key
 * @returns {string} Display name
 */
function getLocationName(locationKey) {
    const locations = {
        'lilongwe': 'Lilongwe',
        'blantyre': 'Blantyre',
        'mzuzu': 'Mzuzu',
        'zomba': 'Zomba',
        'kasungu': 'Kasungu',
        'mangochi': 'Mangochi',
        'karonga': 'Karonga',
        'dedza': 'Dedza',
        'salima': 'Salima'
    };
    return locations[locationKey] || locationKey;
}

// ===================================
   PRODUCT FILTERING AND SORTING
   ===================================

/**
 * Apply all current filters to products
 */
function applyFilters() {
    let filtered = [...state.products];
    
    // Search filter
    if (state.filters.search) {
        const searchTerm = state.filters.search.toLowerCase();
        filtered = filtered.filter(product => 
            product.name.toLowerCase().includes(searchTerm) ||
            product.description.toLowerCase().includes(searchTerm) ||
            product.location.toLowerCase().includes(searchTerm)
        );
    }
    
    // Category filter
    if (state.filters.category !== 'all') {
        filtered = filtered.filter(product => product.category === state.filters.category);
    }
    
    // Location filter
    if (state.filters.location !== 'all') {
        filtered = filtered.filter(product => product.location === getLocationName(state.filters.location));
    }
    
    // Price range filter
    if (state.filters.priceRange !== 'all') {
        const [min, max] = state.filters.priceRange.split('-').map(p => p === '+' ? Infinity : parseInt(p));
        filtered = filtered.filter(product => {
            if (max === Infinity) return product.price >= min;
            return product.price >= min && product.price <= max;
        });
    }
    
    // Quality filter
    if (state.filters.quality !== 'all') {
        filtered = filtered.filter(product => product.quality.toLowerCase() === state.filters.quality);
    }
    
    // Minimum quantity filter
    if (state.filters.minQuantity) {
        filtered = filtered.filter(product => product.quantity >= parseInt(state.filters.minQuantity));
    }
    
    // Certification filter
    if (state.filters.certification !== 'all') {
        filtered = filtered.filter(product => 
            product.certifications && product.certifications.includes(state.filters.certification)
        );
    }
    
    // Seller type filter
    if (state.filters.sellerType !== 'all') {
        filtered = filtered.filter(product => product.seller.type === state.filters.sellerType);
    }
    
    // Apply sorting
    filtered = sortProducts(filtered);
    
    state.filteredProducts = filtered;
    state.currentPage = 1;
    updateResultsCount();
    renderProducts();
    updatePagination();
    updateActiveFiltersDisplay();
}

/**
 * Sort products based on current sort option
 * @param {Array} products - Products to sort
 * @returns {Array} Sorted products
 */
function sortProducts(products) {
    const sorted = [...products];
    
    switch (state.filters.sortBy) {
        case 'price-low':
            return sorted.sort((a, b) => a.price - b.price);
        case 'price-high':
            return sorted.sort((a, b) => b.price - a.price);
        case 'distance':
            return sorted.sort((a, b) => (a.distance || 999) - (b.distance || 999));
        case 'popular':
            return sorted.sort((a, b) => b.views - a.views);
        case 'newest':
        default:
            return sorted.sort((a, b) => new Date(b.dateAdded) - new Date(a.dateAdded));
    }
}

// ===================================
   PRODUCT RENDERING
   ===================================

/**
 * Render products in grid or list view
 */
function renderProducts() {
    const startIndex = (state.currentPage - 1) * state.itemsPerPage;
    const endIndex = startIndex + state.itemsPerPage;
    const productsToShow = state.filteredProducts.slice(startIndex, endIndex);
    
    if (productsToShow.length === 0) {
        showEmptyState();
        return;
    }
    
    elements.productsGrid.innerHTML = '';
    
    productsToShow.forEach(product => {
        const productCard = createProductCard(product);
        elements.productsGrid.appendChild(productCard);
    });
    
    // Show/hide pagination or load more
    if (state.paginationType === 'pagination') {
        elements.paginationSection.style.display = state.totalPages > 1 ? 'block' : 'none';
        elements.loadMoreSection.style.display = 'none';
    } else {
        elements.paginationSection.style.display = 'none';
        elements.loadMoreSection.style.display = state.currentPage < state.totalPages ? 'block' : 'none';
    }
}

/**
 * Create product card element
 * @param {Object} product - Product data
 * @returns {HTMLElement} Product card element
 */
function createProductCard(product) {
    const card = document.createElement('div');
    card.className = `product-card ${state.currentView === 'list' ? 'list-view' : ''}`;

    const isFavorite = state.favorites.includes(product.id);

    card.innerHTML = `
        <div class="product-image-container">
            ${product.certifications.includes('organic') ? '<div class="product-badge organic">Organic</div>' : ''}
            ${product.quality === 'Premium' ? '<div class="product-badge premium">Premium</div>' : ''}
            <img src="${product.images[0]}" alt="${product.name}" class="product-image" loading="lazy">
        </div>
        <div class="product-content">
            <div class="product-header">
                <h3 class="product-title">${product.name}</h3>
                <div class="product-location">
                    <i class="fas fa-map-marker-alt"></i>
                    ${product.location}
                    ${product.distance ? ` • ${product.distance}km` : ''}
                </div>
            </div>
            <div class="product-meta">
                <span class="product-category">${product.category}</span>
                <span class="product-quality">${product.quality}</span>
            </div>
            <div class="product-price-section">
                <div>
                    <span class="product-price">${formatPrice(product.price)}</span>
                    <span class="product-unit">/${product.unit}</span>
                </div>
                <div>${product.quantity} ${product.unit} available</div>
            </div>
            <div class="product-actions">
                <button class="btn btn-primary" onclick="viewProduct(${product.id})">
                    <i class="fas fa-eye"></i>
                    View
                </button>
                <button class="btn btn-outline favorite-btn ${isFavorite ? 'active' : ''}" onclick="toggleFavorite(${product.id})">
                    <i class="${isFavorite ? 'fas' : 'far'} fa-heart"></i>
                </button>
                <button class="btn btn-outline" onclick="addToCart(${product.id})">
                    <i class="fas fa-shopping-cart"></i>
                    Add
                </button>
            </div>
        </div>
    `;
    
    return card;
}

// ===================================
   SHOPPING CART FUNCTIONALITY
   ===================================

/**
 * Add product to cart
 * @param {number} productId - Product ID
 */
function addToCart(productId) {
    const product = state.products.find(p => p.id === productId);
    if (!product) return;
    
    const existingItem = state.cart.find(item => item.id === productId);
    
    if (existingItem) {
        existingItem.quantity++;
    } else {
        state.cart.push({
            ...product,
            quantity: 1
        });
    }
    
    updateCartUI();
    saveCartToCookie();
    showNotification('Product added to cart!', 'success');
}

/**
 * Remove item from cart
 * @param {number} productId - Product ID
 */
function removeFromCart(productId) {
    state.cart = state.cart.filter(item => item.id !== productId);
    updateCartUI();
    saveCartToCookie();
}

/**
 * Update cart quantity
 * @param {number} productId - Product ID
 * @param {number} change - Quantity change (+1 or -1)
 */
function updateCartQuantity(productId, change) {
    const item = state.cart.find(item => item.id === productId);
    if (!item) return;
    
    item.quantity += change;
    if (item.quantity <= 0) {
        removeFromCart(productId);
    } else {
        updateCartUI();
        saveCartToCookie();
    }
}

/**
 * Update cart UI
 */
function updateCartUI() {
    const cartItems = elements.cartItems;
    const cartEmpty = elements.cartEmpty;
    const cartSummary = elements.cartSummary;
    const cartTotal = elements.cartTotal;
    const cartCount = elements.cartCount;

    if (state.cart.length === 0) {
        cartItems.style.display = 'none';
        cartSummary.style.display = 'none';
        cartEmpty.style.display = 'block';
        cartCount.textContent = '0';
        return;
    }

    cartItems.style.display = 'flex';
    cartSummary.style.display = 'block';
    cartEmpty.style.display = 'none';
    
    // Update cart items
    cartItems.innerHTML = '';
    state.cart.forEach(item => {
        const cartItem = document.createElement('div');
        cartItem.className = 'cart-item';

        // Handle both cookie structure (title, image) and browse.js structure (name, images)
        const itemName = item.name || item.title || 'Product';
        const itemImage = item.images && item.images[0] ? item.images[0] : (item.image || '/assets/images/placeholder-product.jpg');

        cartItem.innerHTML = `
            <img src="${itemImage}" alt="${itemName}" class="cart-item-image">
            <div class="cart-item-details">
                <div class="cart-item-title">${itemName}</div>
                <div class="cart-item-price">${formatPrice(item.price)}</div>
                <div class="cart-item-quantity">
                    <button class="quantity-btn" onclick="updateCartQuantity(${item.id}, -1)">
                        <i class="fas fa-minus"></i>
                    </button>
                    <span>${item.quantity}</span>
                    <button class="quantity-btn" onclick="updateCartQuantity(${item.id}, 1)">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
            <button class="btn btn-icon" onclick="removeFromCart(${item.id})" style="margin-left: auto;">
                <i class="fas fa-times"></i>
            </button>
        `;
        cartItems.appendChild(cartItem);
    });

    // Update total
    const total = state.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    if (cartTotal) {
        cartTotal.textContent = formatPrice(total);
    }

    // Update cart count
    const totalItems = state.cart.reduce((sum, item) => sum + item.quantity, 0);
    if (cartCount) {
        cartCount.textContent = totalItems > 99 ? '99+' : totalItems.toString();
    }
}

// ===================================
   FAVORITES FUNCTIONALITY
   ===================================

/**
 * Toggle product favorite status
 * @param {number} productId - Product ID
 */
async function toggleFavorite(productId) {
    // Check if user is logged in
    const isLoggedIn = document.body.classList.contains('logged-in') ||
                      document.querySelector('meta[name="user-id"]');

    if (!isLoggedIn) {
        showNotification('Please log in to add favorites', 'warning');
        return;
    }

    const product = state.products.find(p => p.id === productId);
    if (!product) return;

    const isFavorite = state.favorites.includes(productId);
    const button = document.querySelector(`button[onclick="toggleFavorite(${productId})"]`);

    // Instantly toggle UI state
    if (button) {
        button.classList.toggle('active');
        const icon = button.querySelector('i');
        if (icon) {
            icon.classList.toggle('fas');
            icon.classList.toggle('far');
        }
    }

    try {
        const endpoint = isFavorite ? '/api/favorites/remove' : '/api/favorites/add';
        const response = await fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ listing_id: productId })
        });

        const data = await response.json();

        if (data.success) {
            // Update local state
            const index = state.favorites.indexOf(productId);
            if (isFavorite) {
                if (index > -1) state.favorites.splice(index, 1);
                showNotification('Removed from favorites', 'info');
            } else {
                state.favorites.push(productId);
                showNotification('Added to favorites!', 'success');
            }
        } else {
            // Revert UI state on error
            if (button) {
                button.classList.toggle('active');
                const icon = button.querySelector('i');
                if (icon) {
                    icon.classList.toggle('fas');
                    icon.classList.toggle('far');
                }
            }
            showNotification(data.message || 'Failed to update favorites', 'error');
        }
    } catch (error) {
        console.error('Error toggling favorite:', error);
        // Revert UI state on error
        if (button) {
            button.classList.toggle('active');
            const icon = button.querySelector('i');
            if (icon) {
                icon.classList.toggle('fas');
                icon.classList.toggle('far');
            }
        }
        showNotification('Failed to update favorites. Please try again.', 'error');
    }
}

/**
 * Load favorites from API
 */
async function loadFavorites() {
    // Check if user is logged in
    const isLoggedIn = document.body.classList.contains('logged-in') ||
                      document.querySelector('meta[name="user-id"]');

    if (!isLoggedIn) {
        state.favorites = [];
        return;
    }

    try {
        const response = await fetch('/api/favorites');
        const data = await response.json();

        if (data.success) {
            state.favorites = data.favorites || [];
        } else {
            state.favorites = [];
        }
    } catch (error) {
        console.error('Error loading favorites:', error);
        state.favorites = [];
    }
}

/**
 * Update favorites modal UI
 */
function updateFavoritesUI() {
    const favoritesList = elements.favoritesList;
    const favoritesEmpty = elements.favoritesEmpty;

    if (state.favorites.length === 0) {
        favoritesList.style.display = 'none';
        favoritesEmpty.style.display = 'block';
        return;
    }

    favoritesList.style.display = 'flex';
    favoritesEmpty.style.display = 'none';

    favoritesList.innerHTML = '';
    state.favorites.forEach(listingId => {
        const product = state.products.find(p => p.id === listingId);
        if (product) {
            const favoriteItem = document.createElement('div');
            favoriteItem.className = 'favorite-item';
            favoriteItem.innerHTML = `
                <img src="${product.images[0]}" alt="${product.name}" class="favorite-item-image">
                <div class="favorite-item-details">
                    <div class="favorite-item-title">${product.name}</div>
                    <div class="favorite-item-price">${formatPrice(product.price)}</div>
                </div>
            `;
            favoriteItem.onclick = () => viewProduct(product.id);
            favoritesList.appendChild(favoriteItem);
        }
    });
}

// ===================================
   PRODUCT DETAIL MODAL
   ===================================

/**
 * Show product detail modal
 * @param {number} productId - Product ID
 */
function viewProduct(productId) {
    const product = state.products.find(p => p.id === productId);
    if (!product) return;
    
    const modalContent = document.getElementById('productDetailContent');
    modalContent.innerHTML = `
        <div class="product-detail-content">
            <div>
                <img src="${product.images[0]}" alt="${product.name}" class="product-detail-image">
                <div class="product-badges">
                    ${product.certifications.includes('organic') ? '<div class="product-badge organic">Organic</div>' : ''}
                    ${product.quality === 'Premium' ? '<div class="product-badge premium">Premium</div>' : ''}
                </div>
            </div>
            <div class="product-detail-info">
                <div class="detail-section">
                    <h4 class="detail-title">${product.name}</h4>
                    <p class="detail-text">${product.description}</p>
                </div>
                
                <div class="detail-section">
                    <h5 class="detail-title">Price & Availability</h5>
                    <div class="product-price-section">
                        <div>
                            <span class="product-price">${formatPrice(product.price)}</span>
                            <span class="product-unit">/${product.unit}</span>
                        </div>
                        <div>${product.quantity} ${product.unit} available</div>
                    </div>
                </div>
                
                <div class="detail-section">
                    <h5 class="detail-title">Quality & Certification</h5>
                    <p><strong>Quality:</strong> ${product.quality}</p>
                    <p><strong>Certifications:</strong> ${product.certifications.join(', ') || 'None'}</p>
                </div>
                
                <div class="detail-section">
                    <h5 class="detail-title">Location</h5>
                    <p><i class="fas fa-map-marker-alt"></i> ${product.location}, ${product.region}</p>
                    <p><strong>Distance:</strong> ${product.distance || 'Unknown'} km</p>
                </div>
                
                <div class="detail-section">
                    <h5 class="detail-title">Seller Information</h5>
                    <div class="seller-info">
                        <img src="${product.seller.avatar}" alt="${product.seller.name}" class="seller-avatar">
                        <div class="seller-details">
                            <div class="seller-name">${product.seller.name}</div>
                            <div class="seller-rating">
                                ${generateStars(product.seller.rating)}
                                <span>(${product.seller.rating})</span>
                            </div>
                            <p><strong>Type:</strong> ${product.seller.type}</p>
                        </div>
                    </div>
                </div>
                
                <div class="detail-section">
                    <button class="btn btn-primary btn-full-width" onclick="addToCart(${product.id})">
                        <i class="fas fa-shopping-cart"></i>
                        Add to Cart
                    </button>
                    <button class="btn btn-outline btn-full-width" onclick="contactSeller(${product.id})" style="margin-top: 0.5rem;">
                        <i class="fas fa-envelope"></i>
                        Contact Seller
                    </button>
                </div>
            </div>
        </div>
    `;
    
    openModal('productModal');
}

/**
 * Contact seller (placeholder function)
 * @param {number} productId - Product ID
 */
function contactSeller(productId) {
    showNotification('Contact feature coming soon!', 'info');
    closeModal('productModal');
}

// ===================================
   PAGINATION
   ===================================

/**
 * Update pagination controls
 */
function updatePagination() {
    state.totalPages = Math.ceil(state.filteredProducts.length / state.itemsPerPage);
    
    if (state.paginationType !== 'pagination') return;
    
    const pageNumbers = document.getElementById('pageNumbers');
    const paginationInfo = document.getElementById('paginationInfo');
    const prevBtn = document.getElementById('prevPage');
    const nextBtn = document.getElementById('nextPage');
    
    // Update page numbers
    pageNumbers.innerHTML = '';
    const maxVisiblePages = 5;
    let startPage = Math.max(1, state.currentPage - Math.floor(maxVisiblePages / 2));
    let endPage = Math.min(state.totalPages, startPage + maxVisiblePages - 1);
    
    if (endPage - startPage < maxVisiblePages - 1) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }
    
    for (let i = startPage; i <= endPage; i++) {
        const pageBtn = document.createElement('button');
        pageBtn.className = `page-number ${i === state.currentPage ? 'active' : ''}`;
        pageBtn.textContent = i;
        pageBtn.onclick = () => goToPage(i);
        pageNumbers.appendChild(pageBtn);
    }
    
    // Update pagination info
    paginationInfo.textContent = `Page ${state.currentPage} of ${state.totalPages}`;
    
    // Update prev/next buttons
    prevBtn.disabled = state.currentPage === 1;
    nextBtn.disabled = state.currentPage === state.totalPages;
}

/**
 * Go to specific page
 * @param {number} page - Page number
 */
function goToPage(page) {
    if (page < 1 || page > state.totalPages) return;
    state.currentPage = page;
    renderProducts();
    updatePagination();
    
    // Scroll to top of products
    elements.productsGrid.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

/**
 * Load more products (for infinite scroll)
 */
function loadMoreProducts() {
    if (state.isLoading || state.currentPage >= state.totalPages) return;
    
    state.currentPage++;
    const startIndex = (state.currentPage - 1) * state.itemsPerPage;
    const endIndex = startIndex + state.itemsPerPage;
    const productsToShow = state.filteredProducts.slice(startIndex, endIndex);
    
    productsToShow.forEach(product => {
        const productCard = createProductCard(product);
        elements.productsGrid.appendChild(productCard);
    });
    
    // Hide load more button if all products are loaded
    if (state.currentPage >= state.totalPages) {
        document.getElementById('loadMoreSection').style.display = 'none';
    }
}

// ===================================
   MODAL FUNCTIONS
   ===================================

/**
 * Open modal
 * @param {string} modalId - Modal element ID
 */
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    const overlay = document.getElementById('modalOverlay');
    
    modal.classList.add('open');
    overlay.classList.add('open');
    document.body.style.overflow = 'hidden';
}

/**
 * Close modal
 * @param {string} modalId - Modal element ID
 */
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    const overlay = document.getElementById('modalOverlay');
    
    modal.classList.remove('open');
    overlay.classList.remove('open');
    document.body.style.overflowY = '';
    document.body.style.overflowX = '';
}

// ===================================
   NOTIFICATION SYSTEM
   ===================================

/**
 * Show notification
 * @param {string} message - Notification message
 * @param {string} type - Notification type ('success', 'error', 'info')
 */
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
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
            }
            
            .notification.show {
                transform: translateX(0);
            }
            
            .notification-success {
                border-left: 4px solid var(--primary-green);
            }
            
            .notification-error {
                border-left: 4px solid #dc3545;
            }
            
            .notification-info {
                border-left: 4px solid #17a2b8;
            }
            
            .notification i {
                font-size: 1.25rem;
            }
            
            .notification-success i {
                color: var(--primary-green);
            }
            
            .notification-error i {
                color: #dc3545;
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
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

// ===================================
   EVENT LISTENERS
   ===================================

/**
 * Initialize DOM element references
 */
function initializeElements() {
    elements.searchInput = document.getElementById('searchInput');
    elements.categoryFilter = document.getElementById('categoryFilter');
    elements.locationFilter = document.getElementById('locationFilter');
    elements.priceFilter = document.getElementById('priceFilter');
    elements.sortBy = document.getElementById('sortBy');
    elements.productsGrid = document.getElementById('productsGrid');
    elements.cartSidebar = document.getElementById('cartSidebar');
    elements.cartOverlay = document.getElementById('cartOverlay');
    elements.favoritesModal = document.getElementById('favoritesModal');
    elements.productModal = document.getElementById('productModal');
    elements.modalOverlay = document.getElementById('modalOverlay');
    elements.loadingState = document.getElementById('loadingState');
    elements.emptyState = document.getElementById('emptyState');
    elements.errorState = document.getElementById('errorState');
    elements.resultsCount = document.getElementById('resultsCount');
    elements.locationContext = document.getElementById('locationContext');
    elements.paginationSection = document.getElementById('paginationSection');
    elements.loadMoreSection = document.getElementById('loadMoreSection');
    elements.cartItems = document.getElementById('cartItems');
    elements.cartEmpty = document.getElementById('cartEmpty');
    elements.cartSummary = document.getElementById('cartSummary');
    elements.cartTotal = document.getElementById('cartTotal');
    elements.cartCount = document.getElementById('cartCount');
    elements.favoritesList = document.getElementById('favoritesList');
    elements.favoritesEmpty = document.getElementById('favoritesEmpty');
}

/**
 * Setup event listeners
 */
function setupEventListeners() {
    // Search functionality
    const debouncedSearch = debounce(() => {
        state.filters.search = elements.searchInput.value;
        applyFilters();
    }, 300);
    
    elements.searchInput.addEventListener('input', debouncedSearch);
    
    document.getElementById('clearSearch').addEventListener('click', () => {
        elements.searchInput.value = '';
        state.filters.search = '';
        applyFilters();
    });
    
    // Filter change listeners
    elements.categoryFilter.addEventListener('change', (e) => {
        state.filters.category = e.target.value;
        applyFilters();
    });
    
    elements.locationFilter.addEventListener('change', (e) => {
        state.filters.location = e.target.value;
        applyFilters();
    });
    
    elements.priceFilter.addEventListener('change', (e) => {
        state.filters.priceRange = e.target.value;
        applyFilters();
    });
    
    elements.sortBy.addEventListener('change', (e) => {
        state.filters.sortBy = e.target.value;
        applyFilters();
    });
    
    // Advanced filters
    document.getElementById('advancedFilters').addEventListener('click', () => {
        const panel = document.getElementById('advancedFiltersPanel');
        panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
    });
    
    // Advanced filter listeners
    document.getElementById('qualityFilter').addEventListener('change', (e) => {
        state.filters.quality = e.target.value;
        applyFilters();
    });
    
    document.getElementById('quantityFilter').addEventListener('input', (e) => {
        state.filters.minQuantity = e.target.value;
        applyFilters();
    });
    
    document.getElementById('certificationFilter').addEventListener('change', (e) => {
        state.filters.certification = e.target.value;
        applyFilters();
    });
    
    document.getElementById('sellerType').addEventListener('change', (e) => {
        state.filters.sellerType = e.target.value;
        applyFilters();
    });
    
    // View toggle
    document.getElementById('gridView').addEventListener('click', () => {
        state.currentView = 'grid';
        document.getElementById('gridView').classList.add('active');
        document.getElementById('listView').classList.remove('active');
        renderProducts();
    });
    
    document.getElementById('listView').addEventListener('click', () => {
        state.currentView = 'list';
        document.getElementById('listView').classList.add('active');
        document.getElementById('gridView').classList.remove('active');
        renderProducts();
    });
    
    // Cart functionality
    document.getElementById('cartToggle').addEventListener('click', () => {
        elements.cartSidebar.classList.add('open');
        elements.cartOverlay.classList.add('open');
    });
    
    document.getElementById('closeCart').addEventListener('click', () => {
        elements.cartSidebar.classList.remove('open');
        elements.cartOverlay.classList.remove('open');
    });
    
    elements.cartOverlay.addEventListener('click', () => {
        elements.cartSidebar.classList.remove('open');
        elements.cartOverlay.classList.remove('open');
    });
    
    document.getElementById('checkoutBtn').addEventListener('click', () => {
        showNotification('Checkout functionality coming soon!', 'info');
    });
    
    // Favorites functionality
    document.getElementById('favoritesToggle').addEventListener('click', () => {
        updateFavoritesUI();
        openModal('favoritesModal');
    });
    
    document.getElementById('closeFavorites').addEventListener('click', () => {
        closeModal('favoritesModal');
    });
    
    elements.modalOverlay.addEventListener('click', () => {
        closeModal('productModal');
        closeModal('favoritesModal');
    });
    
    // Pagination
    document.getElementById('prevPage').addEventListener('click', () => {
        if (state.currentPage > 1) {
            goToPage(state.currentPage - 1);
        }
    });
    
    document.getElementById('nextPage').addEventListener('click', () => {
        if (state.currentPage < state.totalPages) {
            goToPage(state.currentPage + 1);
        }
    });
    
    document.getElementById('loadMoreBtn').addEventListener('click', loadMoreProducts);
    
    // Reset and retry buttons
    document.getElementById('resetFilters').addEventListener('click', () => {
        resetFilters();
    });
    
    document.getElementById('clearAllFilters').addEventListener('click', () => {
        resetFilters();
    });
    
    document.getElementById('retryLoad').addEventListener('click', () => {
        loadProducts();
    });
    
    // Scroll to top
    document.getElementById('scrollToTop').addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
    
    // Show/hide scroll to top button
    window.addEventListener('scroll', () => {
        const scrollBtn = document.getElementById('scrollToTop');
        if (window.pageYOffset > 300) {
            scrollBtn.style.display = 'flex';
        } else {
            scrollBtn.style.display = 'none';
        }
    });
}

/**
 * Reset all filters
 */
function resetFilters() {
    state.filters = {
        search: '',
        category: 'all',
        location: 'all',
        priceRange: 'all',
        sortBy: 'newest',
        quality: 'all',
        minQuantity: '',
        certification: 'all',
        sellerType: 'all'
    };
    
    // Reset form elements
    elements.searchInput.value = '';
    elements.categoryFilter.value = 'all';
    elements.locationFilter.value = 'all';
    elements.priceFilter.value = 'all';
    elements.sortBy.value = 'newest';
    document.getElementById('qualityFilter').value = 'all';
    document.getElementById('quantityFilter').value = '';
    document.getElementById('certificationFilter').value = 'all';
    document.getElementById('sellerType').value = 'all';
    
    // Hide advanced filters panel
    document.getElementById('advancedFiltersPanel').style.display = 'none';
    
    applyFilters();
}

/**
 * Update active filters display
 */
function updateActiveFiltersDisplay() {
    const section = document.getElementById('activeFiltersSection');
    const list = document.getElementById('activeFiltersList');
    
    const activeFilters = [];
    
    if (state.filters.search) activeFilters.push(`Search: "${state.filters.search}"`);
    if (state.filters.category !== 'all') activeFilters.push(`Category: ${state.filters.category}`);
    if (state.filters.location !== 'all') activeFilters.push(`Location: ${getLocationName(state.filters.location)}`);
    if (state.filters.priceRange !== 'all') activeFilters.push(`Price: ${state.filters.priceRange}`);
    if (state.filters.quality !== 'all') activeFilters.push(`Quality: ${state.filters.quality}`);
    if (state.filters.minQuantity) activeFilters.push(`Min Qty: ${state.filters.minQuantity}kg`);
    if (state.filters.certification !== 'all') activeFilters.push(`Certified: ${state.filters.certification}`);
    if (state.filters.sellerType !== 'all') activeFilters.push(`Seller: ${state.filters.sellerType}`);
    
    if (activeFilters.length === 0) {
        section.style.display = 'none';
        return;
    }
    
    section.style.display = 'block';
    list.innerHTML = '';
    
    activeFilters.forEach(filter => {
        const filterTag = document.createElement('div');
        filterTag.className = 'active-filter-tag';
        filterTag.innerHTML = `
            ${filter}
            <button class="remove-filter" onclick="removeFilter('${filter}')">
                <i class="fas fa-times"></i>
            </button>
        `;
        list.appendChild(filterTag);
    });
}

/**
 * Remove specific filter
 * @param {string} filter - Filter string to remove
 */
function removeFilter(filter) {
    // This is a simplified version - in production, you'd want more sophisticated filter management
    resetFilters();
}

// ===================================
   API FUNCTIONS (Backend-Ready)
   ===================================

/**
 * Load products from API
 * Replace with actual API endpoint
 */
async function loadProducts() {
    setLoading(true);
    
    try {
        // Simulate API call - replace with actual fetch
        await new Promise(resolve => setTimeout(resolve, 1000));
        
        // In production, this would be:
        // const response = await fetch('/api/products');
        // const data = await response.json();
        // state.products = data.products;
        
        state.products = sampleProducts;
        applyFilters();
        
    } catch (error) {
        console.error('Error loading products:', error);
        showErrorState();
    }
}

/**
 * Get user location (optional)
 */
function getUserLocation() {
    if ('geolocation' in navigator) {
        navigator.geolocation.getCurrentPosition(
            (position) => {
                state.userLocation = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                console.log('User location:', state.userLocation);
            },
            (error) => {
                console.log('Geolocation error:', error);
            }
        );
    }
}

// ===================================
   INITIALIZATION
   ===================================

/**
 * Initialize the browse page
 */
function initializeBrowsePage() {
    initializeElements();
    setupEventListeners();
    getUserLocation();
    loadProducts();

    // Load favorites from API
    loadFavorites();

    // Load cart from cookies
    const savedCart = getCookie(getCartCookieName());

    if (savedCart) {
        try {
            state.cart = JSON.parse(savedCart);
            updateCartUI();
        } catch (e) {
            console.error('Error loading cart:', e);
        }
    }
}

/**
 * Save cart to cookies
 */
function saveCartToCookie() {
    setCookie(getCartCookieName(), state.cart, 7);
}

// Save cart periodically and on page unload
setInterval(saveCartToCookie, 5000);
window.addEventListener('beforeunload', saveCartToCookie);

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeBrowsePage);
} else {
    initializeBrowsePage();
}
}
