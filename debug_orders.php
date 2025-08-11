<?php
// Debug page để test orders
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/OrderModel.php';

// Test dữ liệu
echo "<h2>Debug Orders</h2>";

// Lấy tất cả orders
try {
    $stmt = $conn->prepare("SELECT * FROM orders LIMIT 5");
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Orders found: " . count($orders) . "</h3>";
    
    foreach ($orders as $order) {
        echo "<div style='border: 1px solid #ccc; margin: 10px; padding: 10px;'>";
        echo "<strong>Order ID:</strong> " . $order['id'] . "<br>";
        echo "<strong>User ID:</strong> " . $order['user_id'] . "<br>";
        echo "<strong>Total:</strong> " . number_format($order['total_amount']) . "đ<br>";
        echo "<strong>Status:</strong> " . $order['status'] . "<br>";
        
        // Lấy items của order này
        $orderItems = OrderModel::getOrderItems($order['id']);
        echo "<strong>Items count:</strong> " . count($orderItems) . "<br>";
        
        if (!empty($orderItems)) {
            echo "<ul>";
            foreach ($orderItems as $item) {
                echo "<li>";
                echo $item['product_name'] . " - ";
                echo "Size: " . $item['size'] . " - ";
                echo "Color: " . $item['color'] . " - ";
                echo "Image: " . ($item['product_image'] ?? 'NO IMAGE');
                echo "</li>";
            }
            echo "</ul>";
        }
        
        echo "</div>";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

// Test sản phẩm
echo "<hr><h3>Products Test</h3>";
try {
    $stmt = $conn->prepare("SELECT * FROM products LIMIT 3");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($products as $product) {
        echo "<div style='border: 1px solid #ccc; margin: 10px; padding: 10px;'>";
        echo "<strong>Product:</strong> " . $product['name'] . "<br>";
        echo "<strong>Brand:</strong> " . ($product['brand'] ?? 'NO BRAND') . "<br>";
        echo "<strong>Image:</strong> " . ($product['image'] ?? 'NO IMAGE') . "<br>";
        if ($product['image']) {
            echo "<img src='" . $product['image'] . "' style='width: 100px; height: 100px; object-fit: cover;' onerror='this.style.display=\"none\"'><br>";
        }
        echo "</div>";
    }
} catch (Exception $e) {
    echo "Products Error: " . $e->getMessage();
}
?>
