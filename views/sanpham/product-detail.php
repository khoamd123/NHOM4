<?php
// Load models
require_once __DIR__ . '/../../models/ProductModel.php';

// Lấy ID sản phẩm từ URL
$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($productId <= 0) {
    header("Location: /NHOM4_DU_AN_1/index.php?page=shop");
    exit;
}

// Lấy thông tin sản phẩm
$product = ProductModel::getProductById($productId);
if (!$product) {
    header("Location: /NHOM4_DU_AN_1/index.php?page=shop");
    exit;
}

// Lấy biến thể sản phẩm
$variants = ProductModel::getProductVariants($productId);

// Lấy danh mục của sản phẩm
$categories = ProductModel::getAllCategories();
$productCategories = [];
if (!empty($product['category_names'])) {
    $productCategories = explode(',', $product['category_names']);
}
?>

<div class="page-heading header-text">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <h3><?= htmlspecialchars($product['name']) ?></h3>
        <span class="breadcrumb">
          <a href="/NHOM4_DU_AN_1/index.php">Trang chủ</a> > 
          <a href="/NHOM4_DU_AN_1/index.php?page=shop">Cửa hàng</a> > 
          <?= htmlspecialchars($product['name']) ?>
        </span>
      </div>
    </div>
  </div>
</div>

