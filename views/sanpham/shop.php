<?php
// Load models
require_once __DIR__ . '/../../models/ProductModel.php';

// Lấy tất cả danh mục
$categories = ProductModel::getAllCategories();

// Lấy dữ liệu cho bộ lọc
$allColors = ProductModel::getAllColors();
$allSizes = ProductModel::getAllSizes();
$allGenders = ProductModel::getAllGenders();

// Lấy tham số lọc từ URL (chỉ 3 bộ lọc mới + search)
$filters = [
    'search' => isset($_GET['search']) ? $_GET['search'] : '',
    'colors' => isset($_GET['colors']) ? explode(',', $_GET['colors']) : [],
    'sizes' => isset($_GET['sizes']) ? explode(',', $_GET['sizes']) : [],
    'genders' => isset($_GET['genders']) ? explode(',', $_GET['genders']) : []
];

// Lọc bỏ giá trị rỗng
$filters['colors'] = array_filter($filters['colors']);
$filters['sizes'] = array_filter($filters['sizes']);
$filters['genders'] = array_filter($filters['genders']);

// Lấy sản phẩm theo bộ lọc
$products = ProductModel::getFilteredProducts($filters);

// Nếu không có filter nào, lấy tất cả sản phẩm
if (empty($products) && empty($filters['search']) && empty($filters['colors']) && empty($filters['sizes']) && empty($filters['genders'])) {
    $products = ProductModel::getAllProducts();
}

// Danh sách hiển thị cho giới tính
$genderLabels = [
    'men' => 'Nam',
    'women' => 'Nữ', 
    'unisex' => 'Unisex'
];

// Helper function: Lấy mã màu CSS
function getColorCode($colorName) {
    $colorMap = [
        'Đen' => '#000000',
        'Trắng' => '#FFFFFF',
        'Xám' => '#808080',
        'Nâu' => '#8B4513',
        'Xanh' => '#0066CC',
        'Đỏ' => '#FF0000',
        'Vàng' => '#FFD700',
        'Hồng' => '#FF69B4',
        'Tím' => '#800080',
        'Cam' => '#FFA500'
    ];
    return $colorMap[$colorName] ?? '#CCCCCC';
}

// Helper function: Lấy icon cho giới tính
function getGenderIcon($gender) {
    $iconMap = [
        'men' => 'fa-male',
        'women' => 'fa-female',
        'unisex' => 'fa-users'
    ];
    return $iconMap[$gender] ?? 'fa-user';
}
?>

<div class="page-heading header-text">
  <div class="container">
    <div class="row">
      <div class="col-lg-12">
        <h3>Cửa hàng giày</h3>
        <span class="breadcrumb"><a href="/NHOM4_DU_AN_1/index.php">Trang chủ</a> > Cửa hàng</span>
      </div>
    </div>
  </div>
</div>

