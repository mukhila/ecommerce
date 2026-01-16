/**
 * Cart Management JavaScript with Comprehensive Error Handling
 * Handles Add to Cart, Update, Remove functionality with AJAX
 */

// CSRF Token setup for AJAX requests
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

// Configuration
const CONFIG = {
    requestTimeout: 30000, // 30 seconds
    retryAttempts: 2,
    retryDelay: 1000, // 1 second
    openOffcanvasAfterAdd: true // Open cart offcanvas after adding item
};

/**
 * Validate CSRF token exists
 */
function validateCsrfToken() {
    if (!csrfToken) {
        console.error('CSRF token not found');
        showNotification('Security token missing. Please refresh the page.', 'error');
        return false;
    }
    return true;
}

/**
 * Create fetch request with timeout
 */
function fetchWithTimeout(url, options, timeout = CONFIG.requestTimeout) {
    return Promise.race([
        fetch(url, options),
        new Promise((_, reject) =>
            setTimeout(() => reject(new Error('Request timeout')), timeout)
        )
    ]);
}

/**
 * Handle API response errors
 */
async function handleResponse(response) {
    let data;
    try {
        data = await response.json();
    } catch (e) {
        throw new Error('Invalid server response');
    }

    if (!response.ok) {
        // Handle different HTTP status codes
        switch (response.status) {
            case 400:
                throw new Error(data.message || 'Invalid request');
            case 404:
                throw new Error(data.message || 'Item not found');
            case 422:
                const validationErrors = data.errors ? Object.values(data.errors).flat().join(', ') : data.message;
                throw new Error(validationErrors || 'Validation error');
            case 500:
                throw new Error(data.message || 'Server error. Please try again later.');
            default:
                throw new Error(data.message || 'An error occurred');
        }
    }

    return data;
}

/**
 * Retry failed request
 */
async function retryRequest(requestFn, attempts = CONFIG.retryAttempts) {
    for (let i = 0; i <= attempts; i++) {
        try {
            return await requestFn();
        } catch (error) {
            if (i === attempts || error.message === 'Request timeout' || error.message.includes('Security token')) {
                throw error;
            }
            // Wait before retrying
            await new Promise(resolve => setTimeout(resolve, CONFIG.retryDelay * (i + 1)));
        }
    }
}

/**
 * Add product to cart
 */
async function addToCart(productId, quantity = 1, attributes = {}) {
    if (!validateCsrfToken()) return;

    // Validate inputs
    if (!productId || productId <= 0) {
        showNotification('Invalid product', 'error');
        return;
    }

    if (!quantity || quantity <= 0) {
        showNotification('Invalid quantity', 'error');
        return;
    }

    // Show loading state
    const button = document.querySelector(`[data-product-id="${productId}"]`);
    const originalContent = button ? button.innerHTML : '';
    if (button) {
        button.disabled = true;
        button.innerHTML = '<i class="ri-loader-4-line"></i> Adding...';
    }

    try {
        const requestBody = {
            product_id: parseInt(productId),
            quantity: parseInt(quantity)
        };

        // Add attributes if provided
        if (attributes && Object.keys(attributes).length > 0) {
            requestBody.attributes = attributes;
        }

        const data = await retryRequest(async () => {
            const response = await fetchWithTimeout('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify(requestBody)
            });
            return handleResponse(response);
        });

        if (data.success) {
            showNotification(data.message, 'success');
            updateCartCount(data.cart_count);

            // Refresh and open cart offcanvas
            if (CONFIG.openOffcanvasAfterAdd) {
                refreshCartOffcanvas().then(() => {
                    openCartOffcanvas();
                });
            }
        } else {
            showNotification(data.message || 'Failed to add item to cart', 'error');
        }
    } catch (error) {
        console.error('Add to cart error:', error);
        if (error.message === 'Request timeout') {
            showNotification('Request timed out. Please check your connection and try again.', 'error');
        } else if (error.message === 'Failed to fetch' || error.name === 'TypeError') {
            showNotification('Network error. Please check your internet connection.', 'error');
        } else {
            showNotification(error.message || 'Failed to add item to cart', 'error');
        }
    } finally {
        // Restore button state
        if (button) {
            button.disabled = false;
            button.innerHTML = originalContent;
        }
    }
}

/**
 * Update cart item quantity
 */
