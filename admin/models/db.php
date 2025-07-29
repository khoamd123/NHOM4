<?php
$host = 'localhost';
$db   = 'da1-nhom4-shopgiay';
$user = 'root';
$pass = ''; // Nếu dùng XAMPP mặc định thì để trống

try {
    $conn = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Kết nối thất bại: " . $e->getMessage());
}
?>