<!-- Shop Layout với Sidebar -->
<div class="section trending">
  <div class="container-fluid">
    <div class="row">
      
      <!-- Filter Sidebar (Desktop) -->
      <div class="col-lg-3 col-md-4 d-none d-md-block">
        <div class="filter-sidebar">
          <div class="filter-header">
            <h5><i class="fa fa-filter"></i> Bộ lọc</h5>
            <button type="button" onclick="clearAllFilters()" class="btn-clear-all">
              <i class="fa fa-times"></i> Xóa tất cả
            </button>
          </div>

          <!-- Filter theo màu sắc -->
          <div class="filter-section">
            <h6 class="filter-title">
              <i class="fa fa-palette"></i> Màu sắc
            </h6>
            <div class="filter-content">
              <div class="color-grid">
                <?php if (!empty($allColors)): ?>
                  <?php foreach ($allColors as $color): ?>
                    <label class="color-option-compact">
                      <input type="checkbox" 
                             value="<?= htmlspecialchars($color) ?>" 
                             <?= in_array($color, $filters['colors']) ? 'checked' : '' ?>
                             onchange="updateFilters()">
                      <span class="color-circle" 
                            style="background-color: <?= getColorCode($color) ?>" 
                            title="<?= htmlspecialchars($color) ?>"></span>
                      <span class="color-name"><?= htmlspecialchars($color) ?></span>
                    </label>
                  <?php endforeach; ?>
                <?php else: ?>
                  <label class="color-option-compact">
                    <input type="checkbox" value="Đen" onchange="updateFilters()">
                    <span class="color-circle" style="background-color: #000000"></span>
                    <span class="color-name">Đen</span>
                  </label>
                  <label class="color-option-compact">
                    <input type="checkbox" value="Trắng" onchange="updateFilters()">
                    <span class="color-circle" style="background-color: #FFFFFF; border: 1px solid #ddd;"></span>
                    <span class="color-name">Trắng</span>
                  </label>
                  <label class="color-option-compact">
                    <input type="checkbox" value="Xanh" onchange="updateFilters()">
                    <span class="color-circle" style="background-color: #0066CC"></span>
                    <span class="color-name">Xanh</span>
                  </label>
                  <label class="color-option-compact">
                    <input type="checkbox" value="Nâu" onchange="updateFilters()">
                    <span class="color-circle" style="background-color: #8B4513"></span>
                    <span class="color-name">Nâu</span>
                  </label>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <!-- Filter theo size -->
          <div class="filter-section">
            <h6 class="filter-title">
              <i class="fa fa-ruler"></i> Size
            </h6>
            <div class="filter-content">
              <div class="size-grid">
                <?php if (!empty($allSizes)): ?>
                  <?php foreach ($allSizes as $size): ?>
                    <label class="size-option-compact">
                      <input type="checkbox" 
                             value="<?= htmlspecialchars($size) ?>" 
                             <?= in_array($size, $filters['sizes']) ? 'checked' : '' ?>
                             onchange="updateFilters()">
                      <span class="size-box"><?= htmlspecialchars($size) ?></span>
                    </label>
                  <?php endforeach; ?>
                <?php else: ?>
                  <?php $fallbackSizes = ['36', '37', '38', '39', '40', '41', '42', '43', '44']; ?>
                  <?php foreach ($fallbackSizes as $size): ?>
                    <label class="size-option-compact">
                      <input type="checkbox" value="<?= $size ?>" onchange="updateFilters()">
                      <span class="size-box"><?= $size ?></span>
                    </label>
                  <?php endforeach; ?>
                <?php endif; ?>
              </div>
            </div>
          </div>

          <!-- Filter theo giới tính -->
          <div class="filter-section">
            <h6 class="filter-title">
              <i class="fa fa-users"></i> Giới tính
            </h6>
            <div class="filter-content">
              <div class="gender-list">
                <?php if (!empty($allGenders)): ?>
                  <?php foreach ($allGenders as $gender): ?>
                    <label class="gender-option-compact">
                      <input type="checkbox" 
                             value="<?= htmlspecialchars($gender) ?>" 
                             <?= in_array($gender, $filters['genders']) ? 'checked' : '' ?>
                             onchange="updateFilters()">
                      <span class="gender-checkbox"></span>
                      <i class="fa <?= getGenderIcon($gender) ?>"></i>
                      <span class="gender-text"><?= $genderLabels[$gender] ?? $gender ?></span>
                    </label>
                  <?php endforeach; ?>
                <?php else: ?>
                  <label class="gender-option-compact">
                    <input type="checkbox" value="men" onchange="updateFilters()">
                    <span class="gender-checkbox"></span>
                    <i class="fa fa-male"></i>
                    <span class="gender-text">Nam</span>
                  </label>
                  <label class="gender-option-compact">
                    <input type="checkbox" value="women" onchange="updateFilters()">
                    <span class="gender-checkbox"></span>
                    <i class="fa fa-female"></i>
                    <span class="gender-text">Nữ</span>
                  </label>
                  <label class="gender-option-compact">
                    <input type="checkbox" value="unisex" onchange="updateFilters()">
                    <span class="gender-checkbox"></span>
                    <i class="fa fa-users"></i>
                    <span class="gender-text">Unisex</span>
                  </label>
                <?php endif; ?>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Mobile Filter Button -->
      <div class="col-12 d-block d-md-none mb-3">
        <div class="mobile-filter-controls">
          <div class="row">
            <div class="col-8">
              <form method="GET" action="" class="d-flex">
                <input type="hidden" name="page" value="shop">
                <input type="text" name="search" value="<?= htmlspecialchars($filters['search']) ?>" 
                       class="form-control" placeholder="Tìm kiếm...">
                <button type="submit" class="btn btn-primary ms-2">
                  <i class="fa fa-search"></i>
                </button>
              </form>
            </div>
            <div class="col-4">
              <button class="btn btn-outline-primary w-100" onclick="toggleMobileFilter()">
                <i class="fa fa-filter"></i> Lọc
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Content Area -->
      <div class="col-lg-9 col-md-8">
        
        <!-- Desktop Search -->
        <div class="d-none d-md-block mb-3">
          <div class="search-bar">
            <form method="GET" action="" class="d-flex">
              <input type="hidden" name="page" value="shop">
              <input type="text" name="search" value="<?= htmlspecialchars($filters['search']) ?>" 
                     class="form-control me-2" placeholder="Tìm kiếm sản phẩm...">
              <button type="submit" class="btn btn-primary">Tìm kiếm</button>
              <?php if (!empty($filters['search'])): ?>
                <a href="?page=shop" class="btn btn-secondary ms-2">Xóa</a>
              <?php endif; ?>
            </form>
          </div>
        </div>

        <!-- Results Info -->
        <div class="results-info mb-3">
          <i class="fa fa-info-circle"></i>
          Tìm thấy <strong><?= count($products) ?></strong> sản phẩm
          <?php if (!empty($filters['search']) || !empty($filters['colors']) || !empty($filters['sizes']) || !empty($filters['genders'])): ?>
            với bộ lọc đã chọn
            <?php if (!empty($filters['colors'])): ?>
              • Màu: <strong><?= implode(', ', $filters['colors']) ?></strong>
            <?php endif; ?>
            <?php if (!empty($filters['sizes'])): ?>
              • Size: <strong><?= implode(', ', $filters['sizes']) ?></strong>
            <?php endif; ?>
            <?php if (!empty($filters['genders'])): ?>
              • Giới tính: <strong><?= implode(', ', array_map(function($g) use ($genderLabels) { return $genderLabels[$g] ?? $g; }, $filters['genders'])) ?></strong>
            <?php endif; ?>
            <?php if (!empty($filters['search'])): ?>
              • Từ khóa: <strong>"<?= htmlspecialchars($filters['search']) ?>"</strong>
            <?php endif; ?>
          <?php endif; ?>
        </div>

        <!-- Products Grid -->
        <div class="row trending-box">
          <?php if (empty($products)): ?>
            <div class="col-12 text-center">
              <h4>Không tìm thấy sản phẩm nào</h4>
              <p>Vui lòng thử lại với bộ lọc khác</p>
            </div>
          <?php else: ?>
            <?php foreach ($products as $product): ?>
              <div class="col-lg-4 col-md-6 align-self-center mb-30 trending-items">
                <div class="item">
                  <div class="thumb">
                    <a href="/NHOM4_DU_AN_1/product.php?page=product&id=<?= $product['id'] ?>">
                      <?php 
                      $imagePath = "/NHOM4_DU_AN_1/public/uploads/products/" . htmlspecialchars($product['image']);
                      $fullImagePath = __DIR__ . "/../../public/uploads/products/" . htmlspecialchars($product['image']);
                      
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
                    <a href="/NHOM4_DU_AN_1/product.php?page=product&id=<?= $product['id'] ?>">
                      <i class="fa fa-shopping-bag"></i>
                    </a>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>

        <!-- Phân trang -->
        <?php if (count($products) > 12): ?>
          <div class="row">
            <div class="col-lg-12">
              <ul class="pagination">
                <li><a href="#"> &lt; </a></li>
                <li><a href="#">1</a></li>
                <li><a class="is_active" href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#"> &gt; </a></li>
              </ul>
            </div>
          </div>
        <?php endif; ?>
        
      </div> <!-- End Main Content -->
    </div> <!-- End Row -->
  </div> <!-- End Container -->
