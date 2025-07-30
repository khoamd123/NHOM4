<?php
session_start();

// Kiểm tra đăng nhập admin
function checkAdminAuth() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header("Location: login.php");
        exit;
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