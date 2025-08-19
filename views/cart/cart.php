<!-- Cart page content - auth and data loading handled in main.php -->

<div class="page-heading">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <h3><i class="fas fa-shopping-cart"></i> Giỏ hàng của bạn</h3>
        <span class="breadcrumb">
          <a href="/NHOM4_DU_AN_1/index.php">Trang chủ</a> > Giỏ hàng
        </span>
      </div>
    </div>
  </div>
</div>

<div class="cart-container">
  <div class="container">
    
    <!-- Thông báo lỗi nếu có sản phẩm không khả dụng -->
    <?php if (!empty($invalidItems)): ?>
    <div class="invalid-items-alert alert alert-dismissible fade show" role="alert">
      <h5><i class="fas fa-exclamation-triangle"></i> Một số sản phẩm trong giỏ hàng có vấn đề:</h5>
      <ul class="mb-0">
        <?php foreach ($invalidItems as $item): ?>
        <li>
          <strong><?= htmlspecialchars($item['product_name']) ?></strong> 
          (Size <?= htmlspecialchars($item['size']) ?>, Màu <?= htmlspecialchars($item['color']) ?>):
          <?php if ($item['stock'] < $item['quantity']): ?>
            Chỉ còn <?= $item['stock'] ?> sản phẩm
          <?php else: ?>
            Sản phẩm ngừng kinh doanh
          <?php endif; ?>
        </li>
        <?php endforeach; ?>
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <?php if (empty($cartItems)): ?>
    <!-- Giỏ hàng trống -->
    <div class="row">
      <div class="col-lg-12">
        <div class="empty-cart-container">
          <div class="empty-cart-icon">
            <i class="fas fa-shopping-cart"></i>
          </div>
          <h3>Giỏ hàng của bạn đang trống</h3>
          <p>Hãy thêm một số sản phẩm vào giỏ hàng để bắt đầu mua sắm</p>
          <a href="/NHOM4_DU_AN_1/index.php?page=shop" class="btn btn-primary btn-lg">
            <i class="fas fa-shopping-bag"></i> Tiếp tục mua sắm
          </a>
        </div>
      </div>
    </div>
    
    <?php else: ?>
    <!-- Danh sách sản phẩm trong giỏ -->
    <div class="row">
      <div class="col-lg-8">
        <div class="cart-items-container">
          <div class="cart-header">
            <h4><i class="fas fa-shopping-cart"></i> Sản phẩm trong giỏ (<?= $cartCount ?> sản phẩm)</h4>
          </div>
          
          <?php foreach ($cartItems as $item): ?>
          <div class="cart-item" data-variant-id="<?= $item['variant_id'] ?>">
            <div class="row align-items-center">
              <div class="col-md-1">
                <div class="item-selection">
                  <input type="checkbox" 
                         class="item-checkbox" 
                         id="item_<?= $item['variant_id'] ?>"
                         data-variant-id="<?= $item['variant_id'] ?>"
                         <?= ($item['is_selected'] ?? 1) ? 'checked' : '' ?>
                         onchange="toggleItemSelection(<?= $item['variant_id'] ?>)">
                  <label for="item_<?= $item['variant_id'] ?>" class="checkbox-label"></label>
                </div>
              </div>
              <div class="col-md-1">
                <div class="product-image">
                  <?php 
                  if (!empty($item['product_image'])) {
                      if (filter_var($item['product_image'], FILTER_VALIDATE_URL)) {
                          $imageSrc = $item['product_image'];
                      } else {
                          $imageSrc = '/NHOM4_DU_AN_1/public/uploads/products/' . $item['product_image'];
                      }
                  } else {
                      $imageSrc = '/NHOM4_DU_AN_1/public/assets/images/trending-01.jpg';
                  }
                  ?>
                  <img src="<?= htmlspecialchars($imageSrc) ?>" 
                       alt="<?= htmlspecialchars($item['product_name']) ?>" 
                       class="img-fluid" 
                       style="width: 100%; height: 80px; object-fit: cover; border-radius: 8px;">
                </div>
              </div>
              
              <div class="col-md-4">
                <div class="product-info">
                  <h6><?= htmlspecialchars($item['product_name']) ?></h6>
                  <div class="mb-2">
                    <span class="brand-badge"><?= htmlspecialchars($item['brand']) ?></span>
                  </div>
                  <div class="variant-info">
                    Size: <?= htmlspecialchars($item['size']) ?> | 
                    Màu: <?= htmlspecialchars($item['color']) ?>
                  </div>
                  <div class="stock-info">
                    <?php if ($item['stock'] > 0): ?>
                      <small class="stock-available">
                        <i class="fas fa-check-circle"></i> Còn <?= $item['stock'] ?> sản phẩm
                      </small>
                    <?php else: ?>
                      <small class="stock-unavailable">
                        <i class="fas fa-times-circle"></i> Hết hàng
                      </small>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              
              <div class="col-md-2">
                <div class="price-info text-center">
                  <div class="unit-price">
                    <?= number_format($item['price'], 0, ',', '.') ?>₫
                  </div>
                </div>
              </div>
              
              <div class="col-md-2">
                <div class="quantity-control">
                  <div class="input-group input-group-sm">
                    <button class="btn btn-outline-secondary quantity-btn-minus" type="button">
                      <i class="fas fa-minus"></i>
                    </button>
                    <input type="number" class="form-control text-center quantity-input" 
                           value="<?= $item['quantity'] ?>" 
                           min="1" max="<?= $item['stock'] ?>">
                    <button class="btn btn-outline-secondary quantity-btn-plus" type="button"
                            <?= $item['quantity'] >= $item['stock'] ? 'disabled' : '' ?>>
                      <i class="fas fa-plus"></i>
                    </button>
                  </div>
                </div>
              </div>
              
              <div class="col-md-1">
                <div class="total-price text-center">
                  <strong><?= number_format($item['total_price'], 0, ',', '.') ?>₫</strong>
                </div>
              </div>
              
              <div class="col-md-1">
                <button class="remove-btn" 
                        onclick="removeFromCart(<?= $item['variant_id'] ?>)"
                        title="Xóa sản phẩm">
                  <i class="fas fa-trash"></i>
                </button>
              </div>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
        
        <div class="continue-shopping mt-4">
          <a href="/NHOM4_DU_AN_1/index.php?page=shop" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left"></i> Tiếp tục mua sắm
          </a>
        </div>
      </div>
      
      <!-- Cart Summary -->
      <div class="col-lg-4">
        <div class="cart-summary">
          <div class="card">
            <div class="card-header">
              <h5 class="mb-0">
                <i class="fas fa-receipt"></i> Tổng đơn hàng
              </h5>
            </div>
            <div class="card-body">
              <div class="summary-row">
                <span>Sản phẩm được chọn:</span>
                <span class="selected-count"><?= $selectedCount ?> sản phẩm</span>
              </div>
              <div class="summary-row">
                <span>Tạm tính:</span>
                <span class="cart-subtotal"><?= number_format($selectedTotal, 0, ',', '.') ?>₫</span>
              </div>
              <div class="summary-row">
                <span>Phí vận chuyển:</span>
                <span class="shipping-fee">Miễn phí</span>
              </div>
              <hr>
              <div class="summary-row total-row">
                <strong>
                  <span>Tổng cộng:</span>
                  <span class="cart-total"><?= number_format($selectedTotal, 0, ',', '.') ?>₫</span>
                </strong>
              </div>
              
              <div class="checkout-actions mt-4">
                <a href="/NHOM4_DU_AN_1/index.php?page=checkout" class="checkout-btn">
                  <i class="fas fa-credit-card"></i> Tiến hành thanh toán
                </a>
              </div>
            </div>
          </div>
          
          <!-- Voucher/Coupon -->
          <div class="coupon-card card mt-3">
            <div class="card-header">
              <h6>
                <i class="fas fa-tags"></i> Mã giảm giá
              </h6>
            </div>
            <div class="card-body">
              <div class="input-group">
                <input type="text" class="coupon-input" placeholder="Nhập mã giảm giá" id="coupon-code">
                <button class="coupon-btn" onclick="applyCoupon()">
                  Áp dụng
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </div>
</div>

<!-- CSS và JS đã được load từ main.php layout -->
