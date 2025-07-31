<?php
require_once __DIR__ . '/db.php';

// Lấy tất cả sản phẩm
function getAllProducts() {
    global $conn;
    $stmt = $conn->prepare("
        SELECT p.*, GROUP_CONCAT(c.name) as category_names
        FROM products p
        LEFT JOIN product_categories pc ON p.id = pc.product_id
        LEFT JOIN categories c ON pc.category_id = c.id
        GROUP BY p.id
        ORDER BY p.created_at DESC
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Lấy sản phẩm theo ID
function getProductById($id) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Lấy tất cả danh mục
function getAllCategories() {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM categories ORDER BY name");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Lấy danh mục của sản phẩm
function getProductCategories($productId) {
    global $conn;
    $stmt = $conn->prepare("
        SELECT c.* FROM categories c
        JOIN product_categories pc ON c.id = pc.category_id
        WHERE pc.product_id = ?
    ");
    $stmt->execute([$productId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Lấy biến thể của sản phẩm
function getProductVariants($productId) {
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM product_variants WHERE product_id = ? ORDER BY size, color");
    $stmt->execute([$productId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Thêm sản phẩm mới
function addProduct($name, $slug, $description, $price, $brand, $gender, $image = null, $categories = []) {
    global $conn;
    try {
        $conn->beginTransaction();
        
        // Thêm sản phẩm
        $stmt = $conn->prepare("
            INSERT INTO products (name, slug, description, price, brand, gender, image) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$name, $slug, $description, $price, $brand, $gender, $image]);
        $productId = $conn->lastInsertId();
        
        // Thêm danh mục cho sản phẩm
        if (!empty($categories)) {
            $stmt = $conn->prepare("INSERT INTO product_categories (product_id, category_id) VALUES (?, ?)");
            foreach ($categories as $categoryId) {
                $stmt->execute([$productId, $categoryId]);
            }
        }
        
        $conn->commit();
        return $productId;
    } catch (Exception $e) {
        $conn->rollback();
        return false;
    }
}

// Cập nhật sản phẩm
function updateProduct($id, $name, $slug, $description, $price, $brand, $gender, $image = null, $categories = []) {
    global $conn;
    try {
        $conn->beginTransaction();
        
        // Cập nhật sản phẩm
        $stmt = $conn->prepare("
            UPDATE products 
            SET name = ?, slug = ?, description = ?, price = ?, brand = ?, gender = ?, image = ?
            WHERE id = ?
        ");
        $stmt->execute([$name, $slug, $description, $price, $brand, $gender, $image, $id]);
        
        // Xóa danh mục cũ
        $stmt = $conn->prepare("DELETE FROM product_categories WHERE product_id = ?");
        $stmt->execute([$id]);
        
        // Thêm danh mục mới
        if (!empty($categories)) {
            $stmt = $conn->prepare("INSERT INTO product_categories (product_id, category_id) VALUES (?, ?)");
            foreach ($categories as $categoryId) {
                $stmt->execute([$id, $categoryId]);
            }
        }
        
        $conn->commit();
        return true;
    } catch (Exception $e) {
        $conn->rollback();
        return false;
    }
}

// Xóa sản phẩm
function deleteProduct($id) {
    global $conn;
    try {
        $conn->beginTransaction();
        
        // Xóa danh mục sản phẩm
        $stmt = $conn->prepare("DELETE FROM product_categories WHERE product_id = ?");
        $stmt->execute([$id]);
        
        // Xóa biến thể sản phẩm
        $stmt = $conn->prepare("DELETE FROM product_variants WHERE product_id = ?");
        $stmt->execute([$id]);
        
        // Xóa sản phẩm
        $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
        $stmt->execute([$id]);
        
        $conn->commit();
        return true;
    } catch (Exception $e) {
        $conn->rollback();
        return false;
    }
}

// Thêm biến thể sản phẩm
function addProductVariant($productId, $size, $color, $stock, $price) {
    global $conn;
    $stmt = $conn->prepare("
        INSERT INTO product_variants (product_id, size, color, stock, price) 
        VALUES (?, ?, ?, ?, ?)
    ");
    return $stmt->execute([$productId, $size, $color, $stock, $price]);
}

// Cập nhật biến thể sản phẩm
function updateProductVariant($id, $size, $color, $stock, $price) {
    global $conn;
    $stmt = $conn->prepare("
        UPDATE product_variants 
        SET size = ?, color = ?, stock = ?, price = ?
        WHERE id = ?
    ");
    return $stmt->execute([$size, $color, $stock, $price, $id]);
}

// Xóa biến thể sản phẩm
function deleteProductVariant($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM product_variants WHERE id = ?");
    return $stmt->execute([$id]);
}

// Kiểm tra slug đã tồn tại chưa
function checkSlugExists($slug, $excludeId = null) {
    global $conn;
    if ($excludeId) {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM products WHERE slug = ? AND id != ?");
        $stmt->execute([$slug, $excludeId]);
    } else {
        $stmt = $conn->prepare("SELECT COUNT(*) FROM products WHERE slug = ?");
        $stmt->execute([$slug]);
    }
    return $stmt->fetchColumn() > 0;
}

// Tạo slug từ tên sản phẩm
function createSlug($name) {
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name)));
    return $slug;
}
?> 