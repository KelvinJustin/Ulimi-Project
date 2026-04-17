// Browse Page JavaScript - Product Management System

class ProductManager {
    constructor() {
        this.products = [];
        this.filters = {
            category: 'all',
            location: 'all',
            search: '',
            sortBy: 'newest'
        };
        this.isLoading = false;
        this.currentPage = 1;
        this.productsPerPage = 12;
        this.userFavorites = new Set(); // Track favorited listing IDs
        this.isLoggedIn = false;
        this.userId = null;
        
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.loadFavorites();
        // Products are already rendered server-side, no need to load via AJAX
    }

    setupEventListeners() {
        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const clearSearch = document.getElementById('clearSearch');
        
        if (searchInput) {
            searchInput.addEventListener('input', this.debounce(() => {
                this.filters.search = searchInput.value.trim();
                this.loadProducts();
            }, 300));
        }

        if (clearSearch) {
            clearSearch.addEventListener('click', () => {
                searchInput.value = '';
                this.filters.search = '';
                this.loadProducts();
            });
        }

        // Filter dropdowns
        const categoryFilter = document.getElementById('categoryFilter');
        const locationFilter = document.getElementById('locationFilter');
        const sortBy = document.getElementById('sortBy');

        if (categoryFilter) {
            categoryFilter.addEventListener('change', () => {
                this.filters.category = categoryFilter.value;
                this.loadProducts();
            });
        }

        if (locationFilter) {
            locationFilter.addEventListener('change', () => {
                this.filters.location = locationFilter.value;
                this.loadProducts();
            });
        }

        if (sortBy) {
            sortBy.addEventListener('change', () => {
                this.filters.sortBy = sortBy.value;
                this.loadProducts();
            });
        }

        // Clear all filters
        const clearAllFilters = document.getElementById('clearAllFilters');
        if (clearAllFilters) {
            clearAllFilters.addEventListener('click', () => {
                this.resetFilters();
            });
        }

        // Reset filters
        const resetFilters = document.getElementById('resetFilters');
        if (resetFilters) {
            resetFilters.addEventListener('click', () => {
                this.resetFilters();
            });
        }

        // Retry loading
        const retryLoad = document.getElementById('retryLoad');
        if (retryLoad) {
            retryLoad.addEventListener('click', () => {
                this.loadProducts();
            });
        }
    }

    resetFilters() {
        this.filters = {
            category: 'all',
            location: 'all',
            search: '',
            sortBy: 'newest'
        };

        // Reset form elements
        const searchInput = document.getElementById('searchInput');
        const categoryFilter = document.getElementById('categoryFilter');
        const locationFilter = document.getElementById('locationFilter');
        const sortBy = document.getElementById('sortBy');

        if (searchInput) searchInput.value = '';
        if (categoryFilter) categoryFilter.value = 'all';
        if (locationFilter) locationFilter.value = 'all';
        if (sortBy) sortBy.value = 'newest';

        this.loadProducts();
    }

    async loadFavorites() {
        try {
            const response = await fetch(`${this.getBaseUrl()}/api/favorites`);
            const data = await response.json();
            
            if (data.success && data.favorites) {
                this.userFavorites = new Set(data.favorites);
                this.isLoggedIn = true;
            }
        } catch (error) {
            // User might not be logged in, that's okay
            console.log('Could not load favorites:', error);
            this.isLoggedIn = false;
        }
    }

    async loadProducts() {
        if (this.isLoading) return;

        this.isLoading = true;
        this.showLoadingState();

        console.log('Loading products...');
        console.log('Base URL:', this.getBaseUrl());
        console.log('Filters:', this.filters);

        try {
            const url = `${this.getBaseUrl()}/api/products`;
            console.log('Fetching from:', url);
            
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(this.filters)
            });

