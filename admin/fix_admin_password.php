<?php
require_once 'models/db.php';

// Mật khẩu hiện tại (plain text)
$currentPassword = '123';
$hashedPassword = password_hash($currentPassword, PASSWORD_DEFAULT);

try {
    // Cập nhật mật khẩu cho tài khoản admin@example.com
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = 'admin@example.com'");
    $result = $stmt->execute([$hashedPassword]);
    
    if ($result) {
        echo "<h2>✅ Sửa mật khẩu thành công!</h2>";
        echo "<p><strong>Tài khoản:</strong> admin@example.com</p>";
        echo "<p><strong>Mật khẩu:</strong> <code>$currentPassword</code></p>";
        echo "<p><strong>Trạng thái:</strong> Đã hash mật khẩu thành công</p>";
        
        // Kiểm tra lại
        $checkStmt = $conn->prepare("SELECT * FROM users WHERE email = 'admin@example.com'");
        $checkStmt->execute();
        $user = $checkStmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($currentPassword, $user['password'])) {
            echo "<p style='color: green;'>✅ Xác nhận: Mật khẩu đã được hash đúng cách!</p>";
        } else {
            echo "<p style='color: red;'>❌ Lỗi: Mật khẩu chưa được hash đúng!</p>";
        }
        
        echo "<p><a href='login.php'>→ Đăng nhập ngay</a></p>";
    } else {
        echo "<h2>❌ Lỗi khi cập nhật mật khẩu</h2>";
    }
} catch (PDOException $e) {
    echo "<h2>❌ Lỗi database: " . $e->getMessage() . "</h2>";
}
?>

<style>
body {
    font-family: Arial, sans-serif;
    max-width: 600px;
    margin: 50px auto;
    padding: 20px;
    background: #f5f5f5;
}
h2 {
    color: #333;
    border-bottom: 2px solid #007bff;
    padding-bottom: 10px;
}
code {
    background: #e9ecef;
    padding: 5px 10px;
    border-radius: 3px;
    font-weight: bold;
    color: #d63384;
}
a {
    color: #007bff;
    text-decoration: none;
    font-weight: bold;
}
a:hover {
    text-decoration: underline;
}
</style> 