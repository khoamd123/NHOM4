<?php
require_once 'db.php';

// Lấy tất cả danh mục
function getAllCategories() {
    global $conn;
    $stmt = $conn->query("SELECT * FROM categories");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Thêm danh mục mới
function addCategory($name, $slug) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
    return $stmt->execute([$name, $slug]);
}

// Xóa danh mục
function deleteCategory($id) {
    global $conn;
    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    return $stmt->execute([$id]);
}
?>