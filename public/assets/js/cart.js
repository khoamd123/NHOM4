/**
 * Shopping Cart JavaScript Functions
 * Handles all cart-related interactions
 */

// Configuration
const CART_CONFIG = {
    baseUrl: '/NHOM4_DU_AN_1',
    endpoints: {
        add: '/controllers/CartController.php?action=add',
        update: '/controllers/CartController.php?action=update',
        remove: '/controllers/CartController.php?action=remove',
        clear: '/controllers/CartController.php?action=clear',
        count: '/controllers/CartController.php?action=count',
        mini: '/controllers/CartController.php?action=mini'
    }
};

// Utility functions
function showToast(message, type = 'success') {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
    toast.style.cssText = `
        top: 20px; 
        right: 20px; 
        z-index: 9999; 
        min-width: 300px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    `;
    
    toast.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(toast);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            toast.remove();
        }
    }, 3000);
}

function updateCartBadge(count) {
    const badge = document.querySelector('.cart-badge, .cart-count');
    if (badge) {
        badge.textContent = count;
        badge.style.display = count > 0 ? 'inline' : 'none';
    }
}

function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN').format(price) + '₫';
}

// Main cart functions
function addToCart(variantId, quantity = 1) {
    if (!variantId) {
        showToast('Vui lòng chọn size và màu sắc', 'warning');
        return;
    }
    
    // Show loading state
    const addButton = document.querySelector(`[onclick*="addToCart(${variantId}"]`);
    if (addButton) {
        addButton.disabled = true;
        addButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang thêm...';
    }
    
    fetch(CART_CONFIG.baseUrl + CART_CONFIG.endpoints.add, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `variant_id=${variantId}&quantity=${quantity}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            updateCartBadge(data.cart_count);
            
            // Update mini cart if exists
            updateMiniCart();
        } else {
            showToast(data.message, 'danger');
            
            // Redirect to login if needed
            if (data.redirect) {
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 1500);
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Có lỗi xảy ra. Vui lòng thử lại.', 'danger');
    })
    .finally(() => {
        // Restore button state
        if (addButton) {
            addButton.disabled = false;
            addButton.innerHTML = '<i class="fas fa-shopping-cart"></i> Thêm vào giỏ hàng';
        }
    });
}

// Prevent multiple simultaneous requests
const updateQueue = new Map();

function updateQuantity(variantId, newQuantity) {
    if (newQuantity < 0) return;
    
    // Cancel any pending request for this variant
    if (updateQueue.has(variantId)) {
        clearTimeout(updateQueue.get(variantId));
    }
    
    // Debounce the request
    const timeoutId = setTimeout(() => {
        updateQueue.delete(variantId);
        
        // Show loading state
        const cartItem = document.querySelector(`[data-variant-id="${variantId}"]`);
        if (cartItem) {
            const quantityInput = cartItem.querySelector('.quantity-input');
            if (quantityInput) {
                quantityInput.disabled = true;
            }
        }
        
        fetch(CART_CONFIG.baseUrl + CART_CONFIG.endpoints.update, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `variant_id=${variantId}&quantity=${newQuantity}`
        })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (newQuantity === 0) {
                // Remove item from DOM
                const cartItem = document.querySelector(`[data-variant-id="${variantId}"]`);
                if (cartItem) {
                    cartItem.remove();
                }
                showToast('Đã xóa sản phẩm khỏi giỏ hàng', 'info');
            } else {
                // Update total price for this item
                const cartItem = document.querySelector(`[data-variant-id="${variantId}"]`);
                if (cartItem) {
                    const quantityInput = cartItem.querySelector('.quantity-input');
                    if (quantityInput) {
                        quantityInput.value = newQuantity;
                    }
                    
                    // Update item total (would need price per unit to calculate)
                    // This is simplified - in real app, you'd return the new item total
                }
                showToast('Cập nhật số lượng thành công', 'success');
            }
            
            updateCartBadge(data.cart_count);
            updateCartSummary(data.cart_total);
            updateMiniCart();
            
            // Check if cart is empty and reload page
            if (data.cart_count === 0) {
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            }
        } else {
            showToast(data.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Có lỗi xảy ra. Vui lòng thử lại.', 'danger');
    })
    .finally(() => {
        // Re-enable input
        const cartItem = document.querySelector(`[data-variant-id="${variantId}"]`);
        if (cartItem) {
            const quantityInput = cartItem.querySelector('.quantity-input');
            if (quantityInput) {
                quantityInput.disabled = false;
            }
        }
    });
    
    }, 300); // 300ms debounce
    
    updateQueue.set(variantId, timeoutId);
}

function removeFromCart(variantId) {
    if (!confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?')) {
        return;
    }
    
    fetch(CART_CONFIG.baseUrl + CART_CONFIG.endpoints.remove, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `variant_id=${variantId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove item from DOM
            const cartItem = document.querySelector(`[data-variant-id="${variantId}"]`);
            if (cartItem) {
                cartItem.style.transition = 'opacity 0.3s ease';
                cartItem.style.opacity = '0';
                setTimeout(() => {
                    cartItem.remove();
                }, 300);
            }
            
            showToast(data.message, 'success');
            updateCartBadge(data.cart_count);
            updateCartSummary(data.cart_total);
            updateMiniCart();
            
            // Check if cart is empty and reload page
            if (data.cart_count === 0) {
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            }
        } else {
            showToast(data.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Có lỗi xảy ra. Vui lòng thử lại.', 'danger');
    });
}

function clearCart() {
    if (!confirm('Bạn có chắc chắn muốn xóa toàn bộ giỏ hàng?')) {
        return;
    }
    
    fetch(CART_CONFIG.baseUrl + CART_CONFIG.endpoints.clear, {
        method: 'POST'
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            updateCartBadge(0);
            
            // Reload page to show empty cart
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showToast(data.message, 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Có lỗi xảy ra. Vui lòng thử lại.', 'danger');
    });
}

function updateCartSummary(newTotal) {
    const totalElements = document.querySelectorAll('.cart-total, .cart-subtotal');
    totalElements.forEach(element => {
        element.textContent = newTotal + '₫';
    });
}

function updateMiniCart() {
    fetch(CART_CONFIG.baseUrl + CART_CONFIG.endpoints.mini)
    .then(response => response.json())
    .then(data => {
        // Update mini cart if it exists
        const miniCartContainer = document.querySelector('.mini-cart-items');
        if (miniCartContainer) {
            // This would update the mini cart dropdown
            // Implementation depends on your header structure
        }
    })
    .catch(error => {
        console.error('Error updating mini cart:', error);
    });
}

function loadCartCount() {
    fetch(CART_CONFIG.baseUrl + CART_CONFIG.endpoints.count)
    .then(response => response.json())
    .then(data => {
        updateCartBadge(data.cart_count);
    })
    .catch(error => {
        console.error('Error loading cart count:', error);
    });
}

// Checkout function (placeholder)
function proceedToCheckout() {
    showToast('Chức năng thanh toán đang được phát triển', 'info');
    // window.location.href = CART_CONFIG.baseUrl + '/views/checkout/checkout.php';
}

// Coupon function (placeholder)
function applyCoupon() {
    const couponCode = document.getElementById('coupon-code').value.trim();
    if (!couponCode) {
        showToast('Vui lòng nhập mã giảm giá', 'warning');
        return;
    }
    
    showToast('Chức năng mã giảm giá đang được phát triển', 'info');
}

// Product detail page functions
function selectVariant(variantId, element) {
    // Remove active class from all variants
    document.querySelectorAll('.variant-option').forEach(option => {
        option.classList.remove('active');
    });
    
    // Add active class to selected variant
    element.classList.add('active');
    
    // Update add to cart button
    const addButton = document.querySelector('.add-to-cart-btn');
    if (addButton) {
        addButton.onclick = () => addToCart(variantId);
        addButton.disabled = false;
    }
    
    // Update selected variant info
    const variantInfo = element.dataset;
    if (variantInfo) {
        // Update price if shown
        const priceElement = document.querySelector('.selected-price');
        if (priceElement && variantInfo.price) {
            priceElement.textContent = formatPrice(variantInfo.price);
        }
        
        // Update stock info
        const stockElement = document.querySelector('.selected-stock');
        if (stockElement && variantInfo.stock) {
            stockElement.textContent = `Còn ${variantInfo.stock} sản phẩm`;
            stockElement.className = `selected-stock ${variantInfo.stock > 0 ? 'text-success' : 'text-danger'}`;
        }
    }
}

// Initialize cart functionality
document.addEventListener('DOMContentLoaded', function() {
    // Load cart count on page load
    loadCartCount();
    
    // Auto-refresh cart after login
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('cart_refresh') === '1') {
        // Force refresh cart after login
        setTimeout(() => {
            loadCartCount();
            
            // Get cart count and show welcome message
            fetch(CART_CONFIG.baseUrl + CART_CONFIG.endpoints.count)
            .then(response => response.json())
            .then(data => {
                if (data.cart_count > 0) {
                    showToast(`🎉 Chào mừng bạn trở lại! Giỏ hàng có ${data.cart_count} sản phẩm đang chờ`, 'success');
                }
            })
            .catch(error => {
                console.error('Error checking cart:', error);
            });
        }, 500);
        
        // Clean URL without reload
        if (history.replaceState) {
            const cleanUrl = window.location.pathname;
            history.replaceState({}, document.title, cleanUrl);
        }
    }
    
    // Handle quantity input changes - remove inline handlers to avoid conflicts
    document.querySelectorAll('.quantity-input').forEach(input => {
        // Remove any existing inline handlers
        input.removeAttribute('onchange');
        
        // Add proper event listener
        input.addEventListener('change', function() {
            const variantId = this.closest('[data-variant-id]').dataset.variantId;
            const newQuantity = parseInt(this.value);
            
            if (newQuantity > 0) {
                updateQuantity(variantId, newQuantity);
            }
        });
        
        // Prevent multiple rapid changes
        let timeout;
        input.addEventListener('input', function() {
            clearTimeout(timeout);
            timeout = setTimeout(() => {
                const variantId = this.closest('[data-variant-id]').dataset.variantId;
                const newQuantity = parseInt(this.value);
                
                if (newQuantity > 0) {
                    updateQuantity(variantId, newQuantity);
                }
            }, 500); // Wait 500ms after user stops typing
        });
    });
    
    // Handle quantity buttons - remove inline handlers and add proper event listeners
    document.querySelectorAll('.quantity-control .btn').forEach(button => {
        button.removeAttribute('onclick');
        
        button.addEventListener('click', function() {
            const cartItem = this.closest('[data-variant-id]');
            const variantId = cartItem.dataset.variantId;
            const quantityInput = cartItem.querySelector('.quantity-input');
            const currentQuantity = parseInt(quantityInput.value);
            
            // Check if this is minus or plus button
            const isMinus = this.innerHTML.includes('fa-minus');
            const isPlus = this.innerHTML.includes('fa-plus');
            
            let newQuantity = currentQuantity;
            
            if (isMinus && currentQuantity > 1) {
                newQuantity = currentQuantity - 1;
            } else if (isPlus) {
                const maxQuantity = parseInt(quantityInput.getAttribute('max'));
                if (currentQuantity < maxQuantity) {
                    newQuantity = currentQuantity + 1;
                }
            }
            
            if (newQuantity !== currentQuantity) {
                quantityInput.value = newQuantity;
                updateQuantity(variantId, newQuantity);
            }
        });
    });
    
    // Handle variant selection on product detail page
    document.querySelectorAll('.variant-option').forEach(option => {
        option.addEventListener('click', function() {
            const variantId = this.dataset.variantId;
            selectVariant(variantId, this);
        });
    });
    
    // Auto-select first available variant if only one exists
    const variants = document.querySelectorAll('.variant-option');
    if (variants.length === 1) {
        const variantId = variants[0].dataset.variantId;
        selectVariant(variantId, variants[0]);
    }
});

// Export functions for global access
window.addToCart = addToCart;
window.updateQuantity = updateQuantity;
window.removeFromCart = removeFromCart;
window.clearCart = clearCart;
window.proceedToCheckout = proceedToCheckout;
window.applyCoupon = applyCoupon;
window.selectVariant = selectVariant;
