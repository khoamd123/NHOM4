<?php
require_once __DIR__ . '/models/db.php';
require_once __DIR__ . '/models/productModel.php';

echo "<h2>Kiểm tra Database</h2>";

// Kiểm tra kết nối
try {
    echo "<p>✅ Kết nối database thành công</p>";
} catch (Exception $e) {
    echo "<p>❌ Lỗi kết nối: " . $e->getMessage() . "</p>";
    exit;
}

// Kiểm tra bảng products
try {
    $stmt = $conn->query("DESCRIBE products");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "<h3>Cấu trúc bảng products:</h3>";
    echo "<ul>";
    foreach ($columns as $column) {
        echo "<li>{$column['Field']} - {$column['Type']}</li>";
    }
    echo "</ul>";
} catch (Exception $e) {
    echo "<p>❌ Lỗi kiểm tra bảng products: " . $e->getMessage() . "</p>";
}

// Kiểm tra dữ liệu sản phẩm
try {
    $products = getAllProducts();
    echo "<h3>Số lượng sản phẩm: " . count($products) . "</h3>";
    if (count($products) > 0) {
        echo "<h4>Danh sách sản phẩm:</h4>";
        echo "<ul>";
        foreach ($products as $product) {
            echo "<li>ID: {$product['id']} - {$product['name']} - {$product['brand']}</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>⚠️ Không có sản phẩm nào trong database</p>";
    }
} catch (Exception $e) {
    echo "<p>❌ Lỗi lấy sản phẩm: " . $e->getMessage() . "</p>";
}

// Kiểm tra danh mục
try {
    $categories = getAllCategories();
    echo "<h3>Số lượng danh mục: " . count($categories) . "</h3>";
    if (count($categories) > 0) {
        echo "<h4>Danh sách danh mục:</h4>";
        echo "<ul>";
        foreach ($categories as $category) {
            echo "<li>ID: {$category['id']} - {$category['name']}</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>⚠️ Không có danh mục nào trong database</p>";
    }
} catch (Exception $e) {
    echo "<p>❌ Lỗi lấy danh mục: " . $e->getMessage() . "</p>";
}

// Test thêm sản phẩm
echo "<h3>Test thêm sản phẩm:</h3>";
try {
    $result = addProduct(
        'Test Product', 
        'test-product', 
        'Mô tả test', 
        1000000, 
        'Test Brand', 
        'men', 
        'test.jpg', 
        []
    );
    if ($result) {
        echo "<p>✅ Test thêm sản phẩm thành công (ID: $result)</p>";
        // Xóa sản phẩm test
        deleteProduct($result);
        echo "<p>✅ Đã xóa sản phẩm test</p>";
    } else {
        echo "<p>❌ Test thêm sản phẩm thất bại</p>";
    }
} catch (Exception $e) {
    echo "<p>❌ Lỗi test thêm sản phẩm: " . $e->getMessage() . "</p>";
}
?> 