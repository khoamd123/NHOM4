<?php
// Lấy sản phẩm hot nhất (giá cao nhất hoặc mới nhất)
require_once __DIR__ . '/../../../models/ProductModel.php';
$hotProducts = ProductModel::getAllProducts();

// Sắp xếp theo giá để lấy sản phẩm đắt nhất (premium)
usort($hotProducts, function($a, $b) {
    return $b['price'] - $a['price'];
});

$hotProduct = !empty($hotProducts) ? $hotProducts[0] : null;
?>

<div class="main-banner">
    <div class="container">
      <div class="row align-items-center">
        <!-- Left Content -->
        <div class="col-lg-6">
          <div class="caption header-text">

            
            <h6 class="welcome-text">🔥 SẢN PHẨM HOT NHẤT</h6>
            <h2 class="main-title">GIÀY CHÍNH HÃNG - SALE ĐẾN 50%</h2>
            <p class="description">
              ✨ Khám phá bộ sưu tập giày thể thao, công sở và thời trang<br>
              📦 <strong>Giao hàng toàn quốc</strong> | 
            </p>
            
            <div class="search-input">
              <form id="search" action="/NHOM4_DU_AN_1/index.php" method="GET">
                <input type="hidden" name="page" value="shop">
                <input type="text" 
                       placeholder="Tìm Nike, Adidas, Converse..." 
                       name="search" 
                       id='searchText' 
                       onkeypress="handle" />
                <button type="submit" role="button">🔍 TÌM NGAY</button>
              </form>
            </div>
          </div>
        </div>

        <!-- Right Content - Hot Product -->
        <div class="col-lg-6">
          <div class="hot-product-showcase">
            <?php if ($hotProduct): ?>
            <!-- Hot Product Card -->
            <div class="product-hero">
              <div class="product-image-container">
                <div class="hot-badge">🔥 HOT</div>
                <a href="/NHOM4_DU_AN_1/index.php?page=product&id=<?= $hotProduct['id'] ?>">
                  <?php 
                  $imagePath = "/NHOM4_DU_AN_1/public/uploads/products/" . htmlspecialchars($hotProduct['image']);
                  $fullImagePath = __DIR__ . "/../../../public/uploads/products/" . htmlspecialchars($hotProduct['image']);
                  
                  if (!empty($hotProduct['image']) && file_exists($fullImagePath)): ?>
                    <img src="<?= $imagePath ?>" 
                         alt="<?= htmlspecialchars($hotProduct['name']) ?>" 
                         class="hot-product-image">
                  <?php else: ?>
                    <img src="/NHOM4_DU_AN_1/public/assets/images/trending-01.jpg" 
                         alt="<?= htmlspecialchars($hotProduct['name']) ?>" 
                         class="hot-product-image">
                  <?php endif; ?>
                </a>
              </div>
              
              <div class="product-info">
                <div class="product-brand"><?= htmlspecialchars($hotProduct['brand']) ?></div>
                <h3 class="product-name"><?= htmlspecialchars($hotProduct['name']) ?></h3>
                
                <div class="price-section">
                  <?php 
                  $currentPrice = ProductModel::getProductPrice($hotProduct['id']);
                  $oldPrice = $currentPrice * 1.3; // 30% discount
                  ?>
                  <span class="old-price"><?= number_format($oldPrice, 0, ',', '.') ?>₫</span>
                  <span class="current-price"><?= number_format($currentPrice, 0, ',', '.') ?>₫</span>
                  <span class="discount-badge">-30%</span>
                </div>
                
                <a href="/NHOM4_DU_AN_1/index.php?page=product&id=<?= $hotProduct['id'] ?>" 
                   class="shop-now-btn">
                  🛒 MUA NGAY
                </a>
              </div>
            </div>
            <?php else: ?>
            <!-- Fallback nếu không có sản phẩm -->
            <div class="sale-card">
              <h3>SALE OFF</h3>
              <div class="price">2.500.000 VNĐ</div>
              <div class="discount">-40%</div>
            </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>
</div>

<style>
/* Banner Enhancement Styles */
.main-banner {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  min-height: 500px;
  display: flex;
  align-items: center;
  color: white;
}



.welcome-text {
  color: #FFD700;
  font-weight: bold;
  font-size: 14px;
  text-transform: uppercase;
  letter-spacing: 1px;
  margin-bottom: 10px;
}

