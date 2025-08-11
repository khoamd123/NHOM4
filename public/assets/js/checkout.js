// Checkout functionality
class CheckoutManager {
    constructor() {
        this.form = document.getElementById('checkoutForm');
        this.init();
    }

    init() {
        this.setupFormValidation();
        this.setupAddressSelection();
        this.setupPaymentMethodSelection();
        this.setupFormSubmission();
        this.updateShippingFee();
    }

    setupFormValidation() {
        // Real-time validation
        const requiredFields = this.form.querySelectorAll('input[required], textarea[required]');
        
        requiredFields.forEach(field => {
            field.addEventListener('blur', () => this.validateField(field));
            field.addEventListener('input', () => this.clearFieldError(field));
        });

        // Phone number validation
        const phoneField = document.getElementById('phone');
        if (phoneField) {
            phoneField.addEventListener('input', (e) => {
                // Remove non-numeric characters
                e.target.value = e.target.value.replace(/[^0-9]/g, '');
                
                // Validate phone format
                if (e.target.value.length >= 10) {
                    this.validatePhone(e.target);
                }
            });
        }
    }

    validateField(field) {
        const value = field.value.trim();
        let isValid = true;
        let errorMessage = '';

        // Required field validation
        if (field.hasAttribute('required') && !value) {
            isValid = false;
            errorMessage = 'Trường này là bắt buộc';
        }

        // Specific field validations
        switch (field.type) {
            case 'email':
                if (value && !this.isValidEmail(value)) {
                    isValid = false;
                    errorMessage = 'Email không hợp lệ';
                }
                break;
            case 'tel':
                if (value && !this.isValidPhone(value)) {
                    isValid = false;
                    errorMessage = 'Số điện thoại không hợp lệ';
                }
                break;
        }

        this.showFieldError(field, isValid, errorMessage);
        return isValid;
    }

    validatePhone(field) {
        const phoneRegex = /^(0[3|5|7|8|9])+([0-9]{8})$/;
        const isValid = phoneRegex.test(field.value);
        
        if (!isValid && field.value.length >= 10) {
            this.showFieldError(field, false, 'Số điện thoại không đúng định dạng');
        } else if (isValid) {
            this.clearFieldError(field);
        }
    }

    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    isValidPhone(phone) {
        const phoneRegex = /^(0[3|5|7|8|9])+([0-9]{8})$/;
        return phoneRegex.test(phone);
    }

    showFieldError(field, isValid, message) {
        // Remove existing error
        this.clearFieldError(field);

        if (!isValid) {
            field.classList.add('is-invalid');
            
            // Create error message
            const errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback';
            errorDiv.textContent = message;
            
            // Insert after field
            field.parentNode.insertBefore(errorDiv, field.nextSibling);
        } else {
            field.classList.add('is-valid');
        }
    }

    clearFieldError(field) {
        field.classList.remove('is-invalid', 'is-valid');
        
        // Remove error message
        const errorMsg = field.parentNode.querySelector('.invalid-feedback');
        if (errorMsg) {
            errorMsg.remove();
        }
    }

    setupAddressSelection() {
        const savedAddresses = document.querySelectorAll('input[name="saved_address"]');
        
        savedAddresses.forEach(radio => {
            radio.addEventListener('change', () => {
                if (radio.checked) {
                    this.fillAddressFromSaved(radio);
                }
            });
        });
    }

    fillAddressFromSaved(radio) {
        const label = radio.nextElementSibling;
        const fullnameElement = label.querySelector('strong');
        const phoneMatch = label.textContent.match(/(\d{10,11})/);
        const addressElement = label.querySelector('small');

        if (fullnameElement) {
            document.getElementById('fullname').value = fullnameElement.textContent.trim();
        }
        
        if (phoneMatch) {
            document.getElementById('phone').value = phoneMatch[0];
        }
        
        if (addressElement) {
            document.getElementById('address').value = addressElement.textContent.trim();
        }

        // Clear any validation errors
        this.clearAllFieldErrors();
        
        // Show success message
        this.showToast('Đã điền thông tin từ địa chỉ đã lưu', 'success');
    }

    setupPaymentMethodSelection() {
        const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
        
        paymentMethods.forEach(method => {
            method.addEventListener('change', () => {
                this.updatePaymentInfo(method.value);
            });
        });
    }

    updatePaymentInfo(method) {
        // Hide all payment details
        document.querySelectorAll('.payment-details-info').forEach(detail => {
            detail.style.display = 'none';
        });

        // Show relevant payment info
        const paymentInfo = document.getElementById(`payment-info-${method}`);
        if (paymentInfo) {
            paymentInfo.style.display = 'block';
        }

        // Update button text based on payment method
        const submitBtn = this.form.querySelector('.btn-place-order');
        const buttonTexts = {
            'cod': '<i class="fas fa-money-bill-wave"></i> Đặt hàng (COD)',
            'bank_transfer': '<i class="fas fa-university"></i> Đặt hàng & Chuyển khoản',
            'vnpay': '<i class="fas fa-credit-card"></i> Thanh toán VNPay',
            'momo': '<i class="fas fa-mobile-alt"></i> Thanh toán MoMo'
        };
        
        submitBtn.innerHTML = buttonTexts[method] || '<i class="fas fa-lock"></i> Đặt hàng ngay';
    }

