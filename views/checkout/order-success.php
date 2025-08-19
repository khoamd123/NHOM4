<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt hàng thành công - Shoe Shop</title>
    <link rel="stylesheet" href="/NHOM4_DU_AN_1/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="/NHOM4_DU_AN_1/public/assets/css/checkout.css">
</head>
<body>
    <!-- Header đơn giản -->
    <div class="checkout-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center py-3">
                <a href="/NHOM4_DU_AN_1/index.php" class="logo">
                    <img src="/NHOM4_DU_AN_1/public/assets/images/logo-new.jpg" alt="Shoe Shop" style="height: 40px;">
                </a>
                <div class="checkout-steps">
                    <span class="step completed">1. Thông tin</span>
                    <span class="step-separator">→</span>
                    <span class="step completed">2. Thanh toán</span>
                    <span class="step-separator">→</span>
                    <span class="step active">3. Hoàn tất</span>
                </div>
            </div>
        </div>
    </div>

    <div class="success-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    
                    <!-- Success Message -->
                    <div class="success-card">
                        <div class="success-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <h1 class="success-title">Đặt hàng thành công!</h1>
                        <p class="success-message">
                            Cảm ơn bạn đã mua hàng tại Shoe Shop. Đơn hàng của bạn đã được tiếp nhận và đang được xử lý.
                        </p>
                        
                        <!-- Order Info -->
                        <div class="order-info">
                            <div class="info-row">
                                <span class="info-label">Mã đơn hàng:</span>
                                <span class="info-value">#<?= $order['id'] ?></span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Tổng tiền:</span>
                                <span class="info-value"><?= number_format($order['total_amount'], 0, ',', '.') ?>₫</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Trạng thái:</span>
                                <span class="info-value">
                                    <span class="badge bg-warning">Đang xử lý</span>
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Thời gian đặt:</span>
                                <span class="info-value"><?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></span>
                            </div>
                        </div>

                        <!-- Shipping Info -->
                        <?php 
                        $shippingInfo = json_decode($order['shipping_info'], true);
                        if ($shippingInfo): 
                        ?>
                        <div class="shipping-details">
                            <h5><i class="fas fa-shipping-fast"></i> Thông tin giao hàng</h5>
                            <div class="shipping-content">
                                <p><strong><?= htmlspecialchars($shippingInfo['fullname']) ?></strong></p>
                                <p><i class="fas fa-phone"></i> <?= htmlspecialchars($shippingInfo['phone']) ?></p>
                                <p><i class="fas fa-map-marker-alt"></i> <?= htmlspecialchars($shippingInfo['address']) ?></p>
                                <?php if (!empty($shippingInfo['notes'])): ?>
                                <p><i class="fas fa-sticky-note"></i> <?= htmlspecialchars($shippingInfo['notes']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Order Items -->
                        <div class="order-items">
                            <h5><i class="fas fa-box"></i> Sản phẩm đã đặt</h5>
                            

                            

                            
                            <div class="items-list">
                                <?php foreach ($orderItems as $item): ?>
                                <div class="order-item">
                                    <div class="item-image">
                                        <?php 
                                        // Xử lý đường dẫn ảnh - sử dụng trực tiếp từ controller
                                        $imageSrc = $item['image'] ?? '/NHOM4_DU_AN_1/public/assets/images/featured-01.png';
                                        ?>

                                        <img src="<?= htmlspecialchars($imageSrc) ?>" 
                                             alt="<?= htmlspecialchars($item['product_name']) ?>"
                                             onerror="this.src='/NHOM4_DU_AN_1/public/assets/images/featured-01.png'">
                                    </div>
                                    <div class="item-details">
                                        <h6><?= htmlspecialchars($item['product_name']) ?></h6>
                                        <div class="item-variant">
                                            Size: <?= htmlspecialchars($item['size']) ?> | 
                                            Màu: <?= htmlspecialchars($item['color']) ?>
                                        </div>
                                        <div class="item-quantity">Số lượng: <?= $item['quantity'] ?></div>
                                    </div>
                                    <div class="item-price">
                                        <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?>₫
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Next Steps -->
                        <div class="next-steps">
                            <h5><i class="fas fa-list-check"></i> Bước tiếp theo</h5>
                            <div class="steps-list">
                                <div class="step-item">
                                    <i class="fas fa-check text-success"></i>
                                    <span>Đơn hàng đã được tiếp nhận</span>
                                </div>
                                <div class="step-item">
                                    <i class="fas fa-clock text-warning"></i>
                                    <span>Chúng tôi sẽ xác nhận đơn hàng trong vòng 24h</span>
                                </div>
                                <div class="step-item">
                                    <i class="fas fa-truck text-muted"></i>
                                    <span>Đơn hàng sẽ được giao trong 2-5 ngày làm việc</span>
                                </div>
                                <div class="step-item">
                                    <i class="fas fa-bell text-muted"></i>
                                    <span>Bạn sẽ nhận được thông báo cập nhật qua email</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <a href="/NHOM4_DU_AN_1/index.php" class="btn btn-outline-primary">
                                <i class="fas fa-home"></i>
                                Về trang chủ
                            </a>
                            <a href="/NHOM4_DU_AN_1/index.php?page=shop" class="btn btn-primary">
                                <i class="fas fa-shopping-bag"></i>
                                Tiếp tục mua sắm
                            </a>
                        </div>

                        <!-- Contact Info -->
                        <div class="contact-info">
                            <p class="text-muted">
                                <i class="fas fa-info-circle"></i>
                                Nếu bạn có thắc mắc về đơn hàng, vui lòng liên hệ:
                            </p>
                            <div class="contact-details">
                                <span><i class="fas fa-phone"></i> Hotline: 1900-1234</span>
                                <span><i class="fas fa-envelope"></i> Email: support@shoeshop.com</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/NHOM4_DU_AN_1/vendor/jquery/jquery.min.js"></script>
    <script src="/NHOM4_DU_AN_1/vendor/bootstrap/js/bootstrap.min.js"></script>
    
    <script>
        // Auto refresh để check order status (optional)
        setTimeout(function() {
            // Có thể implement auto check status sau
        }, 5000);
        
        // Confetti effect (optional)
        if (typeof confetti !== 'undefined') {
            confetti({
                particleCount: 100,
                spread: 70,
                origin: { y: 0.6 }
            });
        }
    </script>
</body>
</html>


