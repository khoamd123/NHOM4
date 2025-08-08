<?php
// Load models để lấy danh mục từ database
require_once __DIR__ . '/../../../models/ProductModel.php';

// Lấy tất cả danh mục
$categories = ProductModel::getAllCategories();

// Mảng màu gradient cho các danh mục
$gradients = [
    'linear-gradient(45deg, #e75e8d, #f39c12)',
    'linear-gradient(45deg, #9b59b6, #3498db)', 
    'linear-gradient(45deg, #e74c3c, #f1c40f)',
    'linear-gradient(45deg, #2c3e50, #34495e)',
    'linear-gradient(45deg, #1abc9c, #16a085)',
    'linear-gradient(45deg, #f39c12, #e67e22)',
    'linear-gradient(45deg, #3498db, #2980b9)',
    'linear-gradient(45deg, #e91e63, #9c27b0)'
];

// Icon cho từng loại giày
$categoryIcons = [
    'thể thao' => 'fa-running',
    'công sở' => 'fa-briefcase', 
    'búp bê' => 'fa-female',
    'lười' => 'fa-leaf',
    'cao gót' => 'fa-high-heel',
    'sneaker' => 'fa-shoe-prints',
    'sandal' => 'fa-umbrella-beach',
    'boot' => 'fa-hiking'
];

// Hàm chọn icon phù hợp
function getIconForCategory($categoryName) {
    global $categoryIcons;
    $name = strtolower($categoryName);
    foreach ($categoryIcons as $keyword => $icon) {
        if (strpos($name, $keyword) !== false) {
            return $icon;
        }
    }
    return 'fa-shoe-prints'; // Icon mặc định
}
?>

<div class="section categories">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="section-heading">
                    <h6>| Danh Mục</h6>
                    <h2>Khám phá theo danh mục</h2>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="main-button">
                    <a href="/NHOM4_DU_AN_1/index.php?page=shop">Xem tất cả</a>
                </div>
            </div>
        </div>
        
        <div class="row">
            <?php if (!empty($categories)): ?>
                <?php foreach ($categories as $index => $category): ?>
                    <?php 
                    // Lấy ảnh đại diện và số lượng sản phẩm cho danh mục
                    $categoryImage = ProductModel::getCategoryImage($category['name']);
                    $productCount = ProductModel::getCategoryProductCount($category['name']);
                    
                    // Xử lý đường dẫn ảnh
                    if ($categoryImage) {
                        if (filter_var($categoryImage, FILTER_VALIDATE_URL)) {
                            $imageSrc = $categoryImage;
                        } else {
                            $imageSrc = '/NHOM4_DU_AN_1/public/uploads/products/' . $categoryImage;
                        }
                    } else {
                        // Fallback images cho từng loại danh mục
                        $fallbackImages = [
                            'thể thao' => '/NHOM4_DU_AN_1/public/assets/images/trending-01.jpg',
                            'công sở' => '/NHOM4_DU_AN_1/public/assets/images/trending-02.jpg',
                            'búp bê' => '/NHOM4_DU_AN_1/public/assets/images/trending-03.jpg',
                            'lười' => '/NHOM4_DU_AN_1/public/assets/images/trending-04.jpg',
                            'cao gót' => '/NHOM4_DU_AN_1/public/assets/images/featured-01.png'
                        ];
                        
                        $name = strtolower($category['name']);
                        $imageSrc = '/NHOM4_DU_AN_1/public/assets/images/trending-01.jpg'; // default
                        foreach ($fallbackImages as $keyword => $img) {
                            if (strpos($name, $keyword) !== false) {
                                $imageSrc = $img;
                                break;
                            }
                        }
                    }
                    ?>
                    <div class="col-lg-<?= count($categories) >= 4 ? '3' : '4' ?> col-md-6 col-sm-6 mb-4">
                        <div class="category-item">
                            <div class="category-card-with-image">
                                <div class="category-image">
                                    <img src="<?= htmlspecialchars($imageSrc) ?>" 
                                         alt="<?= htmlspecialchars($category['name']) ?>"
                                         class="category-bg-image">
                                    <div class="category-overlay"></div>
                                </div>
                                <div class="category-content-over-image">
                                    <div class="category-icon">
                                        <i class="fa <?= getIconForCategory($category['name']) ?>"></i>
                                    </div>
                                    <h4><?= htmlspecialchars($category['name']) ?></h4>
                                    <p class="category-count"><?= $productCount ?> sản phẩm</p>
                                    <a href="/NHOM4_DU_AN_1/index.php?page=shop&category=<?= urlencode($category['name']) ?>" class="category-btn-new">
                                        Khám phá ngay <i class="fa fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <!-- Fallback nếu không có danh mục trong database -->
                <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                    <div class="category-item">
                        <div class="category-card" style="background: linear-gradient(45deg, #e75e8d, #f39c12);">
                            <div class="category-content">
                                <div class="category-icon">
                                    <i class="fa fa-running"></i>
                                </div>
                                <h4>Giày Thể Thao</h4>
                                <p>Năng động mọi lúc</p>
                                <a href="/NHOM4_DU_AN_1/index.php?page=shop" class="category-btn">
                                    Xem sản phẩm <i class="fa fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                    <div class="category-item">
                        <div class="category-card" style="background: linear-gradient(45deg, #9b59b6, #3498db);">
                            <div class="category-content">
                                <div class="category-icon">
                                    <i class="fa fa-briefcase"></i>
                                </div>
                                <h4>Giày Công Sở</h4>
                                <p>Lịch lãm, chuyên nghiệp</p>
                                <a href="/NHOM4_DU_AN_1/index.php?page=shop" class="category-btn">
                                    Xem sản phẩm <i class="fa fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                    <div class="category-item">
                        <div class="category-card" style="background: linear-gradient(45deg, #e74c3c, #f1c40f);">
                            <div class="category-content">
                                <div class="category-icon">
                                    <i class="fa fa-heart"></i>
                                </div>
                                <h4>Giày Thời Trang</h4>
                                <p>Phong cách cá nhân</p>
                                <a href="/NHOM4_DU_AN_1/index.php?page=shop" class="category-btn">
                                    Xem sản phẩm <i class="fa fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
                    <div class="category-item">
                        <div class="category-card" style="background: linear-gradient(45deg, #1abc9c, #16a085);">
                            <div class="category-content">
                                <div class="category-icon">
                                    <i class="fa fa-star"></i>
                                </div>
                                <h4>Cao Cấp</h4>
                                <p>Sang trọng đẳng cấp</p>
                                <a href="/NHOM4_DU_AN_1/index.php?page=shop" class="category-btn">
                                    Xem sản phẩm <i class="fa fa-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.categories {
    padding: 80px 0;
    background: #f8f9fa;
}

