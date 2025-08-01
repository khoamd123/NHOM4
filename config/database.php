<?php
try {
    $conn = new PDO("mysql:host=localhost;dbname=da1-nhom4-shopgiay;charset=utf8", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Kết nối CSDL thất bại: " . $e->getMessage());
}