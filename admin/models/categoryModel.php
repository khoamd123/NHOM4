<?php
require_once __DIR__ . '/db.php';

// Lấy tất cả danh mục
function getAllCategories() {
    global $conn;
    try {
        $stmt = $conn->query("SELECT * FROM categories ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting categories: " . $e->getMessage());
        return [];
    }
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