async function updateCartItem(itemId, quantity) {
    if (!validateCsrfToken()) return;

    // Validate inputs
    if (!itemId || itemId <= 0) {
        showNotification('Invalid item', 'error');
        return;
    }

    if (!quantity || quantity <= 0) {
        showNotification('Quantity must be at least 1', 'error');
        return;
    }

    if (quantity > 999) {
        showNotification('Quantity cannot exceed 999', 'error');
        return;
    }

    try {
        const data = await retryRequest(async () => {
            const response = await fetchWithTimeout(`/cart/update/${itemId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    quantity: parseInt(quantity)
                })
            });
            return handleResponse(response);
        });

        if (data.success) {
            // Update item subtotal - look for all elements with this ID
            const itemSubtotalElements = document.querySelectorAll(`#item-subtotal-${itemId}`);
            itemSubtotalElements.forEach(element => {
                element.textContent = `₹${parseFloat(data.item_subtotal).toFixed(2)}`;
            });

            // Update cart total and count
            updateCartTotal(data.cart_total);
            updateCartCount(data.cart_count);

            showNotification(data.message, 'success');
        } else {
            showNotification(data.message || 'Failed to update cart', 'error');
            // Reload page to sync cart state
            setTimeout(() => location.reload(), 1500);
        }
    } catch (error) {
        console.error('Update cart error:', error);
        if (error.message === 'Request timeout') {
            showNotification('Request timed out. Please try again.', 'error');
        } else if (error.message === 'Failed to fetch' || error.name === 'TypeError') {
            showNotification('Network error. Please check your connection.', 'error');
        } else {
            showNotification(error.message || 'Failed to update cart', 'error');
        }
        // Reload page to sync cart state on error
        setTimeout(() => location.reload(), 2000);
    }
}

/**
 * Remove item from cart
 */
async function removeCartItem(itemId) {
    if (!validateCsrfToken()) return;

    if (!confirm('Are you sure you want to remove this item?')) {
        return;
    }

    // Validate input
    if (!itemId || itemId <= 0) {
        showNotification('Invalid item', 'error');
        return;
    }

    try {
        const data = await retryRequest(async () => {
            const response = await fetchWithTimeout(`/cart/remove/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            return handleResponse(response);
        });

        if (data.success) {
            // Remove item row from DOM with animation
            const itemRow = document.querySelector(`#cart-item-${itemId}`);
            if (itemRow) {
                itemRow.style.transition = 'opacity 0.3s';
                itemRow.style.opacity = '0';
                setTimeout(() => itemRow.remove(), 300);
            }

            // Update cart total and count
            updateCartTotal(data.cart_total);
            updateCartCount(data.cart_count);

            // Check if cart is empty
            if (data.cart_count === 0) {
                setTimeout(() => location.reload(), 500); // Reload to show empty cart message
            }

            showNotification(data.message, 'success');
        } else {
            showNotification(data.message || 'Failed to remove item', 'error');
        }
    } catch (error) {
        console.error('Remove item error:', error);
        if (error.message === 'Request timeout') {
            showNotification('Request timed out. Please try again.', 'error');
        } else if (error.message === 'Failed to fetch' || error.name === 'TypeError') {
            showNotification('Network error. Please check your connection.', 'error');
        } else {
            showNotification(error.message || 'Failed to remove item', 'error');
        }
    }
}

/**
 * Clear entire cart
 */
async function clearCart() {
    if (!validateCsrfToken()) return;

    if (!confirm('Are you sure you want to clear your entire cart?')) {
        return;
    }

    try {
        const data = await retryRequest(async () => {
            const response = await fetchWithTimeout('/cart/clear', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            return handleResponse(response);
        });

        if (data.success) {
            showNotification(data.message, 'success');
            setTimeout(() => location.reload(), 500); // Reload to show empty cart
        } else {
            showNotification(data.message || 'Failed to clear cart', 'error');
        }
    } catch (error) {
        console.error('Clear cart error:', error);
        if (error.message === 'Request timeout') {
            showNotification('Request timed out. Please try again.', 'error');
        } else if (error.message === 'Failed to fetch' || error.name === 'TypeError') {
            showNotification('Network error. Please check your connection.', 'error');
        } else {
            showNotification(error.message || 'Failed to clear cart', 'error');
        }
    }
}

/**
 * Update cart count in header
 */
function updateCartCount(count) {
    const cartCountElements = document.querySelectorAll('.cart-count, .cart_qty_cls');
    const safeCount = parseInt(count) || 0;

    cartCountElements.forEach(element => {
        element.textContent = safeCount;
        if (safeCount > 0) {
            element.style.display = 'inline-block';
        } else {
            element.style.display = 'none';
        }
    });
}

/**
 * Update cart total
 */
function updateCartTotal(total) {
    const cartTotalElements = document.querySelectorAll('.cart-total, #cart-total');
    const safeTotal = parseFloat(total) || 0;

    cartTotalElements.forEach(element => {
        element.textContent = `₹${safeTotal.toFixed(2)}`;
    });
}

/**
 * Show notification message
 */
function showNotification(message, type = 'success') {
    // Sanitize message to prevent XSS
    const sanitizedMessage = String(message).replace(/</g, '&lt;').replace(/>/g, '&gt;');

    // Check if notification container exists
    let notificationContainer = document.querySelector('.notification-container');

    if (!notificationContainer) {
        notificationContainer = document.createElement('div');
        notificationContainer.className = 'notification-container';
        notificationContainer.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
        `;
        document.body.appendChild(notificationContainer);
    }

    // Create notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
    notification.style.cssText = `
        min-width: 300px;
        margin-bottom: 10px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        animation: slideIn 0.3s ease-out;
    `;

    notification.innerHTML = `
        <strong>${type === 'success' ? 'Success!' : 'Error!'}</strong> ${sanitizedMessage}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;

    notificationContainer.appendChild(notification);

    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.style.transition = 'opacity 0.3s';
        notification.style.opacity = '0';
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}

/**
 * Initialize quantity increment/decrement buttons
 */
document.addEventListener('DOMContentLoaded', function() {
    // Add CSS animation for notifications
    if (!document.querySelector('#cart-notification-styles')) {
        const style = document.createElement('style');
        style.id = 'cart-notification-styles';
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
        `;
        document.head.appendChild(style);
    }

    // Quantity increment buttons
    document.querySelectorAll('.quantity-right-plus').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.closest('.qty-box').querySelector('.input-number');
            if (!input) return;

            const currentVal = parseInt(input.value) || 1;
            const max = parseInt(input.getAttribute('max')) || 999;

            if (currentVal < max) {
                const newVal = currentVal + 1;
                input.value = newVal;

                // If on cart page, trigger update
                if (input.dataset.itemId) {
                    updateCartItem(input.dataset.itemId, newVal);
                }
            } else {
                showNotification(`Maximum quantity is ${max}`, 'error');
            }
        });
    });

    // Quantity decrement buttons
    document.querySelectorAll('.quantity-left-minus').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.closest('.qty-box').querySelector('.input-number');
            if (!input) return;

            const currentVal = parseInt(input.value) || 1;

            if (currentVal > 1) {
                const newVal = currentVal - 1;
                input.value = newVal;

                // If on cart page, trigger update
                if (input.dataset.itemId) {
                    updateCartItem(input.dataset.itemId, newVal);
                }
            } else {
                showNotification('Minimum quantity is 1', 'error');
            }
        });
    });

    // Add to cart button clicks
    document.querySelectorAll('[data-action="add-to-cart"]').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const productId = this.dataset.productId;
            const quantityInput = document.querySelector(`input[name="quantity"]`);
            const quantity = quantityInput ? parseInt(quantityInput.value) : 1;

            addToCart(productId, quantity);
        });
    });

    // Load cart count on page load
    loadCartCount();
});

/**
 * Load cart count from server
 */
async function loadCartCount() {
    try {
        const response = await fetchWithTimeout('/cart/count', {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        }, 5000); // Shorter timeout for count request

        const data = await response.json();
        if (data.count !== undefined) {
            updateCartCount(data.count);
        }
    } catch (error) {
        console.error('Error loading cart count:', error);
        // Silently fail for cart count - not critical
        updateCartCount(0);
    }
}

/**
 * Update cart item from offcanvas
 */
async function updateCartItemOffcanvas(itemId, quantity) {
    if (quantity < 1) {
        showNotification('Minimum quantity is 1', 'error');
        return;
    }

    try {
        const data = await retryRequest(async () => {
            const response = await fetchWithTimeout(`/cart/update/${itemId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ quantity: parseInt(quantity) })
            });
            return handleResponse(response);
        });

        if (data.success) {
            showNotification(data.message, 'success');
            updateCartCount(data.cart_count);
            // Refresh offcanvas content
            await refreshCartOffcanvas();
        } else {
            showNotification(data.message || 'Failed to update cart', 'error');
        }
    } catch (error) {
        console.error('Update cart error:', error);
        showNotification(error.message || 'Failed to update cart', 'error');
    }
}

/**
 * Remove cart item from offcanvas
 */
async function removeCartItemOffcanvas(itemId) {
    if (!confirm('Are you sure you want to remove this item?')) {
        return;
    }

    try {
        const data = await retryRequest(async () => {
            const response = await fetchWithTimeout(`/cart/remove/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            });
            return handleResponse(response);
        });

        if (data.success) {
            showNotification(data.message, 'success');
            updateCartCount(data.cart_count);
            // Refresh offcanvas content
            await refreshCartOffcanvas();
        } else {
            showNotification(data.message || 'Failed to remove item', 'error');
        }
    } catch (error) {
        console.error('Remove item error:', error);
        showNotification(error.message || 'Failed to remove item', 'error');
    }
}

/**
 * Refresh cart offcanvas content via AJAX
 */
async function refreshCartOffcanvas() {
    try {
        const response = await fetchWithTimeout('/cart/offcanvas', {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        }, 10000);

        const data = await response.json();

        if (data.success) {
            // Update offcanvas body content
            const offcanvasBody = document.getElementById('cart-offcanvas-body');
            if (offcanvasBody) {
                offcanvasBody.innerHTML = data.html;
            }

            // Update cart count in header
            const offcanvasCount = document.getElementById('offcanvas-cart-count');
            if (offcanvasCount) {
                offcanvasCount.textContent = data.count;
            }

            updateCartCount(data.count);
        }
    } catch (error) {
        console.error('Error refreshing cart offcanvas:', error);
    }
}

/**
 * Open cart offcanvas
 */
function openCartOffcanvas() {
    const offcanvasElement = document.getElementById('cartOffcanvas');
    if (offcanvasElement && typeof bootstrap !== 'undefined') {
        const offcanvas = bootstrap.Offcanvas.getOrCreateInstance(offcanvasElement);
        offcanvas.show();
    }
}

/**
 * Close cart offcanvas
 */
function closeCartOffcanvas() {
    const offcanvasElement = document.getElementById('cartOffcanvas');
    if (offcanvasElement && typeof bootstrap !== 'undefined') {
        const offcanvas = bootstrap.Offcanvas.getInstance(offcanvasElement);
        if (offcanvas) {
            offcanvas.hide();
        }
    }
}

// ============================================
// COOKIE CONSENT MANAGEMENT
// ============================================

/**
 * Check and show cookie consent bar
 */
function checkCookieConsent() {
    const cookieConsent = localStorage.getItem('cookieConsent');
    const cookieBar = document.getElementById('cookieBar');

    if (!cookieConsent && cookieBar) {
        // Show cookie bar after a short delay
        setTimeout(() => {
            cookieBar.style.display = 'block';
        }, 1000);
    }
}

/**
 * Accept cookies
 */
function acceptCookies() {
    localStorage.setItem('cookieConsent', 'accepted');
    localStorage.setItem('cookieConsentDate', new Date().toISOString());
    hideCookieBar();
}

/**
 * Decline cookies
 */
function declineCookies() {
    localStorage.setItem('cookieConsent', 'declined');
    localStorage.setItem('cookieConsentDate', new Date().toISOString());
    hideCookieBar();
}

/**
 * Hide cookie bar with animation
 */
function hideCookieBar() {
    const cookieBar = document.getElementById('cookieBar');
    if (cookieBar) {
        cookieBar.style.transition = 'opacity 0.3s, transform 0.3s';
        cookieBar.style.opacity = '0';
        cookieBar.style.transform = 'translateY(100%)';
        setTimeout(() => {
            cookieBar.style.display = 'none';
        }, 300);
    }
}

// Initialize cookie consent check on page load
document.addEventListener('DOMContentLoaded', function() {
    checkCookieConsent();
});
