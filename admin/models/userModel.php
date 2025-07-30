<?php
// Đảm bảo kết nối database được load
if (!isset($conn)) {
    require_once __DIR__ . '/db.php';
}

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
        $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone, role, status, created_at) VALUES (?, ?, ?, ?, ?, 1, NOW())");
        return $stmt->execute([$name, $email, $hash, $phone, $role]);
    } catch (PDOException $e) {
        error_log("Error adding user: " . $e->getMessage());
        return false;
    }
}

// Xóa tài khoản
function deleteUser($id) {
    global $conn;
    try {
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    } catch (PDOException $e) {
        error_log("Error deleting user: " . $e->getMessage());
        return false;
    }
}

// Toggle trạng thái tài khoản (bật/tắt)
function toggleUserStatus($id) {
    global $conn;
    try {
        // Lấy trạng thái hiện tại
        $stmt = $conn->prepare("SELECT status FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            // Đảo ngược trạng thái
            $newStatus = $user['status'] == 1 ? 0 : 1;
            $updateStmt = $conn->prepare("UPDATE users SET status = ? WHERE id = ?");
            return $updateStmt->execute([$newStatus, $id]);
        }
        return false;
    } catch (PDOException $e) {
        error_log("Error toggling user status: " . $e->getMessage());
        return false;
    }
}

// Lấy trạng thái tài khoản
function getUserStatus($id) {
    global $conn;
    try {
        $stmt = $conn->prepare("SELECT status FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ? $user['status'] : null;
    } catch (PDOException $e) {
        error_log("Error getting user status: " . $e->getMessage());
        return null;
    }
}
?>