</div> <!-- End Section -->

<style>
/* Sidebar Filter Styles */
.filter-sidebar {
  background: #fff;
  border: 1px solid #e9ecef;
  border-radius: 12px;
  padding: 0;
  box-shadow: 0 2px 12px rgba(0,0,0,0.08);
  margin-bottom: 20px;
  position: sticky;
  top: 20px;
}

.filter-header {
  background: #f8f9fa;
  padding: 20px;
  border-bottom: 1px solid #e9ecef;
  border-radius: 12px 12px 0 0;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.filter-header h5 {
  margin: 0;
  color: #333;
  font-weight: 600;
  font-size: 18px;
}

.filter-header i {
  color: #e75e8d;
  margin-right: 8px;
}

.btn-clear-all {
  background: none;
  border: none;
  color: #6c757d;
  font-size: 12px;
  padding: 4px 8px;
  border-radius: 20px;
  transition: all 0.2s;
}

.btn-clear-all:hover {
  background: #e9ecef;
  color: #495057;
}

.filter-section {
  border-bottom: 1px solid #f1f3f4;
  padding: 20px;
}

.filter-section:last-child {
  border-bottom: none;
}

.filter-title {
  color: #333;
  margin-bottom: 15px;
  font-weight: 600;
  font-size: 14px;
  text-transform: uppercase;
  letter-spacing: 0.5px;
}

.filter-title i {
  color: #e75e8d;
  margin-right: 8px;
  width: 16px;
}

.filter-content {
  margin-top: 15px;
}

/* Color Filter Compact */
.color-grid {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.color-option-compact {
  display: flex;
  align-items: center;
  cursor: pointer;
  padding: 8px 0;
  transition: all 0.2s;
}

.color-option-compact:hover {
  background: #f8f9fa;
  margin: 0 -10px;
  padding: 8px 10px;
  border-radius: 6px;
}

.color-option-compact input[type="checkbox"] {
  display: none;
}

.color-circle {
  width: 20px;
  height: 20px;
  border-radius: 50%;
  margin-right: 12px;
  border: 2px solid #ddd;
  transition: all 0.2s;
  flex-shrink: 0;
}

.color-option-compact input[type="checkbox"]:checked + .color-circle {
  border-color: #e75e8d;
  box-shadow: 0 0 0 2px rgba(231, 94, 141, 0.2);
}

.color-name {
  font-size: 14px;
  color: #495057;
  font-weight: 500;
}

/* Size Filter Compact */
.size-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 8px;
}

.size-option-compact {
  cursor: pointer;
}

.size-option-compact input[type="checkbox"] {
  display: none;
}

.size-box {
  display: block;
  width: 100%;
  padding: 8px;
  text-align: center;
  border: 1px solid #ddd;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 500;
  color: #495057;
  background: #fff;
  transition: all 0.2s;
}

.size-option-compact input[type="checkbox"]:checked + .size-box {
  background: #e75e8d;
  color: white;
  border-color: #e75e8d;
}

.size-box:hover {
  border-color: #e75e8d;
  color: #e75e8d;
}

/* Gender Filter Compact */
.gender-list {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.gender-option-compact {
  display: flex;
  align-items: center;
  cursor: pointer;
  padding: 8px 0;
  transition: all 0.2s;
}

.gender-option-compact:hover {
  background: #f8f9fa;
  margin: 0 -10px;
  padding: 8px 10px;
  border-radius: 6px;
}

.gender-option-compact input[type="checkbox"] {
  display: none;
}

.gender-checkbox {
  width: 16px;
  height: 16px;
  border: 2px solid #ddd;
  border-radius: 3px;
  margin-right: 12px;
  position: relative;
  transition: all 0.2s;
}

.gender-option-compact input[type="checkbox"]:checked + .gender-checkbox {
  background: #e75e8d;
  border-color: #e75e8d;
}

.gender-option-compact input[type="checkbox"]:checked + .gender-checkbox::after {
  content: '✓';
  position: absolute;
  top: -2px;
  left: 2px;
  color: white;
  font-size: 10px;
  font-weight: bold;
}

.gender-text {
  font-size: 14px;
  color: #495057;
  font-weight: 500;
  margin-left: 8px;
}

/* Mobile Controls */
.mobile-filter-controls {
  background: #fff;
  padding: 15px;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Form Styles */
.form-control {
  border-radius: 8px;
  border: 1px solid #ddd;
  padding: 10px 15px;
}

.btn {
  border-radius: 8px;
  padding: 10px 20px;
  font-weight: 500;
}

.btn-primary {
  background-color: #e75e8d;
  border-color: #e75e8d;
}

.btn-primary:hover {
  background-color: #d63384;
  border-color: #d63384;
}

.btn-outline-primary {
  color: #e75e8d;
  border-color: #e75e8d;
}

.btn-outline-primary:hover {
  background-color: #e75e8d;
  border-color: #e75e8d;
}

/* Results Info */
.results-info {
  background: #e8f5e8;
  padding: 15px 20px;
  border-radius: 8px;
  color: #2e7d32;
  font-weight: 500;
  border-left: 4px solid #4caf50;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
  .filter-sidebar {
    position: fixed;
    top: 0;
    left: -100%;
    width: 300px;
    height: 100vh;
    z-index: 1050;
    transition: left 0.3s ease;
    overflow-y: auto;
  }

  .filter-sidebar.show {
    left: 0;
  }

  .filter-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 1040;
    display: none;
  }

  .filter-overlay.show {
    display: block;
  }
}

/* Product Grid Responsive */
@media (min-width: 768px) and (max-width: 991px) {
  .trending-items .col-lg-4 {
    flex: 0 0 50%;
    max-width: 50%;
  }
}

@media (min-width: 992px) {
  .trending-items .col-lg-4 {
    flex: 0 0 33.333333%;
    max-width: 33.333333%;
  }
}


</style>

<script>
function updateFilters() {
  // Lấy tất cả các filter đã chọn (cập nhật selectors)
  const selectedColors = Array.from(document.querySelectorAll('.color-option-compact input:checked')).map(cb => cb.value);
  const selectedSizes = Array.from(document.querySelectorAll('.size-option-compact input:checked')).map(cb => cb.value);
  const selectedGenders = Array.from(document.querySelectorAll('.gender-option-compact input:checked')).map(cb => cb.value);
  
  // Lấy các params hiện tại
  const urlParams = new URLSearchParams(window.location.search);
  
  // Cập nhật URL params
  urlParams.set('page', 'shop');
  
  // Xóa params cũ
  urlParams.delete('colors');
  urlParams.delete('sizes'); 
  urlParams.delete('genders');
  
  // Thêm params mới nếu có
  if (selectedColors.length > 0) {
    urlParams.set('colors', selectedColors.join(','));
  }
  if (selectedSizes.length > 0) {
    urlParams.set('sizes', selectedSizes.join(','));
  }
  if (selectedGenders.length > 0) {
    urlParams.set('genders', selectedGenders.join(','));
  }
  
  // Redirect với params mới
  window.location.search = urlParams.toString();
}

function clearAllFilters() {
  // Redirect về trang shop không có filter (chỉ giữ search nếu có)
  const urlParams = new URLSearchParams(window.location.search);
  const search = urlParams.get('search');
  
  let newUrl = '?page=shop';
  if (search) {
    newUrl += '&search=' + encodeURIComponent(search);
  }
  
  window.location.href = newUrl;
}

// Mobile filter toggle
function toggleMobileFilter() {
  const sidebar = document.querySelector('.filter-sidebar');
  const overlay = document.querySelector('.filter-overlay') || createOverlay();
  
  sidebar.classList.toggle('show');
  overlay.classList.toggle('show');
}

function createOverlay() {
  const overlay = document.createElement('div');
  overlay.className = 'filter-overlay';
  overlay.onclick = toggleMobileFilter;
  document.body.appendChild(overlay);
  return overlay;
}

// Add loading effect when filtering
document.addEventListener('DOMContentLoaded', function() {
  const filterInputs = document.querySelectorAll('.color-option-compact input, .size-option-compact input, .gender-option-compact input');
  
  filterInputs.forEach(input => {
    input.addEventListener('change', function() {
      // Add loading class to products container
      const productsContainer = document.querySelector('.trending-box');
      if (productsContainer) {
        productsContainer.style.opacity = '0.5';
        productsContainer.style.pointerEvents = 'none';
      }
      
      // Small delay for better UX
      setTimeout(() => {
        updateFilters();
      }, 100);
    });
  });
  
  // Close mobile filter when clicking outside
  document.addEventListener('click', function(e) {
    const sidebar = document.querySelector('.filter-sidebar');
    const mobileButton = document.querySelector('[onclick="toggleMobileFilter()"]');
    
    if (sidebar && sidebar.classList.contains('show') && 
        !sidebar.contains(e.target) && 
        !mobileButton.contains(e.target)) {
      toggleMobileFilter();
    }
  });
});
</script>