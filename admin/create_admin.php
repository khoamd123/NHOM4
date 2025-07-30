<?php
require_once 'models/db.php';

// Tạo admin mặc định
$adminData = [
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => 'admin123',
    'phone' => '0123456789',
    'role' => 1
];

try {
    // Kiểm tra xem admin đã tồn tại chưa
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$adminData['email']]);
    
    if ($stmt->fetch()) {
        echo "Admin đã tồn tại!";
    } else {
        // Tạo admin mới
        $hash = password_hash($adminData['password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$adminData['name'], $adminData['email'], $hash, $adminData['phone'], $adminData['role']]);
        
        echo "✅ Tạo admin thành công!<br>";
        echo "Email: " . $adminData['email'] . "<br>";
        echo "Password: " . $adminData['password'] . "<br>";
        echo "<a href='login.php'>Đăng nhập ngay</a>";
    }
} catch (PDOException $e) {
    echo "❌ Lỗi: " . $e->getMessage();
}
?> 