.category-item {
    height: 100%;
}

/* Layout mới với ảnh sản phẩm */
.category-card-with-image {
    height: 320px;
    border-radius: 20px;
    overflow: hidden;
    position: relative;
    transition: all 0.3s ease;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    background: #fff;
}

.category-card-with-image:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
}

.category-image {
    position: relative;
    width: 100%;
    height: 100%;
    overflow: hidden;
}

.category-bg-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.category-card-with-image:hover .category-bg-image {
    transform: scale(1.1);
}

.category-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(
        135deg, 
        rgba(231, 94, 141, 0.8) 0%, 
        rgba(102, 126, 234, 0.6) 50%,
        rgba(118, 75, 162, 0.8) 100%
    );
    transition: opacity 0.3s ease;
}

.category-card-with-image:hover .category-overlay {
    opacity: 0.9;
}

.category-content-over-image {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 30px;
    color: white;
    z-index: 2;
}

.category-icon {
    font-size: 48px;
    margin-bottom: 20px;
    opacity: 0.9;
    transition: all 0.3s ease;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.category-card-with-image:hover .category-icon {
    transform: scale(1.2) rotate(5deg);
}

.category-content-over-image h4 {
    font-size: 26px;
    font-weight: 700;
    margin-bottom: 8px;
    color: white;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.category-count {
    font-size: 16px;
    margin-bottom: 25px;
    opacity: 0.9;
    color: white;
    background: rgba(255,255,255,0.2);
    padding: 4px 12px;
    border-radius: 12px;
    display: inline-block;
    font-weight: 500;
}

.category-btn-new {
    background: rgba(255,255,255,0.25);
    color: white !important;
    padding: 14px 28px;
    border-radius: 30px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    border: 2px solid rgba(255,255,255,0.4);
    display: inline-flex;
    align-items: center;
    gap: 10px;
    backdrop-filter: blur(10px);
    font-size: 15px;
}

.category-btn-new:hover {
    background: rgba(255,255,255,0.4);
    transform: scale(1.05);
    color: white !important;
    border-color: rgba(255,255,255,0.6);
}

.category-btn-new i {
    font-size: 14px;
    transition: transform 0.3s ease;
}

.category-btn-new:hover i {
    transform: translateX(5px);
}

/* Layout cũ với gradient (cho fallback) */
.category-card {
    height: 280px;
    border-radius: 20px;
    overflow: hidden;
    position: relative;
    transition: all 0.3s ease;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

.category-card:hover {
    transform: translateY(-10px) scale(1.02);
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
}

.category-content {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 30px;
    color: white;
}

.category-content h4 {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 10px;
    color: white;
}

.category-content p {
    font-size: 16px;
    margin-bottom: 25px;
    opacity: 0.9;
    color: white;
}

.category-btn {
    background: rgba(255,255,255,0.2);
    color: white !important;
    padding: 12px 25px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s ease;
    border: 2px solid rgba(255,255,255,0.3);
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.category-btn:hover {
    background: rgba(255,255,255,0.3);
    transform: scale(1.05);
    color: white !important;
}

.category-btn i {
    font-size: 14px;
    transition: transform 0.3s ease;
}

.category-btn:hover i {
    transform: translateX(5px);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .category-card-with-image,
    .category-card {
        height: 240px;
    }
    
    .category-icon {
        font-size: 36px;
        margin-bottom: 15px;
    }
    
    .category-content-over-image h4,
    .category-content h4 {
        font-size: 20px;
    }
    
    .category-count,
    .category-content p {
        font-size: 14px;
        margin-bottom: 20px;
    }
    
    .category-btn-new,
    .category-btn {
        padding: 10px 20px;
        font-size: 14px;
    }
}

@media (max-width: 576px) {
    .categories {
        padding: 60px 0;
    }
    
    .category-card-with-image,
    .category-card {
        height: 200px;
    }
    
    .category-content-over-image,
    .category-content {
        padding: 20px;
    }
}
</style>