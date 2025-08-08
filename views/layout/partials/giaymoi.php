<div class="section trending">
  <div class="container">
    <div class="row">
      <div class="col-lg-6">
        <div class="section-heading">
          <h6>| Giày Mới</h6>
          <h2>Những mẫu giày mới nhất</h2>
        </div>
      </div>
      <div class="col-lg-6">
        <div class="main-button">
          <a href="/NHOM4_DU_AN_1/index.php?page=shop">Xem tất cả</a>
        </div>
      </div>
    </div>
    <div class="row">
      <?php
      require_once __DIR__ . '/../../../models/ProductModel.php';
      
      // Lấy sản phẩm mới nhất (sắp xếp theo ID giảm dần, lấy 4 sản phẩm)
      $newProducts = ProductModel::getAllProducts();
      $newProducts = array_slice($newProducts, 0, 4);
      
      foreach ($newProducts as $product):
      ?>
      <div class="col-lg-3 col-md-6">
        <div class="item">
                     <div class="thumb">
             <a href="/NHOM4_DU_AN_1/index.php?page=shop">
               <?php 
               $imagePath = "/NHOM4_DU_AN_1/public/uploads/products/" . htmlspecialchars($product['image']);
               $fullImagePath = __DIR__ . "/../../../public/uploads/products/" . htmlspecialchars($product['image']);
               
               if (!empty($product['image']) && file_exists($fullImagePath)): ?>
                 <img src="<?= $imagePath ?>" 
                      alt="<?= htmlspecialchars($product['name']) ?>" style="width: 100%; height: 200px; object-fit: cover;">
               <?php else: ?>
                 <img src="/NHOM4_DU_AN_1/public/assets/images/trending-01.jpg" 
                      alt="<?= htmlspecialchars($product['name']) ?>" style="width: 100%; height: 200px; object-fit: cover;">
               <?php endif; ?>
             </a>
            <span class="price">
              <em><?= number_format($product['price'] * 1.2, 0, ',', '.') ?>đ</em>
              <?= number_format($product['price'], 0, ',', '.') ?>đ
            </span>
          </div>
          <div class="down-content">
            <span class="category"><?= htmlspecialchars($product['brand']) ?></span>
            <h4><?= htmlspecialchars($product['name']) ?></h4>
                         <a href="/NHOM4_DU_AN_1/index.php?page=shop">
               <i class="fa fa-shopping-bag"></i>
             </a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div> 