<div class="section trending">
  <div class="container">
    <div class="row">
      <!-- Ảnh sản phẩm -->
      <div class="col-lg-6">
        <div class="product-image-container">
          <?php 
          if (!empty($product['image'])) {
              if (filter_var($product['image'], FILTER_VALIDATE_URL)) {
                  $imageSrc = $product['image'];
              } else {
                  $imageSrc = '/NHOM4_DU_AN_1/public/uploads/products/' . $product['image'];
              }
          } else {
              $imageSrc = '/NHOM4_DU_AN_1/public/assets/images/trending-01.jpg';
          }
          ?>
          <img src="<?= htmlspecialchars($imageSrc) ?>" 
               alt="<?= htmlspecialchars($product['name']) ?>" 
               class="img-fluid product-main-image"
               style="width: 100%; max-height: 500px; object-fit: cover; border-radius: 10px;">
        </div>
      </div>
      
      <!-- Thông tin sản phẩm -->
      <div class="col-lg-6">
        <div class="product-info">
          <h2 class="product-title"><?= htmlspecialchars($product['name']) ?></h2>
          
          <div class="product-brand">
            <strong>Thương hiệu:</strong> <?= htmlspecialchars($product['brand']) ?>
          </div>
          
          <div class="product-price">
            <?php 
            $currentPrice = ProductModel::getProductPrice($productId);
            $oldPrice = $currentPrice * 1.2; // 20% discount
            ?>
            <span class="old-price"><?= number_format($oldPrice, 0, ',', '.') ?>đ</span>
            <span class="current-price"><?= number_format($currentPrice, 0, ',', '.') ?>đ</span>
          </div>
          
          <div class="product-description">
            <h5>Mô tả sản phẩm:</h5>
            <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>
          </div>
          
          <?php if (!empty($productCategories)): ?>
          <div class="product-categories">
            <h5>Danh mục:</h5>
            <div class="category-tags">
              <?php foreach ($productCategories as $category): ?>
                <span class="badge bg-primary me-2"><?= htmlspecialchars(trim($category)) ?></span>
              <?php endforeach; ?>
            </div>
          </div>
          <?php endif; ?>
          
          <!-- Form chọn size và thêm vào giỏ hàng -->
          <?php if (!empty($variants)): ?>
          <div class="product-variants">
            <h5>Chọn size:</h5>
            <div class="size-options">
              <?php foreach ($variants as $variant): ?>
                <div class="size-option">
                  <input type="radio" name="variant_id" id="variant_<?= $variant['id'] ?>" 
                         value="<?= $variant['id'] ?>" class="btn-check">
                  <label class="btn btn-outline-primary" for="variant_<?= $variant['id'] ?>">
                    Size <?= htmlspecialchars($variant['size']) ?> - 
                    <?= htmlspecialchars($variant['color']) ?>
                    <?php if ($variant['stock'] > 0): ?>
                      <span class="badge bg-success">Còn <?= $variant['stock'] ?></span>
                    <?php else: ?>
                      <span class="badge bg-danger">Hết hàng</span>
                    <?php endif; ?>
                  </label>
                </div>
              <?php endforeach; ?>
            </div>
            
            <div class="add-to-cart mt-3">
              <!-- Quantity Selector -->
              <div class="quantity-selector mb-3">
                <label for="quantity" class="form-label">Số lượng:</label>
                <div class="input-group" style="width: 150px;">
                  <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(-1)">
                    <i class="fas fa-minus"></i>
                  </button>
                  <input type="number" class="form-control text-center" id="quantity" value="1" min="1" max="10">
                  <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(1)">
                    <i class="fas fa-plus"></i>
                  </button>
                </div>
              </div>
              
              <!-- Action Buttons -->
              <div class="action-buttons d-flex gap-2 mb-3">
                <button class="btn btn-primary btn-lg add-to-cart-btn" disabled onclick="addToCartFromDetail()">
                  <i class="fa fa-shopping-cart"></i> Thêm vào giỏ hàng
                </button>
                <button class="btn btn-success btn-lg buy-now-btn" disabled onclick="buyNow()">
                  <i class="fa fa-bolt"></i> Mua ngay
                </button>
              </div>
              
              <p class="text-muted">
                <i class="fas fa-info-circle"></i> Vui lòng chọn size để tiếp tục
              </p>
            </div>
          </div>
          <?php else: ?>
          <div class="alert alert-warning">
            <i class="fa fa-exclamation-triangle"></i> Sản phẩm hiện chưa có biến thể. Vui lòng liên hệ admin.
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
    
    <!-- Sản phẩm liên quan -->
    <div class="row mt-5">
      <div class="col-12">
        <h3>Sản phẩm liên quan</h3>
        <div class="row">
          <?php
          // Lấy sản phẩm cùng thương hiệu
          $relatedProducts = ProductModel::getAllProducts();
          $relatedProducts = array_filter($relatedProducts, function($p) use ($product) {
              return $p['id'] != $product['id'] && $p['brand'] == $product['brand'];
          });
          $relatedProducts = array_slice($relatedProducts, 0, 4);
          ?>
          
          <?php if (!empty($relatedProducts)): ?>
            <?php foreach ($relatedProducts as $relatedProduct): ?>
              <div class="col-lg-3 col-md-6 mb-4">
                <div class="item">
                                     <div class="thumb">
                     <a href="/NHOM4_DU_AN_1/index.php?page=product&id=<?= $relatedProduct['id'] ?>">
                       <?php 
                       if (!empty($relatedProduct['image'])) {
                           if (filter_var($relatedProduct['image'], FILTER_VALIDATE_URL)) {
                               $relatedImageSrc = $relatedProduct['image'];
                           } else {
                               $relatedImageSrc = '/NHOM4_DU_AN_1/public/uploads/products/' . $relatedProduct['image'];
                           }
                       } else {
                           $relatedImageSrc = '/NHOM4_DU_AN_1/public/assets/images/trending-01.jpg';
                       }
                       ?>
                       <img src="<?= htmlspecialchars($relatedImageSrc) ?>" 
                            alt="<?= htmlspecialchars($relatedProduct['name']) ?>" 
                            style="width: 100%; height: 200px; object-fit: cover;">
                     </a>
                    <span class="price">
                      <em><?= number_format($relatedProduct['price'] * 1.2, 0, ',', '.') ?>đ</em>
                      <?= number_format($relatedProduct['price'], 0, ',', '.') ?>đ
                    </span>
                  </div>
                  <div class="down-content">
                    <span class="category"><?= htmlspecialchars($relatedProduct['brand']) ?></span>
                    <h4><?= htmlspecialchars($relatedProduct['name']) ?></h4>
                                         <a href="/NHOM4_DU_AN_1/index.php?page=product&id=<?= $relatedProduct['id'] ?>">
                       <i class="fa fa-shopping-bag"></i>
                     </a>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="col-12">
              <p class="text-muted">Không có sản phẩm liên quan.</p>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.product-title {
  color: #333;
  margin-bottom: 20px;
}

