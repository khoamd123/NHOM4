<?php
// Cập nhật danh mục thành thương hiệu
require_once __DIR__ . '/models/db.php';

try {
    // Xóa danh mục cũ
    $conn->exec("DELETE FROM categories");
    
    // Thêm danh mục thương hiệu mới
    $brands = [
        ['name' => 'Nike', 'slug' => 'nike'],
        ['name' => 'Adidas', 'slug' => 'adidas'],
        ['name' => 'Puma', 'slug' => 'puma'],
        ['name' => 'Converse', 'slug' => 'converse'],
        ['name' => 'Vans', 'slug' => 'vans'],
        ['name' => 'New Balance', 'slug' => 'new-balance'],
        ['name' => 'Reebok', 'slug' => 'reebok'],
        ['name' => 'Under Armour', 'slug' => 'under-armour']
    ];
    
    $stmt = $conn->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
    
    foreach ($brands as $brand) {
        $stmt->execute([$brand['name'], $brand['slug']]);
    }
    
    echo "✅ Đã cập nhật danh mục thành công!<br>";
    echo "Đã thêm " . count($brands) . " thương hiệu:<br>";
    
    foreach ($brands as $brand) {
        echo "- " . $brand['name'] . "<br>";
    }
    
    // Kiểm tra lại
    $stmt = $conn->query("SELECT * FROM categories ORDER BY id");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Danh sách danh mục hiện tại:</h3>";
    foreach ($categories as $category) {
        echo "ID: " . $category['id'] . " - " . $category['name'] . " (" . $category['slug'] . ")<br>";
    }
    
} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage();
}
?> 