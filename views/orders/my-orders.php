<style>
* {
    box-sizing: border-box;
}

body {
    font-family: 'Poppins', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
    margin: 0;
    padding: 0;
}

.my-orders-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 40px 20px;
    min-height: calc(100vh - 120px);
}

.navigation-bar {
    display: flex;
    gap: 15px;
    margin-bottom: 30px;
    animation: fadeInDown 0.8s ease-out;
}

.home-btn, .shop-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    background: rgba(255, 255, 255, 0.15);
    color: white;
    text-decoration: none;
    border-radius: 25px;
    font-weight: 600;
    transition: all 0.3s ease;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
    font-size: 0.95rem;
}

.home-btn:hover, .shop-btn:hover {
    background: rgba(255, 255, 255, 0.25);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(255,255,255,0.2);
    color: white;
}

.home-btn i, .shop-btn i {
    font-size: 1rem;
}

.page-header {
    text-align: center;
    margin-bottom: 50px;
    animation: fadeInDown 0.8s ease-out;
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.page-header h1 {
    color: white;
    font-size: 3rem;
    margin-bottom: 15px;
    font-weight: 700;
    text-shadow: 0 2px 10px rgba(0,0,0,0.3);
    position: relative;
}

.page-header h1::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 4px;
    background: linear-gradient(90deg, #ff6b6b, #4ecdc4);
    border-radius: 2px;
}

.page-header p {
    color: rgba(255,255,255,0.9);
    font-size: 1.2rem;
    font-weight: 300;
    margin-top: 25px;
    text-shadow: 0 1px 5px rgba(0,0,0,0.2);
}

.orders-grid {
    display: grid;
    gap: 25px;
    animation: fadeInUp 0.8s ease-out 0.2s both;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.order-card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    padding: 30px;
    border: 1px solid rgba(255,255,255,0.2);
    transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative;
    overflow: hidden;
}

.order-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
    transition: left 0.6s;
}

.order-card:hover::before {
    left: 100%;
}

.order-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    border-color: rgba(255,255,255,0.4);
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 2px solid rgba(102, 126, 234, 0.1);
    position: relative;
}

.order-header::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 60px;
    height: 2px;
    background: linear-gradient(90deg, #667eea, #764ba2);
    border-radius: 1px;
}

.order-id {
    font-weight: 700;
    color: #2c3e50;
    font-size: 1.3rem;
    position: relative;
}

.order-id::before {
    content: '📦';
    margin-right: 8px;
    font-size: 1.1rem;
}

.order-date {
    color: #7f8c8d;
    font-size: 0.95rem;
    font-weight: 400;
    margin-top: 5px;
}

