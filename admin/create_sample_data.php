<?php
require_once 'models/db.php';

echo "<h2>🔧 Tạo dữ liệu mẫu cho thống kê</h2>";

try {
    // Thêm một số sản phẩm mẫu
    $products = [
        ['name' => 'Nike Air Max', 'slug' => 'nike-air-max', 'description' => 'Giày thể thao Nike', 'price' => 120.00, 'brand' => 'Nike', 'gender' => 'unisex'],
        ['name' => 'Adidas Ultraboost', 'slug' => 'adidas-ultraboost', 'description' => 'Giày chạy Adidas', 'price' => 180.00, 'brand' => 'Adidas', 'gender' => 'unisex'],
        ['name' => 'Converse Chuck Taylor', 'slug' => 'converse-chuck', 'description' => 'Giày canvas Converse', 'price' => 65.00, 'brand' => 'Converse', 'gender' => 'unisex'],
        ['name' => 'Vans Old Skool', 'slug' => 'vans-old-skool', 'description' => 'Giày skate Vans', 'price' => 55.00, 'brand' => 'Vans', 'gender' => 'unisex'],
        ['name' => 'Puma RS-X', 'slug' => 'puma-rsx', 'description' => 'Giày retro Puma', 'price' => 95.00, 'brand' => 'Puma', 'gender' => 'unisex']
    ];
    
    $stmt = $conn->prepare("INSERT INTO products (name, slug, description, price, brand, gender, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    
    foreach ($products as $product) {
        $stmt->execute([$product['name'], $product['slug'], $product['description'], $product['price'], $product['brand'], $product['gender']]);
    }
    
    echo "✅ Đã thêm " . count($products) . " sản phẩm mẫu<br>";
    
    // Thêm một số đơn hàng mẫu
    $orders = [
        ['user_id' => 1, 'total_amount' => 120.00, 'status' => 'delivered', 'created_at' => date('Y-m-d H:i:s', strtotime('-2 days'))],
        ['user_id' => 1, 'total_amount' => 180.00, 'status' => 'shipped', 'created_at' => date('Y-m-d H:i:s', strtotime('-1 day'))],
        ['user_id' => 1, 'total_amount' => 65.00, 'status' => 'pending', 'created_at' => date('Y-m-d H:i:s', strtotime('-6 hours'))],
        ['user_id' => 1, 'total_amount' => 95.00, 'status' => 'processing', 'created_at' => date('Y-m-d H:i:s', strtotime('-3 hours'))],
        ['user_id' => 1, 'total_amount' => 240.00, 'status' => 'pending', 'created_at' => date('Y-m-d H:i:s', strtotime('-1 hour'))]
    ];
    
    $stmt = $conn->prepare("INSERT INTO orders (user_id, total_amount, status, created_at) VALUES (?, ?, ?, ?)");
    
    foreach ($orders as $order) {
        $stmt->execute([$order['user_id'], $order['total_amount'], $order['status'], $order['created_at']]);
    }
    
    echo "✅ Đã thêm " . count($orders) . " đơn hàng mẫu<br>";
    
    // Thêm một số user mẫu
    $users = [
        ['name' => 'John Doe', 'email' => 'john@example.com', 'password' => 'password123', 'phone' => '0123456789', 'role' => 0],
        ['name' => 'Jane Smith', 'email' => 'jane@example.com', 'password' => 'password123', 'phone' => '0987654321', 'role' => 0],
        ['name' => 'Bob Johnson', 'email' => 'bob@example.com', 'password' => 'password123', 'phone' => '0555666777', 'role' => 0],
        ['name' => 'Alice Brown', 'email' => 'alice@example.com', 'password' => 'password123', 'phone' => '0111222333', 'role' => 0]
    ];
    
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, role, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    
    foreach ($users as $user) {
        $hash = password_hash($user['password'], PASSWORD_DEFAULT);
        $stmt->execute([$user['name'], $user['email'], $hash, $user['phone'], $user['role']]);
    }
    
    echo "✅ Đã thêm " . count($users) . " user mẫu<br>";
    
    // Thêm một số danh mục mẫu
    $categories = [
        ['name' => 'Giày thể thao', 'slug' => 'giay-the-thao'],
        ['name' => 'Giày chạy', 'slug' => 'giay-chay'],
        ['name' => 'Giày casual', 'slug' => 'giay-casual'],
        ['name' => 'Giày bóng đá', 'slug' => 'giay-bong-da']
    ];
    
    $stmt = $conn->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
    
    foreach ($categories as $category) {
        $stmt->execute([$category['name'], $category['slug']]);
    }
    
    echo "✅ Đã thêm " . count($categories) . " danh mục mẫu<br>";
    
    echo "<br><strong>🎉 Hoàn thành tạo dữ liệu mẫu!</strong><br>";
    echo "<a href='index.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; margin-top: 20px; display: inline-block;'>Xem Dashboard</a>";
    
} catch (PDOException $e) {
    echo "❌ Lỗi: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tạo dữ liệu mẫu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
        }
        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 30px;
        }
        .info {
            background: #e7f3ff;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="info">
            <h3>📋 Dữ liệu mẫu sẽ được tạo:</h3>
            <ul>
                <li>5 sản phẩm giày các loại</li>
                <li>5 đơn hàng với trạng thái khác nhau</li>
                <li>4 user thường</li>
                <li>4 danh mục sản phẩm</li>
            </ul>
        </div>
    </div>
</body>
</html> 