.main-title {
  font-size: 2.5em;
  font-weight: bold;
  margin-bottom: 20px;
  text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.description {
  font-size: 1.1em;
  line-height: 1.6;
  margin-bottom: 25px;
  color: rgba(255,255,255,0.9);
}

.search-input {
  margin-top: 25px;
}

.search-input form {
  display: flex;
  background: white;
  border-radius: 50px;
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.search-input input {
  flex: 1;
  padding: 15px 20px;
  border: none;
  font-size: 16px;
  color: #333;
}

.search-input input::placeholder {
  color: #999;
}

.search-input button {
  background: #e75e8d;
  color: white;
  border: none;
  padding: 15px 25px;
  font-weight: bold;
  cursor: pointer;
  transition: background 0.3s ease;
}

.search-input button:hover {
  background: #d63384;
}

/* Hot Product Showcase */
.hot-product-showcase {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100%;
}

.product-hero {
  background: rgba(255,255,255,0.95);
  border-radius: 20px;
  padding: 25px;
  text-align: center;
  box-shadow: 0 10px 30px rgba(0,0,0,0.2);
  transition: transform 0.3s ease;
  max-width: 350px;
  color: #333;
}

.product-hero:hover {
  transform: translateY(-5px);
}

.product-image-container {
  position: relative;
  margin-bottom: 20px;
}

.hot-badge {
  position: absolute;
  top: -10px;
  right: -10px;
  background: #FF6B6B;
  color: white;
  padding: 5px 12px;
  border-radius: 20px;
  font-size: 12px;
  font-weight: bold;
  z-index: 10;
  box-shadow: 0 2px 8px rgba(0,0,0,0.2);
}

.hot-product-image {
  width: 200px;
  height: 150px;
  object-fit: cover;
  border-radius: 15px;
  transition: transform 0.3s ease;
}

.hot-product-image:hover {
  transform: scale(1.05);
}

.product-brand {
  color: #6c757d;
  font-size: 14px;
  font-weight: 500;
  margin-bottom: 8px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.product-name {
  font-size: 1.4em;
  font-weight: bold;
  margin-bottom: 15px;
  color: #333;
  line-height: 1.3;
}

.price-section {
  margin-bottom: 20px;
}

.old-price {
  color: #999;
  text-decoration: line-through;
  font-size: 14px;
  margin-right: 8px;
}

.current-price {
  color: #e75e8d;
  font-size: 1.5em;
  font-weight: bold;
  margin-right: 10px;
}

.discount-badge {
  background: #FF6B6B;
  color: white;
  padding: 4px 8px;
  border-radius: 12px;
  font-size: 12px;
  font-weight: bold;
}

.shop-now-btn {
  background: linear-gradient(45deg, #e75e8d, #f39c12);
  color: white;
  padding: 12px 30px;
  border-radius: 25px;
  text-decoration: none;
  font-weight: bold;
  display: inline-block;
  transition: all 0.3s ease;
  box-shadow: 0 4px 15px rgba(231, 94, 141, 0.3);
}

.shop-now-btn:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 20px rgba(231, 94, 141, 0.4);
  color: white;
  text-decoration: none;
}

/* Fallback Sale Card */
.sale-card {
  background: rgba(255,255,255,0.9);
  padding: 40px;
  border-radius: 20px;
  text-align: center;
  color: #333;
  box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

.sale-card h3 {
  font-size: 2em;
  margin-bottom: 20px;
  color: #333;
}

.sale-card .price {
  font-size: 2.5em;
  font-weight: bold;
  color: #e75e8d;
  margin-bottom: 15px;
}

.sale-card .discount {
  background: #FF6B6B;
  color: white;
  padding: 8px 20px;
  border-radius: 20px;
  font-weight: bold;
  display: inline-block;
}

/* Responsive */
@media (max-width: 768px) {
  .main-title {
    font-size: 1.8em;
  }
  
  .product-hero {
    margin-top: 30px;
    max-width: 280px;
    padding: 20px;
  }
  
  .hot-product-image {
    width: 150px;
    height: 120px;
  }
  
  .search-input form {
    flex-direction: column;
    border-radius: 15px;
  }
  
  .search-input button {
    border-radius: 0 0 15px 15px;
  }
  
  .description {
    font-size: 1em;
  }
}

@media (max-width: 480px) {
  .main-title {
    font-size: 1.5em;
  }
}
</style>