<?php
require_once __DIR__ . '/db.php';

// Lấy tất cả tài khoản
function getAllUsers() {
    global $conn;
    try {
        $stmt = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting users: " . $e->getMessage());
        return [];
    }
}

// Thêm tài khoản mới
function addUser($name, $email, $password, $phone, $role) {
    global $conn;
    try {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, role, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        return $stmt->execute([$name, $email, $hash, $phone, $role]);
    } catch (PDOException $e) {
        error_log("Error adding user: " . $e->getMessage());
        return false;
    }
}

// Xóa tài khoản
function deleteUser($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    return $stmt->execute([$id]);
}
?>