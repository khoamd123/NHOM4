<?php
require_once 'models/db.php';

// Thông tin admin tùy chỉnh
$adminData = [
    'name' => 'Super Admin',
    'email' => 'superadmin@example.com',
    'password' => '123456', // Bạn có thể thay đổi password này
    'phone' => '0987654321',
    'role' => 1
];

try {
    // Kiểm tra xem admin đã tồn tại chưa
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$adminData['email']]);
    
    if ($stmt->fetch()) {
        echo "<div style='padding: 20px; background: #f8d7da; color: #721c24; border-radius: 5px; margin: 20px;'>";
        echo "❌ Admin với email {$adminData['email']} đã tồn tại!";
        echo "</div>";
    } else {
        // Tạo admin mới
        $hash = password_hash($adminData['password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, role, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$adminData['name'], $adminData['email'], $hash, $adminData['phone'], $adminData['role']]);
        
        echo "<div style='padding: 20px; background: #d4edda; color: #155724; border-radius: 5px; margin: 20px;'>";
        echo "<h3>✅ Tạo admin thành công!</h3>";
        echo "<p><strong>Email:</strong> {$adminData['email']}</p>";
        echo "<p><strong>Password:</strong> {$adminData['password']}</p>";
        echo "<p><strong>Role:</strong> Admin</p>";
        echo "<br>";
        echo "<a href='login.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🔐 Đăng nhập ngay</a>";
        echo "</div>";
    }
} catch (PDOException $e) {
    echo "<div style='padding: 20px; background: #f8d7da; color: #721c24; border-radius: 5px; margin: 20px;'>";
    echo "❌ Lỗi: " . $e->getMessage();
    echo "</div>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tạo Admin</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 30px;
        }
        h1 {
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
        .warning {
            background: #fff3cd;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Tạo Tài Khoản Admin</h1>
        
        <div class="info">
            <h3>📋 Hướng dẫn:</h3>
            <ol>
                <li>Script này sẽ tạo tài khoản admin mặc định</li>
                <li>Sau khi tạo xong, bạn có thể đăng nhập vào admin panel</li>
                <li>Nếu muốn thay đổi thông tin, hãy sửa code trong file này</li>
            </ol>
        </div>
        
        <div class="warning">
            <h3>⚠️ Lưu ý bảo mật:</h3>
            <ul>
                <li>Đổi password ngay sau khi đăng nhập lần đầu</li>
                <li>Xóa file này sau khi tạo admin xong</li>
                <li>Không chia sẻ thông tin đăng nhập</li>
            </ul>
        </div>
        
        <p style="text-align: center;">
            <a href="create_admin.php" style="background: #28a745; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px; margin-right: 10px;">Tạo Admin Mặc Định</a>
            <a href="login.php" style="background: #007bff; color: white; padding: 12px 24px; text-decoration: none; border-radius: 5px;">Đăng Nhập</a>
        </p>
    </div>
</body>
</html> 