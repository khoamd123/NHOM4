<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Khởi tạo giỏ hàng nếu chưa có
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$cart = $_SESSION['cart'];
$total = 0;
?>

<div class="page-heading header-text">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h3>Giỏ hàng của bạn</h3>
                <span class="breadcrumb"><a href="/NHOM4_DU_AN_1/index.php">Trang chủ</a> > Giỏ hàng</span>
            </div>
        </div>
    </div>
</div>

<div class="cart-page section">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php if (empty($cart)): ?>
                    <div class="empty-cart text-center">
                        <div class="empty-cart-icon">
                            <i class="fa fa-shopping-cart" style="font-size: 80px; color: #ccc; margin-bottom: 20px;"></i>
                        </div>
                        <h4>Giỏ hàng của bạn đang trống</h4>
                        <p>Hãy thêm một số sản phẩm vào giỏ hàng để tiếp tục mua sắm.</p>
                        <a href="/NHOM4_DU_AN_1/index.php?page=shop" class="main-button">Tiếp tục mua sắm</a>
                    </div>
                <?php else: ?>
                    <div class="cart-content">
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="cart-items">
                                    <h4>Sản phẩm trong giỏ hàng</h4>
                                    <div class="cart-table">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Sản phẩm</th>
                                                    <th>Giá</th>
                                                    <th>Số lượng</th>
                                                    <th>Tổng</th>
                                                    <th>Thao tác</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($cart as $productId => $item): ?>
                                                    <?php $itemTotal = $item['price'] * $item['quantity']; ?>
                                                    <?php $total += $itemTotal; ?>
                                                    <tr data-product-id="<?php echo $productId; ?>">
                                                        <td class="product-info">
                                                            <div class="d-flex align-items-center">
                                                                <img src="<?php echo $item['image']; ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" class="cart-product-image">
                                                                <div class="product-details">
                                                                    <h6><?php echo htmlspecialchars($item['name']); ?></h6>
                                                                    <small class="text-muted">Size: <?php echo $item['size'] ?? 'N/A'; ?></small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="price"><?php echo number_format($item['price'], 0, ',', '.'); ?>đ</td>
                                                        <td class="quantity">
                                                            <div class="quantity-controls">
                                                                <button class="btn btn-sm btn-outline-secondary quantity-btn" data-action="decrease">-</button>
                                                                <span class="quantity-value"><?php echo $item['quantity']; ?></span>
                                                                <button class="btn btn-sm btn-outline-secondary quantity-btn" data-action="increase">+</button>
                                                            </div>
                                                        </td>
                                                        <td class="item-total"><?php echo number_format($itemTotal, 0, ',', '.'); ?>đ</td>
                                                        <td>
                                                            <button class="btn btn-sm btn-danger remove-item" data-product-id="<?php echo $productId; ?>">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="cart-summary">
                                    <h4>Tổng kết đơn hàng</h4>
                                    <div class="summary-details">
                                        <div class="summary-row">
                                            <span>Tạm tính:</span>
                                            <span class="subtotal"><?php echo number_format($total, 0, ',', '.'); ?>đ</span>
                                        </div>
                                        <div class="summary-row">
                                            <span>Phí vận chuyển:</span>
                                            <span class="shipping">Miễn phí</span>
                                        </div>
                                        <hr>
                                        <div class="summary-row total-row">
                                            <strong>
                                                <span>Tổng cộng:</span>
                                                <span class="total"><?php echo number_format($total, 0, ',', '.'); ?>đ</span>
                                            </strong>
                                        </div>
                                    </div>
                                    <div class="cart-actions">
                                        <button class="btn btn-outline-secondary btn-block mb-2" id="update-cart">Cập nhật giỏ hàng</button>
                                        <button class="btn btn-success btn-block mb-2" id="checkout">Thanh toán</button>
                                        <a href="/NHOM4_DU_AN_1/index.php?page=shop" class="btn btn-link btn-block">Tiếp tục mua sắm</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý thay đổi số lượng
    document.querySelectorAll('.quantity-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const action = this.getAttribute('data-action');
            const row = this.closest('tr');
            const productId = row.getAttribute('data-product-id');
            const quantitySpan = row.querySelector('.quantity-value');
            let currentQuantity = parseInt(quantitySpan.textContent);
            
            if (action === 'increase') {
                currentQuantity++;
            } else if (action === 'decrease' && currentQuantity > 1) {
                currentQuantity--;
            }
            
            // Cập nhật giao diện
            quantitySpan.textContent = currentQuantity;
            
            // Gửi request cập nhật
            updateCartItem(productId, currentQuantity);
        });
    });
    
    // Xử lý xóa sản phẩm
    document.querySelectorAll('.remove-item').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const productId = this.getAttribute('data-product-id');
            if (confirm('Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?')) {
                removeCartItem(productId);
            }
        });
    });
    
    // Xử lý thanh toán
    document.getElementById('checkout')?.addEventListener('click', function() {
        alert('Chức năng thanh toán đang được phát triển!');
    });
});

function updateCartItem(productId, quantity) {
    fetch('/NHOM4_DU_AN_1/controllers/CartController.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=update&product_id=${productId}&quantity=${quantity}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function removeCartItem(productId) {
    fetch('/NHOM4_DU_AN_1/controllers/CartController.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=remove&product_id=${productId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}
</script>
