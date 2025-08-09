// Cart Management JavaScript
class CartManager {
    constructor() {
        this.init();
    }
    
    init() {
        // Cập nhật số lượng giỏ hàng khi trang load
        this.updateCartCount();
        
        // Bind events
        this.bindEvents();
    }
    
    bindEvents() {
        // Bind add to cart buttons
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('add-to-cart-btn') || 
                e.target.closest('.add-to-cart-btn')) {
                e.preventDefault();
                this.handleAddToCart(e.target.closest('.add-to-cart-btn'));
            }
        });
        
        // Bind quantity change buttons
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('quantity-btn')) {
                e.preventDefault();
                this.handleQuantityChange(e.target);
            }
        });
        
        // Bind remove item buttons
        document.addEventListener('click', (e) => {
            if (e.target.classList.contains('remove-item') || 
                e.target.closest('.remove-item')) {
                e.preventDefault();
                this.handleRemoveItem(e.target.closest('.remove-item'));
            }
        });
    }
    
    // Thêm sản phẩm vào giỏ hàng
    async addToCart(productData) {
        try {
            const formData = new FormData();
            formData.append('action', 'add');
            formData.append('product_id', productData.id);
            formData.append('name', productData.name);
            formData.append('price', productData.price);
            formData.append('image', productData.image);
            formData.append('quantity', productData.quantity || 1);
            if (productData.size) {
                formData.append('size', productData.size);
            }
            
            const response = await fetch('/NHOM4_DU_AN_1/controllers/CartController.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.updateCartCount(result.cart_count);
                this.showMessage('Đã thêm sản phẩm vào giỏ hàng!', 'success');
            } else {
                this.showMessage(result.message || 'Có lỗi xảy ra!', 'error');
            }
            
            return result;
        } catch (error) {
            console.error('Add to cart error:', error);
            this.showMessage('Có lỗi xảy ra khi thêm sản phẩm!', 'error');
            return { success: false };
        }
    }
    
    // Cập nhật số lượng sản phẩm
    async updateQuantity(cartKey, quantity) {
        try {
            const formData = new FormData();
            formData.append('action', 'update');
            formData.append('product_id', cartKey);
            formData.append('quantity', quantity);
            
            const response = await fetch('/NHOM4_DU_AN_1/controllers/CartController.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.updateCartCount(result.cart_count);
            }
            
            return result;
        } catch (error) {
            console.error('Update quantity error:', error);
            return { success: false };
        }
    }
    
    // Xóa sản phẩm khỏi giỏ hàng
    async removeItem(cartKey) {
        try {
            const formData = new FormData();
            formData.append('action', 'remove');
            formData.append('product_id', cartKey);
            
            const response = await fetch('/NHOM4_DU_AN_1/controllers/CartController.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.updateCartCount(result.cart_count);
                this.showMessage('Đã xóa sản phẩm khỏi giỏ hàng!', 'success');
            }
            
            return result;
        } catch (error) {
            console.error('Remove item error:', error);
            return { success: false };
        }
    }
    
    // Lấy số lượng giỏ hàng từ server
    async getCartCount() {
        try {
            const formData = new FormData();
            formData.append('action', 'get_count');
            
            const response = await fetch('/NHOM4_DU_AN_1/controllers/CartController.php', {
                method: 'POST',
                body: formData
            });
            
            const result = await response.json();
            return result.cart_count || 0;
        } catch (error) {
            console.error('Get cart count error:', error);
            return 0;
        }
    }
    
    // Cập nhật hiển thị số lượng giỏ hàng
    async updateCartCount(count = null) {
        if (count === null) {
            count = await this.getCartCount();
        }
        
        const cartCountElement = document.getElementById('cart-count');
        if (cartCountElement) {
            cartCountElement.textContent = count;
            
            // Hiệu ứng animation
            cartCountElement.style.transform = 'scale(1.3)';
            setTimeout(() => {
                cartCountElement.style.transform = 'scale(1)';
            }, 200);
        }
    }
    
    // Xử lý click nút "Thêm vào giỏ hàng"
    handleAddToCart(button) {
        const productData = {
            id: button.dataset.productId,
            name: button.dataset.productName,
            price: button.dataset.productPrice,
            image: button.dataset.productImage,
            quantity: this.getSelectedQuantity(button),
            size: this.getSelectedSize(button)
        };
        
        // Validate dữ liệu
        if (!productData.id || !productData.name || !productData.price) {
            this.showMessage('Thông tin sản phẩm không hợp lệ!', 'error');
            return;
        }
        
        // Disable button và show loading
        this.setButtonLoading(button, true);
        
        this.addToCart(productData).finally(() => {
            this.setButtonLoading(button, false);
        });
    }
    
    // Xử lý thay đổi số lượng
    handleQuantityChange(button) {
        const action = button.dataset.action;
        const row = button.closest('tr');
        const cartKey = row.dataset.productId;
        const quantitySpan = row.querySelector('.quantity-value');
        let currentQuantity = parseInt(quantitySpan.textContent);
        
        if (action === 'increase') {
            currentQuantity++;
        } else if (action === 'decrease' && currentQuantity > 1) {
            currentQuantity--;
        } else {
            return;
        }
        
        // Cập nhật giao diện ngay lập tức
        quantitySpan.textContent = currentQuantity;
        
        // Gửi request cập nhật
        this.updateQuantity(cartKey, currentQuantity);
    }
    
    // Xử lý xóa sản phẩm
    handleRemoveItem(button) {
        const cartKey = button.dataset.productId;
        
        if (confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?')) {
            this.removeItem(cartKey).then(result => {
                if (result.success) {
                    // Xóa dòng khỏi table
                    const row = button.closest('tr');
                    if (row) {
                        row.remove();
                    }
                    
                    // Reload trang nếu giỏ hàng trống
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }
            });
        }
    }
    
    // Lấy số lượng được chọn
    getSelectedQuantity(button) {
        const quantityInput = button.closest('.product-item, .product-detail')?.querySelector('input[name="quantity"], .quantity-input');
        return quantityInput ? parseInt(quantityInput.value) || 1 : 1;
    }
    
    // Lấy size được chọn
    getSelectedSize(button) {
        const sizeSelect = button.closest('.product-item, .product-detail')?.querySelector('select[name="size"], .size-select');
        return sizeSelect ? sizeSelect.value : null;
    }
    
    // Set trạng thái loading cho button
    setButtonLoading(button, loading) {
        if (loading) {
            button.classList.add('loading');
            button.disabled = true;
            button.dataset.originalText = button.textContent;
            button.textContent = 'Đang thêm...';
        } else {
            button.classList.remove('loading');
            button.disabled = false;
            button.textContent = button.dataset.originalText || 'Thêm vào giỏ hàng';
        }
    }
    
    // Hiển thị thông báo
    showMessage(message, type = 'info') {
        // Tạo element thông báo
        const messageDiv = document.createElement('div');
        messageDiv.className = `cart-message cart-message-${type}`;
        messageDiv.textContent = message;
        
        // Style cho thông báo
        Object.assign(messageDiv.style, {
            position: 'fixed',
            top: '20px',
            right: '20px',
            padding: '15px 20px',
            borderRadius: '5px',
            color: 'white',
            fontWeight: 'bold',
            zIndex: '9999',
            maxWidth: '300px',
            boxShadow: '0 4px 15px rgba(0,0,0,0.2)'
        });
        
        // Set màu theo type
        switch (type) {
            case 'success':
                messageDiv.style.backgroundColor = '#28a745';
                break;
            case 'error':
                messageDiv.style.backgroundColor = '#dc3545';
                break;
            default:
                messageDiv.style.backgroundColor = '#17a2b8';
        }
        
        // Thêm vào DOM
        document.body.appendChild(messageDiv);
        
        // Animation hiện
        messageDiv.style.transform = 'translateX(100%)';
        setTimeout(() => {
            messageDiv.style.transition = 'transform 0.3s ease';
            messageDiv.style.transform = 'translateX(0)';
        }, 10);
        
        // Tự động ẩn sau 3 giây
        setTimeout(() => {
            messageDiv.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (messageDiv.parentNode) {
                    messageDiv.parentNode.removeChild(messageDiv);
                }
            }, 300);
        }, 3000);
    }
}

// Khởi tạo CartManager khi DOM ready
document.addEventListener('DOMContentLoaded', function() {
    window.cartManager = new CartManager();
});

// Utility functions
window.addToCart = function(productId, name, price, image, quantity = 1, size = null) {
    if (window.cartManager) {
        return window.cartManager.addToCart({
            id: productId,
            name: name,
            price: price,
            image: image,
            quantity: quantity,
            size: size
        });
    }
};

window.updateCartCount = function() {
    if (window.cartManager) {
        window.cartManager.updateCartCount();
    }
};
