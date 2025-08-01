<?php
require_once 'models/db.php';

// Mật khẩu mới cho admin
$newPassword = 'admin123';
$hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

try {
    // Reset mật khẩu cho tất cả tài khoản admin (role = 1)
    $stmt = $conn->prepare("UPDATE users SET password = ? WHERE role = 1");
    $result = $stmt->execute([$hashedPassword]);
    
    if ($result) {
        echo "<h2>✅ Reset mật khẩu thành công!</h2>";
        echo "<p><strong>Mật khẩu mới cho tất cả admin:</strong> <code>$newPassword</code></p>";
        echo "<h3>Các tài khoản admin:</h3>";
        
        // Hiển thị danh sách admin
        $adminStmt = $conn->query("SELECT name, email FROM users WHERE role = 1");
        $admins = $adminStmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo "<ul>";
        foreach ($admins as $admin) {
            echo "<li><strong>{$admin['name']}</strong> - {$admin['email']}</li>";
        }
        echo "</ul>";
        
        echo "<p><a href='login.php'>→ Đăng nhập ngay</a></p>";
    } else {
        echo "<h2>❌ Lỗi khi reset mật khẩu</h2>";
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
ul {
    background: white;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
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