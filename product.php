<?php
// Kiểm tra nếu có tham số page=product
if (isset($_GET['page']) && $_GET['page'] === 'product') {
    // Load models
    require_once __DIR__ . '/models/ProductModel.php';
    
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
    
    // Include layout và nội dung
    include __DIR__ . '/views/layout/main.php';
    exit;
}

// Nếu không phải trang product, chuyển về trang chủ
header("Location: /NHOM4_DU_AN_1/index.php");
exit;
?> 