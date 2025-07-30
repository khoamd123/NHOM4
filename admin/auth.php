<?php
session_start();

// Load database connection
require_once __DIR__ . '/models/db.php';

// Kiểm tra đăng nhập admin
function checkAdminAuth() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header("Location: login.php");
        exit;
    }
    
    // Kiểm tra trạng thái tài khoản từ database
    if (isset($_SESSION['admin_id'])) {
        require_once __DIR__ . '/models/userModel.php';
        $status = getUserStatus($_SESSION['admin_id']);
        if ($status === 0) {
            // Tài khoản bị khóa, đăng xuất
            adminLogout();
        }
    }
}

// Kiểm tra role admin
function checkAdminRole() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header("Location: login.php");
        exit;
    }
    
    // Có thể thêm kiểm tra role từ database nếu cần
    // Hiện tại chỉ cần đăng nhập là được
}

// Lấy thông tin admin hiện tại
function getCurrentAdmin() {
    if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
        return [
            'id' => $_SESSION['admin_id'],
            'name' => $_SESSION['admin_name'],
            'email' => $_SESSION['admin_email']
        ];
    }
    return null;
}

// Đăng xuất
function adminLogout() {
    session_destroy();
    header("Location: login.php");
    exit;
}
?> 