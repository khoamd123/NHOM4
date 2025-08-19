// Cart Selection JavaScript
// Xử lý chọn/bỏ chọn sản phẩm trong giỏ hàng

// Chuyển đổi trạng thái chọn sản phẩm
function toggleItemSelection(variantId) {
    $.ajax({
        url: '/NHOM4_DU_AN_1/cart-selection.php',
        type: 'POST',
        data: {
            action: 'toggle_selection',
            variant_id: variantId
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                // Cập nhật giao diện
                updateCartDisplay(response);
                
                // Hiển thị thông báo
                showNotification('Đã cập nhật giỏ hàng', 'success');
            } else {
                showNotification(response.message || 'Lỗi khi cập nhật', 'error');
            }
        },
        error: function() {
            showNotification('Lỗi kết nối', 'error');
        }
    });
}

// Chọn tất cả sản phẩm
function selectAllItems() {
    $.ajax({
        url: '/NHOM4_DU_AN_1/cart-selection.php',
        type: 'POST',
        data: {
            action: 'select_all'
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                // Cập nhật giao diện
                updateCartDisplay(response);
                
                // Chọn tất cả checkbox
                $('.item-checkbox').prop('checked', true);
                
                // Hiển thị thông báo
                showNotification(response.message || 'Đã chọn tất cả sản phẩm', 'success');
            } else {
                showNotification(response.message || 'Lỗi khi chọn tất cả', 'error');
            }
        },
        error: function() {
            showNotification('Lỗi kết nối', 'error');
        }
    });
}

// Bỏ chọn tất cả sản phẩm
function deselectAllItems() {
    $.ajax({
        url: '/NHOM4_DU_AN_1/cart-selection.php',
        type: 'POST',
        data: {
            action: 'deselect_all'
        },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                // Cập nhật giao diện
                updateCartDisplay(response);
                
                // Bỏ chọn tất cả checkbox
                $('.item-checkbox').prop('checked', false);
                
                // Hiển thị thông báo
                showNotification(response.message || 'Đã bỏ chọn tất cả sản phẩm', 'success');
            } else {
                showNotification(response.message || 'Lỗi khi bỏ chọn tất cả', 'error');
            }
        },
        error: function() {
            showNotification('Lỗi kết nối', 'error');
        }
    });
}

// Cập nhật hiển thị giỏ hàng
function updateCartDisplay(data) {
    // Cập nhật số lượng sản phẩm được chọn
    if (data.selectedCount !== undefined) {
        $('.selected-count').text(data.selectedCount + ' sản phẩm');
    }
    
    // Cập nhật tổng tiền
    if (data.selectedTotal !== undefined) {
        $('.cart-subtotal').text(data.selectedTotal);
        $('.cart-total').text(data.selectedTotal);
    }
    
    // Cập nhật số lượng tổng cộng
    if (data.cartCount !== undefined) {
        // Có thể cập nhật số lượng hiển thị ở header nếu cần
    }
    
    // Kiểm tra nếu không có sản phẩm nào được chọn
    if (data.selectedCount === 0) {
        $('.checkout-btn').addClass('disabled').attr('disabled', 'disabled');
        $('.checkout-btn').html('<i class="fas fa-exclamation-triangle"></i> Vui lòng chọn sản phẩm');
    } else {
        $('.checkout-btn').removeClass('disabled').removeAttr('disabled');
        $('.checkout-btn').html('<i class="fas fa-credit-card"></i> Tiến hành thanh toán');
    }
}

// Hiển thị thông báo
function showNotification(message, type = 'info') {
    // Tạo toast notification
    const toast = $(`
        <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    `);
    
    // Thêm vào container
    if ($('.toast-container').length === 0) {
        $('body').append('<div class="toast-container position-fixed top-0 end-0 p-3"></div>');
    }
    
    $('.toast-container').append(toast);
    
    // Hiển thị toast
    const bsToast = new bootstrap.Toast(toast[0]);
    bsToast.show();
    
    // Tự động ẩn sau 3 giây
    setTimeout(() => {
        bsToast.hide();
    }, 3000);
}

// Khởi tạo khi trang load xong
$(document).ready(function() {
    // Kiểm tra trạng thái ban đầu
    const selectedCount = parseInt($('.selected-count').text());
    if (selectedCount === 0) {
        $('.checkout-btn').addClass('disabled').attr('disabled', 'disabled');
        $('.checkout-btn').html('<i class="fas fa-exclamation-triangle"></i> Vui lòng chọn sản phẩm');
    }
    
    // Thêm CSS cho checkbox
    $('<style>')
        .prop('type', 'text/css')
        .html(`
            .item-selection {
                text-align: center;
                padding: 10px 0;
            }
            .item-checkbox {
                width: 18px;
                height: 18px;
                cursor: pointer;
            }
            .checkbox-label {
                cursor: pointer;
                margin-left: 5px;
            }
            .cart-actions .btn {
                font-size: 0.875rem;
            }
            .toast-container {
                z-index: 9999;
            }
        `)
        .appendTo('head');
});
