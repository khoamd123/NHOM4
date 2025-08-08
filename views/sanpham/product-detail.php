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
            <span class="old-price"><?= number_format($product['price'] * 1.2, 0, ',', '.') ?>đ</span>
            <span class="current-price"><?= number_format($product['price'], 0, ',', '.') ?>đ</span>
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
              <div class="quantity-selector">
                <label for="quantity">Số lượng:</label>
                <input type="number" id="quantity" name="quantity" value="1" min="1" max="10" 
                       class="form-control" style="width: 100px; display: inline-block;">
              </div>
              
              <button class="btn btn-primary btn-lg mt-3" onclick="addToCart()">
                <i class="fa fa-shopping-cart"></i> Thêm vào giỏ hàng
              </button>
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
                     <a href="/NHOM4_DU_AN_1/product.php?page=product&id=<?= $relatedProduct['id'] ?>">
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
                                         <a href="/NHOM4_DU_AN_1/product.php?page=product&id=<?= $relatedProduct['id'] ?>">
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
</style>

<script>
function addToCart() {
  const selectedVariant = document.querySelector('input[name="variant_id"]:checked');
  const quantity = document.getElementById('quantity').value;
  
  if (!selectedVariant) {
    alert('Vui lòng chọn size!');
    return;
  }
  
  if (quantity < 1) {
    alert('Số lượng phải lớn hơn 0!');
    return;
  }
  
  // TODO: Implement add to cart functionality
  alert('Đã thêm vào giỏ hàng! (Chức năng đang phát triển)');
}
</script> 