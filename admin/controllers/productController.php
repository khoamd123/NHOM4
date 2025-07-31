<?php
require_once __DIR__ . '/../auth.php';
checkAdminAuth();

// Đảm bảo kết nối database được load
require_once __DIR__ . '/../models/db.php';
require_once __DIR__ . '/../models/productModel.php';

// Xử lý thêm sản phẩm
if (isset($_POST['add_product'])) {
    try {
        $name = trim($_POST['name']);
        $description = trim($_POST['description']);
        $price = floatval($_POST['price']);
        $brand = trim($_POST['brand']);
        $gender = $_POST['gender'];
        $image = null;
        $categories = isset($_POST['categories']) ? $_POST['categories'] : [];
        
        // Xử lý upload ảnh
        if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] == 0) {
            $uploadDir = __DIR__ . '/../../uploads/products/';
            $fileExtension = strtolower(pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION));
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            // Debug upload
            error_log("Upload debug - File name: " . $_FILES['image_file']['name']);
            error_log("Upload debug - File size: " . $_FILES['image_file']['size']);
            error_log("Upload debug - File error: " . $_FILES['image_file']['error']);
            error_log("Upload debug - Upload dir: " . $uploadDir);
            
            if (in_array($fileExtension, $allowedExtensions)) {
                $fileName = time() . '_' . uniqid() . '.' . $fileExtension;
                $uploadPath = $uploadDir . $fileName;
                
                error_log("Upload debug - Upload path: " . $uploadPath);
                
                if (move_uploaded_file($_FILES['image_file']['tmp_name'], $uploadPath)) {
                    $image = '/NHOM4_DU_AN_1/uploads/products/' . $fileName;
                    error_log("Upload debug - Success: " . $image);
                } else {
                    error_log("Upload debug - Failed to move uploaded file");
                    error_log("Upload debug - tmp_name: " . $_FILES['image_file']['tmp_name']);
                }
            } else {
                error_log("Upload debug - Invalid file extension: " . $fileExtension);
            }
        } elseif (isset($_POST['image_url']) && !empty($_POST['image_url'])) {
            $image = trim($_POST['image_url']);
            error_log("Upload debug - Using image URL: " . $image);
        } else {
            error_log("Upload debug - No image file or URL provided");
        }
        
        // Tạo slug từ tên sản phẩm
        $slug = createSlug($name);
        
        // Kiểm tra slug đã tồn tại chưa
        if (checkSlugExists($slug)) {
            $slug = $slug . '-' . time();
        }
        
        $result = addProduct($name, $slug, $description, $price, $brand, $gender, $image, $categories);
        if ($result) {
            header("Location: /NHOM4_DU_AN_1/admin/index.php?action=products&success=1");
        } else {
            header("Location: /NHOM4_DU_AN_1/admin/index.php?action=products&error=1");
        }
    } catch (Exception $e) {
        // Log lỗi để debug
        error_log("Lỗi thêm sản phẩm: " . $e->getMessage());
        header("Location: /NHOM4_DU_AN_1/admin/index.php?action=products&error=1&debug=" . urlencode($e->getMessage()));
    }
    exit;
}

// Xử lý cập nhật sản phẩm
if (isset($_POST['update_product'])) {
    $id = intval($_POST['id']);
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $price = floatval($_POST['price']);
    $brand = trim($_POST['brand']);
    $gender = $_POST['gender'];
    $image = isset($_POST['image']) ? trim($_POST['image']) : null;
    $categories = isset($_POST['categories']) ? $_POST['categories'] : [];
    
    // Tạo slug từ tên sản phẩm
    $slug = createSlug($name);
    
    // Kiểm tra slug đã tồn tại chưa (trừ sản phẩm hiện tại)
    if (checkSlugExists($slug, $id)) {
        $slug = $slug . '-' . time();
    }
    
    if (updateProduct($id, $name, $slug, $description, $price, $brand, $gender, $image, $categories)) {
        header("Location: /NHOM4_DU_AN_1/admin/index.php?action=products&success=2");
    } else {
        header("Location: /NHOM4_DU_AN_1/admin/index.php?action=products&error=2");
    }
    exit;
}

// Xử lý xóa sản phẩm
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if (deleteProduct($id)) {
        header("Location: /NHOM4_DU_AN_1/admin/index.php?action=products&success=3");
    } else {
        header("Location: /NHOM4_DU_AN_1/admin/index.php?action=products&error=3");
    }
    exit;
}

// Xử lý thêm biến thể sản phẩm
if (isset($_POST['add_variant'])) {
    $productId = intval($_POST['product_id']);
    $size = trim($_POST['size']);
    $color = trim($_POST['color']);
    $stock = intval($_POST['stock']);
    $price = floatval($_POST['price']);
    
    if (addProductVariant($productId, $size, $color, $stock, $price)) {
        header("Location: /NHOM4_DU_AN_1/admin/index.php?action=products&edit=" . $productId . "&success=4");
    } else {
        header("Location: /NHOM4_DU_AN_1/admin/index.php?action=products&edit=" . $productId . "&error=4");
    }
    exit;
}

// Xử lý cập nhật biến thể sản phẩm
if (isset($_POST['update_variant'])) {
    $id = intval($_POST['variant_id']);
    $productId = intval($_POST['product_id']);
    $size = trim($_POST['size']);
    $color = trim($_POST['color']);
    $stock = intval($_POST['stock']);
    $price = floatval($_POST['price']);
    
    if (updateProductVariant($id, $size, $color, $stock, $price)) {
        header("Location: /NHOM4_DU_AN_1/admin/index.php?action=products&edit=" . $productId . "&success=5");
    } else {
        header("Location: /NHOM4_DU_AN_1/admin/index.php?action=products&edit=" . $productId . "&error=5");
    }
    exit;
}

// Xử lý xóa biến thể sản phẩm
if (isset($_GET['delete_variant'])) {
    $id = intval($_GET['delete_variant']);
    $productId = intval($_GET['product_id']);
    
    if (deleteProductVariant($id)) {
        header("Location: /NHOM4_DU_AN_1/admin/index.php?action=products&edit=" . $productId . "&success=6");
    } else {
        header("Location: /NHOM4_DU_AN_1/admin/index.php?action=products&edit=" . $productId . "&error=6");
    }
    exit;
}

// Lấy dữ liệu cho trang
$products = getAllProducts();
$categories = getAllCategories();

// Nếu có action edit, lấy thông tin sản phẩm
$editProduct = null;
$productVariants = [];
if (isset($_GET['edit'])) {
    $editProduct = getProductById($_GET['edit']);
    if ($editProduct) {
        $productVariants = getProductVariants($_GET['edit']);
    }
}

include __DIR__ . '/../views/products/list.php';
?> 