            console.log('Response status:', response.status);
            console.log('Response ok:', response.ok);

            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: Failed to fetch products`);
            }

            const data = await response.json();
            console.log('API Response:', data);
            
            if (data.success) {
                console.log('Products loaded:', data.count);
                this.products = data.products;
                this.renderProducts();
                this.updateResultsCount(data.count);
                this.hideEmptyState();
            } else {
                console.error('API Error:', data.message);
                throw new Error(data.message || 'Unknown error');
            }
        } catch (error) {
            console.error('Error loading products:', error);
            this.showErrorState();
        } finally {
            this.isLoading = false;
            this.hideLoadingState();
        }
    }

    renderProducts() {
        console.log('Rendering products...');
        console.log('Products array:', this.products);
        
        const productsGrid = document.getElementById('productsGrid');
        console.log('Products grid element:', productsGrid);
        
        if (!productsGrid) {
            console.error('Products grid element not found!');
            return;
        }

        if (this.products.length === 0) {
            console.log('No products to render, showing empty state');
            this.showEmptyState();
            return;
        }

        console.log('Creating product cards...');
        const productsHTML = this.products.map(product => this.createProductCard(product)).join('');
        console.log('Products HTML length:', productsHTML.length);
        
        productsGrid.innerHTML = productsHTML;
        console.log('Products rendered successfully');
    }

    createProductCard(product) {
        const baseUrl = this.getBaseUrl();
        const imageUrl = product.image ? `${baseUrl}/${product.image}` : `${baseUrl}/assets/images/placeholder-product.jpg`;
        const isFavorited = this.userFavorites.has(product.id);
        const heartIcon = isFavorited ? 'fas fa-heart' : 'far fa-heart';
        const favoriteClass = isFavorited ? 'favorited' : '';
        
        return `
            <div class="product-card" data-product-id="${product.id}">
                <div class="product-images">
                    <img src="${imageUrl}" alt="${this.escapeHtml(product.title)}" class="product-image" 
                         onerror="this.src='${baseUrl}/assets/images/placeholder-product.jpg'">
                    ${this.getProductBadge(product)}
                </div>
                <div class="product-info">
                    <div class="product-category">${this.formatCategory(product.category)}</div>
                    <h3 class="product-title">${this.escapeHtml(product.title)}</h3>
                    <div class="product-rating">
                        <div class="stars">
                            ${this.generateStars(4.5)}
                        </div>
                        <span class="rating-count">(${Math.floor(Math.random() * 50) + 5} reviews)</span>
                    </div>
                    <div class="product-price">
                        MWK ${product.price.toFixed(2)}
                        <span class="price-unit">/${this.formatPriceUnit(product.price_unit || 'kg')}</span>
                    </div>
                    <div class="product-meta">
                        <div class="seller-info">
                            <i class="fas fa-user-circle"></i>
                            <span class="seller-name">Verified Seller</span>
                            <span class="verified-badge">Verified</span>
                        </div>
                        <div class="location-info">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>${this.formatLocation(product.location)}</span>
                        </div>
                    </div>
                    <div class="product-details">
                        <span class="condition">Good Condition</span>
                        <span class="stock-level">In Stock: ${product.quantity} ${product.price_unit || 'kg'}</span>
                        <span class="shipping">Available Now</span>
                    </div>
                    <div class="product-actions">
                        <button class="btn-add-cart" onclick="productManager.addToCart(${product.id})">
                            <i class="fas fa-shopping-cart"></i>
                            Add to Cart
                        </button>
                        <button class="btn-favorite ${favoriteClass}" onclick="productManager.toggleFavorite(${product.id})">
                            <i class="${heartIcon}"></i>
                        </button>
                        <button class="btn-quick-view" onclick="productManager.quickView(${product.id})">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    getProductBadge(product) {
        const badges = [];
        
        // New badge for products created in the last 7 days
        const createdDate = new Date(product.created_at);
        const daysSinceCreated = Math.floor((Date.now() - createdDate) / (1000 * 60 * 60 * 24));
        if (daysSinceCreated <= 7) {
            badges.push('<span class="product-badge new">New</span>');
        }

        // Popular badge for products with good ratings (simulated)
        if (Math.random() > 0.7) {
            badges.push('<span class="product-badge popular">Popular</span>');
        }

        // Sale badge for discounted items (simulated)
        if (Math.random() > 0.8) {
            badges.push('<span class="product-badge sale">Sale</span>');
        }

        return badges.join('');
    }

