<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán - Shoe Shop</title>
    
    <!-- Bootstrap CSS -->
    <link href="/NHOM4_DU_AN_1/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Custom Checkout CSS -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }

        /* Checkout Header */
        .checkout-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .checkout-header .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
        }

        .checkout-logo {
            display: flex;
            align-items: center;
            text-decoration: none;
            color: #333;
            font-weight: bold;
            font-size: 20px;
        }

        .checkout-logo img {
            height: 40px;
            margin-right: 10px;
        }

        .checkout-steps {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .step {
            display: flex;
            align-items: center;
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .step.active {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        }

        .step:not(.active) {
            background: #f8f9fa;
            color: #6c757d;
        }

        .step-separator {
            color: #dee2e6;
            font-size: 18px;
        }

        /* Main Content */
        .checkout-container {
            padding: 40px 0;
            min-height: calc(100vh - 80px);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Breadcrumb */
        .breadcrumb {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 12px 20px;
            margin-bottom: 30px;
            backdrop-filter: blur(10px);
        }

        .breadcrumb-item a {
            color: #667eea;
            text-decoration: none;
        }

        .breadcrumb-item.active {
            color: #333;
            font-weight: 600;
        }

        /* Alert */
        .alert {
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            margin-bottom: 25px;
        }

        /* Checkout Sections */
        .checkout-section {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            margin-bottom: 25px;
            overflow: hidden;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
        }

        .section-title {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 25px;
            margin: 0;
            font-size: 18px;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-right: 12px;
            font-size: 20px;
        }

        .section-content {
            padding: 25px;
        }

        /* Form Styles */
        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s;
            background: rgba(255, 255, 255, 0.8);
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
            background: white;
        }

        .form-control.error {
            border-color: #dc3545;
            background: #fff5f5;
        }

        /* Payment Methods */
        .payment-method {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            margin-bottom: 15px;
            transition: all 0.3s;
            overflow: hidden;
        }

        .payment-method:hover {
            border-color: #667eea;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.1);
        }

        .payment-method input[type="radio"] {
            display: none;
        }

        .payment-method input[type="radio"]:checked + .form-check-label {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .payment-info {
            display: flex;
            align-items: center;
            padding: 18px 20px;
            cursor: pointer;
        }

        .payment-icon {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 20px;
        }

        .payment-details h6 {
            margin: 0 0 5px 0;
            font-weight: 600;
        }

        /* Order Summary */
        .order-summary {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            position: sticky;
            top: 100px;
        }

        .summary-title {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 20px 25px;
            margin: 0;
            border-radius: 15px 15px 0 0;
            font-size: 18px;
            font-weight: 600;
            display: flex;
            align-items: center;
        }

        .summary-title i {
            margin-right: 12px;
        }

        .cart-items {
            padding: 20px 25px;
            max-height: 300px;
            overflow-y: auto;
        }

        .cart-item {
            display: flex;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #f1f3f4;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .item-image {
            position: relative;
            margin-right: 15px;
        }

        .item-quantity {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #667eea;
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
        }

        .price-summary {
            padding: 20px 25px;
            border-top: 2px dashed #e9ecef;
        }

        .price-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 14px;
        }

        .price-row.total {
            font-size: 18px;
            font-weight: bold;
            color: #28a745;
            padding-top: 15px;
            border-top: 2px solid #e9ecef;
            margin-top: 15px;
        }

        .shipping-info {
            padding: 15px 25px;
            background: #f8f9fa;
            border-radius: 0 0 15px 15px;
        }

        .info-item {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            font-size: 13px;
            color: #6c757d;
        }

        .info-item i {
            color: #28a745;
            margin-right: 8px;
            width: 16px;
        }

        /* Place Order Button */
        .btn-place-order {
            width: calc(100% - 50px);
            margin: 20px 25px 25px 25px;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            border: none;
            color: white;
            padding: 15px;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-place-order:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
        }

        .btn-place-order:disabled {
            background: #6c757d;
            cursor: not-allowed;
            transform: none;
        }

        .checkout-footer {
            padding: 0 25px 25px 25px;
            text-align: center;
        }

        .checkout-footer a {
            color: #667eea;
            text-decoration: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .checkout-steps {
                display: none;
            }
            
            .checkout-container {
                padding: 20px 0;
            }
            
            .container {
                padding: 0 15px;
            }
            
            .order-summary {
                position: static;
                margin-top: 30px;
            }
        }
    </style>
</head>
<body>
    <!-- Checkout Header -->
    <div class="checkout-header">
        <div class="container">
            <a href="/NHOM4_DU_AN_1/index.php" class="checkout-logo">
                <img src="/NHOM4_DU_AN_1/public/assets/images/logo-new.jpg" alt="Shoe Shop">
                Shoe Shop
            </a>
            <div class="checkout-steps">
                <span class="step active">1. Thông tin</span>
                <span class="step-separator">→</span>
                <span class="step">2. Thanh toán</span>
                <span class="step-separator">→</span>
                <span class="step">3. Hoàn tất</span>
            </div>
        </div>
    </div>

    <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle"></i>
            <?= htmlspecialchars($_GET['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="checkout-container">
        <div class="container">
            
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/NHOM4_DU_AN_1/index.php">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="/NHOM4_DU_AN_1/index.php?page=cart">Giỏ hàng</a></li>
                    <li class="breadcrumb-item active">Thanh toán</li>
                </ol>
            </nav>

            <form action="/NHOM4_DU_AN_1/index.php?page=checkout&action=place-order" method="POST" id="checkoutForm">
                <div class="row">
                    <!-- Left Column - Checkout Form -->
                    <div class="col-lg-8">
                        
                        <!-- Shipping Information -->
                        <div class="checkout-section">
                            <h4 class="section-title">
                                <i class="fas fa-shipping-fast"></i>
                                Thông tin giao hàng
                            </h4>
                            
                            <div class="section-content">
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="fullname" class="form-label">Họ và tên *</label>
                                        <input type="text" class="form-control" id="fullname" name="fullname" 
                                               value="<?= isset($_SESSION['user']['name']) ? htmlspecialchars($_SESSION['user']['name']) : '' ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">Số điện thoại *</label>
                                        <input type="tel" class="form-control" id="phone" name="phone" 
                                               value="<?= isset($_SESSION['user']['phone']) ? htmlspecialchars($_SESSION['user']['phone']) : '0901234567' ?>" required>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="address" class="form-label">Địa chỉ giao hàng *</label>
                                    <textarea class="form-control" id="address" name="address" rows="3" 
                                              placeholder="Nhập địa chỉ chi tiết (số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố)" required>Nhập địa chỉ chi tiết (số nhà, tên đường, phường/xã, quận/huyện, tỉnh/thành phố)</textarea>
                                </div>

                                <?php if (isset($data['savedAddresses']) && !empty($data['savedAddresses'])): ?>
                                <div class="saved-addresses">
                                    <h6>Địa chỉ đã lưu:</h6>
                                    <?php foreach ($data['savedAddresses'] as $addr): ?>
                                    <div class="saved-address-item">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="saved_address" 
                                                   value="<?= $addr['id'] ?>" id="addr_<?= $addr['id'] ?>">
                                            <label class="form-check-label" for="addr_<?= $addr['id'] ?>">
                                                <strong><?= htmlspecialchars($addr['fullname']) ?></strong> - <?= htmlspecialchars($addr['phone']) ?><br>
                                                <small class="text-muted"><?= htmlspecialchars($addr['address']) ?></small>
                                                <?php if ($addr['is_default']): ?>
                                                    <span class="badge bg-primary">Mặc định</span>
                                                <?php endif; ?>
                                            </label>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="checkout-section">
                            <h4 class="section-title">
                                <i class="fas fa-credit-card"></i>
                                Phương thức thanh toán
                            </h4>
                            
                            <div class="section-content">
                                <div class="payment-methods">
                                    
                                    <!-- COD -->
                                    <div class="payment-method">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method" 
                                                   value="cod" id="payment_cod" checked>
                                            <label class="form-check-label" for="payment_cod">
                                                <div class="payment-info">
                                                    <div class="payment-icon">
                                                        <i class="fas fa-money-bill-wave"></i>
                                                    </div>
                                                    <div class="payment-details">
                                                        <h6>Thanh toán khi nhận hàng (COD)</h6>
                                                        <small class="text-muted">Thanh toán bằng tiền mặt khi nhận hàng</small>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Bank Transfer -->
                                    <div class="payment-method">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method" 
                                                   value="bank_transfer" id="payment_bank">
                                            <label class="form-check-label" for="payment_bank">
                                                <div class="payment-info">
                                                    <div class="payment-icon">
                                                        <i class="fas fa-university"></i>
                                                    </div>
                                                    <div class="payment-details">
                                                        <h6>Chuyển khoản ngân hàng</h6>
                                                        <small class="text-muted">Chuyển khoản qua ATM, Internet Banking</small>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- VNPay -->
                                    <div class="payment-method">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method" 
                                                   value="vnpay" id="payment_vnpay">
                                            <label class="form-check-label" for="payment_vnpay">
                                                <div class="payment-info">
                                                    <div class="payment-icon">
                                                        <i class="fas fa-credit-card"></i>
                                                    </div>
                                                    <div class="payment-details">
                                                        <h6>VNPay</h6>
                                                        <small class="text-muted">Thanh toán qua thẻ ATM, Visa, Mastercard</small>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- MoMo -->
                                    <div class="payment-method">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="payment_method" 
                                                   value="momo" id="payment_momo">
                                            <label class="form-check-label" for="payment_momo">
                                                <div class="payment-info">
                                                    <div class="payment-icon">
                                                        <i class="fas fa-mobile-alt"></i>
                                                    </div>
                                                    <div class="payment-details">
                                                        <h6>Ví MoMo</h6>
                                                        <small class="text-muted">Thanh toán qua ví điện tử MoMo</small>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Right Column - Order Summary -->
                    <div class="col-lg-4">
                        <div class="order-summary">
                            <h4 class="summary-title">
                                <i class="fas fa-receipt"></i>
                                Tóm tắt đơn hàng
                            </h4>
                            
                            <!-- Cart Items -->
                            <div class="cart-items">
                                <?php 
                                // Check if data exists (when called via controller)
                                if (!isset($data) || empty($data['cartItems'])): 
                                ?>
                                    <div class="alert alert-warning">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        Không có dữ liệu giỏ hàng. 
                                        <a href="/NHOM4_DU_AN_1/index.php?page=cart">Quay lại giỏ hàng</a>
                                    </div>
                                <?php else: ?>
                                    <?php foreach ($data['cartItems'] as $item): ?>
                                <div class="cart-item">
                                    <div class="item-image">
                                        <?php 
                                        // Fix image path - CartModel returns 'product_image' not 'image'
                                        $imageSrc = '';
                                        $imageField = isset($item['product_image']) ? $item['product_image'] : (isset($item['image']) ? $item['image'] : '');
                                        
                                        if (!empty($imageField)) {
                                            if (filter_var($imageField, FILTER_VALIDATE_URL)) {
                                                $imageSrc = $imageField;
                                            } else {
                                                $imageSrc = '/NHOM4_DU_AN_1/public/uploads/products/' . $imageField;
                                            }
                                        } else {
                                            $imageSrc = '/NHOM4_DU_AN_1/public/assets/images/trending-01.jpg';
                                        }
                                        ?>
                                        <img src="<?= htmlspecialchars($imageSrc) ?>" alt="<?= htmlspecialchars($item['product_name']) ?>" 
                                             style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                        <span class="item-quantity"><?= $item['quantity'] ?></span>
                                    </div>
                                    <div class="item-details">
                                        <h6 class="item-name"><?= htmlspecialchars($item['product_name']) ?></h6>
                                        <div class="item-variant">
                                            Size: <?= htmlspecialchars($item['size']) ?> | 
                                            Màu: <?= htmlspecialchars($item['color']) ?>
                                        </div>
                                        <div class="item-price"><?= number_format($item['total_price'], 0, ',', '.') ?>₫</div>
                                    </div>
                                </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>

                            <!-- Price Summary -->
                            <div class="price-summary">
                                <?php if (isset($data) && !empty($data)): ?>
                                <div class="price-row">
                                    <span>Tạm tính:</span>
                                    <span><?= number_format($data['cartTotal'], 0, ',', '.') ?>₫</span>
                                </div>
                                <div class="price-row">
                                    <span>Phí vận chuyển:</span>
                                    <span class="shipping-fee">
                                        <?php if ($data['shippingFee'] == 0): ?>
                                            <span class="text-success">Miễn phí</span>
                                        <?php else: ?>
                                            <?= number_format($data['shippingFee'], 0, ',', '.') ?>₫
                                        <?php endif; ?>
                                    </span>
                                </div>
                                <hr>
                                <div class="price-row total">
                                    <span>Tổng cộng:</span>
                                    <span class="total-amount"><?= number_format($data['finalTotal'], 0, ',', '.') ?>₫</span>
                                </div>
                                <?php else: ?>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    Đang tải thông tin đơn hàng...
                                </div>
                                <?php endif; ?>
                            </div>

                            <!-- Shipping Info -->
                            <div class="shipping-info">
                                <div class="info-item">
                                    <i class="fas fa-truck"></i>
                                    <span>Giao hàng: 2-5 ngày làm việc</span>
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-shield-alt"></i>
                                    <span>Bảo hành chính hãng</span>
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-exchange-alt"></i>
                                    <span>Đổi trả trong 7 ngày</span>
                                </div>
                            </div>

                            <!-- Place Order Button -->
                            <button type="submit" class="btn btn-primary btn-place-order">
                                <i class="fas fa-lock"></i>
                                Đặt hàng ngay
                            </button>

                            <div class="checkout-footer">
                                <small class="text-muted">
                                    Bằng việc đặt hàng, bạn đồng ý với 
                                    <a href="#">Điều khoản sử dụng</a> và 
                                    <a href="#">Chính sách bảo mật</a> của chúng tôi.
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
    // Form validation và UX enhancements
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('checkoutForm');
        const savedAddresses = document.querySelectorAll('input[name="saved_address"]');
        
        // Handle saved address selection
        savedAddresses.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.checked) {
                    const label = this.nextElementSibling;
                    const addressText = label.querySelector('small').textContent;
                    const fullname = label.querySelector('strong').textContent;
                    const phone = label.textContent.match(/(\d{10,11})/)[0];
                    
                    document.getElementById('fullname').value = fullname;
                    document.getElementById('phone').value = phone;
                    document.getElementById('address').value = addressText;
                }
            });
        });
        
        // Form submission with detailed validation
        form.addEventListener('submit', function(e) {
            const fullnameField = document.getElementById('fullname');
            const phoneField = document.getElementById('phone');
            const addressField = document.getElementById('address');
            
            // Check for empty fields and show specific alerts
            const missingFields = [];
            
            if (!fullnameField.value.trim()) {
                missingFields.push('Họ và tên');
            }
            
            if (!phoneField.value.trim()) {
                missingFields.push('Số điện thoại');
            }
            
            if (!addressField.value.trim()) {
                missingFields.push('Địa chỉ giao hàng');
            }
            
            // If there are missing fields, show alert and prevent submission
            if (missingFields.length > 0) {
                e.preventDefault();
                
                // Create detailed alert message
                const alertMessage = `⚠️ Vui lòng điền đầy đủ thông tin:\n\n${missingFields.map(field => `• ${field}`).join('\n')}\n\nHãy điền đầy đủ thông tin trước khi đặt hàng.`;
                
                alert(alertMessage);
                
                // Focus on first missing field
                if (!fullnameField.value.trim()) {
                    fullnameField.focus();
                    fullnameField.classList.add('error');
                } else if (!phoneField.value.trim()) {
                    phoneField.focus();
                    phoneField.classList.add('error');
                } else if (!addressField.value.trim()) {
                    addressField.focus();
                    addressField.classList.add('error');
                }
                
                return false;
            }
            
            // Reset border colors if validation passes
            fullnameField.classList.remove('error');
            phoneField.classList.remove('error');
            addressField.classList.remove('error');
            
            // Show loading state
            const submitBtn = form.querySelector('.btn-place-order');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
        });
        
        // Clear red border when user starts typing
        ['fullname', 'phone', 'address'].forEach(fieldId => {
            const field = document.getElementById(fieldId);
            field.addEventListener('input', function() {
                this.classList.remove('error'); // Remove error class on input
            });
        });
    });
    </script>

<!-- Bootstrap JS -->
<script src="/NHOM4_DU_AN_1/vendor/jquery/jquery.min.js"></script>
<script src="/NHOM4_DU_AN_1/vendor/bootstrap/js/bootstrap.min.js"></script>

</body>
</html>