.product-brand {
  margin-bottom: 15px;
  color: #666;
}

.product-price {
  margin-bottom: 20px;
}

.old-price {
  text-decoration: line-through;
  color: #999;
  font-size: 18px;
  margin-right: 10px;
}

.current-price {
  color: #e75e8d;
  font-size: 24px;
  font-weight: bold;
}

.product-description {
  margin-bottom: 20px;
}

.product-description h5 {
  color: #333;
  margin-bottom: 10px;
}

.product-categories {
  margin-bottom: 20px;
}

.product-categories h5 {
  color: #333;
  margin-bottom: 10px;
}

.size-options {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin-bottom: 20px;
}

.size-option label {
  margin: 0;
  cursor: pointer;
}

.quantity-selector {
  margin-bottom: 15px;
}

.quantity-selector label {
  margin-right: 10px;
  font-weight: bold;
}

.add-to-cart .btn {
  width: 100%;
  padding: 15px;
  font-size: 18px;
}

.product-main-image {
  box-shadow: 0 5px 15px rgba(0,0,0,0.1);
  transition: transform 0.3s ease;
}

.product-main-image:hover {
  transform: scale(1.02);
}

/* Action Buttons Styling */
.action-buttons {
  gap: 15px !important;
}

.action-buttons .btn {
  flex: 1;
  padding: 15px 20px;
  font-weight: 600;
  border-radius: 8px;
  transition: all 0.3s ease;
  position: relative;
  overflow: hidden;
}

.add-to-cart-btn {
  background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
  border: none;
  box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
}

.add-to-cart-btn:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
}

.buy-now-btn {
  background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
  border: none;
  box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.buy-now-btn:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
}

.action-buttons .btn:disabled {
  background: #6c757d;
  box-shadow: none;
  transform: none;
}

.action-buttons .btn i {
  margin-right: 8px;
}

/* Quantity Selector Enhancement */
.quantity-selector .input-group {
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
  border-radius: 8px;
  overflow: hidden;
}

.quantity-selector .btn {
  border: none;
  background: #f8f9fa;
  color: #495057;
  width: 40px;
}

.quantity-selector .btn:hover {
  background: #e9ecef;
  color: #212529;
}

.quantity-selector .form-control {
  border: none;
  background: white;
  font-weight: 600;
  font-size: 16px;
}

/* Force Buy Button Styles */
.buy-now-btn {
  background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%) !important;
  border-color: #28a745 !important;
  color: white !important;
  opacity: 1 !important;
  cursor: pointer !important;
  pointer-events: auto !important;
}

.buy-now-btn:hover {
  background: linear-gradient(135deg, #218838 0%, #1e7e34 100%) !important;
  transform: translateY(-2px);
  box-shadow: 0 8px 25px rgba(40, 167, 69, 0.4) !important;
}

.buy-now-btn:disabled {
  background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%) !important;
  opacity: 0.9 !important;
  cursor: pointer !important;
  pointer-events: auto !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .action-buttons {
    flex-direction: column !important;
    gap: 10px !important;
  }
  
  .action-buttons .btn {
    width: 100%;
    padding: 12px 15px;
  }
}
</style>

<script>
let selectedVariantId = null;

function changeQuantity(change) {
  const quantityInput = document.getElementById('quantity');
  let currentValue = parseInt(quantityInput.value);
  let newValue = currentValue + change;
  
  if (newValue >= 1 && newValue <= parseInt(quantityInput.max)) {
    quantityInput.value = newValue;
  }
}

function addToCartFromDetail() {
  if (!selectedVariantId) {
    alert('Vui lòng chọn size và màu sắc');
    return;
  }
  
  const quantity = parseInt(document.getElementById('quantity').value);
  addToCart(selectedVariantId, quantity);
}