    updateShippingFee() {
        // This would be called when address changes to calculate shipping
        // For now, it's static based on total amount
        const subtotal = parseFloat(document.querySelector('.cart-total')?.dataset.amount || 0);
        
        let shippingFee = 30000; // Default shipping fee
        
        if (subtotal >= 1000000) {
            shippingFee = 0; // Free shipping
        } else if (subtotal >= 500000) {
            shippingFee = 15000; // Reduced shipping
        }

        this.displayShippingFee(shippingFee);
        this.updateTotalAmount(subtotal, shippingFee);
    }

    displayShippingFee(fee) {
        const shippingElement = document.querySelector('.shipping-fee');
        if (shippingElement) {
            if (fee === 0) {
                shippingElement.innerHTML = '<span class="text-success">Miễn phí</span>';
            } else {
                shippingElement.textContent = new Intl.NumberFormat('vi-VN').format(fee) + '₫';
            }
        }
    }

    updateTotalAmount(subtotal, shippingFee) {
        const total = subtotal + shippingFee;
        const totalElement = document.querySelector('.total-amount');
        if (totalElement) {
            totalElement.textContent = new Intl.NumberFormat('vi-VN').format(total) + '₫';
        }
    }

    setupFormSubmission() {
        this.form.addEventListener('submit', (e) => this.handleFormSubmit(e));
    }

    handleFormSubmit(e) {
        e.preventDefault();
        
        // Validate entire form
        if (!this.validateForm()) {
            this.showToast('Vui lòng kiểm tra lại thông tin', 'error');
            return;
        }

        // Show loading state
        this.setLoadingState(true);
        
        // Submit form
        setTimeout(() => {
            e.target.submit();
        }, 500);
    }

    validateForm() {
        const requiredFields = this.form.querySelectorAll('input[required], textarea[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (!this.validateField(field)) {
                isValid = false;
            }
        });

        // Check if payment method is selected
        const paymentMethod = this.form.querySelector('input[name="payment_method"]:checked');
        if (!paymentMethod) {
            this.showToast('Vui lòng chọn phương thức thanh toán', 'error');
            isValid = false;
        }

        return isValid;
    }

    setLoadingState(isLoading) {
        const submitBtn = this.form.querySelector('.btn-place-order');
        const formInputs = this.form.querySelectorAll('input, textarea, select, button');
        
        if (isLoading) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
            
            formInputs.forEach(input => {
                if (input !== submitBtn) {
                    input.disabled = true;
                }
            });
        } else {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-lock"></i> Đặt hàng ngay';
            
            formInputs.forEach(input => {
                input.disabled = false;
            });
        }
    }

    clearAllFieldErrors() {
        const fields = this.form.querySelectorAll('.is-invalid, .is-valid');
        fields.forEach(field => this.clearFieldError(field));
    }

    showToast(message, type = 'info') {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `toast-notification toast-${type}`;
        toast.innerHTML = `
            <div class="toast-content">
                <i class="fas fa-${this.getToastIcon(type)}"></i>
                <span>${message}</span>
            </div>
        `;

        // Add to page
        document.body.appendChild(toast);

        // Show toast
        setTimeout(() => toast.classList.add('show'), 100);

        // Remove toast
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    getToastIcon(type) {
        const icons = {
            'success': 'check-circle',
            'error': 'exclamation-circle',
            'warning': 'exclamation-triangle',
            'info': 'info-circle'
        };
        return icons[type] || 'info-circle';
    }
}

// Toast styles (inject into head)
const toastStyles = `
<style>
.toast-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: white;
    color: #333;
    padding: 15px 20px;
    border-radius: 8px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    transform: translateX(400px);
    transition: transform 0.3s ease;
    z-index: 9999;
    min-width: 300px;
    border-left: 4px solid #2563eb;
}

.toast-notification.show {
    transform: translateX(0);
}

.toast-success {
    border-left-color: #059669;
}

.toast-error {
    border-left-color: #dc2626;
}

.toast-warning {
    border-left-color: #d97706;
}

.toast-content {
    display: flex;
    align-items: center;
    gap: 10px;
}

.toast-content i {
    font-size: 16px;
}

.toast-success .toast-content i {
    color: #059669;
}

.toast-error .toast-content i {
    color: #dc2626;
}

.toast-warning .toast-content i {
    color: #d97706;
}

.toast-info .toast-content i {
    color: #2563eb;
}

@media (max-width: 768px) {
    .toast-notification {
        right: 10px;
        left: 10px;
        min-width: auto;
        transform: translateY(-100px);
    }
    
    .toast-notification.show {
        transform: translateY(0);
    }
}
</style>
`;

// Inject styles
document.head.insertAdjacentHTML('beforeend', toastStyles);

// Initialize checkout when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('checkoutForm')) {
        new CheckoutManager();
    }
});

// Export for global use
window.CheckoutManager = CheckoutManager;