    generateStars(rating) {
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

    formatCategory(category) {
        const categories = {
            'grains': 'Grains & Cereals',
            'legumes': 'Legumes & Pulses',
            'vegetables': 'Vegetables',
            'fruits': 'Fruits',
            'cash-crops': 'Cash Crops',
            'livestock': 'Livestock',
            'inputs': 'Farm Inputs'
        };
        return categories[category] || category;
    }

    formatLocation(location) {
        const locations = {
            'lilongwe': 'Lilongwe, Central',
            'blantyre': 'Blantyre, Southern',
            'mzuzu': 'Mzuzu, Northern',
            'zomba': 'Zomba',
            'kasungu': 'Kasungu',
            'mangochi': 'Mangochi',
            'karonga': 'Karonga',
            'dedza': 'Dedza',
            'salima': 'Salima'
        };
        return locations[location] || location;
    }

    formatPriceUnit(unit) {
        const units = {
            'kg': 'kg',
            'bag': 'bag',
            'ton': 'ton',
            'piece': 'piece',
            'liter': 'liter'
        };
        return units[unit] || 'kg';
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    updateResultsCount(count) {
        const resultsCount = document.getElementById('resultsCount');
        if (resultsCount) {
            resultsCount.textContent = count;
        }
    }

    showLoadingState() {
        const loadingState = document.getElementById('loadingState');
        const productsGrid = document.getElementById('productsGrid');
        
        if (loadingState) loadingState.style.display = 'block';
        if (productsGrid) productsGrid.style.display = 'none';
        this.hideEmptyState();
        this.hideErrorState();
    }

    hideLoadingState() {
        const loadingState = document.getElementById('loadingState');
        const productsGrid = document.getElementById('productsGrid');
        
        if (loadingState) loadingState.style.display = 'none';
        if (productsGrid) productsGrid.style.display = 'grid';
    }

    showEmptyState() {
        const emptyState = document.getElementById('emptyState');
        const productsGrid = document.getElementById('productsGrid');
        
        if (emptyState) emptyState.style.display = 'block';
        if (productsGrid) productsGrid.style.display = 'none';
        this.hideErrorState();
    }

    hideEmptyState() {
        const emptyState = document.getElementById('emptyState');
        if (emptyState) emptyState.style.display = 'none';
    }

    showErrorState() {
        const errorState = document.getElementById('errorState');
        const productsGrid = document.getElementById('productsGrid');
        
        if (errorState) errorState.style.display = 'block';
        if (productsGrid) productsGrid.style.display = 'none';
        this.hideEmptyState();
    }

    hideErrorState() {
        const errorState = document.getElementById('errorState');
        if (errorState) errorState.style.display = 'none';
    }

    addToCart(productId) {
        // Placeholder for cart functionality
        console.log('Add to cart:', productId);
        // TODO: Implement cart functionality
    }

    async toggleFavorite(productId) {
        if (!this.isLoggedIn) {
            alert('Please log in to add items to your favorites');
            return;
        }

        const isFavorited = this.userFavorites.has(productId);
        const endpoint = isFavorited ? '/api/favorites/remove' : '/api/favorites/add';
        const button = document.querySelector(`[data-product-id="${productId}"] .btn-favorite`);

        try {
            const response = await fetch(`${this.getBaseUrl()}${endpoint}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ listing_id: productId })
            });

            const data = await response.json();

            if (data.success) {
                if (isFavorited) {
                    this.userFavorites.delete(productId);
                    if (button) {
                        button.innerHTML = '<i class="far fa-heart"></i>';
                        button.classList.remove('favorited');
                    }
                } else {
                    this.userFavorites.add(productId);
                    if (button) {
                        button.innerHTML = '<i class="fas fa-heart"></i>';
                        button.classList.add('favorited');
                    }
                }
            } else {
                alert(data.message || 'Failed to update favorites');
            }
        } catch (error) {
            console.error('Error toggling favorite:', error);
            alert('Failed to update favorites. Please try again.');
        }
    }

    quickView(productId) {
        // Placeholder for quick view functionality
        console.log('Quick view:', productId);
        // TODO: Implement quick view modal
    }

    getBaseUrl() {
        // Get base URL from the current page
        const path = window.location.pathname;
        const segments = path.split('/');
        return segments.slice(0, -1).join('/');
    }

    debounce(func, wait) {
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
}

// Initialize the product manager when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.productManager = new ProductManager();
});