function buyNow() {
  // Direct redirect to checkout - simplified
  console.log('buyNow clicked - redirecting to checkout');
  window.location.href = '/NHOM4_DU_AN_1/index.php?page=checkout';
}

// Update the selectVariant function for this page
function selectVariant(variantId, element) {
  // Remove active class from all variants
  document.querySelectorAll('.btn-check').forEach(option => {
    const label = document.querySelector(`label[for="${option.id}"]`);
    if (label) label.classList.remove('active');
  });
  
  // Add active class to selected variant
  element.classList.add('active');
  
  // Update selected variant
  selectedVariantId = variantId;
  
  // Enable both buttons
  const addButton = document.querySelector('.add-to-cart-btn');
  const buyButton = document.querySelector('.buy-now-btn');
  
  if (addButton) {
    addButton.disabled = false;
  }
  if (buyButton) {
    buyButton.disabled = false;
  }
  
  const infoText = document.querySelector('.text-muted');
  if (infoText) {
    infoText.innerHTML = '<i class="fas fa-check-circle text-success"></i> Đã chọn size, có thể mua hàng';
  }
}

// Auto-select variant when radio is clicked
document.addEventListener('DOMContentLoaded', function() {
  // FORCE ENABLE BUTTONS IMMEDIATELY
  setTimeout(function() {
    const addButton = document.querySelector('.add-to-cart-btn');
    const buyButton = document.querySelector('.buy-now-btn');
    
    console.log('Force enabling buttons...');
    
    if (addButton) {
      addButton.disabled = false;
      addButton.style.opacity = '1';
      addButton.style.cursor = 'pointer';
      console.log('Add button enabled');
    }
    
    if (buyButton) {
      buyButton.disabled = false;
      buyButton.style.background = 'linear-gradient(135deg, #28a745 0%, #1e7e34 100%)';
      buyButton.style.opacity = '1';
      buyButton.style.cursor = 'pointer';
      buyButton.style.color = 'white';
      console.log('Buy button enabled and styled');
    }
    
    // Remove warning message
    const warningMsg = document.querySelector('.text-muted');
    if (warningMsg && warningMsg.textContent.includes('Vui lòng chọn size')) {
      warningMsg.innerHTML = '<i class="fas fa-info-circle text-info"></i> Sẵn sàng mua hàng';
    }
  }, 100);
  
  document.querySelectorAll('.btn-check').forEach(radio => {
    radio.addEventListener('change', function() {
      if (this.checked) {
        const label = document.querySelector(`label[for="${this.id}"]`);
        selectVariant(this.value, label);
      }
    });
  });
});
</script>

<script src="/NHOM4_DU_AN_1/public/assets/js/cart.js"></script>

<!-- EMERGENCY FIX: Force enable buy button -->
<script>
// Multiple attempts to ensure button works
setTimeout(() => {
  const buyBtn = document.querySelector('.buy-now-btn');
  if (buyBtn) {
    buyBtn.disabled = false;
    buyBtn.style.background = 'linear-gradient(135deg, #28a745 0%, #1e7e34 100%)';
    buyBtn.style.color = 'white';
    buyBtn.style.opacity = '1';
    buyBtn.style.cursor = 'pointer';
    buyBtn.onclick = function() {
      console.log('Buy now clicked!');
      alert('Chuyển đến trang thanh toán...');
      window.location.href = '/NHOM4_DU_AN_1/index.php?page=checkout';
    };
    console.log('✅ EMERGENCY FIX: Buy button fully enabled!');
  }
}, 500);

// Force again after 1 second
setTimeout(() => {
  const buyBtn = document.querySelector('.buy-now-btn');
  if (buyBtn && buyBtn.disabled) {
    buyBtn.disabled = false;
    buyBtn.style.background = '#28a745';
    console.log('🔧 Second attempt: Buy button enabled');
  }
}, 1000);
</script> 