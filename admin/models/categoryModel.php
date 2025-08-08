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

// Lấy danh mục theo ID
function getCategoryById($id) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT * FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Error getting category by ID: " . $e->getMessage());
        return null;
    }
}

// Cập nhật danh mục
function updateCategory($id, $name, $slug) {
    global $conn;
    try {
        $stmt = $conn->prepare("UPDATE categories SET name = ?, slug = ? WHERE id = ?");
        return $stmt->execute([$name, $slug, $id]);
    } catch (PDOException $e) {
        error_log("Error updating category: " . $e->getMessage());
        return false;
    }
}

// Xóa danh mục
function deleteCategory($id) {
    global $conn;
    try {
        // Kiểm tra xem danh mục có tồn tại không
        $stmt = $conn->prepare("SELECT COUNT(*) FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        if ($stmt->fetchColumn() == 0) {
            error_log("Category with ID $id does not exist");
            return false;
        }
        
        // Kiểm tra xem có sản phẩm nào liên kết không
        $stmt = $conn->prepare("SELECT COUNT(*) FROM product_categories WHERE category_id = ?");
        $stmt->execute([$id]);
        $productCount = $stmt->fetchColumn();
        
        if ($productCount > 0) {
            error_log("Cannot delete category $id: linked to $productCount products");
            return false;
        }
        
        $conn->beginTransaction();
        
        // Xóa liên kết trong product_categories trước (để đảm bảo)
        $stmt = $conn->prepare("DELETE FROM product_categories WHERE category_id = ?");
        $stmt->execute([$id]);
        
        // Sau đó xóa danh mục
        $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
        $stmt->execute([$id]);
        
        if ($stmt->rowCount() == 0) {
            throw new Exception("No category was deleted");
        }
        
        $conn->commit();
        return true;
    } catch (PDOException $e) {
        $conn->rollback();
        error_log("Database error deleting category: " . $e->getMessage());
        return false;
    } catch (Exception $e) {
        $conn->rollback();
        error_log("Error deleting category: " . $e->getMessage());
        return false;
    }
}
?>