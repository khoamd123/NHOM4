<?php
require_once 'db.php';

// Lấy tất cả tài khoản
function getAllUsers() {
    global $conn;
    $stmt = $conn->query("SELECT * FROM users");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Thêm tài khoản mới
function addUser($name, $email, $password, $phone, $role) {
    global $conn;
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, role) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$name, $email, $hash, $phone, $role]);
}

// Xóa tài khoản
function deleteUser($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    return $stmt->execute([$id]);
}
?>