.order-status {
    padding: 8px 16px;
    border-radius: 25px;
    font-size: 0.85rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.8px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.status-pending { 
    background: linear-gradient(135deg, #ffeaa7, #fdcb6e);
    color: #8b4513;
    box-shadow: 0 4px 15px rgba(253, 203, 110, 0.4);
}

.status-processing { 
    background: linear-gradient(135deg, #74b9ff, #0984e3);
    color: white;
    box-shadow: 0 4px 15px rgba(116, 185, 255, 0.4);
}

.status-shipped { 
    background: linear-gradient(135deg, #a29bfe, #6c5ce7);
    color: white;
    box-shadow: 0 4px 15px rgba(162, 155, 254, 0.4);
}

.status-delivered { 
    background: linear-gradient(135deg, #00b894, #00a085);
    color: white;
    box-shadow: 0 4px 15px rgba(0, 184, 148, 0.4);
}

.status-cancelled { 
    background: linear-gradient(135deg, #e17055, #d63031);
    color: white;
    box-shadow: 0 4px 15px rgba(225, 112, 85, 0.4);
}

.order-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
    gap: 25px;
    margin-bottom: 25px;
}

.info-item {
    text-align: center;
    padding: 15px;
    background: rgba(102, 126, 234, 0.05);
    border-radius: 12px;
    transition: all 0.3s ease;
    position: relative;
}

.info-item:hover {
    background: rgba(102, 126, 234, 0.1);
    transform: translateY(-2px);
}

.info-label {
    color: #7f8c8d;
    font-size: 0.85rem;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 500;
}

.info-value {
    color: #2c3e50;
    font-weight: 700;
    font-size: 1.2rem;
    position: relative;
}

.shipping-info-card {
    margin-bottom: 20px;
    padding: 20px;
    background: linear-gradient(135deg, rgba(116, 185, 255, 0.1), rgba(162, 155, 254, 0.1));
    border-radius: 15px;
    border-left: 4px solid #74b9ff;
    position: relative;
}

.shipping-info-card::before {
    content: '🚚';
    position: absolute;
    top: 15px;
    right: 15px;
    font-size: 1.5rem;
}

.shipping-info-card strong {
    color: #2c3e50;
    display: inline-block;
    min-width: 80px;
}

.order-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    flex-wrap: wrap;
}

.btn {
    padding: 12px 24px;
    border-radius: 25px;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    position: relative;
    overflow: hidden;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: left 0.6s;
}

.btn:hover::before {
    left: 100%;
}

.btn-primary {
    background: linear-gradient(135deg, #74b9ff, #0984e3);
    color: white;
    box-shadow: 0 4px 15px rgba(116, 185, 255, 0.4);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #0984e3, #74b9ff);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(116, 185, 255, 0.6);
    color: white;
}

.btn-danger {
    background: linear-gradient(135deg, #e17055, #d63031);
    color: white;
    box-shadow: 0 4px 15px rgba(225, 112, 85, 0.4);
}

.btn-danger:hover {
    background: linear-gradient(135deg, #d63031, #e17055);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(225, 112, 85, 0.6);
}

.empty-orders {
    text-align: center;
    padding: 80px 20px;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 25px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    position: relative;
    overflow: hidden;
}

.empty-orders::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(45deg, transparent, rgba(102, 126, 234, 0.1), transparent);
    animation: shimmer 3s infinite;
}

@keyframes shimmer {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.empty-orders i {
    font-size: 5rem;
    background: linear-gradient(135deg, #667eea, #764ba2);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 25px;
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-10px); }
    60% { transform: translateY(-5px); }
}

.empty-orders h3 {
    color: #2c3e50;
    margin-bottom: 15px;
    font-size: 1.8rem;
    font-weight: 700;
}

.empty-orders p {
    color: #7f8c8d;
    margin-bottom: 30px;
    font-size: 1.1rem;
    line-height: 1.6;
}

.btn-shop {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 15px 35px;
    text-decoration: none;
    border-radius: 30px;
    font-weight: 600;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    display: inline-flex;
    align-items: center;
    gap: 10px;
    font-size: 1.1rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}

.btn-shop:hover {
    transform: translateY(-3px) scale(1.05);
    box-shadow: 0 15px 35px rgba(102, 126, 234, 0.6);
    color: white;
}

/* Loading Animation */
.loading-shimmer {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: shimmer-loading 1.5s infinite;
}

@keyframes shimmer-loading {
    0% { background-position: -200% 0; }
    100% { background-position: 200% 0; }
}

/* Responsive Design */
@media (max-width: 768px) {
    .my-orders-container {
        padding: 20px 15px;
    }
    
    .navigation-bar {
        flex-wrap: wrap;
        gap: 10px;
        margin-bottom: 25px;
    }
    
    .home-btn, .shop-btn {
        padding: 10px 16px;
        font-size: 0.9rem;
    }
    
    .page-header h1 {
        font-size: 2.2rem;
    }
    
    .page-header p {
        font-size: 1rem;
    }
    
    .order-card {
        padding: 20px;
    }
    
    .order-header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    
    .order-info {
        grid-template-columns: 1fr;
        gap: 15px;
    }
    
    .order-actions {
        justify-content: center;
        flex-direction: column;
    }
    
    .btn {
        padding: 12px 20px;
        font-size: 0.85rem;
    }
    
    .empty-orders {
        padding: 50px 15px;
    }
    
    .empty-orders i {
        font-size: 4rem;
    }
}

@media (max-width: 480px) {
    .page-header h1 {
        font-size: 1.8rem;
    }
    
    .order-id {
        font-size: 1.1rem;
    }
    
    .info-value {
        font-size: 1rem;
    }
}

/* Product Display Styles */
.order-products {
    margin: 20px 0;
    padding: 20px;
    background: rgba(116, 185, 255, 0.05);
    border-radius: 12px;
    border-left: 4px solid #74b9ff;
}

.products-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.product-item {
    display: flex;
    align-items: center;
    gap: 15px;
    padding: 12px;
    background: rgba(255, 255, 255, 0.8);
    border-radius: 10px;
    transition: all 0.3s ease;
    border: 1px solid rgba(116, 185, 255, 0.2);
}

.product-item:hover {
    background: rgba(255, 255, 255, 0.95);
    transform: translateX(5px);
    box-shadow: 0 4px 12px rgba(116, 185, 255, 0.15);
}

.product-image {
    width: 60px;
    height: 60px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.product-item:hover .product-image img {
    transform: scale(1.05);
}

.product-details {
    flex: 1;
    min-width: 0;
}

.product-name {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 5px;
    font-size: 0.95rem;
    line-height: 1.3;
}

.product-specs {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 5px;
}

.spec-item {
    background: rgba(102, 126, 234, 0.1);
    color: #667eea;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 500;
}

.product-price {
    color: #e74c3c;
    font-weight: 700;
    font-size: 0.9rem;
}

@media (max-width: 768px) {
    .product-item {
        flex-direction: column;
        text-align: center;
        gap: 10px;
    }
    
    .product-image {
        width: 50px;
        height: 50px;
    }
    
    .product-specs {
        justify-content: center;
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .order-card {
        background: rgba(45, 55, 72, 0.95);
        color: #e2e8f0;
        border-color: rgba(255,255,255,0.1);
    }
    
    .order-id, .info-value {
        color: #e2e8f0;
    }
    
    .order-date, .info-label {
        color: #a0aec0;
    }
    
    .empty-orders {
        background: rgba(45, 55, 72, 0.95);
        color: #e2e8f0;
    }
    
    .empty-orders h3 {
        color: #e2e8f0;
    }
    
    .empty-orders p {
        color: #a0aec0;
    }
    
    .product-item {
        background: rgba(60, 70, 85, 0.8);
        border-color: rgba(116, 185, 255, 0.3);
    }
    
    .product-name {
        color: #e2e8f0;
    }
}
</style>

<div class="my-orders-container">
    <div class="navigation-bar">
        <a href="/NHOM4_DU_AN_1/index.php" class="home-btn">
            <i class="fas fa-home"></i> Trang chủ
        </a>
        <a href="/NHOM4_DU_AN_1/index.php?page=shop" class="shop-btn">
            <i class="fas fa-store"></i> Cửa hàng
        </a>
    </div>
    
    <div class="page-header">
        <h1><i class="fas fa-shopping-bag"></i> Đơn hàng của tôi</h1>
        <p>Theo dõi và quản lý các đơn hàng đã đặt</p>
    </div>

    <?php if (empty($orders)): ?>
        <div class="empty-orders">
            <i class="fas fa-shopping-cart"></i>
            <h3>Chưa có đơn hàng nào</h3>
            <p>Bạn chưa có đơn hàng nào. Hãy bắt đầu mua sắm để tạo đơn hàng đầu tiên!</p>
            <a href="/NHOM4_DU_AN_1/index.php?page=shop" class="btn-shop">
                <i class="fas fa-shopping-bag"></i> Mua sắm ngay
            </a>
        </div>
    <?php else: ?>
        <div class="orders-grid">
            <?php foreach ($orders as $order): ?>
                <?php 
                $shippingInfo = json_decode($order['shipping_info'], true);
                $statusClass = OrderModel::getStatusClass($order['status']);
                $statusLabel = OrderModel::getStatusLabel($order['status']);
                ?>
                <div class="order-card">
                    <div class="order-header">
                        <div>
                            <div class="order-id">Đơn hàng #<?= $order['id'] ?></div>
                            <div class="order-date">
                                <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?>
                            </div>
                        </div>
                        <span class="order-status status-<?= $statusClass ?>">
                            <?= $statusLabel ?>
                        </span>
                    </div>

                    <div class="order-info">
                        <div class="info-item">
                            <div class="info-label">Tổng tiền</div>
                            <div class="info-value"><?= number_format($order['total_amount']) ?>₫</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Số sản phẩm</div>
                            <div class="info-value"><?= $order['total_items'] ?> loại</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label">Số lượng</div>
                            <div class="info-value"><?= $order['total_quantity'] ?> sản phẩm</div>
                        </div>
                    </div>

                    <!-- Hiển thị sản phẩm đã đặt -->
                    <?php 
                    $orderItems = OrderModel::getOrderItems($order['id']);
                    // Debug: log order items
                    error_log("Order ID: " . $order['id'] . " - Items count: " . count($orderItems));
                    if (!empty($orderItems)): 
                    ?>
                    <div class="order-products">
                        <h6 style="color: #2c3e50; margin-bottom: 15px; font-weight: 600;">
                            <i class="fas fa-shopping-bag"></i> Sản phẩm đã đặt:
                        </h6>
                        <div class="products-list">
                            <?php foreach($orderItems as $item): ?>
                            <?php 
                            // Debug: log item data
                            error_log("Product: " . $item['product_name'] . " - Image: " . ($item['product_image'] ?? 'NO IMAGE'));
                            ?>
                            <div class="product-item">
                                <div class="product-image">
                                    <?php
                                    $imageSrc = $item['product_image'] ?? '';
                                    // Nếu đường dẫn không bắt đầu bằng http hoặc /, thêm base path
                                    if ($imageSrc && strpos($imageSrc, 'http') !== 0 && strpos($imageSrc, '/') !== 0) {
                                        $imageSrc = '/NHOM4_DU_AN_1/public/uploads/products/' . $imageSrc;
                                    }
                                    // Fallback image
                                    if (!$imageSrc) {
                                        $imageSrc = '/NHOM4_DU_AN_1/public/assets/images/featured-01.png';
                                    }
                                    ?>
                                    <img src="<?= htmlspecialchars($imageSrc) ?>" 
                                         alt="<?= htmlspecialchars($item['product_name']) ?>"
                                         style="border: 1px solid #ddd;"
                                         onload="console.log('Image loaded: <?= addslashes($imageSrc) ?>')"
                                         onerror="console.log('Image failed: <?= addslashes($imageSrc) ?>'); this.src='/NHOM4_DU_AN_1/public/assets/images/featured-01.png'">
                                </div>
                                <div class="product-details">
                                    <div class="product-name"><?= htmlspecialchars($item['product_name']) ?></div>
                                    <div class="product-specs">
                                        <span class="spec-item">Size: <?= htmlspecialchars($item['size']) ?></span>
                                        <span class="spec-item">Màu: <?= htmlspecialchars($item['color']) ?></span>
                                        <span class="spec-item">SL: <?= $item['quantity'] ?></span>
                                    </div>
                                    <div class="product-price"><?= number_format($item['price']) ?>₫</div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="order-products">
                        <p style="color: #7f8c8d; font-style: italic;">
                            <i class="fas fa-info-circle"></i> Không tìm thấy thông tin sản phẩm cho đơn hàng này.
                        </p>
                    </div>
                    <?php endif; ?>

                    <?php if ($shippingInfo): ?>
                        <div class="shipping-info-card">
                            <strong>Giao đến:</strong> <?= htmlspecialchars($shippingInfo['fullname']) ?><br>
                            <strong>SĐT:</strong> <?= htmlspecialchars($shippingInfo['phone']) ?><br>
                            <strong>Địa chỉ:</strong> <?= htmlspecialchars($shippingInfo['address']) ?>
                        </div>
                    <?php endif; ?>

                    <div class="order-actions">
                        <a href="/NHOM4_DU_AN_1/index.php?controller=order&action=orderDetail&id=<?= $order['id'] ?>" 
                           class="btn btn-primary">
                            <i class="fas fa-eye"></i> Xem chi tiết
                        </a>
                        
                        <?php if ($order['status'] === 'pending'): ?>
                            <button onclick="cancelOrder(<?= $order['id'] ?>)" class="btn btn-danger">
                                <i class="fas fa-times"></i> Hủy đơn
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
function cancelOrder(orderId) {
    if (confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
        fetch('/NHOM4_DU_AN_1/index.php?controller=order&action=cancelOrder', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'order_id=' + orderId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Hủy đơn hàng thành công!');
                location.reload();
            } else {
                alert('Lỗi: ' + data.message);
            }
        })
        .catch(error => {
            alert('Có lỗi xảy ra, vui lòng thử lại!');
        });
    }
}
</script> 