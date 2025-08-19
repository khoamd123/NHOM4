<?php
// Fix images in database
require_once __DIR__ . '/config/database.php';

echo "<h2>Fixing Product Images</h2>";

try {
    // Kiểm tra ảnh hiện tại
    $stmt = $conn->prepare("SELECT id, name, image FROM products");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Current Images:</h3>";
    foreach ($products as $product) {
        echo "ID: " . $product['id'] . " - " . $product['name'] . " - Image: " . ($product['image'] ?? 'NO IMAGE') . "<br>";
    }
    
    echo "<hr>";
    
    // Cập nhật ảnh với đường dẫn đúng
    $updates = [
        1 => '/NHOM4_DU_AN_1/public/uploads/products/1753957781_688b45956189f.jpg',
        2 => '/NHOM4_DU_AN_1/public/uploads/products/1754561725_68947cbd453a6.jpg', 
        3 => '/NHOM4_DU_AN_1/public/uploads/products/1754563000_689481b83ab00.jpg',
        4 => '/NHOM4_DU_AN_1/public/uploads/products/1754563143_689482479a486.jpg',
        5 => '/NHOM4_DU_AN_1/public/uploads/products/1754563216_6894829046019.jpg',
        6 => '/NHOM4_DU_AN_1/public/uploads/products/1754563292_689482dccef48.jpg'
    ];
    
    echo "<h3>Updating Images:</h3>";
    foreach ($updates as $productId => $imagePath) {
        $stmt = $conn->prepare("UPDATE products SET image = ? WHERE id = ?");
        $result = $stmt->execute([$imagePath, $productId]);
        
        if ($result) {
            echo "✅ Updated product ID $productId with image: $imagePath<br>";
        } else {
            echo "❌ Failed to update product ID $productId<br>";
        }
    }
    
    echo "<hr>";
    
    // Kiểm tra lại sau khi update
    $stmt = $conn->prepare("SELECT id, name, image FROM products");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>After Update:</h3>";
    foreach ($products as $product) {
        echo "ID: " . $product['id'] . " - " . $product['name'] . " - Image: " . ($product['image'] ?? 'NO IMAGE') . "<br>";
        
        // Test ảnh có tồn tại không
        if ($product['image']) {
            $fullPath = $_SERVER['DOCUMENT_ROOT'] . $product['image'];
            if (file_exists($fullPath)) {
                echo "  ✅ File exists<br>";
            } else {
                echo "  ❌ File NOT exists: $fullPath<br>";
            }
        }
    }
    
    echo "<h3>✅ Done! Refresh your orders page now.</h3>";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>

